<?php

/**
 * Custom WooCommerce Sort By Widget
 */
class Custom_WC_SortBy_Widget extends WP_Widget
{
    /**
     * Widget constructor
     */
    public function __construct()
    {
        $widget_ops = array(
            'classname'   => 'custom_wc_sortby_widget',
            'description' => __('Custom WooCommerce Sort By Widget', 'browter-woofilter'),
        );
        parent::__construct('custom_wc_sortby_widget', __('Sort By', 'browter-woofilter'), $widget_ops);
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

        echo $args['before_widget'];

        echo $args['before_title'] . apply_filters('widget_title', __('Sort By', 'browter-woofilter')) . $args['after_title'];

        echo '<select id="sort-by" class="sortby-select">';
        echo '<option value="menu_order">' . __('Default Sorting', 'browter-woofilter') . '</option>';
        echo '<option value="popularity">' . __('Popularity', 'browter-woofilter') . '</option>';
        echo '<option value="rating">' . __('Average Rating', 'browter-woofilter') . '</option>';
        echo '<option value="date">' . __('Newness', 'browter-woofilter') . '</option>';
        echo '<option value="price">' . __('Price: Low to High', 'browter-woofilter') . '</option>';
        echo '<option value="price-desc">' . __('Price: High to Low', 'browter-woofilter') . '</option>';
        echo '</select>';

        echo $args['after_widget'];
    }
}
