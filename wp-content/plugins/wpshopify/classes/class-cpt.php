<?php

namespace WP_Shopify;

use WP_Shopify\Utils;
use WP_Shopify\Utils\Data as Utils_Data;
use WP_Shopify\Transients;

if (!defined('ABSPATH')) {
    exit();
}

class CPT
{
    public $DB_Settings_General;
    public $plugin_settings;

    /*

	Initialize the class and set its properties.

	*/
    public function __construct($DB_Settings_General, $plugin_settings)
    {
        $this->DB_Settings_General = $DB_Settings_General;
        $this->plugin_settings = $plugin_settings;
    }

    public static function add_meta_to_cpt($posts)
    {
        return array_map(function ($post) {
            $post->post_meta = get_post_meta($post->ID);

            return $post;
        }, $posts);
    }

    public static function get_all_posts($post_type)
    {
        return get_posts([
            'posts_per_page' => -1,
            'post_type' => $post_type,
            'nopaging' => true,
        ]);
    }

    public static function get_all_posts_by_type($post_type)
    {
        return self::add_meta_to_cpt(self::get_all_posts($post_type));
    }

    public static function get_all_posts_compressed($post_type)
    {
        return self::truncate_post_data(self::get_all_posts($post_type));
    }

    public static function only_id_and_post_name($post)
    {
        return [
            'ID' => $post->ID,
            'post_name' => $post->post_name,
        ];
    }

    public static function truncate_post_data($posts)
    {
        return array_map([__CLASS__, 'only_id_and_post_name'], $posts);
    }

    public function find_post_type_slug($type)
    {
        $enable_default_pages =
            $this->plugin_settings['general']['enable_default_pages'];

        if (!$enable_default_pages) {
            return $type;
        }

        $url = $this->plugin_settings['general']['url_' . $type];
        $slug = basename(parse_url($url, PHP_URL_PATH));

        if (!$url || !$slug) {
            return $type;
        }

        return $slug;
    }

    /*

	CPT: Products

	*/
    public function post_type_products()
    {
        if (post_type_exists(WP_SHOPIFY_PRODUCTS_POST_TYPE_SLUG)) {
            return;
        }

        $slug = $this->find_post_type_slug('products');

        $rewrite_rules = [
            'slug' => $slug,
            'with_front' => false,
            'feeds' => true,
        ];

        $publicly_queryable = true;
        $exclude_from_search = false;

        $labels = [
            'name' => 'Products',
            'singular_name' => 'Product',
            'menu_name' => 'Products',
            'new_item' => 'Add New Product',
            'edit_item' => 'Edit Product',
            'not_found' => 'No Products found',
            'not_found_in_trash' => 'No Products found in trash',
        ];

        $args = [
            'label' => 'Products',
            'description' => 'Custom Post Type for Products',
            'labels' => $labels,
            'supports' => [
                'title',
                'page-attributes',
                'editor',
                'custom-fields',
                'comments',
                'thumbnail',
            ],
            'taxonomies' => ['category'],
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => false,
            'menu_position' => 100,
            'menu_icon' => 'dashicons-megaphone',
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => $exclude_from_search,
            'publicly_queryable' => $publicly_queryable,
            'capability_type' => 'post',
            'rewrite' => $rewrite_rules,
            'capabilities' => [
                'create_posts' => false,
            ],
            'map_meta_cap' => true,
        ];

        register_post_type(
            WP_SHOPIFY_PRODUCTS_POST_TYPE_SLUG,
            apply_filters('wps_register_products_args', $args)
        );
    }

