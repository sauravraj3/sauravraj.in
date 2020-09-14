<?php

    if ( !current_user_can(get_option($this->shared->get('slug') . "_connections_menu_capability")) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }

    ?>

    <!-- process data -->

    <?php

    //save the connection into the database
    if( isset($_POST['form_submitted']) and isset($_POST['url_to_connect']) ){

        //set variables to the actual value
        $url_to_connect = $_POST['url_to_connect'];

        $invalid_data_message = '';

        //verify if the "URL to Connect" is empty
        if( strlen(trim($url_to_connect)) == 0 ){
            $invalid_data_message .= '<div class="error settings-error notice is-dismissible below-h2"><p>' . __('The "URL to Connect" field is empty.', 'dahm') . '</p></div>';
            $invalid_data = true;
        }

        for($i=1;$i<=100;$i++){

            if( isset($_POST['url' . $i]) and strlen(trim($_POST['url' . $i])) > 0 ) {
                ${"url" . $i} = $_POST['url' . $i];
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

        global $wpdb;
        $table_name = $wpdb->prefix."da_hm_connect";

        if(!isset($invalid_data)) {

            if (isset($_POST['id'])) {

                //update an existing connection
                $edited_id = $_POST['id'];
                $safe_sql = $wpdb->prepare("UPDATE $table_name SET url_to_connect = %s ,"
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
                    . "url100 = %s, language100 = %s, locale100 = %s WHERE id = %d ", $url_to_connect,
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
                    $edited_id);

                $query_result = $wpdb->query($safe_sql);

                if ($query_result !== false) {
                    $process_data_message = '<div class="updated settings-error notice is-dismissible below-h2"><p>' . __('The connection has been successfully updated.', 'dahm') . '</p></div>';
                }

            } else {


                //verify if a connection with this "URL to Connect" already exists
                if( $this->shared->url_to_connect_exists($url_to_connect) ){
                    $invalid_data_message .= '<div class="error settings-error notice is-dismissible below-h2"><p>' . __('A connection with this "URL to Connect" already exists.', 'dahm') . '</p></div>';
                    $invalid_data = true;
                }

                if(!isset($invalid_data)) {

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
                        . "url61 = %s, language61 = %s, locale61 = %s ,"
                        . "url62 = %s, language62 = %s, locale62 = %s ,"
                        . "url63 = %s, language63 = %s, locale63 = %s ,"
                        . "url64 = %s, language64 = %s, locale64 = %s ,"
                        . "url65 = %s, language65 = %s, locale65 = %s ,"
                        . "url66 = %s, language66 = %s, locale66 = %s ,"
                        . "url67 = %s, language67 = %s, locale67 = %s ,"
                        . "url68 = %s, language68 = %s, locale68 = %s ,"
                        . "url69 = %s, language69 = %s, locale69 = %s ,"
                        . "url70 = %s, language70 = %s, locale70 = %s ,"
                        . "url71 = %s, language71 = %s, locale71 = %s ,"
                        . "url72 = %s, language72 = %s, locale72 = %s ,"
                        . "url73 = %s, language73 = %s, locale73 = %s ,"
                        . "url74 = %s, language74 = %s, locale74 = %s ,"
                        . "url75 = %s, language75 = %s, locale75 = %s ,"
                        . "url76 = %s, language76 = %s, locale76 = %s ,"
                        . "url77 = %s, language77 = %s, locale77 = %s ,"
                        . "url78 = %s, language78 = %s, locale78 = %s ,"
                        . "url79 = %s, language79 = %s, locale79 = %s ,"
                        . "url80 = %s, language80 = %s, locale80 = %s ,"
                        . "url81 = %s, language81 = %s, locale81 = %s ,"
                        . "url82 = %s, language82 = %s, locale82 = %s ,"
                        . "url83 = %s, language83 = %s, locale83 = %s ,"
                        . "url84 = %s, language84 = %s, locale84 = %s ,"
                        . "url85 = %s, language85 = %s, locale85 = %s ,"
                        . "url86 = %s, language86 = %s, locale86 = %s ,"
                        . "url87 = %s, language87 = %s, locale87 = %s ,"
                        . "url88 = %s, language88 = %s, locale88 = %s ,"
                        . "url89 = %s, language89 = %s, locale89 = %s ,"
                        . "url90 = %s, language90 = %s, locale90 = %s ,"
                        . "url91 = %s, language91 = %s, locale91 = %s ,"
                        . "url92 = %s, language92 = %s, locale92 = %s ,"
                        . "url93 = %s, language93 = %s, locale93 = %s ,"
                        . "url94 = %s, language94 = %s, locale94 = %s ,"
                        . "url95 = %s, language95 = %s, locale95 = %s ,"
                        . "url96 = %s, language96 = %s, locale96 = %s ,"
                        . "url97 = %s, language97 = %s, locale97 = %s ,"
                        . "url98 = %s, language98 = %s, locale98 = %s ,"
                        . "url99 = %s, language99 = %s, locale99 = %s ,"
                        . "url100 = %s, language100 = %s, locale100 = %s ", $url_to_connect,
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

                    $query_result = $wpdb->query($safe_sql);

                }

                if ( isset($query_result) and $query_result !== false) {
                    $process_data_message = '<div class="updated settings-error notice is-dismissible below-h2"><p>' . __('The connection has been successfully added.', 'dahm') . '</p></div>';
                }

            }

        }

    }

    //delete a Connection
    if( isset( $_POST['delete_id']) ){

        global $wpdb;
        $delete_id = intval($_POST['delete_id'], 10);
        $table_name = $wpdb->prefix."da_hm_connect";
        $safe_sql = $wpdb->prepare("DELETE FROM $table_name WHERE id = %d ", $delete_id);

        $query_result = $wpdb->query( $safe_sql );
        if($query_result !== false){
            $process_data_message = '<div class="updated settings-error notice is-dismissible below-h2"><p>' . __('The connection has been successfully deleted.', 'dahm') . '</p></div>';
        }

    }

    //clone action and elements in the field
    if( isset( $_POST['clone_id']) ){

        global $wpdb;
        $clone_id = intval($_POST['clone_id'], 10);;

        //clone action
        $table_name = $wpdb->prefix."da_hm_connect";
        $wpdb->query("CREATE TEMPORARY TABLE tmptable_1 SELECT * FROM $table_name WHERE id = $clone_id");
        $wpdb->query("UPDATE tmptable_1 SET id = NULL");
        $wpdb->query("INSERT INTO $table_name SELECT * FROM tmptable_1");
        $wpdb->query("DROP TEMPORARY TABLE IF EXISTS tmptable_1");

    }

    //edit a Connection
    if( isset( $_GET['edit_id']) ){

        global $wpdb;
        $edit_id = $_GET['edit_id'];
        $table_name = $wpdb->prefix."da_hm_connect";
        $safe_sql = $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d ", $edit_id);
        $edit_obj = $wpdb->get_row($safe_sql);

    }

    ?>

    <!-- output ******************************************************************************* -->

    <div class="wrap">

        <?php if ($this->shared->number_of_connections() > 0) : ?>

            <div id="daext-header-wrapper" class="daext-clearfix">

                <h2><?php _e('Hreflang Manager - Connections', 'dahm'); ?></h2>

                <form action="admin.php" method="get">
                    <input type="hidden" name="page" value="da_hm_connections">
                    <?php
                    if (isset($_GET['s']) and strlen(trim($_GET['s'])) > 0) {
                        $search_string = $_GET['s'];
                    } else {
                        $search_string = '';
                    }
                    ?>
                    <input type="text" name="s" placeholder="<?php esc_attr_e('Search...', 'dahm'); ?>"
                           value="<?php echo esc_attr(stripslashes($search_string)); ?>" autocomplete="off" maxlength="255">
                    <input type="submit" value="">
                </form>

            </div>

        <?php else: ?>

            <div id="daext-header-wrapper" class="daext-clearfix">

                <h2><?php esc_attr_e('Hreflang Manager - Connections', 'dahm'); ?></h2>

            </div>

        <?php endif; ?>

        <div id="daext-menu-wrapper">

            <?php if(isset($invalid_data_message)){echo $invalid_data_message;} ?>
            <?php if(isset($process_data_message)){echo $process_data_message;} ?>

            <!-- table -->

            <?php

            //create the query part used to filter the results when a search is performed
            if (isset($_GET['s']) and strlen(trim($_GET['s'])) > 0) {
                $search_string = $_GET['s'];
                global $wpdb;
                $filter = $wpdb->prepare('WHERE (id LIKE %s OR url_to_connect LIKE %s)', '%' . $search_string . '%', '%' . $search_string . '%');
            } else {
                $filter = '';
            }

            //retrieve the total number of connections
            global $wpdb;
            $table_name=$wpdb->prefix."da_hm_connect";
            $total_items = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name $filter" );

            //Initialize the pagination class
            require_once( $this->shared->get('dir') . '/admin/inc/class-dahm-pagination.php' );
            $pag = new Dahm_pagination();
            $pag->set_total_items( $total_items );//Set the total number of items
            $pag->set_record_per_page( 10 ); //Set records per page
            $pag->set_target_page( "admin.php?page=da_hm_connections" );//Set target page
            $pag->set_current_page();//set the current page number from $_GET

            ?>

            <!-- Query the database -->
            <?php
            $dc_wp_query_limit = $pag->query_limit();
            $results = $wpdb->get_results("SELECT * FROM $table_name $filter ORDER BY id DESC $dc_wp_query_limit ", ARRAY_A);

            //get the number of connections that should be displayed in the menu
            $connections_in_menu = intval(get_option('da_hm_connections_in_menu'), 10);

            ?>

            <?php if( count($results) > 0 ) : ?>

                <div class="daext-items-container">

                    <!-- list of featured news -->
                    <table class="daext-items">
                        <thead>
                        <tr>
                            <th><?php _e('ID', 'dahm'); ?></th>
                            <th><?php _e('URL to Connect', 'dahm'); ?></th>
                            <th><?php _e('Connections', 'dahm'); ?></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php foreach($results as $result) : ?>
                            <tr>
                                <td><?php echo $result['id']; ?></td>
                                <td><a target="_blank" href="<?php echo esc_attr( stripslashes( $result['url_to_connect'] ) ); ?>"><?php echo esc_attr( stripslashes( $result['url_to_connect'] ) ); ?></a></td>

                                <td>

                                    <?php

                                    for($i=1;$i<=100;$i++){

                                        if( strlen($result['url' . $i]) > 0 ){
                                            echo '<a target="_blank" href="' . esc_attr( stripslashes( $result['url' . $i] ) ) . '">' . esc_attr( stripslashes( $result['language' . $i] ) );
                                            if( strlen($result['locale' . $i]) > 0 and $result['language' . $i] != 'x-default' ){
                                                echo "-" . esc_attr( stripslashes( $result['locale' . $i] ) );
                                            }
                                            echo '</a> ';
                                        }

                                    }

                                    ?>


                                </td>

                                <td class="icons-container">
                                    <form method="POST" action="admin.php?page=<?php echo $this->shared->get('slug'); ?>_connections">
                                        <input type="hidden" value="<?php echo $result['id']; ?>" name="clone_id" >
                                        <input class="menu-icon clone" type="submit" value="">
                                    </form>
                                    <a class="menu-icon edit" href="admin.php?page=<?php echo $this->shared->get('slug'); ?>_connections&edit_id=<?php echo $result['id']; ?>"></a>
                                    <form method="POST" action="admin.php?page=<?php echo $this->shared->get('slug'); ?>_connections">
                                        <input type="hidden" value="<?php echo $result['id']; ?>" name="delete_id" >
                                        <input class="menu-icon delete" type="submit" value="">
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        </tbody>
                    </table>

                </div>

                <!-- Display the pagination -->
                <?php if($pag->total_items > 0) : ?>
                    <div class="daext-tablenav daext-clearfix">
                        <div class="daext-tablenav-pages">
                            <span class="daext-displaying-num"><?php echo $pag->total_items; ?> <?php _e('items', 'dahm'); ?></span>
                            <?php $pag->show(); ?>
                        </div>
                    </div>
                <?php endif; ?>

            <?php endif; ?>

            <!-- form -->

            <form method="POST" action="admin.php?page=<?php echo $this->shared->get('slug'); ?>_connections" >

                <input name="form_submitted" type="hidden" value="true">

                <?php if(isset($edit_obj)) : ?>

                    <!-- Generate the form to edit an existing connection -->

                    <div class="daext-form-container">

                        <h3 class="daext-form-title"><?php _e('Edit Connection', 'dahm'); ?> <?php echo $edit_obj->id; ?></h3>

                        <table class="daext-form">

                            <input name="id" type="hidden" value="<?php echo $edit_obj->id; ?>">

                            <!-- URL to connect -->
                            <tr valign="top">
                                <th scope="row"><label for="url_to_connect"><label for="url_to_connect"><?php _e('URL to Connect', 'dahm'); ?></label></th>
                                <td><input autocomplete="off" type="text" id="url_to_connect" maxlength="2083" name="url_to_connect" class="regular-text" value="<?php echo esc_attr( stripslashes( $edit_obj->url_to_connect) ); ?>" /></td>
                            </tr>

                            <?php

                            for($i=1;$i<=$connections_in_menu;$i++){

                                ?>

                                    <!-- url<?php echo $i; ?> -->
                                    <tr valign="top">
                                        <th scope="row"><label for="url<?php echo $i; ?>">URL <?php echo $i; ?></label></th>
                                        <td><input autocomplete="off" type="text" id="url<?php echo $i; ?>" maxlength="2083" name="url<?php echo $i; ?>" class="regular-text" value="<?php echo esc_attr( stripslashes( $edit_obj->{"url" . $i} ) ); ?>" /></td>
                                    </tr>

                                    <!-- Language <?php echo $i; ?> -->
                                    <tr valign="top">
                                        <th scope="row"><label for="language<?php echo $i; ?>" ><?php _e('Language', 'dahm'); ?> <?php echo $i; ?></label></th>
                                        <td>
                                            <select id="language<?php echo $i; ?>" class="dahm-language" name="language<?php echo $i; ?>">
                                                <?php

                                                $array_language = get_option('da_hm_language');
                                                foreach ($array_language as $key => $value) {
                                                    echo '<option value="' . $value . '" ' . selected( $edit_obj->{"language" . $i} , $value, false ) . '>' . $value . " - " . $key . '</option>';
                                                }

                                                ?>
                                            </select>
                                        </td>
                                    </tr>

                                    <!-- Locale <?php echo $i; ?> -->
                                    <tr valign="top">
                                        <th scope="row"><label for="locale<?php echo $i; ?>" ><?php _e('Locale', 'dahm'); ?> <?php echo $i; ?></label></th>
                                        <td>
                                            <select id="locale<?php echo $i; ?>" class="dahm-locale" name="locale<?php echo $i; ?>">
                                                <option value=""><?php _e('Not Assigned', 'dahm'); ?></option>
                                                <?php

                                                $array_language = get_option('da_hm_locale');
                                                foreach ($array_language as $key => $value) {
                                                    echo '<option value="' . $value . '" ' . selected( $edit_obj->{"locale" . $i} , $value, false ) . '>' . $value . " - " . $key . '</option>';
                                                }

                                                ?>
                                            </select>
                                        </td>
                                    </tr>

                                <?php

                            }

                            ?>

                        </table>

                        <!-- submit button -->
                        <div class="daext-form-action">
                            <input class="button" type="submit" value="<?php _e('Update Connection', 'dahm'); ?>" >
                        </div>

                    </div>

                <?php else : ?>

                    <!-- Generate the form to add new connection -->

                    <div class="daext-form-container">

                        <div class="daext-form-title"><?php _e('Create New Connection', 'dahm'); ?></div>

                        <table class="daext-form">

                            <!-- URL to connect -->
                            <tr valign="top">
                                <th scope="row"><label for="url_to_connect"><?php _e('URL to Connect', 'dahm'); ?></label></th>
                                <td><input autocomplete="off" type="text" id="url_to_connect" maxlength="2083" name="url_to_connect" class="regular-text" /></td>
                            </tr>

                            <?php

                            for($i=1;$i<=$connections_in_menu;$i++){

                                ?>

                                    <!-- url -->
                                    <tr valign="top">
                                        <th scope="row"><label for="url<?php echo $i; ?>">URL <?php echo $i; ?></label></th>
                                        <td><input autocomplete="off" type="text" id="url<?php echo $i; ?>" maxlength="2083" name="url<?php echo $i; ?>" class="regular-text" /></td>
                                    </tr>

                                    <!-- Language -->
                                    <tr valign="top">
                                        <th scope="row"><label for="language<?php echo $i; ?>" ><?php _e('Language', 'dahm'); ?> <?php echo $i; ?></label></th>
                                        <td>
                                            <select id="language<?php echo $i; ?>" class="dahm-language" name="language<?php echo $i; ?>">
                                                <?php

                                                $array_language = get_option('da_hm_language');
                                                foreach ($array_language as $key => $value) {
                                                    echo '<option value="' . $value . '" ' . selected( get_option("da_hm_default_language_" . $i) , $value, false ) . '>' . $value . " - " . $key . '</option>';
                                                }

                                                ?>
                                            </select>
                                        </td>
                                    </tr>

                                    <!-- Locale -->
                                    <tr valign="top">
                                        <th scope="row"><label for="locale<?php echo $i; ?>" ><?php _e('Locale', 'dahm'); ?> <?php echo $i; ?></label></th>
                                        <td>
                                            <select id="locale<?php echo $i; ?>" class="dahm-locale" name="locale<?php echo $i; ?>">
                                                <option value=""><?php _e('Not Assigned', 'dahm'); ?></option>
                                                <?php

                                                $array_language = get_option('da_hm_locale');
                                                foreach ($array_language as $key => $value) {
                                                    echo '<option value="' . $value . '" ' . selected( get_option("da_hm_default_locale_" . $i) , $value, false ) . '>' . $value . " - " . $key . '</option>';
                                                }

                                                ?>
                                            </select>
                                        </td>
                                    </tr>

                            <?php

                        }

                        ?>

                        </table>

                        <!-- submit button -->
                        <div class="daext-form-action">
                            <input class="button" type="submit" value="<?php _e('Add Connection', 'dahm'); ?>" >
                        </div>

                    </div>

                <?php endif; ?>

            </form>

    </div>

    <?php

    ?>