<?php

namespace WP_Shopify\Render;

use WP_Shopify\Utils;
use WP_Shopify\Utils\Sorting as Utils_Sorting;
use function WP_Shopify\Vendor\DeepCopy\deep_copy;

if (!defined('ABSPATH')) {
    exit();
}

class Templates
{
    public $Template_Loader;

    public function __construct($Template_Loader)
    {
        $this->Template_Loader = $Template_Loader;
    }

    /*

     Template error wrong path

     UNDER TEST

     */
    public function template_error_wrong_path()
    {
        return 'Invalid path for template type';
    }

    /*

     Template error bad data

     UNDER TEST

     */
    public function template_error_bad_data()
    {
        return 'Missing required data when passed to template';
    }

    /*

     Template error no item

     UNDER TEST

     */
    public function template_error_no_item()
    {
        return 'No item found from provided data';
    }

    /*

     2.0 Default template params

     UNDER TEST

     */
    public function default_render_load_wrapper_params()
    {
        return [
            'path' => false,
            'name' => false,
            'type' => false,
            'data' => false,
        ];
    }

    /*

     2.0 Default template params

     UNDER TEST

     */
    public function default_render_load_params()
    {
        return [
            'path' => false,
            'name' => false,
            'type' => false,
            'data' => false,
            'defaults' => false,
            'pre_render' => false,
            'combine' => false,
            'skip_required_data' => false,
        ];
    }

    /*

     Has data

     UNDER TEST

     */
    public function has_params_data($params)
    {
        if (empty($params) || !$this->is_data_set($params)) {
            return false;
        }

        return !$this->is_data_empty($params);
    }

    public function is_name_set($params)
    {
        return isset($params['name']);
    }

    public function is_path_set($params)
    {
        return isset($params['path']);
    }

    public function is_data_set($params)
    {
        return isset($params['data']);
    }

    public function is_type_set($params)
    {
        return isset($params['type']);
    }

    public function is_post_id_set($params)
    {
        if (empty($params) || !$this->is_data_set($params)) {
            return false;
        }

        return isset($params['data']['post_id']);
    }

    public function is_type_wrapper_set($params)
    {
        if (empty($params) || !$this->is_data_set($params)) {
            return false;
        }

        return isset($params['data'][$params['type']]);
    }

    public function is_post_meta_empty($params)
    {
        return empty($params['post_meta']);
    }

    public function is_path_empty($params)
    {
        return empty($params['path']);
    }

    public function is_name_empty($params)
    {
        return empty($params['name']);
    }

    public function is_data_empty($params)
    {
        return empty($params['data']);
    }

    public function is_post_id_empty($params)
    {
        if (empty($params) || !$this->is_data_set($params)) {
            return true;
        }

        return empty($params['data']['post_id']);
    }

    public function is_type_wrapper_empty($params)
    {
        if (empty($params) || !$this->is_data_set($params)) {
            return true;
        }

        return empty($params['data'][$params['type']]);
    }

    public function is_type_id_empty($params)
    {
        if (empty($params) || !$this->is_data_set($params)) {
            return true;
        }

        return empty($params['data'][$params['type'] . '_id']);
    }

    public function is_data_type_array($params)
    {
        if (empty($params) || !$this->is_data_set($params)) {
            return false;
        }

        return is_array($params['data']);
    }

    /*

     Has pre_render prop

     UNDER TEST

     */
    public function has_pre_render($params)
    {
        return !empty($params['pre_render']);
    }

    /*

     Has a type id

     UNDER TEST

     */
    public function has_type($params)
    {
        if (
            empty($params) ||
            !$this->is_data_set($params) ||
            !$this->is_type_set($params)
        ) {
            return false;
        }

        return !$this->is_type_wrapper_empty($params);
    }

