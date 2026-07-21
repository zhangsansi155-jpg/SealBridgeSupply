<?php
/**
 * Product archive template.
 *
 * @package SealBridge
 */

get_header();
?>

<section class="section">
    <div class="section-inner">
        <div class="section-header">
            <h1 class="page-title">Custom Gasket Product Categories</h1>
            <p>Browse electrical enclosure gaskets, control cabinet sealing strips, EPDM foam gaskets, silicone gaskets, adhesive-backed die-cut gaskets, IP rated enclosure gasket support, and drawing-based custom rubber gaskets.</p>
        </div>
        <div class="category-intro">
            <div>
                <span class="eyebrow">Product Categories</span>
                <h2>Enclosure and cabinet gasket categories first</h2>
            </div>
            <p>Each category can be customized by drawing, material, thickness, hardness or density, adhesive requirement, quantity, and compliance documents. Use the control cabinet and enclosure pages when the target term is NEMA enclosure gasket or IP rated enclosure gasket.</p>
        </div>
        <div class="product-filter-panel" data-product-filter>
            <button class="is-active" type="button" data-filter="all">All Products</button>
            <button type="button" data-filter="electrical-enclosure-gaskets">Electrical Enclosure Gaskets</button>
            <button type="button" data-filter="control-cabinet-sealing-strips">Control Cabinet Sealing Strips</button>
            <button type="button" data-filter="epdm-foam-gaskets">EPDM Foam Gaskets</button>
            <button type="button" data-filter="silicone-gaskets">Silicone Gaskets</button>
            <button type="button" data-filter="adhesive-backed-die-cut-gaskets">Adhesive Backed Die Cut Gaskets</button>
            <button type="button" data-filter="custom-rubber-gaskets">Custom Rubber Gaskets</button>
        </div>
        <p class="product-filter-status" data-product-status>Loading products...</p>
        <div class="grid product-shop-grid" data-product-grid>
            <?php
            if (have_posts()) :
                while (have_posts()) :
                    the_post();
                    sealbridge_render_product_card();
                endwhile;
            else :
                $starter_products = [
                    ['Electrical Enclosure Gaskets', 'Custom gasket options for doors, covers, panels, and outdoor enclosure assemblies.', 'enclosure-gaskets.png'],
                    ['Control Cabinet Sealing Strips', 'Rubber and foam sealing strips for cabinet doors, edge protection, and dust sealing.', 'sealing-strips.png'],
                    ['EPDM Foam Gaskets', 'Closed-cell EPDM sponge gaskets for weather-resistant enclosure sealing support.', 'epdm-foam-gaskets.png'],
                    ['Silicone Gaskets', 'Flexible silicone gasket options for UV, aging, and temperature-sensitive applications.', 'silicone-gaskets.png'],
                    ['Adhesive Backed Die Cut Gaskets', 'Die cut sheet gaskets with adhesive backing for fast assembly.', 'adhesive-gaskets.png'],
                    ['Custom Rubber Gaskets', 'Drawing-based rubber gasket sourcing for custom project requirements.', 'custom-rubber-gaskets.png'],
                ];

                foreach ($starter_products as $product) :
                    ?>
                    <article class="product-card">
                        <div class="product-card-media">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/products/' . $product[2]); ?>" alt="<?php echo esc_attr($product[0]); ?>">
                        </div>
                        <div class="product-card-body">
                            <p class="product-type">Main product category</p>
                            <h2><?php echo esc_html($product[0]); ?></h2>
                            <p><?php echo esc_html($product[1]); ?></p>
                            <a class="button" href="<?php echo esc_url(home_url('/contact/')); ?>">Request Quote</a>
                        </div>
                    </article>
                    <?php
                endforeach;
            endif;
            ?>
        </div>
        <div class="product-pagination" data-product-pagination></div>
    </div>
</section>

<section class="section white">
    <div class="section-inner">
        <div class="section-header">
            <h2>Application Scenarios</h2>
            <p>Most buyers start from where the gasket will be used. These scenarios help narrow material, process, and quotation details.</p>
        </div>
        <div class="grid scenario-grid">
            <?php foreach (sealbridge_application_scenarios() as $scenario) : ?>
                <article class="scenario-card">
                    <span class="scenario-icon"></span>
                    <h3><a href="<?php echo esc_url(home_url('/applications/' . $scenario[3] . '/')); ?>"><?php echo esc_html($scenario[0]); ?></a></h3>
                    <p><?php echo esc_html($scenario[1]); ?></p>
                    <strong><?php echo esc_html($scenario[2]); ?></strong>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php
get_footer();
