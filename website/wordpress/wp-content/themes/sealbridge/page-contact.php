<?php
/**
 * Contact page template.
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
                <?php
                $quote_status = sanitize_key(wp_unslash($_GET['quote_status'] ?? ''));
                $quote_notices = [
                    'sent' => ['success', 'Thank you. Your quotation request has been sent to SealBridge Supply. A confirmation email should arrive shortly.'],
                    'invalid' => ['error', 'Please complete the required fields and submit the form again.'],
                    'file_error' => ['error', 'The attachment could not be accepted. Use PDF, CAD, ZIP, JPG, PNG, or WebP files up to 10 MB.'],
                    'rate_limited' => ['error', 'A request was submitted recently. Please wait one minute before trying again.'],
                    'mail_error' => ['error', 'The request could not be emailed. Please contact support@sealbridgesupply.com directly.'],
                ];
                if (isset($quote_notices[$quote_status])) :
                    ?>
                    <div class="form-notice <?php echo esc_attr($quote_notices[$quote_status][0]); ?>" role="status">
                        <?php echo esc_html($quote_notices[$quote_status][1]); ?>
                    </div>
                <?php endif; ?>
                <div class="intro-band">
                    <p>Send the drawing, material, size, quantity, application, destination country, and certificate requirements. We support manufacturers and B2B buyers across Canada, the United Kingdom, Europe, and other international markets.</p>
                </div>
                <h2>Quote Information Checklist</h2>
                <ul class="feature-list">
                    <li>Drawing or sample photo</li>
                    <li>Material target</li>
                    <li>Thickness or profile size</li>
                    <li>Hardness or density</li>
                    <li>Adhesive backing requirement</li>
                    <li>Order quantity and sample quantity</li>
                    <li>Destination country or region</li>
                    <li>Application environment</li>
                    <li>RoHS, REACH, UL94, TDS, or SDS needs</li>
                </ul>
                <h2>Request a Custom Gasket Quote</h2>
                <form id="quote-form" class="quote-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="sealbridge_quote_request">
                    <input type="hidden" name="form_started_at" value="<?php echo esc_attr((string) time()); ?>">
                    <?php wp_nonce_field('sealbridge_quote_request', 'sealbridge_quote_nonce'); ?>
                    <label class="quote-form-honeypot" aria-hidden="true">Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                    <label>Name *<input type="text" name="name" autocomplete="name" required></label>
                    <label>Company<input type="text" name="company" autocomplete="organization"></label>
                    <label>Email *<input type="email" name="email" autocomplete="email" required></label>
                    <label>Country / Region<input type="text" name="country" autocomplete="country-name"></label>
                    <label>WhatsApp<input type="tel" name="whatsapp" autocomplete="tel" placeholder="+86 187 7021 4461"></label>
                    <label>Product Type
                        <select name="product_type">
                            <option>Electrical Enclosure Gasket</option>
                            <option>Control Cabinet Sealing Strip</option>
                            <option>EPDM Foam Gasket</option>
                            <option>Silicone Gasket</option>
                            <option>Adhesive Backed Die Cut Gasket</option>
                            <option>Custom Rubber Gasket</option>
                        </select>
                    </label>
                    <label>Estimated Quantity<input type="text" name="quantity" placeholder="Sample quantity and annual or order quantity"></label>
                    <label>Project Details *<textarea name="message" required placeholder="Material, size, compression gap, application, environment, documents needed..."></textarea></label>
                    <label>Drawing or Product File
                        <input type="file" name="project_file" accept=".pdf,.dxf,.dwg,.step,.stp,.igs,.iges,.zip,.jpg,.jpeg,.png,.webp">
                        <small>PDF, CAD, ZIP, JPG, PNG, or WebP; maximum 10 MB.</small>
                    </label>
                    <label class="quote-form-consent">
                        <input type="checkbox" name="privacy_consent" value="1" required>
                        <span>I agree that SealBridge Supply may use these details to review and respond to this quotation request. See the <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">Privacy Policy</a>.</span>
                    </label>
                    <button class="button" type="submit">Send Quotation Request</button>
                </form>
                <h2>Fast Contact Channels</h2>
                <div class="contact-channel-grid">
                    <a href="<?php echo esc_url('mailto:' . sealbridge_contact_email()); ?>">
                        <strong>Email</strong>
                        <span><?php echo esc_html(sealbridge_contact_email()); ?></span>
                    </a>
                    <a href="https://wa.me/8618770214461" target="_blank" rel="noopener">
                        <strong>WhatsApp</strong>
                        <span>+86 187 7021 4461</span>
                    </a>
                    <?php foreach (sealbridge_social_links() as $platform => $social_url) : ?>
                        <a href="<?php echo esc_url($social_url); ?>" target="_blank" rel="noopener noreferrer">
                            <strong class="contact-social-heading">
                                <span class="social-brand-mark social-brand-mark--<?php echo esc_attr(strtolower($platform)); ?>" aria-hidden="true"><?php echo sealbridge_social_icon($platform); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                                <?php echo esc_html($platform); ?>
                            </strong>
                            <span>View our public profile</span>
                        </a>
                    <?php endforeach; ?>
                </div>
                <?php
            endwhile;
            ?>
        </article>
        <aside class="sidebar-box">
            <h2>Useful Attachments</h2>
            <p>Drawing, product photo, material target, quantity plan, and certificate requirements are the fastest path to a useful first quotation.</p>
            <div class="sidebar-contact-list">
                <a href="<?php echo esc_url('mailto:' . sealbridge_contact_email()); ?>">Email: <?php echo esc_html(sealbridge_contact_email()); ?></a>
                <a href="https://wa.me/8618770214461" target="_blank" rel="noopener">WhatsApp: +86 187 7021 4461</a>
            </div>
            <a class="button" href="<?php echo esc_url(home_url('/products/')); ?>">View Products</a>
        </aside>
    </div>
</section>

<?php
get_footer();
