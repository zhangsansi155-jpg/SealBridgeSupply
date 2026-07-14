<?php
/**
 * Single application template.
 *
 * @package SealBridge
 */

get_header();
?>

<section class="section">
    <div class="section-inner content-layout">
        <article class="entry-content application-article">
            <?php
            while (have_posts()) :
                the_post();
                $article = sealbridge_application_article();
                ?>
                <p class="eyebrow">Application</p>
                <h1 class="page-title"><?php echo esc_html(sealbridge_application_display_title()); ?></h1>
                <div class="intro-band">
                    <p><?php echo esc_html($article['summary']); ?></p>
                </div>
                <h2>What This Scenario Needs</h2>
                <p><?php echo esc_html(get_the_excerpt()); ?></p>
                <h2>Common Buyer Pain Points</h2>
                <ul class="feature-list">
                    <?php foreach ($article['pain_points'] as $pain_point) : ?>
                        <li><?php echo esc_html($pain_point); ?></li>
                    <?php endforeach; ?>
                </ul>
                <h2>Recommended Material Direction</h2>
                <ul>
                    <?php foreach ($article['materials'] as $material) : ?>
                        <li><?php echo esc_html($material); ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php if (!empty($article['structures'])) : ?>
                    <h2>Common Gasket Structures</h2>
                    <ul>
                        <?php foreach ($article['structures'] as $structure) : ?>
                            <li><?php echo esc_html($structure); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <?php if (!empty($article['selection_factors'])) : ?>
                    <h2>Selection Factors to Confirm</h2>
                    <ul class="feature-list">
                        <?php foreach ($article['selection_factors'] as $selection_factor) : ?>
                            <li><?php echo esc_html($selection_factor); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <h2>Related Product Categories</h2>
                <div class="related-products-grid">
                    <?php foreach ($article['products'] as $product_slug) :
                        $product = get_page_by_path($product_slug, OBJECT, 'product');
                        if (!$product) {
                            continue;
                        }
                        ?>
                        <a class="related-product-card" href="<?php echo esc_url(get_permalink($product)); ?>">
                            <img src="<?php echo esc_url(sealbridge_product_image_url($product)); ?>" alt="<?php echo esc_attr(get_the_title($product)); ?>">
                            <span><?php echo esc_html(get_the_title($product)); ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
                <h2>Information Needed for Quotation</h2>
                <table>
                    <tbody>
                        <?php foreach ($article['quote'] as $index => $item) : ?>
                            <tr>
                                <th><?php echo esc_html('Item ' . ($index + 1)); ?></th>
                                <td><?php echo esc_html($item); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <h2>Practical Note</h2>
                <p>For IP-rated enclosure projects, the gasket supports sealing performance, but the final IP rating belongs to the complete enclosure assembly and test condition. Material choice, compression design, installation method, and production tolerance should be reviewed together.</p>
                <?php if (!empty($article['related_articles'])) : ?>
                    <h2>Related Technical Guides</h2>
                    <ul>
                        <?php foreach ($article['related_articles'] as $article_slug) :
                            $related_article = get_page_by_path($article_slug, OBJECT, 'post');
                            if (!$related_article) {
                                continue;
                            }
                            ?>
                            <li><a href="<?php echo esc_url(get_permalink($related_article)); ?>"><?php echo esc_html(get_the_title($related_article)); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <?php
            endwhile;
            ?>
        </article>
        <aside class="sidebar-box">
            <h2>Scenario to Product</h2>
            <p>Use this page to understand the sealing scenario, then open the related product category for material, process, photos, and quote details.</p>
            <a class="button" href="<?php echo esc_url(home_url('/products/')); ?>">View Product Categories</a>
        </aside>
    </div>
</section>

<?php
get_footer();
