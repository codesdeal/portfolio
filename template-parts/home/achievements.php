<?php 
/**
 * Template part for displaying Achievements Section
 * 
 * 
 * @package _themename
 * 
 **/

?>
<section id="achievements" class="achievements-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="title-sec" data-aos="fade-up">
                    <h2 class="section-title">
                        <?php esc_html_e("Achievements on Upwork", '_themename'); ?>
                    </h2>                          
                </div>
                <div class="counter-wrapper">
                    <?php 
                        $num_achievements = get_theme_mod('num_achievements', 4); 
                        for ($i = 1; $i <= $num_achievements; $i++) : ?>
                        <div class="<?php echo "counter counter-$i"; ?>" data-aos="fade-up" data-aos-delay="<?php echo $i * 100; ?>">
                            <div class="<?php echo "conter-number conter-number-$i"; ?>">
                                <?php echo esc_html(get_theme_mod("_themename_counter_number_$i", '')); ?>
                            </div>
                            <div class="<?php echo "conter-title conter-title-$i"; ?>">
                                <?php echo esc_html(get_theme_mod("_themename_counter_title_$i", '')); ?>
                            </div>
                            <div class="<?php echo "conter-description conter-description-$i"; ?>">
                                <?php echo wp_kses_post(get_theme_mod("_themename_counter_description_$i", '')); ?>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>
</section>