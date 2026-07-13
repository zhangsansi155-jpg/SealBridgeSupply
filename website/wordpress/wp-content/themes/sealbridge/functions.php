<?php
/**
 * SealBridge theme setup.
 *
 * @package SealBridge
 */

if (!defined('ABSPATH')) {
    exit;
}

function sealbridge_setup(): void
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('responsive-embeds');
    add_theme_support('editor-styles');
    add_editor_style('style.css');

    register_nav_menus([
        'primary' => __('Primary Navigation', 'sealbridge'),
        'footer' => __('Footer Navigation', 'sealbridge'),
    ]);
}
add_action('after_setup_theme', 'sealbridge_setup');

function sealbridge_assets(): void
{
    $asset_version = (string) filemtime(get_stylesheet_directory() . '/style.css');
    $gallery_script = get_template_directory() . '/assets/product-gallery.js';

    wp_enqueue_style(
        'sealbridge-style',
        get_stylesheet_uri(),
        [],
        $asset_version
    );

    wp_enqueue_script(
        'sealbridge-product-gallery',
        get_template_directory_uri() . '/assets/product-gallery.js',
        [],
        file_exists($gallery_script) ? (string) filemtime($gallery_script) : $asset_version,
        true
    );

    wp_localize_script('sealbridge-product-gallery', 'sealbridgeProducts', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'perPage' => 10,
    ]);
}
add_action('wp_enqueue_scripts', 'sealbridge_assets');

function sealbridge_logo_url(): string
{
    return get_template_directory_uri() . '/assets/sealbridge-logo.svg';
}

function sealbridge_seo_map(): array
{
    return [
        'home' => [
            'title' => 'Custom Gaskets for Electrical Enclosures & Control Cabinets',
            'description' => 'Custom enclosure gaskets, cabinet door seals, EPDM foam gaskets, silicone gaskets, and adhesive-backed die-cut gaskets for B2B projects.',
        ],
        'products_archive' => [
            'title' => 'Custom Gasket Product Categories for Enclosures and Cabinets',
            'description' => 'Browse electrical enclosure gaskets, control cabinet sealing strips, EPDM foam gaskets, silicone gaskets, adhesive-backed die-cut gaskets, and custom rubber gaskets.',
        ],
        'applications_archive' => [
            'title' => 'Gasket Application Scenarios for Electrical Enclosures',
            'description' => 'Application guides for outdoor electrical enclosure gaskets, control cabinet door seals, junction box gaskets, EV charger cabinets, solar inverters, and LED housings.',
        ],
        'products' => [
            'electrical-enclosure-gaskets' => [
                'title' => 'Electrical Enclosure Gaskets | Custom Enclosure Gasket Supplier',
                'description' => 'Custom electrical enclosure gaskets for junction boxes, outdoor covers, equipment housings, and IP-rated enclosure sealing support.',
            ],
            'control-cabinet-sealing-strips' => [
                'title' => 'Control Cabinet Sealing Strips | Cabinet Door Seals',
                'description' => 'Control cabinet sealing strips and cabinet door seals for distribution cabinets, electrical cabinets, server cabinets, and industrial panels.',
            ],
            'epdm-foam-gaskets' => [
                'title' => 'EPDM Foam Gaskets | Closed Cell Sponge Gasket Supplier',
                'description' => 'Closed-cell EPDM foam gaskets and EPDM sponge gaskets for outdoor waterproof, dustproof, weather-resistant enclosure sealing.',
            ],
            'silicone-gaskets' => [
                'title' => 'Silicone Gaskets | Silicone Rubber Gaskets for Housings',
                'description' => 'Silicone rubber gaskets and silicone foam gaskets for LED lighting housings, outdoor electronics, equipment covers, and temperature-sensitive sealing.',
            ],
            'adhesive-backed-die-cut-gaskets' => [
                'title' => 'Adhesive Backed Die Cut Gaskets | Peel and Stick Gaskets',
                'description' => 'Adhesive backed die cut gaskets, self-adhesive foam gaskets, and peel-and-stick rubber gaskets for fast enclosure and panel assembly.',
            ],
            'custom-rubber-gaskets' => [
                'title' => 'Custom Rubber Gaskets According to Drawing | Rubber Seals',
                'description' => 'Custom rubber gaskets and custom rubber seals according to drawing, sample, non-standard size, material, hardness, and project requirements.',
            ],
        ],
        'pages' => [
            'factory-screening' => [
                'title' => 'Gasket Factory Screening and Certificate Review',
                'description' => 'Factory screening for custom gasket sourcing, supplier matching, workshop references, RoHS, REACH, UL94, TDS, SDS, and document review.',
            ],
            'materials' => [
                'title' => 'Gasket Material Selection | EPDM, Silicone, NBR, CR and Foam',
                'description' => 'Compare EPDM, silicone, NBR, neoprene, EVA, and PU foam for enclosure gaskets, cabinet seals, adhesive-backed parts, and custom rubber gaskets.',
            ],
            'capabilities' => [
                'title' => 'Custom Gasket Manufacturing Processes | Die Cutting, Molding, Extrusion',
                'description' => 'Process routes for custom gaskets: die cutting, compression molding, rubber extrusion, adhesive backing, sample coordination, and project support.',
            ],
            'contact' => [
                'title' => 'Request a Custom Gasket Quote | SealBridge Supply',
                'description' => 'Send drawings, material, size, quantity, application, adhesive, and certificate requirements for a custom gasket quotation.',
            ],
            'about' => [
                'title' => 'About SealBridge Supply | Custom Gasket Sourcing Partner',
                'description' => 'SealBridge Supply coordinates custom enclosure gasket, cabinet seal, EPDM foam, silicone gasket, and adhesive-backed gasket sourcing projects.',
            ],
            'service-follow-up' => [
                'title' => 'Custom Gasket Sourcing Service and Follow-up',
                'description' => 'Requirement intake, material direction, factory matching, quotation coordination, sample follow-up, and repeat project records for gasket buyers.',
            ],
            'sourcing-process' => [
                'title' => 'Custom Gasket Sourcing Process from Drawing to Sample',
                'description' => 'A clear sourcing process for enclosure gaskets: application review, drawing check, material selection, supplier coordination, quotation, and sampling.',
            ],
            'faq' => [
                'title' => 'Custom Enclosure Gasket FAQ | Materials, IP Rating, Samples',
                'description' => 'FAQ for custom enclosure gaskets, cabinet seals, foam gaskets, adhesive-backed die-cut parts, IP-rated sealing support, samples, and documents.',
            ],
            'insights' => [
                'title' => 'Gasket Selection Guides and Sourcing Articles',
                'description' => 'Articles about enclosure gasket material selection, EPDM vs silicone, die-cut gasket quotes, IP-rated sealing support, and document review.',
            ],
        ],
    ];
}

