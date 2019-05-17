<?php
/*
  Plugin Name: WP CCSearch
  Plugin URI: https://github.com/lenasterg/wp_ccsearch
  Description: WP CCSearch helps you search millions of free photos then insert into content or set as featured image very quickly.
  Version: 0.1
  Author: lenasterg, nts on cti.gr, sch.gr
  Author URI: https://lenasterg.wordpress.com
  Text Domain: wpcc
  Domain Path: /languages/
 */

defined( 'ABSPATH' ) || exit;

!defined( 'WPCC_VERSION' ) && define( 'WPCC_VERSION', '1.0' );
!defined( 'WPCC_URI' ) && define( 'WPCC_URI', plugin_dir_url( __FILE__ ) );
!defined( 'WPCC_REVIEWS' ) && define( 'WPCC_REVIEWS', 'https://wordpress.org/support/plugin/wp-ccsearch/reviews/?filter=5' );
!defined( 'WPCC_CHANGELOGS' ) && define( 'WPCC_CHANGELOGS', 'https://wordpress.org/plugins/wp-ccsearch/#developers' );
!defined( 'WPCC_DISCUSSION' ) && define( 'WPCC_DISCUSSION', 'https://wordpress.org/support/plugin/wp-ccsearch' );
!defined( 'WPC_URI' ) && define( 'WPC_URI', WPCC_URI );

//include( 'includes/wpc-menu.php' );
//include( 'includes/wpc-dashboard.php' );

