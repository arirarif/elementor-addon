<?php
if (!defined('ABSPATH')) {
    exit;
}

class Wheels_Elementor_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'wheels_widget';
    }
    
    public function get_title() {
        return __('Блок настройки колёс', 'wheels-elementor-widgets');
    }
    
    public function get_icon() {
        return 'eicon-circle-o';
    }
    
    public function get_categories() {
        return ['wheels-category'];
    }
    
    public function get_keywords() {
        return ['колеса', 'шины', 'подбор', 'wheels', 'tires'];
    }
    
    protected function register_controls() {
        $this->register_general_controls();
        $this->register_season_controls();
        $this->register_transport_controls();
    }
    
    protected function register_general_controls() {
        $this->start_controls_section(
            'general_section',
            [
                'label' => __('Основные настройки', 'wheels-elementor-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'button_text',
            [
                'label' => __('Текст кнопки', 'wheels-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('LASS UNS FINDEN!', 'wheels-elementor-widgets'),
                'placeholder' => __('Введите текст кнопки', 'wheels-elementor-widgets'),
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function register_season_controls() {
        $this->start_controls_section(
            'season_section',
            [
                'label' => __('Настройки сезона', 'wheels-elementor-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $repeater = new \Elementor\Repeater();
        
        $repeater->add_control(
            'season_button',
            [
                'label' => __('Сезон', 'wheels-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'summer' => __('Лето', 'wheels-elementor-widgets'),
                    'autumn' => __('Осень', 'wheels-elementor-widgets'),
                    'winter' => __('Зима', 'wheels-elementor-widgets'),
                ],
                'default' => 'summer',
            ]
        );
        
        $repeater->add_control(
            'season_value',
            [
                'label' => __('Slug категории', 'wheels-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('sommerreifen', 'wheels-elementor-widgets'),
                'description' => __('Введите slug категории для этого сезона', 'wheels-elementor-widgets'),
            ]
        );
        
        $this->add_control(
            'season_mapping',
            [
                'label' => __('Сопоставление сезонов', 'wheels-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ season_button }}} -> {{{ season_value }}}',
                'default' => [
                    [
                        'season_button' => 'summer',
                        'season_value' => 'sommerreifen',
                    ],
                    [
                        'season_button' => 'winter', 
                        'season_value' => 'winterreifen',
                    ]
                ],
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function register_transport_controls() {
        $this->start_controls_section(
            'transport_section',
            [
                'label' => __('Типы транспорта', 'wheels-elementor-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $attribute_options = $this->get_all_attribute_options();
        $repeater = new \Elementor\Repeater();
        
        $repeater->add_control(
            'transport_name',
            [
                'label' => __('Название типа', 'wheels-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Auto',
                'placeholder' => __('Например, Автомобиль', 'wheels-elementor-widgets'),
            ]
        );
        
        $repeater->add_control(
            'transport_slug',
            [
                'label' => __('Слаг типа', 'wheels-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'car',
                'placeholder' => __('Например, car', 'wheels-elementor-widgets'),
            ]
        );
        
        $repeater->add_control(
            'transport_icon',
            [
                'label' => __('SVG иконка', 'wheels-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::CODE,
                'language' => 'html',
                'default' => '',
                'description' => __('Вставьте SVG код иконки', 'wheels-elementor-widgets'),
            ]
        );
        
        $repeater->add_control(
            'transport_image',
            [
                'label' => __('Фоновое изображение', 'wheels-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );
        
        $repeater->add_control(
            'width_attribute',
            [
                'label' => __('Атрибут ширины', 'wheels-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $attribute_options,
                'default' => 'pa_width',
                'description' => __('Выберите атрибут WooCommerce для ширины шины', 'wheels-elementor-widgets'),
            ]
        );
        
        $repeater->add_control(
            'height_attribute',
            [
                'label' => __('Атрибут высоты', 'wheels-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $attribute_options,
                'default' => 'pa_height',
                'description' => __('Выберите атрибут WooCommerce для высоты профиля', 'wheels-elementor-widgets'),
            ]
        );
        
        $repeater->add_control(
            'radius_attribute',
            [
                'label' => __('Атрибут радиуса', 'wheels-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $attribute_options,
                'default' => 'pa_diameter',
                'description' => __('Выберите атрибут WooCommerce для диаметра колеса', 'wheels-elementor-widgets'),
            ]
        );
        
        $this->add_control(
            'transport_types',
            [
                'label' => __('Типы транспорта', 'wheels-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ transport_name }}}',
                'default' => [
                    [
                        'transport_name' => 'Auto',
                        'transport_slug' => 'car',
                        'transport_icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M5 11l1.5-4.5h11L19 11m-1.5 5a1.5 1.5 0 0 1-1.5-1.5a1.5 1.5 0 0 1 1.5-1.5a1.5 1.5 0 0 1 1.5 1.5a1.5 1.5 0 0 1-1.5 1.5m-11 0A1.5 1.5 0 0 1 5 14.5A1.5 1.5 0 0 1 6.5 13A1.5 1.5 0 0 1 8 14.5A1.5 1.5 0 0 1 6.5 16M18.92 6c-.2-.58-.76-1-1.42-1h-11c-.66 0-1.22.42-1.42 1L3 12v8a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1v-1h12v1a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1v-8l-2.08-6z"/></svg>',
                        'width_attribute' => 'pa_width',
                        'height_attribute' => 'pa_height', 
                        'radius_attribute' => 'pa_diameter',
                    ]
                ],
            ]
        );
        
        $this->end_controls_section();
    }
    
    private function get_all_attribute_options() {
        $attributes = ['' => __('Выберите атрибут', 'wheels-elementor-widgets')];

        // Add ACF fields section
        $acf_fields = $this->get_acf_fields();
        if (!empty($acf_fields)) {
            foreach ($acf_fields as $field_name => $field_label) {
                $attributes['acf_' . $field_name] = $field_label . ' (ACF: ' . $field_name . ')';
            }
        }

        // Add WooCommerce attributes
        if (function_exists('wc_get_attribute_taxonomies')) {
            $wc_attributes = wc_get_attribute_taxonomies();
            foreach ($wc_attributes as $attribute) {
                $taxonomy_name = 'pa_' . $attribute->attribute_name;
                $attributes[$taxonomy_name] = $attribute->attribute_label . ' (WC: pa_' . $attribute->attribute_name . ')';
            }
        }

        // Fallback options if nothing found
        if (count($attributes) === 1) {
            $attributes['acf_season'] = __('Season (ACF: season)', 'wheels-elementor-widgets');
            $attributes['acf_width'] = __('Width (ACF: width)', 'wheels-elementor-widgets');
            $attributes['acf_diameter'] = __('Diameter (ACF: diameter)', 'wheels-elementor-widgets');
        }

        return $attributes;
    }

    private function get_acf_fields() {
        $fields = [];

        // Check if ACF is active
        if (!function_exists('acf_get_field_groups')) {
            // Fallback: return common field names for Tire Filters
            return [
                'season' => __('Season', 'wheels-elementor-widgets'),
                'width' => __('Width', 'wheels-elementor-widgets'),
                'diameter' => __('Diameter', 'wheels-elementor-widgets'),
            ];
        }

        // Get all ACF field groups
        $field_groups = acf_get_field_groups();

        foreach ($field_groups as $group) {
            $group_fields = acf_get_fields($group['key']);
            if ($group_fields) {
                foreach ($group_fields as $field) {
                    // Only include select, text, number fields that make sense for filtering
                    if (in_array($field['type'], ['select', 'text', 'number', 'radio', 'checkbox'])) {
                        $fields[$field['name']] = $field['label'];
                    }
                }
            }
        }

        return $fields;
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        if (empty($settings['transport_types'])) {
            $this->render_empty_state();
            return;
        }
        
        $this->render_widget($settings);
    }
    
    private function render_empty_state() {
        ?>
        <div class="wheels-empty-state">
            <p><?php esc_html_e('Добавьте типы транспорта в настройках виджета', 'wheels-elementor-widgets'); ?></p>
        </div>
        <?php
    }
    
    private function render_widget($settings) {
        $button_text = $settings['button_text'];
        $transport_types = $settings['transport_types'];
        $season_mapping = $settings['season_mapping'] ?? [];
        
        // Получаем данные для первого транспорта по умолчанию
        $default_transport = $transport_types[0] ?? [];
        $default_image = $default_transport['transport_image']['url'] ?? '';
        ?>
        
        <div class="wheels-wrapper" style="background-image: url('<?php echo esc_url($default_image); ?>')">
            <div class="wheels-block">
                <?php $this->render_header($transport_types); ?>
                <?php $this->render_main_content($season_mapping, $transport_types); ?>
                <?php $this->render_footer($button_text); ?>
            </div>
        </div>
        
        <?php
        // Выводим JavaScript данные
        $this->output_js_data($settings);
    }
    
    private function render_header($transport_types) {
        ?>
        <header class="wheels-header">
            <select name="transport-type" id="transport-type" class="wheels-hidden">
                <?php foreach ($transport_types as $index => $type): ?>
                    <option value="<?php echo esc_attr($type['transport_slug']); ?>" 
                            data-image="<?php echo esc_url($type['transport_image']['url']); ?>"
                            <?php selected($index, 0); ?>>
                        <?php echo esc_html($type['transport_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <?php foreach ($transport_types as $index => $type): ?>
                <div class="wheels-type-container type-action <?php echo $index === 0 ? 'wheels-active' : ''; ?>" 
                     data-type="<?php echo esc_attr($type['transport_slug']); ?>"
                     data-image="<?php echo esc_url($type['transport_image']['url']); ?>">
                    <?php echo wp_kses_post($type['transport_icon']); ?>
                    <p class="wheels-type-title"><?php echo esc_html($type['transport_name']); ?></p>
                </div>
            <?php endforeach; ?>
        </header>
        <?php
    }
    
    private function render_main_content($season_mapping, $transport_types) {
        // Получаем значения для первого транспорта
        $first_transport = $transport_types[0] ?? [];
        $width_values = $this->get_attribute_values($first_transport['width_attribute'] ?? '');
        $height_values = $this->get_attribute_values($first_transport['height_attribute'] ?? '');
        $radius_values = $this->get_attribute_values($first_transport['radius_attribute'] ?? '');
        ?>
        
        <div class="wheels-container">
            <div class="wheels-filters">
                <div class="wheels-info">
                    <svg xmlns="http://www.w3.org/2000/svg" width="512" height="512" viewBox="0 0 512 512">
                        <path fill="currentColor"
                            d="M256 21A235 235 0 0 0 21 256a235 235 0 0 0 235 235a235 235 0 0 0 235-235A235 235 0 0 0 256 21m0 82c84.393 0 153 68.607 153 153s-68.607 153-153 153s-153-68.607-153-153s68.607-153 153-153m0 18c-20.417 0-39.757 4.52-57.09 12.602C210.457 166.482 230.218 208 256 208c25.823 0 44.926-41.65 56.752-74.555C295.505 125.462 276.284 121 256 121m98.752 42.88c-27.714 21.143-61.142 52.79-53.17 77.327c7.981 24.564 53.508 29.858 88.459 30.936c.628-5.294.959-10.678.959-16.143c0-35.642-13.755-68.012-36.248-92.12m-197.729.243C134.663 188.204 121 220.477 121 256c0 5.55.34 11.018.988 16.39c34.833-.825 80.381-6.793 88.344-31.3c7.974-24.542-25.68-55.553-53.309-76.967m70.188 43.643a9 9 0 0 0-5.035 1.714a9 9 0 0 0-1.99 12.57a9 9 0 0 0 12.57 1.993a9 9 0 0 0 1.992-12.572a9 9 0 0 0-7.537-3.705m57.578 0a9 9 0 0 0-.637.004a9 9 0 0 0-6.9 3.7a9 9 0 0 0 1.992 12.573a9 9 0 0 0 12.57-1.992a9 9 0 0 0-1.99-12.57a9 9 0 0 0-5.035-1.715M256 224a32 32 0 0 0-32 32a32 32 0 0 0 32 32a32 32 0 0 0 32-32a32 32 0 0 0-32-32m-46.297 38.037a9 9 0 0 0-2.652.44a9 9 0 0 0-5.78 11.341a9 9 0 0 0 11.34 5.778a9 9 0 0 0 5.78-11.34a9 9 0 0 0-8.688-6.219m92.856.008a9 9 0 0 0-8.95 6.21a9 9 0 0 0 5.78 11.34a9 9 0 0 0 11.34-5.777a9 9 0 0 0-5.78-11.341a9 9 0 0 0-2.39-.432m-92.143 27.713c-21.59.104-50.24 16.832-72.424 31.928c19.029 34.168 52.46 59.164 92.143 66.837c9.99-33.39 18.42-78.618-2.446-93.777c-4.854-3.527-10.737-5.02-17.273-4.988m91.016.02c-6.58 0-12.492 1.516-17.346 5.042c-20.895 15.181-11.863 60.106-2.088 93.678c39.687-7.715 73.108-32.76 92.1-66.973c-22.006-15.224-50.935-31.747-72.666-31.748zM256 295.58a9 9 0 0 0-9 9a9 9 0 0 0 9 9a9 9 0 0 0 9-9a9 9 0 0 0-9-9" />
                    </svg>
                </div>
            </div>
            
            <form class="wheels-form">
                <?php $this->render_parameters($width_values, $height_values, $radius_values); ?>
                <?php $this->render_seasons($season_mapping); ?>
            </form>
        </div>
        <?php
    }
    
    private function render_parameters($width_values = [], $height_values = [], $radius_values = []) {
        ?>
        <div class="wheels-param">
            <label for="width-car" class="wheels-param__label"><?php esc_html_e('Breite', 'wheels-elementor-widgets'); ?></label>
            <select id="width-car" class="wheels-param__input">
                <?php foreach ($width_values as $value): ?>
                    <option value="<?php echo esc_attr($value); ?>"><?php echo esc_html($value); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="wheels-param">
            <label for="height-car" class="wheels-param__label"><?php esc_html_e('Höhe', 'wheels-elementor-widgets'); ?></label>
            <select id="height-car" class="wheels-param__input">
                <?php foreach ($height_values as $value): ?>
                    <option value="<?php echo esc_attr($value); ?>"><?php echo esc_html($value); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="wheels-param">
            <label for="radius-car" class="wheels-param__label"><?php esc_html_e('Radius', 'wheels-elementor-widgets'); ?></label>
            <select id="radius-car" class="wheels-param__input">
                <?php foreach ($radius_values as $value): ?>
                    <option value="<?php echo esc_attr($value); ?>"><?php echo esc_html($value); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php
    }
    
    private function render_seasons($season_mapping) {
        if (empty($season_mapping)) return;
        ?>
        <div class="wheels-seasons">
            <select name="wheels-type" id="wheels-type" class="wheels-hidden">
                <?php foreach ($season_mapping as $index => $mapping): ?>
                    <option value="<?php echo esc_attr($mapping['season_value']); ?>" <?php selected($index, 0); ?>>
                        <?php echo esc_html($mapping['season_value']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <?php foreach ($season_mapping as $index => $mapping): ?>
                <div class="wheels-type-container season-type <?php echo $index === 0 ? 'wheels-active' : ''; ?>" 
                     data-season="<?php echo esc_attr($mapping['season_value']); ?>">
                    <?php echo $this->get_season_icon($mapping['season_button']); ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
    
    private function render_footer($button_text) {
        ?>
        <footer class="wheels-footer">
            <button type="button" class="wheels-button" id="lets_find">
                <?php echo esc_html($button_text); ?>
            </button>
        </footer>
        <?php
    }
    
    private function get_season_icon($season) {
        $icons = [
            'summer' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9M12,2L14.39,5.42C13.65,5.15 12.84,5 12,5C11.16,5 10.35,5.15 9.61,5.42L12,2M3.34,7L7.5,6.65C6.9,7.16 6.36,7.78 5.94,8.5C5.5,9.24 5.25,10 5.11,10.79L3.34,7M3.36,17L5.12,13.23C5.26,14 5.53,14.78 5.95,15.5C6.37,16.24 6.91,16.86 7.5,17.37L3.36,17M20.65,7L18.88,10.79C18.74,10 18.47,9.23 18.05,8.5C17.63,7.78 17.1,7.15 16.5,6.64L20.65,7M20.64,17L16.5,17.36C17.09,16.85 17.62,16.22 18.04,15.5C18.46,14.77 18.73,14 18.87,13.21L20.64,17M12,22L9.59,18.56C10.33,18.83 11.14,19 12,19C12.82,19 13.63,18.83 14.37,18.56L12,22Z"/></svg>',
            'autumn' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M17.66,8L12,2.35L6.34,8C8.16,9.68 9.5,11.9 9.5,14.5C9.5,17.26 7.26,19.5 4.5,19.5C3.06,19.5 1.73,18.97 0.64,18.08C2.37,20.26 5.13,21.5 8,21.5C13.25,21.5 17.5,17.25 17.5,12C17.5,10.68 17.25,9.41 16.78,8.24C17.16,8.14 17.54,8.05 17.66,8M7.5,10A1.5,1.5 0 0,0 6,11.5A1.5,1.5 0 0,0 7.5,13A1.5,1.5 0 0,0 9,11.5A1.5,1.5 0 0,0 7.5,10Z"/></svg>',
            'winter' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12,1A3,3 0 0,1 15,4V5A1,1 0 0,1 16,6V7.07C18.39,8.45 20,11.04 20,14A8,8 0 0,1 12,22A8,8 0 0,1 4,14C4,11.04 5.61,8.45 8,7.07V6A1,1 0 0,1 9,5V4A3,3 0 0,1 12,1M12,3A1,1 0 0,0 11,4V5H13V4A1,1 0 0,0 12,3M12,8C10.22,8 8.63,8.77 7.53,10H16.47C15.37,8.77 13.78,8 12,8M12,20C13.78,20 15.37,19.23 16.47,18H7.53C8.63,19.23 10.22,20 12,20M12,12A2,2 0 0,0 10,14A2,2 0 0,0 12,16A2,2 0 0,0 14,14A2,2 0 0,0 12,12M18,14C18,13.31 17.88,12.65 17.67,12C16.72,12.19 16,13 16,14C16,15 16.72,15.81 17.67,15.97C17.88,15.35 18,14.69 18,14M6,14C6,14.69 6.12,15.35 6.33,15.97C7.28,15.81 8,15 8,14C8,13 7.28,12.19 6.33,12C6.12,12.65 6,13.31 6,14Z"/></svg>'
        ];
        
        return $icons[$season] ?? $icons['summer'];
    }
    
    private function output_js_data($settings) {
        // Prepare data for all transport types
        $transport_data = [];
        foreach ($settings['transport_types'] as $type) {
            $transport_slug = $type['transport_slug'];

            // Get attribute info (type and clean name)
            $width_attr = $this->parse_attribute($type['width_attribute']);
            $height_attr = $this->parse_attribute($type['height_attribute']);
            $radius_attr = $this->parse_attribute($type['radius_attribute']);

            $transport_data[$transport_slug] = [
                'width_values' => $this->get_attribute_values($type['width_attribute']),
                'height_values' => $this->get_attribute_values($type['height_attribute']),
                'radius_values' => $this->get_attribute_values($type['radius_attribute']),
                'width_attr' => $width_attr,
                'height_attr' => $height_attr,
                'radius_attr' => $radius_attr,
            ];
        }

        $js_data = [
            'transportData' => $transport_data,
            'baseUrl' => get_site_url(),
            'shopUrl' => function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : get_site_url() . '/shop/',
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wheels_filter_nonce'),
        ];

        ?>
        <script type="text/javascript">
            window.wheelsWidgetData = <?php echo wp_json_encode($js_data); ?>;
        </script>
        <?php
    }

    private function parse_attribute($attribute) {
        if (empty($attribute)) {
            return ['type' => '', 'name' => ''];
        }

        if (strpos($attribute, 'acf_') === 0) {
            return [
                'type' => 'acf',
                'name' => substr($attribute, 4)
            ];
        }

        if (strpos($attribute, 'pa_') === 0) {
            return [
                'type' => 'wc',
                'name' => $attribute
            ];
        }

        return ['type' => 'unknown', 'name' => $attribute];
    }
    
    private function get_attribute_values($attribute) {
        if (empty($attribute)) {
            return [];
        }

        // Check if it's an ACF field
        if (strpos($attribute, 'acf_') === 0) {
            return $this->get_acf_field_values(substr($attribute, 4));
        }

        // WooCommerce taxonomy attribute
        if (!taxonomy_exists($attribute)) {
            return [];
        }

        $terms = get_terms([
            'taxonomy' => $attribute,
            'hide_empty' => false,
            'orderby' => 'name',
            'order' => 'ASC'
        ]);

        if (is_wp_error($terms) || empty($terms)) {
            return [];
        }

        $values = [];
        foreach ($terms as $term) {
            $values[] = $term->name;
        }

        return $values;
    }

    private function get_acf_field_values($field_name) {
        global $wpdb;

        $values = [];

        // Get unique values from WooCommerce products with this ACF field
        $meta_key = $field_name;

        // Query to get all unique meta values for this field from products
        $results = $wpdb->get_col($wpdb->prepare(
            "SELECT DISTINCT pm.meta_value
             FROM {$wpdb->postmeta} pm
             INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
             WHERE pm.meta_key = %s
             AND pm.meta_value != ''
             AND p.post_type = 'product'
             AND p.post_status = 'publish'
             ORDER BY pm.meta_value ASC",
            $meta_key
        ));

        if (!empty($results)) {
            foreach ($results as $value) {
                // Handle serialized arrays (ACF repeater/checkbox fields)
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
                    $values[] = $value;
                }
            }
        }

        // Sort values naturally (handles numbers properly)
        sort($values, SORT_NATURAL);

        return array_unique($values);
    }
}