    public function has_required_template_params($params)
    {
        if (empty($params)) {
            return false;
        }

        if (
            $this->is_path_set($params) &&
            $this->is_name_set($params) &&
            $this->is_type_set($params)
        ) {
            return true;
        }

        return false;
    }

    /*

     Handles any template errors

     UNDER TEST

     */
    public function handle_template_errors($params, $success_callback)
    {
        // Checks if $data is passed to template ...
        if (
            !$this->has_params_data($params) ||
            !$this->has_required_template_params($params)
        ) {
            return $this->set_and_get_template(
                $this->params_no_results($params)
            );
        }

        // Gets called if no template errors. What want to happen if success
        return $success_callback();
    }

    /*

     Add new item to data

     $item will never be an array, should always be the item in the current loop

     */
    public function add_new_item_to_data($params, $item)
    {
        if (empty($params) || empty($item)) {
            return;
        }

        $params['data'] = $item;

        return $params;
    }

    /*

     Reponsible for actually loading the template

     */
    public function set_and_get_template($params)
    {
        return $this->Template_Loader
            ->set_template_data($params['data'])
            ->get_template_part($params['path'], $params['name']);
    }

    public function convert_params_data_to_object($params)
    {
        $params['data'] = (object) $params['data'];
        return $params;
    }

    public function create_component_options_hash(
        $component_item_data,
        $component_user_options
    ) {
        $component_user_options_hash = Utils::hash(
            $component_user_options,
            true
        );
        $component_item_data_hash = Utils::hash($component_item_data, true);

        return $component_user_options_hash . $component_item_data_hash;
    }

    /*

    Convenience method for assigning component params

    */
    public function get_template_wrapper_options($data)
    {
        $user_params = $data->user_params;
        $params = $data->params;

        $component_render_from_server = isset(
            $user_params['render_from_server']
        )
            ? (int) $user_params['render_from_server']
            : 0;

        $component_is_ready = $component_render_from_server === 1 ? 1 : 0;

        $component_excludes = isset($user_params['excludes'])
            ? $user_params['excludes']
            : false;

        $component_path = isset($params['path']) ? $params['path'] : false;
        $component_type = isset($params['type']) ? $params['type'] : false;
        $component_item_data = isset($params['data']) ? $params['data'] : [];

        $component_type_id = false;
        $component_post_id = false;

        if (
            is_object($component_item_data) &&
            isset($component_item_data->{$component_type . '_id'}) &&
            !empty($component_item_data->{$component_type . '_id'})
        ) {
            $component_type_id = isset(
                $component_item_data->{$component_type . '_id'}
            )
                ? $component_item_data->{$component_type . '_id'}
                : false;

            $component_post_id = isset($component_item_data->post_id)
                ? $component_item_data->post_id
                : false;
        } elseif (is_array($component_item_data)) {
            if (
                isset($component_item_data[0]) &&
                isset($component_item_data[0][$component_type]) &&
                isset(
                    $component_item_data[0][$component_type]
                        ->{$component_type . '_id'}
                ) &&
                !empty(
                    $component_item_data[0][$component_type]
                        ->{$component_type . '_id'}
                )
            ) {
                $component_type_id =
                    $component_item_data[0][$component_type]
                        ->{$component_type . '_id'};
                $component_post_id =
                    $component_item_data[0][$component_type]->post_id;
            }

            if (
                !empty($component_item_data[$component_type]) &&
                !empty(
                    $component_item_data[$component_type]
                        ->{$component_type . '_id'}
                )
            ) {
                $component_type_id =
                    $component_item_data[$component_type]
                        ->{$component_type . '_id'};
                $component_post_id =
                    $component_item_data[$component_type]->post_id;
            }
        }

        $component_id = uniqid('', true);

        if ($params['skip_required_data']) {
            $component_gid = false;
        } else {
            $string =
                'gid://shopify/' .
                ucfirst($component_type) .
                '/' .
                $component_type_id;
            $component_gid = base64_encode($string);
        }

        $component_user_options = $user_params;

        $component_hash = $this->create_component_options_hash(
            $component_item_data,
            $component_user_options
        );

        $component_user_options['component_options_id'] = $component_hash;
        $component_user_options['component_gid'] = $component_gid;

        $component_options_name =
            'wp_shopify_component_options_' . $component_hash;

        $component_options_value = maybe_serialize($component_user_options);

        if ($component_excludes) {
            $component_excludes_encoded = htmlspecialchars(
                json_encode($component_excludes),
                ENT_QUOTES,
                'UTF-8'
            );
        } else {
            $component_excludes_encoded = false;
        }

        return [
            'component_data' => $component_item_data,
            'component_type' => $component_type,
            'component_post_id' => $component_post_id,
            'component_type_id' => $component_type_id,
            'component_render_from_server' => $component_render_from_server,
            'component_is_ready' => $component_is_ready,
            'component_path' => $component_path,
            'component_excludes' => $component_excludes,
            'component_excludes_encoded' => $component_excludes_encoded,
            'component_options' => $component_user_options,
            'component_options_id' => $component_hash,
            'component_options_name' => $component_options_name,
            'component_options_value' => $component_options_value,
            'component_id' => $component_id,
            'component_gid' => $component_gid,
        ];
    }

