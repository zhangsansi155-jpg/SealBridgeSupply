<?php
/**
 * Articles library landing page.
 *
 * @package SealBridge
 */

get_header();

$article_categories = get_categories([
    'hide_empty' => true,
    'slug' => [
        'material-selection',
        'enclosure-sealing',
        'gasket-quotation',
        'compliance-testing',
        'manufacturing-guides',
    ],
]);

$articles = new WP_Query([
    'post_type' => 'post',
    'posts_per_page' => 12,
    'post_status' => 'publish',
]);
?>

<section class="section">
    <div class="section-inner">
        <div class="section-header">
            <div>
                <span class="eyebrow">Technical Library</span>
                <h1 class="page-title">Gasket Selection and Sourcing Articles</h1>
            </div>
            <p>Practical guides for material selection, enclosure sealing, gasket quotations, compliance questions, and custom manufacturing.</p>
        </div>

        <?php if ($article_categories) : ?>
            <div class="grid products">
                <?php foreach ($article_categories as $article_category) : ?>
                    <article class="card">
                        <div class="card-marker"></div>
                        <h2><a href="<?php echo esc_url(get_category_link($article_category->term_id)); ?>"><?php echo esc_html($article_category->name); ?></a></h2>
                        <p><?php echo esc_html(wp_strip_all_tags(category_description($article_category->term_id))); ?></p>
                        <a class="text-link" href="<?php echo esc_url(get_category_link($article_category->term_id)); ?>">View <?php echo esc_html($article_category->count); ?> Guides</a>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="section muted-section">
    <div class="section-inner">
        <div class="section-header">
            <div>
                <span class="eyebrow">Latest Updates</span>
                <h2>Latest Gasket Buying Guides</h2>
            </div>
            <p>Start with the application closest to your project, then send the drawing and operating conditions for a quotation review.</p>
        </div>
        <div class="grid products">
            <?php if ($articles->have_posts()) : ?>
                <?php while ($articles->have_posts()) : $articles->the_post(); ?>
                    <article class="article-card">
                        <span class="article-meta"><?php echo esc_html(get_the_date()); ?> · <?php echo esc_html(get_the_category()[0]->name ?? 'Gasket Guide'); ?></span>
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <p><?php echo esc_html(get_the_excerpt()); ?></p>
                        <a class="text-link" href="<?php the_permalink(); ?>">Read Article</a>
                    </article>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php
get_footer();
