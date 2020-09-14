<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

 <?php
$explode= $_SERVER['REQUEST_URI'];
$struri=explode('/',$explode);
?>
<!--<link rel='alternate' hreflang='en-in' href='https://sauravraj.in/en-in/<?php $r=$struri[1];if ($r=="about-me"){echo $r="about-me";} 
    elseif ($r=="our-services/"){echo $r="our-services/";} elseif($r!=""){echo $r;echo "/";}?><?php $t=$struri[2];if($t!=""){echo $t;echo "/";}?>'>
    
  <link rel='alternate' hreflang='en-us' href='https://sauravraj.in/<?php $r=$struri[1];if ($r=="about-me"){echo $r="about-me";} 
    elseif ($r=="seo-expert-los-angeles-california/"){echo $r="seo-expert-los-angeles-california/";} elseif($r!=""){echo $r;echo "/";}?><?php $t=$struri[2];if($t!=""){echo $t;echo "/";}?>'>-->  
    
    
<?php
	elegant_description();
	elegant_keywords();
	elegant_canonical();

	/**
	 * Fires in the head, before {@see wp_head()} is called. This action can be used to
	 * insert elements into the beginning of the head before any styles or scripts.
	 *
	 * @since 1.0
	 */
	do_action( 'et_head_meta' );

	$template_directory_uri = get_template_directory_uri();
?>

	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-154889124-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-154889124-1');
</script>

	<script type="text/javascript">
		document.documentElement.className = 'js';
	</script>
	
	<?php if ( is_front_page() ) { ?>

	
	<script type="application/ld+json">
 { "@context": "https://schema.org",
 "@type": "Organization",
 "name": "Saurav Raj",
 "legalName" : "Saurav Raj",
 "url": "https://sauravraj.in/",
 "logo": "https://sauravraj.in/wp-content/uploads/2019/12/logo.png",
 "foundingDate": "2015",
 "founders": [
 {
 "@type": "Person",
 "name": "Saurav Raj"
 },
 {
 "@type": "Person",
 "name": "Saurav Raj"
 } ],
 "address": {
 "@type": "PostalAddress",
 "streetAddress": "First Floor, H.S. Complex",
 "addressLocality": "Jamshedpur",
 "addressRegion": "Jharkhand",
 "postalCode": "831004",
 "addressCountry": "INDIA"
 },
 "contactPoint": {
 "@type": "ContactPoint",
 "contactType": "customer support",
 "telephone": "+91-7222968431",
 "email": "info@sauravraj.in"
 },
 "sameAs": [ 
 "https://www.facebook.com/saurav.raj.5851",
 "https://www.linkedin.com/in/saurav-raj-291442141/"
 ]}
</script>
<?php } ?>

<?php if ( is_front_page() ) { ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "LocalBusiness",
  "name": "Saurav Raj",
  "image": "https://sauravraj.in/wp-content/uploads/2019/12/logo.png",
  "@id": "",
  "url": "https://sauravraj.in/",
  "telephone": "+91-7222968431",
        "priceRange": "$450 - $1200 Per Year",

  "address": {
  "@type": "PostalAddress",
 "streetAddress": "First Floor, H.S. Complex",
 "addressLocality": "Jamshedpur",
 "addressRegion": "Jharkhand",
 "postalCode": "831004",
 "addressCountry": "INDIA"
  },

  "openingHoursSpecification": [{
    "@type": "OpeningHoursSpecification",
    "dayOfWeek": [
      "Monday",
      "Tuesday",
      "Wednesday",
      "Thursday",
      "Friday"
    ],
    "opens": "09:00",
    "closes": "18:00"
  },{
    "@type": "OpeningHoursSpecification",
    "dayOfWeek": "Saturday",
    "opens": "09:00",
    "closes": "17:00"
  }],
  "sameAs": [
 "https://www.facebook.com/saurav.raj.5851",
 "https://www.linkedin.com/in/saurav-raj-291442141/"
  ]
}
</script>
	<?php } ?>
	
	  <script type="text/javascript"> 
        document.getElementById("hubspot-link__container sproket").onclick = function() { 
  
            document.getElementById("hubspot-link__container sproket").style.display = "none"; 
 
    </script> 
<style>

.fa {
    display: inline-block;
    font: normal normal normal 14px/1 FontAwesome;
    font-size: inherit;
      font-display: swap;
    text-rendering: auto;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    
}

.hubspot-link__container.sproket {
    display: none !important;
}
.et_pb_module.et_pb_blurb.et_pb_blurb_1_tb_body.cate.et_pb_bg_layout_light.et_pb_text_align_left.et_pb_blurb_position_left a 
 {

   color: #f92c8b !important;
    text-decoration: none;
    font-weight: 500;
    font-size: 17px;
  }
.mobile_menu_bar:before {
    color: #0054ff !important;
}
#page-container {
    padding-top: 80px !important;
}
.menu-item a img, img.menu-image-title-after, img.menu-image-title-before, img.menu-image-title-above, img.menu-image-title-below , .menu-image-hover-wrapper .menu-image-title-above {
    border: none;
    box-shadow: none;
    vertical-align: middle;
    width: auto;
    display: inline;
}
.menu-image-hover-wrapper img.hovered-image,
.menu-item:hover .menu-image-hover-wrapper img.menu-image {
    opacity: 0;
    transition: opacity 0.25s ease-in-out 0s;
}
.menu-item:hover img.hovered-image {
    opacity: 1;
}
.menu-image-title-after.menu-image-not-hovered img,
.menu-image-hovered.menu-image-title-after .menu-image-hover-wrapper, .menu-image-title-before.menu-image-title {
    padding-right: 10px;
}
.menu-image-title-before.menu-image-not-hovered img,
.menu-image-hovered.menu-image-title-before .menu-image-hover-wrapper, .menu-image-title-after.menu-image-title {
    padding-left: 10px;
}

