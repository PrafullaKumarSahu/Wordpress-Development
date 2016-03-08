<?php namespace Analytica\Controllers;

use Herbert\Framework\Models\Option;
use Herbert\Framework\RedirectResponse;
use Herbert\Framework\Http;
use Herbert\Framework\Enqueue;
use Herbert\Framework\Notifier;
use \Google_Client;
use \Google_Service_Analytics;
use Analytica\Helper;

    

class AnalyticaController {

	public $authenticationCode = 'analyticaAccessToken';
	public $settings = 'analyticaAdminSetting';
	//protected $options = ['time', 'accessToken', 'refreshToken'];
	protected $settingOption = ['profileId', 'startDate', 'endDate', 'pageViews', 'order', 'excludeDraftPost', 'excludeNotFoundPost', 'numbersOfItemsInPage'];
	
    public function index()
    {
		/**
		* Create the client object and set the authorization configuration from the
		* client_secrets.js downloaded from developer console
		*/
		$google_client = new Google_Client();
		$google_client->setAuthConfigFile(Helper::assetUrl('/client_secrets.json'));
		$google_client->setRedirectUri('urn:ietf:wg:oauth:2.0:oob');
		$google_client->addScope( Google_Service_Analytics::ANALYTICS_READONLY );
		$google_client->setIncludeGrantedScopes( true );
		$google_client->setAccessType( 'offline' );
        $authUrl = $google_client->createAuthUrl();
		
		/**
		* If the user has already authorized this app then get the access
		* token else redirect to ask the user to authorize access to Google Analytics
		*/ 
		
	
			if( get_option($this->authenticationCode) != '' ){
				$resultset = json_decode(get_option($this->authenticationCode));
				
				if( $google_client->isAccessTokenExpired() ){
					if( isset( $resultset ) ){
						$refreshToken = $resultset->refreshToken;
						$google_client->refreshToken($refreshToken);
						$accessToken = $google_client->getAccessToken();
						update_option( $this->authenticationCode, json_encode(array('time' => current_time( 'mysql' ),'accessToken' =>  $accessToken, 'refreshToken' => $refreshToken )) );
					} else {
						$google_client->authenticate( $_POST['authorizationCode'] );
						$accessToken = $google_client->getAccessToken();
						$refreshToken = $google_client->getRefreshToken();
						update_option( $this->authenticationCode, json_encode(array('time' => current_time( 'mysql' ),'accessToken' =>  $accessToken, 'refreshToken' => $refreshToken )) );
					}
				}
			$authUrl = $google_client->createAuthUrl();
		} else {
				if( isset( $_POST['authorizationCode'] ) && $_POST['authorizationCode'] ){			
					$google_client->authenticate( $_POST['authorizationCode'] );
					$accessToken = $google_client->getAccessToken();
					$refreshToken = $google_client->getRefreshToken();
					update_option( $this->authenticationCode, json_encode(array('time' => current_time( 'mysql' ),'accessToken' =>  $accessToken, 'refreshToken' => $refreshToken )) );
				}
			    $authUrl = $google_client->createAuthUrl();
		}
		?>
		<script>
			var authUrl = '<?php echo $authUrl; ?>';
			//console.log(authUrl);
		</script>
		<?php
		if( isset($accessToken) ){
			$_SESSION['accessToken'] = $accessToken ? $accessToken : $refreshToken;
			$google_client->setAccessToken($_SESSION['accessToken']);
			$analytics = new Google_Service_Analytics($google_client);
			$profile = json_decode(get_option($this->settings))->profileId ?  json_decode(get_option($this->settings))->profileId : $this->getFirstprofileId($analytics);
			$results = $this->getResults($analytics, $profile);
		}
		//echo var_dump($google_client, true);
		//echo var_dump($results, true);
		//echo var_dump(json_decode(get_option($this->settings)) );
		//exit;
        if( isset( json_decode(get_option($this->settings))->numbersOfItemsInPage ) && json_decode(get_option($this->settings))->numbersOfItemsInPage ){
			$maxResults = json_decode(get_option($this->settings))->numbersOfItemsInPage;
		} else {
			$maxResults =  10;
		}
		if(isset($results)){
			$analyticaAdminSetting['profiles'] = $this->getProfiles($results);
			$totalsForAllResults = $results->totalsForAllResults;
			$numberOfTotalPages = ceil($results->getTotalResults()/$maxResults) ;
			$resultsHeader = $results->getColumnHeaders();
			$resultRows = $this->getRows($results);
			$countresults = $results->getTotalResults();
		} 
	    $analyticaAdminSetting['startDate'] = json_decode(get_option($this->settings))->startDate ? json_decode(get_option($this->settings))->startDate : date("m/d/Y", strtotime("-1 year"));
		
		$analyticaAdminSetting['endDate'] = json_decode(get_option($this->settings))->endDate ? json_decode(get_option($this->settings))->endDate : date("m/d/Y");
		
		$analyticaAdminSetting['pageViews'] = json_decode(get_option($this->settings))->pageViews ? json_decode(get_option($this->settings))->pageViews : 10;
		
		$analyticaAdminSetting['order'] = json_decode(get_option($this->settings))->order ? json_decode(get_option($this->settings))->order : 'ascending';
		
		$analyticaAdminSetting['excludeDraftPost'] = json_decode(get_option($this->settings))->excludeDraftPost ? json_decode(get_option($this->settings))->excludeDraftPost : false;
		
		$analyticaAdminSetting['excludeNotFoundPost'] = json_decode(get_option($this->settings))->excludeNotFoundPost ? json_decode(get_option($this->settings))->excludeNotFoundPost : false;
		
	 	$analyticaAdminSetting['numbersOfItemsInPage'] = $this->noOfItemsInPage();
		
		//update_option($this->analyticaAdminSetting, $analyticaAdminSetting );
		
		//var_dump (Option::getProfiles($results));
		
		
		$totalsForAllResults['ga:avgTimeOnPage'] = sprintf('%02d:%02d:%02d', (round($results->totalsForAllResults['ga:avgTimeOnPage'])/3600),(round($results->totalsForAllResults['ga:avgTimeOnPage'])/60%60), round($results->totalsForAllResults['ga:avgTimeOnPage'])%60);
		
	
		//$numberOfTotalPages  = ceil(count($results['rows'])/$maxResults);
		
		return view('@Analytica/admin/index.twig', [
			'title'   => 'Analytica Reports',
			'analyticaAdminSetting' => $analyticaAdminSetting,
			'resultsHeader' => $resultsHeader,
			'totalsForAllResults' => $totalsForAllResults,
			'results' => $resultRows,
			//'countresults' => count($results['rows']),
			'countresults' => $countresults,
			'numberOfTotalPages' =>$numberOfTotalPages,
			'authUrl' => $authUrl
		]);
	
	}
	
