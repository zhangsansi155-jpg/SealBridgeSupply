<?php
/**
 * Seed SealBridge secondary pages and starter content.
 *
 * Run inside the WordPress container:
 * php wp-content/themes/sealbridge/tools/seed-secondary-pages.php
 *
 * @package SealBridge
 */

require_once dirname(__DIR__, 4) . '/wp-load.php';

function sealbridge_upsert_post(array $args): int
{
    $existing = get_page_by_path($args['post_name'], OBJECT, $args['post_type']);

    $postarr = array_merge([
        'post_status' => 'publish',
        'post_author' => 1,
    ], $args);

    if ($existing) {
        $postarr['ID'] = $existing->ID;
        return wp_update_post($postarr, true);
    }

    return wp_insert_post($postarr, true);
}

$pages = [
    [
        'post_title' => 'Materials',
        'post_name' => 'materials',
        'post_type' => 'page',
        'post_content' => '<div class="intro-band"><p>Material choice decides compression recovery, outdoor life, adhesive performance, cost, and the documents that can be provided for a project.</p></div>
<h2>Material Options</h2>
<table><thead><tr><th>Material</th><th>Best Fit</th><th>Notes</th></tr></thead><tbody>
<tr><td>EPDM</td><td>Outdoor electrical enclosures, control cabinets, HVAC units</td><td>Good weather, ozone, aging, and water resistance. Not ideal for long oil exposure.</td></tr>
<tr><td>Silicone</td><td>High or low temperature, UV exposure, soft sealing</td><td>Flexible and stable, usually higher cost than EPDM or NBR.</td></tr>
<tr><td>NBR</td><td>Oil-resistant gasket applications</td><td>Useful around oils and fuels, less preferred for long outdoor exposure.</td></tr>
<tr><td>Neoprene / CR</td><td>Balanced industrial sealing and foam gaskets</td><td>Good general-purpose option when weather, oil, and flame behavior all matter.</td></tr>
<tr><td>EVA / PU Foam</td><td>Cushioning, dust sealing, packaging, low-pressure sealing</td><td>Confirm water absorption, aging, and compression set before outdoor use.</td></tr>
</tbody></table>
<h2>How We Compare Materials</h2>
<ul class="feature-list"><li>Outdoor exposure and UV aging</li><li>Compression set and recovery</li><li>Thickness, density, and hardness</li><li>Adhesive backing compatibility</li><li>RoHS, REACH, TDS, and SDS availability</li><li>Cost target and production process</li></ul>',
    ],
    [
        'post_title' => 'Capabilities',
        'post_name' => 'capabilities',
        'post_type' => 'page',
        'post_content' => '<div class="intro-band"><p>SealBridge Supply coordinates suitable process routes for custom rubber, silicone, and foam gasket projects instead of forcing every inquiry into one factory capability.</p></div>
<h2>Process Routes</h2>
<table><thead><tr><th>Process</th><th>Suitable Products</th><th>Quotation Details Needed</th></tr></thead><tbody>
<tr><td>Die Cutting</td><td>Flat foam gaskets, adhesive backed gaskets, rubber sheet gaskets</td><td>Drawing, material, thickness, adhesive, tolerance, quantity</td></tr>
<tr><td>Compression Molding</td><td>Custom rubber gaskets, shaped silicone parts, thicker rubber components</td><td>3D or 2D drawing, material, hardness, tolerance, mold requirement</td></tr>
<tr><td>Rubber Extrusion</td><td>Cabinet door seals, D-shape strips, U-channel profiles, long sealing strips</td><td>Profile drawing, material, hardness, length, joining requirement</td></tr>
<tr><td>Adhesive Backing</td><td>Assembly-ready foam and rubber gaskets</td><td>Adhesive brand, surface condition, temperature, peel requirement</td></tr>
</tbody></table>
<h2>Project Support</h2>
<ul class="feature-list"><li>Sample pack coordination</li><li>Drawing review before quotation</li><li>Material and process matching</li><li>Supplier photo and video collection</li><li>Packaging and label detail follow-up</li><li>Document support from production partners</li></ul>',
    ],

    [
        'post_title' => 'Factory Screening',
        'post_name' => 'factory-screening',
        'post_type' => 'page',
        'post_content' => '<div class="intro-band"><p>Factory screening brings supplier matching, document review, certificate templates, and workshop references into one practical review page for custom gasket projects.</p></div>
<h2>Factory Screening Overview</h2>
<p>SealBridge Supply works with a focused network of screened production partners and factory contacts for enclosure gaskets, cabinet sealing strips, EPDM foam gaskets, silicone gaskets, adhesive-backed die-cut parts, and drawing-based rubber seals. Factory screening is not a quick name list. We compare product fit, process route, sample response, communication quality, confidentiality awareness, and document support before recommending a factory direction.</p>
<div class="trust-strip"><div><strong>20+</strong><span>screened production partners and factory contacts</span></div><div><strong>4</strong><span>core process routes: die cutting, molding, extrusion, adhesive backing</span></div><div><strong>5+</strong><span>common document types: RoHS, REACH, UL94, TDS, SDS, ISO when available</span></div></div>
<h2>Screening Conditions</h2>
<ul class="feature-list"><li>Product fit for electrical enclosure, control cabinet, foam, silicone, or adhesive-backed gasket projects</li><li>Clear process capability: die cutting, molding, extrusion, lamination, sampling, or assembly support</li><li>Ability to provide useful sample photos, product photos, profile details, workshop references, or production context</li><li>Reasonable response speed for drawings, material questions, MOQ, tooling, lead time, and packaging details</li><li>Document support such as TDS, SDS, RoHS, REACH, UL94 material information, or ISO system certificates when available</li><li>Willingness to protect customer drawings, logos, packaging information, and confidential project files</li></ul>
<h2>Factory Directions We Match</h2>
<table><thead><tr><th>Factory Direction</th><th>Typical Products</th><th>Process Fit</th></tr></thead><tbody>
<tr><td>Die-cut gasket suppliers</td><td>Flat foam gaskets, adhesive-backed pads, rubber sheet gaskets</td><td>Die cutting, adhesive lamination, kiss cutting, sheet processing</td></tr>
<tr><td>Rubber molding suppliers</td><td>Custom rubber gaskets, silicone parts, shaped seals</td><td>Compression molding, tooling, compound review</td></tr>
<tr><td>Rubber extrusion suppliers</td><td>Cabinet door strips, D-shape/P-shape seals, U-channel profiles</td><td>Profile extrusion, cutting, corner joining, long strip packing</td></tr>
<tr><td>Foam material suppliers</td><td>EPDM foam, silicone foam, EVA, PU foam samples</td><td>Material sheet supply, density options, thickness range</td></tr>
<tr><td>Adhesive and lamination partners</td><td>3M-backed or general adhesive-backed gasket parts</td><td>Adhesive selection, release liner, surface treatment support</td></tr>
</tbody></table>
<h2>Workshop and Sample Review</h2>
<div class="factory-workshop-note"><strong>Production partner site references</strong><p>These original workshop photos are used as screening evidence from a production partner. They show relevant process and inspection context; they do not represent a claim that SealBridge owns the facility.</p></div>
<div class="workshop-grid factory-workshop-gallery"><figure><img src="/wp-content/themes/sealbridge/assets/trust/factory-workshop/mixing-molding.webp" alt="Rubber mixing and material preparation equipment at a screened production partner" width="1065" height="663" loading="lazy"><figcaption>Material preparation and rubber mixing equipment used before forming.</figcaption></figure><figure><img src="/wp-content/themes/sealbridge/assets/trust/factory-workshop/tooling-storage.webp" alt="Organized rubber mold storage area at a screened production partner" width="1065" height="663" loading="lazy"><figcaption>Organized mold storage supports tooling identification and repeat orders.</figcaption></figure><figure><img src="/wp-content/themes/sealbridge/assets/trust/factory-workshop/inspection-workshop.webp" alt="Manual trimming and full inspection workshop for rubber sealing parts" width="1065" height="663" loading="lazy"><figcaption>Manual trimming and full inspection area for molded sealing parts.</figcaption></figure><figure><img src="/wp-content/themes/sealbridge/assets/trust/factory-workshop/testing-laboratory.webp" alt="Rubber material and gasket testing laboratory at a screened production partner" width="1065" height="663" loading="lazy"><figcaption>Laboratory reference for dimensional and material-related verification.</figcaption></figure></div>
<h2>Certificate and Document Review</h2>
<p>Certificate support depends on selected material, supplier source, order quantity, and customer requirement. We do not publish customer-specific reports or supplier files publicly by default. Instead, we use a review checklist during project communication.</p>
<figure class="content-figure"><img src="/wp-content/themes/sealbridge/assets/trust/certificate-review.png" alt="Certificate review template illustration"><figcaption>Illustrative certificate review template. It explains the checking method and is not a public claim that every product has every certificate.</figcaption></figure>
<table><thead><tr><th>Document Type</th><th>What We Check</th><th>Important Note</th></tr></thead><tbody>
<tr><td>RoHS</td><td>Material name, test item, test date, supplier or material source</td><td>RoHS is material-related and should match the quoted material.</td></tr>
<tr><td>REACH</td><td>Declaration or test scope, SVHC reference, document date</td><td>Customer requirement should be confirmed because REACH scope may change.</td></tr>
<tr><td>UL94</td><td>Material grade, thickness condition, supplier statement or data sheet</td><td>UL94 grade belongs to tested material conditions, not automatically every finished gasket.</td></tr>
<tr><td>TDS</td><td>Hardness, density, tensile strength, temperature range, compression set</td><td>TDS is useful for engineering review and material comparison.</td></tr>
<tr><td>SDS</td><td>Material safety handling information and supplier source</td><td>SDS supports safety and handling review, not sealing performance.</td></tr>
<tr><td>ISO 9001</td><td>Factory quality management certificate if available</td><td>Check company name, validity, certification body, and business scope.</td></tr>
</tbody></table>
<h2>What We Do Not Overclaim</h2>
<p>We do not describe every supplier as certified for every product. We do not claim that a gasket alone has IP65 or IP66 certification. IP-rated sealing belongs to the final enclosure assembly and test condition. Formal document files should be confirmed against the selected material, supplier source, and purchase requirement.</p>',
    ],
    [
        'post_title' => 'About',
        'post_name' => 'about',
        'post_type' => 'page',
        'post_content' => '<div class="intro-band"><p>SealBridge Supply is a sourcing and project coordination partner focused on custom gaskets for electrical enclosures, control cabinets, and outdoor equipment.</p></div>
<h2>Company Background</h2>
<p>SealBridge Supply was created for overseas B2B buyers who need custom rubber, silicone, and foam gasket support but do not want to spend weeks sorting out materials, processes, supplier communication, samples, and compliance documents. The brand focuses on electrical enclosure, control cabinet, junction box, EV charger cabinet, solar inverter enclosure, LED lighting housing, telecom cabinet, and HVAC equipment sealing projects.</p>
<p>We position ourselves as a custom gasket sourcing and project coordination partner. That means we do not present every product as if it comes from one single factory. Instead, we help customers clarify the project requirement, match the suitable process route, coordinate samples and supplier information, and organize quotation details before production follow-up.</p>
<h2>What We Coordinate</h2>
<ul class="feature-list"><li>Material comparison for EPDM, silicone, NBR, CR, EVA, PU foam, and other gasket materials</li><li>Process matching for die cutting, compression molding, rubber extrusion, adhesive backing, and custom sampling</li><li>Supplier communication, sample photos, product videos, packaging details, and quote parameters</li><li>Document support such as TDS, SDS, RoHS, REACH, and UL94 material information when available</li></ul>
<h2>Supplier Network</h2>
<p>We maintain a working network of 20+ screened production partners and factory contacts across rubber, silicone, foam, adhesive-backed die cutting, molding, and extrusion directions. The number is used as a practical sourcing range rather than a claim that every project will use every factory. For each inquiry, we select partners based on product fit, process route, sample response, document support, and communication quality.</p>
<h2>Who We Serve</h2>
<ul class="feature-list"><li>Electrical enclosure manufacturers</li><li>Control cabinet manufacturers</li><li>Junction box manufacturers</li><li>EV charger cabinet manufacturers</li><li>Solar inverter enclosure manufacturers</li><li>LED lighting housing manufacturers</li></ul>
<h2>How We Work</h2>
<p>Our work starts from application and drawing review, not from a generic price list. A useful gasket quote usually needs the drawing, material target, thickness or profile size, hardness or density, adhesive requirement, quantity, application environment, and document requirements. We help organize those details so the supplier conversation is faster and more accurate.</p>',
    ],
    [
        'post_title' => 'Service & Follow-up',
        'post_name' => 'service-follow-up',
        'post_type' => 'page',
        'post_content' => '<div class="intro-band"><p>SealBridge Supply is built around follow-up service: clarifying requirements, organizing supplier communication, preparing document checklists, and keeping project information reusable for future cooperation.</p></div>
<h2>Our Service Advantages</h2>
<ul class="feature-list"><li>Focused category knowledge around enclosure gaskets, cabinet seals, foam gaskets, silicone gaskets, and adhesive-backed die-cut parts</li><li>Bilingual sourcing coordination between overseas buyers and Chinese production partners</li><li>Supplier matching based on material, process, sample feasibility, document support, and order quantity</li><li>Clear quote checklists that reduce repeated communication and wrong assumptions</li><li>Document-first mindset for RoHS, REACH, UL94, TDS, SDS, and supplier certificates when available</li><li>Project files that can be reused for repeat orders, product page updates, and future sourcing</li></ul>
<h2>How We Serve a New Inquiry</h2>
<table><thead><tr><th>Stage</th><th>What We Do</th><th>Customer Output</th></tr></thead><tbody>
<tr><td>1. Requirement intake</td><td>Review drawing, photo, application, material target, quantity, and compliance needs</td><td>Clean quote checklist</td></tr>
<tr><td>2. Material direction</td><td>Compare EPDM, silicone, NBR, CR, EVA, PU foam, adhesive backing, and process route</td><td>Suggested material/process direction</td></tr>
<tr><td>3. Supplier matching</td><td>Select suitable screened production partners or factory contacts</td><td>Supplier quote request package</td></tr>
<tr><td>4. Quote coordination</td><td>Clarify MOQ, tooling, lead time, sample cost, document support, and packaging details</td><td>More complete quotation information</td></tr>
<tr><td>5. Sample follow-up</td><td>Coordinate sample photos, sample labels, shipment details, and feedback collection</td><td>Sample review file</td></tr>
<tr><td>6. Repeat cooperation</td><td>Keep material, drawing, supplier, quote, and document notes for future orders</td><td>Reusable project record</td></tr>
</tbody></table>
<h2>Follow-up File Structure</h2>
<p>For ongoing cooperation, we can organize each project around a simple file structure:</p>
<ul><li>Product name and application scenario</li><li>Drawing version and sample photos</li><li>Material, hardness or density, thickness, adhesive, and process notes</li><li>Supplier quote parameters, MOQ, tooling, and lead time</li><li>Certificate/document status: RoHS, REACH, UL94, TDS, SDS, ISO 9001 if available</li><li>Sample feedback, packaging notes, and repeat order reference</li></ul>
<h2>How This Helps Long-term Cooperation</h2>
<p>Many gasket projects are not one-time decisions. A buyer may start with one enclosure gasket, then add cabinet strips, foam pads, adhesive-backed parts, or another application. A reusable project record helps reduce repeated explanation, speeds up new quotations, and keeps material and document history clearer.</p>',
    ],
    [
        'post_title' => 'Sourcing Process',
        'post_name' => 'sourcing-process',
        'post_type' => 'page',
        'post_content' => '<div class="intro-band"><p>A clear sourcing process reduces back-and-forth, avoids wrong material assumptions, and helps customers get more useful gasket quotations.</p></div>
<h2>Step 1: Application Review</h2>
<p>We first confirm where the gasket will be used: outdoor electrical enclosure, control cabinet, junction box, EV charger cabinet, solar inverter enclosure, LED lighting housing, or another equipment assembly.</p>
<h2>Step 2: Drawing and Parameter Check</h2>
<table><tbody><tr><th>Drawing</th><td>2D drawing, 3D file, profile drawing, sample photo, or gasket path sketch.</td></tr><tr><th>Material</th><td>EPDM, silicone, NBR, CR, EVA, PU foam, or open material recommendation.</td></tr><tr><th>Dimensions</th><td>Thickness, width, profile size, tolerance, length, and compression gap.</td></tr><tr><th>Assembly</th><td>Adhesive backing, screw compression, groove installation, edge trim, or custom fixture.</td></tr><tr><th>Documents</th><td>RoHS, REACH, UL94 material information, TDS, SDS, or other buyer requirements.</td></tr></tbody></table>
<h2>Step 3: Material and Process Matching</h2>
<p>We compare whether the project is better suited for die cutting, compression molding, rubber extrusion, adhesive lamination, or custom sampling. The goal is to match the project to the right production route before asking for price.</p>
<h2>Step 4: Supplier Coordination and Quotation</h2>
<p>We organize the quote request, coordinate supplier questions, collect sample photos or videos where available, and help clarify mold cost, lead time, MOQ, packaging, and document support.</p>
<h2>Step 5: Sampling and Production Follow-up</h2>
<p>For suitable projects, we coordinate sample preparation, sample labels, packaging information, and production follow-up details so the customer can review the part before bulk order decisions.</p>',
    ],
    [
        'post_title' => 'FAQ',
        'post_name' => 'faq',
        'post_type' => 'page',
        'post_content' => '<div class="intro-band"><p>Common questions from buyers sourcing custom enclosure gaskets, sealing strips, foam gaskets, and adhesive-backed die cut parts.</p></div>
<h2>Can you provide a price without a drawing?</h2>
<p>A rough range may be possible for standard materials, but accurate quotation usually needs drawing, size, material, thickness or profile details, quantity, and application information.</p>
<h2>Can a gasket itself be IP65 or IP66 certified?</h2>
<p>Usually no. IP rating belongs to the final enclosure assembly and test condition. The gasket can support IP-rated sealing, but the complete box, door, compression design, and installation must be tested as a system.</p>
<h2>Do you support small-batch samples?</h2>
<p>Sample support depends on material, process, tooling, supplier schedule, and order potential. Die-cut samples are often easier than molded or extruded custom profiles that require tooling.</p>
<h2>What documents can be provided?</h2>
<p>Depending on the material and supplier, available documents may include TDS, SDS, RoHS, REACH, and UL94 material grade information. Document availability should be confirmed for the selected material and production partner.</p>
<h2>Which material is best for outdoor enclosures?</h2>
<p>EPDM foam is often a practical starting point for outdoor enclosure sealing because of weather, ozone, aging, and water resistance. Silicone may be better for broader temperature range or softer sealing requirements.</p>
<h2>Do you sell standard products or custom parts?</h2>
<p>The site focuses on custom gasket sourcing. Some standard samples and common profiles may be available, but most useful quotations are based on the customer drawing, material, quantity, and application.</p>',
    ],
    [
        'post_title' => 'Privacy Policy',
        'post_name' => 'privacy-policy',
        'post_type' => 'page',
        'post_content' => '<div class="intro-band"><p>This privacy policy explains how SealBridge Supply handles information submitted through website forms, email, and project communication.</p></div>
<h2>Information We May Collect</h2>
<ul><li>Name, company, email, phone, and country when you submit an inquiry.</li><li>Project details such as product type, drawing, material, size, quantity, application, and document requirements.</li><li>Website usage information such as pages visited, browser information, and general traffic data if analytics tools are enabled.</li></ul>
<h2>How We Use Information</h2>
<p>We use submitted information to understand project requirements, communicate about quotations, coordinate samples or supplier information, improve website content, and maintain business records.</p>
<h2>Project Drawings and Confidential Information</h2>
<p>Customer drawings, product photos, and project files are used for quotation and project communication. We do not intentionally publish customer drawings, logos, packaging information, or confidential project files without permission.</p>
<h2>Sharing With Production Partners</h2>
<p>When quotation or sampling requires supplier review, relevant project details may be shared with suitable production partners. We aim to share only the information necessary for material, process, quote, and production evaluation.</p>
<h2>Data Retention</h2>
<p>Inquiry and project communication records may be retained for business follow-up, customer support, and reference for repeat projects unless deletion is requested and legally or operationally possible.</p>
<h2>Contact</h2>
<p>For privacy-related requests, please contact us through the website contact page.</p>',
    ],
    [
        'post_title' => 'Terms of Service',
        'post_name' => 'terms-of-service',
        'post_type' => 'page',
        'post_content' => '<div class="intro-band"><p>These terms describe the general use of the SealBridge Supply website and project communication. They are written for B2B sourcing and quotation discussions.</p></div>
<h2>Website Information</h2>
<p>Website content is provided for general product, material, process, and sourcing reference. It should not be treated as a final engineering specification without project-specific confirmation.</p>
<h2>Quotation and Availability</h2>
<p>Prices, lead times, MOQ, tooling cost, sample cost, document availability, and production capacity depend on material, drawing, quantity, supplier review, and current production conditions. Formal quotations should be confirmed in writing for each project.</p>
<h2>Samples and Product Suitability</h2>
<p>Samples are used for evaluation and confirmation. Final product suitability should be reviewed by the buyer based on the actual application, assembly design, environmental condition, and required testing.</p>
<h2>Compliance and IP Rating</h2>
<p>Material documents such as RoHS, REACH, UL94 information, TDS, and SDS may be coordinated when available. IP ratings apply to complete enclosure assemblies and test conditions, not to a gasket alone.</p>
<h2>Customer Files</h2>
<p>Customers are responsible for ensuring that drawings, photos, logos, and technical files submitted for quotation can be shared for project evaluation. We aim to use those files only for sourcing, quotation, sampling, and production communication.</p>
<h2>Limitation</h2>
<p>SealBridge Supply is a sourcing and coordination brand. Final purchase terms, inspection criteria, packaging, shipping, and compliance obligations should be confirmed in written project documents.</p>',
    ],
    [
        'post_title' => 'Contact / Request a Quote',
        'post_name' => 'contact',
        'post_type' => 'page',
        'post_content' => '<div class="intro-band"><p>Send the drawing, material, size, quantity, application, and certificate requirements. Clear project details help reduce back-and-forth and improve quotation accuracy.</p></div>
<h2>Quote Information Checklist</h2>
<ul class="feature-list"><li>Drawing or photo</li><li>Material target</li><li>Thickness or profile size</li><li>Hardness or density</li><li>Adhesive backing requirement</li><li>Order quantity and sample quantity</li><li>Application environment</li><li>RoHS, REACH, UL94, TDS, or SDS needs</li></ul>
<h2>Request Form Mockup</h2>
<form class="quote-form"><label>Name<input type="text" name="name"></label><label>Email<input type="email" name="email"></label><label>WhatsApp<input type="tel" name="whatsapp" placeholder="+86 187 7021 4461"></label><label>Product Type<select name="product_type"><option>Electrical Enclosure Gasket</option><option>Control Cabinet Sealing Strip</option><option>EPDM Foam Gasket</option><option>Silicone Gasket</option><option>Adhesive Backed Die Cut Gasket</option><option>Custom Rubber Gasket</option></select></label><label>Project Details<textarea name="message" placeholder="Material, size, quantity, application, documents needed..."></textarea></label><button class="button" type="button">Prepare Quote Details</button></form>
<h2>Fast Contact Channels</h2>
<div class="contact-channel-grid"><a href="mailto:support@sealbridgesupply.com"><strong>Email</strong><span>support@sealbridgesupply.com</span></a><a href="https://wa.me/8618770214461" target="_blank" rel="noopener"><strong>WhatsApp</strong><span>+86 187 7021 4461</span></a></div>',
    ],
    [
        'post_title' => 'Articles',
        'post_name' => 'articles',
        'post_type' => 'page',
        'post_content' => '<div class="intro-band"><p>Industry notes, product selection guides, compliance explanations, and sourcing articles for enclosure gasket buyers.</p></div>',
    ],
];

