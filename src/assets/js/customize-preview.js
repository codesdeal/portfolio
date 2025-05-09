import $ from 'jquery';
import strip_tags from './helpers/strip-tags.js'

 wp.customize( 'blogname', (value) => {
    value.bind((to) => {
        $('.c-header__blogname').html(to);
    } )
})

 wp.customize( '_themename_site_info', (value) => {
    value.bind( (to) => {
        $('.c-site-info__text').html(strip_tags(to, '<a>'));
    } )
})

wp.customize( '_themename_display_author_info', (value) => {
    value.bind((to) => {
        if(to){
            $('.c-post-author').show();
        } else {
            $('.c-post-author').hide();
        }
    } )
})