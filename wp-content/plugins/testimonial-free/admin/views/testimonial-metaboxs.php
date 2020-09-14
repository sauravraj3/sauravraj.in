<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

/**
 * Sanitize function for text field.
 */
if ( ! function_exists( 'spftestimonial_sanitize_text' ) ) {
	function spftestimonial_sanitize_text( $value ) {

		$safe_text = filter_var( $value, FILTER_SANITIZE_STRING );
		return $safe_text;

	}
}

//
// Metabox of the testimonial shortcode generator.
// Set a unique slug-like ID.
//
$prefix_shortcode_opts = 'sp_tpro_shortcode_options';

//
// Testimonial metabox.
//
CSF::createMetabox(
	$prefix_shortcode_opts, array(
		'title'     => __( 'Shortcode Options', 'testimonial-free' ),
		'class'     => 'spt-main-class',
		'post_type' => 'sp_tfree_shortcodes',
		// 'post_type' => 'sp_tpro_shortcodes',
		'context'   => 'normal',
	)
);

//
// General Settings section.
//
CSF::createSection(
	$prefix_shortcode_opts, array(
		'title'  => __( 'General Settings', 'testimonial-free' ),
		'icon'   => 'fa fa-wrench',
		'fields' => array(

			array(
				'id'       => 'layout',
				'type'     => 'select_f',
				'title'    => __( 'Layout', 'testimonial-free' ),
				'subtitle' => __( 'Select a layout to display the testimonials.', 'testimonial-free' ),
				'options'  => array(
					'slider'         => array(
						'name'     => __( 'Slider', 'testimonial-free' ),
						'pro_only' => false,
					),
					'grid'           => array(
						'name'     => __( 'Grid (Pro)', 'testimonial-free' ),
						'pro_only' => true,
					),
					'masonry'        => array(
						'name'     => __( 'Masonry (Pro)', 'testimonial-free' ),
						'pro_only' => true,
					),
					'list'           => array(
						'name'     => __( 'List (Pro)', 'testimonial-free' ),
						'pro_only' => true,
					),
					'filter_grid'    => array(
						'name'     => __( 'Filter-Grid (Pro)', 'testimonial-free' ),
						'pro_only' => true,
					),
					'filter_masonry' => array(
						'name'     => __( 'Filter-Masonry (Pro)', 'testimonial-free' ),
						'pro_only' => true,
					),
				),
				'default'  => 'slider',
			),
			array(
				'id'       => 'theme_style',
				'type'     => 'select_f',
				'title'    => __( 'Select Theme', 'testimonial-free' ),
				'subtitle' => __( 'Select which theme you want to display.', 'testimonial-free' ),
				'options'  => array(
					'theme-one'   => array(
						'name'     => __( 'Theme One', 'testimonial-free' ),
						'pro_only' => false,
					),
					'theme-two'   => array(
						'name'     => __( '9+ Themes (Pro)', 'testimonial-free' ),
						'pro_only' => true,
					),
				),
				'default'  => 'theme-one',
			),
			array(
				'id'       => 'display_testimonials_from',
				'type'     => 'select_f',
				'title'    => __( 'Display Testimonials from', 'testimonial-free' ),
				'subtitle' => __( 'Select an option to display the testimonials.', 'testimonial-free' ),
				'options'  => array(
					'latest'                => array(
						'name'     => __( 'Latest', 'testimonial-free' ),
						'pro_only' => false,
					),
					'category'              => array(
						'name'     => __( 'Category (Pro)', 'testimonial-free' ),
						'pro_only' => true,
					),
					'specific_testimonials' => array(
						'name'     => __( 'Specific Testimonials (Pro)', 'testimonial-free' ),
						'pro_only' => true,
					),
				),
				'default'  => 'latest',
			),
			array(
				'id'       => 'number_of_total_testimonials',
				'type'     => 'spinner',
				'title'    => __( 'Total Testimonials', 'testimonial-free' ),
				'subtitle' => __( 'Number of total testimonials to display.', 'testimonial-free' ),
				'default'  => '10',
				'min'      => -1,
			),
			array(
				'id'       => 'testimonial_order_by',
				'type'     => 'select',
				'title'    => __( 'Order by', 'testimonial-free' ),
				'subtitle' => __( 'Select an order by option.', 'testimonial-free' ),
				'options'  => array(
					'ID'       => __( 'ID', 'testimonial-free' ),
					'date'     => __( 'Date', 'testimonial-free' ),
					'title'    => __( 'Title', 'testimonial-free' ),
					'modified' => __( 'Modified', 'testimonial-free' ),
				),
				'default'  => 'date',
			),
			array(
				'id'       => 'testimonial_order',
				'type'     => 'select',
				'title'    => __( 'Order', 'testimonial-free' ),
				'subtitle' => __( 'Select an order option.', 'testimonial-free' ),
				'options'  => array(
					'ASC'  => __( 'Ascending', 'testimonial-free' ),
					'DESC' => __( 'Descending', 'testimonial-free' ),
				),
				'default'  => 'DESC',
			),
			array(
				'type'    => 'subheading',
				'content' => __( 'Responsive Settings', 'testimonial-free' ),
			),
			array(
				'id'       => 'number_of_testimonials',
				'type'     => 'spinner',
				'title'    => __( 'Testimonial Column(s)', 'testimonial-free' ),
				'subtitle' => __( 'Set number of column(s) for the screen larger than 1280px.', 'testimonial-free' ),
				'default'  => '1',
			),
			array(
				'id'       => 'number_of_testimonials_desktop',
				'type'     => 'spinner',
				'title'    => __( 'Testimonial Column(s) on Desktop', 'testimonial-free' ),
				'subtitle' => __( 'Set number of column on desktop for the screen smaller than 1280px.', 'testimonial-free' ),
				'default'  => '1',
			),
			array(
				'id'       => 'number_of_testimonials_small_desktop',
				'type'     => 'spinner',
				'title'    => __( 'Testimonial Column(s) on Small Desktop', 'testimonial-free' ),
				'subtitle' => __( 'Set number of column on small desktop for the screen smaller than 980px.', 'testimonial-free' ),
				'default'  => '1',
			),
			array(
				'id'       => 'number_of_testimonials_tablet',
				'type'     => 'spinner',
				'title'    => __( 'Testimonial Column(s) on Tablet', 'testimonial-free' ),
				'subtitle' => __( 'Set number of column on tablet for the screen smaller than 736px.', 'testimonial-free' ),
				'default'  => '1',
			),
			array(
				'id'       => 'number_of_testimonials_mobile',
				'type'     => 'spinner',
				'title'    => __( 'Testimonial Column(s) on Mobile', 'testimonial-free' ),
				'subtitle' => __( 'Set number of column on mobile for the screen smaller than 480px.', 'testimonial-free' ),
				'default'  => '1',
			),

		),
	)
);

