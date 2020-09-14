<?php

/*
 * this class should be used to stores properties and methods shared by the
 * admin and public side of wordpress
 */
class Dahm_Shared
{
    
    //regex
    public $regex_list_of_post_types = '/^(\s*([A-Za-z0-9_-]+\s*,\s*)+[A-Za-z0-9_-]+\s*|\s*[A-Za-z0-9_-]+\s*)$/';
    public $regex_number_ten_digits = '/^\s*\d{1,10}\s*$/';
    public $regex_capability = '/^\s*[A-Za-z0-9_]+\s*$/';
    
    protected static $instance = null;

    private $data = array();

    private function __construct()
    {

        //Set plugin textdomain
        load_plugin_textdomain('dahm', false, 'hreflang-manager/lang/');
        
        $this->data['slug'] = 'da_hm';
        $this->data['ver'] = '1.09';
        $this->data['dir'] = substr(plugin_dir_path(__FILE__), 0, -7);
        $this->data['url'] = substr(plugin_dir_url(__FILE__), 0, -7);

	    //Here are stored the plugin option with the related default values
	    $this->data['options'] = [

            //Database Version -----------------------------------------------------------------------------------------
		    $this->get('slug') . '_database_version' => "0",

		    //General --------------------------------------------------------------------------------------------------
		    $this->get('slug') . '_show_log' => "0",
		    $this->get('slug') . '_https' => "0",
		    $this->get('slug') . '_connections_in_menu' => "10",
		    $this->get('slug') . '_meta_box_post_types' => "post, page",
		    $this->get('slug') . '_detect_url_mode' => "server_variable",
		    $this->get('slug') . '_auto_trailing_slash' => "1",
		    $this->get('slug') . '_auto_delete' => "1",
		    $this->get('slug') . '_sanitize_url' => "0",
		    $this->get('slug') . '_sample_future_permalink' => "0",
		    $this->get('slug') . '_import_mode' => "exact_copy",
		    $this->get('slug') . '_import_language' => "en",
		    $this->get('slug') . '_import_locale' => "",
		    $this->get('slug') . '_meta_box_capability' => "manage_options",
		    $this->get('slug') . '_editor_sidebar_capability' => "manage_options",
		    $this->get('slug') . '_connections_menu_capability' => "manage_options",
		    $this->get('slug') . '_import_menu_capability' => "manage_options",
		    $this->get('slug') . '_export_menu_capability' => "manage_options",

	    ];

		//Defaults -----------------------------------------------------------------------------------------------------
	    for ($i = 1; $i <= 100; $i++) {
		    $this->data['options'][$this->get('slug') . '_default_language_' . $i] = 'en';
		    $this->data['options'][$this->get('slug') . '_default_locale_' . $i] = '';
	    }

	    //language list (ISO_639-1)
	    $da_hm_language = array(
		    "don't target a specific language or locale" => "x-default",
		    'Abkhaz' => 'ab',
		    'Afar' => 'aa',
		    'Afrikaans' => 'af',
		    'Akan' => 'ak',
		    'Albanian' => 'sq',
		    'Amharic' => 'am',
		    'Arabic' => 'ar',
		    'Aragonese' => 'an',
		    'Armenian' => 'hy',
		    'Assamese' => 'as',
		    'Avaric' => 'av',
		    'Avestan' => 'ae',
		    'Aymara' => 'ay',
		    'Azerbaijani' => 'az',
		    'Bambara' => 'bm',
		    'Bashkir' => 'ba',
		    'Basque' => 'eu',
		    'Belarusian' => 'be',
		    'Bengali/Bangla' => 'bn',
		    'Bihari' => 'bh',
		    'Bislama' => 'bi',
		    'Bosnian' => 'bs',
		    'Breton' => 'br',
		    'Bulgarian' => 'bg',
		    'Burmese' => 'my',
		    'Catalan/Valencian' => 'ca',
		    'Chamorro' => 'ch',
		    'Chechen' => 'ce',
		    'Chichewa/Chewa/Nyanja' => 'ny',
		    'Chinese' => 'zh',
		    'Chuvash' => 'cv',
		    'Cornish' => 'kw',
		    'Corsican' => 'co',
		    'Cree' => 'cr',
		    'Croatian' => 'hr',
		    'Czech' => 'cs',
		    'Danish' => 'da',
		    'Divehi/Dhivehi/Maldivian' => 'dv',
		    'Dutch' => 'nl',
		    'Dzongkha' => 'dz',
		    'English' => 'en',
		    'Esperanto' => 'eo',
		    'Estonian' => 'et',
		    'Ewe' => 'ee',
		    'Faroese' => 'fo',
		    'Fijian' => 'fj',
		    'Finnish' => 'fi',
		    'French' => 'fr',
		    'Fula/Fulah/Pulaar/Pular' => 'ff',
		    'Galician' => 'gl',
		    'Georgian' => 'ka',
		    'German' => 'de',
		    'Greek/Modern' => 'el',
		    'Guaraní' => 'gn',
		    'Gujarati' => 'gu',
		    'Haitian/Haitian Creole' => 'ht',
		    'Hausa' => 'ha',
		    'Hebrew (modern)' => 'he',
		    'Herero' => 'hz',
		    'Hindi' => 'hi',
		    'Hiri Motu' => 'ho',
		    'Hungarian' => 'hu',
		    'Interlingua' => 'ia',
		    'Indonesian' => 'id',
		    'Interlingue' => 'ie',
		    'Irish' => 'ga',
		    'Igbo' => 'ig',
		    'Inupian' => 'ik',
		    'Ido' => 'io',
		    'Icelandic' => 'is',
		    'Italian' => 'it',
		    'Inuktitut' => 'iu',
		    'Japanese' => 'ja',
		    'Javanese' => 'jv',
		    'Kalaallisut/Greenlandic' => 'kl',
		    'Kannada' => 'kn',
		    'Kanuri' => 'kr',
		    'Kashmiri' => 'ks',
		    'Kazakh' => 'kk',
		    'Khmer' => 'km',
		    'Kikuyu/Gikuyu' => 'ki',
		    'Kinyarwanda' => 'rw',
		    'Kyrgyz' => 'ky',
		    'Komi' => 'kv',
		    'Kongo' => 'kg',
		    'Korean' => 'ko',
		    'Kurdish' => 'ku',
		    'Kwanyama/Kuanyama' => 'kj',
		    'Latin' => 'la',
		    'Luxembourgish/Letzeburgesch' => 'lb',
		    'Ganda' => 'lg',
		    'Limburgish/Limburgan/Limburger' => 'li',
		    'Lingala' => 'ln',
		    'Lao' => 'lo',
		    'Lithuanian' => 'lt',
		    'Luba-Katanga' => 'lu',
		    'Latvian' => 'lv',
		    'Manx' => 'gv',
		    'Macedonian' => 'mk',
		    'Malagasy' => 'mg',
		    'Malay' => 'ms',
		    'Malayalam' => 'ml',
		    'Maltese' => 'mt',
		    'Māori' => 'mi',
		    'Marathi/Marāṭhī' => 'mr',
		    'Marshallese' => 'mr',
		    'Mongolian' => 'mn',
		    'Nauru' => 'na',
		    'Navajo/Navaho' => 'nv',
		    'Norwegian Bokmål' => 'nb',
		    'North Ndebele' => 'nd',
		    'Nepali' => 'ne',
		    'Ndonga' => 'ng',
		    'Norwegian Nynorsk' => 'nn',
		    'Norwegian' => 'no',
		    'Nuosu' => 'ii',
		    'South Ndebele' => 'nr',
		    'Occitan' => 'oc',
		    'Ojibwe/Ojibwa' => 'oj',
		    'Old C. Slavonic/C. Slavic/C. Slavonic/Old Bulgarian/Old Slavonic' => 'cu',
		    'Oromo' => 'om',
		    'Orija' => 'or',
		    'Ossetian/Ossetic' => 'os',
		    'Panjabi/Punjabi' => 'pa',
		    'Pāli' => 'pi',
		    'Persian (Farsi)' => 'fa',
		    'Polish' => 'pl',
		    'Pashto/Pushto' => 'ps',
		    'Portuguese' => 'pt',
		    'Quechua' => 'qu',
		    'Romansh' => 'rm',
		    'Kirundi' => 'rn',
		    'Romanian' => 'ro',
		    'Russian' => 'ru',
		    'Sanskrit (Saṁskṛta)' => 'sa',
		    'Sardinian' => 'sc',
		    'Sindhi' => 'sd',
		    'Northern Sami' => 'se',
		    'Samoan' => 'sm',
		    'Sango' => 'sg',
		    'Serbian' => 'sr',
		    'Scottish Gaelic/Gaelic' => 'gd',
		    'Shona' => 'sn',
		    'Sinhala/Sinhalese' => 'si',
		    'Slovak' => 'sk',
		    'Slovene' => 'sl',
		    'Somali' => 'so',
		    'Southern Sotho' => 'st',
		    'South Azebaijani' => 'az',
		    'Spanish/Castilian' => 'es',
		    'Sundanese' => 'su',
		    'Swahili' => 'sw',
		    'Swati' => 'ss',
		    'Swedish' => 'sv',
		    'Tamil' => 'ta',
		    'Telugu' => 'te',
		    'Tajik' => 'tg',
		    'Thai' => 'th',
		    'Tigrinya' => 'ti',
		    'Tibetan Standard/Tibetan/Central' => 'bo',
		    'Turkmen' => 'tk',
		    'Tagalog' => 'tl',
		    'Tswana' => 'tn',
		    'Tonga (Tonga Islands)' => 'to',
		    'Turkish' => 'tr',
		    'Tsonga' => 'ts',
		    'Tatar' => 'tt',
		    'Twi' => 'tw',
		    'Tahitian' => 'ty',
		    'Uyghur/Uighur' => 'ug',
		    'Ukrainian' => 'uk',
		    'Urdu' => 'ur',
		    'Uzbek' => 'uz',
		    'Venda' => 've',
		    'Vietnamese' => 'vi',
		    'Volapük' => 'vo',
		    'Walloon' => 'wa',
		    'Welsh' => 'cy',
		    'Wolof' => 'wo',
		    'Western Frisian' => 'fy',
		    'Xhosa' => 'xh',
		    'Yiddish' => 'yi',
		    'Yoruba' => 'yo',
		    'Zhuang/Chuang' => 'za',
		    'Zulu' => 'zu'
	    );
	    $this->data['options'][$this->get('slug') . '_language'] = $da_hm_language;

	    //country list (ISO 3166-1 alpha-2)
	    $da_hm_locale = array(
		    'Andorra' => 'ad',
		    'United Arab Emirates' => 'ae',
		    'Afghanistan' => 'af',
		    'Antigua and Barbuda' => 'ag',
		    'Anguilla' => 'ai',
		    'Albania' => 'al',
		    'Armenia' => 'am',
		    'Angola' => 'ao',
		    'Antartica' => 'aq',
		    'Argentina' => 'ar',
		    'American Samoa' => 'as',
		    'Austria' => 'at',
		    'Australia' => 'au',
		    'Aruba' => 'aw',
		    'Åland Islands' => 'ax',
		    'Azerbaijan' => 'az',
		    'Bosnia and Herzegovina' => 'ba',
		    'Barbados' => 'bb',
		    'Bangladesh' => 'bd',
		    'Belgium' => 'be',
		    'Burkina Faso' => 'bf',
		    'Bulgaria' => 'bg',
		    'Bahrain' => 'bh',
		    'Burundi' => 'bi',
		    'Benin' => 'bj',
		    'Saint Barthélemy' => 'bl',
		    'Bermuda' => 'bm',
		    'Brunei Darussalam' => 'bn',
		    'Bolivia' => 'bo',
		    'Bonaire, Sint Eustatius and Saba' => 'bq',
		    'Brazil' => 'br',
		    'Bahamas' => 'bs',
		    'Bhutan' => 'bt',
		    'Bouvet Island' => 'bv',
		    'Botswana' => 'bw',
		    'Belarus' => 'by',
		    'Belize' => 'bz',
		    'Canada' => 'ca',
		    'Cocos (Keeling) Islands' => 'cc',
		    'Congo Democratic Republic' => 'cd',
		    'Central African Republic' => 'cf',
		    'Congo' => 'cg',
		    'Switzerland' => 'ch',
		    'Côte d\'Ivoire' => 'ci',
		    'Cook Islands' => 'ck',
		    'Chile' => 'cl',
		    'Cameroon' => 'cm',
		    'China' => 'cn',
		    'Colombia' => 'co',
		    'Costa Rica' => 'cr',
		    'Cuba' => 'cu',
		    'Cape Verde' => 'cv',
		    'Curaçao' => 'cw',
		    'Christmas Island' => 'cx',
		    'Cyprus' => 'cy',
		    'Czech Republic' => 'cz',
		    'Germany' => 'de',
		    'Djibouti' => 'dj',
		    'Denmark' => 'dk',
		    'Dominica' => 'dm',
		    'Dominican Republic' => 'do',
		    'Algeria' => 'dz',
		    'Ecuador' => 'ec',
		    'Estonia' => 'ee',
		    'Egypt' => 'eg',
		    'Western Sahara' => 'eh',
		    'Eritrea' => 'er',
		    'Spain' => 'es',
		    'Ethiopia' => 'et',
		    'Finland' => 'fi',
		    'Fiji' => 'fj',
		    'Falkland Islands (Malvinas)' => 'fk',
		    'Micronesia Federated States of' => 'fm',
		    'Faroe Islands' => 'fo',
		    'France' => 'fr',
		    'Gabon' => 'ga',
		    'United Kingdom' => 'gb',
		    'Grenada' => 'gd',
		    'Georgia' => 'ge',
		    'French Guiana' => 'gf',
		    'Guernsey' => 'gg',
		    'Ghana' => 'gh',
		    'Gibraltar' => 'gi',
		    'Greenland' => 'gl',
		    'Gambia' => 'gm',
		    'Guinea' => 'gn',
		    'Guadeloupe' => 'gp',
		    'Equatorial Guinea' => 'gq',
		    'Greece' => 'gr',
		    'South Georgia and the South Sandwich Islands' => 'gs',
		    'Guatemala' => 'gt',
		    'Guam' => 'gu',
		    'Guinea-Bissau' => 'gw',
		    'Guyana' => 'gy',
		    'Hong Kong' => 'hk',
		    'Heard Island and McDonald Islands' => 'hm',
		    'Honduras' => 'hn',
		    'Croatia' => 'hr',
		    'Haiti' => 'ht',
		    'Hungary' => 'hu',
		    'Indonesia' => 'id',
		    'Ireland' => 'ie',
		    'Israel' => 'il',
		    'Isle of Man' => 'im',
		    'India' => 'in',
		    'British Indian Ocean Territory' => 'io',
		    'Iraq' => 'iq',
		    'Iran, Islamic Republic of' => 'ir',
		    'Iceland' => 'is',
		    'Italy' => 'it',
		    'Jersey' => 'je',
		    'Jamaica' => 'jm',
		    'Jordan' => 'jo',
		    'Japan' => 'jp',
		    'Kenya' => 'ke',
		    'Kyrgyzstan' => 'kg',
		    'Cambodia' => 'kh',
		    'Kiribati' => 'ki',
		    'Comoros' => 'km',
		    'Saint Kitts and Nevis' => 'kn',
		    'Korea, Democratic People\'s Republic of' => 'kp',
		    'Korea, Republic of' => 'kr',
		    'Kuwait' => 'kw',
		    'Cayman Islands' => 'ky',
		    'Kazakhstan' => 'kz',
		    'Lao People\'s Democratic Republic' => 'la',
		    'Lebanon' => 'la',
		    'Saint Lucia' => 'lc',
		    'Liechtenstein' => 'li',
		    'Sri Lanka' => 'lk',
		    'Liberia' => 'lr',
		    'Lesotho' => 'ls',
		    'Lithuania' => 'lt',
		    'Luxembourg' => 'lu',
		    'Latvia' => 'lv',
		    'Libya' => 'ly',
		    'Morocco' => 'ma',
		    'Monaco' => 'mc',
		    'Moldova, Republic of' => 'md',
		    'Montenegro' => 'me',
		    'Saint Martin (French part)' => 'mf',
		    'Madagascar' => 'mg',
		    'Marshall Islands' => 'mh',
		    'Macedonia, the former Yugoslav Republic of' => 'mk',
		    'Mali' => 'ml',
		    'Myanmar' => 'mm',
		    'Mongolia' => 'mn',
		    'Macao' => 'mo',
		    'Northern Mariana Islands' => 'mp',
		    'Martinique' => 'mq',
		    'Mauritania' => 'mr',
		    'Montserrat' => 'ms',
		    'Malta' => 'mt',
		    'Mauritius' => 'mu',
		    'Maldives' => 'mv',
		    'Malawi' => 'mw',
		    'Mexico' => 'mx',
		    'Malaysia' => 'my',
		    'Mozambique' => 'mz',
		    'Namibia' => 'na',
		    'New Caledonia' => 'nc',
		    'Niger' => 'ne',
		    'Norfolk Island' => 'nf',
		    'Nigeria' => 'ng',
		    'Nicaragua' => 'ni',
		    'Netherlands' => 'nl',
		    'Norway' => 'no',
		    'Nepal' => 'np',
		    'Nauru' => 'nr',
		    'Niue' => 'nu',
		    'New Zealand' => 'nz',
		    'Oman' => 'om',
		    'Panama' => 'pa',
		    'Peru' => 'pe',
		    'French Polynesia' => 'pf',
		    'Papua New Guinea' => 'pg',
		    'Philippines' => 'ph',
		    'Pakistan' => 'pk',
		    'Poland' => 'pl',
		    'Saint Pierre and Miquelon' => 'pm',
		    'Pitcairn' => 'pn',
		    'Puerto Rico' => 'pr',
		    'Palestine, State of' => 'ps',
		    'Portugal' => 'pt',
		    'Palau' => 'pw',
		    'Paraguay' => 'py',
		    'Qatar' => 'qa',
		    'Réunion' => 're',
		    'Romania' => 'ro',
		    'Serbia' => 'rs',
		    'Russian Federation' => 'ru',
		    'Rwanda' => 'rw',
		    'Saudi Arabia' => 'sa',
		    'Solomon Islands' => 'sb',
		    'Seychelles' => 'sc',
		    'Sudan' => 'sd',
		    'Sweden' => 'se',
		    'Singapore' => 'sg',
		    'Saint Helena, Ascension and Tristan da Cunha' => 'sh',
		    'Slovenia' => 'si',
		    'Svalbard and Jan Mayen' => 'sj',
		    'Slovakia' => 'sk',
		    'Sierra Leone' => 'sl',
		    'San Marino' => 'sm',
		    'Senegal' => 'sn',
		    'Somalia' => 'so',
		    'Suriname' => 'sr',
		    'South Sudan' => 'ss',
		    'Sao Tome and Principe' => 'st',
		    'El Salvador' => 'sv',
		    'Sint Maarten (Dutch part)' => 'sx',
		    'Syrian Arab Republic' => 'sy',
		    'Swaziland' => 'sz',
		    'Turks and Caicos Islands' => 'tc',
		    'Chad' => 'td',
		    'French Southern Territories' => 'tf',
		    'Togo' => 'tg',
		    'Thailand' => 'th',
		    'Tajikistan' => 'tj',
		    'Tokelau' => 'tk',
		    'Timor-Leste' => 'tl',
		    'Turkmenistan' => 'tm',
		    'Tunisia' => 'tn',
		    'Tonga' => 'to',
		    'Turkey' => 'tr',
		    'Trinidad and Tobago' => 'tt',
		    'Tuvalu' => 'tv',
		    'Taiwan, Province of China' => 'tw',
		    'Tanzania, United Republic of' => 'tz',
		    'Ukraine' => 'ua',
		    'Uganda' => 'ug',
		    'United States Minor Outlying Islands' => 'um',
		    'United States' => 'us',
		    'Uruguay' => 'uy',
		    'Uzbekistan' => 'uz',
		    'Holy See (Vatican City State)' => 'va',
		    'Saint Vincent and the Grenadines' => 'vc',
		    'Venezuela, Bolivarian Republic of' => 've',
		    'Virgin Islands, British' => 'vg',
		    'Virgin Islands, U.S.' => 'vi',
		    'Viet Nam' => 'vn',
		    'Vanuatu' => 'vu',
		    'Wallis and Futuna' => 'wf',
		    'Samoa' => 'ws',
		    'Yemen' => 'ye',
		    'Mayotte' => 'yt',
		    'South Africa' => 'za',
		    'Zambia' => 'zm',
		    'Zimbabwe' => 'zw'
	    );
	    $this->data['options'][$this->get('slug') . '_locale'] = $da_hm_locale;

    }

