<?php

namespace Labelco;

use \WooCommerce as WooCommerce;
use \WC_Cart as WC_CART;
use \WC_Product as WC_Product;
use \WC_Order as WC_Order;
use \WC_Session as WC_Session;
use \WC_Session_Handler as WC_Session_Handler;
use \WC_Order_Item as WC_Order_Item;
use \WC_Order_Item_Product as WC_Order_Item_Product;
use \WC_Product_Simple as WC_Product_Simple;

/**
 * Class WoocommerceCartCustomPrices
 * @author InspireLabs
 * @version 1.1.0
 */
class CartService
{
    const CUSTOM_PRICE_ARRAY_KEY = 'labelco_custom_price';
    const CUSTOM_PRICES_ARRAY_KEY = 'labelco_custom_prices';
    const CUSTOM_PARAMETERS_ARRAY_KEY = 'labelco_custom_parameters';

    /**
     * @var WooCommerce
     */
    private $woocommerce;

    /**
     * @var WC_Session|WC_Session_Handler
     */
    private $woocommerceSession;

    /**
     * @var array
     */
    private $customPrice;

    /**
     * @var array
     */
    private $customPrices;

    /**
     * @var array
     */
    private $customParameters;

    /**
     * @var CartService|null
     */
    private static $instance;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    /**
     * @return CartService|bool
     */
    public static function getInstance()
    {
        if (!class_exists('WooCommerce')) {
            return false;
        }

        if (is_admin() && !defined('DOING_AJAX')) {
            self::$instance = new self();
            add_action('woocommerce_admin_order_item_values',
                [self::$instance, 'filterWoocommerceOrderReceivedItemValues'], 10, 3);
            add_action('woocommerce_order_item_get_formatted_meta_data',
                [self::$instance, 'filterWoocommerceAdminOrderItemValues'], 10, 3);

        } else {
            if (self::$instance === null) {
                self::$instance = new self();
                self::$instance->setupWoocommerceObjects();
                add_action('woocommerce_before_calculate_totals', [self::$instance, 'updatePriceInCart'], 10, 1);
                self::$instance->loadCustomPriceFromSession();
                self::$instance->loadCustomPricesFromSession();
                self::$instance->loadCustomParametersFromSession();
                add_action('woocommerce_checkout_create_order', [self::$instance, 'forwardCustomParamsFromCartToOrder'],
                    20, 2);
                add_action('woocommerce_order_item_get_formatted_meta_data',
                    [self::$instance, 'filterWoocommerceAdminOrderItemValues'], 10, 3);
                add_filter('wc_epo_no_edit_options', '__return_true');
                add_filter('woocommerce_cart_item_price', [self::$instance, 'filterMiniCartPrice'], 10,
                    3);
            }
        }

        return self::$instance;
    }

    /**
     * @param $price
     * @param $cart_item
     * @param $cart_item_key
     * @return string
     */
    public function filterMiniCartPrice($price, $cart_item, $cart_item_key)
    {
        $uniqueKey = $cart_item_key;
        $cachedPrice = isset($this->customPrices[$uniqueKey]) ? $this->customPrices[$uniqueKey] : null;
        $cachedParameters = isset($this->customParameters[$uniqueKey]) ? $this->customParameters[$uniqueKey] : null;

        $output = '';

        if (!empty($cachedPrice)) {
            $output .= wc_price((float)$cachedPrice, ['decimals' => 2]);
        }

        if (!empty($cachedParameters) && false === is_cart()) {
            $output .= $this->formatCartParameters($cachedParameters);

        }

        return $output;
    }

    /**
     * @param object $formatted_meta
     * @param WC_Order_Item $orderItem
     *
     * @return string
     */
    public function filterWoocommerceAdminOrderItemValues($formatted_meta, $orderItem)
    {
        if (false === is_array($formatted_meta)) {
            return $formatted_meta;
        }

        foreach ($formatted_meta as $k => $item) {
            if (self::CUSTOM_PARAMETERS_ARRAY_KEY === $item->key) {
                $unserialized = unserialize(trim(strip_tags($item->display_value)));
                $item->display_value = $this->formatWpAdminOrderParameters($unserialized);
                $item->display_key = "Parametry";
            }
        }

        return $formatted_meta;
    }

    public function filterWoocommerceOrderReceivedItemValues($formatted_meta, $orderItem)
    {
        if (false === is_array($formatted_meta)) {
            return $formatted_meta;
        }

        foreach ($formatted_meta as $k => $item) {
            if (self::CUSTOM_PARAMETERS_ARRAY_KEY === $item->key) {
                $unserialized = unserialize(trim(strip_tags($item->display_value)));
                $item->display_value = $this->formatOrderReceivedParameters($unserialized);
                $item->display_key = "Parametry";
            }
        }

        return $formatted_meta;
    }

    /**
     * @param WC_Order $order
     * @param $data
     */
    public function forwardCustomParamsFromCartToOrder($order, $data)
    {
        $OrderItems = $order->get_items();

        /**
         * @var WC_Order_Item_Product $item
         * @var WC_Product_Simple $legacyProductFromCart
         */
        foreach ($OrderItems as $item) {
            if (!isset($item->legacy_values['data'])) {
                continue;
            }
            $legacyProductFromCart = $item->legacy_values['data'];
            $legacyParams = $legacyProductFromCart->get_meta('_labelco_params');
            $legacyParams['prod_id'] = $item->get_product()->get_id();
            if (empty($legacyParams)) {
                continue;
            }
            $item->add_meta_data(self::CUSTOM_PARAMETERS_ARRAY_KEY, serialize($legacyParams));
        }

        $this->reset();
    }