//
// Slider Settings section.
//
CSF::createSection(
	$prefix_shortcode_opts, array(
		'title'  => __( 'Slider Settings', 'testimonial-free' ),
		'icon'   => 'fa fa-sliders',
		'fields' => array(

			array(
				'id'       => 'slider_auto_play',
				'type'     => 'button_set',
				'title'    => __( 'AutoPlay', 'testimonial-free' ),
				'subtitle' => __( 'On/Off auto play.', 'testimonial-free' ),
				'options'  => array(
					'true'          => __( 'On', 'testimonial-free' ),
					'false'         => __( 'Off', 'testimonial-free' ),
					'off_on_mobile' => __( 'Off on Mobile', 'testimonial-free' ),
				),
				'default'  => 'true',
			),
			array(
				'id'         => 'slider_auto_play_speed',
				'type'       => 'spinner',
				'title'      => __( 'AutoPlay Speed', 'testimonial-free' ),
				'subtitle'   => __( 'Set auto play speed.', 'testimonial-free' ),
				'after'      => __( '(millisecond)', 'testimonial-free' ),
				'default'    => '3000',
				'min'        => 1,
				'dependency' => array(
					'slider_auto_play',
					'any',
					'true,off_on_mobile',
				),
			),
			array(
				'id'       => 'slider_scroll_speed',
				'type'     => 'spinner',
				'title'    => __( 'Pagination Speed', 'testimonial-free' ),
				'subtitle' => __( 'Set pagination/slide scroll speed.', 'testimonial-free' ),
				'after'    => __( '(millisecond)', 'testimonial-free' ),
				'default'  => '600',
				'min'      => 1,
			),
			array(
				'id'       => 'slider_pause_on_hover',
				'type'     => 'switcher',
				'title'    => __( 'Pause on Hover', 'testimonial-free' ),
				'subtitle' => __( 'On/Off slider pause on hover.', 'testimonial-free' ),
				'default'  => true,
			),
			array(
				'id'       => 'slider_infinite',
				'type'     => 'switcher',
				'title'    => __( 'Infinite Loop', 'testimonial-free' ),
				'subtitle' => __( 'On/Off infinite loop mode.', 'testimonial-free' ),
				'default'  => true,
			),
			array(
				'type'    => 'subheading',
				'content' => __( 'Navigation Settings', 'testimonial-free' ),
			),
			array(
				'id'       => 'navigation',
				'type'     => 'button_set',
				'title'    => __( 'Navigation', 'testimonial-free' ),
				'subtitle' => __( 'Show/Hide slider navigation.', 'testimonial-free' ),
				'options'  => array(
					'true'           => __( 'Show', 'testimonial-free' ),
					'false'          => __( 'Hide', 'testimonial-free' ),
					'hide_on_mobile' => __( 'Hide on Mobile', 'testimonial-free' ),
				),
				'default'  => 'true',
			),
			array(
				'id'         => 'navigation_arrow_color',
				'type'       => 'color',
				'title'      => __( 'Arrow Color', 'testimonial-free' ),
				'subtitle'   => __( 'Set the navigation arrow color.', 'testimonial-free' ),
				'default'    => '#444444',
				'dependency' => array(
					'navigation',
					'any',
					'true,hide_on_mobile',
				),
			),
			array(
				'id'         => 'navigation_hover_arrow_color',
				'type'       => 'color',
				'title'      => __( 'Arrow Hover Color', 'testimonial-free' ),
				'subtitle'   => __( 'Set the navigation arrow hover color.', 'testimonial-free' ),
				'default'    => '#52b3d9',
				'dependency' => array(
					'navigation',
					'any',
					'true,hide_on_mobile',
				),
			),

			array(
				'type'    => 'subheading',
				'content' => __( 'Pagination Settings', 'testimonial-free' ),
			),
			array(
				'id'       => 'pagination',
				'type'     => 'button_set',
				'title'    => __( 'Pagination', 'testimonial-free' ),
				'subtitle' => __( 'Show/Hide pagination.', 'testimonial-free' ),
				'options'  => array(
					'true'           => __( 'Show', 'testimonial-free' ),
					'false'          => __( 'Hide', 'testimonial-free' ),
					'hide_on_mobile' => __( 'Hide on Mobile', 'testimonial-free' ),
				),
				'default'  => 'true',
			),
			array(
				'id'         => 'pagination_color',
				'type'       => 'color',
				'title'      => __( 'Dots Color', 'testimonial-free' ),
				'subtitle'   => __( 'Set the pagination dots color.', 'testimonial-free' ),
				'default'    => '#cccccc',
				'dependency' => array(
					'pagination',
					'any',
					'true,hide_on_mobile',
				),
			),
			array(
				'id'         => 'pagination_active_color',
				'type'       => 'color',
				'title'      => __( 'Active Color', 'testimonial-free' ),
				'subtitle'   => __( 'Set the pagination active color.', 'testimonial-free' ),
				'default'    => '#52b3d9',
				'dependency' => array(
					'pagination',
					'any',
					'true,hide_on_mobile',
				),
			),
			array(
				'type'    => 'subheading',
				'content' => __( 'Misc. Settings', 'testimonial-free' ),
			),
			array(
				'id'       => 'adaptive_height',
				'type'     => 'switcher',
				'title'    => __( 'Adaptive Height', 'testimonial-free' ),
				'subtitle' => __( 'On/Off adaptive height for the slider.', 'testimonial-free' ),
				'default'  => false,
			),
			array(
				'id'       => 'slider_swipe',
				'type'     => 'switcher',
				'title'    => __( 'Swipe', 'testimonial-free' ),
				'subtitle' => __( 'On/Off swipe mode.', 'testimonial-free' ),
				'default'  => true,
			),
			array(
				'id'         => 'slider_draggable',
				'type'       => 'switcher',
				'title'      => __( 'Mouse Draggable', 'testimonial-free' ),
				'subtitle'   => __( 'On/Off mouse draggable mode.', 'testimonial-free' ),
				'default'    => true,
				'dependency' => array( 'slider_swipe', '==', 'true' ),
			),
			array(
				'id'       => 'rtl_mode',
				'type'     => 'switcher',
				'title'    => __( 'RTL', 'testimonial-free' ),
				'subtitle' => __( 'On/Off right to left mode.', 'testimonial-free' ),
				'default'  => false,
			),
			array(
				'type'       => 'submessage',
				'style'      => 'danger',
				'content'    => __( 'To make the RTL Mode work, please select an rtl language in the dashboard e.g. Arabic, Hebrew.', 'testimonial-free' ),
				'dependency' => array( 'rtl_mode', '==', 'true' ),
			),

		),
	)
);