function sealbridge_current_seo(): array
{
    $map = sealbridge_seo_map();
    $fallback = [
        'title' => get_bloginfo('name'),
        'description' => get_bloginfo('description'),
    ];

    if (is_front_page()) {
        return $map['home'];
    }

    if (is_post_type_archive('product')) {
        return $map['products_archive'];
    }

    if (is_post_type_archive('application')) {
        return $map['applications_archive'];
    }

    if (is_singular('product')) {
        $post = get_queried_object();
        return $map['products'][$post->post_name] ?? $fallback;
    }

    if (is_page()) {
        $post = get_queried_object();
        return $map['pages'][$post->post_name] ?? [
            'title' => get_the_title($post),
            'description' => wp_trim_words(wp_strip_all_tags($post->post_content), 24),
        ];
    }

    if (is_singular()) {
        $post = get_queried_object();
        return [
            'title' => get_the_title($post),
            'description' => has_excerpt($post) ? get_the_excerpt($post) : wp_trim_words(wp_strip_all_tags($post->post_content), 24),
        ];
    }

    return $fallback;
}

function sealbridge_document_title(array $parts): array
{
    if (is_admin()) {
        return $parts;
    }

    $seo = sealbridge_current_seo();
    if (!empty($seo['title'])) {
        $parts['title'] = $seo['title'];
    }

    return $parts;
}
add_filter('document_title_parts', 'sealbridge_document_title');

function sealbridge_pre_document_title(?string $title): string
{
    if (is_admin()) {
        return $title ?? '';
    }

    $seo = sealbridge_current_seo();
    $seo_title = trim(wp_strip_all_tags($seo['title'] ?? get_bloginfo('name')));
    $site_name = get_bloginfo('name');

    if ($seo_title === '' || stripos($seo_title, $site_name) !== false) {
        return $seo_title;
    }

    return $seo_title . ' | ' . $site_name;
}
add_filter('pre_get_document_title', 'sealbridge_pre_document_title');

function sealbridge_canonical_url(): string
{
    if (is_front_page()) {
        return home_url('/');
    }

    if (is_post_type_archive('product')) {
        return get_post_type_archive_link('product') ?: home_url('/products/');
    }

    if (is_post_type_archive('application')) {
        return get_post_type_archive_link('application') ?: home_url('/applications/');
    }

    if (is_singular() || is_page()) {
        return get_permalink();
    }

    return home_url(add_query_arg([], $GLOBALS['wp']->request ?? ''));
}

