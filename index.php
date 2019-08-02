<?php
/*
  Plugin Name: Easy search and use CC-licensed images for WP
  Plugin URI: https://github.com/lenasterg/wp_ccsearch
  Description: Easy search and use CC-licensed images for WP helps you search millions of CC-licensed images using the Creative Commons Catalog API and insert the original image into content or set as featured image very quickly.
  Version: 1.0
  Author: lenasterg, nts on cti.gr, sch.gr
  Author URI: https://lenasterg.wordpress.com
  Text Domain: ls-wp-ccsearch
  Domain Path: /languages/
 */

defined( 'ABSPATH' ) || exit;

!defined( 'LS_WPCC_VERSION' ) && define( 'LS_WPCC_VERSION', '1.0' );
!defined( 'LS_WPCC_URI' ) && define( 'LS_WPCC_URI', plugin_dir_url( __FILE__ ) );
!defined( 'LS_WPCC_REVIEWS' ) && define( 'LS_WPCC_REVIEWS', 'https://wordpress.org/support/plugin/ls-wp-ccsearch/reviews/' );
!defined( 'LS_WPCC_CHANGELOGS' ) && define( 'LS_WPCC_CHANGELOGS', 'https://wordpress.org/plugins/ls-wp-ccsearch/#developers' );
!defined( 'LS_WPCC_DISCUSSION' ) && define( 'LS_WPCC_DISCUSSION', 'https://wordpress.org/support/plugin/ls-wp-ccsearch' );
!defined( 'WPC_URI' ) && define( 'WPC_URI', LS_WPCC_URI );

//include( 'includes/wpc-menu.php' );
//include( 'includes/wpc-dashboard.php' );