//
// Stylization section.
//
CSF::createSection(
	$prefix_shortcode_opts, array(
		'title'  => __( 'Stylization', 'testimonial-free' ),
		'icon'   => 'fa fa-paint-brush',
		'fields' => array(

			array(
				'id'       => 'section_title',
				'type'     => 'switcher',
				'title'    => __( 'Section Title', 'testimonial-free' ),
				'subtitle' => __( 'Show/Hide the shortcode title as testimonial section title e.g. What Our Customers Saying.', 'testimonial-free' ),
				'default'  => false,
			),
			array(
				'id'       => 'testimonial_title',
				'type'     => 'switcher',
				'title'    => __( 'Testimonial Title', 'testimonial-free' ),
				'subtitle' => __( 'Show/Hide testimonial tagline or title.', 'testimonial-free' ),
				'default'  => true,
			),
			array(
				'type'    => 'subheading',
				'content' => __( 'Testimonial Content Settings', 'testimonial-free' ),
			),
			array(
				'id'       => 'testimonial_text',
				'type'     => 'switcher',
				'title'    => __( 'Testimonial Content', 'testimonial-free' ),
				'subtitle' => __( 'Show/Hide testimonial content.', 'testimonial-free' ),
				'default'  => true,
			),
			array(
				'type'    => 'subheading',
				'content' => __( 'Reviewer Information Settings', 'testimonial-free' ),
			),
			array(
				'id'       => 'testimonial_client_name',
				'type'     => 'switcher',
				'title'    => __( 'Name', 'testimonial-free' ),
				'subtitle' => __( 'Show/Hide reviewer name.', 'testimonial-free' ),
				'default'  => true,
			),
			array(
				'id'       => 'testimonial_client_rating',
				'type'     => 'switcher',
				'title'    => __( 'Star Rating', 'testimonial-free' ),
				'subtitle' => __( 'Show/Hide star ratings.', 'testimonial-free' ),
				'default'  => true,
			),
			array(
				'id'         => 'testimonial_client_rating_color',
				'type'       => 'color',
				'title'      => __( 'Star Rating Color', 'testimonial-free' ),
				'subtitle'   => __( 'Set color for star rating.', 'testimonial-free' ),
				'default'    => '#f3bb00',
				'dependency' => array( 'testimonial_client_rating', '==', 'true' ),
			),
			array(
				'id'       => 'client_designation',
				'type'     => 'switcher',
				'title'    => __( 'Identity or Position', 'testimonial-free' ),
				'subtitle' => __( 'Show/Hide identity or position.', 'testimonial-free' ),
				'default'  => true,
			),

		),
	)
);

