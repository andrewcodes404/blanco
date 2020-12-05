<?php

namespace WP_Shopify;

if (!defined('ABSPATH')) {
    exit();
}

class Templates
{
    public $Template_Loader;
    public $plugin_settings;

    public function __construct($Template_Loader, $plugin_settings)
    {
        $this->Template_Loader = $Template_Loader;
        $this->plugin_settings = $plugin_settings;
    }

    /*

	Template: components/pagination/end

	*/
    public function wps_products_pagination_end()
    {
        $data = [];

        ob_start();
        $this->Template_Loader
            ->set_template_data($data)
            ->get_template_part('components/pagination/end');
        $output = ob_get_clean();
        return $output;
    }

    public function wps_products_pagination_start()
    {
        $data = [];

        ob_start();
        $this->Template_Loader
            ->set_template_data($data)
            ->get_template_part('components/pagination/start');
        $output = ob_get_clean();
        return $output;
    }

    /*

	Template: components/pagination/breadcrumbs

	*/
    public function wpshopify_breadcrumbs($shortcodeData = false)
    {
        if (
            apply_filters(
                'wps_breadcrumbs_show',
                $this->plugin_settings['general']['show_breadcrumbs']
            )
        ) {
            $data = [];

            return $this->Template_Loader
                ->set_template_data($data)
                ->get_template_part('components/pagination/breadcrumbs');
        }
    }

    public function wpshopify_root_elements()
    {
        echo '<div id="wpshopify-root"></div>';
    }

    public function wpshopify_single_template($template)
    {
        if (is_singular(WP_SHOPIFY_PRODUCTS_POST_TYPE_SLUG)) {
            return $this->Template_Loader->get_template_part(
                'products',
                'single',
                false
            );
        }

        if (is_singular(WP_SHOPIFY_COLLECTIONS_POST_TYPE_SLUG)) {
            return $this->Template_Loader->get_template_part(
                'collections',
                'single',
                false
            );
        }

        return $template;
    }

    /*

	Main Template products-all

	*/
    public function wpshopify_all_template($template)
    {
        if (is_post_type_archive(WP_SHOPIFY_PRODUCTS_POST_TYPE_SLUG)) {
            return $this->Template_Loader->get_template_part(
                'products',
                'all',
                false
            );
        }

        if (is_post_type_archive(WP_SHOPIFY_COLLECTIONS_POST_TYPE_SLUG)) {
            return $this->Template_Loader->get_template_part(
                'collections',
                'all',
                false
            );
        }

        return $template;
    }

    public function wpshopify_page_templates($template)
    {
        $enable_default_pages =
            $this->plugin_settings['general']['enable_default_pages'];

        if (!$enable_default_pages) {
            return $template;
        }

        $current_page_id = get_the_ID();
        $page_products_id = $this->plugin_settings['general']['page_products'];
        $page_collections_id =
            $this->plugin_settings['general']['page_collections'];

        if ($current_page_id === $page_products_id) {
            return $this->Template_Loader->get_template_part(
                'products',
                'all',
                false
            );
        }

        if ($current_page_id === $page_collections_id) {
            return $this->Template_Loader->get_template_part(
                'collctions',
                'all',
                false
            );
        }

        return $template;
    }

    public function init()
    {
        add_action('wps_breadcrumbs', [$this, 'wpshopify_breadcrumbs']);
        add_action('wp_footer', [$this, 'wpshopify_root_elements']);

        add_filter('single_template', [$this, 'wpshopify_single_template']);
        add_filter('archive_template', [$this, 'wpshopify_all_template']);

        add_filter('page_template', [$this, 'wpshopify_page_templates']);

        add_filter('wps_products_pagination_start', [
            $this,
            'wps_products_pagination_start',
        ]);
        add_filter('wps_products_pagination_end', [
            $this,
            'wps_products_pagination_end',
        ]);
    }
}