    /*

	CPT: Collections

	*/
    public function post_type_collections()
    {
        if (post_type_exists(WP_SHOPIFY_COLLECTIONS_POST_TYPE_SLUG)) {
            return;
        }

        $slug = $this->find_post_type_slug('collections');

        $rewrite_rules = [
            'slug' => $slug,
            'with_front' => false,
            'feeds' => true,
        ];
        $publicly_queryable = true;
        $exclude_from_search = false;

        $labels = [
            'name' => 'Collections',
            'singular_name' => 'Collection',
            'menu_name' => 'Collections',
            'parent_item_colon' => 'Parent Item:',
            'new_item' => 'Add New Collection',
            'edit_item' => 'Edit Collection',
            'not_found' => 'No Collections found',
            'not_found_in_trash' => 'No Collections found in trash',
        ];

        $args = [
            'label' => 'Collections',
            'description' => 'Custom Post Type for Collections',
            'labels' => $labels,
            'supports' => [
                'title',
                'page-attributes',
                'editor',
                'custom-fields',
                'comments',
                'thumbnail',
            ],
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => false,
            'menu_position' => 100,
            'menu_icon' => 'dashicons-megaphone',
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => $exclude_from_search,
            'publicly_queryable' => $publicly_queryable,
            'capability_type' => 'post',
            'rewrite' => $rewrite_rules,
            'capabilities' => [
                'create_posts' => false,
            ],
            'map_meta_cap' => true,
        ];

        register_post_type(
            WP_SHOPIFY_COLLECTIONS_POST_TYPE_SLUG,
            apply_filters('wps_register_collections_args', $args)
        );
    }

    /*

	$product_or_collection = Array

	*/
    public static function find_existing_post_id($product_or_collection)
    {
        if (
            is_array($product_or_collection) &&
            !empty($product_or_collection)
        ) {
            $product_or_collection = Utils::get_first_array_item(
                $product_or_collection
            );

            return $product_or_collection->ID;
        } else {
            return false;
        }
    }

    /*

	Returns an array containing only products / collections that match the passed in ID.

	*/
    public static function find_only_existing_posts(
        $existing_items,
        $item_id,
        $post_type = ''
    ) {
        return array_filter($existing_items, function ($existing_item) use (
            $item_id,
            $post_type
        ) {
            if (
                isset($existing_item->post_meta[$post_type . '_id']) &&
                is_array($existing_item->post_meta[$post_type . '_id'])
            ) {
                return $existing_item->post_meta[$post_type . '_id'][0] ==
                    $item_id;
            }
        });
    }

    /*

	Adds the post ID if one exists. Used for building the product / collections model

	*/
    public static function set_post_id_if_exists($model, $existing_post_id)
    {
        if (!empty($existing_post_id)) {
            $model['ID'] = $existing_post_id;
        }

        return $model;
    }

    /*

	Post exists

	*/
    public static function post_exists_by_handle($posts, $post_handle)
    {
        return in_array($post_handle, array_column($posts, 'post_name'));
    }

    /*

	Creating array of collects to potentially remove

	*/
    public function find_items_to_add($current_items, $saved_items)
    {
        foreach ($current_items as $key => $current_item) {
            if (
                isset($current_item->collection_id) &&
                $current_item->collection_id
            ) {
                if (isset($saved_items[$current_item->collection_id])) {
                    unset($saved_items[$current_item->collection_id]);
                }
            }

            if (isset($current_item->tag) && $current_item->tag) {
                if (isset($saved_items[$current_item->tag])) {
                    unset($saved_items[$current_item->tag]);
                }
            }
        }

        return $saved_items;
    }

    public function found_item_to_remove($current_item_id, $saved_items)
    {
        return in_array($current_item_id, $saved_items, true);
    }

    /*

	Find the WP Post ID of the product being updated

	*/
    public static function find_existing_post_id_from_collection(
        $existing_collections,
        $collection
    ) {
        $found_post = self::find_only_existing_posts(
            $existing_collections,
            $collection->{WP_SHOPIFY_SHOPIFY_PAYLOAD_KEY},
            'collection'
        );
        $found_post_id = self::find_existing_post_id($found_post);

        return $found_post_id;
    }

