<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
</main>
<?php get_sidebar(); ?>
</div>
<footer class="site-footer">
    <div class="container footer-inner">
        <p>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></p>
        <p class="theme-credit"><?php esc_html_e( 'Theme by ', 'wordpress-blank' ); ?><a href="https://kwdig.com/wordpress-blank-theme" target="_blank" rel="noopener noreferrer">KwDig<span class="screen-reader-text"><?php esc_html_e( '（新しいタブで開きます）', 'wordpress-blank' ); ?></span></a></p>
        <?php if ( has_nav_menu( 'footer' ) ) : ?>
        <nav aria-label="<?php esc_attr_e( 'フッターメニュー', 'wordpress-blank' ); ?>">
            <?php wp_nav_menu( array( 'theme_location' => 'footer', 'container' => false, 'menu_class' => 'menu', 'fallback_cb' => false ) ); ?>
        </nav>
        <?php endif; ?>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
