<?php
/**
 * Plugin Name: Bootstrap Summary Cards
 * Description: Display custom "Summary Cards" (intro/links) in a Bootstrap grid. Supports Custom Post Types, Categories, and Elementor.
 * Version: 1.1
 * Author: D Kandekore
 */

if (!defined('ABSPATH')) exit;

define('BSC_PLUGIN_DIR', plugin_dir_path(__FILE__));

/**
 * --------------------------------------------------------
 * 1. REGISTER CPT & TAXONOMY
 * --------------------------------------------------------
 */
add_action('init', 'bsc_register_cpt_and_tax');

function bsc_register_cpt_and_tax() {
    // 1. Taxonomy: Summary Categories
    register_taxonomy('summary_card_cat', 'summary_card', [
        'labels' => [
            'name' => 'Summary Categories',
            'singular_name' => 'Summary Category',
        ],
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_rest' => true,
    ]);

    // 2. Custom Post Type: Summary Card
    register_post_type('summary_card', [
        'labels' => [
            'name' => 'Summary Cards',
            'singular_name' => 'Summary Card',
            'add_new' => 'Add New Card',
            'add_new_item' => 'Add New Summary Card',
            'edit_item' => 'Edit Summary Card',
        ],
        'public' => true,
        'has_archive' => false,
        'menu_icon' => 'dashicons-grid-view',
        'supports' => ['title', 'editor', 'thumbnail'],
        'show_in_rest' => true,
    ]);
}

/**
 * --------------------------------------------------------
 * 2. CUSTOM FIELDS (Meta Boxes)
 * --------------------------------------------------------
 */
add_action('add_meta_boxes', 'bsc_add_meta_boxes');
add_action('save_post', 'bsc_save_meta_data');

function bsc_add_meta_boxes() {
    add_meta_box(
        'bsc_card_details',
        'Card Details',
        'bsc_render_meta_box',
        'summary_card',
        'normal',
        'high'
    );
}

function bsc_render_meta_box($post) {
    $target_url = get_post_meta($post->ID, '_bsc_target_url', true);
    $icon_class = get_post_meta($post->ID, '_bsc_icon_class', true);
    ?>
    <p>
        <label for="bsc_target_url" style="font-weight:bold;">Target URL (Link Destination):</label><br>
        <input type="url" id="bsc_target_url" name="bsc_target_url" value="<?php echo esc_attr($target_url); ?>" style="width:100%;" placeholder="https://example.com/page-to-visit">
    </p>
    <p>
        <label for="bsc_icon_class" style="font-weight:bold;">Font Awesome Icon Class:</label><br>
        <input type="text" id="bsc_icon_class" name="bsc_icon_class" value="<?php echo esc_attr($icon_class); ?>" style="width:100%;" placeholder="e.g. fa-solid fa-rocket">
        <small>Find icons at <a href="https://fontawesome.com/search?m=free" target="_blank">Font Awesome</a>.</small>
    </p>
    <?php
}

function bsc_save_meta_data($post_id) {
    if (array_key_exists('bsc_target_url', $_POST)) {
        update_post_meta($post_id, '_bsc_target_url', sanitize_url($_POST['bsc_target_url']));
    }
    if (array_key_exists('bsc_icon_class', $_POST)) {
        // Sanitize text field but allow spaces for multiple classes
        update_post_meta($post_id, '_bsc_icon_class', sanitize_text_field($_POST['bsc_icon_class']));
    }
}

/**
 * --------------------------------------------------------
 * 3. ADMIN SETTINGS (Styles)
 * --------------------------------------------------------
 */
add_action('admin_menu', 'bsc_add_admin_menu');
add_action('admin_init', 'bsc_register_settings');

function bsc_add_admin_menu() {
    add_submenu_page(
        'edit.php?post_type=summary_card', // Parent menu (CPT)
        'Display Settings',
        'Display Settings',
        'manage_options',
        'bsc-settings',
        'bsc_options_page'
    );
}

function bsc_register_settings() {
    register_setting('bsc_settings_group', 'bsc_border_color');
    register_setting('bsc_settings_group', 'bsc_divider_color');
    register_setting('bsc_settings_group', 'bsc_bg_color');
    register_setting('bsc_settings_group', 'bsc_desc_length');
    register_setting('bsc_settings_group', 'bsc_button_text');
    register_setting('bsc_settings_group', 'bsc_default_image');
    register_setting('bsc_settings_group', 'bsc_button_color');
    register_setting('bsc_settings_group', 'bsc_text_color');
}

