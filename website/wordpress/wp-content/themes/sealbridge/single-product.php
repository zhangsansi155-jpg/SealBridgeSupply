<?php
/**
 * Single product template.
 *
 * @package SealBridge
 */

get_header();
?>

<section class="section">
    <div class="section-inner">
        <article class="product-detail">
            <?php
            while (have_posts()) :
                the_post();
                $image_url = sealbridge_product_image_url();
                $specs = sealbridge_product_specs();
                $gallery = sealbridge_product_gallery();
                $applications = sealbridge_product_applications();
                $parameter_groups = sealbridge_product_parameters();
                $video_url = (string) get_post_meta(get_the_ID(), '_sealbridge_product_video', true);
                ?>
                <div class="product-gallery" data-product-gallery>
                    <div class="product-gallery-main">
                        <img data-gallery-main src="<?php echo esc_url($image_url); ?>" alt="<?php the_title_attribute(); ?>">
                    </div>
                    <div class="product-thumbs" aria-label="<?php esc_attr_e('Product image angles', 'sealbridge'); ?>">
                        <?php foreach ($gallery as $index => $item) : ?>
                            <button class="product-thumb<?php echo $index === 0 ? ' is-active' : ''; ?>" type="button" data-gallery-src="<?php echo esc_url($item[1]); ?>">
                                <img src="<?php echo esc_url($item[1]); ?>" alt="<?php echo esc_attr($item[0]); ?>">
                                <span><?php echo esc_html($item[0]); ?></span>
                            </button>
                        <?php endforeach; ?>
                    </div>
                    <?php if ($video_url !== '') : ?>
                        <div class="product-video-panel">
                            <video controls preload="metadata" poster="<?php echo esc_url($image_url); ?>">
                                <source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
                            </video>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="product-summary">
                    <p class="eyebrow">Product Category</p>
                    <h1 class="page-title"><?php the_title(); ?></h1>
                    <p class="product-lead"><?php echo esc_html(get_the_excerpt()); ?></p>
                    <dl class="product-spec-table">
                        <div><dt>Material</dt><dd><?php echo esc_html($specs[0]); ?></dd></div>
                        <div><dt>Process</dt><dd><?php echo esc_html($specs[1]); ?></dd></div>
                        <div><dt>Application</dt><dd><?php echo esc_html($specs[2]); ?></dd></div>
                        <div><dt>Quote Info</dt><dd><?php echo esc_html($specs[3]); ?></dd></div>
                    </dl>
                    <h2 class="compact-heading">Common Scenarios</h2>
                    <div class="application-chip-list">
                        <?php foreach ($applications as $application) : ?>
                            <span><?php echo esc_html($application); ?></span>
                        <?php endforeach; ?>
                    </div>
                    <div class="product-card-actions">
                        <a class="button" href="<?php echo esc_url(home_url('/contact/')); ?>">Request Quote</a>
                        <a class="button secondary" href="<?php echo esc_url(home_url('/products/')); ?>">Back to Products</a>
                    </div>
                    <div class="quote-contact-mini">
                        <a href="https://wa.me/8613800000000" target="_blank" rel="noopener">WhatsApp: +86 138 0000 0000</a>
                        <a href="https://www.facebook.com/sealbridgesupply" target="_blank" rel="noopener">Facebook: SealBridge Supply</a>
                    </div>
                </div>
                <div class="entry-content product-rich-content">
                    <?php the_content(); ?>
                </div>
                <?php if ($parameter_groups) : ?>
                    <div class="product-parameter-section">
                        <div class="section-header">
                            <span class="eyebrow">Technical Reference</span>
                            <h2>Parameters and Custom Options</h2>
                            <p>Use these fields to compare the product direction before sending drawings, samples, or a quotation request.</p>
                        </div>
                        <div class="product-parameter-grid">
                            <?php foreach ($parameter_groups as $group_title => $items) : ?>
                                <section class="parameter-card">
                                    <h3><?php echo esc_html($group_title); ?></h3>
                                    <ul>
                                        <?php foreach ($items as $item) : ?>
                                            <li><?php echo esc_html($item); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </section>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                <?php
            endwhile;
            ?>
        </article>
    </div>
</section>

<?php
get_footer();
