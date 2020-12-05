<?php

namespace WP_Shopify;

use WP_Shopify\Options;
use WP_Shopify\Utils;

defined('ABSPATH') ?: exit();

class Shortcodes
{
    public $Render_Products;
    public $Defaults_Products;
    public $Render_Cart;
    public $Defaults_Cart;
    public $DB_Products;
    public $Render_Search;
    public $Defaults_search;
    public $Render_Storefront;
    public $Defaults_storefront;
    public $Render_Collections;

    /*

    Initialize the class and set its properties.

     */
    public function __construct(
        $Render_Products,
        $Defaults_Products,
        $Render_Cart,
        $Defaults_Cart,
        $DB_Products,
        $Render_Search,
        $Defaults_search,
        $Render_Storefront,
        $Defaults_storefront,
        $Render_Collections
    ) {
        $this->Render_Products = $Render_Products;
        $this->Render_Cart = $Render_Cart;
        $this->Defaults_Products = $Defaults_Products;
        $this->Defaults_Cart = $Defaults_Cart;
        $this->DB_Products = $DB_Products;
        $this->Render_Search = $Render_Search;
        $this->Defaults_search = $Defaults_search;
        $this->Render_Storefront = $Render_Storefront;
        $this->Defaults_storefront = $Defaults_storefront;
        $this->Render_Collections = $Render_Collections;
    }

    public function shortcode($fn)
    {
        ob_start();

        $fn();

        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    /*

    Responsible for processing the [wps_products_title] shortcode

     */
    public function shortcode_wps_products_title($shortcode_atts)
    {
        return $this->shortcode(function () use ($shortcode_atts) {
            $this->Render_Products->title(
                $this->Defaults_Products->product_title($shortcode_atts)
            );
        });
    }

    public function shortcode_wps_products_description($shortcode_atts)
    {
        return $this->shortcode(function () use ($shortcode_atts) {
            $this->Render_Products->description(
                $this->Defaults_Products->product_description($shortcode_atts)
            );
        });
    }

    public function shortcode_wps_products_pricing($shortcode_atts)
    {
        return $this->shortcode(function () use ($shortcode_atts) {
            $this->Render_Products->pricing(
                $this->Defaults_Products->product_pricing($shortcode_atts)
            );
        });
    }

    public function shortcode_wps_products_buy_button($shortcode_atts)
    {
        return $this->shortcode(function () use ($shortcode_atts) {
            $this->Render_Products->buy_button(
                $this->Defaults_Products->product_buy_button($shortcode_atts)
            );
        });
    }

    /*

    Responsible for processing the [wps_products] shortcode

     */
    public function shortcode_wps_products($shortcode_atts)
    {
        if (!$shortcode_atts) {
            $shortcode_atts = [];
        }

        return $this->shortcode(function () use ($shortcode_atts) {
            $this->Render_Products->products(
                $this->Defaults_Products->products($shortcode_atts)
            );
        });
    }

    public function shortcode_wps_product_gallery($shortcode_atts)
    {
        if (!$shortcode_atts) {
            $shortcode_atts = [];
        }

        return $this->shortcode(function () use ($shortcode_atts) {
            $this->Render_Products->gallery(
                $this->Defaults_Products->product_gallery($shortcode_atts)
            );
        });
    }

    public function shortcode_wps_cart_icon($shortcode_atts)
    {
        if (!$shortcode_atts) {
            $shortcode_atts = [];
        }

        return $this->shortcode(function () use ($shortcode_atts) {
            $this->Render_Cart->cart_icon(
                $this->Defaults_Cart->cart_icon($shortcode_atts)
            );
        });
    }

    public function shortcode_wps_collections($shortcode_atts)
    {
        if (!$shortcode_atts) {
            $shortcode_atts = [];
        }

        return $this->shortcode(function () use ($shortcode_atts) {
            $this->Render_Collections->collections($shortcode_atts);
        });
    }

    public function wps_cart()
    {
        $this->Render_Cart->cart_icon([
            'type' => 'fixed',
        ]);
    }


    public function init()
    {
        \add_shortcode('wps_products', [$this, 'shortcode_wps_products']);
        \add_shortcode('wps_products_title', [
            $this,
            'shortcode_wps_products_title',
        ]);
        \add_shortcode('wps_products_description', [
            $this,
            'shortcode_wps_products_description',
        ]);
        \add_shortcode('wps_products_pricing', [
            $this,
            'shortcode_wps_products_pricing',
        ]);
        \add_shortcode('wps_products_buy_button', [
            $this,
            'shortcode_wps_products_buy_button',
        ]);
        \add_shortcode('wps_products_gallery', [
            $this,
            'shortcode_wps_product_gallery',
        ]);
        \add_shortcode('wps_collections', [$this, 'shortcode_wps_collections']);


        \add_shortcode('wps_cart_icon', [$this, 'shortcode_wps_cart_icon']);

        if (!is_admin()) {
            \add_action('wp_footer', [$this, 'wps_cart']);
        }
    }
}
