<?php
/**
 * Insights landing page.
 *
 * @package SealBridge
 */

get_header();
?>

<section class="section">
    <div class="section-inner">
        <div class="section-header">
            <div>
                <span class="eyebrow">Article Library</span>
                <h1 class="page-title"><?php the_title(); ?></h1>
            </div>
            <p>Industry notes, product selection guides, compliance explanations, and sourcing articles for enclosure gasket buyers.</p>
        </div>
        <div class="grid products">
            <?php
            $insights = new WP_Query([
                'post_type' => 'post',
                'posts_per_page' => 12,
                'post_status' => 'publish',
            ]);

            if ($insights->have_posts()) :
                while ($insights->have_posts()) :
                    $insights->the_post();
                    ?>
                    <article class="article-card">
                        <span class="article-meta"><?php echo esc_html(get_the_date()); ?></span>
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <p><?php echo esc_html(get_the_excerpt()); ?></p>
                        <a class="text-link" href="<?php the_permalink(); ?>">Read Article</a>
                    </article>
                    <?php
                endwhile;
                wp_reset_postdata();
            else :
                ?>
                <article class="card">
                    <h2>No articles yet</h2>
                    <p>Publish posts in WordPress to build the company content library.</p>
                </article>
                <?php
            endif;
            ?>
        </div>
    </div>
</section>

<?php
get_footer();
