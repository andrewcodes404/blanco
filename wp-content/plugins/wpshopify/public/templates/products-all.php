<?php

defined('ABSPATH') ?: die();

get_header('wpshopify');

$Products = WP_Shopify\Factories\Render\Products\Products_Factory::build();
$Settings = WP_Shopify\Factories\DB\Settings_General_Factory::build();

$post_id = $Settings->get_col_value('page_products');
$is_showing_heading = $Settings->get_col_value('products_heading_toggle');

$description_toggle = $Settings->get_col_value(
    'products_plp_descriptions_toggle'
);

if (!$description_toggle) {
    $products_args = [
        'excludes' => ['description'],
    ];
} else {
    $products_args = [];
}
?>

<style>
   .wps-breadcrumbs {
      max-width: 1100px;
      margin: 0 auto;
   }

   .wps-breadcrumbs-name {
         text-transform: capitalize;
      }

   .wps-products-wrapper {
      display: flex;
   }

   .wps-products-content {
      flex: 1;
   }

   .wps-products-sidebar {
      width: 30%;
   }

   .wps-heading {
      text-align: center;
   }
</style>

<section class="wps-products-wrapper wps-container">

   <div class="wps-products-content">
      
   <?php if ($is_showing_heading) { ?>

      <header class="wps-products-header">
         <h1 class="wps-heading">
            <?= apply_filters(
                'wps_products_all_title',
                $Settings->get_col_value('products_heading')
            ) ?>
         </h1>
      </header>

   <?php } ?>
      
      <?= do_action('wps_breadcrumbs') ?>

         <div class="wps-products-all">
            <?php $Products->products(
                apply_filters('wps_products_all_args', $products_args)
            ); ?>
         </div>

         <?php if (
             apply_filters('wps_products_all_show_post_content', true)
         ) { ?>
         <section class="wps-page-content">
            <?= do_shortcode(
                apply_filters(
                    'wps_products_all_content',
                    get_post_field('post_content', $post_id)
                )
            ) ?>
         </section>
      <?php } ?>

   </div>

   <?php if (apply_filters('wps_products_show_sidebar', false)) { ?>
      <div class="wps-sidebar wps-products-sidebar">
         <?= get_sidebar('wpshopify') ?>
      </div>
   <?php } ?>

</section>

<?php get_footer('wpshopify');
