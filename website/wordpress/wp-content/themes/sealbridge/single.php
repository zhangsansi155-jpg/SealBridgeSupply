<?php
/**
 * Single post template.
 *
 * @package SealBridge
 */

get_header();
?>

<section class="section">
    <div class="section-inner content-layout">
        <article class="entry-content">
            <?php
            while (have_posts()) :
                the_post();
                ?>
                <h1 class="page-title"><?php the_title(); ?></h1>
                <p class="eyebrow"><?php echo esc_html(get_the_date()); ?></p>
                <?php the_content(); ?>
                <?php
            endwhile;
            ?>
        </article>
        <aside class="sidebar-box">
            <h2>Need a gasket quote?</h2>
            <p>Send the drawing, material target, working environment, and certificate requirements.</p>
            <a class="button" href="<?php echo esc_url(home_url('/contact/')); ?>">Request a Quote</a>
        </aside>
    </div>
</section>

<?php
get_footer();
