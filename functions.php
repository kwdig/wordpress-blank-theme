<?php
/** Theme setup and assets. Add new features here. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
function wpblank_setup() {
    load_theme_textdomain( 'wordpress-blank', get_template_directory() . '/languages' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'custom-logo', array( 'height' => 80, 'width' => 240, 'flex-height' => true, 'flex-width' => true ) );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
    add_theme_support( 'editor-styles' );
    add_editor_style( 'assets/editor.css' );
    register_nav_menus( array( 'primary' => __( 'ヘッダーメニュー', 'wordpress-blank' ), 'footer' => __( 'フッターメニュー', 'wordpress-blank' ) ) );
}
add_action( 'after_setup_theme', 'wpblank_setup' );
/** 外観 → ウィジェットで右サイドバーを編集できます。 */
function wpblank_widgets() {
    register_sidebar( array(
        'name' => __( '右サイドバー', 'wordpress-blank' ),
        'id' => 'sidebar-primary',
        'description' => __( 'PCでは本文の右、スマホでは本文の下に表示されます。', 'wordpress-blank' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ) );
}
add_action( 'widgets_init', 'wpblank_widgets' );
function wpblank_assets() {
    $version = wp_get_theme()->get( 'Version' );
    $css_files = array( get_template_directory() . '/style.css' );
    if ( is_child_theme() ) { $css_files[] = get_stylesheet_directory() . '/style.css'; }
    wp_register_style( 'wpblank-style', false, array(), $version );
    wp_enqueue_style( 'wpblank-style' );
    foreach ( $css_files as $css_file ) {
        if ( is_readable( $css_file ) ) {
            wp_add_inline_style( 'wpblank-style', file_get_contents( $css_file ) );
        }
    }
    if ( has_nav_menu( 'primary' ) ) {
        wp_enqueue_script( 'wpblank-navigation', get_template_directory_uri() . '/assets/navigation.js', array(), $version, true );
    }
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) { wp_enqueue_script( 'comment-reply' ); }
}
add_action( 'wp_enqueue_scripts', 'wpblank_assets' );

/** A short description for search results, without adding a heavy SEO plugin. */
function wpblank_meta_description() {
    if ( ! apply_filters( 'wpblank_enable_meta_description', true ) || is_search() || is_404() ) { return; }
    if ( defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' ) || defined( 'SEOPRESS_VERSION' ) ) { return; }
    if ( is_singular() ) {
        $post = get_queried_object();
        $description = post_password_required( $post ) ? get_bloginfo( 'description' ) : ( has_excerpt( $post ) ? $post->post_excerpt : $post->post_content );
    } elseif ( is_category() || is_tag() || is_tax() ) {
        $description = term_description();
        if ( ! trim( wp_strip_all_tags( $description ) ) ) {
            $description = single_term_title( '', false ) . 'の記事一覧。' . get_bloginfo( 'name' ) . 'の最新記事をご覧いただけます。';
        }
    } else {
        $description = get_bloginfo( 'description' ) ?: get_bloginfo( 'name' );
    }
    $description = trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( strip_shortcodes( $description ) ) ) );
    $description = function_exists( 'mb_substr' ) ? mb_substr( $description, 0, 160 ) : substr( $description, 0, 320 );
    if ( $description ) { echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n"; }
}
add_action( 'wp_head', 'wpblank_meta_description', 1 );

/** Generate WebP sub-sizes for future JPEG uploads when supported by the server. */
function wpblank_image_output_format( $formats ) {
    if ( apply_filters( 'wpblank_enable_webp', true ) && wp_image_editor_supports( array( 'mime_type' => 'image/webp' ) ) && ! isset( $formats['image/jpeg'] ) ) {
        $formats['image/jpeg'] = 'image/webp';
    }
    return $formats;
}
add_filter( 'image_editor_output_format', 'wpblank_image_output_format' );

/** Responsive card images and economical WebP encoding. */
function wpblank_card_image_sizes() {
    add_image_size('wpblank-card-small',180,0,false);
    add_image_size('wpblank-card-medium',360,0,false);
    add_image_size('wpblank-card-large',672,0,false);
}
add_action('after_setup_theme','wpblank_card_image_sizes');
add_filter('wp_editor_set_quality',static function($quality,$mime,$size=[]){return $mime==='image/webp' && !empty($size['width']) && $size['width']<=672?52:$quality;},10,3);
/** Bound the transfer size of complex card WebP images; keep originals intact. */
function wpblank_compress_card_webp( $metadata, $attachment_id ) {
    if ( empty( $metadata['sizes'] ) ) { return $metadata; }
    $source = get_attached_file( $attachment_id );
    if ( ! $source || ! is_file( $source ) ) { return $metadata; }
    foreach ( array( 'wpblank-card-small', 'wpblank-card-medium', 'wpblank-card-large' ) as $key ) {
        if ( empty( $metadata['sizes'][$key] ) ) { continue; }
        $size = $metadata['sizes'][$key];
        if ( 'image/webp' !== ( $size['mime-type'] ?? '' ) ) { continue; }
        $path = dirname( $source ) . '/' . $size['file'];
        $budget = max( 8192, (int) ( $size['width'] * $size['height'] * 0.16 ) );
        if ( ! is_file( $path ) || filesize( $path ) <= $budget ) { continue; }
        foreach ( array( 46, 40, 34 ) as $quality ) {
            $editor = wp_get_image_editor( $source );
            if ( is_wp_error( $editor ) ) { break; }
            $resized = $editor->resize( $size['width'], $size['height'], false );
            if ( is_wp_error( $resized ) || is_wp_error( $editor->set_quality( $quality ) ) ) { break; }
            $saved = $editor->save( $path, 'image/webp' );
            if ( is_wp_error( $saved ) ) { break; }
            clearstatcache( true, $path );
            $metadata['sizes'][$key]['filesize'] = filesize( $path );
            if ( $metadata['sizes'][$key]['filesize'] <= $budget ) { break; }
        }
    }
    return $metadata;
}
add_filter( 'wp_generate_attachment_metadata', 'wpblank_compress_card_webp', 20, 2 );


/** Defer menu controls without blocking HTML parsing (WordPress 6.0+). */
function wpblank_defer_control_scripts( $tag, $handle ) {
    if ( 'wpblank-navigation' !== $handle ) { return $tag; }
    return preg_replace( '/<script\b(?=[^>]*\bsrc=)(?![^>]*\bdefer\b)/i', '<script defer', $tag, 1 );
}
add_filter( 'script_loader_tag', 'wpblank_defer_control_scripts', 10, 2 );