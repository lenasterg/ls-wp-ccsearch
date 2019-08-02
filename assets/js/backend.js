var lswpcc_imgs = {};
var lswpcc_selected = new Array();
var lswpcc_opened = false;
var lswpcc_current = '';
var lswpcc_width_small = '630px';
var lswpcc_width_big = '930px';
var lswpcc_height = '700px';

jQuery(document).ready(function ($) {
    let dropdown = $('#lswpcc_provider');
    dropdown.empty();
    dropdown.append('<option value="">'+lswpcc_vars.lswpcc_allproviders+'</option>');
    dropdown.prop('selectedIndex', 0);

    const url = 'https://api.creativecommons.engineering/statistics/image?format=json';

// Populate dropdown with list of providers, since v0.3.0
    $.getJSON(url, function (data) {
        data.sort(SortByDisplay);
        $.each(data, function (key, entry) {
            dropdown.append($('<option></option>').attr('value', entry.provider_name).text(entry.display_name));
        })
    });

    $('.lswpcc_loading_text').hide();

    $('#lswpcc_input').keypress(function (event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            lswpcc_search(1);
        }
        //Stop the event from propogation to other handlers
        //If this line will be removed, then keypress event handler attached
        //at document level will also be triggered
        event.stopPropagation();
    });

    $('body').on('click touch', '#lswpcc_search', function () {
        lswpcc_search(1);
    });

    $('body').on('click touch', '.lswpcc_btn', function () {
        var editor_id = $(this).attr('data-editor');
        $('#lswpcc_editor_id').val(editor_id);

        if (lswpcc_opened) {
            $.colorbox({
                width: lswpcc_width_big,
                height: lswpcc_height,
                inline: true,
                href: "#lswpcc_area",
                scrolling: false,
                fixed: true
            });
        } else {
            $.colorbox({
                width: lswpcc_width_small,
				initialHeight: lswpcc_height,						   
                height: lswpcc_height,
                inline: true,
                href: "#lswpcc_area",
                scrolling: false,
                fixed: true
            });
        }
    });

    $('body').on('change', '#lswpcc_page_select', function () {
        lswpcc_search($(this).val());
    });

    $('body').on('click touch', '#lswpcc_insert', function () {
        for (var i = 0; i < lswpcc_selected.length; i++) {
            var insert = '';
            var align = '';
            var insertsource = '';
            var align_class = '';
            var editor_id = $('#lswpcc_editor_id').val();
           
		   align = ' align="' + lswpcc_escape_html($('#lswpcc_align').val()) + '"';
           align_class = ' class="lswpcc_image ' + lswpcc_escape_html($('#lswpcc_align').val()) + '"';
           
            var sid = lswpcc_selected[i];
            if (lswpcc_imgs[sid].img_caption != '') {
                insert = '[caption id="" ' + align + ']';
            }

            if ($('#lswpcc_use').val() == 'thumbnail') {
                insertsource += lswpcc_imgs[sid].img_thumb;
            }
            if ($('#lswpcc_use').val() == 'full') {
                insertsource += lswpcc_imgs[sid].img_full;
            }


            if ($('#lswpcc_link').val() == 1) {
                insert += '<a href="' + lswpcc_escape_html(lswpcc_imgs[sid].img_site) + '" title="' + lswpcc_escape_html(lswpcc_imgs[sid].img_title) + '"';
            }
            if ($('#lswpcc_link').val() == 2) {
                insert += '<a href="' + lswpcc_escape_html(lswpcc_imgs[sid].img_full) + '" title="' + lswpcc_escape_html(lswpcc_imgs[sid].img_title) + '"';
            }
            if (($('#lswpcc_link').val() != 0) && $('#lswpcc_blank').is(':checked')) {
                insert += ' target="_blank"';
            }
            if (($('#lswpcc_link').val() != 0) && $('#lswpcc_nofollow').is(':checked')) {
                insert += ' rel="nofollow"';
            }
            if ($('#lswpcc_link').val() != 0) {
                insert += '>';
            }

            insert += '<img ' + align_class + ' style="max-width: 100%;" src="' + lswpcc_escape_html(insertsource) + '" title="' + lswpcc_escape_html(lswpcc_imgs[sid].img_title) + '" alt="' + lswpcc_escape_html(lswpcc_imgs[sid].img_title) + '"/>';
            if ($('#lswpcc_link').val() != 0) {
                insert += '</a>';
            }
            if (lswpcc_imgs[sid].img_caption != '') {
                // insert += ' ' + lswpcc_escape_html(lswpcc_imgs[sid].img_caption) + '[/caption]';
                insert += ' ' + lswpcc_imgs[sid].img_caption + '[/caption]';
            }
            insert += '\n';

            var win = window.dialogArguments || opener || parent || top;
            win.send_to_editor(insert);
        }
        $.colorbox.close();
    });

    $('body').on('click touch', '#lswpcc_featured', function () {
        var url = $('#lswpcc_url').val();
        if ($('#lswpcc_use').val() == 'thumbnail') {
            url = $('#lswpcc_urlthumb').val();
        }

        var title = $('#lswpcc_title').val();
        var caption = $('#lswpcc_caption').val();

        $('.lswpcc_featured_url').val(url);
        $('.lswpcc_featured_title').val(title);
        $('.lswpcc_featured_caption').val(caption);
        $('#postimagediv div.inside img').remove();

        $('#postimagediv div.inside').prepend('<img src="' + url + '" width="270"/>');

        $.colorbox.close();
    });

    $('body').on('click touch', '#remove-post-thumbnail', function () {
        $('.lswpcc_featured_url').val('');
    });

    $('body').on('click touch', '.lswpcc_item_overlay', function (event) {
        var checkbox = $(this).parent().find(':checkbox');
        var checkbox_id = $(this).attr('rel');

        $.colorbox.resize({width: lswpcc_width_big, height: lswpcc_height});
        lswpcc_opened = true;
        lswpcc_current = checkbox_id;

        if (event.ctrlKey) {

            if (!checkbox.is(':checked')) {
                lswpcc_selected.push(checkbox_id);
            } else {
                lswpcc_selected.splice(lswpcc_selected.indexOf(checkbox_id), 1);
            }

            checkbox.attr('checked', !checkbox.is(':checked'));
        } else {
            if (!checkbox.is(':checked')) {
                lswpcc_selected = [checkbox_id];
                $('#lswpcc_area').find('input:checkbox').removeAttr('checked');
                checkbox.attr('checked', !checkbox.is(':checked'));
            }
        }
        $('#lswpcc_title').val(lswpcc_imgs[checkbox_id].img_title);
        $('#lswpcc_caption').val(lswpcc_imgs[checkbox_id].img_caption);
        $('#lswpcc_caption_display').html(lswpcc_imgs[checkbox_id].img_caption);
        $('#lswpcc_site').val(lswpcc_imgs[checkbox_id].img_site);

        $('#lswpcc_url').val(lswpcc_imgs[checkbox_id].img_full);
        $('#lswpcc_urlthumb').val(lswpcc_imgs[checkbox_id].img_thumb);
        $('#lswpcc_view').html('<img src="' + lswpcc_imgs[checkbox_id].img_thumb + '"/>');
        $('#lswpcc_error').html('');

        $('#lswpcc_sourcelink').html(lswpcc_imgs[checkbox_id].img_sourcelink);

        $('#lswpcc_insert span').html('(' + lswpcc_selected.length + ')');
        $('#lswpcc_save span').html('(' + lswpcc_selected.length + ')');
        $('#lswpcc_save_only span').html(' (' + lswpcc_selected.length + ')');
    });

});


