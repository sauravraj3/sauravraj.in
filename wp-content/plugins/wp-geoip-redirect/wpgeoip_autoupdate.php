<?php

add_action( 'wp_ajax_wpgeoip_autoupdate', 'wpgeoip_ajax_autoupdate' );

function wpgeoip_ajax_autoupdate() {

	$blogURL = get_bloginfo('url');
	$url = 'http://crivion.com/envato-licensing/index.php';

	if(!isset($_POST['license_code']) OR empty($_POST['license_code'])) die('<div class="updated below-h2 updated-red">License code required</div>');

	//open connection
	$ch = curl_init();

	//set the url, number of POST vars, POST data
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST, 2);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_POSTFIELDS, 'license_code=' . $_POST['license_code'] . '&blogURL=' . $blogURL);
	curl_setopt($ch,CURLOPT_USERAGENT, 'crivion/envato-license-checker-v1.0');

	//execute post
	$result = curl_exec($ch);

	//if LICENSE_VALID_AUTOUPDATE_ENABLED
	if( $result == 'LICENSE_VALID_AUTOUPDATE_ENABLED' ) {
		update_option('wpgeoip_autoupdate', 'Congratulations, AUTOUPDATES ARE ENABLED');
		die('<div class="updated below-h2 updated-red">Congratulations! You have activated auto updates.</div>');
	}else{
		die('<div class="updated below-h2 updated-red">' . $result . '</div>');;
	}

	//close connection
	curl_close($ch);

	die();
}

function wpgeoip_autoupdate() {

	$status = get_option('wpgeoip_autoupdate', 'Not Enabled');

	?>

	<div id="wrap" class="wpgeoip-wrapper">
	<br />
    <img src="<?= plugin_dir_url(__FILE__) ?>/assets/images/icon32x32.png" style="float:left;"/> 
	<h1 style="float:left;margin-top: 10px;margin-left:10px;">WPGeoIP Autoupdate</h1>
    <div style="clear:both;"></div>
    AUTO UPDATE WPGeoIP Country Redirect when a new release is published<br />
    <hr />
	<br />

	<div class="updated">
		<strong>STATUS:</strong> <span class="wpgeoip-status"><?= $status ?></span>
	</div>
	
	<?php 
	if( 'Not Enabled' != get_option('wpgeoip_autoupdate', 'Not Enabled') ) : 
	?>
	<form method="POST" id="wpgeoip_autoupdate_frm">
	<br />
	<h3>Enter your Envato License to Enable Autoupdate (<a href="https://www.youtube.com/watch?v=zOJracLZao8" target="_blank">How to Find License Code</a>)</h3>
	<hr />
	<input type="text" name="wpgeoip_license_code" size="80">

	<input type="submit" name="sb_wpgeoip" value="ENABLE AUTOUPDATE" class="button button-primary">

	</form>

	<div class="wpgeoip-license-result"></div>
	<?php 
	else:
		echo '<br/><br/>Thanks for validating your license. <br/>Please install Envato Market release by Envato team itself for better automatic updates. <br/>
			Download: <a href="http://envato.github.io/wp-envato-market/dist/envato-market.zip" target="_blank">http://envato.github.io/wp-envato-market/dist/envato-market.zip</a><br/>
			Documentation: <a href="http://envato.github.io/wp-envato-market/" target="_blank">http://envato.github.io/wp-envato-market/</a><br/><br/>That will update not only WP GeoIP Country Redirect but all your themes and plugins from Envato Marketplaces!';

	endif; 
	?>
	
	</div>
	<?php

}