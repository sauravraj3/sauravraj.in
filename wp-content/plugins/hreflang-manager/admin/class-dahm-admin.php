<?php

/*
 * this class should be used to work with the administrative side of wordpress
 */
class Dahm_Admin
{

    protected static $instance = null;
    private $shared = null;

    private $screen_id_connections = null;
    private $screen_id_import = null;
    private $screen_id_export = null;
    private $screen_id_options = null;

    private function __construct()
    {

        //assign an instance of the plugin info
        $this->shared = Dahm_Shared::get_instance();

        //Load admin stylesheets and JavaScript
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));

        //Add the admin menu
        add_action('admin_menu', array($this, 'me_add_admin_menu'));

        //Load the options API registrations and callbacks
        add_action('admin_init', array($this, 'op_register_options'));

        //Add the meta box
        add_action('add_meta_boxes', array($this, 'create_meta_box'));

        //Save the meta box
        add_action( 'save_post', array($this, 'save_meta_box') );

        //this hook is triggered during the creation of a new blog
        add_action('wpmu_new_blog', array($this, 'new_blog_create_options_and_tables'), 10, 6);

        //this hook is triggered during the deletion of a blog
        add_action('delete_blog', array($this, 'delete_blog_delete_options_and_tables'), 10, 1);

        //Fires before a post is sent to the trash
        add_action( 'wp_trash_post', array($this, 'delete_post_connection'));

        //Export XML controller
        add_action('init', array($this, 'export_xml_controller'));

    }

    /*
     * return an instance of this class
     */
    public static function get_instance()
    {

        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;

    }

    public function enqueue_admin_styles()
    {

        $screen = get_current_screen();

        //menu connnections
        if ($screen->id == $this->screen_id_connections) {
            wp_enqueue_style($this->shared->get('slug') . '-framework-menu', $this->shared->get('url') . 'admin/assets/css/framework/menu.css', array(), $this->shared->get('ver'));
            wp_enqueue_style($this->shared->get('slug') . '-menu-connections', $this->shared->get('url') . 'admin/assets/css/menu-connections.css', array(), $this->shared->get('ver'));
            wp_enqueue_style($this->shared->get('slug') . '-chosen', $this->shared->get('url') . 'admin/assets/inc/chosen/chosen-min.css', array(), $this->shared->get('ver'));
            wp_enqueue_style($this->shared->get('slug') . '-chosen-custom', $this->shared->get('url') . 'admin/assets/css/chosen-custom.css', array(), $this->shared->get('ver'));
        }

        //menu options
        if ($screen->id == $this->screen_id_options) {
            wp_enqueue_style($this->shared->get('slug') . '-framework-options', $this->shared->get('url') . 'admin/assets/css/framework/options.css', array(), $this->shared->get('ver'));
            wp_enqueue_style($this->shared->get('slug') . '-jquery-ui-tooltip', $this->shared->get('url') . 'admin/assets/css/jquery-ui-tooltip.css', array(), $this->shared->get('ver'));
            wp_enqueue_style($this->shared->get('slug') . '-chosen', $this->shared->get('url') . 'admin/assets/inc/chosen/chosen-min.css', array(), $this->shared->get('ver'));
            wp_enqueue_style($this->shared->get('slug') . '-chosen-custom', $this->shared->get('url') . 'admin/assets/css/chosen-custom.css', array(), $this->shared->get('ver'));
            wp_enqueue_style($this->shared->get('slug') . '-menu-options', $this->shared->get('url') . 'admin/assets/css/menu-options.css', array(), $this->shared->get('ver'));
        }

        $meta_box_post_types = preg_replace('/\s+/', '', get_option( $this->shared->get('slug') . '_meta_box_post_types' ));
        $meta_box_post_types_a = explode(',', $meta_box_post_types);
        if(in_array($screen->id, $meta_box_post_types_a)){
            wp_enqueue_style( $this->shared->get('slug') .'-meta-box', $this->shared->get('url') . 'admin/assets/css/meta-box.css', array(), $this->shared->get('ver') );
            wp_enqueue_style($this->shared->get('slug') . '-chosen', $this->shared->get('url') . 'admin/assets/inc/chosen/chosen-min.css', array(), $this->shared->get('ver'));
            wp_enqueue_style($this->shared->get('slug') . '-chosen-custom', $this->shared->get('url') . 'admin/assets/css/chosen-custom.css', array(), $this->shared->get('ver'));
        }

    }

    /*
     * enqueue admin-specific javascript
     */
    public function enqueue_admin_scripts()
    {
        
        $screen = get_current_screen();

        //menu connnections
        if ($screen->id == $this->screen_id_connections) {
            wp_enqueue_script($this->shared->get('slug') . '-chosen', $this->shared->get('url') . 'admin/assets/inc/chosen/chosen-min.js', array('jquery'), $this->shared->get('ver'));
            wp_enqueue_script($this->shared->get('slug') . '-connections-menu', $this->shared->get('url') . 'admin/assets/js/connections-menu.js', array('jquery'), $this->shared->get('ver'));
        }

        //menu options
        if ($screen->id == $this->screen_id_options) {
            wp_enqueue_script('jquery-ui-tooltip');
            wp_enqueue_script( $this->shared->get('slug') . '-jquery-ui-tooltip-init', $this->shared->get('url') . 'admin/assets/js/jquery-ui-tooltip-init.js', 'jquery', $this->shared->get('ver') );
            wp_enqueue_script($this->shared->get('slug') . '-chosen', $this->shared->get('url') . 'admin/assets/inc/chosen/chosen-min.js', array('jquery'), $this->shared->get('ver'));
            wp_enqueue_script($this->shared->get('slug') . '-options-menu', $this->shared->get('url') . 'admin/assets/js/options-menu.js', array('jquery'), $this->shared->get('ver'));
        }

        $meta_box_post_types = preg_replace('/\s+/', '', get_option( $this->shared->get('slug') . '_meta_box_post_types' ));
        $meta_box_post_types_a = explode(',', $meta_box_post_types);
        if(in_array($screen->id, $meta_box_post_types_a)){
            wp_enqueue_script($this->shared->get('slug') . '-chosen', $this->shared->get('url') . 'admin/assets/inc/chosen/chosen-min.js', array('jquery'), $this->shared->get('ver'));
            wp_enqueue_script($this->shared->get('slug') . '-meta-box', $this->shared->get('url') . 'admin/assets/js/meta-box.js', array('jquery'), $this->shared->get('ver'));
        }


    }

    /*
     * plugin activation
     */
    public function ac_activate($networkwide)
    {

        /*
         * delete options and tables for all the sites in the network
         */
        if (function_exists('is_multisite') and is_multisite()) {

            /*
             * if this is a "Network Activation" create the options and tables
             * for each blog
             */
            if ($networkwide) {

                //get the current blog id
                global $wpdb;
                $current_blog = $wpdb->blogid;

                //create an array with all the blog ids
                $blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");

                //iterate through all the blogs
                foreach ($blogids as $blog_id) {

                    //swith to the iterated blog
                    switch_to_blog($blog_id);

                    //create options and tables for the iterated blog
                    $this->ac_initialize_options();
                    $this->ac_create_database_tables();

                }

                //switch to the current blog
                switch_to_blog($current_blog);

            } else {

                /*
                 * if this is not a "Network Activation" create options and
                 * tables only for the current blog
                 */
                $this->ac_initialize_options();
                $this->ac_create_database_tables();

            }

        } else {

            /*
             * if this is not a multisite installation create options and
             * tables only for the current blog
             */
            $this->ac_initialize_options();
            $this->ac_create_database_tables();

        }

    }

    //create the options and tables for the newly created blog
    public function new_blog_create_options_and_tables($blog_id, $user_id, $domain, $path, $site_id, $meta)
    {

        global $wpdb;

        /*
         * if the plugin is "Network Active" create the options and tables for
         * this new blog
         */
        if (is_plugin_active_for_network('hreflang-manager/init.php')) {

            //get the id of the current blog
            $current_blog = $wpdb->blogid;

            //switch to the blog that is being activated
            switch_to_blog($blog_id);

            //create options and database tables for the new blog
            $this->ac_initialize_options();
            $this->ac_create_database_tables();

            //switch to the current blog
            switch_to_blog($current_blog);

        }

    }

    //delete options and tables for the deleted blog
    public function delete_blog_delete_options_and_tables($blog_id)
    {

        global $wpdb;

        //get the id of the current blog
        $current_blog = $wpdb->blogid;

        //switch to the blog that is being activated
        switch_to_blog($blog_id);

        //create options and database tables for the new blog
        $this->un_delete_options();
        $this->un_delete_database_tables();

        //switch to the current blog
        switch_to_blog($current_blog);

    }

    /*
     * initialize plugin options
     */
    private function ac_initialize_options()
    {

	    foreach($this->shared->get('options') as $key => $value){
		    add_option($key, $value);
	    }

    }

    /*
     * create the plugin database tables
     */
    private function ac_create_database_tables()
    {

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        //check database version and create the database
        if (intval(get_option($this->shared->get('slug') . '_database_version'), 10) < 1) {

            global $wpdb;
            $table_name = $wpdb->prefix . "da_hm_connect";
            $sql = "CREATE TABLE $table_name (
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                url_to_connect TEXT DEFAULT '' NOT NULL,
                url1 TEXT DEFAULT '' NOT NULL,
                language1 VARCHAR(9) DEFAULT '' NOT NULL,
                locale1 VARCHAR(2) DEFAULT '' NOT NULL,
                url2 TEXT DEFAULT '' NOT NULL,
                language2 VARCHAR(9) DEFAULT '' NOT NULL,
                locale2 VARCHAR(2) DEFAULT '' NOT NULL,
                url3 TEXT DEFAULT '' NOT NULL,
                language3 VARCHAR(9) DEFAULT '' NOT NULL,
                locale3 VARCHAR(2) DEFAULT '' NOT NULL,
                url4 TEXT DEFAULT '' NOT NULL,
                language4 VARCHAR(9) DEFAULT '' NOT NULL,
                locale4 VARCHAR(2) DEFAULT '' NOT NULL,
                url5 TEXT DEFAULT '' NOT NULL,
                language5 VARCHAR(9) DEFAULT '' NOT NULL,
                locale5 VARCHAR(2) DEFAULT '' NOT NULL,
                url6 TEXT DEFAULT '' NOT NULL,
                language6 VARCHAR(9) DEFAULT '' NOT NULL,
                locale6 VARCHAR(2) DEFAULT '' NOT NULL,
                url7 TEXT DEFAULT '' NOT NULL,
                language7 VARCHAR(9) DEFAULT '' NOT NULL,
                locale7 VARCHAR(2) DEFAULT '' NOT NULL,
                url8 TEXT DEFAULT '' NOT NULL,
                language8 VARCHAR(9) DEFAULT '' NOT NULL,
                locale8 VARCHAR(2) DEFAULT '' NOT NULL,
                url9 TEXT DEFAULT '' NOT NULL,
                language9 VARCHAR(9) DEFAULT '' NOT NULL,
                locale9 VARCHAR(2) DEFAULT '' NOT NULL,
                url10 TEXT DEFAULT '' NOT NULL,
                language10 VARCHAR(9) DEFAULT '' NOT NULL,
                locale10 VARCHAR(2) DEFAULT '' NOT NULL,
                url11 TEXT DEFAULT '' NOT NULL,
                language11 VARCHAR(9) DEFAULT '' NOT NULL,
                locale11 VARCHAR(2) DEFAULT '' NOT NULL,
                url12 TEXT DEFAULT '' NOT NULL,
                language12 VARCHAR(9) DEFAULT '' NOT NULL,
                locale12 VARCHAR(2) DEFAULT '' NOT NULL,
                url13 TEXT DEFAULT '' NOT NULL,
                language13 VARCHAR(9) DEFAULT '' NOT NULL,
                locale13 VARCHAR(2) DEFAULT '' NOT NULL,
                url14 TEXT DEFAULT '' NOT NULL,
                language14 VARCHAR(9) DEFAULT '' NOT NULL,
                locale14 VARCHAR(2) DEFAULT '' NOT NULL,
                url15 TEXT DEFAULT '' NOT NULL,
                language15 VARCHAR(9) DEFAULT '' NOT NULL,
                locale15 VARCHAR(2) DEFAULT '' NOT NULL,
                url16 TEXT DEFAULT '' NOT NULL,
                language16 VARCHAR(9) DEFAULT '' NOT NULL,
                locale16 VARCHAR(2) DEFAULT '' NOT NULL,
                url17 TEXT DEFAULT '' NOT NULL,
                language17 VARCHAR(9) DEFAULT '' NOT NULL,
                locale17 VARCHAR(2) DEFAULT '' NOT NULL,
                url18 TEXT DEFAULT '' NOT NULL,
                language18 VARCHAR(9) DEFAULT '' NOT NULL,
                locale18 VARCHAR(2) DEFAULT '' NOT NULL,
                url19 TEXT DEFAULT '' NOT NULL,
                language19 VARCHAR(9) DEFAULT '' NOT NULL,
                locale19 VARCHAR(2) DEFAULT '' NOT NULL,
                url20 TEXT DEFAULT '' NOT NULL,
                language20 VARCHAR(9) DEFAULT '' NOT NULL,
                locale20 VARCHAR(2) DEFAULT '' NOT NULL
            )
            COLLATE = utf8_general_ci
            ";

            dbDelta($sql);

            //Update database version
            update_option('da_hm_database_version', "1");

        }

        //increase the maximum number of language and locale to 60, this option
        //has been implemented with version 1.02
        if (intval(get_option($this->shared->get('slug') . '_database_version'), 10) < 2) {

            global $wpdb;

            //alter the old url columns to text
            $table_name = $wpdb->prefix . "da_hm_connect";
            $sql = "ALTER TABLE $table_name
                MODIFY url1 TEXT,
                MODIFY url2 TEXT,
                MODIFY url3 TEXT,
                MODIFY url4 TEXT,
                MODIFY url5 TEXT,
                MODIFY url6 TEXT,
                MODIFY url7 TEXT,
                MODIFY url8 TEXT,
                MODIFY url9 TEXT,
                MODIFY url10 TEXT,
                MODIFY url11 TEXT,
                MODIFY url12 TEXT,
                MODIFY url13 TEXT,
                MODIFY url14 TEXT,
                MODIFY url15 TEXT,
                MODIFY url16 TEXT,
                MODIFY url17 TEXT,
                MODIFY url18 TEXT,
                MODIFY url19 TEXT,
                MODIFY url20 TEXT
            ";

            $wpdb->query($sql);

            //add the new columns from 21 to 60
            $table_name = $wpdb->prefix . "da_hm_connect";
            $sql = "ALTER TABLE $table_name ADD (
                url21 TEXT DEFAULT '' NOT NULL,
                language21 VARCHAR(9) DEFAULT '' NOT NULL,
                locale21 VARCHAR(2) DEFAULT '' NOT NULL,
                url22 TEXT DEFAULT '' NOT NULL,
                language22 VARCHAR(9) DEFAULT '' NOT NULL,
                locale22 VARCHAR(2) DEFAULT '' NOT NULL,
                url23 TEXT DEFAULT '' NOT NULL,
                language23 VARCHAR(9) DEFAULT '' NOT NULL,
                locale23 VARCHAR(2) DEFAULT '' NOT NULL,
                url24 TEXT DEFAULT '' NOT NULL,
                language24 VARCHAR(9) DEFAULT '' NOT NULL,
                locale24 VARCHAR(2) DEFAULT '' NOT NULL,
                url25 TEXT DEFAULT '' NOT NULL,
                language25 VARCHAR(9) DEFAULT '' NOT NULL,
                locale25 VARCHAR(2) DEFAULT '' NOT NULL,
                url26 TEXT DEFAULT '' NOT NULL,
                language26 VARCHAR(9) DEFAULT '' NOT NULL,
                locale26 VARCHAR(2) DEFAULT '' NOT NULL,
                url27 TEXT DEFAULT '' NOT NULL,
                language27 VARCHAR(9) DEFAULT '' NOT NULL,
                locale27 VARCHAR(2) DEFAULT '' NOT NULL,
                url28 TEXT DEFAULT '' NOT NULL,
                language28 VARCHAR(9) DEFAULT '' NOT NULL,
                locale28 VARCHAR(2) DEFAULT '' NOT NULL,
                url29 TEXT DEFAULT '' NOT NULL,
                language29 VARCHAR(9) DEFAULT '' NOT NULL,
                locale29 VARCHAR(2) DEFAULT '' NOT NULL,
                url30 TEXT DEFAULT '' NOT NULL,
                language30 VARCHAR(9) DEFAULT '' NOT NULL,
                locale30 VARCHAR(2) DEFAULT '' NOT NULL,
                url31 TEXT DEFAULT '' NOT NULL,
                language31 VARCHAR(9) DEFAULT '' NOT NULL,
                locale31 VARCHAR(2) DEFAULT '' NOT NULL,
                url32 TEXT DEFAULT '' NOT NULL,
                language32 VARCHAR(9) DEFAULT '' NOT NULL,
                locale32 VARCHAR(2) DEFAULT '' NOT NULL,
                url33 TEXT DEFAULT '' NOT NULL,
                language33 VARCHAR(9) DEFAULT '' NOT NULL,
                locale33 VARCHAR(2) DEFAULT '' NOT NULL,
                url34 TEXT DEFAULT '' NOT NULL,
                language34 VARCHAR(9) DEFAULT '' NOT NULL,
                locale34 VARCHAR(2) DEFAULT '' NOT NULL,
                url35 TEXT DEFAULT '' NOT NULL,
                language35 VARCHAR(9) DEFAULT '' NOT NULL,
                locale35 VARCHAR(2) DEFAULT '' NOT NULL,
                url36 TEXT DEFAULT '' NOT NULL,
                language36 VARCHAR(9) DEFAULT '' NOT NULL,
                locale36 VARCHAR(2) DEFAULT '' NOT NULL,
                url37 TEXT DEFAULT '' NOT NULL,
                language37 VARCHAR(9) DEFAULT '' NOT NULL,
                locale37 VARCHAR(2) DEFAULT '' NOT NULL,
                url38 TEXT DEFAULT '' NOT NULL,
                language38 VARCHAR(9) DEFAULT '' NOT NULL,
                locale38 VARCHAR(2) DEFAULT '' NOT NULL,
                url39 TEXT DEFAULT '' NOT NULL,
                language39 VARCHAR(9) DEFAULT '' NOT NULL,
                locale39 VARCHAR(2) DEFAULT '' NOT NULL,
                url40 TEXT DEFAULT '' NOT NULL,
                language40 VARCHAR(9) DEFAULT '' NOT NULL,
                locale40 VARCHAR(2) DEFAULT '' NOT NULL,
                url41 TEXT DEFAULT '' NOT NULL,
                language41 VARCHAR(9) DEFAULT '' NOT NULL,
                locale41 VARCHAR(2) DEFAULT '' NOT NULL,
                url42 TEXT DEFAULT '' NOT NULL,
                language42 VARCHAR(9) DEFAULT '' NOT NULL,
                locale42 VARCHAR(2) DEFAULT '' NOT NULL,
                url43 TEXT DEFAULT '' NOT NULL,
                language43 VARCHAR(9) DEFAULT '' NOT NULL,
                locale43 VARCHAR(2) DEFAULT '' NOT NULL,
                url44 TEXT DEFAULT '' NOT NULL,
                language44 VARCHAR(9) DEFAULT '' NOT NULL,
                locale44 VARCHAR(2) DEFAULT '' NOT NULL,
                url45 TEXT DEFAULT '' NOT NULL,
                language45 VARCHAR(9) DEFAULT '' NOT NULL,
                locale45 VARCHAR(2) DEFAULT '' NOT NULL,
                url46 TEXT DEFAULT '' NOT NULL,
                language46 VARCHAR(9) DEFAULT '' NOT NULL,
                locale46 VARCHAR(2) DEFAULT '' NOT NULL,
                url47 TEXT DEFAULT '' NOT NULL,
                language47 VARCHAR(9) DEFAULT '' NOT NULL,
                locale47 VARCHAR(2) DEFAULT '' NOT NULL,
                url48 TEXT DEFAULT '' NOT NULL,
                language48 VARCHAR(9) DEFAULT '' NOT NULL,
                locale48 VARCHAR(2) DEFAULT '' NOT NULL,
                url49 TEXT DEFAULT '' NOT NULL,
                language49 VARCHAR(9) DEFAULT '' NOT NULL,
                locale49 VARCHAR(2) DEFAULT '' NOT NULL,
                url50 TEXT DEFAULT '' NOT NULL,
                language50 VARCHAR(9) DEFAULT '' NOT NULL,
                locale50 VARCHAR(2) DEFAULT '' NOT NULL,
                url51 TEXT DEFAULT '' NOT NULL,
                language51 VARCHAR(9) DEFAULT '' NOT NULL,
                locale51 VARCHAR(2) DEFAULT '' NOT NULL,
                url52 TEXT DEFAULT '' NOT NULL,
                language52 VARCHAR(9) DEFAULT '' NOT NULL,
                locale52 VARCHAR(2) DEFAULT '' NOT NULL,
                url53 TEXT DEFAULT '' NOT NULL,
                language53 VARCHAR(9) DEFAULT '' NOT NULL,
                locale53 VARCHAR(2) DEFAULT '' NOT NULL,
                url54 TEXT DEFAULT '' NOT NULL,
                language54 VARCHAR(9) DEFAULT '' NOT NULL,
                locale54 VARCHAR(2) DEFAULT '' NOT NULL,
                url55 TEXT DEFAULT '' NOT NULL,
                language55 VARCHAR(9) DEFAULT '' NOT NULL,
                locale55 VARCHAR(2) DEFAULT '' NOT NULL,
                url56 TEXT DEFAULT '' NOT NULL,
                language56 VARCHAR(9) DEFAULT '' NOT NULL,
                locale56 VARCHAR(2) DEFAULT '' NOT NULL,
                url57 TEXT DEFAULT '' NOT NULL,
                language57 VARCHAR(9) DEFAULT '' NOT NULL,
                locale57 VARCHAR(2) DEFAULT '' NOT NULL,
                url58 TEXT DEFAULT '' NOT NULL,
                language58 VARCHAR(9) DEFAULT '' NOT NULL,
                locale58 VARCHAR(2) DEFAULT '' NOT NULL,
                url59 TEXT DEFAULT '' NOT NULL,
                language59 VARCHAR(9) DEFAULT '' NOT NULL,
                locale59 VARCHAR(2) DEFAULT '' NOT NULL,
                url60 TEXT DEFAULT '' NOT NULL,
                language60 VARCHAR(9) DEFAULT '' NOT NULL,
                locale60 VARCHAR(2) DEFAULT '' NOT NULL
            )";

            $wpdb->query($sql);

            //Update database version
            update_option($this->shared->get('slug') . '_database_version', "2");

        }

        //increase the maximum number of language and locale to 100, this option
        //has been implemented with version 1.04
        if (intval(get_option($this->shared->get('slug') . '_database_version'), 10) < 3) {

            global $wpdb;

            //add the new columns from 61 to 100
            $table_name = $wpdb->prefix . "da_hm_connect";
            $sql = "ALTER TABLE $table_name ADD (
                url61 TEXT DEFAULT '' NOT NULL,
                language61 VARCHAR(9) DEFAULT '' NOT NULL,
                locale61 VARCHAR(2) DEFAULT '' NOT NULL,
                url62 TEXT DEFAULT '' NOT NULL,
                language62 VARCHAR(9) DEFAULT '' NOT NULL,
                locale62 VARCHAR(2) DEFAULT '' NOT NULL,
                url63 TEXT DEFAULT '' NOT NULL,
                language63 VARCHAR(9) DEFAULT '' NOT NULL,
                locale63 VARCHAR(2) DEFAULT '' NOT NULL,
                url64 TEXT DEFAULT '' NOT NULL,
                language64 VARCHAR(9) DEFAULT '' NOT NULL,
                locale64 VARCHAR(2) DEFAULT '' NOT NULL,
                url65 TEXT DEFAULT '' NOT NULL,
                language65 VARCHAR(9) DEFAULT '' NOT NULL,
                locale65 VARCHAR(2) DEFAULT '' NOT NULL,
                url66 TEXT DEFAULT '' NOT NULL,
                language66 VARCHAR(9) DEFAULT '' NOT NULL,
                locale66 VARCHAR(2) DEFAULT '' NOT NULL,
                url67 TEXT DEFAULT '' NOT NULL,
                language67 VARCHAR(9) DEFAULT '' NOT NULL,
                locale67 VARCHAR(2) DEFAULT '' NOT NULL,
                url68 TEXT DEFAULT '' NOT NULL,
                language68 VARCHAR(9) DEFAULT '' NOT NULL,
                locale68 VARCHAR(2) DEFAULT '' NOT NULL,
                url69 TEXT DEFAULT '' NOT NULL,
                language69 VARCHAR(9) DEFAULT '' NOT NULL,
                locale69 VARCHAR(2) DEFAULT '' NOT NULL,
                url70 TEXT DEFAULT '' NOT NULL,
                language70 VARCHAR(9) DEFAULT '' NOT NULL,
                locale70 VARCHAR(2) DEFAULT '' NOT NULL,
                url71 TEXT DEFAULT '' NOT NULL,
                language71 VARCHAR(9) DEFAULT '' NOT NULL,
                locale71 VARCHAR(2) DEFAULT '' NOT NULL,
                url72 TEXT DEFAULT '' NOT NULL,
                language72 VARCHAR(9) DEFAULT '' NOT NULL,
                locale72 VARCHAR(2) DEFAULT '' NOT NULL,
                url73 TEXT DEFAULT '' NOT NULL,
                language73 VARCHAR(9) DEFAULT '' NOT NULL,
                locale73 VARCHAR(2) DEFAULT '' NOT NULL,
                url74 TEXT DEFAULT '' NOT NULL,
                language74 VARCHAR(9) DEFAULT '' NOT NULL,
                locale74 VARCHAR(2) DEFAULT '' NOT NULL,
                url75 TEXT DEFAULT '' NOT NULL,
                language75 VARCHAR(9) DEFAULT '' NOT NULL,
                locale75 VARCHAR(2) DEFAULT '' NOT NULL,
                url76 TEXT DEFAULT '' NOT NULL,
                language76 VARCHAR(9) DEFAULT '' NOT NULL,
                locale76 VARCHAR(2) DEFAULT '' NOT NULL,
                url77 TEXT DEFAULT '' NOT NULL,
                language77 VARCHAR(9) DEFAULT '' NOT NULL,
                locale77 VARCHAR(2) DEFAULT '' NOT NULL,
                url78 TEXT DEFAULT '' NOT NULL,
                language78 VARCHAR(9) DEFAULT '' NOT NULL,
                locale78 VARCHAR(2) DEFAULT '' NOT NULL,
                url79 TEXT DEFAULT '' NOT NULL,
                language79 VARCHAR(9) DEFAULT '' NOT NULL,
                locale79 VARCHAR(2) DEFAULT '' NOT NULL,
                url80 TEXT DEFAULT '' NOT NULL,
                language80 VARCHAR(9) DEFAULT '' NOT NULL,
                locale80 VARCHAR(2) DEFAULT '' NOT NULL,
                url81 TEXT DEFAULT '' NOT NULL,
                language81 VARCHAR(9) DEFAULT '' NOT NULL,
                locale81 VARCHAR(2) DEFAULT '' NOT NULL,
                url82 TEXT DEFAULT '' NOT NULL,
                language82 VARCHAR(9) DEFAULT '' NOT NULL,
                locale82 VARCHAR(2) DEFAULT '' NOT NULL,
                url83 TEXT DEFAULT '' NOT NULL,
                language83 VARCHAR(9) DEFAULT '' NOT NULL,
                locale83 VARCHAR(2) DEFAULT '' NOT NULL,
                url84 TEXT DEFAULT '' NOT NULL,
                language84 VARCHAR(9) DEFAULT '' NOT NULL,
                locale84 VARCHAR(2) DEFAULT '' NOT NULL,
                url85 TEXT DEFAULT '' NOT NULL,
                language85 VARCHAR(9) DEFAULT '' NOT NULL,
                locale85 VARCHAR(2) DEFAULT '' NOT NULL,
                url86 TEXT DEFAULT '' NOT NULL,
                language86 VARCHAR(9) DEFAULT '' NOT NULL,
                locale86 VARCHAR(2) DEFAULT '' NOT NULL,
                url87 TEXT DEFAULT '' NOT NULL,
                language87 VARCHAR(9) DEFAULT '' NOT NULL,
                locale87 VARCHAR(2) DEFAULT '' NOT NULL,
                url88 TEXT DEFAULT '' NOT NULL,
                language88 VARCHAR(9) DEFAULT '' NOT NULL,
                locale88 VARCHAR(2) DEFAULT '' NOT NULL,
                url89 TEXT DEFAULT '' NOT NULL,
                language89 VARCHAR(9) DEFAULT '' NOT NULL,
                locale89 VARCHAR(2) DEFAULT '' NOT NULL,
                url90 TEXT DEFAULT '' NOT NULL,
                language90 VARCHAR(9) DEFAULT '' NOT NULL,
                locale90 VARCHAR(2) DEFAULT '' NOT NULL,
                url91 TEXT DEFAULT '' NOT NULL,
                language91 VARCHAR(9) DEFAULT '' NOT NULL,
                locale91 VARCHAR(2) DEFAULT '' NOT NULL,
                url92 TEXT DEFAULT '' NOT NULL,
                language92 VARCHAR(9) DEFAULT '' NOT NULL,
                locale92 VARCHAR(2) DEFAULT '' NOT NULL,
                url93 TEXT DEFAULT '' NOT NULL,
                language93 VARCHAR(9) DEFAULT '' NOT NULL,
                locale93 VARCHAR(2) DEFAULT '' NOT NULL,
                url94 TEXT DEFAULT '' NOT NULL,
                language94 VARCHAR(9) DEFAULT '' NOT NULL,
                locale94 VARCHAR(2) DEFAULT '' NOT NULL,
                url95 TEXT DEFAULT '' NOT NULL,
                language95 VARCHAR(9) DEFAULT '' NOT NULL,
                locale95 VARCHAR(2) DEFAULT '' NOT NULL,
                url96 TEXT DEFAULT '' NOT NULL,
                language96 VARCHAR(9) DEFAULT '' NOT NULL,
                locale96 VARCHAR(2) DEFAULT '' NOT NULL,
                url97 TEXT DEFAULT '' NOT NULL,
                language97 VARCHAR(9) DEFAULT '' NOT NULL,
                locale97 VARCHAR(2) DEFAULT '' NOT NULL,
                url98 TEXT DEFAULT '' NOT NULL,
                language98 VARCHAR(9) DEFAULT '' NOT NULL,
                locale98 VARCHAR(2) DEFAULT '' NOT NULL,
                url99 TEXT DEFAULT '' NOT NULL,
                language99 VARCHAR(9) DEFAULT '' NOT NULL,
                locale99 VARCHAR(2) DEFAULT '' NOT NULL,
                url100 TEXT DEFAULT '' NOT NULL,
                language100 VARCHAR(9) DEFAULT '' NOT NULL,
                locale100 VARCHAR(2) DEFAULT '' NOT NULL
            )";

            $wpdb->query($sql);

            //Update database version
            update_option($this->shared->get('slug') . '_database_version', "3");

        }

    }

    /*
     * plugin delete
     */
    static public function un_delete()
    {

        /*
         * delete options and tables for all the sites in the network
         */
        if (function_exists('is_multisite') and is_multisite()) {

            //get the current blog id
            global $wpdb;
            $current_blog = $wpdb->blogid;

            //create an array with all the blog ids
            $blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");

            //iterate through all the blogs
            foreach ($blogids as $blog_id) {

                //swith to the iterated blog
                switch_to_blog($blog_id);

                //create options and tables for the iterated blog
                Dahm_Admin::un_delete_options();
                Dahm_Admin::un_delete_database_tables();

            }

            //switch to the current blog
            switch_to_blog($current_blog);

        } else {

            /*
             * if this is not a multisite installation delete options and
             * tables only for the current blog
             */
            Dahm_Admin::un_delete_options();
            Dahm_Admin::un_delete_database_tables();

        }

    }

    /*
     * delete plugin options
     */
    static public function un_delete_options()
    {

	    //assign an instance of Dahm_Shared
	    $shared = Dahm_Shared::get_instance();

	    foreach($shared->get('options') as $key => $value){
		    delete_option($key);
	    }

    }

    /*
     * delete plugin database tables
     */
    static public function un_delete_database_tables()
    {

        //assign an instance of Dahm_Shared
        $shared = Dahm_Shared::get_instance();

        global $wpdb;

        $table_name = $wpdb->prefix . $shared->get('slug') . "_connect";
        $sql = "DROP TABLE $table_name";
        $wpdb->query($sql);

    }

    //meta box -----------------------------------------------------------------
    public function create_meta_box()
    {

        //verify the capability
        if(current_user_can(get_option($this->shared->get('slug') . "_meta_box_capability"))){

	        $post_types = preg_replace('/\s+/', '', get_option( $this->shared->get('slug') . '_meta_box_post_types' ));
	        $post_types_a = explode(',', $post_types);

	        foreach ($post_types_a as $key => $post_type) {
		        $post_type = trim($post_type);
		        add_meta_box('da-hm-meta',
                    'Hreflang Manager',
                    array($this, 'meta_box_callback'),
                    $post_type,
                    'normal',
                    'high',

                    /*
                     * Reference:
                     *
                     * https://make.wordpress.org/core/2018/11/07/meta-box-compatibility-flags/
                     */
                    array(

                        /*
                         * It's not confirmed that this meta box works in the block editor.
                         */
                        '__block_editor_compatible_meta_box' => false,

                        /*
                         * This meta box should only be loaded in the classic editor interface, and the block editor
                         * should not display it.
                         */
                        '__back_compat_meta_box' => true

                    )

                );
	        }

        }

    }

    //display the Hreflang Manager meta box content
    public function meta_box_callback($post)
    {

        ?>

        <table class="form-table table-hreflang-manager">

            <tbody>

            <?php


            /*
             * activate the 'disabled="disabled"' attribute when the post status is not:
             * - publish
             * - future
             * - pending
             * - private
             */
            $post_status = get_post_status();
            if($post_status != 'publish' and $post_status != 'future' and $post_status != 'pending' and $post_status != 'private'){
                $input_disabled = 'disabled="disabled"';
            }else{
                $input_disabled = '';
            }

            /*
             * Look for a connection that has as a url_to_connect value the permalink value of this post
             *
             * If there is already a connection:
             * - show the form with the field already filled with the value from the database
             * If there is no connection:
             * - show the form with empty fields
             */

            //get the number of connections that should be displayed in the menu
            $connections_in_menu = intval(get_option('da_hm_connections_in_menu'), 10);

            $permalink = $this->shared->get_permalink(get_the_ID(), true);

            //look for $permalink in the url_to_connect field of the da_hm_connect database table
            global $wpdb;
            $table_name = $wpdb->prefix . "da_hm_connect";
            $safe_sql = $wpdb->prepare("SELECT * FROM $table_name WHERE url_to_connect = %s", $permalink);
            $permalink_connections = $wpdb->get_row($safe_sql);

            if ($permalink_connections === null) {

                //default empty form
                for ($i = 1; $i <= $connections_in_menu; $i++) {

                    ?>

                    <!-- url -->
                    <tr valign="top">
                        <th scope="row"><label for="url<?php echo $i; ?>"><?php _e('URL', 'dahm'); ?> <?php echo $i; ?></label></th>
                        <td><input autocomplete="off" <?php echo $input_disabled; ?> type="text" id="url<?php echo $i; ?>" maxlength="2083" name="url<?php echo $i; ?>" class="regular-text dahm-url"/></td>
                    </tr>

                    <!-- Language -->
                    <tr valign="top">
                        <th scope="row"><label for="language<?php echo $i; ?>"><?php _e('Language', 'dahm'); ?> <?php echo $i; ?></label></th>
                        <td>
                            <select <?php echo $input_disabled; ?> id="language<?php echo $i; ?>" class="dahm-language" name="language<?php echo $i; ?>">
                                <?php

                                $array_language = get_option('da_hm_language');
                                foreach ($array_language as $key => $value) {
                                    echo '<option value="' . $value . '" ' . selected(get_option("da_hm_default_language_" . $i), $value, false) . '>' . $value . " - " . $key . '</option>';
                                }

                                ?>
                            </select>
                        </td>
                    </tr>

                    <!-- Locale -->
                    <tr valign="top">
                        <th scope="row"><label for="locale<?php echo $i; ?>"><?php _e('Locale', 'dahm'); ?> <?php echo $i; ?></label></th>
                        <td>
                            <select <?php echo $input_disabled; ?> id="locale<?php echo $i; ?>" class="dahm-locale" name="locale<?php echo $i; ?>">
                                <option value=""><?php _e('Not Assigned', 'dahm'); ?></option>
                                <?php

                                $array_language = get_option('da_hm_locale');
                                foreach ($array_language as $key => $value) {
                                    echo '<option value="' . $value . '" ' . selected(get_option("da_hm_default_locale_" . $i), $value, false) . '>' . $value . " - " . $key . '</option>';
                                }

                                ?>
                            </select>
                        </td>
                    </tr>

                    <?php

                }

            } else {

                //form with data retrieved form the database
                for ($i = 1; $i <= $connections_in_menu; $i++) {

                    ?>

                    <!-- url -->
                    <tr valign="top">
                        <th scope="row"><label for="url<?php echo $i; ?>"><?php _e('URL', 'dahm'); ?> <?php echo $i; ?></label></th>
                        <td><input autocomplete="off" type="text" value="<?php echo esc_attr(stripslashes($permalink_connections->{"url" . $i})); ?>" id="url<?php echo $i; ?>" maxlength="2083" name="url<?php echo $i; ?>" class="regular-text dahm-url"/></td>

                    </tr>

                    <!-- Language <?php echo $i; ?> -->
                    <tr valign="top">
                        <th scope="row"><label for="language<?php echo $i; ?>"><?php _e('Language', 'dahm'); ?> <?php echo $i; ?></label></th>
                        <td>
                            <select id="language<?php echo $i; ?>" class="dahm-language" name="language<?php echo $i; ?>">
                                <?php

                                $array_language = get_option('da_hm_language');
                                foreach ($array_language as $key => $value) {
                                    echo '<option value="' . $value . '" ' . selected($permalink_connections->{"language" . $i}, $value, false) . '>' . $value . " - " . $key . '</option>';
                                }

                                ?>
                            </select>
                        </td>
                    </tr>

                    <!-- Locale <?php echo $i; ?> -->
                    <tr valign="top">
                        <th scope="row"><label for="locale<?php echo $i; ?>"><?php _e('Locale', 'dahm'); ?> <?php echo $i; ?></label></th>
                        <td>
                            <select id="locale<?php echo $i; ?>" class="dahm-locale" name="locale<?php echo $i; ?>">
                                <option value=""><?php _e('Not Assigned', 'dahm'); ?></option>
                                <?php

                                $array_language = get_option('da_hm_locale');
                                foreach ($array_language as $key => $value) {
                                    echo '<option value="' . $value . '" ' . selected($permalink_connections->{"locale" . $i}, $value, false) . '>' . $value . " - " . $key . '</option>';
                                }

                                ?>
                            </select>
                        </td>
                    </tr>

                    <?php

                }

            }

            ?>

            </tbody>

        </table>

        <?php

        // Use nonce for verification
        wp_nonce_field(plugin_basename(__FILE__), 'da_hm_nonce');

    }

    public function save_meta_box( $post_id ) {

        //verify the capability
        if(!current_user_can(get_option($this->shared->get('slug') . "_meta_box_capability"))){return;}

        /* --- security verification --- */

        // verify if this is an auto save routine.
        // If it is our form has not been submitted, so we dont want to do anything
        if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // verify this came from the our screen and with proper authorization,
        // because save_post can be triggered at other times
        if ( !isset( $_POST['da_hm_nonce'] ) || !wp_verify_nonce( $_POST['da_hm_nonce'], plugin_basename( __FILE__ ) ) ){
            return;
        }

        /* - end security verification - */

        /*
         * Return ( do not save ) if the post status is not:
         * - publish
         * - future
         * - pending
         * - private
         */
        $post_status = get_post_status();
        if($post_status != 'publish' and $post_status != 'future' and $post_status != 'pending' and $post_status != 'private'){return;}

        //initialize the variables that include the URLs, the languages and the locale
        for($i=1;$i<=100;$i++){

            if( isset($_POST['url' . $i]) and strlen(trim($_POST['url' . $i])) > 0 ) {
                ${"url" . $i} = $_POST['url' . $i];
                $at_least_one_url = true;
            }else {
                ${"url" . $i} = '';
            }

            if( isset($_POST['language' . $i]) ){
                ${"language" . $i} = $_POST['language' . $i];
            }else{
                ${"language" . $i} = get_option($this->shared->get('slug') . '_default_language_' . $i);
            }

            if( isset($_POST['locale' . $i]) ){
                ${"locale" . $i} = $_POST['locale' . $i];
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
	    $permalink = $this->shared->get_permalink(get_the_ID(), true);

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
            $wpdb->query( $safe_sql );

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
            $wpdb->query( $safe_sql );

        }

    }

    /*
     * register the admin menu
     */
    public function me_add_admin_menu() {

        add_menu_page(
            'HM',
            'Hreflang',
            get_option($this->shared->get('slug') . "_connections_menu_capability"),
            $this->shared->get('slug') . '_connections',
            array( $this, 'me_display_menu_connections'),
            'dashicons-admin-site'
        );

        $this->screen_id_connections = add_submenu_page(
            $this->shared->get('slug') . '_connections',
            __('HM - Connections', 'dahm'),
            __('Connections', 'dahm'),
            get_option($this->shared->get('slug') . "_connections_menu_capability"),
            $this->shared->get('slug') . '_connections',
            array( $this, 'me_display_menu_connections')
        );

        $this->screen_id_import = add_submenu_page(
            $this->shared->get('slug') . '_connections',
            __('HM - Import', 'dahm'),
            __('Import', 'dahm'),
            get_option($this->shared->get('slug') . "_import_menu_capability"),
            $this->shared->get('slug') . '_import',
            array( $this, 'me_display_menu_import')
        );

        $this->screen_id_export = add_submenu_page(
            $this->shared->get('slug') . '_connections',
            __('HM - Export', 'dahm'),
            __('Export', 'dahm'),
            get_option($this->shared->get('slug') . "_export_menu_capability"),
            $this->shared->get('slug') . '_export',
            array( $this, 'me_display_menu_export')
        );

        $this->screen_id_options = add_submenu_page(
            $this->shared->get('slug') . '_connections',
            __('HM - Options', 'dahm'),
            __('Options', 'dahm'),
            'manage_options',
            $this->shared->get('slug') . '_options',
            array( $this, 'me_display_menu_options')
        );

    }

    /*
     * includes the connections view
     */
    public function me_display_menu_connections() {
        include_once( 'view/connections.php' );
    }

    /*
     * includes the import view
     */
    public function me_display_menu_import() {
        include_once( 'view/import.php' );
    }

    /*
     * includes the export view
     */
    public function me_display_menu_export() {
        include_once( 'view/export.php' );
    }

    /*
     * includes the options view
     */
    public function me_display_menu_options() {
        include_once( 'view/options.php' );
    }

    /*
     * register options
     */
    public function op_register_options()
    {

        //section general ----------------------------------------------------------
        add_settings_section(
            'da_hm_general_settings_section',
            NULL,
            NULL,
            'da_hm_general_options'
        );

        add_settings_field(
            'detect_url_mode',
            __('Detect URL Mode', 'dahm'),
            array($this, 'detect_url_mode_callback'),
            'da_hm_general_options',
            'da_hm_general_settings_section'
        );

        register_setting(
            'da_hm_general_options',
            'da_hm_detect_url_mode',
            array($this, 'detect_url_mode_validation')
        );

        add_settings_field(
            'https',
            __('HTTPS', 'dahm'),
            array($this, 'https_callback'),
            'da_hm_general_options',
            'da_hm_general_settings_section'
        );

        register_setting(
            'da_hm_general_options',
            'da_hm_https',
            array($this, 'https_validation')
        );

        add_settings_field(
            'auto_trailing_slash',
            __('Auto Trailing Slash', 'dahm'),
            array($this, 'auto_trailing_slash_callback'),
            'da_hm_general_options',
            'da_hm_general_settings_section'
        );

        register_setting(
            'da_hm_general_options',
            'da_hm_auto_trailing_slash',
            array($this, 'auto_trailing_slash_validation')
        );

        add_settings_field(
            'auto_delete',
            __('Auto Delete', 'dahm'),
            array($this, 'auto_delete_callback'),
            'da_hm_general_options',
            'da_hm_general_settings_section'
        );

        register_setting(
            'da_hm_general_options',
            'da_hm_auto_delete',
            array($this, 'auto_delete_validation')
        );

        add_settings_field(
            'sanitize_url',
            __('Sanitize URL', 'dahm'),
            array($this, 'sanitize_url_callback'),
            'da_hm_general_options',
            'da_hm_general_settings_section'
        );

        register_setting(
            'da_hm_general_options',
            'da_hm_sanitize_url',
            array($this, 'sanitize_url_validation')
        );

	    add_settings_field(
		    'sample_future_permalink',
		    __('Sample Future Permalink', 'dahm'),
		    array($this, 'sample_future_permalink_callback'),
		    'da_hm_general_options',
		    'da_hm_general_settings_section'
	    );

	    register_setting(
		    'da_hm_general_options',
		    'da_hm_sample_future_permalink',
		    array($this, 'sample_future_permalink_validation')
	    );

        add_settings_field(
            'show_log',
            __('Show Log', 'dahm'),
            array($this, 'show_log_callback'),
            'da_hm_general_options',
            'da_hm_general_settings_section'
        );

        register_setting(
            'da_hm_general_options',
            'da_hm_show_log',
            array($this, 'show_log_validation')
        );

        add_settings_field(
            'import_mode',
            __('Import Mode', 'dahm'),
            array($this, 'import_mode_callback'),
            'da_hm_general_options',
            'da_hm_general_settings_section'
        );

        register_setting(
            'da_hm_general_options',
            'da_hm_import_mode',
            array($this, 'import_mode_validation')
        );

        add_settings_field(
            'import_language',
            __('Import Language', 'dahm'),
            array($this, 'import_language_callback'),
            'da_hm_general_options',
            'da_hm_general_settings_section'
        );

        register_setting(
            'da_hm_general_options',
            'da_hm_import_language',
            array($this, 'import_language_validation')
        );

        add_settings_field(
            'import_locale',
            __('Import Locale', 'dahm'),
            array($this, 'import_locale_callback'),
            'da_hm_general_options',
            'da_hm_general_settings_section'
        );

        register_setting(
            'da_hm_general_options',
            'da_hm_import_locale',
            array($this, 'import_locale_validation')
        );

        add_settings_field(
            'connections_in_menu',
            __('Connections in Menu', 'dahm'),
            array($this, 'connections_in_menu_callback'),
            'da_hm_general_options',
            'da_hm_general_settings_section'
        );

        register_setting(
            'da_hm_general_options',
            'da_hm_connections_in_menu',
            array($this, 'connections_in_menu_validation')
        );

        add_settings_field(
            'meta_box_post_types',
            __('Meta Box Post Types', 'dahm'),
            array($this, 'meta_box_post_types_callback'),
            'da_hm_general_options',
            'da_hm_general_settings_section'
        );

        register_setting(
            'da_hm_general_options',
            'da_hm_meta_box_post_types',
            array($this, 'meta_box_post_types_validation')
        );

        add_settings_field(
            'meta_box_capability',
            __('Meta Box Capability', 'dahm'),
            array($this, 'meta_box_capability_callback'),
            'da_hm_general_options',
            'da_hm_general_settings_section'
        );

        register_setting(
            'da_hm_general_options',
            'da_hm_meta_box_capability',
            array($this, 'meta_box_capability_validation')
        );

	    add_settings_field(
		    'editor_sidebar_capability',
		    __('Editor Sidebar Capability', 'dahm'),
		    array($this, 'editor_sidebar_capability_callback'),
		    'da_hm_general_options',
		    'da_hm_general_settings_section'
	    );

	    register_setting(
		    'da_hm_general_options',
		    'da_hm_editor_sidebar_capability',
		    array($this, 'editor_sidebar_capability_validation')
	    );

        add_settings_field(
            'connections_menu_capability',
            __('Connections Menu Capability', 'dahm'),
            array($this, 'connections_menu_capability_callback'),
            'da_hm_general_options',
            'da_hm_general_settings_section'
        );

        register_setting(
            'da_hm_general_options',
            'da_hm_connections_menu_capability',
            array($this, 'connections_menu_capability_validation')
        );

        add_settings_field(
            'import_menu_capability',
            __('Import Menu Capability', 'dahm'),
            array($this, 'import_menu_capability_callback'),
            'da_hm_general_options',
            'da_hm_general_settings_section'
        );

        register_setting(
            'da_hm_general_options',
            'da_hm_import_menu_capability',
            array($this, 'import_menu_capability_validation')
        );

        add_settings_field(
            'export_menu_capability',
            __('Export Menu Capability', 'dahm'),
            array($this, 'export_menu_capability_callback'),
            'da_hm_general_options',
            'da_hm_general_settings_section'
        );

        register_setting(
            'da_hm_general_options',
            'da_hm_export_menu_capability',
            array($this, 'export_menu_capability_validation')
        );

        //section defaults ----------------------------------------------------------

        add_settings_section(
            'da_hm_defaults_settings_section',
            NULL,
            NULL,
            'da_hm_defaults_options'
        );

        $connections_in_menu = get_option('da_hm_connections_in_menu');
        for($i=1;$i<=$connections_in_menu;$i++){

            add_settings_field(
                'default_language_' . $i,
                __('Default Language', 'dahm') . ' ' . $i,
                array($this, 'default_language_' . $i . '_callback'),
                'da_hm_defaults_options',
                'da_hm_defaults_settings_section'
            );

            register_setting(
                'da_hm_defaults_options',
                'da_hm_default_language_' . $i,
                array($this, 'default_language_' . $i . '_validation')
            );

            add_settings_field(
                'default_locale_' . $i,
                __('Default Locale', 'dahm') . ' ' . $i,
                array($this, 'default_locale_' . $i . '_callback'),
                'da_hm_defaults_options',
                'da_hm_defaults_settings_section'
            );
    
            register_setting(
                'da_hm_defaults_options',
                'da_hm_default_locale_' . $i,
                array($this, 'default_locale_' . $i . '_validation')
            );

        }

    }

    public function show_log_callback($args){

        $html = '<select id="da-hm-show-log" name="da_hm_show_log">';
        $html .= '<option ' . selected(intval(get_option("da_hm_show_log")), 0, false) . ' value="0">' . __('No', 'dahm') . '</option>';
        $html .= '<option ' . selected(intval(get_option("da_hm_show_log")), 1, false) . ' value="1">' . __('Yes', 'dahm') . '</option>';
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Select "Yes" to display the log on the front-end. Please note that the log will be displayed only to the users who have access to the "Connections" menu.', 'dahm')) . '"></div>';

        echo $html;

    }

    public function show_log_validation($input){

        return intval($input, 10) == 1 ? '1' : '0';

    }

    public function https_callback($args){

        $html = '<select id="da-hm-https" name="da_hm_https">';
        $html .= '<option ' . selected(intval(get_option("da_hm_https")), 0, false) . ' value="0">' . __('No', 'dahm') . '</option>';
        $html .= '<option ' . selected(intval(get_option("da_hm_https")), 1, false) . ' value="1">' . __('Yes', 'dahm') . '</option>';
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('Select "Yes" if your website is using the HTTPS protocol. This option will be considered only if "Detect URL Mode" is set to "Server Variable".', 'dahm') . '"></div>';

        echo $html;

    }

    public function https_validation($input){

        return intval($input, 10) == 1 ? '1' : '0';

    }

    public function connections_in_menu_callback($args){

        $html = '<input autocomplete="off" type="text" id="da_hm_connections_in_menu" name="da_hm_connections_in_menu" class="regular-text" value="' . intval(get_option("da_hm_connections_in_menu"), 10) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Please enter a number from 1 to 100. This option determines the number of connections displayed in the "Defaults" tab, in the "Connections" menu and in the "Hreflang Manager" meta box.', 'dahm')) . '"></div>';
        echo $html;

    }

    public function connections_in_menu_validation($input){

        if( intval($input, 10) < 1 or intval($input, 10) > 100 ){
            add_settings_error( 'da_hm_connections_in_menu', 'da_hm_connections_in_menu', __('Please enter a number from 1 to 100 in the "Connections in Menu" option.', 'dahm') );
            $output = get_option('da_hm_connections_in_menu');
        }else{
            $output = $input;
        }

        return intval($output,  10);

    }

    public function meta_box_post_types_callback($args){

        $html = '<input autocomplete="off" type="text" id="da_hm_meta_box_post_types" name="da_hm_meta_box_post_types" class="regular-text" value="' . esc_attr(get_option("da_hm_meta_box_post_types")) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr(__('A list of post types, separated by comma, where the "Hreflang Manager" meta box should be loaded.', 'dahm')) . '"></div>';

        echo $html;

    }

    public function meta_box_post_types_validation($input){

        if(!preg_match($this->shared->regex_list_of_post_types, $input)){
            add_settings_error( 'da_hm_meta_box_post_types', 'da_hm_meta_box_post_types', __('Please enter a valid list of post types separated by a comma in the "Meta Box Post Types" option.', 'dahm') );
            $output = get_option('da_hm_meta_box_post_types');
        }else{
            $output = $input;
        }

        return $output;

    }


    public function detect_url_mode_callback($args){

        $html = '<select id="da-hm-detect-url-mode" name="da_hm_detect_url_mode">';
        $html .= '<option ' . selected(get_option("da_hm_detect_url_mode"), 'server_variable', false) . ' value="server_variable">' . __('Server Variable', 'dahm') . '</option>';
        $html .= '<option ' . selected(get_option("da_hm_detect_url_mode"), 'wp_request', false) . ' value="wp_request">' . __('WP Request', 'dahm') . '</option>';
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('Select the method used to detect the URL of the page.', 'dahm') . '"></div>';

        echo $html;

    }

    public function detect_url_mode_validation($input){

        if($input === 'server_variable' or $input === 'wp_request'){
            $output = $input;
        }else{
            $output = 'server_variable';
        }

        return $output;

    }

    public function auto_trailing_slash_callback($args){

        $html = '<select id="da-hm-auto-delete" name="da_hm_auto_trailing_slash">';
        $html .= '<option ' . selected(intval(get_option("da_hm_auto_trailing_slash")), 0, false) . ' value="0">' . __('No', 'dahm') . '</option>';
        $html .= '<option ' . selected(intval(get_option("da_hm_auto_trailing_slash")), 1, false) . ' value="1">' . __('Yes', 'dahm') . '</option>';
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('Enable this option to compare the URL defined in the "URL to Connect" field with the URL of the page with and without trailing slash.', 'dahm') . '"></div>';

        echo $html;

    }

    public function auto_trailing_slash_validation($input){

        return intval($input, 10) == 1 ? '1' : '0';

    }

    public function auto_delete_callback($args){

        $html = '<select id="da-hm-auto-delete" name="da_hm_auto_delete">';
        $html .= '<option ' . selected(intval(get_option("da_hm_auto_delete")), 0, false) . ' value="0">' . __('No', 'dahm') . '</option>';
        $html .= '<option ' . selected(intval(get_option("da_hm_auto_delete")), 1, false) . ' value="1">' . __('Yes', 'dahm') . '</option>';
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('Enable this option to automatically delete the connection associated with a post when the post is trashed.', 'dahm') . '"></div>';

        echo $html;

    }

    public function auto_delete_validation($input){

        return intval($input, 10) == 1 ? '1' : '0';

    }

    public function sanitize_url_callback($args){

        $html = '<select id="da-hm-sanitize-url" name="da_hm_sanitize_url">';
        $html .= '<option ' . selected(intval(get_option("da_hm_sanitize_url")), 0, false) . ' value="0">' . __('No', 'dahm') . '</option>';
        $html .= '<option ' . selected(intval(get_option("da_hm_sanitize_url")), 1, false) . ' value="1">' . __('Yes', 'dahm') . '</option>';
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('Enable this option to sanitize the URL in the href attribute of the link element.', 'dahm') . '"></div>';

        echo $html;

    }

    public function sanitize_url_validation($input){

        return intval($input, 10) == 1 ? '1' : '0';

    }

	public function sample_future_permalink_callback($args){

		$html = '<select id="da-hm-sample-future-permalink" name="da_hm_sample_future_permalink">';
		$html .= '<option ' . selected(intval(get_option("da_hm_sample_future_permalink")), 0, false) . ' value="0">' . __('No', 'dahm') . '</option>';
		$html .= '<option ' . selected(intval(get_option("da_hm_sample_future_permalink")), 1, false) . ' value="1">' . __('Yes', 'dahm') . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__('Enable this option to assign a permalink based on the post name to the posts scheduled to be published in a future date.', 'dahm') . '"></div>';

		echo $html;

	}

	public function sample_future_permalink_validation($input){

		return intval($input, 10) == 1 ? '1' : '0';

	}

    public function import_mode_callback($args){

        $html = '<select id="da-hm-auto-delete" name="da_hm_import_mode">';
        $html .= '<option ' . selected(get_option("da_hm_import_mode"), 'exact_copy', false) . ' value="exact_copy">' . __('Exact Copy', 'dahm') . '</option>';
        $html .= '<option ' . selected(get_option("da_hm_import_mode"), 'import_options', false) . ' value="import_options">' . __('Based on Import Options', 'dahm') . '</option>';
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('Select "Exact Copy" to import an exact copy of the connections stored in your XML file or "Based on Import Options" to determine the "URL to Connect" value of the imported connections by using the "Import Language" and "Import Locale" options.', 'dahm') . '"></div>';

        echo $html;

    }

    public function import_mode_validation($input){

        if($input === 'exact_copy' or $input === 'import_options'){
            $output = $input;
        }else{
            $output = 'exact_copy';
        }

        return $output;

    }

    public function import_language_callback($args){
        $html = '<select id="da-hm-import-language" name="da_hm_import_language">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_import_language") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('This option is used to determine the "URL to Connect" value of the imported connections.', 'dahm') . '"></div>';
        echo $html;
    }

    public function import_language_validation($input){
        return $input;
    }

    public function import_locale_callback($args){
        $html = '<select id="da-hm-import-locale" name="da_hm_import_locale">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_import_locale") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('This option is used to determine the "URL to Connect" value of the imported connections.', 'dahm') . '"></div>';

        echo $html;
    }

    public function import_locale_validation($input){
        return $input;
    }

    public function meta_box_capability_callback($args){

        $html = '<input autocomplete="off" type="text" id="da_hm_meta_box_capability" name="da_hm_meta_box_capability" class="regular-text" value="' . esc_attr(get_option("da_hm_meta_box_capability")) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr__('The capability required to get access on the "Hreflang Manager" meta box.', 'dahm') . '"></div>';

        echo $html;

    }

    public function meta_box_capability_validation($input){

        if(!preg_match($this->shared->regex_capability, $input)){
            add_settings_error( 'da_hm_meta_box_capability', 'da_hm_meta_box_capability', __('Please enter a valid capability in the "Meta Box Capability" option.', 'dahm') );
            $output = get_option('da_hm_meta_box_capability');
        }else{
            $output = $input;
        }

        return $output;

    }

	public function editor_sidebar_capability_callback($args){

		$html = '<input autocomplete="off" type="text" id="da_hm_editor_sidebar_capability" name="da_hm_editor_sidebar_capability" class="regular-text" value="' . esc_attr(get_option("da_hm_editor_sidebar_capability")) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__('The capability required to get access on the editor sidebar.', 'dahm') . '"></div>';

		echo $html;

	}

	public function editor_sidebar_capability_validation($input){

		if(!preg_match($this->shared->regex_capability, $input)){
			add_settings_error( 'da_hm_editor_sidebar_capability', 'da_hm_editor_sidebar_capability', __('Please enter a valid capability in the "Editor Sidebar Capability" option.', 'dahm') );
			$output = get_option('da_hm_editor_sidebar_capability');
		}else{
			$output = $input;
		}

		return $output;

	}

    public function connections_menu_capability_callback($args){

    $html = '<input autocomplete="off" type="text" id="da_hm_connections_menu_capability" name="da_hm_connections_menu_capability" class="regular-text" value="' . esc_attr(get_option("da_hm_connections_menu_capability")) . '" />';
    $html .= '<div class="help-icon" title="' . esc_attr__('The capability required to get access on the "Connections" menu.', 'dahm') . '"></div>';

    echo $html;

    }

    public function connections_menu_capability_validation($input){

        if(!preg_match($this->shared->regex_capability, $input)){
            add_settings_error( 'da_hm_connections_menu_capability', 'da_hm_connections_menu_capability', __('Please enter a valid capability in the "Connections Menu Capability" option.', 'dahm') );
            $output = get_option('da_hm_connections_menu_capability');
        }else{
            $output = $input;
        }

        return $output;

    }

    public function import_menu_capability_callback($args){

        $html = '<input autocomplete="off" type="text" id="da_hm_import_menu_capability" name="da_hm_import_menu_capability" class="regular-text" value="' . esc_attr(get_option("da_hm_import_menu_capability")) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr__('The capability required to get access on the "Import" menu.', 'dahm') . '"></div>';

        echo $html;

    }

    public function import_menu_capability_validation($input){

        if(!preg_match($this->shared->regex_capability, $input)){
            add_settings_error( 'da_hm_import_menu_capability', 'da_hm_import_menu_capability', __('Please enter a valid capability in the "Import Menu Capability" option.', 'dahm') );
            $output = get_option('da_hm_import_menu_capability');
        }else{
            $output = $input;
        }

        return $output;

    }

    public function export_menu_capability_callback($args){

        $html = '<input autocomplete="off" type="text" id="da_hm_export_menu_capability" name="da_hm_export_menu_capability" class="regular-text" value="' . esc_attr(get_option("da_hm_export_menu_capability")) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr__('The capability required to get access on the "Export" menu.', 'dahm') . '"></div>';

        echo $html;

    }

    public function export_menu_capability_validation($input){

        if(!preg_match($this->shared->regex_capability, $input)){
            add_settings_error( 'da_hm_export_menu_capability', 'da_hm_export_menu_capability', __('Please enter a valid capability in the "Export Menu Capability" option.', 'dahm') );
            $output = get_option('da_hm_export_menu_capability');
        }else{
            $output = $input;
        }

        return $output;

    }

    //1 ----------------------------------------------------------------------------------------------------------------
    public function default_language_1_callback($args){
        $html = '<select id="da-hm-default-language-1" class="da-hm-default-language" name="da_hm_default_language_1">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_1") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('This option determines the default value of language', 'dahm')) . ' 1.' . '"></div>';
        echo $html;
    }

    public function default_language_1_validation($input){
        return $input;
    }

    public function default_locale_1_callback($args){
        $html = '<select id="da-hm-default-locale-1" class="da-hm-default-locale" name="da_hm_default_locale_1">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_1") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('This option determines the default value of locale', 'dahm')) . ' 1.' . '"></div>';

        echo $html;
    }

    public function default_locale_1_validation($input){
        return $input;
    }

    //2 ----------------------------------------------------------------------------------------------------------------
    public function default_language_2_callback($args){
        $html = '<select id="da-hm-default-language-2" class="da-hm-default-language" name="da_hm_default_language_2">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_2") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('This option determines the default value of language', 'dahm')) . ' 2.' . '"></div>';
        echo $html;
    }

    public function default_language_2_validation($input){
        return $input;
    }

    public function default_locale_2_callback($args){
        $html = '<select id="da-hm-default-locale-2" class="da-hm-default-locale" name="da_hm_default_locale_2">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_2") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('This option determines the default value of locale', 'dahm')) . ' 2.' . '"></div>';
        echo $html;
    }

    public function default_locale_2_validation($input){
        return $input;
    }

    //3 ----------------------------------------------------------------------------------------------------------------
    public function default_language_3_callback($args){
        $html = '<select id="da-hm-default-language-3" class="da-hm-default-language" name="da_hm_default_language_3">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_3") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('This option determines the default value of language', 'dahm')) . ' 3.' . '"></div>';
        echo $html;
    }

    public function default_language_3_validation($input){
        return $input;
    }

    public function default_locale_3_callback($args){
        $html = '<select id="da-hm-default-locale-3" class="da-hm-default-locale" name="da_hm_default_locale_3">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_3") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('This option determines the default value of locale', 'dahm')) . ' 3.' . '"></div>';

        echo $html;
    }

    public function default_locale_3_validation($input){
        return $input;
    }

    //4 ----------------------------------------------------------------------------------------------------------------
    public function default_language_4_callback($args){
        $html = '<select id="da-hm-default-language-4" class="da-hm-default-language" name="da_hm_default_language_4">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_4") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('This option determines the default value of language', 'dahm')) . ' 4.' . '"></div>';
        echo $html;
    }

    public function default_language_4_validation($input){
        return $input;
    }

    public function default_locale_4_callback($args){
        $html = '<select id="da-hm-default-locale-4" class="da-hm-default-locale" name="da_hm_default_locale_4">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_4") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('This option determines the default value of locale', 'dahm')) . ' 4.' . '"></div>';

        echo $html;
    }

    public function default_locale_4_validation($input){
        return $input;
    }

    //5 ----------------------------------------------------------------------------------------------------------------
    public function default_language_5_callback($args){
        $html = '<select id="da-hm-default-language-5" class="da-hm-default-language" name="da_hm_default_language_5">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_5") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('This option determines the default value of language', 'dahm')) . ' 5.' . '"></div>';
        echo $html;
    }

    public function default_language_5_validation($input){
        return $input;
    }

    public function default_locale_5_callback($args){
        $html = '<select id="da-hm-default-locale-5" class="da-hm-default-locale" name="da_hm_default_locale_5">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_5") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('This option determines the default value of locale', 'dahm')) . ' 5.' . '"></div>';

        echo $html;
    }

    public function default_locale_5_validation($input){
        return $input;
    }

    //6 ----------------------------------------------------------------------------------------------------------------
    public function default_language_6_callback($args){
        $html = '<select id="da-hm-default-language-6" class="da-hm-default-language" name="da_hm_default_language_6">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_6") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('This option determines the default value of language', 'dahm')) . ' 6.' . '"></div>';
        echo $html;
    }

    public function default_language_6_validation($input){
        return $input;
    }

    public function default_locale_6_callback($args){
        $html = '<select id="da-hm-default-locale-6" class="da-hm-default-locale" name="da_hm_default_locale_6">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_6") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('This option determines the default value of locale', 'dahm')) . ' 6.' . '"></div>';

        echo $html;
    }

    public function default_locale_6_validation($input){
        return $input;
    }

    //7 ----------------------------------------------------------------------------------------------------------------
    public function default_language_7_callback($args){
        $html = '<select id="da-hm-default-language-7" class="da-hm-default-language" name="da_hm_default_language_7">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_7") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('This option determines the default value of language', 'dahm')) . ' 7.' . '"></div>';
        echo $html;
    }

    public function default_language_7_validation($input){
        return $input;
    }

    public function default_locale_7_callback($args){
        $html = '<select id="da-hm-default-locale-7" class="da-hm-default-locale" name="da_hm_default_locale_7">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_7") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('This option determines the default value of locale', 'dahm')) . ' 7.' . '"></div>';

        echo $html;
    }

    public function default_locale_7_validation($input){
        return $input;
    }

    //8 ----------------------------------------------------------------------------------------------------------------
    public function default_language_8_callback($args){
        $html = '<select id="da-hm-default-language-8" class="da-hm-default-language" name="da_hm_default_language_8">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_8") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('This option determines the default value of language', 'dahm')) . ' 8.' . '"></div>';
        echo $html;
    }

    public function default_language_8_validation($input){
        return $input;
    }

    public function default_locale_8_callback($args){
        $html = '<select id="da-hm-default-locale-8" class="da-hm-default-locale" name="da_hm_default_locale_8">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_8") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('This option determines the default value of locale', 'dahm')) . ' 8.' . '"></div>';

        echo $html;
    }

    public function default_locale_8_validation($input){
        return $input;
    }

    //9 ----------------------------------------------------------------------------------------------------------------
    public function default_language_9_callback($args){
        $html = '<select id="da-hm-default-language-9" class="da-hm-default-language" name="da_hm_default_language_9">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_9") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('This option determines the default value of language', 'dahm')) . ' 9.' . '"></div>';
        echo $html;
    }

    public function default_language_9_validation($input){
        return $input;
    }

    public function default_locale_9_callback($args){
        $html = '<select id="da-hm-default-locale-9" class="da-hm-default-locale" name="da_hm_default_locale_9">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_9") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('This option determines the default value of locale', 'dahm')) . ' 9.' . '"></div>';

        echo $html;
    }

    public function default_locale_9_validation($input){
        return $input;
    }

    //10 ----------------------------------------------------------------------------------------------------------------
    public function default_language_10_callback($args){
        $html = '<select id="da-hm-default-language-10" class="da-hm-default-language" name="da_hm_default_language_10">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_10") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 10' . '"></div>';
        echo $html;
    }

    public function default_language_10_validation($input){
        return $input;
    }

    public function default_locale_10_callback($args){
        $html = '<select id="da-hm-default-locale-10" class="da-hm-default-locale" name="da_hm_default_locale_10">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_10") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 10' . '"></div>';

        echo $html;
    }

    public function default_locale_10_validation($input){
        return $input;
    }

    //11 ----------------------------------------------------------------------------------------------------------------
    public function default_language_11_callback($args){
        $html = '<select id="da-hm-default-language-11" class="da-hm-default-language" name="da_hm_default_language_11">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_11") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 11' . '"></div>';
        echo $html;
    }

    public function default_language_11_validation($input){
        return $input;
    }

    public function default_locale_11_callback($args){
        $html = '<select id="da-hm-default-locale-11" class="da-hm-default-locale" name="da_hm_default_locale_11">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_11") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 11' . '"></div>';

        echo $html;
    }

    public function default_locale_11_validation($input){
        return $input;
    }

    //12 ----------------------------------------------------------------------------------------------------------------
    public function default_language_12_callback($args){
        $html = '<select id="da-hm-default-language-12" class="da-hm-default-language" name="da_hm_default_language_12">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_12") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 12' . '"></div>';
        echo $html;
    }

    public function default_language_12_validation($input){
        return $input;
    }

    public function default_locale_12_callback($args){
        $html = '<select id="da-hm-default-locale-12" class="da-hm-default-locale" name="da_hm_default_locale_12">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_12") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 12' . '"></div>';

        echo $html;
    }

    public function default_locale_12_validation($input){
        return $input;
    }

    //13 ----------------------------------------------------------------------------------------------------------------
    public function default_language_13_callback($args){
        $html = '<select id="da-hm-default-language-13" class="da-hm-default-language" name="da_hm_default_language_13">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_13") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 13' . '"></div>';
        echo $html;
    }

    public function default_language_13_validation($input){
        return $input;
    }

    public function default_locale_13_callback($args){
        $html = '<select id="da-hm-default-locale-13" class="da-hm-default-locale" name="da_hm_default_locale_13">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_13") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 13' . '"></div>';

        echo $html;
    }

    public function default_locale_13_validation($input){
        return $input;
    }

    //14 ----------------------------------------------------------------------------------------------------------------
    public function default_language_14_callback($args){
        $html = '<select id="da-hm-default-language-14" class="da-hm-default-language" name="da_hm_default_language_14">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_14") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 14' . '"></div>';
        echo $html;
    }

    public function default_language_14_validation($input){
        return $input;
    }

    public function default_locale_14_callback($args){
        $html = '<select id="da-hm-default-locale-14" class="da-hm-default-locale" name="da_hm_default_locale_14">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_14") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 14' . '"></div>';

        echo $html;
    }

    public function default_locale_14_validation($input){
        return $input;
    }

    //15 ----------------------------------------------------------------------------------------------------------------
    public function default_language_15_callback($args){
        $html = '<select id="da-hm-default-language-15" class="da-hm-default-language" name="da_hm_default_language_15">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_15") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 15' . '"></div>';
        echo $html;
    }

    public function default_language_15_validation($input){
        return $input;
    }

    public function default_locale_15_callback($args){
        $html = '<select id="da-hm-default-locale-15" class="da-hm-default-locale" name="da_hm_default_locale_15">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_15") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 15' . '"></div>';

        echo $html;
    }

    public function default_locale_15_validation($input){
        return $input;
    }

    //16 ----------------------------------------------------------------------------------------------------------------
    public function default_language_16_callback($args){
        $html = '<select id="da-hm-default-language-16" class="da-hm-default-language" name="da_hm_default_language_16">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_16") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 16' . '"></div>';
        echo $html;
    }

    public function default_language_16_validation($input){
        return $input;
    }

    public function default_locale_16_callback($args){
        $html = '<select id="da-hm-default-locale-16" class="da-hm-default-locale" name="da_hm_default_locale_16">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_16") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 16' . '"></div>';

        echo $html;
    }

    public function default_locale_16_validation($input){
        return $input;
    }

    //17 ----------------------------------------------------------------------------------------------------------------
    public function default_language_17_callback($args){
        $html = '<select id="da-hm-default-language-17" class="da-hm-default-language" name="da_hm_default_language_17">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_17") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 17' . '"></div>';
        echo $html;
    }

    public function default_language_17_validation($input){
        return $input;
    }

    public function default_locale_17_callback($args){
        $html = '<select id="da-hm-default-locale-17" class="da-hm-default-locale" name="da_hm_default_locale_17">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_17") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 17' . '"></div>';

        echo $html;
    }

    public function default_locale_17_validation($input){
        return $input;
    }

    //18 ----------------------------------------------------------------------------------------------------------------
    public function default_language_18_callback($args){
        $html = '<select id="da-hm-default-language-18" class="da-hm-default-language" name="da_hm_default_language_18">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_18") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 18' . '"></div>';
        echo $html;
    }

    public function default_language_18_validation($input){
        return $input;
    }

    public function default_locale_18_callback($args){
        $html = '<select id="da-hm-default-locale-18" class="da-hm-default-locale" name="da_hm_default_locale_18">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_18") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 18' . '"></div>';

        echo $html;
    }

    public function default_locale_18_validation($input){
        return $input;
    }

    //19 ----------------------------------------------------------------------------------------------------------------
    public function default_language_19_callback($args){
        $html = '<select id="da-hm-default-language-19" class="da-hm-default-language" name="da_hm_default_language_19">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_19") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 19' . '"></div>';
        echo $html;
    }

    public function default_language_19_validation($input){
        return $input;
    }

    public function default_locale_19_callback($args){
        $html = '<select id="da-hm-default-locale-19" class="da-hm-default-locale" name="da_hm_default_locale_19">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_19") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 19' . '"></div>';

        echo $html;
    }

    public function default_locale_19_validation($input){
        return $input;
    }

    //20 ----------------------------------------------------------------------------------------------------------------
    public function default_language_20_callback($args){
        $html = '<select id="da-hm-default-language-20" class="da-hm-default-language" name="da_hm_default_language_20">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_20") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 20' . '"></div>';
        echo $html;
    }

    public function default_language_20_validation($input){
        return $input;
    }

    public function default_locale_20_callback($args){
        $html = '<select id="da-hm-default-locale-20" class="da-hm-default-locale" name="da_hm_default_locale_20">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_20") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 20' . '"></div>';

        echo $html;
    }

    public function default_locale_20_validation($input){
        return $input;
    }

    //21 ----------------------------------------------------------------------------------------------------------------
    public function default_language_21_callback($args){
        $html = '<select id="da-hm-default-language-21" class="da-hm-default-language" name="da_hm_default_language_21">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_21") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 21' . '"></div>';
        echo $html;
    }

    public function default_language_21_validation($input){
        return $input;
    }

    public function default_locale_21_callback($args){
        $html = '<select id="da-hm-default-locale-21" class="da-hm-default-locale" name="da_hm_default_locale_21">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_21") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 21' . '"></div>';

        echo $html;
    }

    public function default_locale_21_validation($input){
        return $input;
    }

    //22 ----------------------------------------------------------------------------------------------------------------
    public function default_language_22_callback($args){
        $html = '<select id="da-hm-default-language-22" class="da-hm-default-language" name="da_hm_default_language_22">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_22") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 22' . '"></div>';
        echo $html;
    }

    public function default_language_22_validation($input){
        return $input;
    }

    public function default_locale_22_callback($args){
        $html = '<select id="da-hm-default-locale-22" class="da-hm-default-locale" name="da_hm_default_locale_22">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_22") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 22' . '"></div>';

        echo $html;
    }

    public function default_locale_22_validation($input){
        return $input;
    }

    //23 ----------------------------------------------------------------------------------------------------------------
    public function default_language_23_callback($args){
        $html = '<select id="da-hm-default-language-23" class="da-hm-default-language" name="da_hm_default_language_23">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_23") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 23' . '"></div>';
        echo $html;
    }

    public function default_language_23_validation($input){
        return $input;
    }

    public function default_locale_23_callback($args){
        $html = '<select id="da-hm-default-locale-23" class="da-hm-default-locale" name="da_hm_default_locale_23">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_23") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 23' . '"></div>';

        echo $html;
    }

    public function default_locale_23_validation($input){
        return $input;
    }

    //24 ----------------------------------------------------------------------------------------------------------------
    public function default_language_24_callback($args){
        $html = '<select id="da-hm-default-language-24" class="da-hm-default-language" name="da_hm_default_language_24">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_24") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 24' . '"></div>';
        echo $html;
    }

    public function default_language_24_validation($input){
        return $input;
    }

    public function default_locale_24_callback($args){
        $html = '<select id="da-hm-default-locale-24" class="da-hm-default-locale" name="da_hm_default_locale_24">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_24") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 24' . '"></div>';

        echo $html;
    }

    public function default_locale_24_validation($input){
        return $input;
    }

    //25 ----------------------------------------------------------------------------------------------------------------
    public function default_language_25_callback($args){
        $html = '<select id="da-hm-default-language-25" class="da-hm-default-language" name="da_hm_default_language_25">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_25") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 25' . '"></div>';
        echo $html;
    }

    public function default_language_25_validation($input){
        return $input;
    }

    public function default_locale_25_callback($args){
        $html = '<select id="da-hm-default-locale-25" class="da-hm-default-locale" name="da_hm_default_locale_25">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_25") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 25' . '"></div>';

        echo $html;
    }

    public function default_locale_25_validation($input){
        return $input;
    }

    //26 ----------------------------------------------------------------------------------------------------------------
    public function default_language_26_callback($args){
        $html = '<select id="da-hm-default-language-26" class="da-hm-default-language" name="da_hm_default_language_26">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_26") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 26' . '"></div>';
        echo $html;
    }

    public function default_language_26_validation($input){
        return $input;
    }

    public function default_locale_26_callback($args){
        $html = '<select id="da-hm-default-locale-26" class="da-hm-default-locale" name="da_hm_default_locale_26">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_26") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 26' . '"></div>';

        echo $html;
    }

    public function default_locale_26_validation($input){
        return $input;
    }

    //27 ----------------------------------------------------------------------------------------------------------------
    public function default_language_27_callback($args){
        $html = '<select id="da-hm-default-language-27" class="da-hm-default-language" name="da_hm_default_language_27">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_27") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 27' . '"></div>';
        echo $html;
    }

    public function default_language_27_validation($input){
        return $input;
    }

    public function default_locale_27_callback($args){
        $html = '<select id="da-hm-default-locale-27" class="da-hm-default-locale" name="da_hm_default_locale_27">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_27") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 27' . '"></div>';

        echo $html;
    }

    public function default_locale_27_validation($input){
        return $input;
    }

    //28 ----------------------------------------------------------------------------------------------------------------
    public function default_language_28_callback($args){
        $html = '<select id="da-hm-default-language-28" class="da-hm-default-language" name="da_hm_default_language_28">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_28") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 28' . '"></div>';
        echo $html;
    }

    public function default_language_28_validation($input){
        return $input;
    }

    public function default_locale_28_callback($args){
        $html = '<select id="da-hm-default-locale-28" class="da-hm-default-locale" name="da_hm_default_locale_28">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_28") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 28' . '"></div>';

        echo $html;
    }

    public function default_locale_28_validation($input){
        return $input;
    }

    //29 ----------------------------------------------------------------------------------------------------------------
    public function default_language_29_callback($args){
        $html = '<select id="da-hm-default-language-29" class="da-hm-default-language" name="da_hm_default_language_29">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_29") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 29' . '"></div>';
        echo $html;
    }

    public function default_language_29_validation($input){
        return $input;
    }

    public function default_locale_29_callback($args){
        $html = '<select id="da-hm-default-locale-29" class="da-hm-default-locale" name="da_hm_default_locale_29">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_29") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 29' . '"></div>';

        echo $html;
    }

    public function default_locale_29_validation($input){
        return $input;
    }

    //30 ----------------------------------------------------------------------------------------------------------------
    public function default_language_30_callback($args){
        $html = '<select id="da-hm-default-language-30" class="da-hm-default-language" name="da_hm_default_language_30">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_30") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 30' . '"></div>';
        echo $html;
    }

    public function default_language_30_validation($input){
        return $input;
    }

    public function default_locale_30_callback($args){
        $html = '<select id="da-hm-default-locale-30" class="da-hm-default-locale" name="da_hm_default_locale_30">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_30") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 30' . '"></div>';

        echo $html;
    }

    public function default_locale_30_validation($input){
        return $input;
    }

    //31 ----------------------------------------------------------------------------------------------------------------
    public function default_language_31_callback($args){
        $html = '<select id="da-hm-default-language-31" class="da-hm-default-language" name="da_hm_default_language_31">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_31") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 31' . '"></div>';
        echo $html;
    }

    public function default_language_31_validation($input){
        return $input;
    }

    public function default_locale_31_callback($args){
        $html = '<select id="da-hm-default-locale-31" class="da-hm-default-locale" name="da_hm_default_locale_31">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_31") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 31' . '"></div>';

        echo $html;
    }

    public function default_locale_31_validation($input){
        return $input;
    }

    //32 ----------------------------------------------------------------------------------------------------------------
    public function default_language_32_callback($args){
        $html = '<select id="da-hm-default-language-32" class="da-hm-default-language" name="da_hm_default_language_32">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_32") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 32' . '"></div>';
        echo $html;
    }

    public function default_language_32_validation($input){
        return $input;
    }

    public function default_locale_32_callback($args){
        $html = '<select id="da-hm-default-locale-32" class="da-hm-default-locale" name="da_hm_default_locale_32">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_32") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 32' . '"></div>';

        echo $html;
    }

    public function default_locale_32_validation($input){
        return $input;
    }

    //33 ----------------------------------------------------------------------------------------------------------------
    public function default_language_33_callback($args){
        $html = '<select id="da-hm-default-language-33" class="da-hm-default-language" name="da_hm_default_language_33">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_33") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 33' . '"></div>';
        echo $html;
    }

    public function default_language_33_validation($input){
        return $input;
    }

    public function default_locale_33_callback($args){
        $html = '<select id="da-hm-default-locale-33" class="da-hm-default-locale" name="da_hm_default_locale_33">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_33") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 33' . '"></div>';

        echo $html;
    }

    public function default_locale_33_validation($input){
        return $input;
    }

    //34 ----------------------------------------------------------------------------------------------------------------
    public function default_language_34_callback($args){
        $html = '<select id="da-hm-default-language-34" class="da-hm-default-language" name="da_hm_default_language_34">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_34") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 34' . '"></div>';
        echo $html;
    }

    public function default_language_34_validation($input){
        return $input;
    }

    public function default_locale_34_callback($args){
        $html = '<select id="da-hm-default-locale-34" class="da-hm-default-locale" name="da_hm_default_locale_34">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_34") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 34' . '"></div>';

        echo $html;
    }

    public function default_locale_34_validation($input){
        return $input;
    }

    //35 ----------------------------------------------------------------------------------------------------------------
    public function default_language_35_callback($args){
        $html = '<select id="da-hm-default-language-35" class="da-hm-default-language" name="da_hm_default_language_35">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_35") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 35' . '"></div>';
        echo $html;
    }

    public function default_language_35_validation($input){
        return $input;
    }

    public function default_locale_35_callback($args){
        $html = '<select id="da-hm-default-locale-35" class="da-hm-default-locale" name="da_hm_default_locale_35">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_35") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 35' . '"></div>';

        echo $html;
    }

    public function default_locale_35_validation($input){
        return $input;
    }

    //36 ----------------------------------------------------------------------------------------------------------------
    public function default_language_36_callback($args){
        $html = '<select id="da-hm-default-language-36" class="da-hm-default-language" name="da_hm_default_language_36">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_36") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 36' . '"></div>';
        echo $html;
    }

    public function default_language_36_validation($input){
        return $input;
    }

    public function default_locale_36_callback($args){
        $html = '<select id="da-hm-default-locale-36" class="da-hm-default-locale" name="da_hm_default_locale_36">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_36") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 36' . '"></div>';

        echo $html;
    }

    public function default_locale_36_validation($input){
        return $input;
    }

    //37 ----------------------------------------------------------------------------------------------------------------
    public function default_language_37_callback($args){
        $html = '<select id="da-hm-default-language-37" class="da-hm-default-language" name="da_hm_default_language_37">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_37") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 37' . '"></div>';
        echo $html;
    }

    public function default_language_37_validation($input){
        return $input;
    }

    public function default_locale_37_callback($args){
        $html = '<select id="da-hm-default-locale-37" class="da-hm-default-locale" name="da_hm_default_locale_37">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_37") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 37' . '"></div>';

        echo $html;
    }

    public function default_locale_37_validation($input){
        return $input;
    }

    //38 ----------------------------------------------------------------------------------------------------------------
    public function default_language_38_callback($args){
        $html = '<select id="da-hm-default-language-38" class="da-hm-default-language" name="da_hm_default_language_38">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_38") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 38' . '"></div>';
        echo $html;
    }

    public function default_language_38_validation($input){
        return $input;
    }

    public function default_locale_38_callback($args){
        $html = '<select id="da-hm-default-locale-38" class="da-hm-default-locale" name="da_hm_default_locale_38">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_38") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 38' . '"></div>';

        echo $html;
    }

    public function default_locale_38_validation($input){
        return $input;
    }

    //39 ----------------------------------------------------------------------------------------------------------------
    public function default_language_39_callback($args){
        $html = '<select id="da-hm-default-language-39" class="da-hm-default-language" name="da_hm_default_language_39">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_39") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 39' . '"></div>';
        echo $html;
    }

    public function default_language_39_validation($input){
        return $input;
    }

    public function default_locale_39_callback($args){
        $html = '<select id="da-hm-default-locale-39" class="da-hm-default-locale" name="da_hm_default_locale_39">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_39") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 39' . '"></div>';

        echo $html;
    }

    public function default_locale_39_validation($input){
        return $input;
    }

    //40 ----------------------------------------------------------------------------------------------------------------
    public function default_language_40_callback($args){
        $html = '<select id="da-hm-default-language-40" class="da-hm-default-language" name="da_hm_default_language_40">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_40") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 40' . '"></div>';
        echo $html;
    }

    public function default_language_40_validation($input){
        return $input;
    }

    public function default_locale_40_callback($args){
        $html = '<select id="da-hm-default-locale-40" class="da-hm-default-locale" name="da_hm_default_locale_40">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_40") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 40' . '"></div>';

        echo $html;
    }

    public function default_locale_40_validation($input){
        return $input;
    }

    //41 ----------------------------------------------------------------------------------------------------------------
    public function default_language_41_callback($args){
        $html = '<select id="da-hm-default-language-41" class="da-hm-default-language" name="da_hm_default_language_41">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_41") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 41' . '"></div>';
        echo $html;
    }

    public function default_language_41_validation($input){
        return $input;
    }

    public function default_locale_41_callback($args){
        $html = '<select id="da-hm-default-locale-41" class="da-hm-default-locale" name="da_hm_default_locale_41">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_41") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 41' . '"></div>';

        echo $html;
    }

    public function default_locale_41_validation($input){
        return $input;
    }

    //42 ----------------------------------------------------------------------------------------------------------------
    public function default_language_42_callback($args){
        $html = '<select id="da-hm-default-language-42" class="da-hm-default-language" name="da_hm_default_language_42">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_42") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 42' . '"></div>';
        echo $html;
    }

    public function default_language_42_validation($input){
        return $input;
    }

    public function default_locale_42_callback($args){
        $html = '<select id="da-hm-default-locale-42" class="da-hm-default-locale" name="da_hm_default_locale_42">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_42") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 42' . '"></div>';

        echo $html;
    }

    public function default_locale_42_validation($input){
        return $input;
    }

    //43 ----------------------------------------------------------------------------------------------------------------
    public function default_language_43_callback($args){
        $html = '<select id="da-hm-default-language-43" class="da-hm-default-language" name="da_hm_default_language_43">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_43") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 43' . '"></div>';
        echo $html;
    }

    public function default_language_43_validation($input){
        return $input;
    }

    public function default_locale_43_callback($args){
        $html = '<select id="da-hm-default-locale-43" class="da-hm-default-locale" name="da_hm_default_locale_43">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_43") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 43' . '"></div>';

        echo $html;
    }

    public function default_locale_43_validation($input){
        return $input;
    }

    //44 ----------------------------------------------------------------------------------------------------------------
    public function default_language_44_callback($args){
        $html = '<select id="da-hm-default-language-44" class="da-hm-default-language" name="da_hm_default_language_44">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_44") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 44' . '"></div>';
        echo $html;
    }

    public function default_language_44_validation($input){
        return $input;
    }

    public function default_locale_44_callback($args){
        $html = '<select id="da-hm-default-locale-44" class="da-hm-default-locale" name="da_hm_default_locale_44">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_44") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 44' . '"></div>';

        echo $html;
    }

    public function default_locale_44_validation($input){
        return $input;
    }

    //45 ----------------------------------------------------------------------------------------------------------------
    public function default_language_45_callback($args){
        $html = '<select id="da-hm-default-language-45" class="da-hm-default-language" name="da_hm_default_language_45">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_45") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 45' . '"></div>';
        echo $html;
    }

    public function default_language_45_validation($input){
        return $input;
    }

    public function default_locale_45_callback($args){
        $html = '<select id="da-hm-default-locale-45" class="da-hm-default-locale" name="da_hm_default_locale_45">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_45") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 45' . '"></div>';

        echo $html;
    }

    public function default_locale_45_validation($input){
        return $input;
    }

    //46 ----------------------------------------------------------------------------------------------------------------
    public function default_language_46_callback($args){
        $html = '<select id="da-hm-default-language-46" class="da-hm-default-language" name="da_hm_default_language_46">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_46") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 46' . '"></div>';
        echo $html;
    }

    public function default_language_46_validation($input){
        return $input;
    }

    public function default_locale_46_callback($args){
        $html = '<select id="da-hm-default-locale-46" class="da-hm-default-locale" name="da_hm_default_locale_46">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_46") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 46' . '"></div>';

        echo $html;
    }

    public function default_locale_46_validation($input){
        return $input;
    }

    //47 ----------------------------------------------------------------------------------------------------------------
    public function default_language_47_callback($args){
        $html = '<select id="da-hm-default-language-47" class="da-hm-default-language" name="da_hm_default_language_47">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_47") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 47' . '"></div>';
        echo $html;
    }

    public function default_language_47_validation($input){
        return $input;
    }

    public function default_locale_47_callback($args){
        $html = '<select id="da-hm-default-locale-47" class="da-hm-default-locale" name="da_hm_default_locale_47">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_47") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 47' . '"></div>';

        echo $html;
    }

    public function default_locale_47_validation($input){
        return $input;
    }

    //48 ----------------------------------------------------------------------------------------------------------------
    public function default_language_48_callback($args){
        $html = '<select id="da-hm-default-language-48" class="da-hm-default-language" name="da_hm_default_language_48">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_48") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 48' . '"></div>';
        echo $html;
    }

    public function default_language_48_validation($input){
        return $input;
    }

    public function default_locale_48_callback($args){
        $html = '<select id="da-hm-default-locale-48" class="da-hm-default-locale" name="da_hm_default_locale_48">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_48") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 48' . '"></div>';

        echo $html;
    }

    public function default_locale_48_validation($input){
        return $input;
    }

    //49 ----------------------------------------------------------------------------------------------------------------
    public function default_language_49_callback($args){
        $html = '<select id="da-hm-default-language-49" class="da-hm-default-language" name="da_hm_default_language_49">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_49") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 49' . '"></div>';
        echo $html;
    }

    public function default_language_49_validation($input){
        return $input;
    }

    public function default_locale_49_callback($args){
        $html = '<select id="da-hm-default-locale-49" class="da-hm-default-locale" name="da_hm_default_locale_49">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_49") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 49' . '"></div>';

        echo $html;
    }

    public function default_locale_49_validation($input){
        return $input;
    }

    //50 ----------------------------------------------------------------------------------------------------------------
    public function default_language_50_callback($args){
        $html = '<select id="da-hm-default-language-50" class="da-hm-default-language" name="da_hm_default_language_50">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_50") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 50' . '"></div>';
        echo $html;
    }

    public function default_language_50_validation($input){
        return $input;
    }

    public function default_locale_50_callback($args){
        $html = '<select id="da-hm-default-locale-50" class="da-hm-default-locale" name="da_hm_default_locale_50">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_50") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 50' . '"></div>';

        echo $html;
    }

    public function default_locale_50_validation($input){
        return $input;
    }

    //51 ----------------------------------------------------------------------------------------------------------------
    public function default_language_51_callback($args){
        $html = '<select id="da-hm-default-language-51" class="da-hm-default-language" name="da_hm_default_language_51">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_51") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 51' . '"></div>';
        echo $html;
    }

    public function default_language_51_validation($input){
        return $input;
    }

    public function default_locale_51_callback($args){
        $html = '<select id="da-hm-default-locale-51" class="da-hm-default-locale" name="da_hm_default_locale_51">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_51") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 51' . '"></div>';

        echo $html;
    }

    public function default_locale_51_validation($input){
        return $input;
    }

    //52 ----------------------------------------------------------------------------------------------------------------
    public function default_language_52_callback($args){
        $html = '<select id="da-hm-default-language-52" class="da-hm-default-language" name="da_hm_default_language_52">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_52") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 52' . '"></div>';
        echo $html;
    }

    public function default_language_52_validation($input){
        return $input;
    }

    public function default_locale_52_callback($args){
        $html = '<select id="da-hm-default-locale-52" class="da-hm-default-locale" name="da_hm_default_locale_52">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_52") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 52' . '"></div>';

        echo $html;
    }

    public function default_locale_52_validation($input){
        return $input;
    }

    //53 ----------------------------------------------------------------------------------------------------------------
    public function default_language_53_callback($args){
        $html = '<select id="da-hm-default-language-53" class="da-hm-default-language" name="da_hm_default_language_53">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_53") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 53' . '"></div>';
        echo $html;
    }

    public function default_language_53_validation($input){
        return $input;
    }

    public function default_locale_53_callback($args){
        $html = '<select id="da-hm-default-locale-53" class="da-hm-default-locale" name="da_hm_default_locale_53">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_53") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 53' . '"></div>';

        echo $html;
    }

    public function default_locale_53_validation($input){
        return $input;
    }

    //54 ----------------------------------------------------------------------------------------------------------------
    public function default_language_54_callback($args){
        $html = '<select id="da-hm-default-language-54" class="da-hm-default-language" name="da_hm_default_language_54">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_54") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 54' . '"></div>';
        echo $html;
    }

    public function default_language_54_validation($input){
        return $input;
    }

    public function default_locale_54_callback($args){
        $html = '<select id="da-hm-default-locale-54" class="da-hm-default-locale" name="da_hm_default_locale_54">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_54") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 54' . '"></div>';

        echo $html;
    }

    public function default_locale_54_validation($input){
        return $input;
    }

    //55 ----------------------------------------------------------------------------------------------------------------
    public function default_language_55_callback($args){
        $html = '<select id="da-hm-default-language-55" class="da-hm-default-language" name="da_hm_default_language_55">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_55") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 55' . '"></div>';
        echo $html;
    }

    public function default_language_55_validation($input){
        return $input;
    }

    public function default_locale_55_callback($args){
        $html = '<select id="da-hm-default-locale-55" class="da-hm-default-locale" name="da_hm_default_locale_55">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_55") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 55' . '"></div>';

        echo $html;
    }

    public function default_locale_55_validation($input){
        return $input;
    }

    //56 ----------------------------------------------------------------------------------------------------------------
    public function default_language_56_callback($args){
        $html = '<select id="da-hm-default-language-56" class="da-hm-default-language" name="da_hm_default_language_56">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_56") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 56' . '"></div>';
        echo $html;
    }

    public function default_language_56_validation($input){
        return $input;
    }

    public function default_locale_56_callback($args){
        $html = '<select id="da-hm-default-locale-56" class="da-hm-default-locale" name="da_hm_default_locale_56">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_56") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 56' . '"></div>';

        echo $html;
    }

    public function default_locale_56_validation($input){
        return $input;
    }

    //57 ----------------------------------------------------------------------------------------------------------------
    public function default_language_57_callback($args){
        $html = '<select id="da-hm-default-language-57" class="da-hm-default-language" name="da_hm_default_language_57">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_57") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 57' . '"></div>';
        echo $html;
    }

    public function default_language_57_validation($input){
        return $input;
    }

    public function default_locale_57_callback($args){
        $html = '<select id="da-hm-default-locale-57" class="da-hm-default-locale" name="da_hm_default_locale_57">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_57") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 57' . '"></div>';

        echo $html;
    }

    public function default_locale_57_validation($input){
        return $input;
    }

    //58 ----------------------------------------------------------------------------------------------------------------
    public function default_language_58_callback($args){
        $html = '<select id="da-hm-default-language-58" class="da-hm-default-language" name="da_hm_default_language_58">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_58") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 58' . '"></div>';
        echo $html;
    }

    public function default_language_58_validation($input){
        return $input;
    }

    public function default_locale_58_callback($args){
        $html = '<select id="da-hm-default-locale-58" class="da-hm-default-locale" name="da_hm_default_locale_58">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_58") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 58' . '"></div>';

        echo $html;
    }

    public function default_locale_58_validation($input){
        return $input;
    }

    //59 ----------------------------------------------------------------------------------------------------------------
    public function default_language_59_callback($args){
        $html = '<select id="da-hm-default-language-59" class="da-hm-default-language" name="da_hm_default_language_59">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_59") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 59' . '"></div>';
        echo $html;
    }

    public function default_language_59_validation($input){
        return $input;
    }

    public function default_locale_59_callback($args){
        $html = '<select id="da-hm-default-locale-59" class="da-hm-default-locale" name="da_hm_default_locale_59">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_59") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 59' . '"></div>';

        echo $html;
    }

    public function default_locale_59_validation($input){
        return $input;
    }

    //60 ----------------------------------------------------------------------------------------------------------------
    public function default_language_60_callback($args){
        $html = '<select id="da-hm-default-language-60" class="da-hm-default-language" name="da_hm_default_language_60">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_60") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 60' . '"></div>';
        echo $html;
    }

    public function default_language_60_validation($input){
        return $input;
    }

    public function default_locale_60_callback($args){
        $html = '<select id="da-hm-default-locale-60" class="da-hm-default-locale" name="da_hm_default_locale_60">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_60") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 60' . '"></div>';

        echo $html;
    }

    public function default_locale_60_validation($input){
        return $input;
    }

    //61 ----------------------------------------------------------------------------------------------------------------
    public function default_language_61_callback($args){
        $html = '<select id="da-hm-default-language-61" class="da-hm-default-language" name="da_hm_default_language_61">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_61") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 61' . '"></div>';
        echo $html;
    }

    public function default_language_61_validation($input){
        return $input;
    }

    public function default_locale_61_callback($args){
        $html = '<select id="da-hm-default-locale-61" class="da-hm-default-locale" name="da_hm_default_locale_61">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_61") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 61' . '"></div>';

        echo $html;
    }

    public function default_locale_61_validation($input){
        return $input;
    }

    //62 ----------------------------------------------------------------------------------------------------------------
    public function default_language_62_callback($args){
        $html = '<select id="da-hm-default-language-62" class="da-hm-default-language" name="da_hm_default_language_62">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_62") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 62' . '"></div>';
        echo $html;
    }

    public function default_language_62_validation($input){
        return $input;
    }

    public function default_locale_62_callback($args){
        $html = '<select id="da-hm-default-locale-62" class="da-hm-default-locale" name="da_hm_default_locale_62">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_62") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 62' . '"></div>';

        echo $html;
    }

    public function default_locale_62_validation($input){
        return $input;
    }

    //63 ----------------------------------------------------------------------------------------------------------------
    public function default_language_63_callback($args){
        $html = '<select id="da-hm-default-language-63" class="da-hm-default-language" name="da_hm_default_language_63">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_63") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 63' . '"></div>';
        echo $html;
    }

    public function default_language_63_validation($input){
        return $input;
    }

    public function default_locale_63_callback($args){
        $html = '<select id="da-hm-default-locale-63" class="da-hm-default-locale" name="da_hm_default_locale_63">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_63") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 63' . '"></div>';

        echo $html;
    }

    public function default_locale_63_validation($input){
        return $input;
    }

    //64 ----------------------------------------------------------------------------------------------------------------
    public function default_language_64_callback($args){
        $html = '<select id="da-hm-default-language-64" class="da-hm-default-language" name="da_hm_default_language_64">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_64") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 64' . '"></div>';
        echo $html;
    }

    public function default_language_64_validation($input){
        return $input;
    }

    public function default_locale_64_callback($args){
        $html = '<select id="da-hm-default-locale-64" class="da-hm-default-locale" name="da_hm_default_locale_64">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_64") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 64' . '"></div>';

        echo $html;
    }

    public function default_locale_64_validation($input){
        return $input;
    }

    //65 ----------------------------------------------------------------------------------------------------------------
    public function default_language_65_callback($args){
        $html = '<select id="da-hm-default-language-65" class="da-hm-default-language" name="da_hm_default_language_65">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_65") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 65' . '"></div>';
        echo $html;
    }

    public function default_language_65_validation($input){
        return $input;
    }

    public function default_locale_65_callback($args){
        $html = '<select id="da-hm-default-locale-65" class="da-hm-default-locale" name="da_hm_default_locale_65">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_65") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 65' . '"></div>';

        echo $html;
    }

    public function default_locale_65_validation($input){
        return $input;
    }

    //66 ----------------------------------------------------------------------------------------------------------------
    public function default_language_66_callback($args){
        $html = '<select id="da-hm-default-language-66" class="da-hm-default-language" name="da_hm_default_language_66">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_66") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 66' . '"></div>';
        echo $html;
    }

    public function default_language_66_validation($input){
        return $input;
    }

    public function default_locale_66_callback($args){
        $html = '<select id="da-hm-default-locale-66" class="da-hm-default-locale" name="da_hm_default_locale_66">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_66") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 66' . '"></div>';

        echo $html;
    }

    public function default_locale_66_validation($input){
        return $input;
    }

    //67 ----------------------------------------------------------------------------------------------------------------
    public function default_language_67_callback($args){
        $html = '<select id="da-hm-default-language-67" class="da-hm-default-language" name="da_hm_default_language_67">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_67") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 67' . '"></div>';
        echo $html;
    }

    public function default_language_67_validation($input){
        return $input;
    }

    public function default_locale_67_callback($args){
        $html = '<select id="da-hm-default-locale-67" class="da-hm-default-locale" name="da_hm_default_locale_67">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_67") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 67' . '"></div>';

        echo $html;
    }

    public function default_locale_67_validation($input){
        return $input;
    }

    //68 ----------------------------------------------------------------------------------------------------------------
    public function default_language_68_callback($args){
        $html = '<select id="da-hm-default-language-68" class="da-hm-default-language" name="da_hm_default_language_68">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_68") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 68' . '"></div>';
        echo $html;
    }

    public function default_language_68_validation($input){
        return $input;
    }

    public function default_locale_68_callback($args){
        $html = '<select id="da-hm-default-locale-68" class="da-hm-default-locale" name="da_hm_default_locale_68">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_68") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 68' . '"></div>';

        echo $html;
    }

    public function default_locale_68_validation($input){
        return $input;
    }

    //69 ----------------------------------------------------------------------------------------------------------------
    public function default_language_69_callback($args){
        $html = '<select id="da-hm-default-language-69" class="da-hm-default-language" name="da_hm_default_language_69">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_69") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 69' . '"></div>';
        echo $html;
    }

    public function default_language_69_validation($input){
        return $input;
    }

    public function default_locale_69_callback($args){
        $html = '<select id="da-hm-default-locale-69" class="da-hm-default-locale" name="da_hm_default_locale_69">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_69") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 69' . '"></div>';

        echo $html;
    }

    public function default_locale_69_validation($input){
        return $input;
    }

    //70 ----------------------------------------------------------------------------------------------------------------
    public function default_language_70_callback($args){
        $html = '<select id="da-hm-default-language-70" class="da-hm-default-language" name="da_hm_default_language_70">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_70") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 70' . '"></div>';
        echo $html;
    }

    public function default_language_70_validation($input){
        return $input;
    }

    public function default_locale_70_callback($args){
        $html = '<select id="da-hm-default-locale-70" class="da-hm-default-locale" name="da_hm_default_locale_70">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_70") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 70' . '"></div>';

        echo $html;
    }

    public function default_locale_70_validation($input){
        return $input;
    }

    //71 ----------------------------------------------------------------------------------------------------------------
    public function default_language_71_callback($args){
        $html = '<select id="da-hm-default-language-71" class="da-hm-default-language" name="da_hm_default_language_71">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_71") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 71' . '"></div>';
        echo $html;
    }

    public function default_language_71_validation($input){
        return $input;
    }

    public function default_locale_71_callback($args){
        $html = '<select id="da-hm-default-locale-71" class="da-hm-default-locale" name="da_hm_default_locale_71">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_71") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 71' . '"></div>';

        echo $html;
    }

    public function default_locale_71_validation($input){
        return $input;
    }

    //72 ----------------------------------------------------------------------------------------------------------------
    public function default_language_72_callback($args){
        $html = '<select id="da-hm-default-language-72" class="da-hm-default-language" name="da_hm_default_language_72">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_72") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 72' . '"></div>';
        echo $html;
    }

    public function default_language_72_validation($input){
        return $input;
    }

    public function default_locale_72_callback($args){
        $html = '<select id="da-hm-default-locale-72" class="da-hm-default-locale" name="da_hm_default_locale_72">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_72") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 72' . '"></div>';

        echo $html;
    }

    public function default_locale_72_validation($input){
        return $input;
    }

    //73 ----------------------------------------------------------------------------------------------------------------
    public function default_language_73_callback($args){
        $html = '<select id="da-hm-default-language-73" class="da-hm-default-language" name="da_hm_default_language_73">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_73") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 73' . '"></div>';
        echo $html;
    }

    public function default_language_73_validation($input){
        return $input;
    }

    public function default_locale_73_callback($args){
        $html = '<select id="da-hm-default-locale-73" class="da-hm-default-locale" name="da_hm_default_locale_73">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_73") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 73' . '"></div>';

        echo $html;
    }

    public function default_locale_73_validation($input){
        return $input;
    }

    //74 ----------------------------------------------------------------------------------------------------------------
    public function default_language_74_callback($args){
        $html = '<select id="da-hm-default-language-74" class="da-hm-default-language" name="da_hm_default_language_74">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_74") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 74' . '"></div>';
        echo $html;
    }

    public function default_language_74_validation($input){
        return $input;
    }

    public function default_locale_74_callback($args){
        $html = '<select id="da-hm-default-locale-74" class="da-hm-default-locale" name="da_hm_default_locale_74">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_74") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 74' . '"></div>';

        echo $html;
    }

    public function default_locale_74_validation($input){
        return $input;
    }

    //75 ----------------------------------------------------------------------------------------------------------------
    public function default_language_75_callback($args){
        $html = '<select id="da-hm-default-language-75" class="da-hm-default-language" name="da_hm_default_language_75">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_75") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 75' . '"></div>';
        echo $html;
    }

    public function default_language_75_validation($input){
        return $input;
    }

    public function default_locale_75_callback($args){
        $html = '<select id="da-hm-default-locale-75" class="da-hm-default-locale" name="da_hm_default_locale_75">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_75") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 75' . '"></div>';

        echo $html;
    }

    public function default_locale_75_validation($input){
        return $input;
    }

    //76 ----------------------------------------------------------------------------------------------------------------
    public function default_language_76_callback($args){
        $html = '<select id="da-hm-default-language-76" class="da-hm-default-language" name="da_hm_default_language_76">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_76") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 76' . '"></div>';
        echo $html;
    }

    public function default_language_76_validation($input){
        return $input;
    }

    public function default_locale_76_callback($args){
        $html = '<select id="da-hm-default-locale-76" class="da-hm-default-locale" name="da_hm_default_locale_76">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_76") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 76' . '"></div>';

        echo $html;
    }

    public function default_locale_76_validation($input){
        return $input;
    }

    //77 ----------------------------------------------------------------------------------------------------------------
    public function default_language_77_callback($args){
        $html = '<select id="da-hm-default-language-77" class="da-hm-default-language" name="da_hm_default_language_77">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_77") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 77' . '"></div>';
        echo $html;
    }

    public function default_language_77_validation($input){
        return $input;
    }

    public function default_locale_77_callback($args){
        $html = '<select id="da-hm-default-locale-77" class="da-hm-default-locale" name="da_hm_default_locale_77">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_77") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 77' . '"></div>';

        echo $html;
    }

    public function default_locale_77_validation($input){
        return $input;
    }

    //78 ----------------------------------------------------------------------------------------------------------------
    public function default_language_78_callback($args){
        $html = '<select id="da-hm-default-language-78" class="da-hm-default-language" name="da_hm_default_language_78">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_78") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 78' . '"></div>';
        echo $html;
    }

    public function default_language_78_validation($input){
        return $input;
    }

    public function default_locale_78_callback($args){
        $html = '<select id="da-hm-default-locale-78" class="da-hm-default-locale" name="da_hm_default_locale_78">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_78") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 78' . '"></div>';

        echo $html;
    }

    public function default_locale_78_validation($input){
        return $input;
    }

    //79 ----------------------------------------------------------------------------------------------------------------
    public function default_language_79_callback($args){
        $html = '<select id="da-hm-default-language-79" class="da-hm-default-language" name="da_hm_default_language_79">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_79") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 79' . '"></div>';
        echo $html;
    }

    public function default_language_79_validation($input){
        return $input;
    }

    public function default_locale_79_callback($args){
        $html = '<select id="da-hm-default-locale-79" class="da-hm-default-locale" name="da_hm_default_locale_79">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_79") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 79' . '"></div>';

        echo $html;
    }

    public function default_locale_79_validation($input){
        return $input;
    }

    //80 ----------------------------------------------------------------------------------------------------------------
    public function default_language_80_callback($args){
        $html = '<select id="da-hm-default-language-80" class="da-hm-default-language" name="da_hm_default_language_80">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_80") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 80' . '"></div>';
        echo $html;
    }

    public function default_language_80_validation($input){
        return $input;
    }

    public function default_locale_80_callback($args){
        $html = '<select id="da-hm-default-locale-80" class="da-hm-default-locale" name="da_hm_default_locale_80">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_80") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 80' . '"></div>';

        echo $html;
    }

    public function default_locale_80_validation($input){
        return $input;
    }

    //81 ----------------------------------------------------------------------------------------------------------------
    public function default_language_81_callback($args){
        $html = '<select id="da-hm-default-language-81" class="da-hm-default-language" name="da_hm_default_language_81">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_81") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 81' . '"></div>';
        echo $html;
    }

    public function default_language_81_validation($input){
        return $input;
    }

    public function default_locale_81_callback($args){
        $html = '<select id="da-hm-default-locale-81" class="da-hm-default-locale" name="da_hm_default_locale_81">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_81") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 81' . '"></div>';

        echo $html;
    }

    public function default_locale_81_validation($input){
        return $input;
    }

    //82 ----------------------------------------------------------------------------------------------------------------
    public function default_language_82_callback($args){
        $html = '<select id="da-hm-default-language-82" class="da-hm-default-language" name="da_hm_default_language_82">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_82") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 82' . '"></div>';
        echo $html;
    }

    public function default_language_82_validation($input){
        return $input;
    }

    public function default_locale_82_callback($args){
        $html = '<select id="da-hm-default-locale-82" class="da-hm-default-locale" name="da_hm_default_locale_82">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_82") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 82' . '"></div>';

        echo $html;
    }

    public function default_locale_82_validation($input){
        return $input;
    }

    //83 ----------------------------------------------------------------------------------------------------------------
    public function default_language_83_callback($args){
        $html = '<select id="da-hm-default-language-83" class="da-hm-default-language" name="da_hm_default_language_83">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_83") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 83' . '"></div>';
        echo $html;
    }

    public function default_language_83_validation($input){
        return $input;
    }

    public function default_locale_83_callback($args){
        $html = '<select id="da-hm-default-locale-83" class="da-hm-default-locale" name="da_hm_default_locale_83">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_83") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 83' . '"></div>';

        echo $html;
    }

    public function default_locale_83_validation($input){
        return $input;
    }

    //84 ----------------------------------------------------------------------------------------------------------------
    public function default_language_84_callback($args){
        $html = '<select id="da-hm-default-language-84" class="da-hm-default-language" name="da_hm_default_language_84">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_84") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 84' . '"></div>';
        echo $html;
    }

    public function default_language_84_validation($input){
        return $input;
    }

    public function default_locale_84_callback($args){
        $html = '<select id="da-hm-default-locale-84" class="da-hm-default-locale" name="da_hm_default_locale_84">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_84") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 84' . '"></div>';

        echo $html;
    }

    public function default_locale_84_validation($input){
        return $input;
    }

    //85 ----------------------------------------------------------------------------------------------------------------
    public function default_language_85_callback($args){
        $html = '<select id="da-hm-default-language-85" class="da-hm-default-language" name="da_hm_default_language_85">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_85") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 85' . '"></div>';
        echo $html;
    }

    public function default_language_85_validation($input){
        return $input;
    }

    public function default_locale_85_callback($args){
        $html = '<select id="da-hm-default-locale-85" class="da-hm-default-locale" name="da_hm_default_locale_85">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_85") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 85' . '"></div>';

        echo $html;
    }

    public function default_locale_85_validation($input){
        return $input;
    }

    //86 ----------------------------------------------------------------------------------------------------------------
    public function default_language_86_callback($args){
        $html = '<select id="da-hm-default-language-86" class="da-hm-default-language" name="da_hm_default_language_86">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_86") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 86' . '"></div>';
        echo $html;
    }

    public function default_language_86_validation($input){
        return $input;
    }

    public function default_locale_86_callback($args){
        $html = '<select id="da-hm-default-locale-86" class="da-hm-default-locale" name="da_hm_default_locale_86">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_86") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 86' . '"></div>';

        echo $html;
    }

    public function default_locale_86_validation($input){
        return $input;
    }

    //87 ----------------------------------------------------------------------------------------------------------------
    public function default_language_87_callback($args){
        $html = '<select id="da-hm-default-language-87" class="da-hm-default-language" name="da_hm_default_language_87">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_87") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 87' . '"></div>';
        echo $html;
    }

    public function default_language_87_validation($input){
        return $input;
    }

    public function default_locale_87_callback($args){
        $html = '<select id="da-hm-default-locale-87" class="da-hm-default-locale" name="da_hm_default_locale_87">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_87") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 87' . '"></div>';

        echo $html;
    }

    public function default_locale_87_validation($input){
        return $input;
    }

    //88 ----------------------------------------------------------------------------------------------------------------
    public function default_language_88_callback($args){
        $html = '<select id="da-hm-default-language-88" class="da-hm-default-language" name="da_hm_default_language_88">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_88") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 88' . '"></div>';
        echo $html;
    }

    public function default_language_88_validation($input){
        return $input;
    }

    public function default_locale_88_callback($args){
        $html = '<select id="da-hm-default-locale-88" class="da-hm-default-locale" name="da_hm_default_locale_88">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_88") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 88' . '"></div>';

        echo $html;
    }

    public function default_locale_88_validation($input){
        return $input;
    }

    //89 ----------------------------------------------------------------------------------------------------------------
    public function default_language_89_callback($args){
        $html = '<select id="da-hm-default-language-89" class="da-hm-default-language" name="da_hm_default_language_89">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_89") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 89' . '"></div>';
        echo $html;
    }

    public function default_language_89_validation($input){
        return $input;
    }

    public function default_locale_89_callback($args){
        $html = '<select id="da-hm-default-locale-89" class="da-hm-default-locale" name="da_hm_default_locale_89">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_89") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 89' . '"></div>';

        echo $html;
    }

    public function default_locale_89_validation($input){
        return $input;
    }

    //90 ----------------------------------------------------------------------------------------------------------------
    public function default_language_90_callback($args){
        $html = '<select id="da-hm-default-language-90" class="da-hm-default-language" name="da_hm_default_language_90">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_90") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 90' . '"></div>';
        echo $html;
    }

    public function default_language_90_validation($input){
        return $input;
    }

    public function default_locale_90_callback($args){
        $html = '<select id="da-hm-default-locale-90" class="da-hm-default-locale" name="da_hm_default_locale_90">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_90") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 90' . '"></div>';

        echo $html;
    }

    public function default_locale_90_validation($input){
        return $input;
    }

    //91 ----------------------------------------------------------------------------------------------------------------
    public function default_language_91_callback($args){
        $html = '<select id="da-hm-default-language-91" class="da-hm-default-language" name="da_hm_default_language_91">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_91") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 91' . '"></div>';
        echo $html;
    }

    public function default_language_91_validation($input){
        return $input;
    }

    public function default_locale_91_callback($args){
        $html = '<select id="da-hm-default-locale-91" class="da-hm-default-locale" name="da_hm_default_locale_91">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_91") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 91' . '"></div>';

        echo $html;
    }

    public function default_locale_91_validation($input){
        return $input;
    }

    //92 ----------------------------------------------------------------------------------------------------------------
    public function default_language_92_callback($args){
        $html = '<select id="da-hm-default-language-92" class="da-hm-default-language" name="da_hm_default_language_92">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_92") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 92' . '"></div>';
        echo $html;
    }

    public function default_language_92_validation($input){
        return $input;
    }

    public function default_locale_92_callback($args){
        $html = '<select id="da-hm-default-locale-92" class="da-hm-default-locale" name="da_hm_default_locale_92">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_92") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 92' . '"></div>';

        echo $html;
    }

    public function default_locale_92_validation($input){
        return $input;
    }

    //93 ----------------------------------------------------------------------------------------------------------------
    public function default_language_93_callback($args){
        $html = '<select id="da-hm-default-language-93" class="da-hm-default-language" name="da_hm_default_language_93">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_93") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 93' . '"></div>';
        echo $html;
    }

    public function default_language_93_validation($input){
        return $input;
    }

    public function default_locale_93_callback($args){
        $html = '<select id="da-hm-default-locale-93" class="da-hm-default-locale" name="da_hm_default_locale_93">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_93") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 93' . '"></div>';

        echo $html;
    }

    public function default_locale_93_validation($input){
        return $input;
    }

    //94 ----------------------------------------------------------------------------------------------------------------
    public function default_language_94_callback($args){
        $html = '<select id="da-hm-default-language-94" class="da-hm-default-language" name="da_hm_default_language_94">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_94") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 94' . '"></div>';
        echo $html;
    }

    public function default_language_94_validation($input){
        return $input;
    }

    public function default_locale_94_callback($args){
        $html = '<select id="da-hm-default-locale-94" class="da-hm-default-locale" name="da_hm_default_locale_94">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_94") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 94' . '"></div>';

        echo $html;
    }

    public function default_locale_94_validation($input){
        return $input;
    }

    //95 ----------------------------------------------------------------------------------------------------------------
    public function default_language_95_callback($args){
        $html = '<select id="da-hm-default-language-95" class="da-hm-default-language" name="da_hm_default_language_95">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_95") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 95' . '"></div>';
        echo $html;
    }

    public function default_language_95_validation($input){
        return $input;
    }

    public function default_locale_95_callback($args){
        $html = '<select id="da-hm-default-locale-95" class="da-hm-default-locale" name="da_hm_default_locale_95">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_95") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 95' . '"></div>';

        echo $html;
    }

    public function default_locale_95_validation($input){
        return $input;
    }

    //96 ----------------------------------------------------------------------------------------------------------------
    public function default_language_96_callback($args){
        $html = '<select id="da-hm-default-language-96" class="da-hm-default-language" name="da_hm_default_language_96">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_96") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 96' . '"></div>';
        echo $html;
    }

    public function default_language_96_validation($input){
        return $input;
    }

    public function default_locale_96_callback($args){
        $html = '<select id="da-hm-default-locale-96" class="da-hm-default-locale" name="da_hm_default_locale_96">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_96") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 96' . '"></div>';

        echo $html;
    }

    public function default_locale_96_validation($input){
        return $input;
    }

    //97 ----------------------------------------------------------------------------------------------------------------
    public function default_language_97_callback($args){
        $html = '<select id="da-hm-default-language-97" class="da-hm-default-language" name="da_hm_default_language_97">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_97") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 97' . '"></div>';
        echo $html;
    }

    public function default_language_97_validation($input){
        return $input;
    }

    public function default_locale_97_callback($args){
        $html = '<select id="da-hm-default-locale-97" class="da-hm-default-locale" name="da_hm_default_locale_97">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_97") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 97' . '"></div>';

        echo $html;
    }

    public function default_locale_97_validation($input){
        return $input;
    }

    //98 ----------------------------------------------------------------------------------------------------------------
    public function default_language_98_callback($args){
        $html = '<select id="da-hm-default-language-98" class="da-hm-default-language" name="da_hm_default_language_98">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_98") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 98' . '"></div>';
        echo $html;
    }

    public function default_language_98_validation($input){
        return $input;
    }

    public function default_locale_98_callback($args){
        $html = '<select id="da-hm-default-locale-98" class="da-hm-default-locale" name="da_hm_default_locale_98">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_98") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 98' . '"></div>';

        echo $html;
    }

    public function default_locale_98_validation($input){
        return $input;
    }

    //99 ----------------------------------------------------------------------------------------------------------------
    public function default_language_99_callback($args){
        $html = '<select id="da-hm-default-language-99" class="da-hm-default-language" name="da_hm_default_language_99">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_99") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 99' . '"></div>';
        echo $html;
    }

    public function default_language_99_validation($input){
        return $input;
    }

    public function default_locale_99_callback($args){
        $html = '<select id="da-hm-default-locale-99" class="da-hm-default-locale" name="da_hm_default_locale_99">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_99") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 99' . '"></div>';

        echo $html;
    }

    public function default_locale_99_validation($input){
        return $input;
    }

    //100 ----------------------------------------------------------------------------------------------------------------
    public function default_language_100_callback($args){
        $html = '<select id="da-hm-default-language-100" class="da-hm-default-language" name="da_hm_default_language_100">';
        $array_language = get_option('da_hm_language');
        foreach ($array_language as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_100") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Language', 'dahm')) . ' 100' . '"></div>';
        echo $html;
    }

    public function default_language_100_validation($input){
        return $input;
    }

    public function default_locale_100_callback($args){
        $html = '<select id="da-hm-default-locale-100" class="da-hm-default-locale" name="da_hm_default_locale_100">';
        $html .= '<option value="">' . __('Not Assigned', 'dahm') . '</option>';
        $array_locale = get_option('da_hm_locale');
        foreach ($array_locale as $key => $value) {
            $html .= '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_100") , $value, false ) . '>' . $value . " - " . $key . '</option>';
        }
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr(__('Default Locale', 'dahm')) . ' 100' . '"></div>';

        echo $html;
    }

    public function default_locale_100_validation($input){
        return $input;
    }

    /**
     * Deletes a connection by using the permalink of the trashed post. Note that this operation is performed only if
     * the 'Auto Delete' option is enabled.
     */
    public function delete_post_connection($post_id){

        if(intval(get_option($this->shared->get('slug') . '_auto_delete'), 10) == 1){

            $permalink = get_the_permalink($post_id, false);

            global $wpdb;
            $table_name = $wpdb->prefix . 'da_hm_connect';
            $safe_sql = $wpdb->prepare("DELETE FROM $table_name WHERE url_to_connect = %s", $permalink);
            $wpdb->query($safe_sql);

        }

    }

    /*
     * The click on the "Export" button available in the "Export" menu is intercepted and the
     * method that generates the downloadable XML file is called
     */
    public function export_xml_controller()
    {

        /*
         * Intercept requests that come from the "Export" button of the
         * "Hreflang Export -> Export" menu and generate the downloadable XML file
         */
        if (isset($_POST['dahm_export'])) {

            //verify capability
            if (!current_user_can(get_option($this->shared->get('slug') . "_export_menu_capability"))) {
                wp_die(esc_attr__('You do not have sufficient permissions to access this page.', 'dahm'));
            }

            //get the data from the 'connect' db
            global $wpdb;
            $table_name = $wpdb->prefix . $this->shared->get('slug') . "_connect";
            $connect_a = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id ASC", ARRAY_A);

            //if there are data generate the csv header and the content
            if (count($connect_a) > 0) {

                //generate the header of the XML file
                header('Content-Encoding: UTF-8');
                header('Content-type: text/xml; charset=UTF-8');
                header("Content-Disposition: attachment; filename=hreflang-manager-" . time() . ".xml");
                header("Pragma: no-cache");
                header("Expires: 0");

                //generate initial part of the XML file
                $out = '<?xml version="1.0" encoding="UTF-8" ?>';
                $out .= '<root>';

                //set column content
                foreach ($connect_a as $connect) {

                    $out .= "<connect>";

                    //get all the indexes of the $table array
                    $table_keys = array_keys($connect);

                    //cycle through all the indexes of $connect and create all the tags related to this record
                    foreach ($table_keys as $key) {

                        $out .= "<" . $key . ">" . esc_attr($connect[$key]) . "</" . $key . ">";

                    }

                    $out .= "</connect>";

                }

                //generate the final part of the XML file
                $out .= '</root>';

            } else {
                return false;
            }

            echo $out;
            die();

        }

    }

}