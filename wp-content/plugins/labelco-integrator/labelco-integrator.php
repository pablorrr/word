<?php
/**
 * Plugin Name: Labelco Integrator
 * Description:
 * Version: 1.0.2
 * Author: InspireLabs
 * Author URI: http://www.inspirelabs.pl
 * Text Domain: woocommerce
 * License: GPLv2 or later
 */

require "vendor/autoload.php";
require "db-config.php";

use Labelco\Labelco as Labelco;

function labelco_get_sized_service(): \Labelco\SizesService
{
    //return \Labelco\Labelco::getInstance()->getSizesService();
    var_dump(\Labelco\Labelco::getInstance()->getSizesService());
    die;
}

/**
 * @return Labelco
 */
function get_labelco(): Labelco
{
    return \Labelco\Labelco::getInstance();
}

get_labelco();