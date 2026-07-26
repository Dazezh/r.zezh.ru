<?php
if (!defined('ABSPATH')) exit;

define('ZEZH_VERSION', '1.2.1');

add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption','style','script']);
    add_theme_support('custom-logo');
    add_theme_support('responsive-embeds');
    register_nav_menus(['primary' => 'Основное меню', 'footer' => 'Меню в подвале']);
});

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('zezh-fonts', 'https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap', [], null);
    wp_enqueue_style('zezh-main', get_template_directory_uri().'/assets/css/main.css', [], ZEZH_VERSION);
    wp_enqueue_script('zezh-main', get_template_directory_uri().'/assets/js/main.js', [], ZEZH_VERSION, true);
});

add_action('init', function () {
    register_post_type('project', [
        'labels'=>['name'=>'Проекты','singular_name'=>'Проект','add_new_item'=>'Добавить проект'],
        'public'=>true,'has_archive'=>true,'rewrite'=>['slug'=>'projects'],'show_in_rest'=>true,
        'menu_icon'=>'dashicons-portfolio','supports'=>['title','editor','excerpt','thumbnail','custom-fields']
    ]);
    register_taxonomy('project_type', ['project'], [
        'labels'=>['name'=>'Категории проектов','singular_name'=>'Категория проекта'],
        'public'=>true,'hierarchical'=>true,'show_in_rest'=>true,'rewrite'=>['slug'=>'project-type']
    ]);
    register_post_type('service', [
        'labels'=>['name'=>'Сервисы','singular_name'=>'Сервис','add_new_item'=>'Добавить сервис'],
        'public'=>true,'has_archive'=>true,'rewrite'=>['slug'=>'services'],'show_in_rest'=>true,
        'menu_icon'=>'dashicons-admin-tools','supports'=>['title','editor','excerpt','thumbnail','custom-fields']
    ]);
});

add_action('after_switch_theme', function () {
    foreach (['Личные проекты','Учебные проекты','Заказные проекты'] as $name) {
        if (!term_exists($name, 'project_type')) wp_insert_term($name, 'project_type');
    }
});

function zezh_description() {
    if (is_singular() && has_excerpt()) return wp_strip_all_tags(get_the_excerpt());
    if (is_front_page()) return get_bloginfo('description') ?: 'Портфолио веб-разработчика: личные, учебные и заказные проекты, сервисы и заметки.';
    return wp_strip_all_tags(get_bloginfo('description'));
}

add_action('wp_head', function () {
    if (defined('WPSEO_VERSION') || defined('RANK_MATH_VERSION')) return;
    $desc = zezh_description();
    echo '<meta name="description" content="'.esc_attr($desc).'">'."\n";
    echo '<meta property="og:type" content="'.(is_singular()?'article':'website').'">'."\n";
    echo '<meta property="og:title" content="'.esc_attr(wp_get_document_title()).'">'."\n";
    echo '<meta property="og:description" content="'.esc_attr($desc).'">'."\n";
    echo '<meta property="og:url" content="'.esc_url((is_singular()?get_permalink():home_url('/'))).'">'."\n";
    echo '<meta name="twitter:card" content="summary_large_image">'."\n";
    $schema=['@context'=>'https://schema.org','@type'=>is_singular('service')?'SoftwareApplication':'WebSite','name'=>wp_get_document_title(),'url'=>is_singular()?get_permalink():home_url('/'),'description'=>$desc];
    echo '<script type="application/ld+json">'.wp_json_encode($schema, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES).'</script>'."\n";
}, 2);

add_filter('document_title_separator', fn()=> '·');
