# ZEZH Style Guide

Техническое руководство по созданию страниц в едином стиле.  
Здесь нет метафор — только классы, шрифты, цвета, стрелки и правила их применения.

---

## 1. Структура страницы

Каждая страница оборачивается в `<article class="shell ...">` или `<section class="shell ...">`.  
Класс `.shell` задаёт максимальную ширину **1180px** с отступами **20px** по бокам.

### Шапка страницы (page-head)

Используется на архивных страницах и страницах-заглушках:

```php
<section class="shell page-head">
    <p class="eyebrow">PROJECTS / SKILLS</p>
    <h1>Проекты</h1>
</section>
```

Допустимые форматы `.eyebrow`:

| Формат | Пример | Где используется |
|---|---|---|
| `TYPE / YEAR` | `STYLE GUIDE / 2026` | Страницы-манифесты |
| `TYPE / SUBTYPE` | `PROJECTS / SKILLS` | Архивы |
| `SECTION` | `NOTES`, `SERVICES`, `SELECTED WORK<` | Секции на главной |
| `BRAND / NUM` | `ZEZH / 418` | Главная страница |

---

## 2. Шрифты

| Шрифт | Использование |
|---|---|
| **IBM Plex Sans** | Основной текст, заголовки, кнопки, `.lead`, `.prose` |
| **IBM Plex Mono** | `.eyebrow`, даты (`<time>`), `.article-meta`, `code`, стрелки `← → ↑ ↓ ↗ ↙ ↘`, навигационные маркеры |

### Начертания IBM Plex Sans

| weight | Название | Где |
|---|---|---|
| 400 | Regular | `<body>`, `.prose p` |
| 500 | Medium | — (доступно, но не используется системно) |
| 600 | SemiBold | `.eyebrow`, `.button`, `h3`–`h4`, `.text-link`, `.lead` |
| 700 | Bold | `h1`, `h2`, `.section-head h2` |

### Начертания IBM Plex Mono

| weight | Название | Где |
|---|---|---|
| 400 | Regular | `.article-meta`, `time` |
| 500 | Medium | `code`, `.card span`, `.group-title span`, `.swatch-hex` |
| 600 | SemiBold | `.eyebrow` |

**Правило:** не смешивать две гарнитуры в одном смысловом блоке.  
Текст абзаца — Sans. Дата, стрелка, метка — Mono. Граница проходит по смыслу, а не по желанию.

---

## 3. Типографические классы

### `.eyebrow`

```css
font: 600 13px/1 "IBM Plex Mono", monospace;
letter-spacing: .12em;
color: var(--accent);
text-transform: uppercase;
```

Надзаголовочная строка. Всегда капсом (через `text-transform`).  
Располагается строго над `h1` или `h2`.

### `.lead`

```css
font-size: clamp(20px, 2.2vw, 30px);
max-width: 720px;
color: var(--muted);
line-height: 1.35;
```

Вводный абзац после заголовка. Используется на главной, в `single-project.php`, в `single-service.php`.

### `.prose`

```css
max-width: 780px;
margin: 60px auto;
font-size: 19px;
```

Обёртка для контента статьи. Внутри неё:
- `h2` — 38px, `letter-spacing: -.04em`, `margin-top: 2em`
- `h3` — 28px
- `a` — `color: var(--accent)`, `text-decoration: underline`
- `code` — IBM Plex Mono, фон `var(--surface)`, скругление 4px

### `.article`

```css
padding: 100px 0;
```

Обёртка для страницы записи (`single.php`, `single-project.php`). Заголовок внутри `.article header`.

---

## 4. Цвета (CSS-переменные)

Цвета задаются исключительно через переменные. **Никогда** не используйте hex-значения в стилях страниц.

| Переменная | Назначение | Светлая тема | Тёмная тема |
|---|---|---|---|
| `--bg` | Фон страницы | `#F0EFED` | `#262626` |
| `--surface` | Фон карточек и приподнятых блоков | `#FFFFFF` | `#2E2E2E` |
| `--text` | Основной цвет текста | `#262626` | `#F2EFEC` |
| `--muted` | Приглушённый текст (подписи, даты, `.lead`) | `#68635F` | `#B7AFA9` |
| `--line` | Границы и разделители | `#D7D2CE` | `#47413E` |
| `--accent` | Главный акцент (`.eyebrow`, ссылки, `.text-link`) | `#AF604C` | `#D07861` |
| `--accent2` | Дополнительный акцент (не используется системно) | `#8B45EE` | `#8B45EE` |
| `--header` | Фон залипающего хедера | `rgba(240,239,237,.78)` | `rgba(38,38,38,.8)` |
| `--shadow` | Тень карточек при наведении | `0 24px 70px rgba(38,38,38,.08)` | `0 24px 70px rgba(0,0,0,.2)` |

### Выбор цвета для элемента

```
Фон страницы           → var(--bg)
Фон блока/карточки     → var(--surface)
Основной текст         → var(--text)
Подпись, дата, намёк   → var(--muted)
Граница                → 1px solid var(--line)
Акцент                 → var(--accent)
```

---

## 5. Стрелки и навигационные символы

Используются ТОЛЬКО в IBM Plex Mono. Вставляются как обычные Unicode-символы внутри HTML:

| Символ | HTML-мнемоника | Где |
|---|---|---|
| `←` | `&larr;` | Навигация «назад» (лайтбокс) |
| `→` | `&rarr;` | Ссылки «перейти» (`.text-link`) |
| `↑` | `&uarr;` | Мобильное TOC |
| `↓` | `&darr;` | Якорь «читать дальше» |
| `↗` | `&nearr;` | Кнопка «Открыть проект» в `.card` |
| `↙` | `&swarr;` | Декоративный (пока не используется) |
| `◐` | `&#9680;` | Кнопка переключения темы |

