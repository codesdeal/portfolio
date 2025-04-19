        </div>

        <?php 
            $footer_bg = devabu_sanitize_footer_bg(get_theme_mod( 'devabu_footer_bg', 'dark' ));
        ?>        
        <footer id="footer" role="contentinfo">
            <div class="c-footer c-footer--<?php echo $footer_bg; ?>">
                <?php get_template_part('template-parts/footer/top-info'); ?>
            </div>

                <?php get_template_part('template-parts/footer/widgets'); ?>
                <?php get_template_part('template-parts/footer/info'); ?>
        </footer>

        <?php wp_footer(); ?>
    </body>
</html>