    public function maybe_load_template($params, $item)
    {
        return $this->handle_template_errors($params, function () use (
            $params,
            $item
        ) {
            $params = $this->add_new_item_to_data($params, $item);

            return $this->set_and_get_template($params);
        });
    }

    public function load_many_templates($params)
    {
        return array_map(function ($item) use ($params) {
            return $this->maybe_load_template($params, $item);
        }, $params['data']);
    }

    public function params_client_render($params)
    {
        return [
            'data' => $params,
            'path' => 'components/wrapper/wrapper',
            'name' => 'client',
        ];
    }

    public function params_no_results($params)
    {
        return [
            'data' => $this->pass_error_to_template(
                $params,
                $this->template_error_no_item()
            ),
            'path' => 'components/notices/no',
            'name' => 'results',
        ];
    }

    public function params_wp_error($params, $wp_error)
    {
        return [
            'data' => $this->pass_error_to_template(
                $params,
                $wp_error->get_error_message()
            ),
            'path' => 'components/notices/no',
            'name' => 'results',
        ];
    }

    public function params_wrong_path($params)
    {
        return [
            'data' => $this->pass_error_to_template(
                $params,
                $this->template_error_wrong_path()
            ),
            'path' => 'components/notices/no',
            'name' => 'results',
        ];
    }

    public function pass_error_to_template(
        $params,
        $error_message = 'Unknown template error'
    ) {
        $error_data = [];

        if ($this->is_path_empty($params)) {
            $params['path'] = 'unknown_path';
        }

        if ($this->is_name_empty($params)) {
            $params['name'] = 'unknown_name';
        }

        $error_data['error'] = $error_message;
        $error_data['path'] = $params['path'];
        $error_data['name'] = $params['name'];

        return $error_data;
    }

    public function has_pre_render_method($params)
    {
        return method_exists(
            $this->{$params['pre_render']['class_name']},
            $params['pre_render']['method_name']
        );
    }

    public function pre_render_template($params)
    {
        return $this->{$params['pre_render']['class_name']}->{$params[
            'pre_render'
        ]['method_name']}($params);
    }

    public function pre_render_items($params)
    {
        $pre_rendered_items = [];

        foreach ($params['data'][$params['type']] as $item) {
            $new_params = $params;
            $new_params['data'][$params['type']] = $item;

            $reuslt = $this->pre_render_template($new_params);

            $pre_rendered_items[] = $reuslt['data'];
        }

        return $pre_rendered_items;
    }