function lswpcc_search(page) {
    jQuery('#lswpcc_search').addClass('loading');
    jQuery('#lswpcc_container').html('');
    jQuery('#lswpcc_page').html('');
    var data = {
        action: 'lswpcc_search',
        key: jQuery('#lswpcc_input').val(),
        provider: jQuery('#lswpcc_provider').val(),
//        page: page,
        page: page,
        lswpcc_nonce: lswpcc_vars.lswpcc_nonce
    };
    
    jQuery.ajax({
        method: 'POST',
        url: lswpcc_vars.lswpcc_ajax_url,
        data: data,
        success: function (response) {
            lswpcc_show_images(JSON.parse(response), page);
            console.log(data);
        },
        error: function () {
            console.log('error');
        },
    });
}

/**
 * 
 * @param {type} data
 * @param {type} page
 * @version 3.0, lenasterg
 */
function lswpcc_show_images(data, page) {
    jQuery('#lswpcc_search').removeClass('loading');
    if (data.results != 'undefined') {
        for (var i = 0; i < data.results.length; i++) {
            var img_id = '';
            var img_title = '';
            if (data.results[i].id != undefined) {
                img_id = data.results[i].id;
            } else {
                img_id = data.results[i].id;
            }
            var img_ext = data.results[i].url.split('.').pop().toUpperCase().substring(0, 4);
            var img_site = data.results[i].foreign_landing_url;
            var img_source = data.results[i].source;

            var img_full = data.results[i].url;
            if (typeof data.results[i].thumbnail != 'undefined') {
                var img_thumb = data.results[i].thumbnail;
                img_thumb = img_thumb.replace('https://api.creativecommons.engineering/t/600/', '');
            } else {
                img_thumb = img_full;
            }
            if (typeof data.results[i].title != 'undefined') {
                img_title = String(data.results[i].title);
            } else {
                img_title = img_id;
            }

            var lswpcc_licenses = lswpcc_find_licences(data.results[i].license, data.results[i].license_version);
            var img_caption = ' <p style="font-size: 0.9rem;font-style: italic;"><a href="' + img_site + '">"' + img_title + '"</a> <span>' + lswpcc_vars.lswpcc_by_author + ' <a href="' + data.results[i].creator_url + '">' + data.results[i].creator + '</a></span> ' + lswpcc_vars.lswpcc_licensed_under + ' ' + lswpcc_licenses + '</p>';

            jQuery('#lswpcc_container').append('<div class="lswpcc_item" bg="' + img_thumb + '"><div class="lswpcc_item_overlay" rel="' + img_id + '"></div><div class="lswpcc_check"><input type="checkbox" value="' + img_id + '"/></div><span>' +
                    img_ext + ' | </span></div>'
                    );

            var img_sourcelink = '<a href="' + img_site + '" target="_blank">' + img_source + '</a>'


            lswpcc_imgs[img_id] = {
                img_ext: img_ext,
                img_site: img_site,
                img_thumb: img_thumb,
                img_full: img_full,
                img_title: img_title,
                img_caption: img_caption,
                img_sourcelink: img_sourcelink
            };
        }
        jQuery('.lswpcc_item').each(function () {
            var bg_url = jQuery(this).attr('bg');
            jQuery(this).css('background-image', 'url(' + bg_url + ')');
        });
    }
    if (data.result_count != 'undefined') {
        var pages = lswpcc_vars.lswpcc_res_about + ' ' + data.result_count + ' ' + lswpcc_vars.lswpcc_res_pages + ': ';
        var per_page = 20;
        if (data.result_count / per_page > 1) {
            pages += '<select id="lswpcc_page_select" class="lswpcc_page_select">';
            for (var j = 1; j < data.result_count / per_page + 1; j++) {
                pages += '<option value="' + j + '"';
                if (j == page) {
                    pages += ' selected';
                }
                pages += '>' + j + '</option> ';
            }
            pages += '</select>';
        }
        jQuery('#lswpcc_page').html(pages);
    }
}