function bsc_options_page() { ?>
    <div class="wrap">
        <h1>Summary Cards Design Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('bsc_settings_group'); ?>
            <?php do_settings_sections('bsc_settings_group'); ?>
            <table class="form-table">
                <tr>
                    <th>Border Color</th>
                    <td><input type="text" name="bsc_border_color" value="<?php echo esc_attr(get_option('bsc_border_color', '#cccccc')); ?>" /></td>
                </tr>
                <tr>
                    <th>Divider Color</th>
                    <td><input type="text" name="bsc_divider_color" value="<?php echo esc_attr(get_option('bsc_divider_color', '#eeeeee')); ?>" /></td>
                </tr>
                <tr>
                    <th>Background Color</th>
                    <td><input type="text" name="bsc_bg_color" value="<?php echo esc_attr(get_option('bsc_bg_color', '#ffffff')); ?>" /></td>
                </tr>
                <tr>
                    <th>Text Color</th>
                    <td><input type="text" name="bsc_text_color" value="<?php echo esc_attr(get_option('bsc_text_color', '#333333')); ?>" /></td>
                </tr>
                <tr>
                    <th>Button Color</th>
                    <td><input type="text" name="bsc_button_color" value="<?php echo esc_attr(get_option('bsc_button_color', '#007bff')); ?>" /></td>
                </tr>
                <tr>
                    <th>Description Limit (characters)</th>
                    <td><input type="number" name="bsc_desc_length" value="<?php echo esc_attr(get_option('bsc_desc_length', 300)); ?>" min="50" max="2000" /></td>
                </tr>
                <tr>
                    <th>Button Text</th>
                    <td><input type="text" name="bsc_button_text" value="<?php echo esc_attr(get_option('bsc_button_text', 'Read More')); ?>" /></td>
                </tr>
                <tr>
                    <th>Default Image (URL)</th>
                    <td>
                        <input type="text" name="bsc_default_image" value="<?php echo esc_attr(get_option('bsc_default_image', '')); ?>" style="width:80%;" placeholder="https://example.com/default.jpg" />
                        <p><em>Fallback image if no Featured Image is set.</em></p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
<?php }

/**
 * --------------------------------------------------------
 * 4. SHORTCODE
 * --------------------------------------------------------
 */
add_shortcode('bootstrap_summary_cards', 'bsc_display_cards');

function bsc_display_cards($atts) {
    $atts = shortcode_atts([
        'category'   => '', // slug
        'columns'    => '3',
        'limit'      => '12',
    ], $atts, 'bootstrap_summary_cards');

    return bsc_render_cards(
        $atts['category'],
        $atts['columns'],
        $atts['limit']
    );
}

/**
 * --------------------------------------------------------
 * 5. RENDER FUNCTION
 * --------------------------------------------------------
 */
