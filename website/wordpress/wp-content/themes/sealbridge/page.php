<?php
/**
 * Default page template.
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
                <?php if (is_page('about')) : ?>
                    <div class="intro-band">
                        <p>We support electrical enclosure, control cabinet, outdoor lighting, EV charging, solar inverter, and industrial equipment manufacturers across Canada, the United Kingdom, Europe, and other international markets.</p>
                    </div>
                <?php endif; ?>
                <?php the_content(); ?>
                <?php
            endwhile;
            ?>
        </article>
        <aside class="sidebar-box">
            <h2>Request Details</h2>
            <p>Share drawing, material, size, thickness, quantity, adhesive needs, and compliance requirements for a clearer quotation.</p>
            <a class="button" href="<?php echo esc_url(home_url('/contact/')); ?>">Contact Us</a>
        </aside>
    </div>
</section>

<?php
get_footer();
