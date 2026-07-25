</main>
<footer class="site-footer">
    <div class="shell footer-grid">
        <div>
            <a class="text-link" href="https://github.com/Dazezh/r.zezh.ru">Исходный код на GitHub</a>
            <p>Веб, сервисы и проекты без корпоративного тумана.</p>
        </div>
        <div><?php wp_nav_menu(['theme_location' => 'footer', 'container' => false, 'fallback_cb' => false]); ?></div>
        <div class="muted">© <?php echo date('Y'); ?> Роман Бальчонок</div>
    </div>
</footer><?php wp_footer(); ?></body>

</html>