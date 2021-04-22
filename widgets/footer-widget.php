<?php

class Footer_Widget extends WP_Widget
{
    function __construct()
    {
        parent::__construct(
            'footer-widget',
            'Footer Widget'
        );
    }

    public function widget($args, $instance)
    {
        echo
            '<div class="footer-widget">' .
                '<div class="grid">' .
                    '<div class="grid-item">' .
                        '<p>' . $instance['text'] . '</p>' .
                    '</div>' .
                    '<div class="grid-item">' .
                        '<div class="button-container">' .
                            '<a class="btn btn-light" href="' . $instance['join_url'] . '">' .
                                'Join Us' .
                            '</a>' .
                            '<a class="btn btn-outline-light" href="' . $instance['donate_url'] . '">' .
                                'Donate' .
                            '</a>' .
                        '</div>' .
                    '</div>' .
                '</div>' .
            '</div>';
    }

    public function form($instance)
    {
        $text = !empty($instance['text']) ? $instance['text'] : esc_html__('', 'text_domain');
        $join_url = !empty($instance['join_url']) ? $instance['join_url'] : esc_html__('', 'text_domain');
        $donate_url = !empty($instance['donate_url']) ? $instance['donate_url'] : esc_html__('', 'text_domain');
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('text')); ?>"><?php echo esc_html__('Text:', 'text_domain'); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('text')); ?>"
                      name="<?php echo esc_attr($this->get_field_name('text')); ?>" type="text" cols="30"
                      rows="10"><?php echo esc_attr($text); ?></textarea>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('join_url')); ?>"><?php echo esc_html__('Join URL:', 'text_domain'); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('join_url')); ?>"
                      name="<?php echo esc_attr($this->get_field_name('join_url')); ?>" type="text" cols="30"
                      rows="10"><?php echo esc_attr($join_url); ?></textarea>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('donate_url')); ?>"><?php echo esc_html__('Donate URL:', 'text_domain'); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('donate_url')); ?>"
                      name="<?php echo esc_attr($this->get_field_name('donate_url')); ?>" type="text" cols="30"
                      rows="10"><?php echo esc_attr($donate_url); ?></textarea>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['text'] = (!empty($new_instance['text'])) ? $new_instance['text'] : '';
        $instance['join_url'] = (!empty($new_instance['join_url'])) ? $new_instance['join_url'] : '';
        $instance['donate_url'] = (!empty($new_instance['donate_url'])) ? $new_instance['donate_url'] : '';

        return $instance;
    }
}

wp_enqueue_style( 'footer-widget', get_stylesheet_directory_uri() . '/widgets/footer-widget.css' );
register_sidebar( array(
    'name'          => __( 'Footer Widget Sidebar', '_s' ),
    'id'            => 'footer-widget-sidebar',
    'before_widget' => '<section id="%1$s" class="footer-widget">',
    'after_widget'  => '</section>',
));
register_widget('Footer_Widget');
?>
