<?php get_header();
while (have_posts()):
    the_post();
    $bg = get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>
    <article class="service-hero" <?php if ($bg)
        echo ' style="--service-bg:url(' . esc_url($bg) . ')"'; ?>>
        <div class="service-bg" aria-hidden="true"></div>
        <div class="shell service-content">
            <p class="eyebrow">SERVICE / <?php echo esc_html(get_the_date('Y')); ?></p>
            <h1><?php the_title(); ?></h1>
            <p class="lead"><?php echo esc_html(get_the_excerpt()); ?></p><a class="button" href="#story">Почему, как и
                зачем ↓</a>
        </div>
    </article>
    <section id="story" class="shell article service-story">
        <div class="prose"><?php the_content(); ?></div>
    </section>
<?php endwhile;
get_footer(); ?>