    /*

	Find the WP Post ID of the product being updated

	*/
    public static function find_existing_post_id_from_product(
        $existing_products,
        $product
    ) {
        $product_id = Utils::find_product_id($product);

        $found_post = self::find_only_existing_posts(
            $existing_products,
            $product_id,
            'product'
        );
        $found_post_id = self::find_existing_post_id($found_post);

        return $found_post_id;
    }

    public static function num_of_posts($type)
    {
        return Utils_Data::add_totals(
            array_values(get_object_vars(wp_count_posts($type)))
        );
    }

    /*

	Checks whether any posts of a given type exist or not

	*/
    public static function posts_exist($type)
    {
        if (self::num_of_posts($type) > 0) {
            return true;
        } else {
            return false;
        }
    }

    /*

	Grabs the current author ID

	*/
    public static function return_author_id()
    {
        if (get_current_user_id() === 0) {
            $author_id = 1;
        } else {
            $author_id = get_current_user_id();
        }

        return intval($author_id);
    }

    /*

	Responsible for assigning a post_id to a post

	*/
    public static function set_post_id($post, $post_id)
    {
        $post->post_id = $post_id;

        return $post;
    }

    public static function get_all_posts_truncated($post_type, $inclusions)
    {
        return Utils::lessen_array_by(
            CPT::get_all_posts($post_type),
            $inclusions
        );
    }

    public static function add_props($item, $props)
    {
        foreach ($props as $key => $value) {
            $item->{$key} = $value;
        }

        return $item;
    }

    public static function add_props_to_items($items, $props)
    {
        return array_map(function ($item) use ($props) {
            return self::add_props($item, $props);
        }, $items);
    }

    public function create_default_products_page()
    {
        return \wp_insert_post([
            'post_title' => wp_strip_all_tags('WP Shopify Products'),
            'post_status' => 'publish',
            'post_author' => get_current_user_id(),
            'post_type' => 'page',
            'post_name' => 'products',
        ]);
    }

    public function create_default_collections_page()
    {
        return \wp_insert_post([
            'post_title' => wp_strip_all_tags('WP Shopify Collections'),
            'post_status' => 'publish',
            'post_author' => get_current_user_id(),
            'post_type' => 'page',
            'post_name' => 'collections',
        ]);
    }

    public function maybe_create_default_pages()
    {
        if (empty($this->plugin_settings['general'])) {
            return [];
        }

        $already_created =
            $this->plugin_settings['general']['default_pages_created'];

        if ($already_created) {
            return [];
        }

        $created_successfully = false;

        $results = [
            'products' => $this->create_default_products_page(),
            'collections' => $this->create_default_collections_page(),
        ];

        if ($results['products'] && $results['collections']) {
            $created_successfully = true;
        }

        if ($created_successfully) {
            $this->DB_Settings_General->update_column_single(
                ['default_pages_created' => true],
                ['id' => 1]
            );

            if (
                !is_wp_error($results['products']) &&
                $results['products'] !== 0
            ) {
                $this->DB_Settings_General->update_column_single(
                    ['page_products' => $results['products']],
                    ['id' => 1]
                );

                $this->DB_Settings_General->update_column_single(
                    ['page_products_default' => $results['products']],
                    ['id' => 1]
                );

                $this->DB_Settings_General->update_column_single(
                    ['url_products' => get_permalink($results['products'])],
                    ['id' => 1]
                );
            }

            if (
                !is_wp_error($results['collections']) &&
                $results['collections'] !== 0
            ) {
                $this->DB_Settings_General->update_column_single(
                    ['page_collections' => $results['collections']],
                    ['id' => 1]
                );

                $this->DB_Settings_General->update_column_single(
                    ['page_collections_default' => $results['collections']],
                    ['id' => 1]
                );

                $this->DB_Settings_General->update_column_single(
                    [
                        'url_collections' => get_permalink(
                            $results['collections']
                        ),
                    ],
                    ['id' => 1]
                );
            }
        }

        return $results;
    }

    public function init()
    {
        $this->post_type_products();
        $this->post_type_collections();
        $this->maybe_create_default_pages();
    }
}
