<?php
/**
 * Front page template.
 *
 * @package SealBridge
 */

get_header();
?>

<section class="hero">
    <div class="section-inner hero-grid">
        <div>
            <span class="eyebrow">Custom Gasket Sourcing</span>
            <h1>Custom Gasket Sourcing for Electrical Enclosures & Control Cabinets</h1>
            <p>Sourcing, sampling, and production coordination for EPDM foam gaskets, silicone gaskets, adhesive-backed die-cut gaskets, sealing strips, and custom rubber parts.</p>
            <div class="hero-actions">
                <a class="button" href="<?php echo esc_url(home_url('/contact/')); ?>">Request a Quote</a>
                <a class="button secondary" href="<?php echo esc_url(home_url('/products/')); ?>">View Products</a>
            </div>
        </div>
        <aside class="spec-panel" aria-label="Project focus">
            <ul class="spec-list">
                <li><span>Materials</span><strong>EPDM, Silicone, NBR, Foam</strong></li>
                <li><span>Processes</span><strong>Die Cutting, Molding, Extrusion</strong></li>
                <li><span>Support</span><strong>Samples, TDS, SDS, Reports</strong></li>
                <li><span>Applications</span><strong>IP-rated Enclosure Sealing</strong></li>
            </ul>
        </aside>
    </div>
</section>

<section class="section white">
    <div class="section-inner">
        <div class="section-header">
            <h2>Hot Application Scenarios</h2>
            <p>Common buying situations that help customers choose the right gasket category faster.</p>
        </div>
        <div class="grid scenario-grid">
            <?php foreach (array_slice(sealbridge_application_scenarios(), 0, 6) as $scenario) : ?>
                <article class="scenario-card">
                    <span class="scenario-icon"></span>
                    <h3><a href="<?php echo esc_url(home_url('/applications/' . $scenario[3] . '/')); ?>"><?php echo esc_html($scenario[0]); ?></a></h3>
                    <p><?php echo esc_html($scenario[1]); ?></p>
                    <strong>Recommended: <?php echo esc_html($scenario[2]); ?></strong>
                </article>
            <?php endforeach; ?>
        </div>
        <div class="section-link-row">
            <a class="button secondary" href="<?php echo esc_url(home_url('/applications/')); ?>">View Scenario Details</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="section-inner">
        <div class="section-header">
            <h2>Product Categories</h2>
            <p>Start from the gasket family, then match it to the enclosure or equipment application.</p>
        </div>
        <div class="grid home-category-grid">
            <?php
            $featured_products = new WP_Query([
                'post_type' => 'product',
                'posts_per_page' => 4,
                'orderby' => 'menu_order title',
                'order' => 'ASC',
            ]);

            if ($featured_products->have_posts()) :
                while ($featured_products->have_posts()) :
                    $featured_products->the_post();
                    $applications = sealbridge_product_applications();
                    ?>
                    <article class="home-category-card">
                        <a class="home-category-media" href="<?php the_permalink(); ?>">
                            <img src="<?php echo esc_url(sealbridge_product_image_url()); ?>" alt="<?php the_title_attribute(); ?>">
                        </a>
                        <div class="home-category-body">
                            <p class="product-type">Product category</p>
                            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <p><?php echo esc_html(get_the_excerpt()); ?></p>
                            <div class="application-chip-list">
                                <?php foreach ($applications as $application) : ?>
                                    <span><?php echo esc_html($application); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </article>
                    <?php
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
        <div class="section-link-row">
            <a class="button" href="<?php echo esc_url(home_url('/products/')); ?>">View All Product Categories</a>
        </div>
    </div>
</section>

