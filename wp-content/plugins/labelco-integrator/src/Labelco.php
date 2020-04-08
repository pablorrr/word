<?php
namespace Labelco;
/*
public function labelcoGetProdMeta (linia 95 i 594)- njprwd nie ma racji bytu - korzytsa ze starych rozwiazan - kreowanie porduktu  z najnowszych zamowien
od teraz w chwili pierwszej implementacji calcculatora- generatora na produkcji nie ma byc zadnych "starych produkt?w " WC , beda sie pojawiac dopiro po dodaniu 
do koszyka (przycisk przy kalkulatorze- ktory zawiera sie poprzez shortcode, display narazie na stronie g?ownej)
*/
use Exception;
use Labelco\DB;
use Labelco\DB as DBAlias;
use Labelco\SizesService as SizesService;
use Labelco\FinalPriceService as FinalPriceService;
use Labelco\QuantitiesService as QuantitiesService;
use Labelco\MaterialService as MaterialService;
use Labelco\Ajax as AjaxService;
use Labelco\CartService as CheckoutProcessService;
use Labelco\FinalPriceValidator as FinalPriceValidator;
use Labelco\SizesValidator as SizesValidator;
use mysql_xdevapi\Result;

final class Labelco
{

    private static $instance;

    // to create custom Labelco plugin WP Settings API
    private $LabelcoOptions;

    /**
     *
     * @var DBAlias
     */
    private $db;

    /**
     *
     * @var AjaxService
     */
    private $ajax;

    /**
     *
     * @var FinalPriceValidator
     */
    private $finalPriceValidator;

    /**
     *
     * @var CartService
     */
    private $cartService;

    private function __construct()
    {}

    private function __clone()
    {}

    /**
     *
     * @return int|null
     */
    private static function resolveCurrentProductId()
    {
        if (false === self::isAjax()) {
            if (true === is_product()) {
                global $post;
                return $post->ID;
            }
        }

        return null;
    }

    public function wpLoaded()
    {
      //  add_action('woocommerce_before_add_to_cart_button', [
       
       add_shortcode('flexolabels_calc',[
            $this,
            'includeCalculatorTemplate'
        ], 5);
       
      /* add_action('woocommerce_before_shop_loop', [$this,
           'labecloCalcDoShortcode'
       ], 5);*/
       
        add_action('wp_enqueue_scripts', [
            $this,
            'includeScripts'
        ]);
        add_action('woocommerce_after_single_product_summary', [
            $this,
            'labelcoGetProdMeta'
        ]);
        // Remove product data tabs if unnecessary
        add_filter('woocommerce_product_tabs', [
            $this,
            'labelco_remove_product_tabs'
        ], 98);
    }

    public function labelco_remove_product_tabs($tabs)
    {

        // remove prod tabs only if prod has term Produkt generowany
        if (has_term('Produkt generowany', 'product_cat')) {
            unset($tabs['additional_information']); // Remove the additional information tab
            unset($tabs['description']);
        }
        return $tabs;
    }
    
    
 /*   public function labecloCalcDoShortcode(){
       
        echo do_shortcode( '[flexolabels_calc]' );
        }*/

    public function woocommerceInit()
    {
        $this->cartService = CartService::getInstance();
    }

    /**
     *
     * @param
     *            $cart_item_data
     * @param
     *            $currentProductId
     * @return mixed
     * @throws \Exception
     */
    public function addToCartProcess($cart_item_data, $currentProductId)
    {
        $unique_cart_item_key = md5(microtime() . rand());
        $cart_item_data['key'] = $unique_cart_item_key;

        $finalPrice = $this->calculateFinalPrice($currentProductId);

        if (null !== $finalPrice) {
            $this->injectPriceToCart($finalPrice, $currentProductId);
            $this->getCartService()->setCustomParameters($currentProductId, [
                'size_height' => $finalPrice->getHeight(),
                'size_width' => $finalPrice->getWidth(),
                'quantity' => $finalPrice->getQuantity()
            ]);
        }
        return $cart_item_data;
    }

    /**
     *
     * @param FinalPriceModel $finalPrice
     * @param
     *            $currentProductId
     */
    private function injectPriceToCart(FinalPriceModel $finalPrice, $currentProductId)
    {
        $this->getCartService()->setCustomPrice($currentProductId, $finalPrice->getPriceTotal());
    }

    /**
     *
     * @param
     *            $currentProductId
     * @return FinalPriceModel|null
     * @throws \Exception
     */
    private function calculateFinalPrice($currentProductId)
    {
        $finalPriceSrv = $this->getFinalPriceService();
        $materialSrv = $this->getMaterialService();
        $size = $_POST['labelco_size_select'];
        $post = get_post($currentProductId);
        $materialId = $materialSrv->getMaterialIdByWooProductSlug($post->post_name);
        $quantities = $_POST['labelco_quantities_select'];
        $validator = $this->getFinalPriceValidator();
        $validator->validate($materialId, $size, $quantities);

        return $finalPriceSrv->getSummary((int) $materialId, (int) $size, (int) $quantities);
    }

