<?php

/*
 * this class should be used to work with the public side of wordpress
 */
class Dahm_Public{
    
    //general class properties
    protected static $instance = null;
    private $shared = null;
    
    private function __construct() {
        
        //assign an instance of the plugin info
        $this->shared = Dahm_Shared::get_instance();
        
        //write in front-end head
        add_action('wp_head', array($this, 'set_hreflang'));

        //write in the get_footer hook
        add_action( 'get_footer', array($this, 'generate_log'));

        //enqueue styles
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

	    /*
	     * Add custom routes to the Rest API
	     */
	    add_action( 'rest_api_init', array($this, 'rest_api_register_route'));
        
    }
    
    /*
     * create an instance of this class
     */
    public static function get_instance() {

        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
        
    }

    /*
     * Write the connections in the 'head' section of the page
     */
    public function set_hreflang(){

        //retrive a numeric array with the connections
        $hreflang_output = $this->shared->generate_hreflang_output();

        //echo the connections in the head of the document
        if( $hreflang_output != false ){
            foreach ($hreflang_output as $single_connection) {
                echo $single_connection;
            }
        }

    }

    /*
     * Write the log with the connections
     */
    public function generate_log(){

        //don't show the log if the current user has no edit_posts capabilities or if the log in not enabled
        if( !current_user_can(get_option($this->shared->get('slug') . "_connections_menu_capability")) or ( intval(get_option("da_hm_show_log"), 10) != 1 ) ){ return; }

        //retrive a numeric array with the connections
        $hreflang_output = $this->shared->generate_hreflang_output();

        //echo the connections in the head of the document
        if( $hreflang_output !== false ){ ?>

            <div id="da-hm-log-container">
                <p id="da-hm-log-heading" ><?php _e('The following lines have been added in the HEAD section of this page', 'dahm'); ?>:</p>
                <?php
                foreach ($hreflang_output as $key => $single_connection) {
                    echo "<p>" . esc_attr( $single_connection ) . "</p>";
                }
                ?>
            </div>

            <?php
        }

    }

    //enqueue styles
    public function enqueue_styles(){

        //enqueue the style used to show the log if the current user has the edit_posts capability and if the log is enabled
        if( current_user_can(get_option($this->shared->get('slug') . "_connections_menu_capability")) and ( intval(get_option("da_hm_show_log"), 10) == 1 ) ){
            wp_enqueue_style($this->shared->get('slug') . '-general', $this->shared->get('url') . 'public/assets/css/general.css', array(), $this->shared->get('ver'));
        }

    }

	/*
	 * Add custom routes to the Rest API
	 */
	function rest_api_register_route(){

		//Add the GET 'daext-autolinks-manager/v1/options' endpoint to the Rest API
		register_rest_route(
			'daext-hreflang-manager/v1', '/post/(?P<id>\d+)', array(
				'methods'  => 'GET',
				'callback' => array($this, 'rest_api_daext_hreflang_manager_read_connections_callback'),
			)
		);

		//Add the POST 'daext-hreflang-manager/v1/post' endpoint to the Rest API
		register_rest_route(
			'daext-hreflang-manager/v1', '/post/', array(
				'methods'  => 'POST',
				'callback' => array($this, 'rest_api_daext_hreflang_manager_post_connection_callback'),
			)
		);

		//Add the GET 'daext-hreflang-manager/v1/options' endpoint to the Rest API
		register_rest_route(
			'daext-hreflang-manager/v1', '/options/', array(
				'methods'  => 'GET',
				'callback' => array($this, 'rest_api_daext_hreflang_manager_read_options_callback'),
			)
		);

	}

	/*
	 * Callback for the GET 'daext-autolinks-manager/v1/options' endpoint of the Rest API
	 */
	function rest_api_daext_hreflang_manager_read_connections_callback( $data ) {

		//Check the capability
		if (!current_user_can(get_option($this->shared->get('slug') . "_editor_sidebar_capability"))) {
			return new WP_Error(
				'rest_read_error',
				'Sorry, you are not allowed to view the Hreflang Manager options.',
				array('status' => 403)
			);
		}

		//Generate the response

		$url_to_connect = $this->shared->get_permalink($data['id'], true);

		global $wpdb;
		$table_name = $wpdb->prefix . "da_hm_connect";
		$safe_sql = $wpdb->prepare("SELECT * FROM $table_name WHERE url_to_connect = %s", $url_to_connect);
		$row = $wpdb->get_row($safe_sql, ARRAY_A);

		if($wpdb->num_rows > 0){

			//Prepare the response
			$response = new WP_REST_Response($row);

		}else{

			return false;
		}

		return $response;

	}