.menu-image-title.menu-image-title-above, .menu-image-title.menu-image-title-below {
    text-align: center;
    display: block;
}
.menu-image-title-above.menu-image-not-hovered > img,
.menu-image-hovered.menu-image-title-above .menu-image-hover-wrapper, .menu-image-title-above .menu-image-hover-wrapper {
    display: block;
    padding-top: 10px;
    margin: 0 auto !important;
}
.menu-image-title-below.menu-image-not-hovered > img,
.menu-image-hovered.menu-image-title-below .menu-image-hover-wrapper, .menu-image-title-below .menu-image-hover-wrapper {
    display: block;
    padding-bottom: 10px;
    margin: 0 auto !important;
}
.menu-image-title-hide .menu-image-title, .menu-image-title-hide.menu-image-title {
	display: none;
}
/* Alignment of the Menu items. Divi, Twenty 17*/
#et-top-navigation .nav li.menu-item, .navigation-top .main-navigation li {
    display: inline-flex;
}
/* The laptop with borders */
.laptop {
  -webkit-transform-origin: 0 0;
  transform-origin: 0 0;
  -webkit-transform: scale(.6) translate(-50%); /* Scaled down for a better Try-it experience (change to 1 for full scale) */
  transform: scale(.6) translate(-50%); /* Scaled down for a better Try-it experience (change to 1 for full scale) */
  left: 50%;
  position: absolute;
  width: 1366px;
  height: 800px;
  border-radius: 6px;
  border-style: solid;
  border-color: black;
  border-width: 24px 24px 80px;
  background-color: black;

}

/* The keyboard of the laptop */
.laptop:after {
  content: '';
  display: block;
  position: absolute;
  width: 1600px;
  height: 60px;
  margin: 80px 0 0 -110px;
  background: black;
  border-radius: 6px;
}

/* The top of the keyboard */
.laptop:before {
  content: '';
  display: block;
  position: absolute;
  width: 250px;
  height: 30px;
  bottom: -110px;
  left: 50%;
  -webkit-transform: translate(-50%);
  transform: translate(-50%);
  background: #f1f1f1;
  border-bottom-left-radius: 5px;
  border-bottom-right-radius: 5px;
  z-index: 1;
}

/* The screen (or content) of the device */
.laptop .content {
  width: 1366px;
  height: 800px;
  overflow: hidden;
  border: none;
}
#top-menu li.mega-menu>ul {
    padding-top: 16px !important;
    margin-top: 38px;
}


#top-menu a {
    color: #0e2b5c !important;
    font-style: normal !important;
    text-transform: none !important;
    font-size: 1rem !important;
    font-weight: 500 !important;
}
    h1, h2, h3 {
    font-family: Rubik !important;
    font-weight: 500 !important;
    color: #0e2b5c !important;
    line-height: 1.2em !important;
}
body{color: #627792 !important;background-color: #f3f6f9 !important;font-style:normal;font-size: 1rem;line-height: 1.625em;font-weight: normal !important;}
#main-content {
    background-color: #f3f6f9 !important;
}
.post-content-inner p {
    font-size: 16px !important;
}

