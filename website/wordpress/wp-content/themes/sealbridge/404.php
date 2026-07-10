<?php
/**
 * 404 template.
 *
 * @package SealBridge
 */

get_header();
?>

<section class="section">
    <div class="section-inner entry-content">
        <h1 class="page-title">Page Not Found</h1>
        <p>The page may have moved. Try the product pages or send a project request.</p>
        <a class="button" href="<?php echo esc_url(home_url('/')); ?>">Back to Home</a>
    </div>
</section>

<?php
get_footer();