function sealbridge_seo_head(): void
{
    if (is_admin()) {
        return;
    }

    $seo = sealbridge_current_seo();
    $description = trim(wp_strip_all_tags($seo['description'] ?? ''));
    $title = trim(wp_strip_all_tags($seo['title'] ?? wp_get_document_title()));
    $canonical = sealbridge_canonical_url();
    $image = sealbridge_logo_url();

    if (is_singular('product')) {
        $image = sealbridge_product_image_url(get_queried_object());
    }

    if ($description !== '') {
        echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
    }
    echo '<link rel="canonical" href="' . esc_url($canonical) . '">' . "\n";
    echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_url($canonical) . '">' . "\n";
    echo '<meta property="og:type" content="' . (is_singular() ? 'article' : 'website') . '">' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
    echo '<meta property="og:image" content="' . esc_url($image) . '">' . "\n";

    if (is_front_page()) {
        $home_url = home_url('/');
        $schema = [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'Organization',
                    '@id' => $home_url . '#organization',
                    'name' => get_bloginfo('name'),
                    'url' => $home_url,
                    'logo' => $image,
                    'description' => $description,
                ],
                [
                    '@type' => 'WebSite',
                    '@id' => $home_url . '#website',
                    'name' => get_bloginfo('name'),
                    'alternateName' => 'SealBridge',
                    'url' => $home_url,
                    'publisher' => ['@id' => $home_url . '#organization'],
                    'inLanguage' => 'en-US',
                ],
            ],
        ];
    } else {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => is_singular('product') ? 'Service' : 'Organization',
            'name' => is_singular('product') ? get_the_title() : get_bloginfo('name'),
            'description' => $description,
            'url' => $canonical,
        ];
    }

    if (is_singular('product')) {
        $schema['image'] = $image;
        $schema['serviceType'] = 'Custom gasket sourcing and project coordination';
        $schema['provider'] = [
            '@type' => 'Organization',
            '@id' => home_url('/') . '#organization',
            'name' => get_bloginfo('name'),
            'url' => home_url('/'),
        ];
        $schema['areaServed'] = 'Worldwide';
    } elseif (!is_front_page()) {
        $schema['logo'] = $image;
    }

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
add_action('wp_head', 'sealbridge_seo_head', 2);
remove_action('wp_head', 'rel_canonical');

/**
 * The public website is English even when the WordPress dashboard locale is
 * Chinese. Keep the admin locale untouched and declare the storefront
 * language accurately for browsers and search engines.
 */
function sealbridge_public_language_attributes(string $output): string
{
    if (is_admin()) {
        return $output;
    }

    return 'lang="en-US" dir="ltr"';
}
add_filter('language_attributes', 'sealbridge_public_language_attributes');

/**
 * Provide a complete theme-owned brand icon set when no WordPress Site Icon is
 * configured. SVG remains the sharpest option, while the raster fallbacks are
 * used by search engines, link previews, older browsers, and Apple devices.
 * Once an icon is selected in Settings > General, WordPress becomes the source
 * of truth and this fallback is not printed.
 */
function sealbridge_brand_favicon(): void
{
    if (has_site_icon()) {
        return;
    }

    $assets_url = get_template_directory_uri() . '/assets/';
    echo '<link rel="icon" href="' . esc_url($assets_url . 'sealbridge-favicon.svg') . '" type="image/svg+xml">' . "\n";
    echo '<link rel="icon" href="' . esc_url($assets_url . 'sealbridge-favicon-48.png') . '" type="image/png" sizes="48x48">' . "\n";
    echo '<link rel="icon" href="' . esc_url($assets_url . 'sealbridge-favicon-96.png') . '" type="image/png" sizes="96x96">' . "\n";
    echo '<link rel="icon" href="' . esc_url($assets_url . 'sealbridge-favicon-192.png') . '" type="image/png" sizes="192x192">' . "\n";
    echo '<link rel="icon" href="' . esc_url($assets_url . 'sealbridge-favicon-512.png') . '" type="image/png" sizes="512x512">' . "\n";
    echo '<link rel="shortcut icon" href="' . esc_url($assets_url . 'favicon.ico') . '" type="image/x-icon">' . "\n";
    echo '<link rel="apple-touch-icon" href="' . esc_url($assets_url . 'apple-touch-icon.png') . '" sizes="180x180">' . "\n";
    echo '<meta name="application-name" content="SealBridge Supply">' . "\n";
}
add_action('wp_head', 'sealbridge_brand_favicon', 3);

function sealbridge_disable_author_sitemap($provider, string $name)
{
    if ($name === 'users') {
        return false;
    }

    return $provider;
}
add_filter('wp_sitemaps_add_provider', 'sealbridge_disable_author_sitemap', 10, 2);

