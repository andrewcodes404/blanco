<?php

namespace WP_Shopify\Render;

if (!defined('ABSPATH')) {
    exit();
}

/*

Render: Search

*/
class Search
{
    public $Templates;

    public function __construct($Templates)
    {
        $this->Templates = $Templates;
    }

    /*

	Search: Search

	*/
    public function search($data = [])
    {
        return $this->Templates->load([
            'path' => 'components/search/search',
            'type' => 'search',
            'defaults' => 'search',
            'data' => $data,
            'skip_required_data' => true,
        ]);
    }
}
