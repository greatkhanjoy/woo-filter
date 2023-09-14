<?php

/**
 * Custom WooCommerce Brand Filter Widget
 */
class Custom_WC_Brand_Filter_Widget extends WP_Widget
{
    /**
     * Widget constructor
     */
    public function __construct()
    {
        $widget_ops = array(
            'classname'   => 'custom_wc_brand_filter_widget',
            'description' => __('Custom WooCommerce Brand Filter Widget', 'browter-woofilter'),
        );
        parent::__construct('custom_wc_brand_filter_widget', __('Brand Filter', 'browter-woofilter'), $widget_ops);
    }

    /**
     * Output the widget content
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance)
    {
        if (!is_shop() && !is_product_taxonomy()) {
            return;
        }

        $taxonomy = 'product_brand';
        $terms = get_terms($taxonomy, array(
            'hide_empty' => true,
        ));

        echo $args['before_widget'];

        if (!empty($terms)) {
            echo $args['before_title'] . apply_filters('widget_title', __('Brands', 'browter-woofilter')) . $args['after_title'];
            echo '<ul>';

            foreach ($terms as $term) {
                $term_link = get_term_link($term, $taxonomy);
                if (is_wp_error($term_link)) {
                    continue;
                }

                echo '<li><label><input type="checkbox" class="filter-checkbox" value="' . esc_attr($term->slug) . '"> ' . $term->name . '</label></li>';
            }

            echo '</ul>';
        }

        echo $args['after_widget'];
    }
}
