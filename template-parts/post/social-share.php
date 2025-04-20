<?php
/**
 * Social sharing buttons template part
 */

// Get current page URL
$current_url = urlencode(get_permalink());
// Get current page title
$current_title = urlencode(get_the_title());
// Get post thumbnail for social media
$current_image = has_post_thumbnail() ? urlencode(get_the_post_thumbnail_url(null, 'large')) : '';

$share_links = array(
    'facebook' => array(
        'url' => "https://www.facebook.com/sharer/sharer.php?u={$current_url}",
        'icon' => 'fab fa-facebook',
        'label' => __('Share on Facebook', '_themename')
    ),
    'twitter' => array(
        'url' => "https://twitter.com/intent/tweet?url={$current_url}&text={$current_title}",
        'icon' => 'fab fa-twitter',
        'label' => __('Share on Twitter', '_themename')
    ),
    'linkedin' => array(
        'url' => "https://www.linkedin.com/shareArticle?mini=true&url={$current_url}&title={$current_title}",
        'icon' => 'fab fa-linkedin',
        'label' => __('Share on LinkedIn', '_themename')
    ),
    'pinterest' => array(
        'url' => "https://pinterest.com/pin/create/button/?url={$current_url}&media={$current_image}&description={$current_title}",
        'icon' => 'fab fa-pinterest',
        'label' => __('Pin on Pinterest', '_themename')
    )
);
?>

<div class="c-social-share" aria-label="<?php esc_attr_e('Share this post', '_themename'); ?>">
    <h3 class="c-social-share__title"><?php esc_html_e('Share this post:', '_themename'); ?></h3>
    <ul class="c-social-share__list">
        <?php foreach ($share_links as $network => $data) : ?>
            <li class="c-social-share__item">
                <a href="<?php echo esc_url($data['url']); ?>" 
                   class="c-social-share__link c-social-share__link--<?php echo esc_attr($network); ?>"
                   target="_blank"
                   rel="noopener noreferrer"
                   aria-label="<?php echo esc_attr($data['label']); ?>">
                    <i class="<?php echo esc_attr($data['icon']); ?>" aria-hidden="true"></i>
                    <span class="screen-reader-text"><?php echo esc_html($data['label']); ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>