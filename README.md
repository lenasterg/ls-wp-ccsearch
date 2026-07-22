# Easy search and use CC-licensed images

Easy search and use CC-licensed images helps you search millions of CC-licensed images without leaving the WordPress editor and use them into your post content or set them as a featured image very quickly.

You can download the final stable version via the WordPress.org repository at [https://wordpress.org/plugins/ls-wp-ccsearch/](https://wordpress.org/plugins/ls-wp-ccsearch/)

## Description

Easy search and use CC-licensed images helps you search millions of free photos using the [Openverse API](https://api.openverse.engineering/v1/) and insert the original image into your content or set it as a featured image very quickly.
It allows the user to filter the provider source.
The plugin is inspired and based on [CCSearch](https://ccsearch.creativecommons.org/) and it wouldn't be possible without the [Openverse API](https://api.openverse.engineering/v1/).

After the plugin's activation, you can see the "Image with CC License" button above the editor and as an option into the "Add Media" pop-up window.
By pressing it you can search using Latin characters for an image, browse the returned images, preview an image and its license and adjust the image settings:
 - Use of thumbnail or original image.
 - Set the image link (if any).
 - Insert the image into the post or as a featured image.

> **NOTE 1:** Please, verify the license at the source. CC Search aggregates data from publicly available repositories of open content. Creative Commons does not host the content and does not verify that the content is properly CC-licensed or that the attribution information is accurate or complete. Please follow the link to the source of the content to independently verify before reuse.

> **NOTE 2:** Currently, the plugin needs the [Classic Editor](https://wordpress.org/plugins/classic-editor/) plugin in order to work for WordPress 5+.

## Features

- Works in the WordPress editor and adds a button above the content text area and into the "Add Media" pop-up window.
- Via a pop-up window, allows searching through millions of images using Creative Commons Catalog API power.
- Allows filtering by a provider.
- Paginated results.
- Quick insert original image or thumbnail with an (optional) link to the image URL or the original site.
- Use image as a featured image for the blog post.
- WPML compatible.
- Multisite compatible.
- Translation ready (it's already translated in Greek).

## Installation

1. Unzip the `ls-wp-ccsearch.zip`.
2. Copy the `ls-wp-ccsearch` folder to `wp-content/plugins`.
3. Go to **Plugins > Installed Plugins**, find *Easy search and use CC-licensed images* and click **Activate**.
4. Now when you create/edit a post, you can see the "Image with CC License" button above the editor and as an option into the "Add Media" pop-up window.
5. Enjoy!

## Screenshots

1. The "Image with CC license" button on editor.
2. Search for an image.
3. Preview of an image and its license.
4. Image settings: use of thumbnail or original image, set the image link (if any). Insert the image into the post or as featured image.
5. The image and its license into the editor.
6. The published post with the image.

## Roadmap

- Multiple images select support.
- Gutenberg compatibility.
- Multiselect options for filtering: providers.
- Select options for filtering: licenses, creator.
- Image for the 'Image with CC license' editor button.
- Info page.
- Initial step for registering in the API and use of Bearer authentication.

## Credits

- The plugin is inspired and based on [CC Search](https://ccsearch.creativecommons.org/) and wouldn't be possible without the [Creative Commons Catalog API](https://api.openverse.engineering).
- The plugin's code is based on the [WP Pexels](https://wordpress.org/plugins/wp-pexels/) code by WPclever.net.
- Pointer's code is based on the [Tuts+ Admin Pointers Guide](https://code.tutsplus.com/articles/integrating-with-wordpress-ui-admin-pointers--wp-26853).

## Changelog

### 5.0
* **Performance:** Restricted admin scripts to load exclusively on post edit and new post screens.
* **Security:** Enhanced security by escaping all localized variables and translation strings in the backend.
* Renamed from Easy search and use CC-licensed images for WP to Easy search and use CC-licensed images.
* Tested WordPress 7.0 compatibility.

### 4.0
* Fix bug for displaying available images sources.
* WordPress 6.5.4 compatibility.

### 3.2
* Fix bug when image was set as post thumbnail. Props to @gnr5 for spotting the bug.
* Display images sources sorted by name.
* WordPress 6.1 compatibility.

### 3.1
* Remove attributes images.

### 3.0
* Changes to use openverse API urls.

### 2.0
* Changes at creativecommons API urls.
* Support also non-latin keywords.

### 1.0
* Replaces curl with WordPress native functionality.
* Fix some typos.
* Sanitize input.
* Renamed from WP CCsearch to Easy search and use CC-licensed images for WP.

### 0.5.0
* Add class to img.
* Larger colorbox height.
* New strings added.
* New screenshots.

### 0.4.0
* Add wp-pointer on editor's button.
* Fix: Wrong textdomain.
* Removed unused code.
* New strings added.

### 0.3.0
* Add provider dropdown select.
* Fix: Use full image when thumbnail url is broken.

### 0.2.0
* Fix ls-wp-ccsearch.pot.
* Greek translation added.

### 0.1
* Released.