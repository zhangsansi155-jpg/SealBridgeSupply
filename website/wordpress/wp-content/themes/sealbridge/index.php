<?php
/**
 * Main blog index.
 *
 * @package SealBridge
 */

get_header();
?>

<section class="section">
    <div class="section-inner">
        <div class="section-header">
            <h1 class="page-title"><?php echo esc_html(is_category() ? single_cat_title('', false) : (get_the_archive_title() ?: 'Industry Content')); ?></h1>
            <p><?php echo esc_html(is_category() && category_description() ? wp_strip_all_tags(category_description()) : 'Material notes, sourcing guides, and enclosure gasket application references.'); ?></p>
        </div>
        <div class="grid products">
            <?php
            if (have_posts()) :
                while (have_posts()) :
                    the_post();
                    ?>
                    <article class="card">
                        <div class="card-marker"></div>
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <p><?php echo esc_html(get_the_excerpt()); ?></p>
                    </article>
                    <?php
                endwhile;
            else :
                ?>
                <article class="card">
                    <h2>No content yet</h2>
                    <p>Add posts about materials, compliance, applications, and sourcing guidance.</p>
                </article>
                <?php
            endif;
            ?>
        </div>
    </div>
</section>

<?php
get_footer();
