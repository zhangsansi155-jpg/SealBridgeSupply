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
                <?php if (is_page('capabilities')) : ?>
                    <section class="capability-evidence" aria-labelledby="manufacturing-quality-title">
                        <span class="eyebrow">Manufacturing &amp; Quality Review</span>
                        <h2 id="manufacturing-quality-title">Production Capability We Review Before Quotation</h2>
                        <p>For drawing-based gasket projects, SealBridge checks the practical production route with the selected manufacturing partner before a quotation is issued. This helps avoid quoting a part as a simple die-cut gasket when it actually needs molding, extrusion, a secondary cure, or a different material route.</p>
                        <div class="capability-evidence-grid">
                            <article>
                                <h3>Process Coverage</h3>
                                <ul>
                                    <li>Compression and transfer molding for shaped rubber and silicone parts</li>
                                    <li>Rubber extrusion for cabinet door profiles and long sealing strips</li>
                                    <li>Die cutting, sheet conversion, and adhesive-backed gasket preparation</li>
                                    <li>Secondary cure, trimming, and project-specific finishing where required</li>
                                </ul>
                            </article>
                            <article>
                                <h3>Quality Checkpoints</h3>
                                <ul>
                                    <li>Incoming material inspection (IQC)</li>
                                    <li>In-process checks during forming and trimming (IPQC)</li>
                                    <li>Final inspection and outgoing quality checks (FQC / OQC)</li>
                                    <li>Packaging and label review for repeatable delivery</li>
                                </ul>
                            </article>
                            <article>
                                <h3>Available Project Evidence</h3>
                                <ul>
                                    <li>Material hardness, density, thickness, and dimensional review</li>
                                    <li>Tensile, aging, low-temperature, or compression-related checks when applicable</li>
                                    <li>Sample photos, process photos, and inspection context on request</li>
                                    <li>TDS, SDS, RoHS, REACH, UL94, or current quality-system evidence when available</li>
                                </ul>
                            </article>
                        </div>
                        <p class="capability-evidence-note">Capabilities and documents are confirmed for the selected material, process, and production partner. They are not blanket claims for every gasket or every project.</p>
                    </section>
                <?php endif; ?>
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