	/*
     * Callback for the POST 'daext-hreflang-manager/v1/post/' endpoint of the Rest API.
	 *
	 * This method is used to save the connection when the "Update" button of the Gutenberg editor is clicked.
     */
	function rest_api_daext_hreflang_manager_post_connection_callback( $data ) {

		//Check the capability
		if (!current_user_can(get_option($this->shared->get('slug') . "_editor_sidebar_capability"))) {
			return new WP_Error(
				'rest_read_error',
				'Sorry, you are not allowed to view to add a connection.',
				array('status' => 403)
			);
		}

		$data = json_decode($data->get_body());
		$post_id = $data->postId;
		$data = $data->connectionData;

		//initialize the variables that include the URLs, the languages and the locale
		for($i=1;$i<=100;$i++){

			if( isset($data->{'url' . $i}) and strlen(trim($data->{'url' . $i})) > 0 ) {
				${"url" . $i} = $data->{'url' . $i};
				$at_least_one_url = true;
			}else {
				${"url" . $i} = '';
			}

			if( isset($data->{'language' . $i}) ){
				${"language" . $i} = $data->{'language' . $i};
			}else{
				${"language" . $i} = get_option($this->shared->get('slug') . '_default_language_' . $i);
			}

			if( isset($data->{'locale' . $i}) ){
				${"locale" . $i} = $data->{'locale' . $i};
			}else{
				${"locale" . $i} = get_option($this->shared->get('slug') . '_default_locale_' . $i);
			}

		}

		/*
         * save the fields in the da_hm_connect database table:
         *
         * - if a row with the da_hm_connect equal to the current permalink already exists update the row
         *
         * - if a row with the da_hm_connect equal to the current permalink doesn't exists create a new row
         */
		$permalink = $this->shared->get_permalink($post_id, true);

		//look for $permalink in the url_to_connect field of the da_hm_connect database table
		global $wpdb;
		$table_name = $wpdb->prefix . "da_hm_connect";
		$safe_sql = $wpdb->prepare("SELECT * FROM $table_name WHERE url_to_connect = %s", $permalink);
		$permalink_connections = $wpdb->get_row($safe_sql);

		if($permalink_connections !== null){

			//update an existing connection
			$safe_sql = $wpdb->prepare("UPDATE $table_name SET "
			                           . "url1 = %s, language1 = %s, locale1 = %s,"
			                           . "url2 = %s, language2 = %s, locale2 = %s ,"
			                           . "url3 = %s, language3 = %s, locale3 = %s ,"
			                           . "url4 = %s, language4 = %s, locale4 = %s ,"
			                           . "url5 = %s, language5 = %s, locale5 = %s ,"
			                           . "url6 = %s, language6 = %s, locale6 = %s ,"
			                           . "url7 = %s, language7 = %s, locale7 = %s ,"
			                           . "url8 = %s, language8 = %s, locale8 = %s ,"
			                           . "url9 = %s, language9 = %s, locale9 = %s ,"
			                           . "url10 = %s, language10 = %s, locale10 = %s ,"
			                           . "url11 = %s, language11 = %s, locale11 = %s ,"
			                           . "url12 = %s, language12 = %s, locale12 = %s ,"
			                           . "url13 = %s, language13 = %s, locale13 = %s ,"
			                           . "url14 = %s, language14 = %s, locale14 = %s ,"
			                           . "url15 = %s, language15 = %s, locale15 = %s ,"
			                           . "url16 = %s, language16 = %s, locale16 = %s ,"
			                           . "url17 = %s, language17 = %s, locale17 = %s ,"
			                           . "url18 = %s, language18 = %s, locale18 = %s ,"
			                           . "url19 = %s, language19 = %s, locale19 = %s ,"
			                           . "url20 = %s, language20 = %s, locale20 = %s,"
			                           . "url21 = %s, language21 = %s, locale21 = %s,"
			                           . "url22 = %s, language22 = %s, locale22 = %s,"
			                           . "url23 = %s, language23 = %s, locale23 = %s,"
			                           . "url24 = %s, language24 = %s, locale24 = %s,"
			                           . "url25 = %s, language25 = %s, locale25 = %s,"
			                           . "url26 = %s, language26 = %s, locale26 = %s,"
			                           . "url27 = %s, language27 = %s, locale27 = %s,"
			                           . "url28 = %s, language28 = %s, locale28 = %s,"
			                           . "url29 = %s, language29 = %s, locale29 = %s,"
			                           . "url30 = %s, language30 = %s, locale30 = %s,"
			                           . "url31 = %s, language31 = %s, locale31 = %s,"
			                           . "url32 = %s, language32 = %s, locale32 = %s,"
			                           . "url33 = %s, language33 = %s, locale33 = %s,"
			                           . "url34 = %s, language34 = %s, locale34 = %s,"
			                           . "url35 = %s, language35 = %s, locale35 = %s,"
			                           . "url36 = %s, language36 = %s, locale36 = %s,"
			                           . "url37 = %s, language37 = %s, locale37 = %s,"
			                           . "url38 = %s, language38 = %s, locale38 = %s,"
			                           . "url39 = %s, language39 = %s, locale39 = %s,"
			                           . "url40 = %s, language40 = %s, locale40 = %s,"
			                           . "url41 = %s, language41 = %s, locale41 = %s,"
			                           . "url42 = %s, language42 = %s, locale42 = %s,"
			                           . "url43 = %s, language43 = %s, locale43 = %s,"
			                           . "url44 = %s, language44 = %s, locale44 = %s,"
			                           . "url45 = %s, language45 = %s, locale45 = %s,"
			                           . "url46 = %s, language46 = %s, locale46 = %s,"
			                           . "url47 = %s, language47 = %s, locale47 = %s,"
			                           . "url48 = %s, language48 = %s, locale48 = %s,"
			                           . "url49 = %s, language49 = %s, locale49 = %s,"
			                           . "url50 = %s, language50 = %s, locale50 = %s,"
			                           . "url51 = %s, language51 = %s, locale51 = %s,"
			                           . "url52 = %s, language52 = %s, locale52 = %s,"
			                           . "url53 = %s, language53 = %s, locale53 = %s,"
			                           . "url54 = %s, language54 = %s, locale54 = %s,"
			                           . "url55 = %s, language55 = %s, locale55 = %s,"
			                           . "url56 = %s, language56 = %s, locale56 = %s,"
			                           . "url57 = %s, language57 = %s, locale57 = %s,"
			                           . "url58 = %s, language58 = %s, locale58 = %s,"
			                           . "url59 = %s, language59 = %s, locale59 = %s,"
			                           . "url60 = %s, language60 = %s, locale60 = %s,"
			                           . "url61 = %s, language61 = %s, locale61 = %s,"
			                           . "url62 = %s, language62 = %s, locale62 = %s,"
			                           . "url63 = %s, language63 = %s, locale63 = %s,"
			                           . "url64 = %s, language64 = %s, locale64 = %s,"
			                           . "url65 = %s, language65 = %s, locale65 = %s,"
			                           . "url66 = %s, language66 = %s, locale66 = %s,"
			                           . "url67 = %s, language67 = %s, locale67 = %s,"
			                           . "url68 = %s, language68 = %s, locale68 = %s,"
			                           . "url69 = %s, language69 = %s, locale69 = %s,"
			                           . "url70 = %s, language70 = %s, locale70 = %s,"
			                           . "url71 = %s, language71 = %s, locale71 = %s,"
			                           . "url72 = %s, language72 = %s, locale72 = %s,"
			                           . "url73 = %s, language73 = %s, locale73 = %s,"
			                           . "url74 = %s, language74 = %s, locale74 = %s,"
			                           . "url75 = %s, language75 = %s, locale75 = %s,"
			                           . "url76 = %s, language76 = %s, locale76 = %s,"
			                           . "url77 = %s, language77 = %s, locale77 = %s,"
			                           . "url78 = %s, language78 = %s, locale78 = %s,"
			                           . "url79 = %s, language79 = %s, locale79 = %s,"
			                           . "url80 = %s, language80 = %s, locale80 = %s,"
			                           . "url81 = %s, language81 = %s, locale81 = %s,"
			                           . "url82 = %s, language82 = %s, locale82 = %s,"
			                           . "url83 = %s, language83 = %s, locale83 = %s,"
			                           . "url84 = %s, language84 = %s, locale84 = %s,"
			                           . "url85 = %s, language85 = %s, locale85 = %s,"
			                           . "url86 = %s, language86 = %s, locale86 = %s,"
			                           . "url87 = %s, language87 = %s, locale87 = %s,"
			                           . "url88 = %s, language88 = %s, locale88 = %s,"
			                           . "url89 = %s, language89 = %s, locale89 = %s,"
			                           . "url90 = %s, language90 = %s, locale90 = %s,"
			                           . "url91 = %s, language91 = %s, locale91 = %s,"
			                           . "url92 = %s, language92 = %s, locale92 = %s,"
			                           . "url93 = %s, language93 = %s, locale93 = %s,"
			                           . "url94 = %s, language94 = %s, locale94 = %s,"
			                           . "url95 = %s, language95 = %s, locale95 = %s,"
			                           . "url96 = %s, language96 = %s, locale96 = %s,"
			                           . "url97 = %s, language97 = %s, locale97 = %s,"
			                           . "url98 = %s, language98 = %s, locale98 = %s,"
			                           . "url99 = %s, language99 = %s, locale99 = %s,"
			                           . "url100 = %s, language100 = %s, locale100 = %s WHERE url_to_connect = %s ",
				$url1, $language1, $locale1,
				$url2, $language2, $locale2,
				$url3, $language3, $locale3,
				$url4, $language4, $locale4,
				$url5, $language5, $locale5,
				$url6, $language6, $locale6,
				$url7, $language7, $locale7,
				$url8, $language8, $locale8,
				$url9, $language9, $locale9,
				$url10, $language10, $locale10,
				$url11, $language11, $locale11,
				$url12, $language12, $locale12,
				$url13, $language13, $locale13,
				$url14, $language14, $locale14,
				$url15, $language15, $locale15,
				$url16, $language16, $locale16,
				$url17, $language17, $locale17,
				$url18, $language18, $locale18,
				$url19, $language19, $locale19,
				$url20, $language20, $locale20,
				$url21, $language21, $locale21,
				$url22, $language22, $locale22,
				$url23, $language23, $locale23,
				$url24, $language24, $locale24,
				$url25, $language25, $locale25,
				$url26, $language26, $locale26,
				$url27, $language27, $locale27,
				$url28, $language28, $locale28,
				$url29, $language29, $locale29,
				$url30, $language30, $locale30,
				$url31, $language31, $locale31,
				$url32, $language32, $locale32,
				$url33, $language33, $locale33,
				$url34, $language34, $locale34,
				$url35, $language35, $locale35,
				$url36, $language36, $locale36,
				$url37, $language37, $locale37,
				$url38, $language38, $locale38,
				$url39, $language39, $locale39,
				$url40, $language40, $locale40,
				$url41, $language41, $locale41,
				$url42, $language42, $locale42,
				$url43, $language43, $locale43,
				$url44, $language44, $locale44,
				$url45, $language45, $locale45,
				$url46, $language46, $locale46,
				$url47, $language47, $locale47,
				$url48, $language48, $locale48,
				$url49, $language49, $locale49,
				$url50, $language50, $locale50,
				$url51, $language51, $locale51,
				$url52, $language52, $locale52,
				$url53, $language53, $locale53,
				$url54, $language54, $locale54,
				$url55, $language55, $locale55,
				$url56, $language56, $locale56,
				$url57, $language57, $locale57,
				$url58, $language58, $locale58,
				$url59, $language59, $locale59,
				$url60, $language60, $locale60,
				$url61, $language61, $locale61,
				$url62, $language62, $locale62,
				$url63, $language63, $locale63,
				$url64, $language64, $locale64,
				$url65, $language65, $locale65,
				$url66, $language66, $locale66,
				$url67, $language67, $locale67,
				$url68, $language68, $locale68,
				$url69, $language69, $locale69,
				$url70, $language70, $locale70,
				$url71, $language71, $locale71,
				$url72, $language72, $locale72,
				$url73, $language73, $locale73,
				$url74, $language74, $locale74,
				$url75, $language75, $locale75,
				$url76, $language76, $locale76,
				$url77, $language77, $locale77,
				$url78, $language78, $locale78,
				$url79, $language79, $locale79,
				$url80, $language80, $locale80,
				$url81, $language81, $locale81,
				$url82, $language82, $locale82,
				$url83, $language83, $locale83,
				$url84, $language84, $locale84,
				$url85, $language85, $locale85,
				$url86, $language86, $locale86,
				$url87, $language87, $locale87,
				$url88, $language88, $locale88,
				$url89, $language89, $locale89,
				$url90, $language90, $locale90,
				$url91, $language91, $locale91,
				$url92, $language92, $locale92,
				$url93, $language93, $locale93,
				$url94, $language94, $locale94,
				$url95, $language95, $locale95,
				$url96, $language96, $locale96,
				$url97, $language97, $locale97,
				$url98, $language98, $locale98,
				$url99, $language99, $locale99,
				$url100, $language100, $locale100,
				$permalink);
			$query_result = $wpdb->query( $safe_sql );

		}else{

			//Return ( do not create a new connection ) if there are not a single url defined
			if(!isset($at_least_one_url)){return;}

			//add a new connection into the database
			$safe_sql = $wpdb->prepare("INSERT INTO $table_name SET url_to_connect = %s ,"
			                           . "url1 = %s, language1 = %s, locale1 = %s,"
			                           . "url2 = %s, language2 = %s, locale2 = %s ,"
			                           . "url3 = %s, language3 = %s, locale3 = %s ,"
			                           . "url4 = %s, language4 = %s, locale4 = %s ,"
			                           . "url5 = %s, language5 = %s, locale5 = %s ,"
			                           . "url6 = %s, language6 = %s, locale6 = %s ,"
			                           . "url7 = %s, language7 = %s, locale7 = %s ,"
			                           . "url8 = %s, language8 = %s, locale8 = %s ,"
			                           . "url9 = %s, language9 = %s, locale9 = %s ,"
			                           . "url10 = %s, language10 = %s, locale10 = %s ,"
			                           . "url11 = %s, language11 = %s, locale11 = %s ,"
			                           . "url12 = %s, language12 = %s, locale12 = %s ,"
			                           . "url13 = %s, language13 = %s, locale13 = %s ,"
			                           . "url14 = %s, language14 = %s, locale14 = %s ,"
			                           . "url15 = %s, language15 = %s, locale15 = %s ,"
			                           . "url16 = %s, language16 = %s, locale16 = %s ,"
			                           . "url17 = %s, language17 = %s, locale17 = %s ,"
			                           . "url18 = %s, language18 = %s, locale18 = %s ,"
			                           . "url19 = %s, language19 = %s, locale19 = %s ,"
			                           . "url20 = %s, language20 = %s, locale20 = %s ,"
			                           . "url21 = %s, language21 = %s, locale21 = %s ,"
			                           . "url22 = %s, language22 = %s, locale22 = %s ,"
			                           . "url23 = %s, language23 = %s, locale23 = %s ,"
			                           . "url24 = %s, language24 = %s, locale24 = %s ,"
			                           . "url25 = %s, language25 = %s, locale25 = %s ,"
			                           . "url26 = %s, language26 = %s, locale26 = %s ,"
			                           . "url27 = %s, language27 = %s, locale27 = %s ,"
			                           . "url28 = %s, language28 = %s, locale28 = %s ,"
			                           . "url29 = %s, language29 = %s, locale29 = %s ,"
			                           . "url30 = %s, language30 = %s, locale30 = %s ,"
			                           . "url31 = %s, language31 = %s, locale31 = %s ,"
			                           . "url32 = %s, language32 = %s, locale32 = %s ,"
			                           . "url33 = %s, language33 = %s, locale33 = %s ,"
			                           . "url34 = %s, language34 = %s, locale34 = %s ,"
			                           . "url35 = %s, language35 = %s, locale35 = %s ,"
			                           . "url36 = %s, language36 = %s, locale36 = %s ,"
			                           . "url37 = %s, language37 = %s, locale37 = %s ,"
			                           . "url38 = %s, language38 = %s, locale38 = %s ,"
			                           . "url39 = %s, language39 = %s, locale39 = %s ,"
			                           . "url40 = %s, language40 = %s, locale40 = %s ,"
			                           . "url41 = %s, language41 = %s, locale41 = %s ,"
			                           . "url42 = %s, language42 = %s, locale42 = %s ,"
			                           . "url43 = %s, language43 = %s, locale43 = %s ,"
			                           . "url44 = %s, language44 = %s, locale44 = %s ,"
			                           . "url45 = %s, language45 = %s, locale45 = %s ,"
			                           . "url46 = %s, language46 = %s, locale46 = %s ,"
			                           . "url47 = %s, language47 = %s, locale47 = %s ,"
			                           . "url48 = %s, language48 = %s, locale48 = %s ,"
			                           . "url49 = %s, language49 = %s, locale49 = %s ,"
			                           . "url50 = %s, language50 = %s, locale50 = %s ,"
			                           . "url51 = %s, language51 = %s, locale51 = %s ,"
			                           . "url52 = %s, language52 = %s, locale52 = %s ,"
			                           . "url53 = %s, language53 = %s, locale53 = %s ,"
			                           . "url54 = %s, language54 = %s, locale54 = %s ,"
			                           . "url55 = %s, language55 = %s, locale55 = %s ,"
			                           . "url56 = %s, language56 = %s, locale56 = %s ,"
			                           . "url57 = %s, language57 = %s, locale57 = %s ,"
			                           . "url58 = %s, language58 = %s, locale58 = %s ,"
			                           . "url59 = %s, language59 = %s, locale59 = %s ,"
			                           . "url60 = %s, language60 = %s, locale60 = %s ,"
			                           . "url61 = %s, language61 = %s, locale61 = %s,"
			                           . "url62 = %s, language62 = %s, locale62 = %s,"
			                           . "url63 = %s, language63 = %s, locale63 = %s,"
			                           . "url64 = %s, language64 = %s, locale64 = %s,"
			                           . "url65 = %s, language65 = %s, locale65 = %s,"
			                           . "url66 = %s, language66 = %s, locale66 = %s,"
			                           . "url67 = %s, language67 = %s, locale67 = %s,"
			                           . "url68 = %s, language68 = %s, locale68 = %s,"
			                           . "url69 = %s, language69 = %s, locale69 = %s,"
			                           . "url70 = %s, language70 = %s, locale70 = %s,"
			                           . "url71 = %s, language71 = %s, locale71 = %s,"
			                           . "url72 = %s, language72 = %s, locale72 = %s,"
			                           . "url73 = %s, language73 = %s, locale73 = %s,"
			                           . "url74 = %s, language74 = %s, locale74 = %s,"
			                           . "url75 = %s, language75 = %s, locale75 = %s,"
			                           . "url76 = %s, language76 = %s, locale76 = %s,"
			                           . "url77 = %s, language77 = %s, locale77 = %s,"
			                           . "url78 = %s, language78 = %s, locale78 = %s,"
			                           . "url79 = %s, language79 = %s, locale79 = %s,"
			                           . "url80 = %s, language80 = %s, locale80 = %s,"
			                           . "url81 = %s, language81 = %s, locale81 = %s,"
			                           . "url82 = %s, language82 = %s, locale82 = %s,"
			                           . "url83 = %s, language83 = %s, locale83 = %s,"
			                           . "url84 = %s, language84 = %s, locale84 = %s,"
			                           . "url85 = %s, language85 = %s, locale85 = %s,"
			                           . "url86 = %s, language86 = %s, locale86 = %s,"
			                           . "url87 = %s, language87 = %s, locale87 = %s,"
			                           . "url88 = %s, language88 = %s, locale88 = %s,"
			                           . "url89 = %s, language89 = %s, locale89 = %s,"
			                           . "url90 = %s, language90 = %s, locale90 = %s,"
			                           . "url91 = %s, language91 = %s, locale91 = %s,"
			                           . "url92 = %s, language92 = %s, locale92 = %s,"
			                           . "url93 = %s, language93 = %s, locale93 = %s,"
			                           . "url94 = %s, language94 = %s, locale94 = %s,"
			                           . "url95 = %s, language95 = %s, locale95 = %s,"
			                           . "url96 = %s, language96 = %s, locale96 = %s,"
			                           . "url97 = %s, language97 = %s, locale97 = %s,"
			                           . "url98 = %s, language98 = %s, locale98 = %s,"
			                           . "url99 = %s, language99 = %s, locale99 = %s,"
			                           . "url100 = %s, language100 = %s, locale100 = %s ", $permalink,
				$url1, $language1, $locale1,
				$url2, $language2, $locale2,
				$url3, $language3, $locale3,
				$url4, $language4, $locale4,
				$url5, $language5, $locale5,
				$url6, $language6, $locale6,
				$url7, $language7, $locale7,
				$url8, $language8, $locale8,
				$url9, $language9, $locale9,
				$url10, $language10, $locale10,
				$url11, $language11, $locale11,
				$url12, $language12, $locale12,
				$url13, $language13, $locale13,
				$url14, $language14, $locale14,
				$url15, $language15, $locale15,
				$url16, $language16, $locale16,
				$url17, $language17, $locale17,
				$url18, $language18, $locale18,
				$url19, $language19, $locale19,
				$url20, $language20, $locale20,
				$url21, $language21, $locale21,
				$url22, $language22, $locale22,
				$url23, $language23, $locale23,
				$url24, $language24, $locale24,
				$url25, $language25, $locale25,
				$url26, $language26, $locale26,
				$url27, $language27, $locale27,
				$url28, $language28, $locale28,
				$url29, $language29, $locale29,
				$url30, $language30, $locale30,
				$url31, $language31, $locale31,
				$url32, $language32, $locale32,
				$url33, $language33, $locale33,
				$url34, $language34, $locale34,
				$url35, $language35, $locale35,
				$url36, $language36, $locale36,
				$url37, $language37, $locale37,
				$url38, $language38, $locale38,
				$url39, $language39, $locale39,
				$url40, $language40, $locale40,
				$url41, $language41, $locale41,
				$url42, $language42, $locale42,
				$url43, $language43, $locale43,
				$url44, $language44, $locale44,
				$url45, $language45, $locale45,
				$url46, $language46, $locale46,
				$url47, $language47, $locale47,
				$url48, $language48, $locale48,
				$url49, $language49, $locale49,
				$url50, $language50, $locale50,
				$url51, $language51, $locale51,
				$url52, $language52, $locale52,
				$url53, $language53, $locale53,
				$url54, $language54, $locale54,
				$url55, $language55, $locale55,
				$url56, $language56, $locale56,
				$url57, $language57, $locale57,
				$url58, $language58, $locale58,
				$url59, $language59, $locale59,
				$url60, $language60, $locale60,
				$url61, $language61, $locale61,
				$url62, $language62, $locale62,
				$url63, $language63, $locale63,
				$url64, $language64, $locale64,
				$url65, $language65, $locale65,
				$url66, $language66, $locale66,
				$url67, $language67, $locale67,
				$url68, $language68, $locale68,
				$url69, $language69, $locale69,
				$url70, $language70, $locale70,
				$url71, $language71, $locale71,
				$url72, $language72, $locale72,
				$url73, $language73, $locale73,
				$url74, $language74, $locale74,
				$url75, $language75, $locale75,
				$url76, $language76, $locale76,
				$url77, $language77, $locale77,
				$url78, $language78, $locale78,
				$url79, $language79, $locale79,
				$url80, $language80, $locale80,
				$url81, $language81, $locale81,
				$url82, $language82, $locale82,
				$url83, $language83, $locale83,
				$url84, $language84, $locale84,
				$url85, $language85, $locale85,
				$url86, $language86, $locale86,
				$url87, $language87, $locale87,
				$url88, $language88, $locale88,
				$url89, $language89, $locale89,
				$url90, $language90, $locale90,
				$url91, $language91, $locale91,
				$url92, $language92, $locale92,
				$url93, $language93, $locale93,
				$url94, $language94, $locale94,
				$url95, $language95, $locale95,
				$url96, $language96, $locale96,
				$url97, $language97, $locale97,
				$url98, $language98, $locale98,
				$url99, $language99, $locale99,
				$url100, $language100, $locale100);
			$query_result = $wpdb->query( $safe_sql );

		}

		//Generate the response
		$response = new WP_REST_Response($data);

		return $response;

	}

	/*
     * Callback for the GET 'daext-hreflang-manager/v1/options' endpoint of the Rest API
     */
	function rest_api_daext_hreflang_manager_read_options_callback( $data ) {

		//Check the capability
		if (!current_user_can(get_option($this->shared->get('slug') . "_editor_sidebar_capability"))) {
			return new WP_Error(
				'rest_read_error',
				'Sorry, you are not allowed to view the Hreflang Manager options.',
				array('status' => 403)
			);
		}

		//Generate the response
		$response = [];
		foreach($this->shared->get('options') as $key => $value){
			$response[$key] = get_option($key);
		}

		//Prepare the response
		$response = new WP_REST_Response($response);

		return $response;

	}
    
}