$product_pages = [
    ['Electrical Enclosure Gaskets', 'electrical-enclosure-gaskets', 'Flat, foam, or molded gaskets for electrical enclosures, junction boxes, outdoor covers, and equipment housings.', 'Use this category when the buyer needs a gasket for an electrical box, enclosure cover, access panel, outdoor housing, or IP-rating support project. Confirm drawing, gasket path, thickness, compression target, material, quantity, and document needs.'],
    ['Control Cabinet Sealing Strips', 'control-cabinet-sealing-strips', 'Extruded or foam sealing strips for control cabinets, distribution cabinets, server cabinets, and cabinet door edges.', 'Use this category for cabinet door seals, edge seals, and long strip profiles. Confirm profile drawing, door gap, material, hardness or density, length, corner treatment, and installation method.'],
    ['EPDM Foam Gaskets', 'epdm-foam-gaskets', 'Closed-cell EPDM sponge and foam gaskets for outdoor waterproof, dustproof, weather-resistant sealing.', 'Use this category when the project needs outdoor aging resistance, compression recovery, water resistance support, or foam sealing around covers and panels. Confirm density, thickness, adhesive requirement, and compression gap.'],
    ['Silicone Gaskets', 'silicone-gaskets', 'Silicone rubber and silicone foam gaskets for LED lighting housings, outdoor electronics, high/low temperature covers, and weather-exposed assemblies.', 'Use this category when softness, UV exposure, temperature range, or silicone material is important. Confirm solid or foam silicone, hardness, color, temperature range, food/flame requirements, and adhesive surface treatment.'],
    ['Adhesive Backed Die Cut Gaskets', 'adhesive-backed-die-cut-gaskets', 'Die-cut foam or rubber gaskets with adhesive backing for quick peel-and-stick assembly on boxes, covers, and panels.', 'Use this category when the buyer wants release-liner-backed parts for faster assembly. Confirm material, thickness, adhesive brand or grade, liner type, tolerance, packing format, and surface condition.'],
    ['Custom Rubber Gaskets', 'custom-rubber-gaskets', 'Drawing-based custom rubber gaskets and seals for non-standard sizes, samples, molded parts, and custom projects.', 'Use this category for customer drawings, samples, custom dimensions, molded gaskets, cut gaskets, or special rubber seals. Confirm material, hardness, tolerance, tooling, sample plan, bulk quantity, and compliance documents.'],
];

