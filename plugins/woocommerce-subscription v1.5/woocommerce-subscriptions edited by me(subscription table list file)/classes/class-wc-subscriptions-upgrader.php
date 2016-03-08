<?php
/**
 * A timeout resistant, single-serve upgrader for WC Subscriptions.
 *
 * This class is used to make all reasonable attempts to neatly upgrade data between versions of Subscriptions.
 *
 * For example, the subscription meta data associated with an order significantly changed between 1.1.n and 1.2.
 * It was imperative the data be upgraded to the new schema without hassle. A hassle could easily occur if 100,000
 * orders were being modified - memory exhaustion, script time out etc.
 *
 * @package		WooCommerce Subscriptions
 * @subpackage	WC_Subscriptions_Checkout
 * @category	Class
 * @author		Brent Shepherd
 * @since		1.2
 */
class WC_Subscriptions_Upgrader {

	private static $active_version;

	private static $upgrade_limit;

	private static $about_page_url;

	/**
	 * Hooks upgrade function to init.
	 *
	 * @since 1.2
	 */
	public static function init() {

		self::$active_version = get_option( WC_Subscriptions_Admin::$option_prefix . '_active_version', '0' );

		self::$upgrade_limit = apply_filters( 'woocommerce_subscriptions_hooks_to_upgrade', 250 );

		self::$about_page_url = admin_url( 'index.php?page=wcs-about&wcs-updated=true' );

		if ( isset( $_POST['action'] ) && 'wcs_upgrade' == $_POST['action'] ) {


			add_action( 'wp_ajax_wcs_upgrade', __CLASS__ . '::ajax_upgrade', 10 );

		} elseif ( @current_user_can( 'activate_plugins' ) ) {

			if ( 'true' == get_transient( 'wc_subscriptions_is_upgrading' ) ) {

				self::upgrade_in_progress_notice();

			} elseif ( isset( $_GET['wcs_upgrade_step'] ) || version_compare( self::$active_version, WC_Subscriptions::$version, '<' ) ) {

				// Run updates as soon as admin hits site
				add_action( 'init', __CLASS__ . '::upgrade', 11 );

			} elseif( is_admin() && isset( $_GET['page'] ) && 'wcs-about' == $_GET['page'] ){

				add_action( 'admin_menu', __CLASS__ . '::updated_welcome_page' );

			}

		}
	}

	/**
	 * Checks which upgrades need to run and calls the necessary functions for that upgrade.
	 *
	 * @since 1.2
	 */
	public static function upgrade(){
		global $wpdb;


		// Update the hold stock notification to be one week (if it's still at the default 60 minutes) to prevent cancelling subscriptions using manual renewals and payment methods that can take more than 1 hour (i.e. PayPal eCheck)
		if ( '0' == self::$active_version ) {

			$hold_stock_duration = get_option( 'woocommerce_hold_stock_minutes' );

			if ( 60 == $hold_stock_duration ) {
				update_option( 'woocommerce_hold_stock_minutes', 60 * 24 * 7 );
			}
		}

		// Keep track of site url to prevent duplicate payments from staging sites, first added in 1.3.8 & updated with 1.4.2 to work with WP Engine staging sites
		if ( '0' == self::$active_version || version_compare( self::$active_version, '1.4.2', '<' ) ) {
			WC_Subscriptions::set_duplicate_site_url_lock();
		}

		// Don't autoload cron locks
		if ( '0' != self::$active_version && version_compare( self::$active_version, '1.4.3', '<' ) ) {
			$wpdb->query(
				"UPDATE $wpdb->options
				SET autoload = 'no'
				WHERE option_name LIKE 'wcs_blocker_%'"
			);
		}

		// Add support for quantities  & migrate wp_cron schedules to the new action-scheduler system.
		if ( '0' != self::$active_version && version_compare( self::$active_version, '1.5', '<' ) ) {
			self::upgrade_to_version_1_5();
		}

		self::upgrade_complete();
	}

