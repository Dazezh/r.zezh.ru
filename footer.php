</main>
<footer class="site-footer">
    <div class="shell footer-grid">
        <div>
            <a class="text-link" href="https://github.com/Dazezh/r.zezh.ru">Исходный код на GitHub</a>
            <p>Веб, сервисы и проекты без корпоративного тумана.</p>
        </div>
        <div><?php wp_nav_menu(['theme_location' => 'footer', 'container' => false, 'fallback_cb' => false, 'menu_class' => 'footer-menu']); ?></div>
        <div class="muted">© <?php echo date('Y'); ?> Роман Бальчонок</div>
    </div>
</footer>
    <aside class="cookie-notice" id="cookie-notice" hidden>
        <div class="shell cookie-notice-inner">
            <p>Здесь собираются куки-файлы — без них сайт не сможет быть удобным. Никакой слежки, только то, что нужно для работы. Подробнее — в <a href="<?php echo esc_url(get_privacy_policy_url()); ?>">политике конфиденциальности</a>.</p>
            <button class="button cookie-notice-ok" id="cookie-notice-ok">Ок</button>
        </div>
    </aside><?php wp_footer(); ?></body>

</html>