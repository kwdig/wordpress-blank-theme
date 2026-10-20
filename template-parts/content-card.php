<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
    <?php if ( has_post_thumbnail() ) : ?>
    <a class="thumbnail-link" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( get_the_title() ?: __( '無題の記事', 'wordpress-blank' ) ); ?>">
        <?php global $wp_query;
        $first_image = 0 === (int) $wp_query->current_post;
        the_post_thumbnail( 'medium_large', array(
            'sizes' => '(max-width: 480px) calc(100vw - 32px), (max-width: 768px) 120px, (max-width: 960px) 140px, 180px',
            'loading' => $first_image ? 'eager' : 'lazy',
            'fetchpriority' => $first_image ? 'high' : 'auto',
            'decoding' => 'async',
        ) ); ?>
    </a>
    <?php endif; ?>
    <div class="post-card-body">
    <h2><a href="<?php the_permalink(); ?>"><?php echo esc_html( get_the_title() ?: __( '無題の記事', 'wordpress-blank' ) ); ?></a></h2>
    <?php if ( 'post' === get_post_type() ) : ?>
    <div class="post-meta"><time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>　<span class="post-categories">カテゴリー：<?php the_category( ', ' ); ?></span>
        <?php if ( has_tag() ) : ?><span class="post-tags">　タグ：<?php the_tags( '', ', ', '' ); ?></span><?php endif; ?></div>
    <?php endif; ?>
    <?php the_excerpt(); ?>
    <a class="read-more" href="<?php the_permalink(); ?>"><?php esc_html_e( '続きを読む', 'wordpress-blank' ); ?><span class="screen-reader-text">：<?php the_title(); ?></span></a>
    </div>
</article>
