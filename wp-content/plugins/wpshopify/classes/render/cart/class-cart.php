<?php

namespace WP_Shopify\Render;

if (!defined('ABSPATH')) {
    exit();
}

class Cart
{
    public $Templates;
    public $Defaults_Cart;

    public function __construct($Templates, $Defaults_Cart)
    {
        $this->Templates = $Templates;
        $this->Defaults_Cart = $Defaults_Cart;
    }

    public function cart_icon($data = [])
    {
        return $this->Templates->load([
            'path' => 'components/cart/icon/wrapper',
            'type' => 'cart',
            'defaults' => 'cart',
            'data' => array_merge(
                $this->Defaults_Cart->cart_icon($data),
                $data
            ),
            'skip_required_data' => true,
        ]);
    }
}
