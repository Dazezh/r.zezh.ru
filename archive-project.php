<?php get_header(); ?>
<section class="shell page-head">
    <p class="eyebrow">PROJECTS / SKILLS</p>
    <h1>Проекты</h1>
</section>
<section class="shell section">
    <div class="cards"><?php while (have_posts()):
        the_post(); ?>
            <article class="card"><a
                    href="<?php the_permalink(); ?>"><?php if (has_post_thumbnail())
                          the_post_thumbnail('large'); ?>
                    <div class="card-body">
                        <h2><?php the_title(); ?></h2>
                        <p><?php echo esc_html(get_the_excerpt()); ?></p>
                    </div>
                </a></article><?php endwhile; ?>
    </div>
</section><?php get_footer(); ?>