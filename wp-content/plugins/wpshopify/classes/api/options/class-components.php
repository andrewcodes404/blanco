<?php

namespace WP_Shopify\API\Options;

use WP_Shopify\Utils;

defined('ABSPATH') ?: exit();

class Components extends \WP_Shopify\API
{
    public function __construct($Template_Loader)
    {
        $this->Template_Loader = $Template_Loader;
    }

    public function get_components_template($request)
    {
        $template_name = $request->get_param('data');
        $template_name = pathinfo($template_name)['filename'];

        ob_start();
        $this->Template_Loader
            ->set_template_data([])
            ->get_template_part('components/custom/' . $template_name);
        $output = ob_get_clean();

        return $this->handle_response([
            'response' => Utils::sanitize_html_template(
                Utils::strip_tags_content($output, '<style>', true)
            ),
        ]);
    }

    public function register_route_components_template()
    {
        return register_rest_route(
            WP_SHOPIFY_SHOPIFY_API_NAMESPACE,
            '/components/template',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'get_components_template'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function init()
    {
        add_action('rest_api_init', [
            $this,
            'register_route_components_template',
        ]);
    }
}