    public function includeScripts()
    {
        wp_enqueue_style('labelco-css', $this->getCssUri() . '/labelco.css');
        wp_enqueue_script('labelco', $this->getJsUri() . '/labelco.js', array(
            'jquery'
        ));

        wp_localize_script('labelco', 'labelcoVariables', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'productId' => self::resolveCurrentProductId()
        ));
    }

    // Labelco Admin Interface Section//

    // Labelco add menu page
    public function LabelcoAddPluginPage()
    {
        add_menu_page('Labelco Settings', // page_title
        'Labelco Settings', // menu_title
        'manage_options', // capability
        'labelco-plug', // menu_slug
        [
            $this,
            'LabelcoCreateAdminPage'
        ], // function
        'dashicons-admin-generic', // icon_url
        3 // position
        );
    }

    // Labelco create Admin Page
    public function LabelcoCreateAdminPage()
    {
        $this->LabelcoOptions = get_option('LabelcoOptionName');
        ?>

<div class="wrap">
	<h2>Fixolabels Settings Panel</h2>

            <?php settings_errors('host_name_port_no');?>
            <?php settings_errors('db_name');?>
            <?php settings_errors('user_login');?>
            <?php settings_errors('user_pass');?>
            <?php settings_errors('charset_code');?>

			<form method="post" action="options.php">
				<?php
        settings_fields('LabelcoOptionGroup');
        do_settings_sections('LabelcoAdmin');
        submit_button();
        ?>
			</form>
</div>

<?php

}

    public function LabelcoPageInit()
    {
        register_setting('LabelcoOptionGroup', // option_group
        'LabelcoOptionName', // option_name
        [
            $this,
            'LabelcoSanitize'
        ] // sanitize_callback
        );

        add_settings_section('LabelcoSettingSection', // section to group options
        'Settings', // title
                     // TODO: sprawdz , zastap i uzyj __return_flase
            [
                $this,
                'LabelcoSectionInfo'
            ], // callback
            'LabelcoAdmin' // page
        );

        add_settings_field('host_name_port_no', // id
        __('Server address and port number', 'fixolabels'), // title
        [
            $this,
            'host_name_port_no_callback'
        ], // callback
        'LabelcoAdmin', // page
        'LabelcoSettingSection' // section
        );

        add_settings_field('db_name', // id
        __('Database name', 'fixolabels'), [
            $this,
            'db_name_callback'
        ], // callback
        'LabelcoAdmin', // page
        'LabelcoSettingSection' // section
        );

        add_settings_field('user_login', // id
        __('User login', 'fixolabels'), [
            $this,
            'user_login_callback'
        ], // callback
        'LabelcoAdmin', // page
        'LabelcoSettingSection' // section
        );

        // sprawdz dokaldnia co wyrzuca i konwertuje sanitaze_text_field
        add_settings_field('user_pass', // id
        __('User password', 'fixolabels'), [
            $this,
            'user_pass_callback'
        ], // callback
        'LabelcoAdmin', // page
        'LabelcoSettingSection' // section
        );

        add_settings_field('charset_code', // id
        __('Charset code', 'fixolabels'), [
            $this,
            'charset_code_callback'
        ], // callback
        'LabelcoAdmin', // page
        'LabelcoSettingSection' // section
        );
    }

    public function LabelcoSanitize($input)
    {
        $sanitary_values = array();
        /*
         * Host name and port sanitaze
         */
        $typeHostNo = null;
        $messageHostNo = null;

        if (isset($input['host_name_port_no']) && preg_match('/[0-9.:]/', $input['host_name_port_no'])) {

            $sanitary_values['host_name_port_no'] = sanitize_text_field($input['host_name_port_no']);
            $typeHostNo = 'updated';
            $messageHostNo = __('Host name and port number field saved correctly', 'fixolabels');
        } else {
            $typeHostNo = 'error';
            $messageHostNo = __('Host name and port number field can not be empty or wrong format', 'fixolabels');
        }
        add_settings_error('host_name_port_no', esc_attr('settings_updated'), $messageHostNo, $typeHostNo);

        /*
         * DB name sanitaze
         */

        $typeDB = null;
        $messageDB = null;

        if (isset($input['db_name']) && preg_match('/^[a-zA-Z0-9._-]/', $input['db_name'])) {
            $sanitary_values['db_name'] = sanitize_text_field($input['db_name']);
            $typeDB = 'updated';
            $messageDB = __('Database name field saved correctly', 'fixolabels');
        } 
        else {
            $typeDB = 'error';
            $messageDB = __('Database name field can not be empty or wrong format', 'fixolabels');
        }

        add_settings_error('db_name', esc_attr('settings_updated'), $messageDB, $typeDB);

        /*
         * user login sanitaze
         */

        $typeUserLogin = null;
        $messageUserLogin = null;

        if (isset($input['user_login']) && preg_match('/^[a-zA-Z0-9._-]/', $input['user_login'])) {
            $sanitary_values['user_login'] = sanitize_text_field($input['user_login']);
            $typeUserLogin = 'updated';
            $messageUserLogin = __('User Login field saved correctly', 'fixolabels');
        } else {
            $typeUserLogin = 'error';
            $messageUserLogin = __('User Login field can not be empty or wrong format', 'fixolabels');
        }
        add_settings_error('user_login', esc_attr('settings_updated'), $messageUserLogin, $typeUserLogin);

        /*
         * User password sanitaze
         */

        $typeUserPass = null;
        $messageUserPass = null;

        if (isset($input['user_pass']) && ! empty($input['user_pass'])) {
            $sanitary_values['user_pass'] = sanitize_text_field($input['user_pass']);
            $typeUserPass = 'updated';
            $messageUserPass = __('User password field saved correctly', 'fixolabels');
        } 
        else {
            $typeUserPass = 'error';
            $messageUserPass = __('User password field can not be empty or wrong format', 'fixolabels');
        }
        add_settings_error('user_pass', esc_attr('settings_updated'), $messageUserPass, $typeUserPass);

        /*
         * User charset sanitaze
         */

        $typeCharset = null;
        $messageCharset = null;

        if (isset($input['charset_code']) && preg_match('/^[a-zA-Z0-9.-]/', $input['charset_code'])) {
            $sanitary_values['charset_code'] = sanitize_text_field($input['charset_code']);
            $typeCharset = 'updated';
            $messageCharset = __('Charset field saved correctly', 'fixolabels');
        } else {
            $typeCharset = 'error';
            $messageCharset = __('Charset name field can not be empty or wrong format', 'fixolabels');
        }

        add_settings_error('charset_code', esc_attr('settings_updated'), $messageCharset, $typeCharset);

        return $sanitary_values;
    }

    // TODO: __return false implementation
    public function LabelcoSectionInfo()
    {
        // echo "testing message";
    }

    // Labelco Options Callbacks Section
    public function host_name_port_no_callback()
    {
        printf('<input class="regular-text" type="text" name="LabelcoOptionName[host_name_port_no]" 
                           id="host_name_port_no" value="%s"  placeholder="%s" require>', isset($this->LabelcoOptions['host_name_port_no']) ? esc_attr($this->LabelcoOptions['host_name_port_no']) : '', __('like e.g 99.157.14.142:1314', 'fixolabels'));
    }

    public function db_name_callback()
    {
        printf('<input class="regular-text" type="text" name="LabelcoOptionName[db_name]" 
                            id="db_name" value="%s" placeholder="%s" require>', isset($this->LabelcoOptions['db_name']) ? esc_attr($this->LabelcoOptions['db_name']) : '', __('like e.g dbname', 'fixolabels'));
    }

    public function user_login_callback()
    {
        printf('<input class="regular-text" type="text" name="LabelcoOptionName[user_login]"
                            id="user_login" value="%s" placeholder="%s" require >', isset($this->LabelcoOptions['user_login']) ? esc_attr($this->LabelcoOptions['user_login']) : '', __('like e.g user_login', 'fixolabels'));
    }

    public function user_pass_callback()
    {
        printf('<input class="regular-text" type="password" name="LabelcoOptionName[user_pass]" 
                            id="user_pass" value="%s" placeholder="%s" require>', isset($this->LabelcoOptions['user_pass']) ? esc_attr($this->LabelcoOptions['user_pass']) : '', __('like e.g ^&*$GGI', 'fixolabels'));
    }

    public function charset_code_callback()
    {
        printf('<input class="regular-text" type="text" name="LabelcoOptionName[charset_code]" 
                            id="charset_code" value="%s" placeholder="%s" require>', isset($this->LabelcoOptions['charset_code']) ? esc_attr($this->LabelcoOptions['charset_code']) : '', __('like e.g utf-8', 'fixolabels'));
    }

    /**
     *
     * @return Labelco
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
            // *****Add Admin Interface Area*****//
            // create admin menu
            add_action('admin_menu', [
                self::$instance,
                'LabelcoAddPluginPage'
            ]);
            // option page init
            add_action('admin_init', [
                self::$instance,
                'LabelcoPageInit'
            ]);

            try {
                self::$instance->db = DB::instance();
            } catch (Exception $e) {
                echo '<div class="notice notice-error"><p>' . $e->getMessage() . '</p></div>';
            }

            add_action('wp', [
                self::$instance,
                'wpLoaded'
            ]);
            add_action('woocommerce_init', [
                self::$instance,
                'woocommerceInit'
            ]);
            add_filter('woocommerce_add_cart_item_data', [
                self::$instance,
                'addToCartProcess'
            ], 10, 2);
            self::$instance->ajax = self::$instance->getAjaxService();
        }

        return self::$instance;
    }

    /**
     *
     * @return \Labelco\SizesService
     */
    public function getSizesService(): SizesService
    {
        return new SizesService($this->db);
    }

    /**
     *
     * @return \Labelco\QuantitiesService
     */
    public function getQuantitiesService(): QuantitiesService
    {
        return new QuantitiesService($this->db);
    }

    /**
     *
     * @return \Labelco\FinalPriceService
     */
    public function getFinalPriceService(): FinalPriceService
    {
        return new FinalPriceService($this->db);
    }

    /**
     *
     * @return \Labelco\MaterialService
     */
    public function getMaterialService(): MaterialService
    {
        return new MaterialService($this->db);
    }

    /**
     *
     * @return Ajax
     */
    public function getAjaxService(): AjaxService
    {
        return new AjaxService();
    }

    /**
     *
     * @return \Labelco\FinalPriceValidator
     */
    public function getFinalPriceValidator(): FinalPriceValidator
    {
        return new FinalPriceValidator();
    }

    /**
     *
     * @return \Labelco\SizesValidator
     */
    public function getSizesValidator(): SizesValidator
    {
        return new SizesValidator();
    }

    /**
     *
     * @return string
     */
    private function getPluginsDir(): string
    {
        return ABSPATH . 'wp-content/plugins/labelco-integrator/';
    }

    /**
     *
     * @return string
     */
    private function getTemplatesDir(): string
    {
        return $this->getPluginsDir() . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'templates';
    }

    /**
     *
     * @return string
     */
    private function getJsUri(): string
    {
        return plugins_url('/assets/js', $this->getPluginsDir() . DIRECTORY_SEPARATOR . 'labelco-integrator.php');
    }

    /**
     *
     * @return string
     */
    private function getCssUri(): string
    {
        return plugins_url('/assets/css', $this->getPluginsDir() . DIRECTORY_SEPARATOR . 'labelco-integrator.php');
    }

    public function includeCalculatorTemplate()
    {
      //  if (! has_term('Produkt generowany', 'product_cat')) {
            require ($this->getTemplatesDir() . DIRECTORY_SEPARATOR . 'calculator.php');
      //  }
    }

    public function labelcoGetProdMeta()
    {
        global $post;
        global $product;

        if ($product->has_attributes() || $product->has_dimensions() || $product->has_weight()) {

            $labelcoProdSizeMetaVal = get_post_meta($post->ID, '_labelco_prod_size', true);

            if (isset($labelcoProdSizeMetaVal) && (has_term('Produkt generowany', 'product_cat'))) {
                ob_start();
                echo '<p>' . __('Product Size  : ', 'fixolabels') . $labelcoProdSizeMetaVal . ' </p>';
                $content = ob_get_clean();
                echo $content;
            }

            $labelcoProdQuantityMetaVal = get_post_meta($post->ID, '_labelco_prod_quantity', true);

            if (isset($labelcoProdQuantityMetaVal) && (has_term('Produkt generowany', 'product_cat'))) {
                ob_start();
                echo '<p>' . __('Product Quantitiy   :  ', 'fixolabels') . $labelcoProdQuantityMetaVal . '</p>';
                $content = ob_get_clean();
                echo $content;
            }

            $labelcoPrice = get_post_meta($post->ID, '_regular_price', true) ? get_post_meta($post->ID, '_regular_price', true) : get_post_meta($post->ID, '_labelco_prod_spare_price', true);

            $labelcoSale = get_post_meta($post->ID, '_sale_price', true) ? get_post_meta($post->ID, ' _sale_price', true) : get_post_meta($post->ID, '_labelco_prod_spare_price', true);

            if (isset($labelcoPrice) || isset($labelcoSale) && (has_term('Produkt generowany', 'product_cat'))) {
                ob_start();
                echo '<p>' . __('Product Price   :  ', 'fixolabels') . wc_price($labelcoPrice) . '</p>';
                echo '<p>' . __('Product Sale   :  ', 'fixolabels') . wc_price($labelcoSale) . '</p>';
                $content = ob_get_clean();
                echo $content;
            }
        } // if statement has attr , dimensions
    }

    /**
     *
     * @return bool
     */
    private static function isAjax()
    {
        return defined('DOING_AJAX') && true === DOING_AJAX;
    }

    /**
     *
     * @return \Labelco\CartService
     */
    public function getCartService(): \Labelco\CartService
    {
        return $this->cartService;
    }
}
