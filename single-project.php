<?php get_header();
while (have_posts()):
    the_post();
    $reading = zezh_reading_time();
    $content = apply_filters('the_content', get_the_content());
    $toc     = zezh_build_toc($content); ?>
    <article class="shell article">
        <header>
            <p class="eyebrow">
                <?php echo esc_html(strtoupper(get_post_type_object(get_post_type())->labels->singular_name)); ?></p>
            <h1><?php the_title(); ?></h1>
            <p class="lead"><?php echo esc_html(get_the_excerpt()); ?></p>
        </header>
        <div class="article-meta">
            <span><?php echo esc_html($reading['words']); ?> слов</span>
            <span><?php echo esc_html($reading['minutes']); ?> мин. чтения</span>
        </div><?php if (has_post_thumbnail()): ?>
            <div class="article-cover"><?php the_post_thumbnail('full'); ?></div><?php endif; ?>
        <div class="prose"><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
    </article>
    <script type="application/json" id="zezh-toc-data"
        data-icon-dark="<?php echo esc_url(get_template_directory_uri() . '/assets/img/icon/r-book-dark.svg'); ?>"
        data-icon-light="<?php echo esc_url(get_template_directory_uri() . '/assets/img/icon/r-book-light.svg'); ?>"><?php echo wp_json_encode($toc, JSON_UNESCAPED_UNICODE); ?></script><?php endwhile;
get_footer(); ?>