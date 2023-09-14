<?php

/**
 * Plugin Name: Woo Filter
 * Description: WooCommerce Custom Product Filter
 * Author URI:  https://greatkhanjoy.browter.com
 * Plugin URI:  https://browter.com/woo-filter
 * Version:     1.0.0
 * Author:      Imran Hosein Khan Joy
 * Text Domain: browter-woofilter
 * Domain Path: /i18n
 */

// Register the custom widget
add_action('widgets_init', 'register_custom_wc_brand_filter_widget');
function register_custom_wc_brand_filter_widget()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-custom-wc-brand-filter-widget.php';
    register_widget('Custom_WC_Brand_Filter_Widget');

    require_once plugin_dir_path(__FILE__) . 'includes/class-custom-wc-sortby-widget.php';
    register_widget('Custom_WC_SortBy_Widget');
}

// Add "product_brand" taxonomy to WooCommerce
add_action('init', 'add_product_brand_taxonomy');
function add_product_brand_taxonomy()
{
    $labels = array(
        'name'              => __('Product Brands', 'browter-woofilter'),
        'singular_name'     => __('Product Brand', 'browter-woofilter'),
        'search_items'      => __('Search Brands', 'browter-woofilter'),
        'all_items'         => __('All Brands', 'browter-woofilter'),
        'parent_item'       => __('Parent Brand', 'browter-woofilter'),
        'parent_item_colon' => __('Parent Brand:', 'browter-woofilter'),
        'edit_item'         => __('Edit Brand', 'browter-woofilter'),
        'update_item'       => __('Update Brand', 'browter-woofilter'),
        'add_new_item'      => __('Add New Brand', 'browter-woofilter'),
        'new_item_name'     => __('New Brand Name', 'browter-woofilter'),
        'menu_name'         => __('Brands', 'browter-woofilter'),
    );

    $args = array(
        'labels'            => $labels,
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'     => false,
    );

    register_taxonomy('product_brand', 'product', $args);
}

// Enqueue filter scripts
add_action('wp_enqueue_scripts', 'enqueue_filter_scripts');
function enqueue_filter_scripts()
{
    wp_enqueue_script('filter-scripts', plugin_dir_url(__FILE__) . 'js/filter-scripts.js', array('jquery'), '1.0.0', true);
    wp_localize_script('filter-scripts', 'filter_ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}

// AJAX handler for filtering products
add_action('wp_ajax_filter_products', 'filter_products');
add_action('wp_ajax_nopriv_filter_products', 'filter_products');
// Add this code in your plugin or theme's PHP file 
function filter_products()
{
    $brands = $_POST['brands'];
    $sortby = $_POST['sort_by'];
    $offset = isset($_POST['offset']) ? $_POST['offset'] : 0;



    $defaltarg  = array(
        'post_type' => 'product',
        'orderby' => $sortby,
        'posts_per_page' => 3, // Default number of products to load
        'offset' => $offset, // Use the offset parameter

    );

    $args = array(
        'post_type' => 'product',
        'tax_query' => array(
            array(
                'taxonomy' => 'product_brand',
                'field'    => 'slug',
                'terms'    => $brands,
            ),
        ),
        'orderby' => $sortby,
        'posts_per_page' => 3, // Default number of products to load
        'offset' => $offset, // Use the offset parameter
    );
    if (empty($brands) || empty($brands[0])) {
        $query = new WP_Query($defaltarg);
    } else {
        $query = new WP_Query($args);
    }
    // $query = new WP_Query($args);
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            wc_get_template_part('content', 'product');
        }
    }
    wp_reset_postdata();
    die();
}
