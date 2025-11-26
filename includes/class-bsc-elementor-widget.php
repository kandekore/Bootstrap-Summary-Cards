<?php
if (!defined('ABSPATH')) exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class BSC_Elementor_Widget extends Widget_Base {

    public function get_name() {
        return 'bsc_cards';
    }

    public function get_title() {
        return 'Bootstrap Summary Cards';
    }

    public function get_icon() {
        return 'eicon-gallery-grid';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function register_controls() {
        $this->start_controls_section('content_section', [
            'label' => __('Settings', 'bsc'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        // Get Custom Taxonomy Categories
        $categories = get_terms([
            'taxonomy'   => 'summary_card_cat',
            'hide_empty' => false,
        ]);

        $options = [];
        if (!empty($categories) && !is_wp_error($categories)) {
            foreach ($categories as $cat) {
                $options[$cat->slug] = $cat->name;
            }
        }

        $this->add_control('category', [
            'label'       => __('Category', 'bsc'),
            'type'        => Controls_Manager::SELECT2,
            'options'     => $options,
            'multiple'    => true,
            'description' => 'Select categories to display.',
        ]);

        $this->add_control('columns', [
            'label'   => __('Columns per Row', 'bsc'),
            'type'    => Controls_Manager::NUMBER,
            'min'     => 1,
            'max'     => 6,
            'default' => 3,
        ]);

        $this->add_control('limit', [
            'label'   => __('Total Cards Limit', 'bsc'),
            'type'    => Controls_Manager::NUMBER,
            'min'     => 1,
            'max'     => 50,
            'default' => 9,
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $category = '';
        if (!empty($settings['category'])) {
            $category = is_array($settings['category'])
                ? implode(',', $settings['category'])
                : $settings['category'];
        }

        // Call the main render function
        echo bsc_render_cards(
            $category,
            $settings['columns'],
            $settings['limit']
        );
    }
}