$product_seo_sections = [
    'electrical-enclosure-gaskets' => '<h2>Electrical Enclosure Gasket Applications</h2><p>Electrical enclosure gaskets are used around enclosure covers, access doors, junction boxes, outdoor electrical boxes, equipment housings, and control panels where dust and water sealing support is required. Common project goals include stable compression, outdoor aging resistance, cleaner installation, and material documents for export customers.</p><h2>Common Material Direction</h2><p>EPDM foam is often selected for outdoor electrical enclosure gasket projects because it supports weather, ozone, aging, and water resistance. Silicone foam or silicone rubber may be considered when the enclosure faces wider temperature exposure, softer sealing requirements, or LED/electronics-related heat conditions.</p><h2>Quote Details for Enclosure Gaskets</h2><p>For a useful quote, send the enclosure drawing, gasket path, cover size, thickness, compression target, material preference, adhesive requirement, order quantity, and document needs such as RoHS, REACH, UL94 material information, TDS, or SDS.</p>',
    'control-cabinet-sealing-strips' => '<h2>Electrical Panel Door Gasket Applications</h2><p>Control cabinet sealing strips—also searched as <strong>electrical panel door gaskets</strong>, electrical cabinet door seals, or gasketing for control cabinets—are used for distribution cabinets, electrical panels, server cabinets, telecom cabinets, and industrial equipment doors. The seal helps close an uneven door gap and supports the complete enclosure against dust and water ingress when the cabinet, gasket path, latches, corners, and compression are designed and tested together.</p><h2>Profile and Material Options</h2><p>Typical cabinet door seal profiles include bulb seals, D-shape strips, P-shape strips, U-channel edge seals, self-grip profiles, and custom extruded rubber profiles. EPDM rubber is a common starting point for weather-exposed NEMA enclosure sealing, while silicone rubber may be selected for softer sealing or broader temperature conditions. A gasket is not independently NEMA 3R, NEMA 4, or NEMA 4X rated; those ratings apply to the completed enclosure.</p><h2>Door Gap and Compression</h2><p>Choose the profile from the measured minimum and maximum door gap rather than from appearance alone. The compressed height must accommodate cabinet tolerances without creating excessive closing force. Corner joining, hinge-side compression, latch spacing, groove dimensions, and repeated door opening can all change the result. Review our <a href="/nema-3r-4-4x-enclosure-gasket-requirements/">NEMA enclosure gasket requirements guide</a> before setting the sealing target.</p><h2>Quote Details for Cabinet Seals</h2><p>Useful inputs include a profile drawing, sample photo, cabinet door gap range, gasket path dimensions, installation method, material, hardness or sponge density, color, roll length or cut length, corner joining requirement, packing method, annual quantity, and requested material documents. For sponge profiles, also review the <a href="/closed-cell-epdm-sponge-density-compression-guide/">closed-cell EPDM density and compression guide</a>.</p>',
    'epdm-foam-gaskets' => '<h2>EPDM Foam Gasket Applications</h2><p>EPDM foam gaskets and EPDM sponge gaskets are used for outdoor waterproof and dustproof sealing around enclosure doors, access panels, junction boxes, solar inverter enclosures, EV charger cabinets, battery cabinets, HVAC equipment, and other weather-exposed housings.</p><h2>Closed-cell Foam Selection</h2><p>Closed-cell EPDM foam is often preferred when the project needs compression recovery, low water absorption, weather resistance, and adhesive-backed assembly. Thickness, density, compression force, adhesive type, and liner format should be confirmed before sampling.</p><h2>Quote Details for EPDM Foam Gaskets</h2><p>Send the drawing or dimensions, target thickness, density or compression requirement, adhesive need, working temperature, outdoor exposure condition, quantity, and whether the part should be supplied as strips, die-cut frames, kiss-cut sheets, or roll material.</p>',
    'silicone-gaskets' => '<h2>Silicone Gasket Applications</h2><p>Silicone gaskets and silicone rubber gaskets are commonly used for LED lighting housings, outdoor electronics, battery covers, equipment housings, and temperature-sensitive enclosure projects. Buyers often search for silicone gaskets when they need softer compression, better UV stability, or a wider temperature range than a standard foam gasket can provide.</p><h2>Solid Silicone and Silicone Foam</h2><p>Solid silicone can be used for molded or die-cut gasket parts where stable elasticity and temperature resistance are required. Silicone foam can be useful for softer compression sealing around covers, lenses, and electronic housings. Color, hardness, surface finish, adhesive treatment, and flame or food-grade requirements should be confirmed before quotation.</p><h2>When Silicone Wins Over EPDM</h2><p>Silicone is often selected when the project faces higher heat, lower temperature, stronger UV exposure, or a softer closing force. EPDM may still be a better economic choice for many outdoor enclosure projects, so the right material should be chosen from the enclosure design, not from search volume alone.</p><h2>Silicone Gasket Quote Checklist</h2><ul class="feature-list"><li>2D or 3D drawing, sketch, or sample photo</li><li>Solid silicone or silicone foam preference</li><li>Hardness, color, and surface finish</li><li>Temperature exposure and UV condition</li><li>Adhesive requirement and tolerance</li><li>Quantity and document needs</li></ul><h2>Quote Details for Silicone Gaskets</h2><p>For quotation, provide a 2D or 3D drawing, solid or foam silicone preference, hardness, color, temperature exposure, UV condition, adhesive requirement, tolerance, quantity, and any compliance documents requested by the buyer.</p>',
    'adhesive-backed-die-cut-gaskets' => '<h2>Adhesive Backed Die Cut Gasket Applications</h2><p>Adhesive backed die cut gaskets, self-adhesive gaskets, and peel-and-stick foam gaskets are used when assembly speed and clean placement matter. They are common for junction boxes, access covers, electronic housings, enclosure panels, LED housings, and small gasket parts that need release liner support.</p><h2>Die Cutting and Adhesive Options</h2><p>Common material options include EPDM foam, EVA foam, PE foam, silicone foam, rubber sheet, and adhesive-backed gasket materials. The adhesive brand or grade, release liner, surface condition, tolerance, and packing format affect both production and assembly performance.</p><h2>Quote Details for Die Cut Gaskets</h2><p>Useful files include DXF, PDF, or CAD drawings, material and thickness target, adhesive requirement, liner type, tolerance, assembly surface, sheet or roll packing preference, sample quantity, and bulk quantity.</p>',
    'custom-rubber-gaskets' => '<h2>Custom Rubber Gasket Applications</h2><p>Custom rubber gaskets and custom rubber seals according to drawing are used when standard gasket sizes or profiles cannot match the project. They may be molded, die-cut, extruded, bonded, or assembled based on the drawing, sample, material, tolerance, and quantity.</p><h2>Drawing-based Rubber Seal Options</h2><p>Common materials include EPDM, NBR, silicone, neoprene / CR, and custom compounds. EPDM is often used for outdoor sealing, NBR for oil-resistant applications, silicone for temperature-sensitive sealing, and CR for balanced industrial performance. Tooling may be required for molded or special-profile parts.</p><h2>Quote Details for Custom Rubber Gaskets</h2><p>Send the drawing or physical sample, material target, hardness, dimensions, tolerance, surface finish, application environment, sample plan, bulk quantity, inspection criteria, packaging requirement, and document needs.</p>',
];

