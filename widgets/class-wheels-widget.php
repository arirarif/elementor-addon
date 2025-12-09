<?php
if (!defined('ABSPATH')) {
    exit;
}

class Wheels_Elementor_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'wheels_widget';
    }

    public function get_title() {
        return __('Tire Filter Widget', 'wheels-elementor-widgets');
    }

    public function get_icon() {
        return 'eicon-circle-o';
    }

    public function get_categories() {
        return ['wheels-category'];
    }

    public function get_keywords() {
        return ['tires', 'wheels', 'filter', 'search'];
    }

    protected function register_controls() {
        // General Settings
        $this->start_controls_section(
            'general_section',
            [
                'label' => __('General Settings', 'wheels-elementor-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __('Button Text', 'wheels-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Find Tires', 'wheels-elementor-widgets'),
            ]
        );

        $this->add_control(
            'background_image',
            [
                'label' => __('Background Image', 'wheels-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
            ]
        );

        $this->end_controls_section();

        // Filter Fields - Season, Width, Diameter
        $this->start_controls_section(
            'filter_section',
            [
                'label' => __('Filter Fields', 'wheels-elementor-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // Season field name
        $this->add_control(
            'season_field',
            [
                'label' => __('Season Field Name', 'wheels-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'season',
                'description' => __('ACF field name for Season', 'wheels-elementor-widgets'),
            ]
        );

        $this->add_control(
            'season_label',
            [
                'label' => __('Season Label', 'wheels-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Season',
            ]
        );

        // Width field name
        $this->add_control(
            'width_field',
            [
                'label' => __('Width Field Name', 'wheels-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'width',
                'description' => __('ACF field name for Width', 'wheels-elementor-widgets'),
            ]
        );

        $this->add_control(
            'width_label',
            [
                'label' => __('Width Label', 'wheels-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Width',
            ]
        );

        // Diameter field name
        $this->add_control(
            'diameter_field',
            [
                'label' => __('Diameter Field Name', 'wheels-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'diameter',
                'description' => __('ACF field name for Diameter', 'wheels-elementor-widgets'),
            ]
        );

        $this->add_control(
            'diameter_label',
            [
                'label' => __('Diameter Label', 'wheels-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Diameter',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        // Get field names
        $season_field = $settings['season_field'] ?: 'season';
        $width_field = $settings['width_field'] ?: 'width';
        $diameter_field = $settings['diameter_field'] ?: 'diameter';

        // Get values from products
        $season_values = $this->get_field_values($season_field);
        $width_values = $this->get_field_values($width_field);
        $diameter_values = $this->get_field_values($diameter_field);

        // Labels
        $season_label = $settings['season_label'] ?: 'Season';
        $width_label = $settings['width_label'] ?: 'Width';
        $diameter_label = $settings['diameter_label'] ?: 'Diameter';
        $button_text = $settings['button_text'] ?: 'Find Tires';
        $bg_image = $settings['background_image']['url'] ?? '';

        // Get shop URL
        $shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop/');
        ?>

        <div class="wheels-wrapper" <?php echo $bg_image ? 'style="background-image: url(\'' . esc_url($bg_image) . '\')"' : ''; ?>>
            <div class="wheels-block">
                <form class="wheels-form" id="wheels-filter-form" action="<?php echo esc_url($shop_url); ?>" method="GET">

                    <!-- Season -->
                    <div class="wheels-param">
                        <label for="wheels-season" class="wheels-param__label"><?php echo esc_html($season_label); ?></label>
                        <select id="wheels-season" name="meta_<?php echo esc_attr($season_field); ?>" class="wheels-param__input">
                            <option value=""><?php esc_html_e('Select', 'wheels-elementor-widgets'); ?></option>
                            <?php foreach ($season_values as $value): ?>
                                <option value="<?php echo esc_attr($value); ?>"><?php echo esc_html($value); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Width -->
                    <div class="wheels-param">
                        <label for="wheels-width" class="wheels-param__label"><?php echo esc_html($width_label); ?></label>
                        <select id="wheels-width" name="meta_<?php echo esc_attr($width_field); ?>" class="wheels-param__input">
                            <option value=""><?php esc_html_e('Select', 'wheels-elementor-widgets'); ?></option>
                            <?php foreach ($width_values as $value): ?>
                                <option value="<?php echo esc_attr($value); ?>"><?php echo esc_html($value); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Diameter -->
                    <div class="wheels-param">
                        <label for="wheels-diameter" class="wheels-param__label"><?php echo esc_html($diameter_label); ?></label>
                        <select id="wheels-diameter" name="meta_<?php echo esc_attr($diameter_field); ?>" class="wheels-param__input">
                            <option value=""><?php esc_html_e('Select', 'wheels-elementor-widgets'); ?></option>
                            <?php foreach ($diameter_values as $value): ?>
                                <option value="<?php echo esc_attr($value); ?>"><?php echo esc_html($value); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <input type="hidden" name="post_type" value="product">

                    <footer class="wheels-footer">
                        <button type="submit" class="wheels-button">
                            <?php echo esc_html($button_text); ?>
                        </button>
                    </footer>
                </form>
            </div>
        </div>

        <script type="text/javascript">
        (function() {
            // Remove empty parameters before submit
            document.getElementById('wheels-filter-form').addEventListener('submit', function(e) {
                var selects = this.querySelectorAll('select');
                selects.forEach(function(select) {
                    if (!select.value) {
                        select.removeAttribute('name');
                    }
                });
            });
        })();
        </script>
        <?php
    }

    /**
     * Get unique field values from WooCommerce products
     */
    private function get_field_values($field_name) {
        global $wpdb;

        if (empty($field_name)) {
            return [];
        }

        $values = [];

        // Get unique values from WooCommerce products
        $results = $wpdb->get_col($wpdb->prepare(
            "SELECT DISTINCT pm.meta_value
             FROM {$wpdb->postmeta} pm
             INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
             WHERE pm.meta_key = %s
             AND pm.meta_value != ''
             AND pm.meta_value IS NOT NULL
             AND p.post_type = 'product'
             AND p.post_status = 'publish'
             ORDER BY pm.meta_value ASC",
            $field_name
        ));

        if (!empty($results)) {
            foreach ($results as $value) {
                if (empty($value) || $value === '0') {
                    continue;
                }

                // Handle serialized arrays
                if (is_serialized($value)) {
                    $unserialized = maybe_unserialize($value);
                    if (is_array($unserialized)) {
                        foreach ($unserialized as $v) {
                            if (!empty($v) && !in_array($v, $values)) {
                                $values[] = $v;
                            }
                        }
                    }
                } else {
                    if (!in_array($value, $values)) {
                        $values[] = $value;
                    }
                }
            }
        }

        // Sort naturally
        sort($values, SORT_NATURAL);

        return $values;
    }
}
