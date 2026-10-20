<?php get_header(); ?>
<header class="archive-header">
<?php if ( is_search() ) : ?>
    <h1><?php printf( esc_html__( '検索結果：%s', 'wordpress-blank' ), esc_html( get_search_query() ) ); ?></h1>
<?php elseif ( is_archive() ) : ?>
    <?php if ( is_category() ) : ?>
        <h1><?php echo esc_html( single_cat_title( '', false ) ); ?></h1>
    <?php elseif ( is_tag() ) : ?>
        <h1><?php echo esc_html( single_tag_title( '', false ) ); ?></h1>
    <?php else : the_archive_title( '<h1>', '</h1>' ); endif; ?>
    <?php the_archive_description( '<div>', '</div>' ); ?>
<?php else : ?>
    <h1><?php echo esc_html( is_home() && ! is_front_page() ? get_the_title( get_option( 'page_for_posts' ) ) : __( '記事一覧', 'wordpress-blank' ) ); ?></h1>
<?php endif; ?>
</header>
<?php if ( have_posts() ) : ?>
    <div class="post-grid">
    <?php while ( have_posts() ) : the_post(); get_template_part( 'template-parts/content', 'card' ); endwhile; ?>
    </div>
    <?php the_posts_pagination( array( 'mid_size' => 2, 'prev_text' => __( '前へ', 'wordpress-blank' ), 'next_text' => __( '次へ', 'wordpress-blank' ) ) ); ?>
<?php else : get_template_part( 'template-parts/content', 'none' ); endif; ?>
<?php get_footer(); ?>
