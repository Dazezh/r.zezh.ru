<?php get_header(); ?>
<section class="hero shell">
    <div class="hero-copy">
        <p class="eyebrow">ZEZH / 418</p>
        <h1><?php bloginfo('name'); ?></h1>
        <p class="lead">
            <?php echo esc_html(get_bloginfo('description') ?: 'Веб-разработка, свои сервисы и проекты, которым зачем-то дали жить.'); ?>
        </p>
        <div class="hero-actions"><a class="button" href="#projects">Смотреть проекты</a><a class="text-link"
                href="<?php echo esc_url(get_post_type_archive_link('service')); ?>">Сервисы →</a></div>
    </div>
    <div class="hero-mark"><img class="brand-dark"
            src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/logo/logo-dark.svg'); ?>" alt=""><img
            class="brand-light"
            src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/logo/logo-light.svg'); ?>" alt=""></div>
</section>
<section id="projects" class="shell section">
    <div class="section-head row">
        <div>
            <p class="eyebrow">SELECTED WORK<</p>
            <h2>Проекты</h2>
        </div><a class="text-link"
            href="<?php echo esc_url((get_post_type_archive_link('project')) ?: home_url('/projects/')); ?>">Все проекты
            →</a>
    </div>
    <?php $order = ['Личные проекты', 'Учебные проекты', 'Заказные проекты'];
    foreach ($order as $cat):
        $term = get_term_by('name', $cat, 'project_type');
        if (!$term)
            continue;
        $q = new WP_Query(['post_type' => 'project', 'posts_per_page' => 6, 'tax_query' => [['taxonomy' => 'project_type', 'field' => 'term_id', 'terms' => $term->term_id]], 'orderby' => ['menu_order' => 'ASC', 'date' => 'DESC']]);
        if (!$q->have_posts())
            continue; ?>
        <div class="project-group">
            <div class="group-title">
                <h3><?php echo esc_html($cat); ?></h3><span><?php echo intval($q->found_posts); ?></span>
            </div>
            <div class="cards"><?php while ($q->have_posts()):
                $q->the_post(); ?>
                    <article class="card"><a
                            href="<?php the_permalink(); ?>"><?php if (has_post_thumbnail())
                                  the_post_thumbnail('large', ['loading' => 'lazy']); ?>
                            <div class="card-body">
                                <h4><?php the_title(); ?></h4>
                                <p><?php echo esc_html(get_the_excerpt()); ?></p><span>Открыть проект ↗</span>
                            </div>
                        </a></article><?php endwhile; ?>
            </div>
        </div><?php wp_reset_postdata(); endforeach; ?>
</section>
<section class="shell section">
    <div class="section-head row">
        <div>
            <p class="eyebrow">NOTES</p>
            <h2>Последнее из блога</h2>
        </div><a class="text-link"
            href="<?php echo esc_url(get_permalink(get_option('page_for_posts')) ?: home_url('/blog/')); ?>">Все записи
            →</a>
    </div>
    <div class="post-list">
        <?php $b = new WP_Query(['post_type' => 'post', 'posts_per_page' => 3]);
        while ($b->have_posts()):
            $b->the_post(); ?><a
                class="post-row"
                href="<?php the_permalink(); ?>"><time><?php echo esc_html(get_the_date('d.m.Y')); ?></time><strong><?php the_title(); ?></strong><span>↗</span></a><?php endwhile;
        wp_reset_postdata(); ?>
    </div>
</section>
<?php get_footer(); ?>