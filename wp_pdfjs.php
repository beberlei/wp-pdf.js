<?php
/*
 * Plugin Name: wp_pdf.js
 * Plugin URI:
 * Description: Publish PDF presentations and documents in your posts.
 * Version: 0.2
 * Author: Henning Kropp
 * Author URI: http://henning.kropponline.de
 * License: GPLv3 (http://www.gnu.org/licenses/gpl-3.0.txt)
*/

define( 'WP_PDFJS_DIR', plugin_dir_path( __FILE__ ) );
define( 'WP_PDFJS_URL', plugin_dir_url( __FILE__ ) );

// add css + js
function add_wp_pdfjs_css_and_script() {
    wp_enqueue_style('wp_pdfjs_css', WP_PDFJS_URL.'wp_pdfjs.css', false );
    wp_enqueue_script('wp_pdfjs_js', WP_PDFJS_URL.'wp_pdfjs.js', false );
}
add_action('admin_enqueue_scripts', 'add_wp_pdfjs_css_and_script' );

function add_wp_pdfjs_view_css() {
    wp_enqueue_script('wp_pdfjs', WP_PDFJS_URL.'pdf.js', false );
    wp_enqueue_script('wp_pdfjs_view_js', WP_PDFJS_URL.'wp_pdfjs_view.js', false );
}
add_action( 'wp_enqueue_scripts', 'add_wp_pdfjs_view_css' );

// add button to edit menu
function wp_pdfjs_media_button() {
    $title = esc_attr('Add a presentation');
    echo '<a href="#" title="' . $title . '"><div id="wp_pdfjs-menu-button" alt="' . $title . '"></div></a>';
}
add_action( 'media_buttons', 'wp_pdfjs_media_button', 1000 );

// add pdf filter to media uploader
function modify_post_mime_types($post_mime_types) {
    $post_mime_types['application/pdf'] = array(__('PDFs'), __('Manage PDFs'), _n_noop('PDF <span class="count">(%s)</span>', 'PDFs <span class="count">(%s)</span>'));
    return $post_mime_types;
}
add_filter('post_mime_types', 'modify_post_mime_types');

// add upload mime pdf
function custom_upload_mimes ( $existing_mimes ) {
    $existing_mimes['pdf'] = 'application/pdf';
    return $existing_mimes;
}
add_filter('upload_mimes', 'custom_upload_mimes');


// shortcode [wp_pdfjs io=123 scale=1.5]
function wp_pdfjs_func( $atts ) {
    $atts = shortcode_atts(array(
                'id' => '-1',
                'url' => '',
                'scale' => '1.5',
                'download' => false,
                    ), $atts);

    if ($atts['id'] <= 0 && $atts['url'] == '') {
        return "[wp_pdfjs: MISSING ATTACHMENT ID OR URL!]";
    }

    $pdfjs_image_url = WP_PDFJS_URL.'images/';
    $pdfjs_script_url = WP_PDFJS_URL.'pdf.js';

    $presentation_url = "";
    if( $atts['id'] < 0){
        $atts['id'] = base_convert($atts['url'], 10, 36);
        $presentation_url = $atts['url'];
    } else {
        $presentation_url = wp_get_attachment_url($atts['id']);
    }

    $id = $atts['id'];
    $scale = $atts['scale'];
    $download = $atts['download'];

    $return_str = <<<HTML
<div id="wp_pdfjs_canvas_container_{$id}" data-wp-pdf="{$presentation_url}" data-wp-pdf-scale="{$scale}">
    <canvas style="border:1px solid black;width:100%;">
    Loading ....
    </canvas>

    <div data-wp-pdfjs-pagination class="wp_pdfjs_navi">
        <center>
        <!-- DOWNLOAD_LINK -->
        <a href="#" data-wp-pdf-prev><img src="{$pdfjs_image_url}glyphicons_210_left_arrow.png"/></a>
        &nbsp;
        <small><span data-wp-pdfjs-page-num></span> / <span data-wp-pdfjs-page-total></span></small>
        &nbsp;
        <a href="#" data-wp-pdf-next><img src="{$pdfjs_image_url}glyphicons_211_right_arrow.png"/></a>
      </center>
    </div><br/>
</div>
HTML;

    if ($download) {
        $return_str = str_replace(
            "<!-- DOWNLOAD_LINK -->",
            "<a title='Download' alt='Download' href='{$presentation_url}' style='float: left;'><img src='{$pdfjs_image_url}glyphicons_134_inbox_in.png' style='width: 14px; height: 14px;'/></a>",
            $return_str
        );
    }

    return $return_str;
}
add_shortcode( 'wp_pdfjs', 'wp_pdfjs_func' );

?>