	protected function validate($inputs)
	{
		foreach( $inputs as $input )
		{
			if( empty($input) )
			{
				//Notifier::error('Please compelete all fields', true);
				return false;
			}
		}
		return true;
	}
	
	protected function check($inputs)
	{
		
		if( $inputs['authorizationCode'] ){
		    $response = sanitize_text_field( $inputs['authorizationCode'] );
		}
		
		if( $inputs['analyticaAdminSetting'] ) {
			$response = $this->sanitize( $inputs['analyticaAdminSetting'] );
		}
		
		if($response)
		{
			//return true;
			return $response;
		}
		return false;
		//return $response;
	}
		
	public function save(Http $http){
		
		if($http->only($this->settings)){
			$inputs = $http->only($this->settings);
		}
		if( ! $this->validate($inputs) || ! $this->check($inputs) )
		{
			return redirect_response( panel_url( 'Analytica::mainPanel' ) )->with( '__form_data', $inputs );
		}
		
		update_option( $this->settings, json_encode($inputs['analyticaAdminSetting']) );
		
		return redirect_response( panel_url( 'Analytica::mainPanel' ) );
	}
			
	/**
	* Get the user's first view (profile) ID.
	*/
	public static function getFirstprofileId(&$analytics)
	{
	   
	  // Get the list of accounts for the authorized user.
	  $accounts = $analytics->management_accounts->listManagementAccounts();

	   if (count($accounts->getItems()) > 0) 
	   {
		$items = $accounts->getItems();
		$firstAccountId = $items[0]->getId();

		// Get the list of properties for the authorized user.
		$properties = $analytics->management_webproperties
			->listManagementWebproperties($firstAccountId);

		if (count($properties->getItems()) > 0)
  	    {
		  $items = $properties->getItems();
		  $firstPropertyId = $items[0]->getId();

		  // Get the list of views (profiles) for the authorized user.
		  $profiles = $analytics->management_profiles
			  ->listManagementProfiles($firstAccountId, $firstPropertyId);

		  if (count($profiles->getItems()) > 0)
		 {
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
	
	public function getResults( &$analytics, &$profileId, &$page = 1 ){
		
		if( isset( json_decode(get_option($this->settings) )->startDate ) && preg_match('~[0-9]~', json_decode(get_option($this->settings) )->startDate ) ){
			$startDate = date( 'Y-m-d', strtotime( json_decode(get_option($this->settings) )->startDate) );
		} else {
			$startDate = date('Y-m-d', strtotime("-1 year"));
		}

		if( isset( json_decode(get_option($this->settings) )->endDate ) && preg_match('~[0-9]~', json_decode(get_option($this->settings) )->endDate ) ){
			$endDate = date( 'Y-m-d', strtotime( json_decode(get_option($this->settings) )->endDate ) );
		} else {
			$endDate = date('Y-m-d');
		}
		
		if( isset( json_decode(get_option($this->settings) )->order ) && json_decode(get_option($this->settings) )->order ==  'ascending' ){
			$order = 'ga:pageViews';
		} else {
			$order = '-ga:pageViews';
		}
		
		if( isset( json_decode(get_option($this->settings) )->pageViews ) &&  json_decode(get_option($this->settings) )->pageViews ){
			$filter = 'ga:pageViews<' . json_decode(get_option($this->settings) )->pageViews;
		} else {
			$filter = 'ga:pageViews<10';
		}
		
		if( isset( json_decode(get_option($this->settings) )->numbersOfItemsInPage ) && json_decode(get_option($this->settings) )->numbersOfItemsInPage ){
			$maxResults = json_decode(get_option($this->settings) )->numbersOfItemsInPage;
		} else {
			$maxResults =  10;
		}
		$page = 2;
		return $analytics->data_ga->get(
		    'ga:' . $profileId,
		    $startDate,
		    $endDate,
		    'ga:pageviews,ga:uniquePageviews,ga:avgTimeOnPage,ga:bounceRate,ga:exitRate',
		    //array('dimensions' => 'ga:PagePath,ga:PagePathLevel1'));
		    //array('dimensions' => 'ga:PagePath,ga:PagePathLevel1', 'sort' => $order, 'filters' => $filter ) );
			
			array('dimensions' => 'ga:PagePath,ga:PagePathLevel1', 'sort' => $order, 'filters' => $filter, 'start-index' => 1 + ($page-1)*$maxResults, 'max-results' =>$maxResults ) );
			
			//array('dimensions' => 'ga:PagePath,ga:PagePathLevel1', 'sort' => $order, 'filters' => $filter, 'start-index' => 1, 'max-results' =>10000 ) );
			
			//array('dimensions' => 'ga:PagePathLevel1', 'sort' => $order, 'filters' => $filter ) );
	}
	
	public function getProfiles(){
		$google_client = new Google_Client();
		if( $_SESSION['accessToken'] ){
			$google_client->setAccessToken($_SESSION['accessToken']);
		}
		$analytics = new Google_Service_Analytics($google_client);

		$accounts = $analytics->management_accountSummaries->listManagementAccountSummaries();
		
		$profiles = SELF::parse_opt_groups(SELF::format_profile_call($accounts->getItems()));

		$html = '<select id="profile_id" name="analyticaAdminSetting[profileId]" value="' . json_decode(get_option($this->settings))->profileId . '">' .  '<option value="">Choose a Profile</option>';
	
		foreach($profiles as $profile):
		   foreach($profile as $val) {
			    if(json_decode(get_option($this->settings))->profileId == $val[0]['id'] ){
				   $html .= '<option value="'. $val[0]['id'] . '" selected >' . $val[0]['name'] . '</option>';
				} else {
				    $html .= '<option value="'. $val[0]['id'] . '">' . $val[0]['name'] . '</option>';
				}
			}
		endforeach;

		$html .= '</select>';
		//$html = json_decode(get_option(  $this->settings ), true);
		//var_dump( json_decode(get_option($this->settings))->profileId);
		//echo var_dump($html);
        return $html;
	}
	
	public static function format_profile_call( &$response ) {
        if ( isset( $response) ){
			$accounts = array();
			foreach ( $response as $item ) {
			
				// Check if webProperties is set
				if ( isset( $item['webProperties'] ) ) {
					$profiles = array();

					foreach ( $item['webProperties'] as $property_key => $property ) {
						$profiles[ $property_key ] = array(
							'id'    => $property['id'],
							'name'  => $property['name'],
							'items' => array(),
						);
			   
						// Check if profiles is set
						if ( isset( $property['profiles'] ) ) {
							foreach ( $property['profiles'] as $key => $profile ) {
								$profiles[ $property_key ]['items'][ $key ] = array_merge(
									get_object_vars($profile),
									array(
										'name'    => $profile['name'] . ' (' . $property['id'] . ')',
										'ua_code' => $property['id'],
									)
								);
							}
						}
					}

					$accounts[ $item['id'] ] = array(
						'id'          => $item['id'],
						'ua_code'     => $property['id'],
						'parent_name' => $item['name'],
						'items'       => $profiles,
					);

				}
			}
			return $accounts;
		} 
		return false;
	}
	
	public static function parse_opt_groups(&$values){
		$opt_groups = array();
		foreach( $values as $key=>$value ){
			foreach( $value['items'] as $subitem ){
				$opt_groups[$subitem['name']]['items'] = $subitem['items'];
			}
		}
		return $opt_groups;
	}
	
	public function sanitize( &$input ){
		/* var_dump($input);
		var_dump($input['startDate']);
		exit; */
		$newInput = array();

        if( isset( $input['profileId'] ) ){
            $newInput['profileId'] = sanitize_text_field( $input['profileId'] );
	    } 
		if( isset( $input['startDate'] ) ){
			$input['startDate'] = date('Y-m-d',strtotime($input['startDate']));
		    $newInput['startDate'] = preg_replace("([^0-9/])", "", $input['startDate']);
			
			
		}
		if( isset( $input['endDate'] ) ){
			$input['endDate'] = date('Y-m-d',strtotime($input['endDate']));
		    $newInput['endDate'] = preg_replace("([^0-9/])", "", $input['endDate']); 
		}
		if( isset( $input['pageViews'] ) ){
		    $newInput['pageViews'] = filter_var($input['pageViews'], FILTER_SANITIZE_NUMBER_INT);
		}
		if( isset( $input['order'] ) ){
			$newInput['order'] = sanitize_text_field( $input['order'] );
		}
		if( isset( $input['excludeDraftPost'] ) ){
			$newInput['excludeDraftPost'] = sanitize_text_field( $input['excludeDraftPost'] );
		}
		if( isset( $input['excludeNotFound'] ) ){
			$newInput['excludeNotFound'] = sanitize_text_field( $input['excludeNotFound'] );
		}
		if( isset( $input['numberOfItemsInPage'] ) ){
			$newInput['numberOfItemsInPage'] = sanitize_text_field( $input['numberOfItemsInPage'] );
		}
		
        return $newInput;
	}
	
	public function getRows(&$results, &$page = 1){
		//var_dump($results->getRows());
		
		$i = 0;
		$newResults = array();
		$maxResults = json_decode(get_option($this->settings))->numbersOfItemsInPage ? json_decode(get_option($this->settings))->numbersOfItemsInPage : 10;
		$index = 0 + ($page-1)*$maxResults;
		
		if( count( $results->getRows() ) > 0 ){
			//for( $j = $index; $j < ($page*$maxResults); $j++ ){
			for( $j = 0; $j < $maxResults; $j++ ){
				foreach($results->getRows()[$j] as $key=>$cell):
					switch($key){
						case 0:
							$postId = url_to_postid( site_url().htmlspecialchars($cell, ENT_NOQUOTES) );
							if( get_post_status( $postId ) == 'publish' )
							{
								$status = 'Published';
							}elseif( get_post_status( $postId ) == 'draft' ){
								$status = 'Draft';
							}elseif( get_post_status( $postId ) == 'pending' ){
								$status = 'Pending Preview';
							}elseif( get_post_status( $postId ) == 'private' ){
								$status = 'Private';
							}else{
								$status = get_post_status($postId);
							}
							if( $postId == '' ){
								//$postTitle = get_headers(site_url() . htmlspecialchars($cell, ENT_NOQUOTES))[0];
								$postTitle = 'NotFound';
								$newResults[$i][0] = $postTitle;
							} else {
								$postTitle = '<strong><span class="post' . $postId . '"><a href="'. get_edit_post_link($postId) .'">' . get_the_title($postId) . '</a></span> &nbsp;&mdash;&nbsp;  <span class="status">' . $status . '</span></strong><div class="row-actions"><span class="edit"><a href="'. get_edit_post_link($postId) .'">Edit</a>&#124;</span><span class="draft"><a class="todraft"  id="draft'. $postId .'" onClick="changeStatus(' . $postId . ')" title="draft" href="javascript:void(0)" title="draft" rel="permalink">Draft</a>&#124;</span><span class="trash"><a class="submitdelete" title="Move this item to the Trash" href="'. get_delete_post_link( $postId, '', false ) .'">Trash</a>&#124;</span><span class="view"><a target="blank" href="'. get_permalink($postId) .'" title="View “test2”" rel="permalink">View</a></span></div>';		
								$newResults[$i][0] = $postTitle;
							}
							break;
						case 1:
							$newResults[$i][1] =  trim(htmlspecialchars($cell, ENT_NOQUOTES), '/');
							break;
						case 2:
							$newResults[$i][2] = htmlspecialchars($cell, ENT_NOQUOTES);
							break;
						case 3:
							$newResults[$i][3] = htmlspecialchars($cell, ENT_NOQUOTES);
							break;
						case 4:
							$newResults[$i][4] = sprintf('%02d:%02d:%02d', (round((int)htmlspecialchars($cell, ENT_NOQUOTES))/3600),(round((int)htmlspecialchars($cell, ENT_NOQUOTES))/60%60), round((int)htmlspecialchars($cell, ENT_NOQUOTES))%60);
							break;
						case 5:
							$newResults[$i][5] = round(floatval(htmlspecialchars($cell, ENT_NOQUOTES)), 2);
							break;
						case 6:
							$newResults[$i][6] = round(floatval(htmlspecialchars($cell, ENT_NOQUOTES)), 2);
							break;
						default:
							break;
					}
				endforeach;
				$i++;
			}
		}
		$arr = array_values($newResults);
		
		return $arr;
	}
	
	public function postStatusChange(){
		//global $wpdb;	
		
        if( isset( $_POST['selected_posts'] ) ){
			$selectedPosts = $_POST['selected_posts'];
			$draftPosts = array();
			foreach( $selectedPosts as $selectedPost ){
				$myPost = array(
					'ID' => trim( $selectedPost, 'post' ),
					'post_status' => 'draft',
				);
				//Update the post into the database
				$draftPosts[] = wp_update_post( $myPost );
			}
			
			if( isset( $draftPosts ) ){
				global $post;
				$slugs = array();
				foreach( $draftPosts as $draftPost ){
					$post = get_post( $draftPost );
					$slugs[$draftPost] = $post->post_name;
				}
				echo json_encode($slugs);
			}
			exit;
		} else{
		    echo null;
			exit;
		}
	}
	public function noOfItemsInPage(){
		
		$noOfPages = array( 10, 25, 50, 100, 500, 1000 );
		$html = '';
		$html .= '<select id="numbersOfItemsInPage" name="analyticaAdminSetting[numbersOfItemsInPage]" class="" value="">';
		foreach( $noOfPages as $page ){
			if( $page == json_decode(get_option($this->settings))->numbersOfItemsInPage )
		        $html .= '<option value="' . $page . '" selected>' . $page . '</option>';
			else
				$html .= '<option value="' . $page . '">' . $page . '</option>';
		}
		$html .='</select>';
		
		return $html;
	}
	
	public function nextPage(){
		
		if( isset( $_POST['page_number']  ) ){
		    //echo $_POST['page_number'];
			$google_client = new Google_Client();
			$google_client->setAccessToken( $_SESSION['accessToken'] );
			$analytics = new Google_Service_Analytics( $google_client );
			$profile =  json_decode(get_option($this->settings))->profileId ? json_decode(get_option($this->settings))->profileId : $this->getFirstProfileId($analytics);
			$page = $_POST['page_number'];
			$results = $this->getResults($analytics, $profile, $page);
			$results = $this->getRows( $results, $page );
			echo json_encode($results);
		}
		exit;
	}
}
