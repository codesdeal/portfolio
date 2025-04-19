<?php

/**
 * Template Name: Contact Page
 *
 * The template for displaying the Contact info.
 *
 * @package devabu
 */

get_header();

?>

<section id="dine-with-us" class="top-section-gap">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-6 contact-txt" data-aos="fade-up">
                <h1>Questions or Comments?</h1>
                <h3>Send Us A Message</h3>
            </div>
            <div class="col-md-6">
                <div class="contact-frm">
                    <?php echo do_shortcode( '[contact-form-7 id="889f8ae" title="Contact"]' ); ?>
                </div>
            </div>
        </div>
    </div>
</section>


<?php get_footer(); ?>