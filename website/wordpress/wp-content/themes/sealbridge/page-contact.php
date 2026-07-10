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
                <div class="intro-band">
                    <p>Send the drawing, material, size, quantity, application, and certificate requirements. Clear project details help reduce back-and-forth and improve quotation accuracy.</p>
                </div>
                <h2>Quote Information Checklist</h2>
                <ul class="feature-list">
                    <li>Drawing or sample photo</li>
                    <li>Material target</li>
                    <li>Thickness or profile size</li>
                    <li>Hardness or density</li>
                    <li>Adhesive backing requirement</li>
                    <li>Order quantity and sample quantity</li>
                    <li>Application environment</li>
                    <li>RoHS, REACH, UL94, TDS, or SDS needs</li>
                </ul>
                <h2>Request Form Mockup</h2>
                <form class="quote-form">
                    <label>Name<input type="text" name="name" autocomplete="name"></label>
                    <label>Email<input type="email" name="email" autocomplete="email"></label>
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
                    <label>Project Details<textarea name="message" placeholder="Material, size, quantity, application, documents needed..."></textarea></label>
                    <button class="button" type="button">Prepare Quote Details</button>
                </form>
                <h2>Fast Contact Channels</h2>
                <div class="contact-channel-grid">
                    <a href="https://wa.me/8618770214461" target="_blank" rel="noopener">
                        <strong>WhatsApp</strong>
                        <span>+86 187 7021 4461</span>
                    </a>
                </div>
                <?php
            endwhile;
            ?>
        </article>
        <aside class="sidebar-box">
            <h2>Useful Attachments</h2>
            <p>Drawing, product photo, material target, quantity plan, and certificate requirements are the fastest path to a useful first quotation.</p>
            <div class="sidebar-contact-list">
                <a href="https://wa.me/8618770214461" target="_blank" rel="noopener">WhatsApp: +86 187 7021 4461</a>
            </div>
            <a class="button" href="<?php echo esc_url(home_url('/products/')); ?>">View Products</a>
        </aside>
    </div>
</section>

<?php
get_footer();