$applications = [
    ['Outdoor Electrical Enclosures', 'outdoor-electrical-enclosures', 'Gasket selection guide for outdoor electrical boxes, equipment enclosures, and weather-exposed cabinet assemblies.'],
    ['Control Cabinets', 'control-cabinets', 'Sealing strip and gasket guide for industrial control cabinet doors, edges, and repeated access panels.'],
    ['Junction Box Gaskets', 'junction-boxes', 'Custom junction box gasket guide for die-cut EPDM foam, adhesive-backed seals, cover compression, and drawing-based quotation.'],
    ['EV Charger Enclosure Gaskets', 'ev-charger-cabinets', 'Outdoor gasket selection guide for EV charger enclosure doors, access panels, display housings, and electrical compartments.'],
    ['Solar Inverter Enclosures', 'solar-inverter-enclosures', 'EPDM and silicone gasket guide for solar inverter enclosures exposed to UV, heat, and outdoor humidity.'],
    ['LED Lighting Housings', 'led-lighting-housings', 'Silicone and foam gasket guide for LED lighting covers, lenses, housings, and outdoor fixtures.'],
];

$posts = [
    ['EPDM vs Silicone for Outdoor Enclosure Gaskets', 'epdm-vs-silicone-outdoor-enclosure-gaskets', 'EPDM is often a practical outdoor enclosure material because it offers strong weather, ozone, aging, and water resistance. Silicone is useful when broader temperature range, softness, or UV stability is more important. The better choice depends on compression, cost, exposure, and document requirements.'],
    ['What Information Is Needed for a Custom Die Cut Gasket Quote?', 'custom-die-cut-gasket-quote-information', 'A useful quote request should include drawing, material, thickness, adhesive requirement, tolerance, quantity, application, and compliance needs. Clear inputs help suppliers avoid assumptions and reduce quotation cycles.'],
    ['Can a Gasket Be IP65 or IP66 Certified?', 'can-a-gasket-be-ip65-or-ip66-certified', 'A gasket alone should not usually be described as IP65 or IP66 certified. The IP rating belongs to the final enclosure assembly under test conditions. Gasket material and compression design can support that target, but the enclosure must be verified as a system.'],
    ['How to Choose Electrical Enclosure Gaskets for Outdoor Boxes', 'choose-electrical-enclosure-gaskets-outdoor-boxes', 'Electrical enclosure gasket selection should start from the box structure, cover compression, outdoor exposure, material aging, adhesive requirement, and document needs. EPDM foam and silicone gasket materials are common options for outdoor enclosure sealing support.'],
    ['Control Cabinet Door Seal Profiles: What Buyers Should Confirm', 'control-cabinet-door-seal-profiles', 'Control cabinet door seal projects usually need a profile drawing or sample, door gap, material, hardness, color, roll length, and corner joining requirement. These details help avoid quoting the wrong cabinet sealing strip profile.'],
    ['EPDM Foam Gasket with Adhesive: Key Quote Parameters', 'epdm-foam-gasket-with-adhesive-quote-parameters', 'EPDM foam gasket with adhesive is useful for outdoor panels, access covers, and enclosure doors. Buyers should confirm thickness, density, compression gap, adhesive grade, liner type, surface condition, and working environment.'],
    ['Silicone Gaskets for LED Lighting Housings and Outdoor Electronics', 'silicone-gaskets-led-lighting-outdoor-electronics', 'Silicone gaskets are useful for LED lighting housings, outdoor electronics, and covers exposed to heat, UV, or wider temperature ranges. Solid silicone and silicone foam should be compared based on compression and assembly design.'],
    ['Adhesive Backed Die Cut Gaskets for Fast Enclosure Assembly', 'adhesive-backed-die-cut-gaskets-fast-enclosure-assembly', 'Adhesive backed die cut gaskets help speed up assembly for junction boxes, electronic housings, enclosure covers, and panels. Material, adhesive, release liner, tolerance, and packing format should be confirmed before sampling.'],
    ['Custom Rubber Gaskets According to Drawing: From Sample to Quote', 'custom-rubber-gaskets-according-to-drawing-sample-quote', 'Custom rubber gaskets according to drawing require material, hardness, dimensions, tolerance, application environment, tooling condition, sample requirement, quantity, and inspection criteria before a useful quotation can be prepared.'],
];

