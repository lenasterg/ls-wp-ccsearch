
jQuery( document ).ready( function( $ ) {
//
//wp.blocks.registerBlockStyle( 'core/image', {
//    name: 'fancy-quote',
//    label: 'Fancy Quote'
//} );



function addWpCCsearch(settings, name){
    if ( name == 'core/image' ) {
        console.log(settings);
        return settings;
    }
 
}

wp.hooks.addFilter('blocks.registerBlockType','ls-wp-ccsearch/image-block',addWpCCsearch);
//
//function addListBlockClassName( settings, name ) {
//    if ( name !== 'core/list' ) {
//        return settings;
//    }
// 
//    return lodash.assign( {}, settings, {
//        supports: lodash.assign( {}, settings.supports, {
//            className: true
//        } ),
//    } );
//}
// 
//wp.hooks.addFilter(
//    'blocks.registerBlockType',
//    'my-plugin/class-names/list-block',
//    addListBlockClassName
//);
} );