<?php get_header();
while (have_posts()):
    the_post(); ?>
    <article class="shell article">
        <header>
            <p class="eyebrow">
                <?php echo esc_html(strtoupper(get_post_type_object(get_post_type())->labels->singular_name)); ?></p>
            <h1><?php the_title(); ?></h1>
            <p class="lead"><?php echo esc_html(get_the_excerpt()); ?></p>
        </header><?php if (has_post_thumbnail()): ?>
            <div class="article-cover"><?php the_post_thumbnail('full'); ?></div><?php endif; ?>
        <div class="prose"><?php the_content(); ?></div>
    </article><?php endwhile;
get_footer(); ?>