    /*

    $params['data'] represents an data with the shape of:

    Array (
    [post_id] => Array
       (
             [0] => 18357
             [1] => 18366
       )
    )

    $params['data']['product'] Should always represent an array of objects

    */
    public function get_params_for_load($normalized_params, $params)
    {
        if (isset($normalized_params['products'])) {
            $params['data']['products'] = $normalized_params['products'];
        }

        $params['data'] = $this->pre_render_items($params);

        $user_params = $this->extract_user_params(
            $normalized_params,
            $params['type']
        );

        if ($this->is_showing_post_meta($user_params)) {
            $user_params['post_meta'] = get_post_meta(29682);
        }

        return [
            'params' => $params,
            'user_params' => $user_params,
        ];
    }

    public function is_skipping_required_data($params)
    {
        return isset($params['skip_required_data']) &&
            $params['skip_required_data'];
    }

    public function add_item_to_data($params, $item)
    {
        if ($params['type'] === 'products') {
            $type = 'product';
        } elseif ($params['type'] === 'collections') {
            $type = 'collection';
        } else {
            $type = $params['type'];
        }

        return [
            $type => $item,
        ];
    }

    public function is_combining($data)
    {
        if (!isset($data->combine)) {
            return false;
        }

        return $data->combine;
    }

    public function is_indexed_array($params)
    {
        return is_array($this->first_item_in_data($params));
    }

    public function collpase_duplicate_items($params, $item)
    {
        $copy = $params;
        $copy['data'] = $item;

        return $copy;
    }

    public function combine_with_user_params($params, $user_params, $item)
    {
        $hello = $this->add_item_to_data($params, $item);

        if ($params['type'] = 'products') {
            $user_params['product'] = $hello['product'];
        } elseif ($params['type'] = 'collections') {
            $user_params['collection'] = $hello['collection'];
        }

        $final_data = $user_params;

        return [
            'path' => $params['path'],
            'name' => $params['name'],
            'type' => $params['type'],
            'data' => $final_data,
        ];
    }

    /*

     Grabs first item in data array, false otherwise

     */
    public function first_item_in_data($params)
    {
        if (
            empty($params) ||
            !$this->is_data_set($params) ||
            !is_array($params['data'])
        ) {
            return false;
        }

        if (is_array($params['data']) && !isset($params['data'][0])) {
            return false;
        }

        return $params['data'][0];
    }

    public function add_post_meta_to_data($item, $user_params)
    {
        $stuff = array_map(function ($data) use ($user_params) {
            if (is_object($data) && isset($data->post_meta)) {
                $data->post_meta = $user_params['post_meta'];
            }

            if (is_array($data) && isset($data['post_meta'])) {
                $data['post_meta'] = $user_params['post_meta'];
            }

            return $data;
        }, $item['data']);

        $item['data'] = $stuff;

        return $item;
    }

    public function limit_data($data, $user_params)
    {
        return array_slice($data, 0, (int) $user_params['limit']);
    }

    public function order_data($data, $user_params)
    {
        $result = Utils_Sorting::sort_by($data, $user_params['orderby']);

        if ($user_params['order'] === 'desc') {
            $result = Utils_Sorting::reverse($result);
        }

        return $result;
    }

    /*

    Runs for all templates no matter the size

    */
    public function load_wrapper_templates($params, $user_params)
    {
        $results = [];

        $params_copy = $params;

        if (!empty($user_params['orderby'])) {
            $params['data'] = $this->order_data($params['data'], $user_params);
        }

        if (!empty($user_params['limit'])) {
            $params['data'] = $this->limit_data($params['data'], $user_params);
        }

        foreach ($params['data'] as $item) {
            $params_copy['data'] = $item;
            $results[] = $this->load_wrapper_template(
                $params_copy,
                $user_params
            );
        }

        return $results;
    }

    public function load_wrapper_template($params, $user_params)
    {
        $wrapper_params = [
            'Templates' => $this,
            'params' => $params,
            'user_params' => $user_params,
            'combine' => $params['combine'],
        ];

        return $this->Template_Loader
            ->set_template_data($wrapper_params)
            ->get_template_part('components/wrapper/wrapper-server');
    }

