/**
 * @author     Dennis Rogers <dennis@drogers.net>
 * @address    www.drogers.net
 * @date       2/3/15
 */

jQuery(document).ready(function(){
    
    jQuery("#main-carousel .carousel-inner .item").each(function(){
       jQuery(this).css('background-image', 'url(' + jQuery(this).find('img').attr('src') + ')');
    });
    
    jQuery(window).resize(function(){resizeImages()});
    
    resizeImages();
});

function resizeImages() {
    jQuery('.carousel-inner .item').css('height', jQuery(window).height() - 40);
}