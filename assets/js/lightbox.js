/**
 * ZEZH Lightbox — полноэкранный просмотрщик изображений
 * с зумом (колёсико, кнопки, pinch) и навигацией.
 *
 * Ожидает, что изображения в контенте обёрнуты в <a data-zezh-lightbox>
 * со ссылкой на полноразмерную версию.
 */
(() => {
    'use strict';

    /* ------------------------------------------------------------------ */
    /*  Конфигурация                                                       */
    /* ------------------------------------------------------------------ */
    const CONFIG = {
        zoomStep: 0.35,       // шаг зума колёсиком / кнопкой
        minScale: 0.4,        // минимальный масштаб
        maxScale: 6,          // максимальный масштаб
        swipeThreshold: 60,   // пикселей для свайпа на мобильных
        doubleTapMs: 320,     // окно для двойного тапа
    };

    /* ------------------------------------------------------------------ */
    /*  Состояние                                                          */
    /* ------------------------------------------------------------------ */
    const state = {
        items: [],            // [{ el: <a>, href: '...' }]
        index: 0,
        scale: 1,
        tx: 0, ty: 0,        // translate (px)
        open: false,
        loading: false,
        dragging: false,
        dragStart: { x: 0, y: 0 },
        dragTx: 0, dragTy: 0,
        // Pinch
        pinchStartDist: 0,
        pinchStartScale: 1,
        pinchMid: { x: 0, y: 0 },
        // Double-tap
        lastTap: 0,
    };

    /* ------------------------------------------------------------------ */
    /*  DOM-элементы                                                       */
    /* ------------------------------------------------------------------ */
    let overlay, stage, imgWrap, img, spinner;
    let btnClose, btnPrev, btnNext, btnZoomIn, btnZoomOut, zoomLabel, counter;

    /* ------------------------------------------------------------------ */
    /*  Сборка DOM                                                         */
    /* ------------------------------------------------------------------ */
    function buildDOM() {
        overlay = document.createElement('div');
        overlay.className = 'zezh-lb-overlay';
        overlay.setAttribute('role', 'dialog');
        overlay.setAttribute('aria-modal', 'true');
        overlay.setAttribute('aria-label', 'Просмотр изображения');
        overlay.innerHTML = `
            <div class="zezh-lb-toolbar">
                <span class="zezh-lb-counter"></span>
                <div class="zezh-lb-zoom-group">
                    <button class="zezh-lb-btn zezh-lb-zoom-out" aria-label="Уменьшить" type="button">−</button>
                    <span class="zezh-lb-zoom-label">100%</span>
                    <button class="zezh-lb-btn zezh-lb-zoom-in" aria-label="Увеличить" type="button">+</button>
                </div>
                <button class="zezh-lb-btn zezh-lb-close" aria-label="Закрыть" type="button">✕</button>
            </div>
            <button class="zezh-lb-btn zezh-lb-arrow zezh-lb-arrow--prev" aria-label="Предыдущее" type="button">←</button>
            <button class="zezh-lb-btn zezh-lb-arrow zezh-lb-arrow--next" aria-label="Следующее" type="button">→</button>
            <div class="zezh-lb-stage">
                <div class="zezh-lb-img-wrap">
                    <div class="zezh-lb-spinner"></div>
                    <img alt="" />
                </div>
            </div>`;

        document.body.appendChild(overlay);

        // Кешируем ссылки
        stage    = overlay.querySelector('.zezh-lb-stage');
        imgWrap  = overlay.querySelector('.zezh-lb-img-wrap');
        img      = overlay.querySelector('.zezh-lb-img-wrap img');
        spinner  = overlay.querySelector('.zezh-lb-spinner');
        btnClose = overlay.querySelector('.zezh-lb-close');
        btnPrev  = overlay.querySelector('.zezh-lb-arrow--prev');
        btnNext  = overlay.querySelector('.zezh-lb-arrow--next');
        btnZoomIn  = overlay.querySelector('.zezh-lb-zoom-in');
        btnZoomOut = overlay.querySelector('.zezh-lb-zoom-out');
        zoomLabel  = overlay.querySelector('.zezh-lb-zoom-label');
        counter    = overlay.querySelector('.zezh-lb-counter');
    }

    /* ------------------------------------------------------------------ */
    /*  Сбор ссылок (все data-zezh-lightbox внутри .prose / .article-cover) */
    /* ------------------------------------------------------------------ */
    function collectItems() {
        const links = document.querySelectorAll(
            '.prose a[data-zezh-lightbox], .article-cover a[data-zezh-lightbox]'
        );
        state.items = Array.from(links).map(el => ({ el, href: el.href }));
    }

    /* ------------------------------------------------------------------ */
    /*  Показать / скрыть спиннер                                          */
    /* ------------------------------------------------------------------ */
    function showSpinner() {
        state.loading = true;
        spinner.style.display = '';
        img.style.opacity = '0';
    }

    function hideSpinner() {
        state.loading = false;
        spinner.style.display = 'none';
        img.style.opacity = '';
    }

    /* ------------------------------------------------------------------ */
    /*  Сброс трансформаций                                                */
    /* ------------------------------------------------------------------ */
    function resetTransform(animate = false) {
        state.scale = 1;
        state.tx = 0;
        state.ty = 0;
        imgWrap.classList.remove('is-dragging');
        if (!animate) {
            imgWrap.style.transition = 'none';
            applyTransform();
            // force reflow
            void imgWrap.offsetHeight;
            imgWrap.style.transition = '';
        } else {
            applyTransform();
        }
        updateZoomLabel();
    }

    function applyTransform() {
        imgWrap.style.transform =
            `translate(${state.tx}px, ${state.ty}px) scale(${state.scale})`;
    }

    function updateZoomLabel() {
        zoomLabel.textContent = Math.round(state.scale * 100) + '%';
    }

    function updateCursorClasses() {
        stage.classList.toggle('is-zoomed', state.scale > 1.01);
        stage.classList.toggle('is-fit', state.scale <= 1.01);
    }

    /* ------------------------------------------------------------------ */
    /*  Загрузка изображения по индексу                                    */
    /* ------------------------------------------------------------------ */
    function loadImage(index) {
        if (index < 0 || index >= state.items.length) return;
        state.index = index;
        resetTransform();
        showSpinner();

        const item = state.items[index];
        const src = item.href;

        // Предзагрузка
        const preload = new Image();
        preload.onload = () => {
            if (state.items[state.index]?.href !== src) return; // уже переключили
            img.src = src;
            img.alt = item.el.querySelector('img')?.alt || '';
            hideSpinner();
        };
        preload.onerror = () => {
            hideSpinner();
        };
        preload.src = src;

        updateUI();
    }

    function updateUI() {
        const total = state.items.length;
        counter.textContent = total > 1
            ? `${state.index + 1} / ${total}`
            : '';

        btnPrev.style.visibility = total > 1 ? '' : 'hidden';
        btnNext.style.visibility = total > 1 ? '' : 'hidden';
    }

    /* ------------------------------------------------------------------ */
    /*  Открыть / Закрыть                                                  */
    /* ------------------------------------------------------------------ */
    function open(startIndex) {
        if (state.open) return;
        collectItems();
        if (!state.items.length) return;

        state.open = true;
        document.body.style.overflow = 'hidden';
        overlay.classList.add('is-open');
        loadImage(startIndex);
    }

    function close() {
        if (!state.open) return;
        state.open = false;
        document.body.style.overflow = '';
        overlay.classList.remove('is-open');
        resetTransform();
    }

    function navigate(delta) {
        const newIdx = state.index + delta;
        if (newIdx < 0 || newIdx >= state.items.length) return;
        loadImage(newIdx);
    }

    /* ------------------------------------------------------------------ */
    /*  Зум                                                                */
    /* ------------------------------------------------------------------ */
    function zoom(step, originX = null, originY = null) {
        const oldScale = state.scale;
        let newScale = oldScale + step;

        // Мягкая граница
        if (newScale < CONFIG.minScale) newScale = CONFIG.minScale;
        if (newScale > CONFIG.maxScale) newScale = CONFIG.maxScale;
        if (Math.abs(newScale - oldScale) < 0.001) return;

        // Если задана точка фокуса — масштабируем относительно неё
        if (originX !== null && originY !== null) {
            const ratio = newScale / oldScale;
            state.tx = originX - ratio * (originX - state.tx);
            state.ty = originY - ratio * (originY - state.ty);
        }

        state.scale = newScale;
        imgWrap.classList.add('is-dragging');
        applyTransform();
        updateZoomLabel();
        updateCursorClasses();
    }

    function zoomToFit() {
        resetTransform(true);
        updateCursorClasses();
    }

    /* ------------------------------------------------------------------ */
    /*  Перетаскивание (pan)                                               */
    /* ------------------------------------------------------------------ */
    function onDragStart(e) {
        if (state.scale <= 1) return; // не перетаскиваем при fit
        e.preventDefault();
        state.dragging = true;
        state.dragTx = state.tx;
        state.dragTy = state.ty;

        const pt = e.touches ? e.touches[0] : e;
        state.dragStart = { x: pt.clientX, y: pt.clientY };
        stage.classList.add('is-grabbing');
        imgWrap.classList.add('is-dragging');
    }

    function onDragMove(e) {
        if (!state.dragging) return;
        e.preventDefault();

        const pt = e.touches ? e.touches[0] : e;
        state.tx = state.dragTx + (pt.clientX - state.dragStart.x);
        state.ty = state.dragTy + (pt.clientY - state.dragStart.y);
        applyTransform();
    }

    function onDragEnd() {
        if (!state.dragging) return;
        state.dragging = false;
        stage.classList.remove('is-grabbing');
    }

    /* ------------------------------------------------------------------ */
    /*  Pinch-zoom (мобильные)                                             */
    /* ------------------------------------------------------------------ */
    function onPinchStart(e) {
        if (e.touches.length !== 2) return;
        const dx = e.touches[0].clientX - e.touches[1].clientX;
        const dy = e.touches[0].clientY - e.touches[1].clientY;
        state.pinchStartDist = Math.hypot(dx, dy);
        state.pinchStartScale = state.scale;
        state.pinchMid = {
            x: (e.touches[0].clientX + e.touches[1].clientX) / 2,
            y: (e.touches[0].clientY + e.touches[1].clientY) / 2,
        };
    }

    function onPinchMove(e) {
        if (e.touches.length !== 2 || state.pinchStartDist <= 0) return;
        e.preventDefault();

        const dx = e.touches[0].clientX - e.touches[1].clientX;
        const dy = e.touches[0].clientY - e.touches[1].clientY;
        const dist = Math.hypot(dx, dy);
        const ratio = dist / state.pinchStartDist;
        let newScale = state.pinchStartScale * ratio;

        if (newScale < CONFIG.minScale) newScale = CONFIG.minScale;
        if (newScale > CONFIG.maxScale) newScale = CONFIG.maxScale;

        const realRatio = newScale / state.scale;
        state.tx = state.pinchMid.x - realRatio * (state.pinchMid.x - state.tx);
        state.ty = state.pinchMid.y - realRatio * (state.pinchMid.y - state.ty);
        state.scale = newScale;

        imgWrap.classList.add('is-dragging');
        applyTransform();
        updateZoomLabel();
        updateCursorClasses();
    }

    function onPinchEnd() {
        state.pinchStartDist = 0;
    }

    /* ------------------------------------------------------------------ */
    /*  Double-tap zoom (мобильные)                                        */
    /* ------------------------------------------------------------------ */
    function onDoubleTap(e) {
        const now = Date.now();
        if (now - state.lastTap < CONFIG.doubleTapMs) {
            e.preventDefault();
            if (state.scale > 1.1) {
                zoomToFit();
            } else {
                // Увеличиваем относительно точки тапа
                zoom(1.5, e.clientX, e.clientY);
            }
        }
        state.lastTap = now;
    }

    /* ------------------------------------------------------------------ */
    /*  Обработчики событий                                                */
    /* ------------------------------------------------------------------ */
    function bindEvents() {
        // Закрытие
        btnClose.addEventListener('click', close);
        overlay.addEventListener('click', e => {
            if (e.target === overlay || e.target === stage) close();
        });

        // Навигация
        btnPrev.addEventListener('click', () => navigate(-1));
        btnNext.addEventListener('click', () => navigate(1));

        // Кнопки зума
        btnZoomIn.addEventListener('click', () => {
            zoom(CONFIG.zoomStep);
        });
        btnZoomOut.addEventListener('click', () => {
            zoom(-CONFIG.zoomStep);
        });

        // Колёсико мыши
        overlay.addEventListener('wheel', e => {
            if (!state.open || state.loading) return;
            e.preventDefault();
            const delta = -Math.sign(e.deltaY) * CONFIG.zoomStep;
            zoom(delta, e.clientX, e.clientY);
        }, { passive: false });

        // Двойной клик по stage
        stage.addEventListener('dblclick', e => {
            if (state.scale > 1.1) {
                zoomToFit();
            } else {
                zoom(1.5, e.clientX, e.clientY);
            }
        });

        // Drag (мышь)
        stage.addEventListener('mousedown', onDragStart);
        window.addEventListener('mousemove', e => {
            if (state.dragging) onDragMove(e);
        });
        window.addEventListener('mouseup', onDragEnd);

        // Touch: drag + pinch
        stage.addEventListener('touchstart', e => {
            if (e.touches.length === 1) {
                onDragStart(e);
                onDoubleTap(e);
            } else if (e.touches.length === 2) {
                onPinchStart(e);
                state.dragging = false; // отменяем drag при pinch
            }
        }, { passive: false });

        stage.addEventListener('touchmove', e => {
            if (state.dragging && e.touches.length === 1) {
                onDragMove(e);
            } else if (e.touches.length === 2) {
                onPinchMove(e);
            }
        }, { passive: false });

        stage.addEventListener('touchend', e => {
            onDragEnd();
            onPinchEnd();
        });

        // Свайп для навигации (одно касание, не при зуме)
        let swipeStartX = 0;
        stage.addEventListener('touchstart', e => {
            if (e.touches.length === 1 && state.scale <= 1.01) {
                swipeStartX = e.touches[0].clientX;
            }
        }, { passive: true });

        stage.addEventListener('touchend', e => {
            if (state.scale > 1.01 || state.items.length < 2) return;
            const dx = (e.changedTouches[0]?.clientX || swipeStartX) - swipeStartX;
            if (Math.abs(dx) > CONFIG.swipeThreshold) {
                navigate(dx > 0 ? -1 : 1);
            }
        });

        // Клавиатура
        document.addEventListener('keydown', e => {
            if (!state.open) return;
            switch (e.key) {
                case 'Escape':   close(); break;
                case 'ArrowLeft':  navigate(-1); break;
                case 'ArrowRight': navigate(1); break;
                case '+':
                case '=':        zoom(CONFIG.zoomStep); break;
                case '-':        zoom(-CONFIG.zoomStep); break;
                case '0':        zoomToFit(); break;
            }
        });
    }

    /* ------------------------------------------------------------------ */
    /*  Привязка кликов к изображениям в контенте                          */
    /* ------------------------------------------------------------------ */
    function bindImageClicks() {
        document.addEventListener('click', e => {
            const link = e.target.closest('a[data-zezh-lightbox]');
            if (!link) return;
            e.preventDefault();

            collectItems();
            const idx = state.items.findIndex(item => item.el === link);
            open(idx >= 0 ? idx : 0);
        });
    }

    /* ------------------------------------------------------------------ */
    /*  Инициализация                                                      */
    /* ------------------------------------------------------------------ */
    function init() {
        buildDOM();
        bindEvents();
        bindImageClicks();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