	/**
	 * When an upgrade is complete, set the active version, delete the transient locking upgrade and fire a hook.
	 *
	 * @since 1.2
	 */
	public static function upgrade_complete() {
		// Set the new version now that all upgrade routines have completed
		update_option( WC_Subscriptions_Admin::$option_prefix . '_active_version', WC_Subscriptions::$version );

		do_action( 'woocommerce_subscriptions_upgraded', WC_Subscriptions::$version );
	}

	/**
	 * Add support for quantities for subscriptions.
	 * Update all current subscription wp_cron tasks to the new action-scheduler system.
	 *
	 * @since 1.5
	 */
	private static function upgrade_to_version_1_5() {

		$_GET['wcs_upgrade_step'] = ( ! isset( $_GET['wcs_upgrade_step'] ) ) ? 0 : $_GET['wcs_upgrade_step'];

		switch ( (int)$_GET['wcs_upgrade_step'] ) {
			case 1:
				self::display_database_upgrade_helper();
				break;
			case 3: // keep a way to circumvent the upgrade routine just in case
				self::upgrade_complete();
				wp_safe_redirect( self::$about_page_url );
				break;
			case 0:
			default:
				wp_safe_redirect( admin_url( 'admin.php?wcs_upgrade_step=1' ) );
				break;
		}

		exit();
	}

	/**
	 * Move scheduled subscription hooks out of wp-cron and into the new Action Scheduler.
	 *
	 * Also set all existing subscriptions to "sold individually" to maintain previous behavior
	 * for existing subscription products before the subscription quantities feature was enabled..
	 *
	 * @since 1.5
	 */
	public static function ajax_upgrade() {
		global $wpdb;


		@set_time_limit( 600 );
		@ini_set( 'memory_limit', apply_filters( 'admin_memory_limit', WP_MAX_MEMORY_LIMIT ) );

		set_transient( 'wc_subscriptions_is_upgrading', 'true', 60 * 2 );

		if ( 'products' == $_POST['upgrade_step'] ) {

			// Set status to 'sold individually' for all existing subscriptions that haven't already been updated
			$sql = "SELECT DISTINCT ID FROM {$wpdb->posts} as posts
				JOIN {$wpdb->postmeta} as postmeta
					ON posts.ID = postmeta.post_id
					AND (postmeta.meta_key LIKE '_subscription%')
				JOIN  {$wpdb->postmeta} AS soldindividually
					ON posts.ID = soldindividually.post_id
					AND ( soldindividually.meta_key LIKE '_sold_individually' AND soldindividually.meta_value !=  'yes' )
				WHERE posts.post_type = 'product'";

			$subscription_product_ids = $wpdb->get_results( $sql );


			foreach ( $subscription_product_ids as $product_id ) {
				update_post_meta( $product_id->ID, '_sold_individually', 'yes' );
			}

			$results = array(
				'message' => sprintf( __( 'Marked %s subscription products as "sold individually".', 'woocommerce-subscriptions' ), count( $subscription_product_ids ) )
			);

		} else {

			$counter  = 0;

			$before_cron_update = microtime(true);

			// update all of the current Subscription cron tasks to the new Action Scheduler
			$cron = _get_cron_array();


			foreach ( $cron as $timestamp => $actions ) {
				foreach ( $actions as $hook => $details ) {
					if ( $hook == 'scheduled_subscription_payment' || $hook == 'scheduled_subscription_expiration' || $hook == 'scheduled_subscription_end_of_prepaid_term' || $hook == 'scheduled_subscription_trial_end' || $hook == 'paypal_check_subscription_payment' ) {
						foreach ( $details as $hook_key => $values ) {


							if ( ! wc_next_scheduled_action( $hook, $values['args'] ) ) {
								wc_schedule_single_action( $timestamp, $hook, $values['args'] );
								unset( $cron[$timestamp][$hook][$hook_key] );
								$counter++;
							}


							if ( $counter >= self::$upgrade_limit ) {
								break;
							}
						}

						// If there are no other jobs scheduled for this hook at this timestamp, remove the entire hook
						if ( 0 == count( $cron[$timestamp][$hook] ) ) {
							unset( $cron[$timestamp][$hook] );
						}
						if ( $counter >= self::$upgrade_limit ) {
							break;
						}
					}
				}

				// If there are no actions schedued for this timestamp, remove the entire schedule
				if ( 0 == count( $cron[$timestamp] ) ) {
					unset( $cron[$timestamp] );
				}
				if ( $counter >= self::$upgrade_limit ) {
					break;
				}
			}

			// Set the cron with the removed schedule
			_set_cron_array( $cron );

			$results = array(
				'upgraded_count' => $counter,
				'message'        => sprintf( __( 'Migrated %s subscription related hooks to the new scheduler (in {execution_time} seconds).', 'woocommerce-subscriptions' ), $counter )
			);

		}

		if ( isset( $counter ) && $counter < self::$upgrade_limit ) {
			self::upgrade_complete();
		}

		delete_transient( 'wc_subscriptions_is_upgrading' );

		header( 'Content-Type: application/json; charset=utf-8' );
		echo json_encode( $results );
		exit();
	}