foreach ($pages as $page) {
    sealbridge_upsert_post($page);
}

$merged_trust_pages = ['quality-compliance', 'supplier-network', 'certificate-templates'];
foreach ($merged_trust_pages as $merged_trust_page_slug) {
    $merged_trust_page = get_page_by_path($merged_trust_page_slug, OBJECT, 'page');
    if ($merged_trust_page) {
        wp_trash_post($merged_trust_page->ID);
    }
}


$main_product_slugs = array_column($product_pages, 1);

foreach ($product_pages as $product) {
    $product_content = '<div class="intro-band"><p>' . esc_html($product[2]) . '</p></div>'
        . '<h2>Product Positioning</h2><p>' . esc_html($product[3]) . '</p>'
        . ($product_seo_sections[$product[1]] ?? '')
        . '<h2>Common Quote Parameters</h2><ul class="feature-list"><li>Drawing or sample photo</li><li>Material and color</li><li>Thickness, size, or profile drawing</li><li>Hardness or density</li><li>Adhesive requirement</li><li>Quantity and compliance documents</li></ul>';

    $product_id = sealbridge_upsert_post([
        'post_title' => $product[0],
        'post_name' => $product[1],
        'post_type' => 'product',
        'post_excerpt' => $product[2],
        'post_content' => $product_content,
    ]);

    if (!is_wp_error($product_id)) {
        update_post_meta($product_id, '_sealbridge_product_filters', $product[1]);
        delete_post_meta($product_id, '_sealbridge_external_image');
        delete_post_meta($product_id, '_sealbridge_product_video');
        delete_post_meta($product_id, '_sealbridge_product_price');
        delete_post_meta($product_id, '_sealbridge_product_moq');
    }
}

