/**
 * 
 * @since v.0.4.0
 */

//Code based on https://code.tutsplus.com/articles/integrating-with-wordpress-ui-admin-pointers--wp-26853

jQuery(document).ready(function ($) {
    wp_ccsearch_open_pointer(0);
    function wp_ccsearch_open_pointer(i) {
        pointer = wp_ccsearch_Pointer.pointers[i];
        options = $.extend(pointer.options, {
            close: function () {
                $.post(ajaxurl, {
                    pointer: pointer.pointer_id,
                    action: 'dismiss-wp-pointer'
                });
            }
        });

        $(pointer.target).pointer(options).pointer('open');

    }

//Close tooltip on button click
    $('body').on('click touch', '.wpcc_btn', function () {
          pointer = wp_ccsearch_Pointer.pointers[0];
          $(pointer.target).pointer(options).pointer('close');
    });
    
    
});

