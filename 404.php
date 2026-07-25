<?php get_header(); ?>
<section class="shell error-page">
    <p class="eyebrow">ERROR / 404</p>
    <h1>Тут ничего нет.</h1>
    <p class="lead">Ссылка либо умерла, либо никогда и не жила. Интернет умеет и то и другое.</p><a class="button"
        href="<?php echo esc_url(home_url('/')); ?>">На главную</a>
</section><?php get_footer(); ?>