//
// Typography section.
//
CSF::createSection(
	$prefix_shortcode_opts, array(
		'title'  => __( 'Typography', 'testimonial-free' ),
		'icon'   => 'fa fa-font',
		'fields' => array(
			array(
				'type'    => 'notice',
				'style'   => 'normal',
				'content' => __( 'The Following Typography (900+ Google Fonts) options are available in the <a href="https://shapedplugin.com/plugin/testimonial-pro/" target="_blank">Pro Version</a> only except color fields.', 'testimonial-free' ),
			),
			array(
				'id'       => 'section_title_font_load',
				'type'     => 'switcher',
				'title'    => __( 'Load Section Title Font', 'testimonial-free' ),
				'subtitle' => __( 'On/Off google font for the section title.', 'testimonial-free' ),
				'class'    => 'sp-testimonial-font-load',
				'default'  => true,
			),
			array(
				'id'           => 'section_title_typography',
				'type'         => 'typography',
				'title'        => __( 'Section Title Font', 'testimonial-free' ),
				'subtitle'     => __( 'Set testimonial section title font properties.', 'testimonial-free' ),
				'default'      => array(
					'font-family'    => 'Open Sans',
					'font-weight'    => '600',
					'type'           => 'google',
					'font-size'      => '22',
					'line-height'    => '22',
					'text-align'     => 'center',
					'text-transform' => 'none',
					'letter-spacing' => 0,
					'color'          => '#444444',
				),
				'preview'      => true,
				'preview_text' => 'What Our Customers Saying', // Replace preview text with any text you like.
			),
			array(
				'id'       => 'testimonial_title_font_load',
				'type'     => 'switcher',
				'title'    => __( 'Load Testimonial Title Font', 'testimonial-free' ),
				'subtitle' => __( 'On/Off google font for the testimonial tagline or title.', 'testimonial-free' ),
				'class'    => 'sp-testimonial-font-load',
				'default'  => true,
			),
			array(
				'id'           => 'testimonial_title_typography',
				'type'         => 'typography',
				'title'        => __( 'Testimonial Title Font', 'testimonial-free' ),
				'subtitle'     => __( 'Set testimonial tagline or title font properties.', 'testimonial-free' ),
				'default'      => array(
					'font-family'    => 'Open Sans',
					'font-weight'    => '600',
					'type'           => 'google',
					'font-size'      => '20',
					'line-height'    => '30',
					'text-align'     => 'center',
					'text-transform' => 'none',
					'letter-spacing' => 0,
					'color'          => '#333333',
				),
				'preview'      => true,
				'preview_text' => 'The Testimonial Title', // Replace preview text with any text you like.
			),
			array(
				'id'       => 'testimonial_text_font_load',
				'type'     => 'switcher',
				'title'    => __( 'Load Testimonial Content Font', 'testimonial-free' ),
				'subtitle' => __( 'On/Off google font for the testimonial content.', 'testimonial-free' ),
				'class'    => 'sp-testimonial-font-load',
				'default'  => true,
			),
			array(
				'id'       => 'testimonial_text_typography',
				'type'     => 'typography',
				'title'    => __( 'Testimonial Content Font', 'testimonial-free' ),
				'subtitle' => __( 'Set testimonial content font properties.', 'testimonial-free' ),
				'default'  => array(
					'font-family'    => 'Open Sans',
					'font-weight'    => 'normal',
					'type'           => 'google',
					'font-size'      => '16',
					'line-height'    => '26',
					'text-align'     => 'center',
					'text-transform' => 'none',
					'letter-spacing' => 0,
					'color'          => '#333333',
				),
				'color'    => true,
				'preview'  => true,
			),
			array(
				'id'       => 'client_name_font_load',
				'type'     => 'switcher',
				'title'    => __( 'Load Name Font', 'testimonial-free' ),
				'subtitle' => __( 'On/Off google font for the name.', 'testimonial-free' ),
				'class'    => 'sp-testimonial-font-load',
				'default'  => true,
			),
			array(
				'id'           => 'client_name_typography',
				'type'         => 'typography',
				'title'        => __( 'Name Font', 'testimonial-free' ),
				'subtitle'     => __( 'Set name font properties.', 'testimonial-free' ),
				'default'      => array(
					'font-family'    => 'Open Sans',
					'font-weight'    => '700',
					'type'           => 'google',
					'font-size'      => '16',
					'line-height'    => '24',
					'text-align'     => 'center',
					'text-transform' => 'none',
					'letter-spacing' => 0,
					'color'          => '#333333',
				),
				'color'        => true,
				'preview'      => true,
				'preview_text' => 'Jacob Firebird', // Replace preview text with any text you like.
			),
			array(
				'id'       => 'designation_company_font_load',
				'type'     => 'switcher',
				'title'    => __( 'Load Identity or Position & Company Name Font', 'testimonial-free' ),
				'subtitle' => __( 'On/Off google font for the identity or position & company name.', 'testimonial-free' ),
				'class'    => 'sp-testimonial-font-load',
				'default'  => true,
			),
			array(
				'id'           => 'client_designation_company_typography',
				'type'         => 'typography',
				'title'        => __( 'Identity or Position & Company Name Font', 'testimonial-free' ),
				'subtitle'     => __( 'Set identity or position & company name font properties.', 'testimonial-free' ),
				'default'      => array(
					'font-family'    => 'Open Sans',
					'font-weight'    => 'normal',
					'type'           => 'google',
					'font-size'      => '16',
					'line-height'    => '24',
					'text-align'     => 'center',
					'text-transform' => 'none',
					'letter-spacing' => 0,
					'color'          => '#444444',
				),
				'color'        => true,
				'preview'      => true,
				'preview_text' => 'CEO - Firebird Media Inc.', // Replace preview text with any text you like.
			),
			array(
				'id'       => 'location_font_load',
				'type'     => 'switcher',
				'title'    => __( 'Load Location Font', 'testimonial-free' ),
				'subtitle' => __( 'On/Off google font for the location.', 'testimonial-free' ),
				'class'    => 'sp-testimonial-font-load',
				'default'  => true,
			),
			array(
				'id'           => 'client_location_typography',
				'type'         => 'typography',
				'title'        => __( 'Location Font', 'testimonial-free' ),
				'subtitle'     => __( 'Set location font properties.', 'testimonial-free' ),
				'class'        => 'sp-testimonial-font-color',
				'default'      => array(
					'font-family'    => 'Open Sans',
					'font-weight'    => 'normal',
					'type'           => 'google',
					'font-size'      => '15',
					'line-height'    => '20',
					'text-align'     => 'center',
					'text-transform' => 'none',
					'letter-spacing' => 0,
					'color'          => '#444444',
				),
				'color'        => true,
				'preview'      => true,
				'preview_text' => 'Los Angeles', // Replace preview text with any text you like.
			),
			array(
				'id'       => 'phone_font_load',
				'type'     => 'switcher',
				'title'    => __( 'Load Phone or Mobile Font', 'testimonial-free' ),
				'subtitle' => __( 'On/Off google font for the phone or mobile.', 'testimonial-free' ),
				'class'    => 'sp-testimonial-font-load',
				'default'  => true,
			),
			array(
				'id'           => 'client_phone_typography',
				'type'         => 'typography',
				'title'        => __( 'Phone or Mobile Font', 'testimonial-free' ),
				'subtitle'     => __( 'Set phone or mobile font properties.', 'testimonial-free' ),
				'class'        => 'sp-testimonial-font-color',
				'default'      => array(
					'font-family'    => 'Open Sans',
					'font-weight'    => 'normal',
					'type'           => 'google',
					'font-size'      => '15',
					'line-height'    => '20',
					'text-align'     => 'center',
					'text-transform' => 'none',
					'letter-spacing' => 0,
					'color'          => '#444444',
				),
				'color'        => true,
				'preview'      => true,
				'preview_text' => '+1 234567890', // Replace preview text with any text you like.
			),
			array(
				'id'       => 'email_font_load',
				'type'     => 'switcher',
				'title'    => __( 'Load Email Address Font', 'testimonial-free' ),
				'subtitle' => __( 'On/Off google font for the email address.', 'testimonial-free' ),
				'class'    => 'sp-testimonial-font-load',
				'default'  => true,
			),
			array(
				'id'           => 'client_email_typography',
				'type'         => 'typography',
				'title'        => __( 'Email Address Font', 'testimonial-free' ),
				'subtitle'     => __( 'Set email address font properties.', 'testimonial-free' ),
				'class'        => 'sp-testimonial-font-color',
				'default'      => array(
					'font-family'    => 'Open Sans',
					'font-weight'    => 'normal',
					'type'           => 'google',
					'font-size'      => '15',
					'line-height'    => '20',
					'text-align'     => 'center',
					'text-transform' => 'none',
					'letter-spacing' => 0,
					'color'          => '#444444',
				),
				'color'        => true,
				'preview'      => true,
				'preview_text' => 'mail@yourwebsite.com', // Replace preview text with any text you like.
			),
			array(
				'id'       => 'date_font_load',
				'type'     => 'switcher',
				'title'    => __( 'Load Date Font', 'testimonial-free' ),
				'subtitle' => __( 'On/Off google font for the date.', 'testimonial-free' ),
				'class'    => 'sp-testimonial-font-load',
				'default'  => true,
			),
			array(
				'id'           => 'testimonial_date_typography',
				'type'         => 'typography',
				'title'        => __( 'Date Font', 'testimonial-free' ),
				'subtitle'     => __( 'Set date font properties.', 'testimonial-free' ),
				'class'        => 'sp-testimonial-font-color',
				'default'      => array(
					'font-family'    => 'Open Sans',
					'font-weight'    => 'normal',
					'type'           => 'google',
					'font-size'      => '15',
					'line-height'    => '20',
					'text-align'     => 'center',
					'text-transform' => 'none',
					'letter-spacing' => 0,
					'color'          => '#444444',
				),
				'color'        => true,
				'preview'      => true,
				'preview_text' => 'February 21, 2018', // Replace preview text with any text you like.
			),
			array(
				'id'       => 'website_font_load',
				'type'     => 'switcher',
				'title'    => __( 'Load Website Font', 'testimonial-free' ),
				'subtitle' => __( 'On/Off google font for the website.', 'testimonial-free' ),
				'class'    => 'sp-testimonial-font-load',
				'default'  => true,
			),
			array(
				'id'           => 'client_website_typography',
				'type'         => 'typography',
				'title'        => __( 'Website Font', 'testimonial-free' ),
				'subtitle'     => __( 'Set website font properties.', 'testimonial-free' ),
				'class'        => 'sp-testimonial-font-color',
				'default'      => array(
					'font-family'    => 'Open Sans',
					'font-weight'    => 'normal',
					'type'           => 'google',
					'font-size'      => '15',
					'line-height'    => '20',
					'text-align'     => 'center',
					'text-transform' => 'none',
					'letter-spacing' => 0,
					'color'          => '#444444',
				),
				'color'        => true,
				'preview'      => true,
				'preview_text' => 'www.yourwebsite.com', // Replace preview text with any text you like.
			),
			array(
				'id'       => 'filter_font_load',
				'type'     => 'switcher',
				'title'    => __( 'Load Filter Font', 'testimonial-free' ),
				'subtitle' => __( 'On/Off google font for the filter.', 'testimonial-free' ),
				'class'    => 'sp-testimonial-font-load',
				'default'  => true,
			),
			array(
				'id'           => 'filter_typography',
				'type'         => 'typography',
				'title'        => __( 'Filter Font', 'testimonial-free' ),
				'subtitle'     => __( 'Set filter font properties.', 'testimonial-free' ),
				'default'      => array(
					'font-family'    => 'Open Sans',
					'font-weight'    => 'normal',
					'type'           => 'google',
					'font-size'      => '15',
					'line-height'    => '20',
					'text-align'     => 'center',
					'text-transform' => 'none',
					'letter-spacing' => 0,
				),
				'color'        => false,
				'preview'      => true,
				'preview_text' => 'All', // Replace preview text with any text you like.
			),

		),
	)
);

