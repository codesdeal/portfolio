<?php
/*
* Template Name: Privacy Policy Page
*/

get_header();

?>

<section id="blog-details-content-section" class="section-margin-bottom">
    <div class="container">
        <div class="row">
            <!-- Blog Post Content -->
            <div class="col-md-8 order-2">
                <article class="blog blog-details-margin-bottom">
                    <h1 class="custom-text-color"><?php the_title(); ?></h1>
                    <b>Effective Date:</b> <?php echo get_the_date(); ?> <br>
                    <b>Last Updated:</b> <?php echo get_the_modified_date(); ?><br><br>
                    <div class="post-content">
                        <?php the_content(); ?>
                    </div>
                </article>
            </div>

            <!-- Sidebar -->
            <aside class="col-md-4 position-relative order-1">
                <div class="side-bar">
                    <?php if (function_exists('fdw_get_table_of_contents')): ?>
                        <h5 class="title-margin-bottom-2"><?php _e('Table of Contents', 'fusiondigiweb'); ?></h5>
                        <?php echo fdw_get_table_of_contents(); ?>
                    <?php endif; ?>
                </div>
            </aside>
        </div>
    </div>
</section>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const links = document.querySelectorAll('.custom-list-style-none a');
        links.forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href.startsWith('#')) {
                    e.preventDefault();
                    const targetElement = document.getElementById(href.substring(1));
                    if (targetElement) {
                        targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }
            });
        });
    });
</script>

<?php
get_footer();