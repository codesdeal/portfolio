<?php
/**
 * Template part for displaying notification toggle button
 */

if (get_theme_mod('_themename_enable_push_notifications', true)) : ?>
    <div class="c-notification-toggle">
        <button 
            class="c-button c-button--icon js-notification-toggle" 
            aria-label="<?php esc_attr_e('Toggle notifications', '_themename'); ?>"
            data-prompt="<?php echo esc_attr(get_theme_mod('_themename_notification_prompt')); ?>"
        >
            <span class="c-notification-toggle__icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                    <path fill="none" d="M0 0h24v24H0z"/>
                    <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2zm-2 1H8v-6c0-2.48 1.51-4.5 4-4.5s4 2.02 4 4.5v6z"/>
                </svg>
            </span>
            <span class="c-notification-toggle__label screen-reader-text">
                <?php esc_html_e('Enable Notifications', '_themename'); ?>
            </span>
        </button>
    </div>
<?php endif; ?>