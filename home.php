<?php get_header(); ?>
<section class="shell page-head">
    <p class="eyebrow">BLOG / LOG</p>
    <h1>Записи</h1>
    <p>Заметки о разработке, инфраструктуре и вещах, которые пришлось чинить.</p>
</section>
<section class="shell section">
    <div class="blog-grid"><?php if (have_posts()):
        while (have_posts()):
            the_post(); ?>
                <article class="blog-card"><a
                        href="<?php the_permalink(); ?>"><?php if (has_post_thumbnail())
                              the_post_thumbnail('large', ['loading' => 'lazy']); ?>
                        <div><time><?php echo esc_html(get_the_date('d.m.Y')); ?></time>
                            <h2><?php the_title(); ?></h2>
                            <p><?php echo esc_html(get_the_excerpt()); ?></p>
                        </div>
                    </a></article><?php endwhile;
        the_posts_pagination(); else: ?>
            <p>Записей пока нет.</p><?php endif; ?>
    </div>
</section><?php get_footer(); ?>