    public static function get_instance()
    {

        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;

    }

    //retrieve data
    public function get($index)
    {
        return $this->data[$index];
    }

    /*
     * Generate an array with the connections associated with the current url
     *
     * @return An array with the connections associated with the current url or False if there are not connections
     * associated with the current url
     */
    public function generate_hreflang_output(){

        //get the current url
        $current_url = $this->get_current_url();

        global $wpdb;
        $table_name=$wpdb->prefix . "da_hm_connect";

        /**
         * If the 'Auto Trailing Slash' option is enabled compare the 'url_to_connect' value in the database not only
         * with $current_url, but also with the URL present in $current_url with the trailing slash manually added or
         * removed.
         */
        if(intval(get_option("da_hm_auto_trailing_slash"), 10) == 1){

            if(substr($current_url, strlen($current_url) - 1) == '/'){

                /**
                 * In this case there is a trailing slash, so remove it and compare the 'url_to_connect' value in the
                 * database not only with $current_url, but also with $current_url_without_trailing_slash, which is
                 * $current_url with the trailing slash removed.
                 */
                $current_url_without_trailing_slash = substr($current_url, 0, -1);
                $safe_sql = $wpdb->prepare( "SELECT * FROM $table_name WHERE url_to_connect = %s or url_to_connect = %s", $current_url, $current_url_without_trailing_slash);

            }else{

                /**
                 * In this case there is no trailing slash, so add it and compare the 'url_to_connect' value in the
                 * database not only with $current_url, but also with $current_url_with_trailing_slash, which is
                 * $current_url with the trailing slash added.
                 */
                $current_url_with_trailing_slash = $current_url . '/';
                $safe_sql = $wpdb->prepare( "SELECT * FROM $table_name WHERE url_to_connect = %s or url_to_connect = %s", $current_url, $current_url_with_trailing_slash);

            }

        }else{
            $safe_sql = $wpdb->prepare( "SELECT * FROM $table_name WHERE url_to_connect = %s", $current_url);
        }

        $results = $wpdb->get_row($safe_sql);

        if($results === NULL){

            return false;

        }else{

            //init $hreflang_output
            $hreflang_output = array();

            //generate an array with all the connections
            for ( $i=1; $i<=100; $i++ ){

                //check if this is a valid hreflang
                if( strlen( $results->{'url' . $i} ) > 0 and strlen( $results->{'language' . $i} ) > 0 ){

                    //echo the locale only if is > than 0 and if the related language is not x-default
                    if( strlen( $results->{'locale' . $i} ) > 0 and $results->{'language' . $i} != "x-default" ){
                        $locale = "-" . $results->{'locale' . $i};
                    }else{
                        $locale = "";
                    }

                    /**
                     * Add the link element to the output and sanitize the URL in the href attribute of the link element
                     * if the 'Sanitize URL' option is enabled.
                     */
                    if(intval(get_option("da_hm_sanitize_url"), 10) == 1){
                        $hreflang_output[$i] = '<link rel="alternate" href="' . esc_url($results->{'url' . $i}) . '" hreflang="' . $results->{'language' . $i} . $locale . '" />';
                    }else{
                        $hreflang_output[$i] = '<link rel="alternate" href="' . $results->{'url' . $i} . '" hreflang="' . $results->{'language' . $i} . $locale . '" />';
                    }

                }

            }

            if( is_array($hreflang_output) )
                return $hreflang_output;
            else{
                return false;
            }

        }

    }