<section class="section white">
    <div class="section-inner">
        <div class="section-header">
            <h2>Factory Screening</h2>
            <p>One review flow for factory matching, sample response, workshop references, and certificate/document checks.</p>
        </div>
        <div class="trust-strip">
            <div><strong>20+</strong><span>screened production partners and factory contacts</span></div>
            <div><strong>4</strong><span>main process routes: die cutting, molding, extrusion, adhesive backing</span></div>
            <div><strong>5</strong><span>common document types: RoHS, REACH, UL94, TDS, SDS</span></div>
        </div>
        <div class="factory-screening-layout">
            <div class="factory-screening-points">
                <article>
                    <span>01</span>
                    <h3>Factory Fit</h3>
                    <p>Match the inquiry to die cutting, molding, extrusion, foam material, or adhesive lamination partners.</p>
                </article>
                <article>
                    <span>02</span>
                    <h3>Sample & Workshop Review</h3>
                    <p>Check sample photos, workshop references, profile details, response speed, and production communication quality.</p>
                </article>
                <article>
                    <span>03</span>
                    <h3>Document Check</h3>
                    <p>Review RoHS, REACH, UL94, TDS, SDS, and ISO information against the selected material and supplier source.</p>
                </article>
            </div>
            <a class="factory-screening-feature" href="<?php echo esc_url(home_url('/factory-screening/')); ?>">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/trust/factory-screening.png'); ?>" alt="Factory screening and sample review illustration">
                <div>
                    <span>Factory Screening</span>
                    <p>Factory matching, sample response, workshop references, and certificate/document review are handled together in one screening workflow.</p>
                    <strong>View Factory Screening</strong>
                </div>
            </a>
        </div>
    </div>
</section>

<section class="section">
    <div class="section-inner">
        <div class="section-header">
            <h2>How We Support Buyers</h2>
            <p>Service advantages are shown through practical follow-up, organized files, and clearer communication instead of factory-style overclaiming.</p>
        </div>
        <div class="grid products">
            <article class="card">
                <div class="card-marker"></div>
                <h3>Requirement Translation</h3>
                <p>We turn drawings, photos, application notes, and certificate needs into supplier-ready quote details.</p>
            </article>
            <article class="card">
                <div class="card-marker"></div>
                <h3>Document Templates</h3>
                <p>When real certificates cannot be displayed publicly, we show the fields and review logic customers should expect.</p>
            </article>
            <article class="card">
                <div class="card-marker"></div>
                <h3>Follow-up Library</h3>
                <p>Samples, quote parameters, supplier notes, material options, and document status can be organized for repeat projects.</p>
            </article>
        </div>
        <div class="section-link-row">
            <a class="button" href="<?php echo esc_url(home_url('/service-follow-up/')); ?>">View Service Follow-up</a>
            <a class="button secondary" href="<?php echo esc_url(home_url('/factory-screening/')); ?>">View Factory Screening</a>
        </div>
    </div>
</section>

<section class="section white">
    <div class="section-inner">
        <div class="section-header">
            <div>
                <span class="eyebrow">Article Library</span>
                <h2>Latest Insights</h2>
            </div>
            <p>Publish practical articles about materials, product selection, compliance, factory screening, and sourcing follow-up.</p>
        </div>
        <div class="grid products">
            <?php
            $latest_posts = new WP_Query([
                'post_type' => 'post',
                'posts_per_page' => 3,
                'post_status' => 'publish',
            ]);

            if ($latest_posts->have_posts()) :
                while ($latest_posts->have_posts()) :
                    $latest_posts->the_post();
                    ?>
                    <article class="article-card">
                        <span class="article-meta"><?php echo esc_html(get_the_date()); ?></span>
                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <p><?php echo esc_html(get_the_excerpt()); ?></p>
                        <a class="text-link" href="<?php the_permalink(); ?>">Read Article</a>
                    </article>
                    <?php
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
        <div class="section-link-row">
            <a class="button secondary" href="<?php echo esc_url(home_url('/insights/')); ?>">View All Articles</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="section-inner">
        <div class="section-header">
            <h2>Project Coordination</h2>
            <p>Built for buyers who need clear material choices, practical sampling, and organized quotation details.</p>
        </div>
        <div class="process">
            <div class="process-step"><strong>01</strong>Review application, drawing, size, quantity, and compliance needs.</div>
            <div class="process-step"><strong>02</strong>Match material and process options such as EPDM foam, silicone, molding, or extrusion.</div>
            <div class="process-step"><strong>03</strong>Coordinate samples, photos, TDS, SDS, RoHS, REACH, and quotation data.</div>
            <div class="process-step"><strong>04</strong>Support production follow-up, packaging details, and shipment coordination.</div>
        </div>
    </div>
</section>

<?php
get_footer();