function bsc_render_cards($category = '', $columns = 3, $limit = 12) {
    // Get Styles
    $border_color  = get_option('bsc_border_color', '#cccccc');
    $divider_color = get_option('bsc_divider_color', '#eeeeee');
    $bg_color      = get_option('bsc_bg_color', '#ffffff');
    $text_color    = get_option('bsc_text_color', '#333333');
    $button_color  = get_option('bsc_button_color', '#007bff');
    $desc_length   = intval(get_option('bsc_desc_length', 300));
    $button_text   = esc_html(get_option('bsc_button_text', 'Read More'));
    $default_image = trim(get_option('bsc_default_image', ''));

    // Query Args
    $args = [
        'post_type'      => 'summary_card',
        'posts_per_page' => intval($limit),
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'tax_query'      => [],
    ];

    if (!empty($category)) {
        $args['tax_query'][] = [
            'taxonomy' => 'summary_card_cat',
            'field'    => 'slug',
            'terms'    => explode(',', $category),
        ];
    }

    $query = new WP_Query($args);
    if (!$query->have_posts()) {
        return '<p>No summary cards found.</p>';
    }

    // Load Assets
    wp_enqueue_style('bsc-bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css', [], '5.3.2');
    wp_enqueue_style('bsc-fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css', [], '6.5.2');

    // Calculate Column Class
    $col_class = 'col-md-' . (12 / max(1, intval($columns)));

    ob_start(); ?>
    <style>
        .bsc-card-body { display:flex; flex-direction:column; }
        .bsc-section-divider { width:75%; margin:0.75rem auto; border:0; border-top:1px solid var(--bsc-divider-color, #cccccc); align-self:center; }
        .bsc-image-divider-wrapper { position: relative; text-align:center; margin: 0; padding: 0px 0 30px; border-top: 10px solid var(--bsc-divider-color, #cccccc); }
        .bsc-image-divider-line { border: none; border-top: 10px solid #ccc; margin: 0 auto; width: 100%; }
        .bsc-image-divider-icon { position: absolute; top: 0; left: 50%; transform: translate(-50%, -50%); width: 56px; height: 56px; border-radius: 50%; display: flex; justify-content: center; align-items: center; background: var(--bsc-divider-color, #fff)!important; z-index: 3; font-size: x-large; }
    </style>

    <div class="container my-4">
        <div class="row g-4">
            <?php while ($query->have_posts()) : $query->the_post();
                // Get Meta
                $post_id = get_the_ID();
                $target_url = get_post_meta($post_id, '_bsc_target_url', true);
                if (empty($target_url)) $target_url = '#'; // Fallback if user forgot URL

                $icon_class = get_post_meta($post_id, '_bsc_icon_class', true);

                $content = wp_strip_all_tags(get_the_content());
                if (strlen($content) > $desc_length) {
                    $content = substr($content, 0, $desc_length) . '...';
                }

                // Image Logic
                $image_url = '';
                if (has_post_thumbnail()) {
                    $image_url = get_the_post_thumbnail_url($post_id, 'medium');
                } elseif (!empty($default_image)) {
                    $image_url = esc_url($default_image);
                } else {
                    $image_url = 'https://via.placeholder.com/800x500?text=No+Image';
                }
                ?>
                <div class="<?php echo esc_attr($col_class); ?>">
                    <div class="card h-100 shadow-sm"
                         style="border:2px solid <?php echo esc_attr($border_color); ?>;
                                background-color:<?php echo esc_attr($bg_color); ?>;">
                        
                        <?php if ($image_url): ?>
                            <a href="<?php echo esc_url($target_url); ?>" style="text-decoration:none;">
                                <img src="<?php echo esc_url($image_url); ?>" class="card-img-top" alt="<?php the_title_attribute(); ?>">
                            </a>
                        <?php endif; ?>

                        <div class="bsc-image-divider-wrapper" style="--bsc-divider-color:<?php echo esc_attr($divider_color); ?>;">
                            <hr class="bsc-image-divider-line" style="border-top:1px solid <?php echo esc_attr($divider_color); ?>;">
                            <?php if (!empty($icon_class)): ?>
                                <span class="bsc-image-divider-icon" style="background-color:<?php echo esc_attr($bg_color); ?>;">
                                    <i class="<?php echo esc_attr($icon_class); ?>" style="color:<?php echo esc_attr($bg_color); ?>;"></i>
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="card-body bsc-card-body"
                             style="color:<?php echo esc_attr($text_color); ?>; --bsc-divider-color:<?php echo esc_attr($divider_color); ?>;">
                            
                            <a href="<?php echo esc_url($target_url); ?>" style="text-decoration:none; color:inherit;">
                                <h5 class="card-title text-center mb-2"><?php the_title(); ?></h5>
                            </a>
                            <hr class="bsc-section-divider">

                            <?php if (!empty($content)): ?>
                                <p class="small text-center flex-grow-1 mb-3"><?php echo esc_html($content); ?></p>
                                <hr class="bsc-section-divider">
                            <?php endif; ?>

                            <a href="<?php echo esc_url($target_url); ?>"
                               class="btn w-100 mt-auto"
                               style="background-color:<?php echo esc_attr($button_color); ?>; color:#fff;">
                                <?php echo esc_html($button_text); ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <?php
    wp_reset_postdata();
    return ob_get_clean();
}

/**
 * --------------------------------------------------------
 * 6. ELEMENTOR WIDGET REGISTER
 * --------------------------------------------------------
 */
function bsc_register_elementor_widget($widgets_manager) {
    require_once BSC_PLUGIN_DIR . 'includes/class-bsc-elementor-widget.php';
    $widgets_manager->register(new \BSC_Elementor_Widget());
}
add_action('elementor/widgets/register', 'bsc_register_elementor_widget');