<?php

namespace WP_Shopify;

use WP_Shopify\Utils;
use WP_Shopify\Options;

if (!defined('ABSPATH')) {
    exit();
}

class Updater
{
    public $plugin_settings;

    public function __construct($plugin_settings)
    {
        $this->plugin_settings = $plugin_settings;
    }

    /*

	check_for_updates

	*/
    public function check_for_updates($license_key)
    {
        /*

		This is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
		you should use your own CONSTANT name, and be sure to replace it throughout this file

		*/
        if (!defined('EDD_SL_STORE_URL')) {
            define('EDD_SL_STORE_URL', WP_SHOPIFY_PLUGIN_ENV);
        }

        // The name of your product. This should match the download name in EDD exactly
        if (!defined('EDD_SAMPLE_ITEM_ID')) {
            define('EDD_SAMPLE_ITEM_ID', 35);
        }

        // load our custom updater
        if (!class_exists('WP_Shopify_EDD_SL_Plugin_Updater')) {
            include WP_SHOPIFY_PLUGIN_DIR_PATH .
                'vendor/EDD/WP_Shopify_EDD_SL_Plugin_Updater.php';
        }

        // Setup the updater
        // Calls the init() function within the constructor
        return new \WP_Shopify_EDD_SL_Plugin_Updater(
            EDD_SL_STORE_URL,
            WP_SHOPIFY_BASENAME,
            [
                'version' => WP_SHOPIFY_NEW_PLUGIN_VERSION,
                'license' => $license_key,
                'item_name' => WP_SHOPIFY_PLUGIN_NAME_FULL,
                'item_id' => EDD_SAMPLE_ITEM_ID,
                'author' => WP_SHOPIFY_PLUGIN_NAME_FULL,
                'url' => home_url(),
                'beta' => $this->plugin_settings['general']['enable_beta'],
            ]
        );
    }

    public function init_updates()
    {
        if (
            empty($this->plugin_settings) ||
            empty($this->plugin_settings['license'])
        ) {
            return;
        }

        $license_status = $this->plugin_settings['license']['license'];
        $license_key = $this->plugin_settings['license']['license_key'];

        if ($license_status === 'valid') {
            $this->check_for_updates($license_key);
        }
    }

    /*

	Init

	*/
    public function init()
    {
    }
}
