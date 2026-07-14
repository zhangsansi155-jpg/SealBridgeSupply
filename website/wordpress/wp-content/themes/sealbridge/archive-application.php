<?php
/**
 * Application archive template.
 *
 * @package SealBridge
 */

get_header();
?>

<section class="section">
    <div class="section-inner">
        <div class="section-header">
            <h1 class="page-title">Applications</h1>
            <p>Practical sealing applications for enclosure, cabinet, energy, lighting, telecom, and HVAC equipment projects.</p>
        </div>
        <div class="grid products">
            <?php
            if (have_posts()) :
                while (have_posts()) :
                    the_post();
                    ?>
                    <article class="card">
                        <div class="card-marker"></div>
                        <h2><a href="<?php the_permalink(); ?>"><?php echo esc_html(sealbridge_application_display_title()); ?></a></h2>
                        <p><?php echo esc_html(get_the_excerpt()); ?></p>
                    </article>
                    <?php
                endwhile;
            else :
                $starter_applications = [
                    ['Outdoor Electrical Enclosures', 'Weather-resistant gasket selection for dust and water ingress protection support.'],
                    ['Control Cabinets', 'Door seals, edge strips, and foam gaskets for industrial cabinet assemblies.'],
                    ['EV Charger Cabinets', 'Outdoor sealing support for access panels, doors, and electrical modules.'],
                    ['Solar Inverter Enclosures', 'EPDM and silicone gasket options for UV, aging, and temperature exposure.'],
                    ['LED Lighting Housings', 'Foam and silicone gasket options for fixture covers and outdoor lighting.'],
                    ['Telecom Cabinets', 'Custom sealing parts for equipment cabinets and outdoor communication enclosures.'],
                ];

                foreach ($starter_applications as $application) :
                    ?>
                    <article class="card">
                        <div class="card-marker"></div>
                        <h2><?php echo esc_html($application[0]); ?></h2>
                        <p><?php echo esc_html($application[1]); ?></p>
                    </article>
                    <?php
                endforeach;
            endif;
            ?>
        </div>
    </div>
</section>

<?php
get_footer();
