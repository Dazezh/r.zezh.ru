<?php
/**
 * Template Name: Поговорим о стиле
 *
 * Страница-манифест дизайн-системы ZEZH:
 * цвета, типографика и философия визуального языка.
 */

get_header(); ?>

<article class="shell style-page">

    <!-- ================================================================ -->
    <!--  Шапка                                                            -->
    <!-- ================================================================ -->
    <header class="style-header">
        <p class="eyebrow">STYLE GUIDE / <?php echo esc_html(get_the_date('Y')); ?></p>
        <h1><?php the_title(); ?></h1>

        <div class="style-hero-mark">
            <img class="brand-dark"
                src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/r-style/r-dark.svg'); ?>"
                alt="Логотип ZEZH — тёмная версия"
                width="330" height="349">
            <img class="brand-light"
                src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/r-style/r-light.svg'); ?>"
                alt="Логотип ZEZH — светлая версия"
                width="330" height="349">
        </div>
    </header>

    <!-- ================================================================ -->
    <!--  Вступительный текст                                              -->
    <!-- ================================================================ -->
    <div class="style-intro">
        <p class="lead">«Давай будем немного тише, хорошо?»</p>

        <p>Вместо бесконечных анимаций, вымученых корпоративных цветов и попыток удержать внимание здесь
            используются геометрия, типографика и тёплая палитра, вдохновлённая современным
            веб-конструктивизмом.</p>

        <p>Мне не нужно демонстрировать возможности браузера. Мне интереснее узнать, состоится ли 
            у нас разговор.</p>
    </div>

    <!-- ================================================================ -->
    <!--  Палитра                                                          -->
    <!-- ================================================================ -->
    <section class="style-palette" aria-labelledby="palette-title">
        <h2 id="palette-title" class="style-section-title">Цвета</h2>

        <div class="style-swatches">
            <?php
            $colors = [
                [
                    'var'    => '--bg',
                    'name'   => 'Фон',
                    'desc'   => 'Основа всего полотна. Тёплый, как бумага.',
                    'light'  => '#F0EFED',
                    'dark'   => '#262626',
                ],
                [
                    'var'    => '--surface',
                    'name'   => 'Поверхность',
                    'desc'   => 'Карточки и приподнятые блоки.',
                    'light'  => '#FFFFFF',
                    'dark'   => '#2E2E2E',
                ],
                [
                    'var'    => '--text',
                    'name'   => 'Текст',
                    'desc'   => 'Основной цвет набора. Достаточно контрастный, но не резкий.',
                    'light'  => '#262626',
                    'dark'   => '#F2EFEC',
                ],
                [
                    'var'    => '--muted',
                    'name'   => 'Приглушённый',
                    'desc'   => 'Второстепенный текст, подписи, даты.',
                    'light'  => '#68635F',
                    'dark'   => '#B7AFA9',
                ],
                [
                    'var'    => '--line',
                    'name'   => 'Линия',
                    'desc'   => 'Разделители и границы — едва заметные, но держат сетку.',
                    'light'  => '#D7D2CE',
                    'dark'   => '#47413E',
                ],
                [
                    'var'    => '--accent',
                    'name'   => 'Кирпичный',
                    'desc'   => 'Главный акцент. Цвет дома и спокойствия.',
                    'light'  => '#AF604C',
                    'dark'   => '#D07861',
                ],
            ];

            foreach ($colors as $c): ?>
                <article class="swatch-card">
                    <div class="swatch-visual">
                        <span class="swatch-chip swatch-chip--light"
                            style="--swatch-color:<?php echo esc_attr($c['light']); ?>"
                            aria-label="<?php echo esc_attr($c['name']); ?> в светлой теме"></span>
                        <span class="swatch-chip swatch-chip--dark"
                            style="--swatch-color:<?php echo esc_attr($c['dark']); ?>"
                            aria-label="<?php echo esc_attr($c['name']); ?> в тёмной теме"></span>
                    </div>
                    <div class="swatch-info">
                        <h3><?php echo esc_html($c['name']); ?></h3>
                        <code><?php echo esc_html($c['var']); ?></code>
                        <p><?php echo esc_html($c['desc']); ?></p>
                        <div class="swatch-hex">
                            <span><?php echo esc_html($c['light']); ?></span>
                            <span class="muted">/</span>
                            <span><?php echo esc_html($c['dark']); ?></span>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ================================================================ -->
    <!--  Типографика                                                      -->
    <!-- ================================================================ -->
    <section class="style-type" aria-labelledby="type-title">
        <h2 id="type-title" class="style-section-title">Типографика</h2>

        <!-- Блок 1: Навигационные маркеры -->
        <div class="type-block type-block--markers">
            <div class="type-markers-showcase">
                <span class="eyebrow">SELECTED WORK<</span>
                <span class="eyebrow">PROJECTS / SKILLS</span>
                <span class="eyebrow">STYLE GUIDE / 2026</span>
            </div>
            <div class="type-arrows" aria-hidden="true">
                ← &nbsp;→ &nbsp;↑ &nbsp;↓ &nbsp;↗ &nbsp;↙
            </div>
            <p class="type-caption">Мелкие подписи и стрелки не декоративны. Это интуитивная карта страницы:
                <strong>IBM Plex Mono</strong> визуально отделяет навигационные маркеры от повествования.</p>
        </div>

        <!-- Блок 2: Сравнение Sans / Mono -->
        <div class="type-block type-block--compare">
            <div class="type-col">
                <code class="type-label">IBM Plex Sans</code>
                <div class="type-specimen type-specimen--sans">
                    <p style="font-weight:400">Regular — «Давай будем немного тише, хорошо?»</p>
                    <p style="font-weight:500">Medium — геометрия, типографика, тёплая палитра</p>
                    <p style="font-weight:600">SemiBold — веб-конструктивизм</p>
                    <p style="font-weight:700">Bold — принять кирпич</p>
                </div>
            </div>
            <div class="type-col">
                <code class="type-label">IBM Plex Mono</code>
                <div class="type-specimen type-specimen--mono">
                    <p style="font-weight:400">Regular — ← → ↑ ↓ ↗ ↙</p>
                    <p style="font-weight:500">Medium — SELECTED WORK<</p>
                    <p style="font-weight:600">SemiBold — 2026 / КИРПИЧНЫЙ</p>
                    <p style="font-weight:400; color:var(--muted)">Единая гарнитура без смешивания разных шрифтов</p>
                </div>
            </div>
        </div>

        <!-- Блок 3: Буква «К» -->
        <div class="type-block type-block--ka">
            <div class="type-ka-big" aria-hidden="true">
                <span class="type-ka-char">К</span>
                <span class="type-ka-char">к</span>
            </div>
            <p class="type-caption">IBM Plex — один из немногих шрифтов, одинаково качественно проработанных
                для латиницы и кириллицы. Он рисует «К» так, как она должна выглядеть.</p>
        </div>

        <!-- Блок 4: Манифест -->
        <div class="type-block type-block--manifesto">
            <p class="type-manifesto">Я не показываю тебе дизайн.<br>Я разговариваю с тобой.</p>
            <p class="type-manifesto-sub">Пытаюсь вызвать эмоцию, заставить остановиться,
                согласиться, поспорить или хотя&nbsp;бы задуматься.</p>
            <p class="type-manifesto-accent">А ещё… принять кирпич.</p>
        </div>
    </section>

    <!-- ================================================================ -->
    <!--  Заключительный текст                                             -->
    <!-- ================================================================ -->
    <div class="style-outro">
        <div class="style-outro-mark">
            <img class="brand-dark"
                src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/r-style/r-dark.svg'); ?>"
                alt=""
                width="140" height="148"
                aria-hidden="true">
            <img class="brand-light"
                src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/r-style/r-light.svg'); ?>"
                alt=""
                width="140" height="148"
                aria-hidden="true">
        </div>

        <p>Что такое кирпичный? Это цвет дома и спокойствия, а строгая композиция создаёт ощущение
            порядка, где каждая деталь находится на своём месте. Давай сделаем этот мир конструктивнее?</p>
    </div>

</article>

<?php get_footer(); ?>
