jQuery(document).ready(function(){
    var direction = jQuery('#os_custom_box_for_rtl select').val(); //catch the direction value
    if(direction == 'rtl') {
        jQuery('#titlewrap').css('direction', 'RTL');
    }
    // start the script
    jQuery('#os_custom_box_for_rtl select').on('change', function(){
        var direction = jQuery(this).val();
        if(direction == 'rtl') {
            jQuery('#titlewrap').css('direction', 'RTL');
            // change the direction for the content in the textarea to RTL
            tinyMCE.activeEditor.dom.addClass(tinyMCE.activeEditor.dom.select('#tinymce'), 'osRTldirection');
        }else{
            jQuery('#titlewrap').css('direction', 'LTR');
            tinyMCE.activeEditor.dom.addClass(tinyMCE.activeEditor.dom.select('#tinymce'), 'osLTRdirection');
        }

    });
});