	/**
	 * Let the site administrator know we are upgrading the database and provide a confirmation is complete.
	 *
	 * This is important to avoid the possibility of a database not upgrading correctly, but the site continuing
	 * to function without any remedy.
	 *
	 * @since 1.2
	 */
	public static function display_database_upgrade_helper() {
		global $woocommerce;


		wp_register_style( 'wcs-upgrade', plugins_url( '/css/wcs-upgrade.css', WC_Subscriptions::$plugin_file ) );
		wp_register_script( 'wcs-upgrade', plugins_url( '/js/wcs-upgrade.js', WC_Subscriptions::$plugin_file ), 'jquery' );

		$script_data = array(
			'hooks_per_request' => self::$upgrade_limit,
			'ajax_url' => admin_url( 'admin-ajax.php' ),
		);

		error_log( '$script_data = ' . print_r( $script_data, true ) );

		wp_localize_script( 'wcs-upgrade', 'wcs_update_script_data', $script_data );

		$subscription_count = WC_Subscriptions::get_total_subscription_count();
		$estimated_duration = ceil( $subscription_count / 500 );

@header( 'Content-Type: ' . get_option( 'html_type' ) . '; charset=' . get_option( 'blog_charset' ) ); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head>
	<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php echo get_option( 'blog_charset' ); ?>" />
	<title><?php _e( 'WooCommerce Subscriptions Update', 'woocommerce-subscriptions' ); ?></title>
	<?php wp_admin_css( 'install', true ); ?>
	<?php wp_admin_css( 'ie', true ); ?>
	<?php wp_print_styles( 'wcs-upgrade' ); ?>
	<?php wp_print_scripts( 'jquery' ); ?>
	<?php wp_print_scripts( 'wcs-upgrade' ); ?>
</head>
<body class="wp-core-ui">
<h1 id="logo"><img alt="WooCommerce Subscriptions" width="325px" height="120px" src="<?php echo plugins_url( 'images/woocommerce_subscriptions_logo.png', WC_Subscriptions::$plugin_file ); ?>" /></h1>
<div id="update-welcome">
	<h2><?php _e( 'Database Update Required', 'woocommerce-subscriptions' ); ?></h2>
	<p><?php _e( 'The WooCommerce Subscriptions plugin has been updated!', 'woocommerce-subscriptions' ); ?></p>
	<p><?php _e( 'Before we send you on your way, we need to update your database to the newest version. If you do not have a recent backup of your site, now is a good time to create one.', 'woocommerce-subscriptions' ); ?></p>
	<p><?php _e( 'The update process may take a little while, so please be patient.', 'woocommerce-subscriptions' ); ?></p>
	<form id="subscriptions-upgrade" method="get" action="<?php echo admin_url( 'admin.php' ); ?>">
		<input type="submit" class="button" value="<?php _e( 'Update Database', 'woocommerce-subscriptions' ); ?>">
	</form>
</div>
<div id="update-messages">
	<h2><?php _e( 'Update in Progress', 'woocommerce-subscriptions' ); ?></h2>
	<p><?php printf( __( 'The full update process for the %s subscriptions on your site will take approximately %s to %s minutes.', 'woocommerce-subscriptions' ), $subscription_count, $estimated_duration, $estimated_duration * 2 ); ?></p>
	<p><?php _e( 'This page will display the results of the process as each batch of subscriptions is updated. No need to refresh or restart the process. Non-administrative users will continue to be able to browse your site without interuption while the update is in progress.', 'woocommerce-subscriptions' ); ?></p>
	<ol>
	</ol>
	<img id="update-ajax-loader" alt="loading..." width="16px" height="16px" src="<?php echo plugins_url( 'images/ajax-loader@2x.gif', WC_Subscriptions::$plugin_file ); ?>" />
</div>
<div id="update-complete">
	<h2><?php _e( 'Update Complete', 'woocommerce-subscriptions' ); ?></h2>
	<p><?php _e( 'Your database has been successfully updated!', 'woocommerce-subscriptions' ); ?></p>
	<p class="step"><a class="button" href="<?php echo esc_url( self::$about_page_url ); ?>"><?php _e( 'Continue', 'woocommerce-subscriptions' ); ?></a></p>
</div>
<div id="update-error">
	<h2><?php _e( 'Update Error', 'woocommerce-subscriptions' ); ?></h2>
	<p><?php _e( 'There was an error with the update. Please refresh the page and try again.', 'woocommerce-subscriptions' ); ?></p>
</div>
</body>
</html>
<?php
	}

