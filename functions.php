<?php
if (!defined('ABSPATH')) exit;

define('ZEZH_VERSION', '1.3.1');

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

/**
 * Подсчёт количества слов и времени чтения для статьи.
 */
function zezh_reading_time($post_id = 0) {
    $post_id = $post_id ?: get_the_ID();
    $content = get_post_field('post_content', $post_id);
    $text    = wp_strip_all_tags($content);
    // Считаем Unicode-слова (работает для кириллицы)
    $words   = preg_match_all('/\p{L}+/u', $text, $m) ? count($m[0]) : 0;
    $minutes = (int) max(1, ceil($words / 200));
    return ['words' => $words, 'minutes' => $minutes];
}

/**
 * Построение оглавления (TOC) по заголовкам h2/h3 в контенте.
 * Модифицирует контент «на лету» — добавляет id к заголовкам.
 * Возвращает дерево: [['id','text','level','children'=>[...]]].
 */
function zezh_build_toc(&$content) {
    $tree = [];

    if (empty(trim($content))) return $tree;

    $dom = new DOMDocument('1.0', 'UTF-8');
    libxml_use_internal_errors(true);
    $dom->loadHTML('<?xml encoding="UTF-8"><div>' . $content . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    libxml_clear_errors();

    $xpath   = new DOMXPath($dom);
    $nodes   = $xpath->query('//h2 | //h3');
    $used    = [];
    $current = null;

    foreach ($nodes as $node) {
        $tag   = $node->tagName;
        $level = (int) substr($tag, 1);
        $text  = trim($node->textContent);

        if (empty($text)) continue;

        // Генерируем уникальный ID
        $base = sanitize_title($text);
        $id   = $base;
        $suffix = 0;
        while (isset($used[$id])) {
            $suffix++;
            $id = $base . '-' . $suffix;
        }
        $used[$id] = true;

        // Добавляем id к элементу
        $node->setAttribute('id', $id);

        $entry = ['id' => $id, 'text' => $text, 'level' => $level, 'children' => []];

        if ($level === 2) {
            $tree[]  = $entry;
            $current = &$tree[count($tree) - 1];
        } elseif ($level === 3 && $current) {
            $current['children'][] = $entry;
        } elseif ($level === 3) {
            // h3 без предшествующего h2 — добавляем на верхний уровень
            $tree[] = $entry;
        }
    }

    // Извлекаем HTML обратно
    $wrapper = $dom->getElementsByTagName('div')->item(0);
    $inner   = '';
    foreach ($wrapper->childNodes as $child) {
        $inner .= $dom->saveHTML($child);
    }
    $content = $inner;

    return $tree;
}

add_filter('document_title_separator', fn()=> '·');
