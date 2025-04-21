<article <?php post_class('c-post u-margin-bottom-20 u-margin-top-60'); ?> >
    <div class="c-post__inner">
        <?php if ( has_post_thumbnail() ) : ?>
            <div class="c-post__thumbnail">
                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                    <?php the_post_thumbnail( 'large' ); ?>
                </a>
            </div>
        <?php endif; ?>

        <header class="c-post__header">
            <?php if(is_single()){?>
                <h1 class="c-post__single-title">
                    <?php the_title(); ?>
                </h1>
            <?php  } else { ?>
                <h2 class="c-post__title">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
                </h2>
            <?php } ?>
            <div class="c-post__meta">
                <?php _themename_post_meta(); ?>
            </div>
        </header>

        <?php if(is_single()){?>
            <div class="c-post__content">
                <?php 
                    the_content();
                    wp_link_pages();                
                ?>
            </div>
        <?php  } else { ?>
            <div class="c-post__excerpt">
                <?php the_excerpt(); ?>
            </div>
        <?php } ?>

        <?php if(is_single()){?>

        <footer class="c-post__footer">
            <?php 
             if(has_category()){
                echo '<div class="c-post__cats">';
                $cats_list = get_the_category_list( esc_html__(',', '_themename') );
                printf(
                    wp_kses(
                        __('Posted in: %s', '_themename'),
                        [
                            'span' => [
                                'class' => []
                            ]
                        ]
                    ),
                    $cats_list
                );
                echo '</div>';
             }
                if(has_tag()){
                    echo '<div class="c-post__tags">';
                    $tags_list = get_the_tag_list('<ul><li>', '</li><li>', '</li></ul>');
                    echo $tags_list;
                    echo '</div>';
                }
            ?>
        </footer>
        <?php } ?>


        <?php if( !is_single()) { _themename_readmore_link();} ?>   
    </div>
</article>