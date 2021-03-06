<?php

namespace WP_Shopify\Factories\Render;

if (!defined('ABSPATH')) {
    exit();
}

use WP_Shopify\Render\Templates;
use WP_Shopify\Factories;

class Templates_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (is_null(self::$instantiated)) {
            self::$instantiated = new Templates(
                Factories\Template_Loader_Factory::build()
            );
        }

        return self::$instantiated;
    }
}