$all_products = get_posts([
    'post_type' => 'product',
    'post_status' => ['publish', 'draft', 'pending', 'private'],
    'posts_per_page' => -1,
]);

foreach ($all_products as $existing_product) {
    if (!in_array($existing_product->post_name, $main_product_slugs, true)) {
        wp_trash_post($existing_product->ID);
    }
}


foreach ($applications as $application) {
    sealbridge_upsert_post([
        'post_title' => $application[0],
        'post_name' => $application[1],
        'post_type' => 'application',
        'post_excerpt' => $application[2],
        'post_content' => '<div class="intro-band"><p>' . esc_html($application[2]) . '</p></div><h2>Typical Gasket Requirements</h2><ul class="feature-list"><li>Outdoor aging resistance</li><li>Compression recovery</li><li>Dust and water sealing support</li><li>Suitable adhesive or mechanical installation</li><li>Stable supply and sampling</li><li>Material documents when available</li></ul>',
    ]);
}

foreach ($posts as $post) {
    $content = '<p>' . esc_html($post[2]) . '</p><h2>Buyer Takeaway</h2><p>Confirm the actual application, material data, assembly method, and document requirements before locking the specification.</p>';
    sealbridge_upsert_post([
        'post_title' => $post[0],
        'post_name' => $post[1],
        'post_type' => 'post',
        'post_excerpt' => wp_trim_words($post[2], 24),
        'post_content' => $content,
    ]);
}