    /*
     * Get the current URL
     */
    public function get_current_url(){

        if(get_option("da_hm_detect_url_mode") === 'server_variable'){

            //Detect the URL using the "Server Variable" method
            if( intval(get_option("da_hm_https"), 10) == 0 ){
                $protocol = 'http';
            }else{
                $protocol = 'https';
            }
            return $protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        }else{

            //Detect the URL using the "WP Request" method
            global $wp;
            return trailingslashit(home_url(add_query_arg(array(),$wp->request)));

        }

    }

    /*
     * Verify if the provided $url_to_connect already exists in the db table
     *
     * @param $url_to_connect
     * @return bool True if exists or false if does not exist
     */
    public function url_to_connect_exists($url_to_connect){

        global $wpdb;
        $table_name = $wpdb->prefix . "da_hm_connect";
        $safe_sql = $wpdb->prepare("SELECT id FROM $table_name WHERE url_to_connect = %s", $url_to_connect);
        $wpdb->get_results($safe_sql, ARRAY_A);

        if($wpdb->num_rows > 0){
            return true;
        }else{
            return false;
        }

    }

    /*
     * Returns the number of records available in the '[prefix]_da_hm_connect' db table
     *
     * @return int The number of records available in the '[prefix]_da_hm_connect' db table
     */
    public function number_of_connections(){

        global $wpdb;
        $table_name  = $wpdb->prefix . $this->get( 'slug' ) . "_connect";
        $total_items = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );

        return $total_items;

    }

    /**
     * Generates the 'url_to_connect' value based on the 'Import Language' and "Import Locale' options.
     *
     * @param $connection Array with all the information of a connection (all the fields of the 'connect' db table
     * except 'id')
     * @return String
     */
    public function generate_url_to_connect($connection){

        //Retrieve the 'Import Language' and the "Import Locale' from the options
        $import_language = get_option($this->get('slug') . '_import_language');
        $import_locale = get_option($this->get('slug') . '_import_locale');

        /**
         * Search the 'url' where the related 'language' and 'locale' are the same of the ones specified with the
         * 'Import Language' and 'Import Locale' options. In case this 'url' is found use it as the 'url_to_connect'
         * value.
         */
        for($i=1;$i<=100;$i++){

            if((string)$connection['language' . $i] === (string)$import_language and (string)$connection['locale' . $i] === (string)$import_locale){
                $url_to_connect = $connection['url' . $i];
                break;
            }

        }

        /**
         * If a specific 'url_to_connect' is found return it, otherwise use the 'url_to_connect' value available in the
         * imported XML file.
         */
        if(isset($url_to_connect)){
            return $url_to_connect;
        }else{
            return $connection['url_to_connect'];
        }

    }

	/**
	 * Get the permalink of the post.
	 *
	 * Note that if the:
	 *
	 * - "Sample Permalink" option is enabled
	 * - And if the post status is 'future'
	 *
	 * The value of the permalink field is generated with the get_sample_permalink() function.
	 *
	 * @param $post_id The post id.
	 * @param $require True if the wp-admin/includes/post.php file should be required.
	 *
	 * @return String The permalink of the post associated with the provided post id.
	 */
    public function get_permalink($post_id, $require = false){

	    $post_status = get_post_status($post_id);

	    /**
	     * If the post status is 'future' the value of the url_to_connect field is generated
	     * with the get_future_permalink() function. Otherwise it's generated with the get_permalink() function.
	     */
	    if(intval(get_option('da_hm_sample_future_permalink'), 10) === 1 and $post_status === 'future'){

		    if($require){
			    require_once(ABSPATH . 'wp-admin/includes/post.php');
		    }

		    $permalink_a = get_sample_permalink($post_id);
		    $permalink = preg_replace('/\%[^\%]+name\%/', $permalink_a[1], $permalink_a[0]);

	    }else{

	    	$permalink = get_permalink($post_id);

	    }

		return $permalink;

	}

}