	/**
	 * Let the site administrator know we are upgrading the database already to prevent duplicate processes running the
	 * upgrade. Also provides some useful diagnostic information, like how long before the site admin can restart the
	 * upgrade process, and how many subscriptions per request can typically be updated given the amount of memory
	 * allocated to PHP.
	 *
	 * @since 1.4
	 */
	public static function upgrade_in_progress_notice() {

		$upgrade_transient_timeout = get_option( '_transient_timeout_wc_subscriptions_is_upgrading' );

		$time_until_update_allowed = $upgrade_transient_timeout - time();

		// Find out how many subscriptions can be processed before running out of memory on this installation. Subscriptions can process around 2500 with the usual 64M memory
		$memory_limit = ini_get( 'memory_limit' );
		$subscription_before_exhuastion = round( ( 3500 / 250 ) * str_replace( 'M', '', $memory_limit ) );

@header( 'Content-Type: ' . get_option( 'html_type' ) . '; charset=' . get_option( 'blog_charset' ) ); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head>
	<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php echo get_option( 'blog_charset' ); ?>" />
	<title><?php _e( 'WooCommerce Subscriptions Update in Progress', 'woocommerce-subscriptions' ); ?></title>
	<?php wp_admin_css( 'install', true ); ?>
	<?php wp_admin_css( 'ie', true ); ?>
</head>
<body class="wp-core-ui">
<h1 id="logo"><img alt="WooCommerce Subscriptions" width="325px" height="120px" src="<?php echo plugins_url( 'images/woocommerce_subscriptions_logo.png', WC_Subscriptions::$plugin_file ); ?>" /></h1>
<h2><?php _e( 'The Upgrade is in Progress', 'woocommerce-subscriptions' ); ?></h2>
<p><?php _e( 'The WooCommerce Subscriptions plugin is currently running its database upgrade routine.', 'woocommerce-subscriptions' ); ?></p>
<p><?php printf( __( 'If you received a server error and reloaded the page to find this notice, please refresh the page in %s seconds and the upgrade routine will recommence without issues. Subscriptions can update approximately %s subscriptions before exhausting the memory available on your PHP installation (which has %s allocated). It will update approxmiately 750 subscriptions per minute.', 'woocommerce-subscriptions' ), $time_until_update_allowed, $subscription_before_exhuastion, $memory_limit ); ?></p>
<p><?php _e( 'Rest assured, although the update process may take a little while, it is coded to prevent defects, your site is safe and will be up and running again, faster than ever, shortly.', 'woocommerce-subscriptions' ); ?></p>
</body>
</html>
<?php
	die();
	}