$default_post = get_page_by_path('hello-world', OBJECT, 'post');
if ($default_post) {
    wp_update_post([
        'ID' => $default_post->ID,
        'post_title' => 'How to Read a Gasket Material TDS',
        'post_name' => 'how-to-read-gasket-material-tds',
        'post_excerpt' => 'A technical data sheet helps buyers compare hardness, density, tensile strength, temperature range, and compression properties before selecting a gasket material.',
        'post_content' => '<p>A technical data sheet is useful only when the buyer checks whether the material grade, test method, thickness condition, and supplier source match the actual gasket project.</p><h2>Buyer Takeaway</h2><p>Use TDS files for material comparison, then confirm sample performance under the real enclosure design and compression condition.</p>',
        'post_status' => 'publish',
    ]);
}

$menu_name = 'Primary Navigation';
$menu = wp_get_nav_menu_object($menu_name);

if (!$menu) {
    $menu_id = wp_create_nav_menu($menu_name);
} else {
    $menu_id = (int) $menu->term_id;
}

foreach (wp_get_nav_menu_items($menu_id) ?: [] as $item) {
    wp_delete_post($item->ID, true);
}

$nav_items = [
    ['Home', home_url('/')],
    ['Products', home_url('/products/')],
    ['Applications', home_url('/applications/')],
    ['Articles', home_url('/articles/')],
    ['Materials', home_url('/materials/')],
    ['Capabilities', home_url('/capabilities/')],
    ['Factory Screening', home_url('/factory-screening/')],
    ['About', home_url('/about/')],
    ['Contact', home_url('/contact/')],
];

foreach ($nav_items as $item) {
    wp_update_nav_menu_item($menu_id, 0, [
        'menu-item-title' => $item[0],
        'menu-item-url' => $item[1],
        'menu-item-status' => 'publish',
    ]);
}

$locations = get_theme_mod('nav_menu_locations', []);
$locations['primary'] = $menu_id;
set_theme_mod('nav_menu_locations', $locations);

update_option('show_on_front', 'posts');
update_option('permalink_structure', '/%postname%/');
flush_rewrite_rules();

echo "Seeded SealBridge secondary pages, products, applications, posts, and navigation.\n";
