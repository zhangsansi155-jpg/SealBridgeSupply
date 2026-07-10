<?php
/**
 * Site header.
 *
 * @package SealBridge
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header">
    <div class="header-inner">
        <a class="brand" href="<?php echo esc_url(home_url('/')); ?>">
            <span class="brand-name"><?php bloginfo('name'); ?></span>
            <span class="brand-tagline"><?php bloginfo('description'); ?></span>
        </a>
        <nav class="main-nav" aria-label="<?php esc_attr_e('Primary navigation', 'sealbridge'); ?>">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'container' => false,
                'fallback_cb' => 'sealbridge_default_menu',
            ]);
            ?>
        </nav>
    </div>
</header>
<main class="site-main">
