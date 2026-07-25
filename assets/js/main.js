(() => {
    const root = document.documentElement, toggle = document.querySelector('.theme-toggle');
    const favLight = document.querySelector('#zezh-favicon-light');
    const favDark = document.querySelector('#zezh-favicon-dark');

    const syncFavicon = () => {
        const hidden = document.visibilityState === 'hidden';
        // Вкладка активна → белая иконка; неактивна → тёмная
        if (favLight) favLight.disabled = hidden;
        if (favDark) favDark.disabled = !hidden;
    };

    const set = t => {
        root.dataset.theme = t;
        try { localStorage.setItem('zezh-theme', t) } catch (e) { }
        toggle?.setAttribute('aria-label', t === 'dark' ? 'Включить светлую тему' : 'Включить тёмную тему');
    };

    toggle?.addEventListener('click', () => set(root.dataset.theme === 'dark' ? 'light' : 'dark'));
    syncFavicon();
    document.addEventListener('visibilitychange', syncFavicon);

    /* --- Mobile menu --- */
    const menuToggle = document.querySelector('.menu-toggle');
    const body = document.body;

    const closeMenu = () => {
        body.classList.remove('menu-open');
        menuToggle?.setAttribute('aria-expanded', 'false');
        menuToggle?.setAttribute('aria-label', 'Открыть меню');
    };

    const openMenu = () => {
        body.classList.add('menu-open');
        menuToggle?.setAttribute('aria-expanded', 'true');
        menuToggle?.setAttribute('aria-label', 'Закрыть меню');
    };

    menuToggle?.addEventListener('click', () => {
        body.classList.contains('menu-open') ? closeMenu() : openMenu();
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && body.classList.contains('menu-open')) closeMenu();
    });

    document.querySelector('.nav-wrap nav')?.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            if (body.classList.contains('menu-open')) closeMenu();
        });
    });
})();
