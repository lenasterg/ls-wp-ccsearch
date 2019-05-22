# WP CCSearch 

WP CCSearch helps you search millions of free images then insert into content or set as featured image very quickly.

# Test site
You can test the plugin functionality at http://users.sch.gr/stergatu/wordpress/ by login via http://users.sch.gr/stergatu/wordpress/wp-login.php as testuser: testuser.


## Description

WP CCSearch helps you search millions of free images using the CCsearch API (https://api.creativecommons.engineering/#tag/image)  then insert the original image into content or set as featured image very quickly.
It allows the user to filter the provider source.
The plugin is inspired and based on the https://ccsearch.creativecommons.org/ and it wouldnt't be possible without the Creative Commons Catalog API https://api.creativecommons.engineering/

After the plugin's activation, you can see the "Image with CC Licence"  button above the editor and as option into the "Add Media" pop-up window. 
By pressing it  you can search using Latin characters for an image, browse the returned images, preview an image and its license and adjuct the image settings: 
 - use of thumbnail or original image,
 - set the image link (if any). 
 - Insert the image into the post or as featured image.


NOTE: Please, verify the license at the source. CC Search aggregates data from publicly available repositories of open content. 
Creative Commons does not host the content and does not verify that the content is properly CC-licensed or that the attribution information is accurate or complete. 
Please follow the link to the source of the content to independently verify before reuse.

Currently, the plugin needs Classic Editor (https://wordpress.org/plugins/classic-editor/) in order to work.


## Features

- Search millions of free images using CCsearch API
- Quick insert image with original URL or thumbmail
- Use image as featured image
- WPML integration
- Multisite compatible


## Installation

1. Unzip the wp-ccsearch.zip
2. Copy wp-ccsearch folder to wp-content/plugins
3. Go to Plugins > Installed Plugins, find WP CCSearch and click Active
4. Now when you create/edit the post, you can see the "Image with CC Licence"  button above the editor and as option into the "Add Media" pop-up window. 
5. Enjoy!

## Screenshots
1. The "Image with CC licence" button on editor
2. Search for an image 
3. Preview of an image and its license.
4. Image settings: use of thumbnail or original image, set the image link (if any). Insert the image into the post or as featured image.
5. The image and it's license into the editor
6. The published post with the image.


## Changelog


### 0.4.0 
* Add wp-pointer on editor's button
* Fix: Wrong textdomain
* Removed unused code
* New strings added

### 0.3.0 
* Add provider dropdown select
* Fix: Use full image when thumbnail url is broken 


### 0.2.0 
* Fix wp-ccsearch.pot
* Greek translation added

### 0.1 
* Released

## Roadmap
- Multiple images select support
- Gutenberg compatibility
- Multiselect options for filtering: providers
- select options for filtering: licenses, creator
- Image for the 'Image via CC Search' editor button
- Info page

## Credits 
- The plugin is inspired and based on the https://ccsearch.creativecommons.org/ and it wouldnt't be possible without the Creative Commons Catalog API https://api.creativecommons.engineering/
- The plugin's code is based on the WP Pexels https://wordpress.org/plugins/wp-pexels/  code by WPclever.net
- Pointer's code is based on https://code.tutsplus.com/articles/integrating-with-wordpress-ui-admin-pointers--wp-26853
