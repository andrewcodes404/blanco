<?php

namespace WP_Shopify\Factories\Shortcodes;

defined('ABSPATH') ?: exit();

use WP_Shopify\Factories;

class Shortcodes_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (!$plugin_settings) {
            $plugin_settings = Factories\DB\Settings_Plugin_Factory::build();
        }

        if (is_null(self::$instantiated)) {
            self::$instantiated = new \WP_Shopify\Shortcodes(
                Factories\Render\Products\Products_Factory::build(),
                Factories\Render\Products\Defaults_Factory::build(
                    $plugin_settings
                ),
                Factories\Render\Cart\Cart_Factory::build(),
                Factories\Render\Cart\Defaults_Factory::build($plugin_settings),
                Factories\DB\Products_Factory::build(),
                Factories\Render\Search\Search_Factory::build(),
                Factories\Render\Search\Defaults_Factory::build(
                    $plugin_settings
                ),
                Factories\Render\Storefront\Storefront_Factory::build(),
                Factories\Render\Storefront\Defaults_Factory::build(),
                Factories\Render\Collections\Collections_Factory::build()
            );
        }

        return self::$instantiated;
    }
}
