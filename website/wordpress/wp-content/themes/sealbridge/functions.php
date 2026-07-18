<?php
/**
 * SealBridge theme setup.
 *
 * @package SealBridge
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Restore the original HTTPS scheme when WordPress runs behind Cloudflare or
 * another trusted reverse proxy. Without this, WordPress can generate http://
 * schema URLs and redirect HTTPS sitemap requests back to the same URL.
 */
function sealbridge_normalize_proxy_https(): void
{
    $forwarded_proto = strtolower(trim((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')));
    $cf_visitor = strtolower((string) ($_SERVER['HTTP_CF_VISITOR'] ?? ''));

    if ($forwarded_proto === 'https' || str_contains($cf_visitor, '"scheme":"https"')) {
        $_SERVER['HTTPS'] = 'on';
        $_SERVER['SERVER_PORT'] = '443';
    }
}
sealbridge_normalize_proxy_https();

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

function sealbridge_contact_email(): string
{
    return 'support@sealbridgesupply.com';
}

/** Official public social profiles used in navigation and structured data. */
function sealbridge_social_links(): array
{
    return [
        'Facebook' => 'https://www.facebook.com/people/SealBridge-Supply/61591637324325/',
        'LinkedIn' => 'https://www.linkedin.com/in/%E6%99%BA%E7%8E%89-%E5%BC%A0-0888a3422/',
    ];
}

/** Return the official brand glyph for a configured social platform. */
function sealbridge_social_icon(string $platform): string
{
    if ('Facebook' === $platform) {
        return '<svg class="social-brand-icon" viewBox="0 0 24 24" role="img" aria-label="Facebook"><path fill="currentColor" d="M24 12.073C24 5.405 18.627 0 12 0S0 5.405 0 12.073C0 18.1 4.388 23.094 10.125 24v-8.437H7.078v-3.49h3.047V9.413c0-3.025 1.792-4.697 4.533-4.697 1.313 0 2.686.236 2.686.236v2.971h-1.513c-1.49 0-1.956.931-1.956 1.887v2.263h3.328l-.532 3.49h-2.796V24C19.612 23.094 24 18.1 24 12.073Z"/></svg>';
    }

    if ('LinkedIn' === $platform) {
        return '<svg class="social-brand-icon" viewBox="0 0 24 24" role="img" aria-label="LinkedIn"><path fill="currentColor" d="M20.452 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.356V8.999h3.414v1.561h.048c.475-.9 1.636-1.85 3.367-1.85 3.601 0 4.267 2.37 4.267 5.455v6.287ZM5.337 7.433a2.063 2.063 0 1 1 0-4.126 2.063 2.063 0 0 1 0 4.126Zm1.782 13.019H3.555V8.999h3.564v11.453ZM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003Z"/></svg>';
    }

    return '';
}

/** Redirect back to the quote form with a non-sensitive result code. */
function sealbridge_quote_redirect(string $status): void
{
    $url = add_query_arg('quote_status', sanitize_key($status), home_url('/contact/'));
    wp_safe_redirect($url . '#quote-form', 303, 'SealBridge Supply');
    exit;
}

/** Process public and signed-in custom gasket quotation requests. */
function sealbridge_handle_quote_request(): void
{
    $nonce = sanitize_text_field(wp_unslash($_POST['sealbridge_quote_nonce'] ?? ''));
    if (!wp_verify_nonce($nonce, 'sealbridge_quote_request')) {
        sealbridge_quote_redirect('invalid');
    }

    // A hidden honeypot and minimum completion time stop simple form bots.
    $website = trim((string) wp_unslash($_POST['website'] ?? ''));
    $started_at = absint($_POST['form_started_at'] ?? 0);
    if ($website !== '' || $started_at === 0 || (time() - $started_at) < 2) {
        sealbridge_quote_redirect('invalid');
    }

    $name = sanitize_text_field(wp_unslash($_POST['name'] ?? ''));
    $company = sanitize_text_field(wp_unslash($_POST['company'] ?? ''));
    $email = sanitize_email(wp_unslash($_POST['email'] ?? ''));
    $country = sanitize_text_field(wp_unslash($_POST['country'] ?? ''));
    $whatsapp = sanitize_text_field(wp_unslash($_POST['whatsapp'] ?? ''));
    $product_type = sanitize_text_field(wp_unslash($_POST['product_type'] ?? ''));
    $quantity = sanitize_text_field(wp_unslash($_POST['quantity'] ?? ''));
    $message = sanitize_textarea_field(wp_unslash($_POST['message'] ?? ''));
    $privacy_consent = !empty($_POST['privacy_consent']);

    if ($name === '' || !is_email($email) || $message === '' || !$privacy_consent) {
        sealbridge_quote_redirect('invalid');
    }

    $remote_ip = sanitize_text_field($_SERVER['REMOTE_ADDR'] ?? 'unknown');
    $rate_key = 'sealbridge_quote_' . md5($remote_ip . '|' . strtolower($email));
    if (get_transient($rate_key)) {
        sealbridge_quote_redirect('rate_limited');
    }
    set_transient($rate_key, 1, MINUTE_IN_SECONDS);

    $attachments = [];
    $temporary_attachment = '';
    $upload = $_FILES['project_file'] ?? null;

    if (is_array($upload) && ($upload['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
        $allowed_extensions = ['pdf', 'dxf', 'dwg', 'step', 'stp', 'igs', 'iges', 'zip', 'jpg', 'jpeg', 'png', 'webp'];
        $original_name = sanitize_file_name((string) ($upload['name'] ?? ''));
        $extension = strtolower((string) pathinfo($original_name, PATHINFO_EXTENSION));
        $file_size = absint($upload['size'] ?? 0);
        $temporary_name = (string) ($upload['tmp_name'] ?? '');

        if (
            ($upload['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK
            || $file_size === 0
            || $file_size > 10 * MB_IN_BYTES
            || !in_array($extension, $allowed_extensions, true)
            || !is_uploaded_file($temporary_name)
        ) {
            sealbridge_quote_redirect('file_error');
        }

        $temporary_attachment = trailingslashit(get_temp_dir()) . wp_unique_filename(get_temp_dir(), $original_name);
        if (!move_uploaded_file($temporary_name, $temporary_attachment)) {
            sealbridge_quote_redirect('file_error');
        }
        $attachments[] = $temporary_attachment;
    }

    $subject_context = $company !== '' ? $company : $name;
    $subject = sprintf('[Website RFQ] %s — %s', $product_type ?: 'Custom gasket project', $subject_context);
    $body = implode("\n", [
        'New quotation request from sealbridgesupply.com',
        '',
        'Name: ' . $name,
        'Company: ' . ($company ?: 'Not provided'),
        'Email: ' . $email,
        'Country / Region: ' . ($country ?: 'Not provided'),
        'WhatsApp: ' . ($whatsapp ?: 'Not provided'),
        'Product Type: ' . ($product_type ?: 'Not specified'),
        'Estimated Quantity: ' . ($quantity ?: 'Not provided'),
        '',
        'Project Details:',
        $message,
        '',
        'Submitted: ' . current_time('mysql'),
    ]);
    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        sprintf('Reply-To: %s <%s>', $name, $email),
    ];

    $sent = wp_mail(sealbridge_contact_email(), $subject, $body, $headers, $attachments);

    if ($temporary_attachment !== '' && file_exists($temporary_attachment)) {
        wp_delete_file($temporary_attachment);
    }

    if (!$sent) {
        delete_transient($rate_key);
        sealbridge_quote_redirect('mail_error');
    }

    $confirmation_subject = 'We received your SealBridge Supply quotation request';
    $confirmation_body = "Hello {$name},\n\nThank you for sending your gasket project details. We received your request and will review the application, material, drawing, quantity, and document requirements.\n\nIf you need to add another file, reply to support@sealbridgesupply.com and include your company name.\n\nSealBridge Supply\nCustom Gasket Sourcing & Project Coordination";
    wp_mail($email, $confirmation_subject, $confirmation_body, ['Content-Type: text/plain; charset=UTF-8']);

    sealbridge_quote_redirect('sent');
}
add_action('admin_post_nopriv_sealbridge_quote_request', 'sealbridge_handle_quote_request');
add_action('admin_post_sealbridge_quote_request', 'sealbridge_handle_quote_request');

/** Keep the public quote nonce and form timestamp out of edge caches. */
function sealbridge_no_cache_quote_page(): void
{
    if (is_page('contact')) {
        nocache_headers();
    }
}
add_action('template_redirect', 'sealbridge_no_cache_quote_page', 0);

function sealbridge_seo_map(): array
{
    return [
        'home' => [
            'title' => 'Custom Gasket Sourcing for Electrical Enclosures & Control Cabinets',
            'description' => 'Custom gasket sourcing, sampling, and production coordination for electrical enclosures, cabinet doors, EPDM foam, silicone, and die-cut gasket projects.',
        ],
        'products_archive' => [
            'title' => 'Custom Gasket Product Categories for Enclosures and Cabinets',
            'description' => 'Browse electrical enclosure gaskets, control cabinet sealing strips, EPDM foam gaskets, silicone gaskets, adhesive-backed die-cut gaskets, and custom rubber gaskets.',
        ],
        'applications_archive' => [
            'title' => 'Gasket Application Scenarios for Electrical Enclosures',
            'description' => 'Application guides for outdoor electrical enclosure gaskets, control cabinet door seals, junction box gaskets, EV charger cabinets, solar inverters, and LED housings.',
        ],
        'categories' => [
            'material-selection' => [
                'title' => 'Gasket Material Selection Guides',
                'description' => 'Compare EPDM, silicone, sponge, foam, hardness, density, temperature, compression, and adhesive options for custom gasket projects.',
            ],
            'enclosure-sealing' => [
                'title' => 'Electrical Enclosure Sealing Guides',
                'description' => 'Practical guides for electrical enclosure gaskets, control cabinet door seals, outdoor boxes, compression design, and ingress protection projects.',
            ],
            'gasket-quotation' => [
                'title' => 'Custom Gasket Quotation Guides',
                'description' => 'Prepare gasket RFQs with the right drawings, materials, tolerances, quantities, tooling, inspection, packaging, and compliance information.',
            ],
            'compliance-testing' => [
                'title' => 'Gasket Compliance and Testing Guides',
                'description' => 'Understand IP testing, material documents, certificates, inspection requirements, and compliance questions for enclosure gasket projects.',
            ],
            'manufacturing-guides' => [
                'title' => 'Custom Gasket Manufacturing Guides',
                'description' => 'Guides to die cutting, adhesive lamination, release liners, tooling, tolerances, sampling, and production of custom gaskets.',
            ],
        ],
        'products' => [
            'electrical-enclosure-gaskets' => [
                'title' => 'Electrical Enclosure Gaskets | Custom Foam, Rubber & Silicone Seals',
                'description' => 'Custom electrical enclosure gaskets in EPDM foam, rubber, and silicone for panels, junction boxes, outdoor covers, and drawing-based sealing projects.',
            ],
            'control-cabinet-sealing-strips' => [
                'title' => 'Control Cabinet Door Sealing Strips | Gasketing for Electrical Cabinets',
                'description' => 'Control cabinet door sealing strips, electrical panel door gaskets, bulb seals, and EPDM profiles sized to the door gap and installation method.',
            ],
            'epdm-foam-gaskets' => [
                'title' => 'EPDM Foam Gaskets | Closed Cell Sponge Gasket Supplier',
                'description' => 'Closed-cell EPDM foam gaskets and EPDM sponge gaskets for outdoor waterproof, dustproof, weather-resistant enclosure sealing.',
            ],
            'silicone-gaskets' => [
                'title' => 'Silicone Gaskets for Electrical Enclosures | Custom Foam & Solid Silicone',
                'description' => 'Custom silicone rubber and silicone foam gaskets for electrical enclosures, LED housings, outdoor electronics, equipment covers, and temperature-sensitive sealing.',
            ],
            'adhesive-backed-die-cut-gaskets' => [
                'title' => 'Custom Die Cut Gaskets | SealBridge Supply',
                'description' => 'Custom die cut foam and rubber gaskets with adhesive backing for electrical boxes, enclosure panels, covers, and repeatable peel-and-stick assembly.',
            ],
            'custom-rubber-gaskets' => [
                'title' => 'Custom Rubber Gaskets According to Drawing | Rubber Seals',
                'description' => 'Custom rubber gaskets and custom rubber seals according to drawing, sample, non-standard size, material, hardness, and project requirements.',
            ],
        ],
        'applications' => [
            'junction-boxes' => [
                'title' => 'Junction Box Gaskets | Custom Die-Cut and Adhesive-Backed Seals',
                'description' => 'Custom junction box gaskets for cover sealing, including die-cut EPDM foam, adhesive-backed seals, silicone gaskets, and drawing-based quotation support.',
            ],
            'ev-charger-cabinets' => [
                'title' => 'EV Charger Enclosure Gaskets | Outdoor Cabinet Sealing',
                'description' => 'Custom EV charger enclosure gaskets for cabinet doors, access panels, and outdoor housings using EPDM foam, silicone, and drawing-based sealing parts.',
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
            'articles' => [
                'title' => 'Gasket Selection Guides and Sourcing Articles',
                'description' => 'Practical gasket guides covering material selection, enclosure sealing, RFQ preparation, compliance questions, and custom manufacturing.',
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

    if (is_category()) {
        $category = get_queried_object();
        return $map['categories'][$category->slug] ?? [
            'title' => single_cat_title('', false),
            'description' => category_description($category->term_id) ?: get_bloginfo('description'),
        ];
    }

    if (is_singular('product')) {
        $post = get_queried_object();
        return $map['products'][$post->post_name] ?? $fallback;
    }

    if (is_singular('application')) {
        $post = get_queried_object();
        return $map['applications'][$post->post_name] ?? [
            'title' => get_the_title($post),
            'description' => has_excerpt($post) ? get_the_excerpt($post) : wp_trim_words(wp_strip_all_tags($post->post_content), 24),
        ];
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

    if (is_category()) {
        $category = get_queried_object();
        $category_url = get_category_link($category->term_id);
        return is_wp_error($category_url) ? home_url('/') : $category_url;
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
    } elseif (is_singular('post') && has_post_thumbnail()) {
        $featured_image = get_the_post_thumbnail_url(get_queried_object_id(), 'full');
        if (is_string($featured_image) && $featured_image !== '') {
            $image = $featured_image;
        }
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
                    'email' => sealbridge_contact_email(),
                    'sameAs' => array_values(sealbridge_social_links()),
                    'contactPoint' => [
                        '@type' => 'ContactPoint',
                        'contactType' => 'customer support and quotation requests',
                        'email' => sealbridge_contact_email(),
                        'availableLanguage' => ['English', 'Chinese'],
                    ],
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
    } elseif (is_singular('post')) {
        $home_url = home_url('/');
        $schema = [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'Organization',
                    '@id' => $home_url . '#organization',
                    'name' => get_bloginfo('name'),
                    'url' => $home_url,
                    'logo' => sealbridge_logo_url(),
                    'email' => sealbridge_contact_email(),
                    'sameAs' => array_values(sealbridge_social_links()),
                ],
                [
                    '@type' => 'BlogPosting',
                    '@id' => $canonical . '#article',
                    'headline' => get_the_title(),
                    'description' => $description,
                    'url' => $canonical,
                    'mainEntityOfPage' => [
                        '@type' => 'WebPage',
                        '@id' => $canonical,
                    ],
                    'image' => $image,
                    'datePublished' => get_the_date(DATE_W3C),
                    'dateModified' => get_the_modified_date(DATE_W3C),
                    'author' => [
                        '@type' => 'Organization',
                        '@id' => $home_url . '#organization',
                        'name' => get_bloginfo('name'),
                    ],
                    'publisher' => [
                        '@id' => $home_url . '#organization',
                    ],
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

        if (!is_singular('product')) {
            $schema['email'] = sealbridge_contact_email();
            $schema['sameAs'] = array_values(sealbridge_social_links());
        }
    }

    if (is_singular('product')) {
        $schema['image'] = $image;
        $schema['serviceType'] = 'Custom gasket sourcing and project coordination';
        $schema['provider'] = [
            '@type' => 'Organization',
            '@id' => home_url('/') . '#organization',
            'name' => get_bloginfo('name'),
            'url' => home_url('/'),
            'email' => sealbridge_contact_email(),
            'sameAs' => array_values(sealbridge_social_links()),
        ];
        $schema['areaServed'] = [
            ['@type' => 'Country', 'name' => 'Canada'],
            ['@type' => 'Country', 'name' => 'United Kingdom'],
            ['@type' => 'AdministrativeArea', 'name' => 'European Union'],
            ['@type' => 'Country', 'name' => 'United States'],
        ];
    } elseif (!is_front_page() && !is_singular('post')) {
        $schema['logo'] = $image;
    }

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";

    if (is_singular('product')) {
        $product_faq = sealbridge_product_faq(get_queried_object());
        if ($product_faq) {
            $faq_schema = [
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                '@id' => $canonical . '#faq',
                'mainEntity' => array_map(
                    static function (string $question, string $answer): array {
                        return [
                            '@type' => 'Question',
                            'name' => $question,
                            'acceptedAnswer' => [
                                '@type' => 'Answer',
                                'text' => $answer,
                            ],
                        ];
                    },
                    array_keys($product_faq),
                    array_values($product_faq)
                ),
            ];

            echo '<script type="application/ld+json">' . wp_json_encode($faq_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
        }
    }
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

/**
 * Serve the brand icon from the conventional root favicon URL.
 *
 * WordPress handles /favicon.ico through the `do_faviconico` action and falls
 * back to its own blue W logo when the request is not overridden.  Browsers,
 * crawlers, and link-preview tools still request this root URL even when the
 * page head contains explicit icon links, so keep it aligned with the theme
 * icon set and return the image directly without a redirect.
 */
function sealbridge_serve_root_favicon(): void
{
    $favicon_path = get_template_directory() . '/assets/favicon.ico';

    if (!is_readable($favicon_path)) {
        return;
    }

    status_header(200);
    header('Content-Type: image/x-icon');
    header('Content-Length: ' . (string) filesize($favicon_path));
    header('Cache-Control: no-cache, must-revalidate, max-age=0');
    header('CDN-Cache-Control: no-store');
    readfile($favicon_path);
    exit;
}
add_action('do_faviconico', 'sealbridge_serve_root_favicon', 0);

/**
 * Return the most useful editorial guides for a product category.
 */
function sealbridge_product_article_slugs(string $product_slug): array
{
    $map = [
        'electrical-enclosure-gaskets' => [
            'choose-electrical-enclosure-gaskets-outdoor-boxes',
            'can-a-gasket-be-ip65-or-ip66-certified',
            'nema-3r-4-4x-enclosure-gasket-requirements',
            'electrical-enclosure-gasket-material-guide',
        ],
        'control-cabinet-sealing-strips' => [
            'control-cabinet-door-seal-profiles',
            'nema-3r-4-4x-enclosure-gasket-requirements',
        ],
        'epdm-foam-gaskets' => [
            'epdm-foam-gasket-with-adhesive-quote-parameters',
            'epdm-vs-silicone-outdoor-enclosure-gaskets',
            'closed-cell-epdm-sponge-density-compression-guide',
        ],
        'silicone-gaskets' => [
            'silicone-gaskets-led-lighting-outdoor-electronics',
            'epdm-vs-silicone-outdoor-enclosure-gaskets',
            'choose-electrical-enclosure-gaskets-outdoor-boxes',
        ],
        'adhesive-backed-die-cut-gaskets' => [
            'adhesive-backed-die-cut-gaskets-fast-enclosure-assembly',
            'custom-die-cut-gasket-quote-information',
        ],
        'custom-rubber-gaskets' => [
            'custom-rubber-gaskets-according-to-drawing-sample-quote',
            'custom-die-cut-gasket-quote-information',
        ],
    ];

    return $map[$product_slug] ?? [];
}

function sealbridge_disable_author_sitemap($provider, string $name)
{
    if ($name === 'users') {
        return false;
    }

    return $provider;
}
add_filter('wp_sitemaps_add_provider', 'sealbridge_disable_author_sitemap', 10, 2);

/**
 * Sitemap XML endpoints must never be sent through WordPress canonical URL
 * redirects. This protects the sitemap index from proxy-related self loops.
 */
function sealbridge_preserve_sitemap_requests($redirect_url, string $requested_url)
{
    $path = (string) wp_parse_url($requested_url, PHP_URL_PATH);

    if (preg_match('#^/wp-sitemap(?:-[^/]+)?\.xml$#', $path)) {
        return false;
    }

    return $redirect_url;
}
add_filter('redirect_canonical', 'sealbridge_preserve_sitemap_requests', 10, 2);

/** Redirect the retired article-library slug to its canonical replacement. */
function sealbridge_redirect_legacy_insights(): void
{
    $path = (string) wp_parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);

    if (trim($path, '/') !== 'insights') {
        return;
    }

    wp_safe_redirect(home_url('/articles/'), 301, 'SealBridge Supply');
    exit;
}
add_action('template_redirect', 'sealbridge_redirect_legacy_insights', 1);

/** Keep database-backed navigation menus aligned with /articles/. */
function sealbridge_update_legacy_article_menu_urls(array $items): array
{
    $legacy_url = untrailingslashit(home_url('/insights/'));
    $article_url = home_url('/articles/');

    foreach ($items as $item) {
        if (isset($item->url) && untrailingslashit((string) $item->url) === $legacy_url) {
            $item->url = $article_url;
        }
    }

    return $items;
}
add_filter('wp_nav_menu_objects', 'sealbridge_update_legacy_article_menu_urls');

function sealbridge_default_menu(): void
{
    ?>
    <ul>
        <li><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
        <li><a href="<?php echo esc_url(home_url('/products/')); ?>">Products</a></li>
        <li><a href="<?php echo esc_url(home_url('/applications/')); ?>">Applications</a></li>
        <li><a href="<?php echo esc_url(home_url('/articles/')); ?>">Articles</a></li>
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

/**
 * Add focused buyer guidance for product queries that already receive search
 * impressions. The copy clarifies purchasing intent without changing the
 * database-managed product description.
 */
function sealbridge_product_buyer_guidance(?WP_Post $post = null): array
{
    $post = $post ?: get_post();
    $guidance = [
        'electrical-enclosure-gaskets' => [
            'title' => 'Electrical Enclosure Gaskets for Panels, Covers, and Outdoor Boxes',
            'intro' => 'Use this product route for drawing-based enclosure cover gaskets, junction box frames, access-panel seals, and outdoor electrical box gaskets. The correct material depends on the cover geometry, compression gap, exposure conditions, and assembly method—not on an IP label alone.',
            'checks' => [
                'Material direction' => 'EPDM foam is a common starting point for outdoor weather resistance and compressible door sealing. Silicone foam or solid rubber may suit wider temperature ranges or different compression loads.',
                'Design information' => 'Send the enclosure drawing, gasket path, cover dimensions, target thickness, compression gap, adhesive requirement, and expected order quantity.',
                'Validation scope' => 'A gasket supports an IP65 or IP66 enclosure design, but the rating applies to the complete assembled enclosure and its test conditions.',
            ],
            'links' => [
                'Outdoor electrical enclosure gasket applications' => '/applications/outdoor-electrical-enclosures/',
                'Junction box gasket applications' => '/applications/junction-boxes/',
                'Electrical enclosure gasket material guide' => '/electrical-enclosure-gasket-material-guide/',
                'Silicone gaskets for electrical enclosures' => '/products/silicone-gaskets/',
                'How to choose an electrical enclosure gasket' => '/choose-electrical-enclosure-gaskets-outdoor-boxes/',
            ],
        ],
        'silicone-gaskets' => [
            'title' => 'Silicone Gaskets for Electrical Enclosures',
            'intro' => 'Use this product route for silicone gaskets, silicone rubber gaskets, and silicone foam gaskets where softness, UV exposure, temperature range, and clean assembly matter. Buyers often compare solid silicone against silicone foam for LED housings, outdoor electronics, battery covers, and temperature-sensitive enclosure projects.',
            'checks' => [
                'Material direction' => 'Solid silicone is usually selected for molded or cut parts that need stable elasticity and higher temperature resistance. Silicone foam can be a better fit when softer compression and lower closing force matter more.',
                'Design information' => 'Send the gasket path, solid or foam silicone preference, hardness, color, temperature range, UV exposure, adhesive requirement, and annual quantity.',
                'Validation scope' => 'Silicone is often chosen when EPDM is not the best fit for temperature or softness. The final gasket still needs to be designed around the complete enclosure assembly and test condition.',
            ],
            'links' => [
                'Silicone gasket applications for LED and outdoor electronics' => '/applications/led-lighting-housings/',
                'Outdoor electrical enclosure gasket applications' => '/applications/outdoor-electrical-enclosures/',
                'Electrical enclosure gasket material guide' => '/electrical-enclosure-gasket-material-guide/',
                'Electrical enclosure gasket options' => '/products/electrical-enclosure-gaskets/',
                'EPDM vs silicone outdoor gasket guide' => '/epdm-vs-silicone-outdoor-enclosure-gaskets/',
            ],
        ],
        'control-cabinet-sealing-strips' => [
            'title' => 'Control Cabinet Door Sealing Strips and Electrical Panel Door Gaskets',
            'intro' => 'Gasketing for control cabinets normally starts with the door gap and mounting edge. Bulb seals, D-profile strips, self-grip edge seals, and custom EPDM extrusions create different compression forces and installation conditions.',
            'checks' => [
                'Profile fit' => 'Confirm profile width and height, retaining edge dimensions, door closing gap, bend radius, and whether corners are cut, bonded, or supplied as continuous strip.',
                'Material direction' => 'Dense or sponge EPDM profiles are widely used for cabinet doors. Silicone profiles may be considered when softness or temperature exposure is more demanding.',
                'Quotation details' => 'Provide a profile drawing or section photo, material, hardness or density, color, roll or cut length, annual volume, and packaging preference.',
            ],
            'links' => [
                'Control cabinet sealing applications' => '/applications/control-cabinets/',
                'Electrical enclosure gaskets for cabinet panels' => '/products/electrical-enclosure-gaskets/',
                'Control cabinet door seal profile guide' => '/control-cabinet-door-seal-profiles/',
                'Request a custom cabinet gasket quote' => '/contact/',
            ],
        ],
        'adhesive-backed-die-cut-gaskets' => [
            'title' => 'Custom Die Cut Gaskets for Enclosure Assembly',
            'intro' => 'Custom die cut gaskets can be supplied as individual foam or rubber frames, kiss-cut parts on a release liner, or adhesive-backed pads for repeatable placement on electrical boxes, panels, and covers.',
            'checks' => [
                'Material and thickness' => 'Specify EPDM foam, silicone foam, rubber sheet, or another target material together with thickness, density or hardness, and compression requirements.',
                'Adhesive system' => 'The assembly surface, adhesive grade, release liner, operating temperature, and environmental exposure should be reviewed before sampling.',
                'Production file' => 'Send a DXF, PDF, CAD drawing, or dimensioned sketch with tolerances, quantity per order, packing format, and any inspection or document needs.',
            ],
            'links' => [
                'Adhesive-backed gasket assembly guide' => '/adhesive-backed-die-cut-gaskets-fast-enclosure-assembly/',
                'Custom die cut gasket RFQ checklist' => '/custom-die-cut-gasket-quote-information/',
                'Junction box gasket applications' => '/applications/junction-boxes/',
            ],
        ],
    ];

    return $guidance[$post->post_name] ?? [];
}

/**
 * Keep the visible product FAQs and FAQ structured data in one source of truth.
 */
function sealbridge_product_faq(?WP_Post $post = null): array
{
    $post = $post ?: get_post();
    $faqs = [
        'electrical-enclosure-gaskets' => [
            'What material is commonly used for electrical enclosure gaskets?' => 'Closed-cell EPDM foam is a common starting point for weather-exposed covers and panels. Silicone foam or solid silicone may be considered when temperature range, UV exposure, or softer compression is more important. Material selection should follow the enclosure gap and service conditions.',
            'Can an electrical enclosure gasket be supplied with adhesive backing?' => 'Yes. Adhesive-backed foam or rubber gaskets can support clean placement during assembly. Confirm the mounting surface, operating environment, adhesive requirement, release liner, and required positioning accuracy before sampling.',
            'Does a gasket itself have an IP65 or IP66 rating?' => 'No. IP ratings apply to the completed enclosure assembly under its test conditions. The gasket material, compression path, latches, corners, and cover design all contribute to the final result.',
            'What information is needed for an enclosure gasket quote?' => 'Provide the enclosure drawing or gasket path, cover dimensions, thickness or profile target, compression gap, material preference, adhesive requirement, quantity, and any RoHS, REACH, TDS, or SDS document needs.',
        ],
        'silicone-gaskets' => [
            'When should I choose silicone foam instead of solid silicone?' => 'Silicone foam can be useful when lower closing force and softer compression are needed around covers, lenses, or housings. Solid silicone is often selected for cut or molded parts that need stable elasticity and a more defined gasket section.',
            'How do silicone gaskets compare with EPDM for an electrical enclosure?' => 'Silicone is often considered for wider temperature exposure, UV stability, or softer compression. EPDM can be a practical and economical choice for many outdoor enclosure projects. The right choice depends on the enclosure design, compression range, and actual environment.',
            'Can silicone gaskets be adhesive backed?' => 'Yes, but the adhesive system should be matched to the silicone surface treatment, mounting material, temperature exposure, and assembly process. Confirm the adhesive requirement before sampling rather than assuming a general tape will bond correctly.',
            'What should be included in a silicone gasket RFQ?' => 'Send a drawing or sample, solid or foam preference, hardness, color, temperature exposure, UV condition, adhesive requirement, tolerance, quantity, and any requested material documents.',
        ],
        'control-cabinet-sealing-strips' => [
            'How do I choose a control cabinet door sealing strip profile?' => 'Start with the measured minimum and maximum door gap, mounting edge, available compression height, bend radius, latch locations, and corner treatment. Select the profile from those dimensions rather than from a catalog image alone.',
            'Can an electrical panel door gasket support IP or NEMA enclosure testing?' => 'A gasket supports the enclosure sealing design, but IP and NEMA ratings belong to the complete cabinet assembly and its test conditions. Door compression, latches, corners, frame design, and installation quality must all be considered.',
            'How should cabinet door seal corners be supplied?' => 'Corners may be cut, bonded, molded, or formed from a continuous strip depending on the profile, cabinet geometry, and assembly process. Share the door drawing and corner requirement so the joining approach can be reviewed before quotation.',
            'What is needed to quote control cabinet sealing strips?' => 'Provide a profile drawing, section photo, or sample together with door-gap range, gasket path, material, hardness or density, color, roll or cut length, corner requirement, quantity, packing, and document needs.',
        ],
    ];

    return $faqs[$post->post_name] ?? [];
}

function sealbridge_application_scenarios(): array
{
    return [
        ['Outdoor Electrical Enclosures', 'EPDM foam, silicone, and custom frame gaskets for outdoor box sealing.', 'EPDM Foam Gaskets / Enclosure Gaskets', 'outdoor-electrical-enclosures'],
        ['Control Cabinets', 'Door sealing strips, edge trims, and compression gaskets for repeated cabinet access.', 'Control Cabinet Sealing Strips', 'control-cabinets'],
        ['Junction Box Gaskets', 'Compact die-cut gaskets and adhesive-backed pads for cover and panel sealing.', 'Adhesive Backed Die Cut Gaskets', 'junction-boxes'],
        ['EV Charger Enclosure Gaskets', 'Outdoor gasket options for charger cabinet doors, access panels, and housings.', 'EPDM Foam / Silicone Gaskets', 'ev-charger-cabinets'],
        ['Solar Inverter Enclosures', 'UV, aging, and compression-focused sealing for outdoor energy equipment.', 'EPDM / Silicone Gaskets', 'solar-inverter-enclosures'],
        ['LED Lighting Housings', 'Soft silicone and foam gaskets for covers, lenses, and outdoor fixtures.', 'Silicone Gaskets / Foam Gaskets', 'led-lighting-housings'],
    ];
}

/**
 * Keep application H1s aligned with the primary buyer-intent query even when
 * an older database title is still present.
 */
function sealbridge_application_display_title(?WP_Post $post = null): string
{
    $post = $post ?: get_post();
    $titles = [
        'junction-boxes' => 'Junction Box Gaskets',
        'ev-charger-cabinets' => 'EV Charger Enclosure Gaskets',
    ];

    return $titles[$post->post_name] ?? get_the_title($post);
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
            'summary' => 'Custom junction box gaskets seal the joint between a box body and its removable cover. Common formats include die-cut EPDM foam frames, adhesive-backed seals, molded rubber gaskets, and silicone parts made to the cover drawing.',
            'pain_points' => ['Small gasket paths make dimensional tolerance and corner continuity important', 'Adhesive backing must match powder-coated metal, plastic, or another assembly surface', 'Cover screws can create uneven compression between fastening points', 'Outdoor junction boxes need stronger weather and aging resistance than general-purpose open-cell foam'],
            'materials' => ['Closed-cell EPDM foam for outdoor junction box cover sealing', 'Adhesive-backed die-cut foam for controlled placement and faster assembly', 'Silicone foam or solid silicone where softness or wider temperature exposure matters'],
            'structures' => ['One-piece die-cut frame gasket', 'Kiss-cut adhesive-backed gasket supplied on a release liner', 'Molded cover gasket with controlled corners', 'Foam strip joined into a frame for larger or lower-volume boxes'],
            'selection_factors' => ['Cover flange width and gasket path', 'Minimum, nominal, and maximum sealing gap', 'Fastener spacing and available closing force', 'Outdoor exposure, temperature, UV, and target enclosure test', 'Adhesive surface, liner, installation method, and annual volume'],
            'products' => ['adhesive-backed-die-cut-gaskets', 'electrical-enclosure-gaskets', 'epdm-foam-gaskets'],
            'related_articles' => ['adhesive-backed-die-cut-gaskets-fast-enclosure-assembly', 'can-a-gasket-be-ip65-or-ip66-certified'],
            'quote' => ['2D drawing, DXF, or cover gasket path', 'Box and cover material plus sealing surface finish', 'Material, thickness, density or hardness, and adhesive requirement', 'Compression gap, tolerance, and target enclosure test', 'Sample quantity, annual volume, liner, packing, and document needs'],
        ],
        'ev-charger-cabinets' => [
            'summary' => 'EV charger enclosure gaskets protect cabinet doors, access panels, display or connector housings, and internal electrical compartments from outdoor dust and water exposure. The gasket must be selected together with the enclosure gap, latch pressure, service access, and test target.',
            'pain_points' => ['Outdoor UV, ozone, rain, humidity, and thermal cycling can accelerate material aging', 'Tall charger cabinet doors need consistent compression along hinges, latches, and corners', 'Service panels, displays, connector areas, and main doors may need different gasket constructions', 'Material traceability and RoHS, REACH, UL94, TDS, or SDS information may be required'],
            'materials' => ['Closed-cell EPDM foam for weather-exposed charger cabinet doors and panels', 'Silicone foam or silicone rubber for softer closing force or wider temperature exposure', 'Custom molded or die-cut rubber gaskets for shaped covers, displays, and connector housings'],
            'structures' => ['Die-cut frame gasket for access panels and flat covers', 'Extruded bulb or D-profile seal for cabinet doors', 'Corner-joined foam or rubber frame', 'Molded gasket for non-flat charger components'],
            'selection_factors' => ['Enclosure gap and intended compression range', 'Door stiffness, hinge position, latch spacing, and closing force', 'Outdoor temperature, UV, ozone, rain, and cleaning exposure', 'Adhesive-backed, retained-channel, or mechanically located installation', 'Complete enclosure IP/NEMA test target and requested material documents'],
            'products' => ['epdm-foam-gaskets', 'silicone-gaskets', 'electrical-enclosure-gaskets'],
            'related_articles' => ['epdm-vs-silicone-outdoor-enclosure-gaskets', 'can-a-gasket-be-ip65-or-ip66-certified'],
            'quote' => ['Cabinet door, panel, or gasket-path drawing', 'Minimum and maximum gap plus compression target', 'Working temperature, UV, rain, cleaning, and service-access conditions', 'Material, thickness or profile size, joining, and adhesive requirements', 'Sample and annual quantity plus RoHS, REACH, UL94, TDS, or SDS needs'],
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
