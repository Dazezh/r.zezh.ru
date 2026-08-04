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
    let menuScrollY = 0;

    const closeMenu = () => {
        body.classList.remove('menu-open');
        // body.style.top = ''; НЕ ВОЗВРАЩАТЬ, ЛОМАЕТСЯ ПРИ СКРОЛЛЕ ВВЕРХ, ЕСЛИ МЕНЮ БЫЛО ОТКРЫТО НА НИЖНЕЙ ЧАСТИ СТРАНИЦЫ
        window.scrollTo(0, menuScrollY);
        menuToggle?.setAttribute('aria-expanded', 'false');
        menuToggle?.setAttribute('aria-label', 'Открыть меню');
    };

    const openMenu = () => {
        menuScrollY = window.scrollY;
        // body.style.top = -menuScrollY + 'px'; АНАЛОГИЧНО
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

    /* --- Table of Contents --- */
    const tocDataEl = document.getElementById('zezh-toc-data');
    if (!tocDataEl) return;

    let tocData;
    try { tocData = JSON.parse(tocDataEl.textContent); } catch (e) { return; }
    if (!tocData.length) return;

    const tocIconDark  = tocDataEl.dataset.iconDark || '';
    const tocIconLight = tocDataEl.dataset.iconLight || '';

    const buildList = (items) => {
        const ul = document.createElement('ul');
        items.forEach(item => {
            const li = document.createElement('li');
            const a = document.createElement('a');
            a.href = '#' + item.id;
            a.textContent = item.text;
            li.appendChild(a);
            if (item.children && item.children.length) {
                li.appendChild(buildList(item.children));
            }
            ul.appendChild(li);
        });
        return ul;
    };

    // Desktop sidebar
    const sidebar = document.createElement('nav');
    sidebar.className = 'toc-sidebar';
    sidebar.setAttribute('aria-label', 'Содержание статьи');
    sidebar.appendChild(buildList(tocData));
    document.body.appendChild(sidebar);

    // Mobile bottom sheet
    const mobile = document.createElement('nav');
    mobile.className = 'toc-mobile';
    mobile.setAttribute('aria-label', 'Содержание статьи');
    mobile.innerHTML = `
        <button class="toc-mobile__toggle" aria-expanded="false">
            <img class="brand-dark" src="${tocIconDark}" alt="" aria-hidden="true" width="18" height="18">
            <img class="brand-light" src="${tocIconLight}" alt="" aria-hidden="true" width="18" height="18">
            Содержание
            <span class="toc-mobile__arrow" aria-hidden="true">↑</span>
        </button>
        <div class="toc-mobile__panel"></div>`;
    mobile.querySelector('.toc-mobile__panel').appendChild(buildList(tocData));
    document.body.appendChild(mobile);

    // Mobile toggle
    const mobileToggle = mobile.querySelector('.toc-mobile__toggle');
    mobileToggle.addEventListener('click', () => {
        const open = mobile.classList.toggle('toc-mobile--open');
        mobileToggle.setAttribute('aria-expanded', open);
    });

    // Scroll spy via IntersectionObserver
    const allLinks = document.querySelectorAll('.toc-sidebar a, .toc-mobile a');
    const headings = tocData.flatMap(item => {
        const els = [document.getElementById(item.id)];
        item.children?.forEach(child => els.push(document.getElementById(child.id)));
        return els;
    }).filter(Boolean);

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            const id = entry.target.id;
            allLinks.forEach(link => {
                link.classList.toggle('active', link.getAttribute('href') === '#' + id);
            });
        });
    }, { rootMargin: '-80px 0px -60% 0px' });

    headings.forEach(h => observer.observe(h));

    // Smooth scroll on click
    allLinks.forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            const target = document.getElementById(link.getAttribute('href').slice(1));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                // Close mobile panel after click
                mobile.classList.remove('toc-mobile--open');
                mobileToggle.setAttribute('aria-expanded', 'false');
            }
        });
    });
})();