p{font-size:1.125rem !important;line-height: 1.625;}
    
</style>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php
	$product_tour_enabled = et_builder_is_product_tour_enabled();
	$page_container_style = $product_tour_enabled ? ' style="padding-top: 0px;"' : ''; ?>
	<div id="page-container"<?php echo et_core_intentionally_unescaped( $page_container_style, 'fixed_string' ); ?>>
<?php
	if ( $product_tour_enabled || is_page_template( 'page-template-blank.php' ) ) {
		return;
	}

	$et_secondary_nav_items = et_divi_get_top_nav_items();

	$et_phone_number = $et_secondary_nav_items->phone_number;

	$et_email = $et_secondary_nav_items->email;

	$et_contact_info_defined = $et_secondary_nav_items->contact_info_defined;

	$show_header_social_icons = $et_secondary_nav_items->show_header_social_icons;

	$et_secondary_nav = $et_secondary_nav_items->secondary_nav;

	$et_top_info_defined = $et_secondary_nav_items->top_info_defined;

	$et_slide_header = 'slide' === et_get_option( 'header_style', 'left' ) || 'fullscreen' === et_get_option( 'header_style', 'left' ) ? true : false;
?>

	<?php if ( $et_top_info_defined && ! $et_slide_header || is_customize_preview() ) : ?>
		<?php ob_start(); ?>
		<div id="top-header"<?php echo $et_top_info_defined ? '' : 'style="display: none;"'; ?>>
			<div class="container clearfix">

			<?php if ( $et_contact_info_defined ) : ?>

				<div id="et-info">
				<?php if ( '' !== ( $et_phone_number = et_get_option( 'phone_number' ) ) ) : ?>
					<span id="et-info-phone"><?php echo et_core_esc_previously( et_sanitize_html_input_text( $et_phone_number ) ); ?></span>
				<?php endif; ?>

				<?php if ( '' !== ( $et_email = et_get_option( 'header_email' ) ) ) : ?>
					<a href="<?php echo esc_attr( 'mailto:' . $et_email ); ?>"><span id="et-info-email"><?php echo esc_html( $et_email ); ?></span></a>
				<?php endif; ?>

				<?php
				if ( true === $show_header_social_icons ) {
					get_template_part( 'includes/social_icons', 'header' );
				} ?>
				</div> <!-- #et-info -->

			<?php endif; // true === $et_contact_info_defined ?>

				<div id="et-secondary-menu">
				<?php
					if ( ! $et_contact_info_defined && true === $show_header_social_icons ) {
						get_template_part( 'includes/social_icons', 'header' );
					} else if ( $et_contact_info_defined && true === $show_header_social_icons ) {
						ob_start();

						get_template_part( 'includes/social_icons', 'header' );

						$duplicate_social_icons = ob_get_contents();

						ob_end_clean();

						printf(
							'<div class="et_duplicate_social_icons">
								%1$s
							</div>',
							et_core_esc_previously( $duplicate_social_icons )
						);
					}

					if ( '' !== $et_secondary_nav ) {
						echo et_core_esc_wp( $et_secondary_nav );
					}

					et_show_cart_total();
				?>
				</div> <!-- #et-secondary-menu -->

			</div> <!-- .container -->
		</div> <!-- #top-header -->
	<?php
		$top_header = ob_get_clean();

		/**
		 * Filters the HTML output for the top header.
		 *
		 * @since 3.10
		 *
		 * @param string $top_header
		 */
		echo et_core_intentionally_unescaped( apply_filters( 'et_html_top_header', $top_header ), 'html' );
	?>
	<?php endif; // true ==== $et_top_info_defined ?>

	<?php if ( $et_slide_header || is_customize_preview() ) : ?>
		<?php ob_start(); ?>
		<div class="et_slide_in_menu_container">
			<?php if ( 'fullscreen' === et_get_option( 'header_style', 'left' ) || is_customize_preview() ) { ?>
				<span class="mobile_menu_bar et_toggle_fullscreen_menu"></span>
			<?php } ?>

			<?php
				if ( $et_contact_info_defined || true === $show_header_social_icons || false !== et_get_option( 'show_search_icon', true ) || class_exists( 'woocommerce' ) || is_customize_preview() ) { ?>
					<div class="et_slide_menu_top">

					<?php if ( 'fullscreen' === et_get_option( 'header_style', 'left' ) ) { ?>
						<div class="et_pb_top_menu_inner">
					<?php } ?>
			<?php }

				if ( true === $show_header_social_icons ) {
					get_template_part( 'includes/social_icons', 'header' );
				}

				et_show_cart_total();
			?>
			<?php if ( false !== et_get_option( 'show_search_icon', true ) || is_customize_preview() ) : ?>
				<?php if ( 'fullscreen' !== et_get_option( 'header_style', 'left' ) ) { ?>
					<div class="clear"></div>
				<?php } ?>
				<form role="search" method="get" class="et-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php
						printf( '<input type="search" class="et-search-field" placeholder="%1$s" value="%2$s" name="s" title="%3$s" />',
							esc_attr__( 'Search &hellip;', 'Divi' ),
							get_search_query(),
							esc_attr__( 'Search for:', 'Divi' )
						);
					?>
					<button type="submit" id="searchsubmit_header"></button>
				</form>
			<?php endif; // true === et_get_option( 'show_search_icon', false ) ?>

			<?php if ( $et_contact_info_defined ) : ?>

				<div id="et-info">
				<?php if ( '' !== ( $et_phone_number = et_get_option( 'phone_number' ) ) ) : ?>
					<span id="et-info-phone"><?php echo et_core_esc_previously( et_sanitize_html_input_text( $et_phone_number ) ); ?></span>
				<?php endif; ?>

				<?php if ( '' !== ( $et_email = et_get_option( 'header_email' ) ) ) : ?>
					<a href="<?php echo esc_attr( 'mailto:' . $et_email ); ?>"><span id="et-info-email"><?php echo esc_html( $et_email ); ?></span></a>
				<?php endif; ?>
				</div> <!-- #et-info -->

			<?php endif; // true === $et_contact_info_defined ?>
			<?php if ( $et_contact_info_defined || true === $show_header_social_icons || false !== et_get_option( 'show_search_icon', true ) || class_exists( 'woocommerce' ) || is_customize_preview() ) { ?>
				<?php if ( 'fullscreen' === et_get_option( 'header_style', 'left' ) ) { ?>
					</div> <!-- .et_pb_top_menu_inner -->
				<?php } ?>

				</div> <!-- .et_slide_menu_top -->
			<?php } ?>

			<div class="et_pb_fullscreen_nav_container">
				<?php
					$slide_nav = '';
					$slide_menu_class = 'et_mobile_menu';

					$slide_nav = wp_nav_menu( array( 'theme_location' => 'primary-menu', 'container' => '', 'fallback_cb' => '', 'echo' => false, 'items_wrap' => '%3$s' ) );
					$slide_nav .= wp_nav_menu( array( 'theme_location' => 'secondary-menu', 'container' => '', 'fallback_cb' => '', 'echo' => false, 'items_wrap' => '%3$s' ) );
				?>

				<ul id="mobile_menu_slide" class="<?php echo esc_attr( $slide_menu_class ); ?>">

				<?php
					if ( '' === $slide_nav ) :
				?>
						<?php if ( 'on' === et_get_option( 'divi_home_link' ) ) { ?>
							<li <?php if ( is_home() ) echo( 'class="current_page_item"' ); ?>><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'Divi' ); ?></a></li>
						<?php }; ?>

						<?php show_page_menu( $slide_menu_class, false, false ); ?>
						<?php show_categories_menu( $slide_menu_class, false ); ?>
				<?php
					else :
						echo et_core_esc_wp( $slide_nav ) ;
					endif;
				?>

				</ul>
			</div>
		</div>
	<?php
		$slide_header = ob_get_clean();

		/**
		 * Filters the HTML output for the slide header.
		 *
		 * @since 3.10
		 *
		 * @param string $top_header
		 */
		echo et_core_intentionally_unescaped( apply_filters( 'et_html_slide_header', $slide_header ), 'html' );
	?>
	<?php endif; // true ==== $et_slide_header ?>

	<?php ob_start(); ?>
		<header id="main-header" data-height-onload="<?php echo esc_attr( et_get_option( 'menu_height', '66' ) ); ?>">
			<div class="container clearfix et_menu_container">
			<?php
				$logo = ( $user_logo = et_get_option( 'divi_logo' ) ) && ! empty( $user_logo )
					? $user_logo
					: $template_directory_uri . '/images/logo.png';

				ob_start();
			?>
				<div class="logo_container">
					<span class="logo_helper"></span>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<img src="<?php echo esc_attr( $logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" id="logo" data-height-percentage="<?php echo esc_attr( et_get_option( 'logo_height', '54' ) ); ?>" />
					</a>
				</div>
			<?php
				$logo_container = ob_get_clean();

				/**
				 * Filters the HTML output for the logo container.
				 *
				 * @since 3.10
				 *
				 * @param string $logo_container
				 */
				echo et_core_intentionally_unescaped( apply_filters( 'et_html_logo_container', $logo_container ), 'html' );
			?>
				<div id="et-top-navigation" data-height="<?php echo esc_attr( et_get_option( 'menu_height', '66' ) ); ?>" data-fixed-height="<?php echo esc_attr( et_get_option( 'minimized_menu_height', '40' ) ); ?>">
					<?php if ( ! $et_slide_header || is_customize_preview() ) : ?>
						<nav id="top-menu-nav">
						<?php
							$menuClass = 'nav';
							if ( 'on' === et_get_option( 'divi_disable_toptier' ) ) $menuClass .= ' et_disable_top_tier';
							$primaryNav = '';

							$primaryNav = wp_nav_menu( array( 'theme_location' => 'primary-menu', 'container' => '', 'fallback_cb' => '', 'menu_class' => $menuClass, 'menu_id' => 'top-menu', 'echo' => false ) );
							if ( empty( $primaryNav ) ) :
						?>
							<ul id="top-menu" class="<?php echo esc_attr( $menuClass ); ?>">
								<?php if ( 'on' === et_get_option( 'divi_home_link' ) ) { ?>
									<li <?php if ( is_home() ) echo( 'class="current_page_item"' ); ?>><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'Divi' ); ?></a></li>
								<?php }; ?>

								<?php show_page_menu( $menuClass, false, false ); ?>
								<?php show_categories_menu( $menuClass, false ); ?>
							</ul>
						<?php
							else :
								echo et_core_esc_wp( $primaryNav );
							endif;
						?>
						</nav>
					<?php endif; ?>

					<?php
					if ( ! $et_top_info_defined && ( ! $et_slide_header || is_customize_preview() ) ) {
						et_show_cart_total( array(
							'no_text' => true,
						) );
					}
					?>

					<?php if ( $et_slide_header || is_customize_preview() ) : ?>
						<span class="mobile_menu_bar et_pb_header_toggle et_toggle_<?php echo esc_attr( et_get_option( 'header_style', 'left' ) ); ?>_menu"></span>
					<?php endif; ?>

					<?php if ( ( false !== et_get_option( 'show_search_icon', true ) && ! $et_slide_header ) || is_customize_preview() ) : ?>
					<div id="et_top_search">
						<span id="et_search_icon"></span>
					</div>
					<?php endif; // true === et_get_option( 'show_search_icon', false ) ?>

					<?php

					/**
					 * Fires at the end of the 'et-top-navigation' element, just before its closing tag.
					 *
					 * @since 1.0
					 */
					do_action( 'et_header_top' );

					?>
				</div> <!-- #et-top-navigation -->
			</div> <!-- .container -->
			<div class="et_search_outer">
				<div class="container et_search_form_container">
					<form role="search" method="get" class="et-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php
						printf( '<input type="search" class="et-search-field" placeholder="%1$s" value="%2$s" name="s" title="%3$s" />',
							esc_attr__( 'Search &hellip;', 'Divi' ),
							get_search_query(),
							esc_attr__( 'Search for:', 'Divi' )
						);
					?>
					</form>
					<span class="et_close_search_field"></span>
				</div>
			</div>
		</header> <!-- #main-header -->
	<?php
		$main_header = ob_get_clean();

		/**
		 * Filters the HTML output for the main header.
		 *
		 * @since 3.10
		 *
		 * @param string $main_header
		 */
		echo et_core_intentionally_unescaped( apply_filters( 'et_html_main_header', $main_header ), 'html' );
	?>
		<div id="et-main-area">
	<?php
		/**
		 * Fires after the header, before the main content is output.
		 *
		 * @since 3.10
		 */
		do_action( 'et_before_main_content' );