//
// Upgrade to Pro section.
//
CSF::createSection(
	$prefix_shortcode_opts, array(
		'title'  => __( 'Upgrade to Pro', 'testimonial-free' ),
		'icon'   => 'fa fa-rocket',
		'fields' => array(
			array(
				'type'    => 'upgrade',
				'content' => 'upgrade',
			),

		),
	)
);

//
// Metabox of the Testimonial.
// Set a unique slug-like ID.
//
$prefix_testimonial_opts = 'sp_tpro_meta_options';

//
// Testimonial metabox.
//
CSF::createMetabox(
	$prefix_testimonial_opts, array(
		'title'     => __( 'Testimonial Options', 'testimonial-free' ),
		'class'     => 'spt-main-class',
		'post_type' => 'spt_testimonial',
		'context'   => 'normal',
	)
);

//
// Reviewer Information section.
//
CSF::createSection(
	$prefix_testimonial_opts, array(
		'title'  => __( 'Reviewer Information', 'testimonial-free' ),
		'fields' => array(

			array(
				'id'       => 'tpro_name',
				'type'     => 'text',
				'title'    => __( 'Name', 'testimonial-free' ),
				'subtitle' => __( 'Type reviewer name here.', 'testimonial-free' ),
				'sanitize' => 'spftestimonial_sanitize_text',
			),
			array(
				'id'       => 'tpro_designation',
				'type'     => 'text',
				'title'    => __( 'Identity or Position', 'testimonial-free' ),
				'subtitle' => __( 'Type reviewer identity or position here.', 'testimonial-free' ),
				'sanitize' => 'spftestimonial_sanitize_text',
			),
			array(
				'id'       => 'tpro_rating',
				'type'     => 'rating',
				'title'    => __( 'Rating Star', 'testimonial-free' ),
				'subtitle' => __( 'Rating star along with testimonial.', 'testimonial-free' ),
				'options'  => array(
					'five_star'  => __( '5 Stars', 'testimonial-free' ),
					'four_star'  => __( '4 Stars', 'testimonial-free' ),
					'three_star' => __( '3 Stars', 'testimonial-free' ),
					'two_star'   => __( '2 Stars', 'testimonial-free' ),
					'one_star'   => __( '1 Star', 'testimonial-free' ),
				),
				'default'  => '',
				'sanitize' => 'spftestimonial_sanitize_text',
			),

		),
	)
);