	public static function updated_welcome_page() {
		$about_page = add_dashboard_page( __( 'Welcome to WooCommerce Subscriptions 1.5', 'woocommerce-subscriptions' ), __( 'About WooCommerce Subscriptions', 'woocommerce-subscriptions' ), 'manage_options', 'wcs-about', __CLASS__ . '::about_screen' );
		add_action( 'admin_print_styles-'. $about_page, __CLASS__ . '::admin_css' );
		add_action( 'admin_head',  __CLASS__ . '::admin_head' );
	}

	/**
	 * admin_css function.
	 *
	 * @access public
	 * @return void
	 */
	public function admin_css() {
		wp_enqueue_style( 'woocommerce-subscriptions-about', plugins_url( '/css/about.css', WC_Subscriptions::$plugin_file ), array(), self::$active_version );
	}

	/**
	 * Add styles just for this page, and remove dashboard page links.
	 *
	 * @access public
	 * @return void
	 */
	public function admin_head() {
		remove_submenu_page( 'index.php', 'wcs-about' );
		remove_submenu_page( 'index.php', 'wcs-credits' );
		remove_submenu_page( 'index.php', 'wcs-translators' );
	}

	/**
	 * Output the about screen.
	 */
	public function about_screen() {
		$settings_page = admin_url( 'admin.php?page=wc-settings&tab=subscriptions' );
		?>
	<div class="wrap about-wrap">

		<h1><?php _e( 'Welcome to Subscriptions 1.5', 'woocommerce-subscriptions' ); ?></h1>

		<div class="about-text woocommerce-about-text">
			<?php _e( 'Thank you for updating to the latest version! Subscriptions 1.5 is more powerful, scalable, and reliable than ever before. We hope you enjoy it.', 'woocommerce-subscriptions' ); ?>
		</div>

		<div class="wcs-badge"><?php printf( __( 'Version 1.5', 'woocommerce-subscriptions' ), self::$active_version ); ?></div>

		<p class="woocommerce-actions">
			<a href="<?php echo $settings_page; ?>" class="button button-primary"><?php _e( 'Settings', 'woocommerce-subscriptions' ); ?></a>
			<a class="docs button button-primary" href="<?php echo esc_url( apply_filters( 'woocommerce_docs_url', 'http://docs.woothemes.com/documentation/subscriptions/', 'woocommerce-subscriptions' ) ); ?>"><?php _e( 'Docs', 'woocommerce-subscriptions' ); ?></a>
			<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://www.woothemes.com/products/woocommerce-subscriptions/" data-text="I just upgraded to Subsriptions 1.5" data-via="WooThemes" data-size="large" data-hashtags="WooCommerce">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		</p>

		<div class="changelog">
			<h3><?php _e( "Check Out What's New", 'woocommerce-subscriptions' ); ?></h3>

			<div class="feature-section col two-col">
				<div>
					<img src="<?php echo plugins_url( '/images/customer-view-syncd-subscription-monthly.png', WC_Subscriptions::$plugin_file ); ?>" />
				</div>

				<div class="last-feature">
					<h4><?php _e( 'Renewal Synchronisation', 'woocommerce-subscriptions' ); ?></h4>
					<p><?php _e( 'Subscription renewal dates can now be aligned to a specific day of the week, month or year.', 'woocommerce-subscriptions' ); ?></p>
					<p><?php _e( 'If you sell physical goods and want to ship only on certain days, or sell memberships and want to align membership periods to a calendar day, WooCommerce Subscriptions can now work on your schedule.', 'woocommerce-subscriptions' ); ?></p>
					<p><?php printf( __( '%sEnable renewal synchronisation%s now or %slearn more%s about this feature.', 'woocommerce-subscriptions' ), '<a href="' . esc_url( $settings_page ) . '">', '</a>', '<a href="' .  esc_url( 'http://docs.woothemes.com/document/subscriptions/renewal-synchronisation/' ) . '">', '</a>' ); ?></p>
				</div>
			</div>
			<hr/>
			<div class="feature-section col two-col">
				<div>
					<h4><?php _e( 'Mixed Checkout', 'woocommerce-subscriptions' ); ?></h4>
					<p><?php _e( 'Simple, variable and other non-subscription products can now be purchased in the same transaction as a subscription product.', 'woocommerce-subscriptions' ); ?></p>
					<p><?php printf( __( 'This makes it easier for your customers to buy more from your store and soon it will also be possible to offer %sProduct Bundles%s & %sComposite Products%s which include a subscription.', 'woocommerce-subscriptions' ), '<a href="' . esc_url( 'http://www.woothemes.com/products/product-bundles/' ) . '">', '</a>', '<a href="' . esc_url( 'http://www.woothemes.com/products/composite-products/' ) . '">', '</a>' ); ?></p>
					<p><?php printf( __( '%sEnable mixed checkout%s now under the %sMiscellaneous%s settings section.', 'woocommerce-subscriptions' ), '<a href="' . esc_url( $settings_page ) . '">', '</a>', '<strong>', '</strong>' ); ?></p>
				</div>

				<div class="last-feature">
					<img src="<?php echo plugins_url( '/images/mixed-checkout.png', WC_Subscriptions::$plugin_file ); ?>" />
				</div>
			</div>
			<hr/>
			<div class="feature-section col two-col">
				<div>
					<img src="<?php echo plugins_url( '/images/subscription-quantities.png', WC_Subscriptions::$plugin_file ); ?>" />
				</div>

				<div class="last-feature">
					<h4><?php _e( 'Subscription Quantities', 'woocommerce-subscriptions' ); ?></h4>
					<p><?php _e( 'Your customers no longer need to purchase a subscription multiple times to access multiple quantities of the same product.', 'woocommerce-subscriptions' ); ?></p>
					<p><?php printf( __( 'For any subscription product not marked as %sSold Individually%s on the %sInventory%s tab%s of the %sEdit Product%s screen, your customers can now choose to purchase multiple quantities in the one transaction.', 'woocommerce-subscriptions' ), '<strong>', '</strong>', '<strong><a href="' . esc_url( 'http://docs.woothemes.com/document/managing-products/#inventory-tab' ) . '">', '</strong>', '</a>', '<strong>', '</strong>' ); ?></p>
					<p><?php printf( __( 'Your existing subscription products have been automatically set as %sSold Individually%s, so nothing will change for existing products, unless you want it to. Edit %ssubscription products%s.', 'woocommerce-subscriptions' ), '<strong>', '</strong>', '<a href="' . admin_url( 'edit.php?post_type=product&product_type=subscription' ) . '">', '</a>' ); ?></p>
				</div>
			</div>
		</div>
		<hr/>
		<div class="changelog">

			<div class="feature-section col three-col">

				<div>
					<img src="<?php echo plugins_url( '/images/responsive-subscriptions.png', WC_Subscriptions::$plugin_file ); ?>" />
					<h4><?php _e( 'Responsive Subscriptions Table', 'woocommerce-subscriptions' ); ?></h4>
					<p><?php printf( __( 'The default template for the %sMy Subscriptions%s table is now responsive to make it easy for your customers to view and manage their subscriptions on any device.', 'woocommerce-subscriptions' ), '<strong><a href="' . esc_url( 'http://docs.woothemes.com/document/subscriptions/customers-view/#section-1' ) . '">', '</a></strong>' ); ?></p>
				</div>

				<div>
					<img src="<?php echo plugins_url( '/images/subscription-switch-customer-email.png', WC_Subscriptions::$plugin_file ); ?>" />
					<h4><?php _e( 'Subscription Switch Emails', 'woocommerce-subscriptions' ); ?></h4>
					<p><?php printf( __( 'Subscriptions now sends two new emails when a customer upgrades or downgrades her subscription. Enable, disable or customise these emails on the %sEmail Settings%s screen.', 'woocommerce-subscriptions' ), '<strong><a href="' . admin_url( 'admin.php?page=wc-settings&tab=email&section=wcs_email_completed_switch_order' ) . '">', '</a></strong>' ); ?></p>
				</div>

				<div class="last-feature">
					<img src="<?php echo plugins_url( '/images/woocommerce-points-and-rewards-points-log.png', WC_Subscriptions::$plugin_file ); ?>" />
					<h4><?php _e( 'Points & Rewards', 'woocommerce-subscriptions' ); ?></h4>
					<p><?php printf( __( 'Support for the %sPoints & Rewards extension%s: points will now be rewarded for each subscription renewal.', 'woocommerce-subscriptions' ), '<a href="' . esc_url( 'http://www.woothemes.com/products/woocommerce-points-and-rewards/' ) . '">', '</a>' ); ?></p>
				</div>

			</div>
		</div>
		<hr/>
		<div class="changelog under-the-hood">

			<h3><?php _e( 'Under the Hood - New Scheduling System', 'woocommerce-subscriptions' ); ?></h3>
			<p><?php _e( 'Subscriptions 1.5 also introduces a completely new scheduling system - Action Scheduler.', 'woocommerce-subscriptions' ); ?></p>

			<div class="feature-section col three-col">
				<div>
					<h4><?php _e( 'Built to Sync', 'woocommerce-subscriptions' ); ?></h4>
					<p><?php _e( 'Introducing the new subscription synchronisation feature also introduces a new technical challenge - thousands of renewals may be scheduled for the same time.', 'woocommerce-subscriptions' ); ?></p>
					<p><?php _e( 'WordPress\'s scheduling system was not made to handle queues like that, but the new Action Scheduler is designed to process queues with thousands of renewals so you can sync subscriptions with confidence.', 'woocommerce-subscriptions' ); ?></p>
				</div>
				<div>
					<h4><?php _e( 'Built to Debug', 'woocommerce-subscriptions' ); ?></h4>
					<p><?php _e( 'When things go wrong, the more information available, the easier it is to diagnose and find a fix. Traditionally, a subscription renewal problem was tricky to diagnose because renewal happened in the background.', 'woocommerce-subscriptions' ); ?></p>
					<p><?php _e( 'Action Scheduler now logs important events around renewals and makes this and other important information available through a specially designed administration interface.', 'woocommerce-subscriptions' ); ?></p>
				</div>
				<div class="last-feature">
					<h4><?php _e( 'Built to Scale', 'woocommerce-subscriptions' ); ?></h4>
					<p><?php _e( 'The new Action Scheduler uses battle tested WordPress core functionality to ensure your site can scale its storage of scheduled subscription events, like an expiration date or renewal date, to handle thousands or even hundreds of thousands of subscriptions.', 'woocommerce-subscriptions' ); ?></p>
					<p><?php _e( 'We want stores of all sizes to be able to rely on WooCommerce Subscriptions.', 'woocommerce-subscriptions' ); ?></p>
				</div>
		</div>
		<hr/>
		<div class="return-to-dashboard">
			<a href="<?php echo esc_url( $settings_page ); ?>"><?php _e( 'Go to WooCommerce Subscriptions Settings', 'woocommerce-subscriptions' ); ?></a>
		</div>
	</div>
		<?php
	}
}
add_action( 'plugins_loaded', 'WC_Subscriptions_Upgrader::init', 10 );