function sealbridge_default_menu(): void
{
    ?>
    <ul>
        <li><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
        <li><a href="<?php echo esc_url(home_url('/products/')); ?>">Products</a></li>
        <li><a href="<?php echo esc_url(home_url('/applications/')); ?>">Applications</a></li>
        <li><a href="<?php echo esc_url(home_url('/insights/')); ?>">Articles</a></li>
        <li><a href="<?php echo esc_url(home_url('/materials/')); ?>">Materials</a></li>
        <li><a href="<?php echo esc_url(home_url('/capabilities/')); ?>">Capabilities</a></li>
        <li><a href="<?php echo esc_url(home_url('/factory-screening/')); ?>">Factory Screening</a></li>
        <li><a href="<?php echo esc_url(home_url('/about/')); ?>">About</a></li>
        <li><a href="<?php echo esc_url(home_url('/contact/')); ?>">Contact</a></li>
    </ul>
    <?php
}

function sealbridge_register_content_types(): void
{
    register_post_type('product', [
        'labels' => [
            'name' => __('Products', 'sealbridge'),
            'singular_name' => __('Product', 'sealbridge'),
            'add_new_item' => __('Add New Product', 'sealbridge'),
            'edit_item' => __('Edit Product', 'sealbridge'),
        ],
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-products',
        'rewrite' => ['slug' => 'products'],
        'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'custom-fields'],
        'show_in_rest' => true,
    ]);

    register_post_type('application', [
        'labels' => [
            'name' => __('Applications', 'sealbridge'),
            'singular_name' => __('Application', 'sealbridge'),
            'add_new_item' => __('Add New Application', 'sealbridge'),
            'edit_item' => __('Edit Application', 'sealbridge'),
        ],
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-admin-site-alt3',
        'rewrite' => ['slug' => 'applications'],
        'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'custom-fields'],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'sealbridge_register_content_types');

function sealbridge_product_archive_query(WP_Query $query): void
{
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('product')) {
        $query->set('posts_per_page', 10);
        $query->set('orderby', 'date');
        $query->set('order', 'DESC');
    }
}
add_action('pre_get_posts', 'sealbridge_product_archive_query');

function sealbridge_product_filter_map(): array
{
    return [
        'electrical-enclosure-gaskets' => 'electrical-enclosure-gaskets',
        'control-cabinet-sealing-strips' => 'control-cabinet-sealing-strips',
        'epdm-foam-gaskets' => 'epdm-foam-gaskets',
        'silicone-gaskets' => 'silicone-gaskets',
        'adhesive-backed-die-cut-gaskets' => 'adhesive-backed-die-cut-gaskets',
        'custom-rubber-gaskets' => 'custom-rubber-gaskets',
    ];
}

function sealbridge_product_slugs_for_filter(string $filter): array
{
    if ($filter === 'all') {
        return [];
    }

    $slugs = [];
    foreach (sealbridge_product_filter_map() as $slug => $categories) {
        if (in_array($filter, explode(' ', $categories), true)) {
            $slugs[] = $slug;
        }
    }

    $dynamic_products = get_posts([
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'meta_key' => '_sealbridge_product_filters',
    ]);

    foreach ($dynamic_products as $product_id) {
        $categories = (string) get_post_meta($product_id, '_sealbridge_product_filters', true);
        if (in_array($filter, explode(' ', $categories), true)) {
            $slugs[] = get_post_field('post_name', $product_id);
        }
    }

    return array_values(array_unique($slugs));
}

function sealbridge_product_filters(?WP_Post $post = null): string
{
    $post = $post ?: get_post();
    $dynamic_filters = (string) get_post_meta($post->ID, '_sealbridge_product_filters', true);

    if ($dynamic_filters !== '') {
        return $dynamic_filters;
    }

    return sealbridge_product_filter_map()[$post->post_name] ?? 'custom-rubber-gaskets';
}

function sealbridge_render_product_card(?WP_Post $post = null): void
{
    $post = $post ?: get_post();
    $specs = sealbridge_product_specs($post);
    $applications = sealbridge_product_applications($post);
    $filters = sealbridge_product_filters($post);
    $video_url = (string) get_post_meta($post->ID, '_sealbridge_product_video', true);
    $price = (string) get_post_meta($post->ID, '_sealbridge_product_price', true);
    $moq = (string) get_post_meta($post->ID, '_sealbridge_product_moq', true);
    ?>
    <article class="product-card" data-product-card data-category="<?php echo esc_attr($filters); ?>">
        <a class="product-card-media" href="<?php echo esc_url(get_permalink($post)); ?>">
            <img src="<?php echo esc_url(sealbridge_product_image_url($post)); ?>" alt="<?php echo esc_attr(get_the_title($post)); ?>">
        </a>
        <div class="product-card-body">
            <p class="product-type">Main product category</p>
            <h2><a href="<?php echo esc_url(get_permalink($post)); ?>"><?php echo esc_html(get_the_title($post)); ?></a></h2>
            <p><?php echo esc_html(get_the_excerpt($post)); ?></p>
            <dl class="product-mini-specs">
                <div><dt>Material</dt><dd><?php echo esc_html($specs[0]); ?></dd></div>
                <div><dt>Process</dt><dd><?php echo esc_html($specs[1]); ?></dd></div>
            </dl>
            <?php if ($price !== '' || $moq !== '') : ?>
                <dl class="product-commercials">
                    <?php if ($price !== '') : ?>
                        <div><dt>FOB</dt><dd><?php echo esc_html($price); ?></dd></div>
                    <?php endif; ?>
                    <?php if ($moq !== '') : ?>
                        <div><dt>MOQ</dt><dd><?php echo esc_html($moq); ?></dd></div>
                    <?php endif; ?>
                </dl>
            <?php endif; ?>
            <div class="application-chip-list" aria-label="<?php esc_attr_e('Application scenarios', 'sealbridge'); ?>">
                <?php foreach ($applications as $application) : ?>
                    <span><?php echo esc_html($application); ?></span>
                <?php endforeach; ?>
            </div>
            <div class="product-card-actions">
                <a class="button" href="<?php echo esc_url(get_permalink($post)); ?>">View Details</a>
                <a class="button secondary" href="<?php echo esc_url(home_url('/contact/')); ?>">Quote</a>
            </div>
            <?php if ($video_url !== '') : ?>
                <span class="product-video-badge">Video available</span>
            <?php endif; ?>
        </div>
    </article>
    <?php
}

function sealbridge_load_products(): void
{
    $filter = isset($_POST['filter']) ? sanitize_key((string) wp_unslash($_POST['filter'])) : 'all';
    $paged = isset($_POST['page']) ? max(1, (int) $_POST['page']) : 1;
    $allowed_filters = ['all', 'electrical-enclosure-gaskets', 'control-cabinet-sealing-strips', 'epdm-foam-gaskets', 'silicone-gaskets', 'adhesive-backed-die-cut-gaskets', 'custom-rubber-gaskets'];

    if (!in_array($filter, $allowed_filters, true)) {
        $filter = 'all';
    }

    $args = [
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => 10,
        'paged' => $paged,
        'orderby' => 'date',
        'order' => 'DESC',
    ];

    $slugs = sealbridge_product_slugs_for_filter($filter);
    if ($filter !== 'all') {
        $args['post_name__in'] = $slugs ?: ['__none__'];
    }

    $products = new WP_Query($args);

    ob_start();
    if ($products->have_posts()) {
        while ($products->have_posts()) {
            $products->the_post();
            sealbridge_render_product_card();
        }
    } else {
        echo '<article class="card"><h2>No products found</h2><p>Try another product category or send a custom inquiry.</p></article>';
    }
    wp_reset_postdata();

    wp_send_json_success([
        'html' => ob_get_clean(),
        'total' => (int) $products->found_posts,
        'page' => $paged,
        'maxPages' => (int) $products->max_num_pages,
        'perPage' => 10,
    ]);
}
add_action('wp_ajax_sealbridge_load_products', 'sealbridge_load_products');
add_action('wp_ajax_nopriv_sealbridge_load_products', 'sealbridge_load_products');

function sealbridge_excerpt_length(): int
{
    return 24;
}
add_filter('excerpt_length', 'sealbridge_excerpt_length');

function sealbridge_product_image_map(): array
{
    return [
        'electrical-enclosure-gaskets' => 'enclosure-gaskets.png',
        'control-cabinet-sealing-strips' => 'sealing-strips.png',
        'epdm-foam-gaskets' => 'epdm-foam-gaskets.png',
        'silicone-gaskets' => 'silicone-gaskets.png',
        'adhesive-backed-die-cut-gaskets' => 'adhesive-gaskets.png',
        'custom-rubber-gaskets' => 'custom-rubber-gaskets.png',
    ];
}

function sealbridge_product_image_url(?WP_Post $post = null): string
{
    $post = $post ?: get_post();
    $external_image = (string) get_post_meta($post->ID, '_sealbridge_external_image', true);

    if ($external_image !== '') {
        return $external_image;
    }

    $map = sealbridge_product_image_map();
    $file = $map[$post->post_name] ?? 'enclosure-gaskets.png';
    $gallery_file = str_replace('.png', '-main.png', $file);

    return get_template_directory_uri() . '/assets/products/gallery/' . $gallery_file;
}

function sealbridge_product_gallery(?WP_Post $post = null): array
{
    $post = $post ?: get_post();
    $external_image = (string) get_post_meta($post->ID, '_sealbridge_external_image', true);

    if ($external_image !== '') {
        return [
            ['Main View', $external_image],
        ];
    }

    $map = sealbridge_product_image_map();
    $file = $map[$post->post_name] ?? 'enclosure-gaskets.png';
    $base = str_replace('.png', '', $file);
    $base_uri = get_template_directory_uri() . '/assets/products/gallery/';

    return [
        ['Main View', $base_uri . $base . '-main.png'],
        ['Angle View', $base_uri . $base . '-angle.png'],
        ['Edge Detail', $base_uri . $base . '-detail.png'],
    ];
}

function sealbridge_product_specs(?WP_Post $post = null): array
{
    $post = $post ?: get_post();
    $dynamic_specs = get_post_meta($post->ID, '_sealbridge_product_specs', true);

    if (is_array($dynamic_specs) && count($dynamic_specs) >= 4) {
        return array_values($dynamic_specs);
    }

    $specs = [
        'electrical-enclosure-gaskets' => ['EPDM foam / Silicone / CR', 'Die cutting / molding', 'Electrical enclosures, junction boxes, outdoor covers', 'Drawing, thickness, compression target'],
        'control-cabinet-sealing-strips' => ['EPDM / Silicone / PVC / Rubber', 'Rubber extrusion', 'Control cabinets, distribution cabinets, cabinet doors', 'Profile drawing, hardness, length'],
        'epdm-foam-gaskets' => ['Closed-cell EPDM sponge', 'Die cutting / strip cutting', 'Outdoor waterproof and dustproof sealing', 'Density, thickness, adhesive'],
        'silicone-gaskets' => ['Solid silicone / silicone foam', 'Die cutting / compression molding', 'High/low temperature, LED, outdoor electronics', 'Hardness, temperature range, color'],
        'adhesive-backed-die-cut-gaskets' => ['EPDM foam / EVA / Rubber sheet', 'Die cutting + adhesive lamination', 'Fast assembly on boxes, panels, and covers', 'Adhesive brand, liner, tolerance'],
        'custom-rubber-gaskets' => ['EPDM / NBR / Silicone / CR', 'Molding / cutting / extrusion', 'Drawing, sample, and non-standard gasket projects', 'Material, tooling, tolerance, quantity'],
    ];

    return $specs[$post->post_name] ?? ['Custom material', 'Custom process', 'Industrial sealing', 'Drawing and quantity'];
}

function sealbridge_product_applications(?WP_Post $post = null): array
{
    $post = $post ?: get_post();
    $dynamic_applications = get_post_meta($post->ID, '_sealbridge_product_applications', true);

    if (is_array($dynamic_applications) && $dynamic_applications) {
        return array_values($dynamic_applications);
    }

    $applications = [
        'electrical-enclosure-gaskets' => ['Electrical Enclosures', 'Junction Boxes', 'Outdoor Covers', 'Equipment Housings'],
        'control-cabinet-sealing-strips' => ['Control Cabinets', 'Distribution Cabinets', 'Cabinet Doors', 'Industrial Panels'],
        'epdm-foam-gaskets' => ['Outdoor Enclosure Doors', 'Waterproof Panels', 'Dustproof Covers', 'Weather-resistant Sealing'],
        'silicone-gaskets' => ['LED Lighting Housings', 'Outdoor Electronics', 'Temperature-sensitive Covers', 'Equipment Housings'],
        'adhesive-backed-die-cut-gaskets' => ['Panel Assembly', 'Access Covers', 'Junction Boxes', 'Electronic Housings'],
        'custom-rubber-gaskets' => ['Drawing-based Projects', 'Sample-based Projects', 'Non-standard Sizes', 'Custom Enclosures'],
    ];

    return $applications[$post->post_name] ?? ['Industrial Sealing', 'Custom Enclosures', 'Equipment Housings'];
}

function sealbridge_product_parameters(?WP_Post $post = null): array
{
    $post = $post ?: get_post();

    $parameters = [
        'electrical-enclosure-gaskets' => [
            'Typical Materials' => ['EPDM foam', 'Silicone foam', 'Neoprene / CR', 'NBR sheet', 'Custom rubber sheet'],
            'Common Structures' => ['Flat frame gasket', 'Cover gasket', 'Panel gasket', 'Molded corner gasket', 'Die-cut sheet gasket'],
            'Key Parameters' => ['Thickness: 1-10 mm reference', 'Hardness / density by drawing', 'Compression gap', 'Water and dust sealing target', 'Adhesive backing optional'],
            'Quote Checklist' => ['Enclosure drawing or cover size', 'Gasket path / CAD file', 'Material target', 'Thickness and compression target', 'Quantity and document needs'],
        ],
        'control-cabinet-sealing-strips' => [
            'Typical Materials' => ['EPDM dense rubber', 'EPDM sponge rubber', 'Silicone rubber', 'PVC edge trim', 'Co-extruded rubber profile'],
            'Common Structures' => ['Bulb seal', 'D-shape strip', 'U-channel edge seal', 'Self-grip profile', 'Custom extrusion profile'],
            'Key Parameters' => ['Profile width and height', 'Door gap', 'Hardness / sponge density', 'Roll length or cut length', 'Corner joining requirement'],
            'Quote Checklist' => ['Profile drawing or sample photo', 'Cabinet door gap', 'Material and color', 'Length per set', 'Annual quantity and packing method'],
        ],
        'epdm-foam-gaskets' => [
            'Typical Materials' => ['Closed-cell EPDM sponge', 'EPDM foam sheet', 'EPDM foam strip', 'EPDM foam with adhesive', 'EPDM blend foam'],
            'Common Structures' => ['Die-cut frame', 'Foam strip', 'Adhesive-backed pad', 'Kiss-cut sheet', 'Roll material'],
            'Key Parameters' => ['Thickness: 1-20 mm reference', 'Density / compression force', 'Closed-cell requirement', 'Adhesive type', 'Outdoor aging requirement'],
            'Quote Checklist' => ['Drawing or dimensions', 'Thickness and density target', 'Adhesive requirement', 'Compression gap', 'Working environment and quantity'],
        ],
        'silicone-gaskets' => [
            'Typical Materials' => ['Solid silicone', 'Silicone foam', 'High-temperature silicone', 'Food-grade silicone option', 'Flame-retardant silicone option'],
            'Common Structures' => ['Flat gasket', 'Molded silicone gasket', 'Silicone foam frame', 'Extruded silicone strip', 'Custom shape gasket'],
            'Key Parameters' => ['Hardness: Shore A reference', 'Temperature range', 'Color', 'Surface finish', 'Adhesive or primer requirement'],
            'Quote Checklist' => ['2D/3D drawing or sample', 'Solid or foam silicone', 'Hardness and color', 'Temperature exposure', 'Compliance document needs'],
        ],
        'adhesive-backed-die-cut-gaskets' => [
            'Typical Materials' => ['EPDM foam', 'EVA foam', 'PE foam', 'Rubber sheet', 'Silicone foam', '3M or general adhesive backing'],
            'Common Structures' => ['Die-cut gasket', 'Kiss-cut liner sheet', 'Peel-and-stick pad', 'Frame gasket', 'Multi-cavity sheet'],
            'Key Parameters' => ['Material thickness', 'Adhesive brand / grade', 'Release liner type', 'Tolerance', 'Packing sheet or roll format'],
            'Quote Checklist' => ['DXF/PDF drawing', 'Material and thickness', 'Adhesive surface', 'Tolerance requirement', 'Assembly surface and quantity'],
        ],
        'custom-rubber-gaskets' => [
            'Typical Materials' => ['EPDM', 'NBR', 'Silicone', 'Neoprene / CR', 'FKM option', 'Custom compound'],
            'Common Structures' => ['Molded gasket', 'Cut gasket', 'Rubber washer', 'Extruded profile', 'Bonded or assembled gasket'],
            'Key Parameters' => ['Material and hardness', 'Tolerance', 'Tooling requirement', 'Surface finish', 'Sample and mass production quantity'],
            'Quote Checklist' => ['Drawing or physical sample', 'Material / hardness target', 'Application environment', 'Tolerance and inspection criteria', 'Sample schedule and bulk quantity'],
        ],
    ];

    return $parameters[$post->post_name] ?? [];
}

function sealbridge_application_scenarios(): array
{
    return [
        ['Outdoor Electrical Enclosures', 'EPDM foam, silicone, and custom frame gaskets for outdoor box sealing.', 'EPDM Foam Gaskets / Enclosure Gaskets'],
        ['Control Cabinets', 'Door sealing strips, edge trims, and compression gaskets for repeated cabinet access.', 'Control Cabinet Sealing Strips'],
        ['Junction Boxes', 'Compact die-cut gaskets and adhesive-backed pads for cover and panel sealing.', 'Adhesive Backed Die Cut Gaskets'],
        ['EV Charger Cabinets', 'Outdoor gasket options for access doors, panels, and weather-exposed assemblies.', 'EPDM Foam / Silicone Gaskets'],
        ['Solar Inverter Enclosures', 'UV, aging, and compression-focused sealing for outdoor energy equipment.', 'EPDM / Silicone Gaskets'],
        ['LED Lighting Housings', 'Soft silicone and foam gaskets for covers, lenses, and outdoor fixtures.', 'Silicone Gaskets / Foam Gaskets'],
    ];
}

function sealbridge_application_article(?WP_Post $post = null): array
{
    $post = $post ?: get_post();

    $articles = [
        'outdoor-electrical-enclosures' => [
            'summary' => 'Outdoor electrical enclosures need gasket materials that can handle rain, dust, UV exposure, temperature changes, and long-term compression on doors or covers.',
            'pain_points' => ['Water and dust ingress around doors and covers', 'Compression loss after repeated opening', 'UV and ozone aging outdoors', 'Unclear RoHS, REACH, TDS, or SDS document support'],
            'materials' => ['EPDM foam for weather resistance and compression recovery', 'Silicone foam or silicone rubber for broader temperature exposure', 'Neoprene / CR when balanced weather and industrial performance is needed'],
            'products' => ['electrical-enclosure-gaskets', 'epdm-foam-gaskets', 'silicone-gaskets'],
            'quote' => ['Enclosure drawing or gasket path', 'Door gap and compression target', 'Material and thickness', 'Adhesive or mechanical installation', 'Quantity and compliance documents'],
        ],
        'control-cabinets' => [
            'summary' => 'Control cabinet sealing usually focuses on door sealing strips, edge protection, dust sealing, and stable compression after repeated cabinet access.',
            'pain_points' => ['Door gaps vary between cabinet designs', 'Long strips need consistent profile and hardness', 'Corners or joints may leak if not specified', 'Suppliers may quote without confirming profile drawings'],
            'materials' => ['EPDM rubber for general cabinet door sealing', 'Silicone for softer or wider temperature sealing', 'PVC or rubber edge trim for U-channel protection'],
            'products' => ['control-cabinet-sealing-strips', 'epdm-foam-gaskets', 'custom-rubber-gaskets'],
            'quote' => ['Profile drawing or sample photo', 'Material and hardness', 'Strip length and annual quantity', 'Corner joining or cutting requirement', 'Packaging and label needs'],
        ],
        'junction-boxes' => [
            'summary' => 'Junction boxes often need compact die-cut gaskets, adhesive-backed pads, or formed enclosure gaskets that support cover sealing and quick assembly.',
            'pain_points' => ['Small gasket dimensions make tolerance important', 'Adhesive backing must match assembly surface', 'Cover screw compression may be uneven', 'Outdoor boxes need better aging resistance than ordinary EVA foam'],
            'materials' => ['EPDM foam for outdoor box sealing', 'Adhesive-backed foam for fast assembly', 'Silicone gasket when softness or temperature range matters'],
            'products' => ['adhesive-backed-die-cut-gaskets', 'electrical-enclosure-gaskets', 'epdm-foam-gaskets'],
            'quote' => ['2D drawing or cover inner size', 'Material and adhesive requirement', 'Thickness and tolerance', 'Release liner preference', 'Assembly environment'],
        ],
        'ev-charger-cabinets' => [
            'summary' => 'EV charger cabinets are outdoor equipment, so gasket selection should consider rain, dust, heat, UV exposure, cabinet access panels, and long service life.',
            'pain_points' => ['Outdoor aging and water exposure', 'Large cabinet doors need stable compression', 'Different panels may require different gasket forms', 'Compliance documents may be requested by overseas customers'],
            'materials' => ['EPDM foam for outdoor weather sealing', 'Silicone gasket for temperature and UV-sensitive positions', 'Custom rubber gaskets for shaped cabinet parts'],
            'products' => ['epdm-foam-gaskets', 'silicone-gaskets', 'electrical-enclosure-gaskets'],
            'quote' => ['Cabinet panel drawing', 'Working environment and IP target', 'Material preference', 'Thickness or profile size', 'RoHS / REACH / UL94 information needs'],
        ],
        'solar-inverter-enclosures' => [
            'summary' => 'Solar inverter enclosures need gasket options that can stay reliable under UV exposure, temperature changes, outdoor humidity, and continuous compression.',
            'pain_points' => ['UV and ozone aging', 'Thermal cycling and compression set', 'Outdoor water and dust sealing', 'Need to avoid overclaiming IP rating on the gasket alone'],
            'materials' => ['EPDM foam for outdoor enclosure sealing', 'Silicone foam for temperature-sensitive sealing', 'Closed-cell sponge materials when low water absorption is required'],
            'products' => ['epdm-foam-gaskets', 'silicone-gaskets', 'electrical-enclosure-gaskets'],
            'quote' => ['Drawing or gasket path', 'Temperature range', 'Thickness and compression target', 'Outdoor exposure details', 'Document requirements'],
        ],
        'led-lighting-housings' => [
            'summary' => 'LED lighting housings often need soft gasket materials for lens covers, fixture bodies, outdoor housings, and assemblies exposed to heat and UV.',
            'pain_points' => ['Heat near the light source', 'UV and outdoor aging', 'Soft sealing around lens or cover areas', 'Need clean appearance and stable assembly'],
            'materials' => ['Silicone gasket for heat and UV exposure', 'Silicone foam for soft compression', 'EPDM foam for general outdoor housing sealing'],
            'products' => ['silicone-gaskets', 'epdm-foam-gaskets', 'adhesive-backed-die-cut-gaskets'],
            'quote' => ['Housing drawing', 'Lens or cover sealing path', 'Temperature and UV exposure', 'Material color', 'Quantity and packing requirements'],
        ],
    ];

    return $articles[$post->post_name] ?? $articles['outdoor-electrical-enclosures'];
}
