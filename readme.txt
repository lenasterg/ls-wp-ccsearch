=== Easy search and use CC-licensed images for WP ===
Contributors: lenasterg, nts on cti.gr, sch.gr
Donate link: 
Tags: cc, creative commons, media library, free image, free photo, multisite
Requires at least: 4.6
Tested up to: 5.2.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easy search and use CC-licensed images for WP helps you search millions of CC-licensed images without leaving WordPress editor and use one into your post content or set as featured image very quickly.

== Description ==

Easy search and use CC-licensed images for WP helps you search millions of free photos using the Creative Commons Catalog API (https://api.creativecommons.engineering/#tag/image) then insert the original image into content or set as featured image very quickly.
It allows the user to filter the provider source.
The plugin is inspired and based on the https://ccsearch.creativecommons.org/ and it wouldnt't be possible without the Creative Commons Catalog API https://api.creativecommons.engineering/

After the plugin's activation, you can see the "Image with CC License"  button above the editor and as option into the "Add Media" pop-up window. 
By pressing it  you can search using Latin characters for an image, browse the returned images, preview an image and its license and adjust the image settings: 
 - use of thumbnail or original image,
 - set the image link (if any). 
 - Insert the image into the post or as featured image.

The plugin’s features are:

- Works in WordPress editor and add a button above the content text area and into the “Add Media” pop-up window.
- Via a pop-up window, allows searching through millions of images using Creative Commons Catalog API power.
- Allows filtering by a provider
- Paginated results
- Quick insert original image or thumbnail with an (optional) link to the image URL or the original site
- Use image as a featured image for the blog post
- WPML compatible
- Multisite compatible
- Translation ready (it’s already translated in Greek)
- Tested up to WordPress 5.2.2 with Classic Editor plugin

You can find a blog post about the inspiration and how it works in https://opensource.creativecommons.org/blog/entries/2019-07-24-cc-search-wp-plugin/

NOTE 1: Please, verify the license at the source. Creative Commons does not host the content and does not verify that the content is properly CC-licensed or that the attribution information is accurate or complete. 
Please follow the link to the source of the content to independently verify before reuse.

NOTE 2: Currently, the plugin needs Classic Editor (https://wordpress.org/plugins/classic-editor/) in order to work for WP 5+.


= Features =

- Search millions of free photos using Creative Commons Catalog API
- Filter by provider
- Quick insert image with original URL or thumbnail
- Use image as featured image
- WPML integration
- Multisite compatible


== Installation ==

1. Unzip the ls-wp-ccsearch.zip
2. Copy ls-wp-ccsearch folder to wp-content/plugins
3. Go to Plugins > Installed Plugins, find Easy search and use CC-licensed images for WP and click Active
4. Now when you create/edit the post, you can see the "Image with CC License"  button above the editor and as option into the "Add Media" pop-up window. 
5. Enjoy!

== Screenshots ==
1. The "Image with CC license" button on editor
2. Search for an image 
3. Preview of an image and its license.
4. Image settings: use of thumbnail or original image, set the image link (if any). Insert the image into the post or as featured image.
5. The image and it's license into the editor
6. The published post with the image.

== Changelog ==


= 1.0 =
* Replaces curl with WordPress native functionality
* Fix some typos
* Sanitize input
* Renamed from WP CCsearch to Easy search and use CC-licensed images for WP

= 0.5.0 =
* Add class to img
* Larger colorbox height
* New strings added
* New screenshots
= 0.4.0 =
* Add wp-pointer on editor's button
* Fix: Wrong textdomain
* Removed unused code
* New strings added

= 0.3.0 =
* Add provider dropdown select
* Fix: Use full image when thumbnail url is broken 

= 0.2.0 =
* Fix ls-wp-ccsearch.pot
* Greek translation added

= 0.1.0 =
* Released

== Roadmap ==
- Multiple images select support
- Gutenberg compatibility
- Multiselect options for filtering: providers
- select options for filtering: licenses, creator
- Image for the 'Image with CC license' editor button
- Info page

== Credits ==
- The plugin is inspired and based on the https://ccsearch.creativecommons.org/ and it wouldnt't be possible without the Creative Commons Catalog API https://api.creativecommons.engineering/
- The plugin's code is based on the WP Pexels https://wordpress.org/plugins/wp-pexels/  code by WPclever.net
- Pointer's code is based on https://code.tutsplus.com/articles/integrating-with-wordpress-ui-admin-pointers--wp-26853