//
// Social Profiles section.
//
CSF::createSection(
	$prefix_testimonial_opts, array(
		'title'  => __( 'Social Profiles', 'testimonial-free' ),
		'fields' => array(
			array(
				'type'    => 'notice',
				'style'   => 'normal',
				'content' => __( 'The Social Profile options are available in the <a href="https://shapedplugin.com/plugin/testimonial-pro" target="_blank">Pro Version</a> only.', 'testimonial-free' ),
			),
			array(
				'id'       => 'tpro_social_facebook_url',
				'type'     => 'text_f',
				'title'    => __( 'Facebook', 'testimonial-free' ),
				'subtitle' => __( 'Type facebook URL here.', 'testimonial-free' ),
			),
			array(
				'id'       => 'tpro_social_twitter_url',
				'type'     => 'text_f',
				'title'    => __( 'Twitter', 'testimonial-free' ),
				'subtitle' => __( 'Type twitter URL here.', 'testimonial-free' ),
			),
			array(
				'id'       => 'tpro_social_linked_in_url',
				'type'     => 'text_f',
				'title'    => __( 'LinkedIn', 'testimonial-free' ),
				'subtitle' => __( 'Type linkedin URL here.', 'testimonial-free' ),
			),
			array(
				'id'       => 'tpro_social_instagram_url',
				'type'     => 'text_f',
				'title'    => __( 'Instagram', 'testimonial-free' ),
				'subtitle' => __( 'Type Instagram URL here.', 'testimonial-free' ),
			),
			array(
				'id'       => 'tpro_social_youtube_url',
				'type'     => 'text_f',
				'title'    => __( 'YouTube', 'testimonial-free' ),
				'subtitle' => __( 'Type youtube URL here.', 'testimonial-free' ),
			),
			array(
				'id'       => 'tpro_social_pinterest_url',
				'type'     => 'text_f',
				'title'    => __( 'Pinterest', 'testimonial-free' ),
				'subtitle' => __( 'Type pinterest URL here.', 'testimonial-free' ),
			),
			array(
				'id'       => 'tpro_social_skype_url',
				'type'     => 'text_f',
				'title'    => __( 'Skype', 'testimonial-free' ),
				'subtitle' => __( 'Type skype URL here.', 'testimonial-free' ),
			),
			array(
				'id'       => 'tpro_social_stumble_upon_url',
				'type'     => 'text_f',
				'title'    => __( 'StumbleUpon', 'testimonial-free' ),
				'subtitle' => __( 'Type stumbleupon URL here.', 'testimonial-free' ),
			),
			array(
				'id'       => 'tpro_social_reddit_url',
				'type'     => 'text_f',
				'title'    => __( 'Reddit', 'testimonial-free' ),
				'subtitle' => __( 'Type reddit URL here.', 'testimonial-free' ),
			),
			array(
				'id'       => 'tpro_social_dribbble_url',
				'type'     => 'text_f',
				'title'    => __( 'Dribbble', 'testimonial-free' ),
				'subtitle' => __( 'Type dribbble URL here.', 'testimonial-free' ),
			),
			array(
				'id'       => 'tpro_social_snapchat_url',
				'type'     => 'text_f',
				'title'    => __( 'SnapChat', 'testimonial-free' ),
				'subtitle' => __( 'Type snapchat URL here.', 'testimonial-free' ),
			),

		),
	)
);