/** 
 * 
 * @param string license
 * @param string license_version
 * @returns string
 * @author lenasterg
 * @version 1.0
 */
function lswpcc_find_licences(license, license_version) {
    var license_img = '';
    var licenses = license.split('-');
    for (index = 0; index < licenses.length; ++index) {
        license_img += ' <img style="width: 20px;height: 20px;margin-right: 3px;display: inline-block;" src="https://ccsearch.creativecommons.org/static/img/cc-' + licenses[index] + '_icon.svg" />';
    }

    var cc_img = ' <img style="width: 20px; height: 20px;margin-right: 3px;display: inline-block;" src="https://ccsearch.creativecommons.org/static/img/cc_icon.svg" />';

    var img_licence_link = ' <a href="https://creativecommons.org/licenses/' + license + '/' + license_version + '?ref=ccsearch&atype=html"    target="_blank" rel="noopener noreferrer" style="display: inline-block;white-space: none;opacity: .7;margin-top: 2px;margin-left: 3px;height: 22px !important;"> CC ' + license + '-' + license_version + cc_img + license_img + '</a>';

    return img_licence_link;
}

/**
 * 
 * @param {type} str
 * @returns {Boolean}
 */
function lswpcc_is_url(str) {
    var pattern = new RegExp('^(https?:\\/\\/)?' + // protocol
            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.?)+[a-z]{2,}|' + // domain name
            '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
            '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
            '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
            '(\\#[-a-z\\d_]*)?$', 'i'); // fragment locator
    return pattern.test(str);
}

function lswpcc_change_value(img_id, img_field, img_value) {
    lswpcc_imgs[img_id][img_field] = img_value;
}

function lswpcc_insert_caret(areaId, text) {
    var thisArea = document.getElementById(areaId);
    var scrollPos = thisArea.scrollTop;
    var strPos = 0;
    var br = (
            (
                    thisArea.selectionStart || thisArea.selectionStart == '0'
                    ) ?
            "ff" : (
                    document.selection ? "ie" : false
                    )
            );
    if (br == "ie") {
        thisArea.focus();
        var range = document.selection.createRange();
        range.moveStart('character', -thisArea.value.length);
        strPos = range.text.length;
    } else if (br == "ff") {
        strPos = thisArea.selectionStart;
    }

    var front = (
            thisArea.value
            ).substring(0, strPos);
    var back = (
            thisArea.value
            ).substring(strPos, thisArea.value.length);
    thisArea.value = front + text + back;
    strPos = strPos + text.length;
    if (br == "ie") {
        thisArea.focus();
        var range = document.selection.createRange();
        range.moveStart('character', -thisArea.value.length);
        range.moveStart('character', strPos);
        range.moveEnd('character', 0);
        range.select();
    } else if (br == "ff") {
        thisArea.selectionStart = strPos;
        thisArea.selectionEnd = strPos;
        thisArea.focus();
    }
    thisArea.scrollTop = scrollPos;
}

/**
 * 
 * @param {type} html
 * @returns {String}
 */
function lswpcc_escape_html(html) {
    var fn = function (tag) {
        var charsToReplace = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&#34;'
        };
        return charsToReplace[tag] || tag;
    }
    if (typeof html !== 'string') {
        return '';
    } else {
        return html.replace(/[&<>"]/g, fn);
    }
}


/**
 * Based on https://www.devcurry.com/2010/05/sorting-json-array.html
 * @param string x
 * @param string y
 * @returns string
 * 
 */
 function SortByDisplay(x, y) {
    return ((x.display_name == y.display_name) ? 0 : ((x.display_name > y.display_name) ? 1 : -1));
}