    /**
     * @param WC_Cart $cartObject
     *
     * @return WC_Cart
     */
    public function updatePriceInCart($cartObject)
    {
        if (null === self::$instance->customPrice) {
            return $cartObject;
        }

        $productsArray = $cartObject->get_cart();

        foreach ($productsArray as $productInCartItem) {
            /**
             * @var WC_Product $wcProduct
             */
            $wcProduct = $productInCartItem['data'];
            $a = self::$instance->customPrice['product_id'];
            $b = $wcProduct->get_id();

            if ((int)$a === $b) {
                $uniqueKey = $productInCartItem['key'];
                $cachedPrice = isset($this->customPrices[$uniqueKey]) ? $this->customPrices[$uniqueKey] : null;
                $cachedParameters = isset($this->customParameters[$uniqueKey]) ? $this->customParameters[$uniqueKey] : null;
                if (!empty($cachedPrice)) {
                    $calculatedPrice = $cachedPrice;

                    $originalName = method_exists($wcProduct,
                        'get_name') ? $wcProduct->get_name() : $wcProduct->post->post_title;

                    if (!Is_checkout()) {
                        $newName = $wcProduct->get_title() . $this->formatCartParameters($cachedParameters);
                    } else {
                        $newName = $originalName;
                    }

                    if (method_exists($wcProduct, 'set_name')) {
                        $wcProduct->set_name($newName);
                    } else {
                        $wcProduct->post->post_title = $newName;
                    }

                } else {
                    $calculatedPrice = self::$instance->customPrice['price'];
                    $this->customPrices[$uniqueKey] = $calculatedPrice;
                    $this->customParameters[$uniqueKey] = $this->customPrice['params'];
                    $this->saveToWoocommerceSession(self::CUSTOM_PRICES_ARRAY_KEY, $this->customPrices);
                    $this->saveToWoocommerceSession(self::CUSTOM_PARAMETERS_ARRAY_KEY, $this->customParameters);
                }

                $wcProduct->add_meta_data('_labelco_params', $cachedParameters);
                $wcProduct->set_price($calculatedPrice);
            }
        }

        return $cartObject;
    }

    /**
     * @param $parameters
     * @return string
     */
    private function formatCartParameters(array $parameters)
    {
        $output = sprintf(
            '<br><div><span style="color: #d43a3a">Rozmiar: (%sx%s)<br>Ilość: %s</span></div>',
            $parameters['size_height'],
            $parameters['size_width'],
            $parameters['quantity']
        );

        return $output;
    }

    /**
     * @param $parameters
     * @return string
     */
    private function formatOrderReceivedParameters(array $parameters)
    {
        $output = sprintf(
            '<br><div><span style="color: #d43a3a">Rozmiar: (%sx%s)<br>Ilość: %s</span></div>',
            $parameters['size_height'],
            $parameters['size_width'],
            $parameters['quantity']
        );

        return $output;
    }

    /**
     * @param $parameters
     * @return string
     */
    private function formatWpAdminOrderParameters(array $parameters)
    {
        return sprintf(
            '<span style="color: #d43a3a">Rozmiar: (%sx%s) | Ilość: %s</span>',
            $parameters['size_height'],
            $parameters['size_width'],
            $parameters['quantity']
        );
    }


    private function setupWoocommerceObjects()
    {
        self::$instance->woocommerce = WC();
        self::$instance->woocommerceSession = self::$instance->woocommerce->session;
    }

    private function saveCustomPrice()
    {
        if (null !== $this->customPrice) {
            $this->saveToWoocommerceSession(self::CUSTOM_PRICE_ARRAY_KEY, $this->customPrice);
        }
    }

    /**
     * @param int $productId
     * @param float $price
     */
    public function setCustomPrice($productId, $price)
    {
        $this->customPrice = ['product_id' => $productId, 'price' => $price];
        $this->saveCustomPrice();
    }

    /**
     * @param int $productId
     * @param $params
     */
    public function setCustomParameters($productId, $params)
    {
        if (isset($this->customPrice)) {
            $this->customPrice['params'] = $params;
        } else {
            $this->customPrice = ['product_id' => $productId, 'params' => $params];
        }
        $this->saveCustomPrice();
    }

    /**
     * @return float|null
     */
    public function getCustomPrice()
    {
        return !empty($this->customPrice) ? $this->customPrice['price'] : null;
    }

    public function removeCustomPrice()
    {
        $this->customPrice = null;
        $this->saveCustomPrice();
    }

    public function reset()
    {
        $this->saveToWoocommerceSession(self::CUSTOM_PRICE_ARRAY_KEY, null);
        $this->saveToWoocommerceSession(self::CUSTOM_PRICES_ARRAY_KEY, null);
        $this->saveToWoocommerceSession(self::CUSTOM_PARAMETERS_ARRAY_KEY, null);
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function saveToWoocommerceSession($key, $value)
    {

        $this->woocommerceSession->set($key, $value);
    }

    private function loadCustomPriceFromSession()
    {
        $this->customPrice = $this->getFromSession(self::CUSTOM_PRICE_ARRAY_KEY);
    }

    private function loadCustomPricesFromSession()
    {
        $this->customPrices = $this->getFromSession(self::CUSTOM_PRICES_ARRAY_KEY);
    }

    private function loadCustomParametersFromSession()
    {
        $this->customParameters = $this->getFromSession(self::CUSTOM_PARAMETERS_ARRAY_KEY);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    private function getFromSession($key)
    {
        if (null === self::$instance->woocommerceSession) {
            return null;
        }

        return self::$instance->woocommerceSession->get($key);
    }
}
