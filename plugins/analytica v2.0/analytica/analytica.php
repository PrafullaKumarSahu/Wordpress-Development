<?php
/**
* Plugin Name: Analytica
* Plugin Author: Indibits
* Plugin URI: http://indibits.com/plugins/analytica
*/
require_once realpath(dirname(__FILE__) . '/google-api-php-client/src/Google/autoload.php');

session_start();
global $analytica_db_version;
$analytica_db_version = '1.0';
class Analytica_Analytic
{
    /**
    * Holds the values to be used in the fields callbacks
    */
    private $options;
    
    /**
    * Start up
    */
    public function __construct()
    {
		register_activation_hook( __FILE__, array( $this, 'analytica_install' ) );
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );	
    }

    /**
    * Add options page
    */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_utility_page(
            'Analytica', 
            'Analytica', 
            'manage_options', 
            'analytica-admin-settings', 
            array( $this, 'create_admin_page' ), 'dashicons-chart-area'
        );
    }

    /**
    * Options page callback
    */
    public function create_admin_page()
    {		
	    global $wpdb;
        // Set class property
        $this->options = get_option( 'analytica-option-name' );
        ?>
        <div class="wrap">
            <h2>Analytica Reports</h2> 		
		<?php
	   // Check if there is a logout request in the URL.
		if (isset($_REQUEST['logout'])) {
			// Clear the access token from the session storage.
			unset($_SESSION['access_token']);
		}
		/*************************/
		$client = new Google_Client();
		$client->setAuthConfigFile(plugin_dir_url( __FILE__ ) . '/client_secrets.json');
		$client->setRedirectUri( site_url() . '/wp-admin/admin.php?page=analytica-admin-settings');
		$client->addScope(Google_Service_Analytics::ANALYTICS_READONLY);
		$client->setIncludeGrantedScopes(true);
		$client->setAccessType('offline');
		if (!isset($_SESSION['access_token']) && isset( $_GET['code'] )) {
		   
			if($client->isAccessTokenExpired()){
				$client->authenticate($_GET['code']);
				$accessToken = $client->getAccessToken();
				$refreshToken = $client->getRefreshToken();
			
				//global $wpdb;
				$table_name = $wpdb->prefix . 'analyticaAnalytics';
				$wpdb->insert( 
					$table_name,
					array( 
					    'time' => current_time( 'mysql' ),
						//'authenication_code' => $this->options['authenication_code'], 
						'authenication_code' => $_GET['code'], 
						'accessToken' =>  $accessToken,
						'refreshToken' => $refreshToken,
					)
				);
			} 
		} else {
			//$resultset = $wpdb->get_row( 'SELECT `refreshToken` FROM ' . $wpdb->prefix . 'analyticaAnalytics WHERE authenication_code ="' . $this->options["authenication_code"] . '"', ARRAY_A );
			$resultset = $wpdb->get_row( 'SELECT `refreshToken` FROM ' . $wpdb->prefix . 'analyticaAnalytics ORDER BY ID DESC LIMIT 1', ARRAY_A );
			
			$refreshToken = $resultset['refreshToken'];
			//echo $refreshToken;
			if ($client->isAccessTokenExpired()) {
				if( $refreshToken ){
					$client->refreshToken( $refreshToken );
					$accessToken = $client->getAccessToken();
					$table_name = $wpdb->prefix . 'analyticaAnalytics';
					$wpdb->insert( 
						$table_name,
						array( 
							'time' => current_time( 'mysql' ),
							'authenication_code' => $this->options['authenication_code'], 
							'accessToken' =>  $accessToken,
							'refreshToken' => $refreshToken,
						)
					);
				}
			}
		}
		$auth_url = $client->createAuthUrl();
		?>
		 <a href='<?php echo $auth_url; ?>'  id="loginText">Get Authentication </a>
		<?php
		if( isset($accessToken) ){
			$_SESSION['access_token'] = $accessToken ? $accessToken : $refreshToken;
			$client->setAccessToken($_SESSION['access_token']);
			// Create an authorized analytics service object.
			$analytics = new Google_Service_Analytics($client);
				
			// Get the first view (profile) id for the authorized user.
			$profile = $this->getFirstProfileId($analytics);

			// Get the results from the Core Reporting API and print the results.
			$this->results = $this->getResults($analytics, $profile);
		}
		?>
	 
        </div>
		<div class="analytica-report">
		    <?php
			    if( isset( $this->results ) ){
                    $this->printDataTable($this->results);
				}
			?>
		</div>
        <?php
    }
	public function getFirstprofileId(&$analytics) {
	  // Get the user's first view (profile) ID.
	  // Get the list of accounts for the authorized user.
	  $accounts = $analytics->management_accounts->listManagementAccounts();

	   if (count($accounts->getItems()) > 0) {
		$items = $accounts->getItems();
		$firstAccountId = $items[0]->getId();

		// Get the list of properties for the authorized user.
		$properties = $analytics->management_webproperties
			->listManagementWebproperties($firstAccountId);

		if (count($properties->getItems()) > 0) {
		  $items = $properties->getItems();
		  $firstPropertyId = $items[0]->getId();

		  // Get the list of views (profiles) for the authorized user.
		  $profiles = $analytics->management_profiles
			  ->listManagementProfiles($firstAccountId, $firstPropertyId);

		  if (count($profiles->getItems()) > 0) {
			$items = $profiles->getItems();

			// Return the first view (profile) ID.
			return $items[0]->getId();

		  } else {
			throw new Exception('No views (profiles) found for this user.');
		  }
		} else {
		  throw new Exception('No properties found for this user.');
		}
	  } else {
		throw new Exception('No accounts found for this user.');
	  }
	}

	public function getResults(&$analytics, $profileId) {
	  // Calls the Core Reporting API and queries for the number of sessions
	  // for the last seven days.
	  return $analytics->data_ga->get(
		  'ga:' . $profileId,
		  '7daysAgo',
		  'today',
		  'ga:pageviews',
		 
		  array('dimensions' => 'ga:pagePath') );
	}

	public function printDataTable(&$results) {

	  if (count($this->results->getRows()) > 0) {
		$table = '';
		$table .= '<table>';

		// Print headers.
		$table .= '<tr>';

		foreach ($this->results->getColumnHeaders() as $header) {
		  $table .= '<th>' . $header->name . '</th>';
		}
		$table .= '</tr>';

		// Print table rows.
		foreach ($this->results->getRows() as $row) {
		  $table .= '<tr>';
			foreach ($row as $cell) {
			  $table .= '<td>'
					 . htmlspecialchars($cell, ENT_NOQUOTES)
					 . '</td>';
			}
		  $table .= '</tr>';
		}
		$table .= '</table>';

	  } else {
		$table .= '<p>No Results Found.</p>';
	  }
	  print $table;
	}
	
	public function analytica_install(){

		global $wpdb;
		global $analytica_db_version;
		
		$table_name = $wpdb->prefix . 'analyticaAnalytics';
		
		$charset_collate = $wpdb->get_charset_collate();
		
		$sql = "CREATE TABLE IF NOT EXISTS $table_name(
		    id mediumint(9)  NOT NULL  AUTO_INCREMENT,
			time datetime  DEFAULT '0000-00-00 00:00:00'  NOT NULL,
			authenication_code varchar(1000)  NOT NULL,
			accessToken varchar(2000)  NOT NULL,
			refreshToken varchar(2000)  NOT NULL,
			UNIQUE KEY id(id)
		)$charset_collate;";
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		
		add_option( 'analytica_db_version', $analytica_db_version );
	}
	
	public static function analytica_uninstall()
    {
		header('Location: /uninstall.php');   
    }

}

if( is_admin() ){
    $analytica = new Analytica_Analytic();
	register_uninstall_hook(__FILE__, array( 'Analytica_Analytic', 'analytica_uninstall' ));
}