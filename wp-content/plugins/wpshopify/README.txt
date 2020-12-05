=== WP Shopify ===
Contributors: andrewmrobbins
Donate link: https://wpshop.io/purchase/
Tags: shopify, ecommerce, store, sell, products, shop, purchase, buy
Requires at least: 5.4
Requires PHP: 5.6
Tested up to: 5.5
Stable tag: trunk
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Sell and build custom Shopify experiences on WordPress.

== Description ==

WP Shopify allows you to sell your [Shopify](https://www.shopify.com/?ref=wps&utm_content=links&utm_medium=website&utm_source=wpshopify) products on any WordPress site. Your store data is synced as custom post types giving you the ability to utilize the full power of native WordPress functionality. On the front-end we use the [Shopify Buy Button](https://www.shopify.com/?ref=wps&utm_content=links&utm_medium=website&utm_source=wpshopify) to create an easy to use cart experience without the use of any iFrames.

= Features =
* Sync your products and collections as native WordPress post
* Templates
* No iFrames
* Over 100+ actions and filters allowing you to customize any part of the storefront
* Display your products using custom pages and shortcodes
* Built-in cart experience using [Shopify's Buy Button](https://www.shopify.com/?ref=wps&utm_content=links&utm_medium=website&utm_source=wpshopify)
* SEO optimized
* Advanced access to your Shopify data saved in custom database tables

See the [full list of features here](https://wpshop.io/how/)

https://www.youtube.com/watch?v=v3AC2SPK40o

= WP Shopify Pro =
WP Shopify is also available in a Pro version which includes 80+ Templates, Automatic Syncing, Order and Customer Data, Cross-domain Tracking, Live Support, and much more functionality! [Learn more](https://wpshop.io/purchase)

= We want to hear from you! (Get 10% off WP Shopify Pro) =
Our next short-term goal is to clearly define the [WP Shopify roadmap](https://www.surveymonkey.com/r/3P55HXX). A crucial part of this process is learning from you! We'd love to get your feedback in a [short three question survey](https://www.surveymonkey.com/r/3P55HXX).

The questions are surrounding: 
- How you're using WP Shopify
- What problems you're solving by using the plugin
- What you like the most about the plugin

To show our appreciation, we'll send you a 10% off discount code that will work for any new purchases or renewals of WP Shopify Pro. Just add your email toward the bottom. Thanks! 🙏

[Take the WP Shopify user survey](https://www.surveymonkey.com/r/3P55HXX)

= Links =
* [Website](https://wpshop.io/)
* [Documentation](https://docs.wpshop.io)
* [WP Shopify Pro](https://wpshop.io/purchase)


== Installation ==
From your WordPress dashboard

1. Visit Plugins > Add New
2. Search for *WP Shopify*
3. Activate WP Shopify from your Plugins page
4. Create a [Shopify private app](https://docs.wpshop.io). More [info here](https://help.shopify.com/manual/apps/private-apps)
5. Back in WordPress, click on the menu item __WP Shopify__ and begin syncing your Shopify store to WordPress.
6. We've created a [guide](https://docs.wpshop.io) if you need help during the syncing process

== Screenshots ==
[https://wpshop.io/screenshots/1-syncing-cropped.jpg  Easy and fast syncing process]
[https://wpshop.io/screenshots/2-settings-cropped.jpg  Many settings and options to choose from]
[https://wpshop.io/screenshots/3-posts-cropped.jpg  Sync your store as native WordPress posts]


== Frequently Asked Questions ==

Read the [full list of FAQ](https://wpshop.io/faq/)

= How does this work? =
You can think of WordPress as the frontend and [Shopify](https://www.shopify.com/?ref=wps&utm_content=links&utm_medium=website&utm_source=wpshopify) as the backend. You manage your store (add products, change prices, etc) from within Shopify and those changes sync into WordPress. WP Shopify also allows you to sell your products and is bundled with a cart experience using the [Shopify Buy Button SDK](https://www.shopify.com/?ref=wps&utm_content=links&utm_medium=website&utm_source=wpshopify).

After installing the plugin you connect your Shopify store to WordPress by filling in your Shopify API keys. After syncing, you can display / sell your products in various ways such as:

1. Using the default pages “yoursite.com/products” and “yoursite.com/collections“
2. Shortcodes [wps_products] and [wps_collections]

We also save your Shopify products as Custom Post Types enabling you to harness the native power of WordPress.

= Doesn’t Shopify already have a WordPress plugin? =
Technically yes but it [has been discontinued](https://wptavern.com/shopify-discontinues-its-official-plugin-for-wordpress).

Shopify has instead moved attention to their [Buy Button](https://www.shopify.ca/buy-button) which is an open-source library that allows you to embed products with snippets of HTML and JavaScript. The main drawback to this is that Shopify uses iFrames for the embeds which limit the ability for layout customizations.

WP Shopify instead uses a combination of the Buy Button and Shopify API to create an iFrame-free experience. This gives allows you to sync Shopify data directly into WordPress. We also save the products and collections as Custom Post Types which unlocks the native power of WordPress.

= Is this SEO friendly? =
We’ve gone to great lengths to ensure we’ve conformed to all the SEO best practices including semantic alt text, Structured Data, and indexable content.

= Does this work with third party Shopify apps? =
Unfortunately no. We rely on the main Shopify API which doesn’t expose third-party app data. However the functionality found in many of the Shopify apps can be reproduced by other WordPress plugins.

= How do I display my products? =
Documentation on how to display your products can be [found here](https://docs.wpshop.io/#/getting-started/displaying).

= How does the checkout process work? =
WP Shopify does not handle any portion of the checkout process. When a customer clicks the checkout button within the cart, they’re redirected to the default Shopify checkout page to finish the process. The checkout page is opened in a new tab.

More information on the Shopify checkout process can be [found here](https://help.shopify.com/manual/sell-online/checkout-settings).

= Does this work with Shopify's Lite plan? =
Absolutely! In fact this is our recommendation if you intend to only sell on WordPress. More information on Shopify's [Lite plan](https://www.shopify.com/lite)


== Changelog ==
Our full changelog can be [found here](https://wpshop.io/changelog/)

= 3.2.0 =
- **Fixed:** Bug in `get_products_by_collection_id()` causing incorrect collection ID to be used
- **Fixed:** The default products page was using the wrong post ID
- **Fixed:** Issue causing block-specific assets to load on the front-end
- **Fixed:** Added `rest_get_url_prefix` to bootstrap process to prevent 404s
- **Improved:** Noticeably reduced load times when many other plugins activated
- **Improved:** Removed a ton of dead code
- **Improved:** Added logo icon to block categories 
- **Improved:** Made Product component CSS more compatible with WordPress themes
- **Improved:** Removed additional third-party notices on WP Shopify settings pages
- **Dev:** Added new JS action `wpshopify.render`
- **Dev:** Added new PHP filter: `wpshopify_webhooks_callback_url`
- **Dev:** Added new JS filter: `misc.link.href`
- **Dev:** Added WordPress `5.5` support

= 3.1.2 =
- **Fixed:** Storefront styles
- **Fixed:** Bug causing broken JS filter `cart.lineItems.link`
- **Fixed:** Missing styles for tags when using the Storefront component 
- **Fixed:** Bug causing sold out label to show when no featured image exists
- **Fixed:** Missing PHP function `get_product_ids_from_vendors`
- **Fixed:** Bug causing missing Storefront types 
- **Fixed:** Bug causing missing prices when set to $0
- **Fixed:** Missing range "compare at" prices
- **Improved:** Added missing `permission_callback` to some REST endpoints

= 3.1.1 =
- **Feature:** Added ability to show products by `post_id`
- **Feature:** Added sold out label to feature images when product is out of stock
- **Fixed:** Bug causing broken product single pages under some circumstances
- **Improved:** Added missing notice styles when no items are found
- **Improved:** Added ability to toggle page content of default products page
- **Improved:** Now centering the default PLP heading
- **Improved:** German translation of the begin checkout button
- **Dev:** Added PHP filter: `wps_products_all_show_post_content`

= 3.1.0 =
- **Fixed:** Bug causing settings to revert when toggling between tabs
- **Fixed:** Bug causing duplicated queries for enable_default_pages
- **Fixed:** Bug causing version number mismatches which prevented tables from updating when migrating to version 3.0
- **Fixed:** Bug hiding the default products and collections heading set within the plugin settings
- **Fixed:** Bug causing image zoom to fail on mobile, when using the storefront shortcode or in blocks
- **Fixed:** Bug in [wps_products_buy_button] shortcode causing missing add to cart button
- **Fixed:** Bug causing product urls to break due to missing forward slash "/"
- **Fixed:** Bug in product and collection single templates causing duplicate data to show
- **Fixed:** Bug causing the PHP filter wps_products_single_args to break template overrides
- **Fixed:** Bug with Render API not honoring the excludes attribute or any other properties
- **Fixed:** Error Argument 'first' on Field 'collections' has an invalid value
- **Fixed:** Bug causing API error when domain has bad characters
- **Improved:** Mobile styles
- **Improved:** Added special placeholder loaders for Search and Storefront component
- **Improved:** Moved remaining external CSS into the JS components
- **Improved:** Error handling when invalid or missing API keys
- **Dev:** Updated dev dependencies

= 3.0.8 =
- **Fixed:** Collection pages not linking to products 
- **Fixed:** Bug causing shortcodes to not be parsed on default product pages
- **Fixed:** Bug causing product links to break when linking directly to Shopify
- **Fixed:** Preventing placeholder loader styles from becoming too narrow
- **Fixed:** Unnecessary spacing when using the align height feature
- **Fixed:** Bug causing loading animation to not run
- **Updated:** Collections listing page will now link collections in same tab / window
- **Improved:** Added max width to remove discount icon
- **Improved:** Overall layout of Collection pages
- **Improved:** Moved more external styles into JS components
- **Improved:** The UI of the cart checkout button is snappier
- **Dev:** added JS filter `misc.layout.containerWidth`

= 3.0.7 =
- **Fixed:** Bug causing third-party plugin notices to show on plugin's free version admin pages
- **Fixed:** Spacing issues in the product components 
- **Updated:** Bumped WordPress version requirement
- **Improved:** Removed _t import
- **Improved:** useHook function
- **Dev:** Updated JS dependencies