**Правило:** стрелка всегда отделяется от текста пробелом (или `&nbsp;` в конце строки).  
Пример: `Все проекты →`, `Открыть проект ↗`.

---

## 6. Карточки и сетки

### `.cards` — сетка из трёх колонок

```css
display: grid;
grid-template-columns: repeat(3, 1fr);
gap: 18px;
```

На мобильных (`≤800px`) — одна колонка.

### `.card` — элемент сетки

```html
<article class="card">
    <a href="...">
        <img ...>
        <div class="card-body">
            <h2>Заголовок</h2>
            <p>Описание</p>
            <!-- Опционально: -->
            <span>Открыть проект ↗</span>
        </div>
    </a>
</article>
```

Карточка — всегда ссылка. Вся поверхность кликабельна.

### `.post-row` — строка в списке записей

```html
<a class="post-row" href="...">
    <time>01.01.2026</time>
    <strong>Заголовок</strong>
    <span>↗</span>
</a>
```

Grid: `120px 1fr auto`. Разделитель — `border-bottom: 1px solid var(--line)`.

---

## 7. Кнопки и ссылки

### `.button` — основная кнопка

```html
<a class="button" href="...">Смотреть проекты</a>
```

Инвертирует фон/текст: `background: var(--text); color: var(--bg)`.

### `.text-link` — текстовая ссылка с подчёркиванием

```html
<a class="text-link" href="...">Все проекты →</a>
```

Подчёркивание: `border-bottom: 1px solid var(--accent)`.

---

## 8. Статья (single-шаблоны)

Базовая структура для `single.php`, `single-project.php`, `single-service.php`:

```
get_header()
<article class="shell article">
    <header>
        <p class="eyebrow">ТИП_ЗАПИСИ</p>
        <h1>Заголовок</h1>
        <p class="lead">Краткое описание</p>
    </header>

    <!-- Опционально: мета-информация -->
    <div class="article-meta">
        <span>1200 слов</span>
        <span>7–9 мин. чтения</span>
    </div>

    <!-- Опционально: обложка -->
    <div class="article-cover">
        <img src="..." alt="">
    </div>

    <!-- Контент -->
    <div class="prose">
        <?php the_content(); ?>
    </div>
</article>
get_footer()
```

### `.article-meta`

```css
font: 400 13px/1 "IBM Plex Mono", monospace;
color: var(--muted);
```

Элементы разделяются через `·` (псевдоэлемент `::after` с `content: "·"` и `margin-left: 8px`).

---

## 9. Сервис (single-service.php)

Отличается от обычной статьи наличием hero-секции с размытым фоном:

```php
<article class="service-hero" style="--service-bg:url(...)">
    <div class="service-bg" aria-hidden="true"></div>
    <div class="shell service-content">
        <p class="eyebrow">SERVICE / 2026</p>
        <h1>Заголовок</h1>
        <p class="lead">Описание</p>
        <a class="button" href="#story">Почему, как и зачем ↓</a>
    </div>
</article>
<section id="story" class="shell article service-story">
    <div class="prose"><?php the_content(); ?></div>
</section>
```

`--service-bg` — inline-стиль с URL фонового изображения.

---

## 10. Переключение тем (логотипы)

Для изображений, зависящих от темы, используется стандартный паттерн:

```html
<div class="hero-mark">
    <img class="brand-dark" src=".../logo-dark.svg" alt="">
    <img class="brand-light" src=".../logo-light.svg" alt="">
</div>
```

CSS уже определён в `main.css`:
```css
.brand-light { display: none; }
:root[data-theme="dark"] .brand-dark { display: none; }
:root[data-theme="dark"] .brand-light { display: block; }
```

Скрипт `main.js` переключает `data-theme` на `<html>` — всё остальное работает автоматически.

---

## 11. Анимации: правило

По умолчанию анимаций **нет**. Исключения (уже в коде):

| Элемент | Анимация | Причина |
|---|---|---|
| `.card:hover` | `border-color: var(--muted)` | Функциональный отклик на интерактивном элементе |
| `.menu-toggle span` | `transform` при открытии меню | Индикатор состояния. У пользователя выпадут глаза |
| `.toc-sidebar a` | `color` + `border-color` | Подсветка активного пункта. Не совсем анимация, выделение |
| `.swatch-chip:hover` | `translateX(±4px)` **без перехода** | Рывок, не анимация. Функциональный элемент просмотра цвета |
| `.zezh-lb-overlay` | `opacity` | У пользователя будут болеть глаза если внезапно появится просмотрщик фото |

**Запрещено:** `fadeIn`, `fadeOut`, `@keyframes` для декоративных целей, `scale` на тексте/карточках, автономные появления блоков при скролле.

Анимации и разбытие используется **исключительно** в функциональных целях:
- Обозначение фокуса для размытия (применяется редко)
- Анимации раскрытия для интуитивности и отображения состояния
- Анимации переключения состояния для визуальной идентификации интерактивных объектов

---

## 12. Чек-лист для новой страницы

- [ ] Обёртка — `.shell`
- [ ] Шапка: `.eyebrow` + `h1`
- [ ] Вводный текст: `.lead` (если нужен)
- [ ] Цвета только через `var(--...)`
- [ ] Стрелки в IBM Plex Mono, с пробелом перед символом
- [ ] Карточки — `<article class="card">` внутри `<a>`
- [ ] `alt` у всех изображений
- [ ] `aria-label` у кнопок без текста
- [ ] `aria-hidden="true"` у декоративных изображений
- [ ] Адаптив проверен на `≤800px`
- [ ] Светлая и тёмная темы проверены
- [ ] Нет анимаций, кроме разрешённых
 