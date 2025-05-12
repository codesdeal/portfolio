        </div>

        <?php 
            $footer_bg = _themename_sanitize_footer_bg(get_theme_mod( '_themename_footer_bg', 'dark' ));
        ?>        
        <footer id="footer" role="contentinfo">
            <div class="c-footer c-footer--<?php echo $footer_bg; ?>">
                <?php get_template_part('template-parts/footer/top-info'); ?>
            </div>

                <?php get_template_part('template-parts/footer/widgets'); ?>
                <?php get_template_part('template-parts/footer/info'); ?>
        </footer>
        <!-- ###footer section -->
        <?php wp_footer(); ?>
    </body>
</html>