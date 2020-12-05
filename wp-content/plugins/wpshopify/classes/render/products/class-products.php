<?php

namespace WP_Shopify\Render;

if (!defined('ABSPATH')) {
    exit();
}

class Products
{
    public $Templates;
    public $Defaults_Products;

    public function __construct($Templates, $Defaults_Products)
    {
        $this->Templates = $Templates;
        $this->Defaults_Products = $Defaults_Products;
    }

    public function merge_user_component_data($user_data, $component_type)
    {
        return array_merge(
            $this->Defaults_Products->$component_type($user_data),
            $user_data
        );
    }

    public function buy_button($data = [])
    {
        return $this->add_to_cart($data);
    }

    public function add_to_cart($data = [])
    {
        return $this->Templates->load([
            'path' => 'components/products/buy-button/buy',
            'name' => 'button',
            'type' => 'products',
            'defaults' => 'add_to_cart',
            'data' => $this->merge_user_component_data(
                $data,
                'product_add_to_cart'
            ),
        ]);
    }

    /*

    Products: Title

     */
    public function title($data = [])
    {
        return $this->Templates->load([
            'path' => 'components/products/title/title',
            'type' => 'products',
            'defaults' => 'title',
            'data' => $this->merge_user_component_data($data, 'product_title'),
        ]);
    }

    /*

    Products: Description

     */
    public function description($data = [])
    {
        return $this->Templates->load([
            'path' => 'components/products/description/description',
            'type' => 'products',
            'defaults' => 'description',
            'data' => $this->merge_user_component_data(
                $data,
                'product_description'
            ),
        ]);
    }

    public function pricing($data = [])
    {
        return $this->Templates->load([
            'path' => 'components/products/pricing/pricing',
            'type' => 'products',
            'defaults' => 'pricing',
            'data' => array_merge(
                $this->Defaults_Products->product_pricing($data),
                $data
            ),
        ]);
    }

    public function gallery($data = [])
    {
        return $this->Templates->load([
            'path' => 'components/products/gallery/gallery',
            'type' => 'products',
            'defaults' => 'gallery',
            'data' => array_merge(
                $this->Defaults_Products->product_gallery($data),
                $data
            ),
        ]);
    }

    public function products($data = [])
    {
        return $this->Templates->load([
            'path' => 'components/products/products-all',
            'type' => 'products',
            'defaults' => 'products',
            'cache_key' => 'wp_shopify_shortcode_wps_products_',
            'data' => $this->merge_user_component_data($data, 'products'),
        ]);
    }
}
