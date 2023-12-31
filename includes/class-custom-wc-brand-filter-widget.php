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
            'description' => __('Browter WooCommerce Brand Filter Widget', 'browter-woofilter'),
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
        $title = !empty($instance['title']) ? $instance['title'] : __('Brands', 'browter-woofilter');
        $taxonomy = 'product_brand';
        $terms = get_terms($taxonomy, array(
            'hide_empty' => true,
            'orderby' => 'name',
            'order' => 'ASC',
        ));

        echo $args['before_widget'];

        if ($title) {
            echo $args['before_title'] . apply_filters('widget_title', $title) . $args['after_title'];
        }

        echo '<ul>';

        foreach ($terms as $term) {
            $term_link = get_term_link($term, $taxonomy);

            if (is_wp_error($term_link)) {
                continue;
            }

            echo '<li><label><input type="checkbox" class="filter-checkbox" value="' . esc_attr($term->slug) . '"> ' . $term->name . '</label></li>';
        }

        echo '</ul>';
        echo $args['after_widget'];
    }

    /**
     * Output the widget options form on the admin side
     *
     * @param array $instance
     */
    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : '';
?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'browter-woofilter'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
<?php
    }

    /**
     * Update the widget options
     *
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = !empty($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
        return $instance;
    }
}