if ( !class_exists( 'WPCCsearch' ) ) {

	class WPCCsearch {

		function __construct() {
			add_action( 'plugins_loaded', array( $this, 'lswpcc_load_textdomain' ) );
			add_action( 'admin_menu', array( $this, 'lswpcc_menu' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'lswpcc_load_scripts' ) );
			add_filter( 'plugin_action_links', array( $this, 'lswpcc_settings_link' ), 10, 2 );
			add_action( 'wp_ajax_lswpcc_search', array( $this, 'lswpcc_search_ajax' ) );
			add_action( 'wp_ajax_nopriv_lswpcc_search', array( $this, 'lswpcc_search_ajax' ) );
			add_action( 'media_buttons', array( $this, 'lswpcc_add_button' ) );
			add_action( 'admin_footer', array( $this, 'lswpcc_area_content' ) );
			add_action( 'save_post', array( $this, 'lswpcc_save_post_data' ), 10, 3 );
			// media tabs
			add_filter( 'media_upload_tabs', array( $this, 'lswpcc_media_upload_tabs' ) );
			add_action( 'media_upload_wpcc', array( $this, 'lswpcc_media_upload_iframe' ) );
			/**
			 * @since v. 0.4.0
			 * wppointer file 
			 */
			require_once 'ls-wp-ccsearch-pointer.php';
		}

		function lswpcc_load_textdomain() {
			load_plugin_textdomain( 'ls-wp-ccsearch', false, basename( dirname( __FILE__ ) ) . '/languages/' );
		}

		function lswpcc_media_upload_tabs( $tabs ) {
			$tabs['wpcc'] = esc_html__( 'Image with CC license', 'ls-wp-ccsearch' );

			return ( $tabs );
		}

		function lswpcc_media_upload_iframe() {
			return wp_iframe( array( $this, 'lswpcc_media_upload_content' ) );
		}

		function lswpcc_media_upload_content() {
			media_upload_header();
			self::lswpcc_area();
		}

		/** 	
		 * @version 2.0 
		 * @author lenasterg
		 */
		function lswpcc_menu() {
			add_submenu_page( 'lswpccsearch', esc_html__( 'Easy search and use CC-licensed images for WP', 'ls-wp-ccsearch' ), esc_html__( 'Easy search and use CC-licensed images for WP', 'ls-wp-ccsearch' ), 'manage_options', 'lswpccsearch-wpcc', array(
				&$this,
				'lswpcc_menu_settings'
			) );
		}

		function lswpcc_menu_settings() {
			$page_slug = 'lswpccsearch-wpcc';
			$active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'how';
			?>
			<div class="lswpccsearch_settings_page wrap">
				<h1 class="lswpccsearch_settings_page_title"><?php echo esc_html__( 'Easy search and use CC-licensed images for WP', 'ls-wp-ccsearch' ) . ' ' . LS_WPCC_VERSION; ?></h1>
				<div class="lswpccsearch_settings_page_desc about-text">
					<p>
						<a href="<?php echo esc_url( LS_WPCC_REVIEWS ); ?>"
						   target="_blank"><?php esc_html_e( 'Reviews', 'ls-wp-ccsearch' ); ?></a> | <a
						   href="<?php echo esc_url( LS_WPCC_CHANGELOGS ); ?>"
						   target="_blank"><?php esc_html_e( 'Changelogs', 'ls-wp-ccsearch' ); ?></a>
						| <a href="<?php echo esc_url( LS_WPCC_DISCUSSION ); ?>"
							 target="_blank"><?php esc_html_e( 'Discussion', 'ls-wp-ccsearch' ); ?></a>
					</p>
				</div>
				<div class="lswpccsearch_settings_page_nav">
					<h2 class="nav-tab-wrapper">
						<a href="?page=<?php echo $page_slug; ?>&amp;tab=how"
						   class="nav-tab <?php echo $active_tab == 'how' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'How to use?', 'ls-wp-ccsearch' ); ?></a>
					</h2>
				</div>
				<div class="lswpccsearch_settings_page_content">
					<?php if ( $active_tab == 'how' ) { ?>
						<div class="lswpccsearch_settings_page_content_text">
							<p><?php esc_html_e( '1. Press the "Image with CC License" button above editor', 'ls-wp-ccsearch' ); ?></p>
							<p><img src="<?php echo LS_WPCC_URI; ?>assets/images/how-01.jpg"/></p>

							<p><?php esc_html_e( '2. Type any key to search', 'ls-wp-ccsearch' ); ?></p>
							<p><img src="<?php echo LS_WPCC_URI; ?>assets/images/how-02.jpg"/></p>

							<p><?php esc_html_e( '3. Choose the photo as you want then insert or set featured', 'ls-wp-ccsearch' ); ?></p>
							<p><img src="<?php echo LS_WPCC_URI; ?>assets/images/how-03.jpg"/></p>
						</div>
					<?php } ?>
				</div>
			</div>
			<?php
		}

		/** 	
		 * @version 2.0
		 * @author lenasterg
		 */
		function lswpcc_load_scripts() {
			wp_enqueue_script( 'colorbox', LS_WPCC_URI . 'assets/js/jquery.colorbox.js', array( 'jquery' ), LS_WPCC_VERSION );
			wp_enqueue_style( 'colorbox', LS_WPCC_URI . 'assets/css/colorbox.css' );
			wp_enqueue_style( 'wpcc', LS_WPCC_URI . 'assets/css/backend.css' );
			wp_enqueue_script( 'wpcc', LS_WPCC_URI . 'assets/js/backend.js', array( 'jquery' ), LS_WPCC_VERSION, true );
			wp_localize_script( 'wpcc', 'lswpcc_vars', array(
				'lswpcc_ajax_url' => admin_url( 'admin-ajax.php' ),
				'lswpcc_media_url' => admin_url( 'upload.php' ),
				'lswpcc_nonce' => wp_create_nonce( 'lswpcc_nonce' ),
				'lswpcc_by_author' => __( 'by', 'ls-wp-ccsearch' ),
				'lswpcc_licensed_under' => __( 'is licensed under', 'ls-wp-ccsearch' ),
				'lswpcc_res_about' => __( 'About', 'ls-wp-ccsearch' ),
				'lswpcc_res_pages' => __( 'results / Pages', 'ls-wp-ccsearch' ),
				'lswpcc_allproviders' => __( 'All providers', 'ls-wp-ccsearch' ),
			) );
		}

		/**
		 * 
		 * @staticvar type $plugin
		 * @param type $links
		 * @param type $file
		 * @return type
		 * @version 2.0 lenasterg
		 */
		function lswpcc_settings_link( $links, $file ) {
			static $plugin;
			if ( !isset( $plugin ) ) {
				$plugin = plugin_basename( __FILE__ );
			}
			return $links;
		}

		/**
		 * @version 3.0
		 */
		function lswpcc_search_ajax() {
			$licence = $provider = '';
			if ( isset( $_POST['provider'] ) ) {
				$provider = '&provider=' . esc_html( sanitize_key( $_POST['provider'] ) );
			}
			//	$provider = '&provider=500px,behance,CAPL,flickr';
			//	$licence='&li=cc0';
			//	$lt = '&lt=modification';
//			$lt = '&lt=commercial';
			$lt = '&li=BY-NC-SA';

			if ( !isset( $_POST['lswpcc_nonce'] ) || !wp_verify_nonce( $_POST['lswpcc_nonce'], 'lswpcc_nonce' ) ) {
				die( esc_html__( 'Permissions check failed', 'ls-wp-ccsearch' ) );
			}
			
			$page= absint($_POST['page']);
			if ( isset( $_POST['key'] ) ) {
				$title= esc_url( sanitize_text_field( $_POST['key'] ) );
				$urli = 'https://api.creativecommons.engineering/image/search?format=json&shouldPersistImages=true' . $lt . $licence . $provider . '&title=' .$title . '&pagesize=20&page=' . $page;
			} else {
				$urli = 'https://api.creativecommons.engineering/image/search?format=json&shouldPersistImages=true' . $lt . $licence . $provider . '&pagesize=20&page=1';
			}

			$response = wp_safe_remote_get( $urli );
			$body = wp_remote_retrieve_body( $response );
			echo $body;
			die();
		}

		function lswpcc_add_button( $editor_id ) {
			echo ' <a href="#lswpcc_area" id="lswpcc_btn" data-editor="' . $editor_id . '" class="lswpcc_btn button add_media" title="' . __( 'Image with CC license', 'ls-wp-ccsearch' ) . '">' . esc_html__( 'Image with CC license', 'ls-wp-ccsearch' ) . '</a><input type="hidden" class="lswpcc_featured_url" name="lswpcc_featured_url" value="" /><input type="hidden" class="lswpcc_featured_title" name="lswpcc_featured_title" value="" /><input type="hidden" class="lswpcc_featured_caption" name="lswpcc_featured_caption" value="" /> ';
		}

		function lswpcc_save_post_data( $post_id, $post ) {
			if ( isset( $post->post_status ) && 'auto-draft' == $post->post_status ) {
				return;
			}
			if ( wp_is_post_revision( $post_id ) ) {
				return;
			}
			if ( !empty( $_POST['lswpcc_featured_url'] ) ) {
				if ( strstr( $_SERVER['REQUEST_URI'], 'wp-admin/post-new.php' ) || strstr( $_SERVER['REQUEST_URI'], 'wp-admin/post.php' ) ) {
					if ( 'page' == $_POST['post_type'] ) {
						if ( !current_user_can( 'edit_page', $post_id ) ) {
							return;
						}
					} else {
						if ( !current_user_can( 'edit_post', $post_id ) ) {
							return;
						}
					}
					$lswpcc_url = sanitize_text_field( $_POST['lswpcc_featured_url'] );
					$lswpcc_title = sanitize_text_field( $_POST['lswpcc_featured_title'] );
					$lswpcc_caption = sanitize_text_field( $_POST['lswpcc_featured_caption'] );
					self::lswpcc_save_featured( $lswpcc_url, $lswpcc_title, $lswpcc_caption );
				}
			}
		}

		function lswpcc_save_featured( $file_url, $title = null, $caption = null ) {
			global $post;
			if ( !function_exists( 'media_handle_upload' ) ) {
				require_once( ABSPATH . 'wp-admin' . '/includes/image.php' );
				require_once( ABSPATH . 'wp-admin' . '/includes/file.php' );
				require_once( ABSPATH . 'wp-admin' . '/includes/media.php' );
			}
			$thumb_id = 0;
			$post_data = array(
				'post_title' => $title,
				'post_excerpt' => $caption
			);
			$filename = pathinfo( $file_url, PATHINFO_FILENAME );
			@set_time_limit( 300 );
			if ( !empty( $file_url ) ) {
				$tmp = download_url( $file_url );
				$ext = pathinfo( $file_url, PATHINFO_EXTENSION );
				$file_array['name'] = $filename . '.' . $ext;
				$file_array['tmp_name'] = $tmp;
				if ( is_wp_error( $tmp ) ) {
					@unlink( $file_array['tmp_name'] );
					$file_array['tmp_name'] = '';
				}
				$thumb_id = media_handle_sideload( $file_array, $post->ID, $desc = null, $post_data );
				if ( is_wp_error( $thumb_id ) ) {
					@unlink( $file_array['tmp_name'] );

					return $thumb_id;
				}
			}
			set_post_thumbnail( $post, $thumb_id );
		}

		function lswpcc_area_content() {
			?>
			<div style='display:none'>
				<?php self::lswpcc_area( true ); ?>
			</div>
			<?php
		}

		/**
		 * version 2.0, lenasterg
		 * @param type $full
		 */
		function lswpcc_area( $full = false ) {
			?>
			<div id="lswpcc_area" class="lswpcc_area">
				<div class="lswpcc_area_content">
					<div class="lswpcc_area_content_col lswpcc_area_content_left">
						<div class="lswpcc_area_content_col_inner">
							<div class="lswpcc_area_content_col_top">
								<label for="lswpcc_input"><?php _e( 'Use only Latin letters', 'ls-wp-ccsearch' ); ?>  </label>
								<input type="text" id="lswpcc_input" name="lswpcc_input" class="w100"
									   placeholder="<?php esc_html_e( 'keyword', 'ls-wp-ccsearch' ); ?>"/>
								<select id="lswpcc_provider" name="lswpcc_provider"></select>
								<input type="button" id="lswpcc_search" class="p20"
									   value="<?php esc_html_e( 'Search', 'ls-wp-ccsearch' ); ?>"/>
							</div>
							<div class="lswpcc_area_content_col_mid">
								<div id="lswpcc_container" class="lswpcc_container"></div>
							</div>
							<div class="lswpcc_area_content_col_bot">
								<div id="lswpcc_page" class="lswpcc_page"></div>
							</div>
						</div>
					</div>
					<div class="lswpcc_area_content_col lswpcc_area_content_right">
						<div id="lswpcc_use_image" class="lswpcc_area_content_right_inner lswpcc_area_content_col_inner">
							<div class="lswpcc_area_content_col_mid">
								<div id="lswpcc_view"></div>

								<div class="lswpcc_area_content_col_bot">
									<input type="hidden" id="lswpcc_title"/>
									<label for="lswpcc_caption"><?php esc_html_e( 'CC license', 'ls-wp-ccsearch' ); ?>:</label><br/>
									<input type="hidden" id="lswpcc_caption" name="lswpcc_caption">
									<div id="lswpcc_caption_display"></div>
									<div class="lswpcc_cc_verify"><?php _e( 'NOTE: Please verify the license at the source', 'ls-wp-ccsearch' ); ?>:<span id="lswpcc_sourcelink"></span>  <?php _e( 'before reuse' ); ?>.
										<br/><?php _e( 'The images are aggregated from publicly available repositories of open content and we can not verify that are properly CC-licensed or that the attribution information is accurate or complete.', 'ls-wp-ccsearch' ); ?></div>
								</div>

								<div class="lswpcc_item_info">
									<div><?php esc_html_e( 'Alignment', 'ls-wp-ccsearch' ); ?></div>
									<div>
										<select name="lswpcc_align" id="lswpcc_align" class="lswpcc_select">
											<option
												value="alignnone"><?php esc_html_e( 'None', 'ls-wp-ccsearch' ); ?>
											</option>
											<option
												value="alignleft"><?php esc_html_e( 'Left', 'ls-wp-ccsearch' ); ?>
											</option>
											<option
												value="alignright"><?php esc_html_e( 'Right', 'ls-wp-ccsearch' ); ?>
											</option>
											<option
												value="aligncenter"><?php esc_html_e( 'Center', 'ls-wp-ccsearch' ); ?>
											</option>
										</select>
									</div>
								</div>
								<div class="lswpcc_item_info">
									<div><?php esc_html_e( 'Use', 'ls-wp-ccsearch' ); ?></div>
									<div>
										<select name="lswpcc_use" id="lswpcc_use" class="lswpcc_select">
											<option
												value="thumbnail"><?php esc_html_e( 'Thumbnail image', 'ls-wp-ccsearch' ); ?>
											</option>
											<option
												value="full"><?php esc_html_e( 'Full image', 'ls-wp-ccsearch' ); ?>
											</option>
										</select>
									</div>
								</div>
								<div class="lswpcc_item_info">
									<div><?php esc_html_e( 'Link to', 'ls-wp-ccsearch' ); ?></div>
									<div>
										<select name="lswpcc_link" id="lswpcc_link" class="lswpcc_select">
											<option
												value="0"><?php esc_html_e( 'None', 'ls-wp-ccsearch' ); ?></option>
											<option
												value="1"><?php esc_html_e( 'Original site', 'ls-wp-ccsearch' ); ?></option>
											<option
												value="2" selected=""><?php esc_html_e( 'Original image', 'ls-wp-ccsearch' ); ?></option>
										</select>
									</div>
								</div>
								<div class="lswpcc_item_info">
									<div>&nbsp;</div>
									<div>
										<input name="lswpcc_blank" id="lswpcc_blank" type="checkbox"
											   class="lswpcc_checkbox"/> <?php esc_html_e( 'Open in new windows', 'ls-wp-ccsearch' ); ?>
									</div>
								</div>
								<div class="lswpcc_item_info">
									<div>&nbsp;</div>
									<div>
										<input name="lswpcc_nofollow" id="lswpcc_nofollow" type="checkbox"
											   class="lswpcc_checkbox"/> <?php esc_html_e( 'Rel nofollow', 'ls-wp-ccsearch' ); ?>
									</div>
								</div>

							</div>
							<div class="lswpcc_area_content_col_bot">
								<?php if ( $full ) { ?>
									<div class="lswpcc_actions">
										<div>
											<input type="hidden" id="lswpcc_site"/>
											<input type="hidden" id="lswpcc_url"/>
											<input type="hidden" id="lswpcc_urlthumb"/>
											<input type="hidden" id="lswpcc_editor_id"/>
											<button id="lswpcc_insert">
												<?php esc_html_e( 'Insert', 'ls-wp-ccsearch' ); ?><span></span>
											</button>
										</div>
										<div>
											<button id="lswpcc_featured">
												<?php esc_html_e( 'Featured', 'ls-wp-ccsearch' ); ?>
											</button>
										</div>
									</div>
								<?php } else { ?>
									<div class="lswpcc_actions one_button">
										<div>
											<input type="hidden" id="lswpcc_site"/>
											<input type="hidden" id="lswpcc_url"/>
											<input type="hidden" id="lswpcc_editor_id"/>
											<button id="lswpcc_insert">
												<?php esc_html_e( 'Insert', 'ls-wp-ccsearch' ); ?><span></span>
											</button>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}

	}

	new WPCCsearch();
}