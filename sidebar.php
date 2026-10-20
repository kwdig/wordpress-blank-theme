<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<aside class="site-sidebar" aria-label="<?php esc_attr_e( 'サイドバー', 'wordpress-blank' ); ?>">
<?php if ( is_active_sidebar( 'sidebar-primary' ) ) : ?>
    <?php dynamic_sidebar( 'sidebar-primary' ); ?>
<?php else : ?>
    <section class="widget">
        <h2 class="widget-title"><?php esc_html_e( '検索', 'wordpress-blank' ); ?></h2>
        <?php get_search_form(); ?>
    </section>
    <section class="widget">
        <h2 class="widget-title"><?php esc_html_e( '最近の記事', 'wordpress-blank' ); ?></h2>
        <ul>
        <?php foreach ( get_posts( array( 'numberposts' => 5, 'post_status' => 'publish', 'ignore_sticky_posts' => true ) ) as $recent_post ) : ?>
            <li><a href="<?php echo esc_url( get_permalink( $recent_post ) ); ?>"><?php echo esc_html( get_the_title( $recent_post ) ?: __( '無題の記事', 'wordpress-blank' ) ); ?></a></li>
        <?php endforeach; ?>
        </ul>
    </section>
    <section class="widget">
        <h2 class="widget-title"><?php esc_html_e( 'カテゴリー', 'wordpress-blank' ); ?></h2>
        <ul><?php wp_list_categories( array( 'title_li' => '', 'show_count' => false ) ); ?></ul>
    </section>
<?php endif; ?>
<?php
// Published pages from this site only; custom widgets do not hide these links.
$wpblank_pages = wp_list_pages( array(
    'title_li'    => '',
    'echo'        => false,
    'post_status'=> 'publish',
    'sort_column'=> 'menu_order,post_title',
) );
if ( $wpblank_pages ) : ?>
    <section class="widget widget-pages">
        <h2 class="widget-title"><?php esc_html_e( '固定ページ', 'wordpress-blank' ); ?></h2>
        <ul><?php echo $wpblank_pages; // WordPress generates escaped page links. ?></ul>
    </section>
<?php endif; ?>
</aside>