    public function has_many_wrappers($params)
    {
        return is_array($params['data']) && isset($params['data'][0]);
    }

    /*


     Load many wrapper

     */
    public function load_many_wrapper($params, $user_params)
    {
        // TODO: Not sure if we need this conditional anymore
        if ($this->has_many_wrappers($params)) {
            $results = $this->load_wrapper_templates($params, $user_params);
        } else {
            $results = $this->load_wrapper_template($params, $user_params);
        }

        return Utils::flatten_array($results);
    }

    /*

   Requires a data structure like this:

   [path] => components/products/add-to-cart/price
   [name] => one
   [type] => product
   [data] => Array
   (
   [price] => '<span></span>',

     */
    public function load_single_wrapper($params)
    {
        return $this->load_wrapper([
            'path' => $params['path'],
            'name' => isset($params['name']) ? $params['name'] : false,
            'type' => $params['type'],
            'data' => $params['data'],
        ]);
    }

    public function extract_user_params($params, $type)
    {
        $params_copy = $params;

        unset($params_copy[$type]);

        return $params_copy;
    }

    public function is_showing_post_meta($params)
    {
        if ($this->is_post_meta_empty($params)) {
            return false;
        }

        return true;
    }

    public function merge_with_default_template_params($params)
    {
        return wp_parse_args($params, $this->default_render_load_params());
    }

    /*

     2.0 Load

     UNDER TEST

     */
    public function load($params)
    {
        return $this->set_and_get_template(
            $this->params_client_render($params)
        );
    }

    public function get_type_wrapper($params)
    {
        return $params['data'][$params['type']];
    }

    public function has_type_wrapper($params)
    {
        if (!$this->is_data_type_array($params)) {
            return false;
        }

        return $this->is_type_wrapper_set($params);
    }

    public function has_single_item($params)
    {
        if (!$this->has_type_wrapper($params)) {
            if (
                !$this->is_data_type_array($params) ||
                count($params['data']) === 1
            ) {
                return true;
            } else {
                return false;
            }
        } elseif (
            is_array($this->get_type_wrapper($params)) &&
            count($this->get_type_wrapper($params)) === 1
        ) {
            return false;
        }
    }

    public function collapse_single_item($params)
    {
        if ($this->has_type_wrapper($params)) {
            $params['data'][$params['type']] = $this->get_type_wrapper(
                $params
            )[0];
        } else {
            if (is_array($params['data'])) {
                $params['data'] = $params['data'][0];
            }
        }

        return $params;
    }

    public function maybe_wrap_data_in_type_prop($params)
    {
        if (!$this->has_type_wrapper($params)) {
            if (!$this->is_data_type_array($params)) {
                $params_copy = $params;
                $params_copy['data'] = [];

                $params_copy['data'][$params['type']] = $params['data'];

                return $params_copy;
            }

            $params['data'][$params['type']] = $params['data'];
        }

        return $params;
    }

    /*

     2.0 Load a template

     $params['data'] must be set to a single object type like 'product'. Cannot be an array.

     UNDER TEST

     */
    public function load_wrapper($params)
    {
        $params = wp_parse_args(
            $params,
            $this->default_render_load_wrapper_params()
        );

        return $this->handle_template_errors($params, function () use (
            $params
        ) {
            // If 'data' only has one item in the array, remove array and add 'product'
            if ($this->has_single_item($params)) {
                $params = $this->collapse_single_item($params);
            }

            $params = $this->maybe_wrap_data_in_type_prop($params);

            // Load the template, checks for false if error loading
            $file_path = $this->set_and_get_template($params);

            if (!$file_path) {
                return $this->set_and_get_template(
                    $this->params_wrong_path($params)
                );
            }

            return $file_path;
        });
    }
}
