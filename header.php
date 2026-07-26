<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link
        rel="icon"
        type="image/x-icon"
        id="zezh-favicon"
        href="<?php echo esc_url(get_template_directory_uri() . '/assets/img/logo/favicon/favicon-white.ico'); ?>"
        data-active="<?php echo esc_url(get_template_directory_uri() . '/assets/img/logo/favicon/favicon-white.ico'); ?>"
        data-hidden="<?php echo esc_url(get_template_directory_uri() . '/assets/img/logo/favicon/favicon-black.ico'); ?>"
    >
    <script>try { document.documentElement.dataset.theme = localStorage.getItem('zezh-theme') || ((matchMedia('(prefers-color-scheme:dark)').matches) ? 'dark' : 'light') } catch (e) { }</script>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>><?php wp_body_open(); ?>
    <header class="site-header">
        <div class="shell nav-wrap">
            <a class="brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="На главную">
                <img class="brand-dark"
                    src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/logo/icon-dark.svg'); ?>"
                    alt="ZEZH">
                <img class="brand-light"
                    src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/logo/icon-light.svg'); ?>"
                    alt="ZEZH">
            </a>
            <nav aria-label="Основное меню">
                <?php wp_nav_menu(['theme_location' => 'primary', 'container' => false, 'fallback_cb' => false]); ?></nav>
            <button class="menu-toggle" type="button" aria-label="Открыть меню" aria-expanded="false">
                <span></span><span></span>
            </button>
            <button class="theme-toggle" type="button" aria-label="Сменить тему"><span>◐</span></button>
        </div>
    </header>
    <main>