if ( !class_exists( 'WPCCsearch' ) ) {

	class WPCCsearch {

		function __construct() {
			add_action( 'plugins_loaded', array( $this, 'wpcc_load_textdomain' ) );
			add_action( 'admin_menu', array( $this, 'wpcc_menu' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'wpcc_load_scripts' ) );
			add_filter( 'plugin_action_links', array( $this, 'wpcc_settings_link' ), 10, 2 );
			add_action( 'wp_ajax_wpcc_search', array( $this, 'wpcc_search_ajax' ) );
			add_action( 'wp_ajax_nopriv_wpcc_search', array( $this, 'wpcc_search_ajax' ) );
			add_action( 'media_buttons', array( $this, 'wpcc_add_button' ) );
			add_action( 'admin_footer', array( $this, 'wpcc_area_content' ) );
			add_action( 'save_post', array( $this, 'wpcc_save_post_data' ), 10, 3 );
			// media tabs
			add_filter( 'media_upload_tabs', array( $this, 'wpcc_media_upload_tabs' ) );
			add_action( 'media_upload_wpcc', array( $this, 'wpcc_media_upload_iframe' ) );
		}

		function wpcc_load_textdomain() {
			load_plugin_textdomain( 'wp-ccsearch', false, basename( dirname( __FILE__ ) ) . '/languages/' );
		}

		function wpcc_media_upload_tabs( $tabs ) {
			$tabs['wpcc'] = esc_html__( 'Image via CC search', 'wp-ccsearch' );

			return ( $tabs );
		}

		function wpcc_media_upload_iframe() {
			return wp_iframe( array( $this, 'wpcc_media_upload_content' ) );
		}

		function wpcc_media_upload_content() {
			media_upload_header();
			self::wpcc_area();
		}

		/** 	
		 * @version 2.0 
		 * @author lenasterg
		 */
		function wpcc_menu() {
			add_submenu_page( 'wpccsearch', esc_html__( 'WP CCSearch', 'wp-ccsearch' ), esc_html__( 'WP CCSearch', 'wp-ccsearch' ), 'manage_options', 'wpccsearch-wpcc', array(
				&$this,
				'wpcc_menu_settings'
			) );
		}

		function wpcc_menu_settings() {
			$page_slug = 'wpccsearch-wpcc';
			$active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'how';
			?>
			<div class="wpccsearch_settings_page wrap">
				<h1 class="wpccsearch_settings_page_title"><?php echo esc_html__( 'WP CCSearch', 'wp-ccsearch' ) . ' ' . WPCC_VERSION; ?></h1>
				<div class="wpccsearch_settings_page_desc about-text">
					<p>
						<?php printf( esc_html__( 'Thank you for using our plugin! If you are satisfied, please reward it a full five-star %s rating.', 'wp-ccsearch' ), '<span style="color:#ffb900">&#9733;&#9733;&#9733;&#9733;&#9733;</span>' ); ?>
						<br/>
						<a href="<?php echo esc_url( WPCC_REVIEWS ); ?>"
						   target="_blank"><?php esc_html_e( 'Reviews', 'wp-ccsearch' ); ?></a> | <a
						   href="<?php echo esc_url( WPCC_CHANGELOGS ); ?>"
						   target="_blank"><?php esc_html_e( 'Changelogs', 'wp-ccsearch' ); ?></a>
						| <a href="<?php echo esc_url( WPCC_DISCUSSION ); ?>"
							 target="_blank"><?php esc_html_e( 'Discussion', 'wp-ccsearch' ); ?></a>
					</p>
				</div>
				<div class="wpccsearch_settings_page_nav">
					<h2 class="nav-tab-wrapper">
						<a href="?page=<?php echo $page_slug; ?>&amp;tab=how"
						   class="nav-tab <?php echo $active_tab == 'how' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'How to use?', 'wp-ccsearch' ); ?></a>
					</h2>
				</div>
				<div class="wpccsearch_settings_page_content">
					<?php if ( $active_tab == 'how' ) { ?>
						<div class="wpccsearch_settings_page_content_text">
							<p><?php esc_html_e( '1. Press the "Image via CCsearch" button above editor', 'wp-ccsearch' ); ?></p>
							<p><img src="<?php echo WPCC_URI; ?>assets/images/how-01.jpg"/></p>

							<p><?php esc_html_e( '2. Type any key to search', 'wp-ccsearch' ); ?></p>
							<p><img src="<?php echo WPCC_URI; ?>assets/images/how-02.jpg"/></p>

							<p><?php esc_html_e( '3. Choose the photo as you want then insert or set featured', 'wp-ccsearch' ); ?></p>
							<p><img src="<?php echo WPCC_URI; ?>assets/images/how-03.jpg"/></p>
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
		function wpcc_load_scripts() {
			wp_enqueue_script( 'colorbox', WPCC_URI . 'assets/js/jquery.colorbox.js', array( 'jquery' ), WPCC_VERSION );
			wp_enqueue_style( 'colorbox', WPCC_URI . 'assets/css/colorbox.css' );
			wp_enqueue_style( 'wpcc', WPCC_URI . 'assets/css/backend.css' );
			wp_enqueue_script( 'wpcc', WPCC_URI . 'assets/js/backend.js', array( 'jquery' ), WPCC_VERSION, true );
			wp_localize_script( 'wpcc', 'wpcc_vars', array(
				'wpcc_ajax_url' => admin_url( 'admin-ajax.php' ),
				'wpcc_media_url' => admin_url( 'upload.php' ),
				'wpcc_nonce' => wp_create_nonce( 'wpcc_nonce' ),
				'wpcc_by_author' => __( 'by', 'wp-ccsearch' ),
				'wpcc_licensed_under' => __( 'is licensed under', 'wp-ccsearch' ),
				'wpcc_res_about' =>__('About', 'wp-ccsearch' ), 
				'wpcc_res_pages' =>__('results / Pages', 'wp-ccsearch' ), 
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
		function wpcc_settings_link( $links, $file ) {
			static $plugin;
			if ( !isset( $plugin ) ) {
				$plugin = plugin_basename( __FILE__ );
			}
			return $links;
		}

		function wpcc_search_ajax() {
			$licence = $provider = '';
			//	$provider = '&provider=500px,behance,CAPL,flickr';
			//	$licence='&li=cc0';
			//	$lt = '&lt=modification';
//			$lt = '&lt=commercial';
			$lt = '&li=BY-NC-SA';
			//$provider='&provider=500px,thorvaldsensmuseum,thingiverse,svgsilh,sciencemuseum,rijksmuseum,rawpixel,nypl,museumsvictoria,met,mccordmuseum,iha,geographorguk,floraon,eol,digitaltmuseum,deviantart,clevelandmuseum,brooklynmuseum,behance,animaldiversity,WoRMS,CAPL';
			if ( !isset( $_POST['wpcc_nonce'] ) || !wp_verify_nonce( $_POST['wpcc_nonce'], 'wpcc_nonce' ) ) {
				die( esc_html__( 'Permissions check failed', 'wp-ccsearch' ) );
			}
			$ch = curl_init();
			$page = isset( $_POST['page'] ) ? $_POST['page'] : 1;
			if ( isset( $_POST['key'] ) ) {
				curl_setopt( $ch, CURLOPT_URL, 'https://api.creativecommons.engineering/image/search?format=json&shouldPersistImages=true' . $lt . $licence . $provider . '&title=' . esc_url( $_POST['key'] ) . '&pagesize=20&page=' . $page );
			} else {
				curl_setopt( $ch, CURLOPT_URL, 'https://api.creativecommons.engineering/image/search?format=json&shouldPersistImages=true' . $lt . $licence . $provider . '&pagesize=20&page=1' );
			}
			curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json'
			) );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			echo curl_exec( $ch );
			curl_close( $ch );
			die();
		}

		function wpcc_add_button( $editor_id ) {
			echo ' <a href="#wpcc_area" id="wpcc_btn" data-editor="' . $editor_id . '" class="wpcc_btn button add_media" title="Image via CC Search">' . esc_html__( 'Image via CC Search', 'wp-ccsearch' ) . '</a><input type="hidden" class="wpcc_featured_url" name="wpcc_featured_url" value="" /><input type="hidden" class="wpcc_featured_title" name="wpcc_featured_title" value="" /><input type="hidden" class="wpcc_featured_caption" name="wpcc_featured_caption" value="" /> ';
		}

		function wpcc_save_post_data( $post_id, $post ) {
			if ( isset( $post->post_status ) && 'auto-draft' == $post->post_status ) {
				return;
			}
			if ( wp_is_post_revision( $post_id ) ) {
				return;
			}
			if ( !empty( $_POST['wpcc_featured_url'] ) ) {
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
					$wpcc_url = sanitize_text_field( $_POST['wpcc_featured_url'] );
					$wpcc_title = sanitize_text_field( $_POST['wpcc_featured_title'] );
					$wpcc_caption = sanitize_text_field( $_POST['wpcc_featured_caption'] );
					self::wpcc_save_featured( $wpcc_url, $wpcc_title, $wpcc_caption );
				}
			}
		}

		function wpcc_save_featured( $file_url, $title = null, $caption = null ) {
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

		function wpcc_area_content() {
			?>
			<div style='display:none'>
				<?php self::wpcc_area( true ); ?>
			</div>
			<?php
		}

		/**
		 * version 2.0, lenasterg
		 * @param type $full
		 */
		function wpcc_area( $full = false ) {
			?>
			<div id="wpcc_area" class="wpcc_area">
				<div class="wpcc_area_content">
					<div class="wpcc_area_content_col wpcc_area_content_left">
						<div class="wpcc_area_content_col_inner">
							<div class="wpcc_area_content_col_top">
								<label for="wpcc_input"><?php _e( 'Use only Latin letters', 'wpcc' ); ?>  </label>
								<input type="text" id="wpcc_input" name="wpcc_input" class="w300"
									   placeholder="<?php esc_html_e( 'keyword', 'wp-ccsearch' ); ?>"/>
								<input type="button" id="wpcc_search" class="p20"
									   value="<?php esc_html_e( 'Search', 'wp-ccsearch' ); ?>"/>
							</div>
							<div class="wpcc_area_content_col_mid">
								<div id="wpcc_container" class="wpcc_container"></div>
							</div>
							<div class="wpcc_area_content_col_bot">
								<div id="wpcc_page" class="wpcc_page"></div>
							</div>
						</div>
					</div>
					<div class="wpcc_area_content_col wpcc_area_content_right">
						<div id="wpcc_use_image" class="wpcc_area_content_right_inner wpcc_area_content_col_inner">
							<div class="wpcc_area_content_col_mid">
								<div id="wpcc_view"></div>

								<div class="wpcc_area_content_col_bot">
									<input type="hidden" id="wpcc_title"/>
									<label for="wpcc_caption"><?php esc_html_e( 'CC license', 'wp-ccsearch' ); ?>:</label><br/>
									<input type="hidden" id="wpcc_caption" name="wpcc_caption">
									<div id="wpcc_caption_display"></div>
									<div class="wpcc_cc_verify"><?php  _e( 'NOTE: Please verify the license at the source','wp-ccsearch');?>:<span id="wpcc_sourcelink"></span>
										<br/><?php  _e( 'CC Search aggregates data from publicly available repositories of open content. CC does not host the content and does not verify that the content is properly CC-licensed or that the attribution information is accurate or complete. Please follow the link to the source of the content to independently verify before reuse.', 'wp-ccsearch' );?></div>
								</div>
							
								<div class="wpcc_item_info">
									<div><?php esc_html_e( 'Alignment', 'wp-ccsearch' ); ?></div>
									<div>
										<select name="wpcc_align" id="wpcc_align" class="wpcc_select">
											<option
												value="alignnone"><?php esc_html_e( 'None', 'wp-ccsearch' ); ?>
											</option>
											<option
												value="alignleft"><?php esc_html_e( 'Left', 'wp-ccsearch' ); ?>
											</option>
											<option
												value="alignright"><?php esc_html_e( 'Right', 'wp-ccsearch' ); ?>
											</option>
											<option
												value="aligncenter"><?php esc_html_e( 'Center', 'wp-ccsearch' ); ?>
											</option>
										</select>
									</div>
								</div>
								<div class="wpcc_item_info">
									<div><?php esc_html_e( 'Use', 'wp-ccsearch' ); ?></div>
									<div>
										<select name="wpcc_use" id="wpcc_use" class="wpcc_select">
											<option
												value="thumbnail"><?php esc_html_e( 'Thumbnail image', 'wp-ccsearch' ); ?>
											</option>
											<option
												value="full"><?php esc_html_e( 'Full image', 'wp-ccsearch' ); ?>
											</option>
										</select>
									</div>
								</div>
								<div class="wpcc_item_info">
									<div><?php esc_html_e( 'Link to', 'wp-ccsearch' ); ?></div>
									<div>
										<select name="wpcc_link" id="wpcc_link" class="wpcc_select">
											<option
												value="0"><?php esc_html_e( 'None', 'wp-ccsearch' ); ?></option>
											<option
												value="1"><?php esc_html_e( 'Original site', 'wp-ccsearch' ); ?></option>
											<option
												value="2" selected=""><?php esc_html_e( 'Original image', 'wp-ccsearch' ); ?></option>
										</select>
									</div>
								</div>
								<div class="wpcc_item_info">
									<div>&nbsp;</div>
									<div>
										<input name="wpcc_blank" id="wpcc_blank" type="checkbox"
											   class="wpcc_checkbox"/> <?php esc_html_e( 'Open in new windows', 'wp-ccsearch' ); ?>
									</div>
								</div>
								<div class="wpcc_item_info">
									<div>&nbsp;</div>
									<div>
										<input name="wpcc_nofollow" id="wpcc_nofollow" type="checkbox"
											   class="wpcc_checkbox"/> <?php esc_html_e( 'Rel nofollow', 'wp-ccsearch' ); ?>
									</div>
								</div>

							</div>
							<div class="wpcc_area_content_col_bot">
								<?php if ( $full ) { ?>
									<div class="wpcc_actions">
										<div>
											<input type="hidden" id="wpcc_site"/>
											<input type="hidden" id="wpcc_url"/>
											<input type="hidden" id="wpcc_urlthumb"/>
											<input type="hidden" id="wpcc_editor_id"/>
											<button id="wpcc_insert">
												<?php esc_html_e( 'Insert', 'wp-ccsearch' ); ?><span></span>
											</button>
										</div>
										<div>
											<button id="wpcc_featured">
												<?php esc_html_e( 'Featured', 'wp-ccsearch' ); ?>
											</button>
										</div>
									</div>
								<?php } else { ?>
									<div class="wpcc_actions one_button">
										<div>
											<input type="hidden" id="wpcc_site"/>
											<input type="hidden" id="wpcc_url"/>
											<input type="hidden" id="wpcc_editor_id"/>
											<button id="wpcc_insert">
												<?php esc_html_e( 'Insert', 'wp-ccsearch' ); ?><span></span>
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