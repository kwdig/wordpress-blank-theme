<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="screen-reader-text" href="#main-content"><?php esc_html_e( '本文へスキップ', 'wordpress-blank' ); ?></a>
<header class="site-header">
    <div class="container header-inner">
        <div class="site-branding">
            <?php if ( has_custom_logo() ) { the_custom_logo(); } else { ?>
                <a class="site-title" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
            <?php } ?>
            <?php if ( get_bloginfo( 'description' ) ) : ?><p class="site-description"><?php bloginfo( 'description' ); ?></p><?php endif; ?>
        </div>
        <div class="header-search"><?php get_search_form(); ?></div>
        <?php if ( has_nav_menu( 'primary' ) ) : ?>
        <button class="menu-toggle" type="button" aria-controls="site-navigation" aria-expanded="false"><?php esc_html_e( 'メニュー', 'wordpress-blank' ); ?></button>
        <nav id="site-navigation" class="site-nav" aria-label="<?php esc_attr_e( 'メインメニュー', 'wordpress-blank' ); ?>">
            <?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'menu', 'fallback_cb' => false ) ); ?>
        </nav>
        <?php endif; ?>
    </div>
</header>
<div class="site-layout container">
<main id="main-content" class="site-main" tabindex="-1">
