<?php
/**
 * Site footer.
 *
 * @package SealBridge
 */
?>
</main>
<footer class="site-footer">
    <div class="section-inner footer-grid">
        <div class="footer-brand">
            <img class="footer-logo" src="<?php echo esc_url(sealbridge_logo_url()); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
            <strong><?php bloginfo('name'); ?></strong>
            <p><?php bloginfo('description'); ?></p>
            <nav class="footer-social-links" aria-label="<?php esc_attr_e('SealBridge Supply social profiles', 'sealbridge'); ?>">
                <?php foreach (sealbridge_social_links() as $platform => $social_url) : ?>
                    <a href="<?php echo esc_url($social_url); ?>" target="_blank" rel="noopener noreferrer">
                        <span class="social-brand-mark social-brand-mark--<?php echo esc_attr(strtolower($platform)); ?>" aria-hidden="true"><?php echo sealbridge_social_icon($platform); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                        <?php echo esc_html($platform); ?>
                    </a>
                <?php endforeach; ?>
            </nav>
        </div>
        <nav class="footer-link-group" aria-label="<?php esc_attr_e('Products and sourcing', 'sealbridge'); ?>">
            <strong>Explore</strong>
            <a href="<?php echo esc_url(home_url('/products/')); ?>">Products</a>
            <a href="<?php echo esc_url(home_url('/applications/')); ?>">Applications</a>
            <a href="<?php echo esc_url(home_url('/articles/')); ?>">Articles</a>
            <a href="<?php echo esc_url(home_url('/materials/')); ?>">Materials</a>
            <a href="<?php echo esc_url(home_url('/capabilities/')); ?>">Capabilities</a>
        </nav>
        <nav class="footer-link-group" aria-label="<?php esc_attr_e('Company information', 'sealbridge'); ?>">
            <strong>Company</strong>
            <a href="<?php echo esc_url(home_url('/about/')); ?>">About</a>
            <a href="<?php echo esc_url(home_url('/factory-screening/')); ?>">Factory Screening</a>
            <a href="<?php echo esc_url(home_url('/service-follow-up/')); ?>">Service & Follow-up</a>
            <a href="<?php echo esc_url(home_url('/sourcing-process/')); ?>">Sourcing Process</a>
            <a href="<?php echo esc_url(home_url('/faq/')); ?>">FAQ</a>
            <a href="<?php echo esc_url(home_url('/contact/')); ?>">Request a Quote</a>
        </nav>
        <nav class="footer-link-group" aria-label="<?php esc_attr_e('Policies and compliance', 'sealbridge'); ?>">
            <strong>Trust</strong>
            <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">Privacy Policy</a>
            <a href="<?php echo esc_url(home_url('/terms-of-service/')); ?>">Terms of Service</a>
            <a href="<?php echo esc_url('mailto:' . sealbridge_contact_email()); ?>"><?php echo esc_html(sealbridge_contact_email()); ?></a>
        </nav>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
