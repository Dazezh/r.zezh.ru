(() => {
    const root = document.documentElement, toggle = document.querySelector('.theme-toggle');
    const favicon = document.getElementById('zezh-favicon');

    const syncFavicon = () => {
        if (!favicon) return;

        favicon.href = document.visibilityState === 'hidden'
            ? favicon.dataset.hidden
            : favicon.dataset.active;
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

    /* --- Cookie consent --- */
    const cookieNotice = document.getElementById('cookie-notice');
    const cookieOk = document.getElementById('cookie-notice-ok');

    if (cookieNotice && cookieOk && !localStorage.getItem('zezh-cookie-ok')) {
        cookieNotice.hidden = false;
        cookieOk.addEventListener('click', () => {
            cookieNotice.hidden = true;
            try { localStorage.setItem('zezh-cookie-ok', '1') } catch (e) { }
        });
    }
})();
