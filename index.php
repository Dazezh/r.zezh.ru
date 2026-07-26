<?php get_header(); ?>
<section class="shell page-head">
    <h1><?php echo is_archive() ? esc_html(get_the_archive_title()) : 'Материалы'; ?></h1>
</section>
<section class="shell section">
    <div class="blog-grid"><?php while (have_posts()):
        the_post(); ?>
            <article class="blog-card"><a href="<?php the_permalink(); ?>">
                    <div><time><?php echo esc_html(get_the_date('d.m.Y')); ?></time>
                        <h2><?php the_title(); ?></h2>
                        <p><?php echo esc_html(get_the_excerpt()); ?></p>
                    </div>
                </a></article><?php endwhile; ?>
    </div><?php the_posts_pagination(); ?>
</section><?php get_footer(); ?>