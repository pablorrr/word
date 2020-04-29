<?php
/**
 * The template for displaying header
 *
 * Displays all of the <head>  and <header> section
 *
 * @package wptheme
 */
?>

<!DOCTYPE html>

<!--[if gt IE 9]>
<html <?php language_attributes(); ?>> <![endif]-->
<!--[if !IE]><!-->
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title"
          content="<?php bloginfo('name'); ?> - <?php bloginfo('description'); ?>">
    <link rel="profile" href="http://gmpg.org/xfn/11">

    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
    <?php wp_head(); ?>

</head>
<body data-spy="scroll" data-target=".navbar" data-offset="50" <?php body_class(); ?>>

<header id="" class="" style="" role="banner">

    <div class="container-fluid text-vcenter">
        <div class="row align-items-start">
            <div class="col-md-12">
                <nav class="navbar navbar-expand-md fixed-top" data-spy="affix" data-offset-top="400"
                     role="navigation">

                    <div class="container-fluid">
                        <div class="navbar-header">
                            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse"
                                    data-target="#mainNav" aria-controls="navbarCollapse" aria-expanded="false"
                                    aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                        </div>
                        <?php if (has_nav_menu('primary')): ?>
                        <div id="mainNav" class="navbar-collapse collapse">
                            <?php
                            wp_nav_menu(array(
                                'theme_location' => 'primary',
                                'container' => 'div',
                                'container_id' => '',
                                'container_class' => '',
                                'menu_id' => false,
                                'menu_class' => 'navbar-nav mr-auto',
                                'depth' => 3,
                                'walker' => new wptheme_navwalker()
                            ));
                            ?>
                        </div>
                    </div>
                    <?php elseif (is_user_logged_in()): ?>

                        <div class="container-fluid">
                            <h3>
                                <?php printf(wp_kses(__('Theres no menu, <a href="%1$s" target="%2$s">go to 		admin 	panel and create and activate menu</a>.', 'wptheme'),
                                    array('a' => array('href' => array(), 'target' => array()))),
                                    admin_url('nav-menus.php'), '_blank'); ?>
                            </h3>
                        </div>
                        <!-- end has menu primary-->
                    <?php endif; ?>
                </nav>

                <!-- main home content -->
                <div class="container mt-5 pt-5">
                    <div class="row justify-content-center">


                        <h1 class=""><a style="" class="site-title"
                                        href="<?php echo esc_url(home_url('/')); ?>"
                                        rel="home"><?php bloginfo('name'); ?></a></h1>
                    </div>
                    <div class="row justify-content-center">
                        <h2 class="has-text-align-center">
                            <?php if (is_shop())
                                _e('Shop Page', 'wptheme');
                            elseif (is_checkout())
                                _e('Checkout Page', 'wptheme');
                            elseif (is_cart())
                                _e('Cart Page', 'wptheme');
                            elseif (is_account_page())
                                _e('Account Page', 'wptheme');
                            elseif (is_product())
                                _e('Product Page', 'wptheme');
                            elseif (is_product_category())
                                _e('Product Category Page', 'wptheme');
                            elseif (is_product_tag())
                                _e('Product Tag Page', 'wptheme');
                            ?></h2>
                    </div>


                </div>
            </div><!--.col-md-12 -->
        </div><!--.row align-items-start-->
    </div><!--container-fluid text-vcenter-->


</header>
