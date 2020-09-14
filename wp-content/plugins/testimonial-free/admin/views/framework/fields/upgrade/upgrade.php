<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.
/**
 *
 * Field: Upgrade
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'CSF_Field_upgrade' ) ) {
	class CSF_Field_upgrade extends CSF_Fields {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {
			?>
			<div class="sp-tfree-mbf-tab-content">
				<div class="sp-tfree-upgrade-header">
					<div class="tfree-overlay-color"></div>
					<div class="sp-tfree-container">
						<div class="sp-tfree-col-lg-7">
							<h1 class="sp-tfree-upgrade-title">Collect & Showcase Your Testimonials Aesthetically and Increase Conversions!</h1>
							<h4 class="sp-tfree-upgrade-subtitle">Upgrade to <b>PRO</b> Version of <b>Testimonial</b> to benefit
								from all features!</h4>
						</div>
						<div class="sp-tfree-col-lg-5">
							<a href="https://shapedplugin.com/plugin/testimonial-pro"
							class="button button-large free-upgrade-btn upgrade-btn" target="_blank">Upgrade to PRO!</a><a
								href="https://shapedplugin.com/demo/testimonial-pro"
								class="button button-large free-upgrade-btn demo-btn" target="_blank">All Features & Demo</a>
						</div>
					</div>
				</div>
				<div class="sp-tfree-upgrade-body">
					<div class="sp-tfree-section-feature-icon text-center"><img
							src="<?php echo SP_TFREE_URL . 'admin/assets/images/pro-feature.png'; ?>" alt=""></div>
					<h2 class="sp-tfree-section-title text-center">PREMIUM FEATURES</h2>
					<div class="tfree-option">
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content">
								<img src="<?php echo SP_TFREE_URL . 'admin/assets/images/upgrade-ss1.png'; ?>" alt="">
							</div>

						</div>
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content">
								<h2>5+ Testimonial Layouts</h2>
								<h4><i>(Slider, Grid, Masonry, List, & Filter-Grid & Filter-Masonry)</i></h4>
								<p>You can select from 6 beautiful testimonial layouts: Slider, Grid, Masonry, List, Filter-Grid & Filter-Masonry. Creating a customized layout is super easy. You can change the number of layout columns, reviewer info to show, font, & color etc.</p>
								<p>Showcase your testimonials how you want and use multi-creative ideas as you need.</p>
							</div>

						</div>
					</div>
					<div class="tfree-option">
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content">
								<h2>10+ Professional Themes</h2>
								<p>Get designer quality results without writing a single line of code through 10+ professionally pre-designed themes for front-end display. Each theme has a different structure and huge customization options to cover all the demands.</p>
							</div>

						</div>
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content"><img src="
							<?php
							echo SP_TFREE_URL . 'admin/assets/images/upgrade-ss2.png'
							?>
								" alt=""></div>
						</div>
					</div>
					<div class="tfree-option">

						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content"><img src="
							<?php
							echo SP_TFREE_URL . 'admin/assets/images/upgrade-ss3.png'
							?>
								" alt=""></div>
						</div>
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content">
								<h2>840+ GOOGLE FONTS</h2>
								<h4><i>(Font family, size, transform, letter spacing, line-height, & color)</i></h4>
								<p>Testimonial Pro includes over 840+ Google fonts. You can add your desired font from 840+ Google Fonts. Customize the font family, size, transform, letter spacing, color, and line-height for every element.</p>

								<p>Manage any content with Google Fonts with live preview. That’s robust features of Testimonial Pro that can never be missed by creative people to make site beautiful and unique feel.</p>
							</div>

						</div>
					</div>
					<div class="tfree-option">

						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content">
								<h2>Shortcode Generator With 100+ Visual Customisation Options</h2>
								<p>It could be easier to generate the shortcode to display the testimonials. Just go to the Shortcode Generator, choose the settings you want and generated shortcode is ready to use where you want like posts, pages, and widgets. Generated Shortcodes are to be used in posts, pages and widgets, while the php function will be in your theme files.</p>
							</div>
						</div>
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content"><img src="
							<?php
							echo SP_TFREE_URL . 'admin/assets/images/upgrade-ss4.png'
							?>" alt=""></div>
						</div>
					</div>

					<div class="tfree-option">
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content"><img src="<?php echo SP_TFREE_URL . 'admin/assets/images/upgrade-ss5.png'; ?>" alt=""></div>

						</div>
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content">
								<h2>14 Display Options</h2>
								<p>Pick individual fields for each Testimonial's information. You can toggle between Testimonial Image, Video, title, Content, Name, Rating star, identity, Company, Location, Mobile, E-mail, Date, Website, And Social profile links.</p>
							</div>
						</div>

					</div>
					<div class="tfree-option">

						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content">
								<h2>TAILOR-MADE RESPONSIVITY</h2>
								<h4><i>(Control on Standard Desktop, Tablet & Mobile)</i></h4>
								<p>All Testimonial layouts are fully responsive & mobile touch-friendly and used intuitive breakpoints settings that you can customize the number of testimonials displayed on a Desktop, Laptop, iPad, iPhone and Android Devices.</p>
								<p>Total control on your slider at any resolution! With the premium version of the plugin, you can set the number of testimonial columns to show simultaneously in the slider, grid, masonry and isotope filter according to the specific device resolution.</p>
								<p>Three sizes are generally considered: Standard Desktop (desktop for the screen smaller than 1100px.), tablet (tablet for the screen smaller than 990px.) and mobile ( mobile for the screen smaller than 650px.).</p>
							</div>
						</div>
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content"><img src="
							<?php
							echo SP_TFREE_URL . 'admin/assets/images/upgrade-ss6.png'
							?>
								"
															alt=""></div>
						</div>
					</div>
					<div class="tfree-option">
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content"><img src="<?php echo SP_TFREE_URL . 'admin/assets/images/upgrade-ss7.png'; ?>"
															alt=""></div>

						</div>
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content">
								<h2>Drag & Drop Re-Ordering!</h2>
								<p>One of the most amazing features of Testimonial Pro is the ability to drag & drop re-order testimonials. You can re-order your testimonials simply by drag & drop, or choose to display the testimonials randomly. </p>

							</div>

						</div>

					</div>
					<div class="tfree-option">

						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content">
								<h2>Showcase by Specific Category</h2>
								<p>Do you want to show a specific testimonial category to your potential customers? You can show testimonials from categories. Save your time by allowing automatical showcasing of available testimonials from the category.</p>
							</div>

						</div>
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content"><img src="
							<?php
							echo SP_TFREE_URL . 'admin/assets/images/upgrade-ss8.png'
							?>
								" alt=""></div>
						</div>
					</div>
					<div class="tfree-option">
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content"><img src="
							<?php
							echo SP_TFREE_URL . 'admin/assets/images/upgrade-ss9.png'
							?>
								" alt=""></div>
						</div>
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content">
								<h2>Display Specific Testimonials</h2>
								<h4><i>(If you can leverage it, you can earn from it.)</i></h4>
								<p>You can display the specific testimonials from available testimonials in the list. Highlight your specific testimonials in strategic positions, it will allow you to convert visitors into your valuable customers.</p>
							</div>
						</div>
					</div>
					<div class="tfree-option">
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content">
								<h2>Front-end Submission Form</h2>
								<p>You can create Front-end Submission Form for customers to collect new testimonials for your business. When you receive a new testimonial, simply review and approve it to automatically add it to your customer testimonials page!</p>
							</div>
						</div>
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content"><img src="
							<?php
							echo SP_TFREE_URL . 'admin/assets/images/upgrade-ss10.png'
							?>
								" alt=""></div>
						</div>

					</div>
					<div class="tfree-option">
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content"><img src="
							<?php
							echo SP_TFREE_URL . 'admin/assets/images/upgrade-ss11.png'
							?>
								" alt=""></div>
						</div>
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content">
								<h2>Front-end Form Fields Control</h2>
								<p>You can choose which fields and the messages to display! You can sort your own order and control show/hide, required, label and placeholder attribute for all fields in Testimonial Submission Form.  It’s that simple.
								</p>
							</div>
						</div>
					</div>

					<div class="tfree-option">
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content">
								<h2>Thumbnail Slider</h2>
								<p>One of the most stunning features of Testimonial Pro is the ability to create Thumbnail Slider. If you enable thumbnail slider, you can display testimonials using the Thumbnail Slider. It's modern and looks pretty.</p>
							</div>
						</div>
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content"><img src="
							<?php
							echo SP_TFREE_URL . 'admin/assets/images/upgrade-ss12.png'
							?>
								" alt=""></div>
						</div>
					</div>
					<div class="tfree-option">
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content"><img src="
							<?php
							echo SP_TFREE_URL . 'admin/assets/images/upgrade-ss13.png'
							?>
								" alt=""></div>
						</div>
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content">
								<h2>Video Testimonial & Lightbox</h2>
								<p>Video Testimonials are more effective to increase sales of a business. You can create video testimonial with Lightbox instead of simple image testimonial with Testimonial Pro. You can use video from YouTube, Vimeo or any video link. </p>
							</div>
						</div>
					</div>
					<div class="tfree-option">
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content">
								<h2>Read More & Characters Limit Options</h2>
								<p>You can choose testimonial content display type, show full testimonial body or content with characters limit. You can set custom ellipsis after content, customize the Read More button text, color, and hover color.</p>
							</div>
						</div>
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content"><img src="
							<?php
							echo SP_TFREE_URL . 'admin/assets/images/upgrade-ss14.png'
							?>
								" alt=""></div>
						</div>
					</div>

					<div class="tfree-option">
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content"><img src="
							<?php
							echo SP_TFREE_URL . 'admin/assets/images/upgrade-ss15.png'
							?>
								" alt=""></div>
						</div>
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content">
								<h2>Read More Action Type</h2>
								<h4><i>(Show Testimonial in a Expand or Popup)</i></h4>
								<p>You can choose Read More button action type to show testimonial in a expand or popup page. In Expand, the testimonial content will collapse and expand long blocks of text. In PopUp, All Testimonial content will show like lightbox.</p>

							</div>

						</div>
					</div>

					<div class="tfree-option">
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content">
								<h2>Advanced Link Options</h2>
								<p>Testimonial Pro has several options for your links. You can link the identity or position of the testimonial through website URL that will lead to a page or company website URL, perfect for case studies!</p>
							</div>
						</div>
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content"><img src="
							<?php
							echo SP_TFREE_URL . 'admin/assets/images/upgrade-ss16.png'
							?>
								"
															alt=""></div>
						</div>
					</div>

					<div class="tfree-option">
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content"><img src="
							<?php
							echo SP_TFREE_URL . 'admin/assets/images/upgrade-ss17.png'
							?>
								"
															alt=""></div>
						</div>
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content">
								<h2>Rich Snippets/Structured Data compatible</h2>
								<p>The plugin is Rich Snippets compatible. When used properly this information might display in the search engine result pages! Testimonial Pro uses schema.org compliant JSON-LD markup to appear correctly in search results.</p>
							</div>
						</div>
					</div>
					
					<div class="tfree-option">
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content">
								<h2>Slider Control Options</h2>
								<p>You can set how many testimonials to scroll at a time in the carousel or show, navigation & pagination show/hide, autoplay, speed, animation, loop, pause on hover, draggable, swipe, ticker mode, and many other settings.</p>
							</div>
						</div>
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content"><img src="
							<?php
							echo SP_TFREE_URL . 'admin/assets/images/upgrade-ss18.png'
							?>
								"
															alt=""></div>
						</div>
					</div>

					<div class="tfree-option">
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content"><img src="
							<?php
							echo SP_TFREE_URL . 'admin/assets/images/upgrade-ss19.png'
							?>
								"
															alt=""></div>
						</div>
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content">
								<h2>Navigation Positions & Styles </h2>
								<h4><i>(13 Navigation, 16 Pagination, 6 Arrow Styles & 8 Positions)</i></h4>
								<p>You can select your desired arrow style to fit your needs from many styles. This plugin has 13 Navigation, 16 Pagination, 6 Arrow Styles and 8+ different navigational arrow positions (Top right, top center, top left, bottom left, bottom center, bottom right, vertically center, vertically inner center, vertically inner center on hover etc.).</p>
								<p>You can set your desired style, position and color your own way. Also customize pagination dots color and active color as well as arrow color, border color, background color and much more!</p>
							</div>
						</div>
					</div>
					<div class="tfree-option">
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content">
								<h2>Custom Image Re-sizing</h2>
								<p>You can control the image size to your specific size. You can change the default size of your testimonial images on the settings. The newly uploaded image will be resized or cropped to the specified dimensions.</p>
							</div>
						</div>
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content"><img src="
							<?php
							echo SP_TFREE_URL . 'admin/assets/images/upgrade-ss20.png'
							?>
								"
															alt=""></div>
						</div>
					</div>

					<div class="tfree-option">
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content"><img src="
							<?php
							echo SP_TFREE_URL . 'admin/assets/images/upgrade-ss21.png'
							?>
								"
															alt=""></div>
						</div>
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content">
								<h2>WPBakery(formerly Visual Composer) & Widget Ready</h2>
								<p>The premium plugin includes a Widget to display the layouts. Just create a layout in the Shortcode Generator page, save it to use in the widget! A Testimonials module available to add to your page via the V.C interface.</p>
							</div>
						</div>
					</div>
					<div class="tfree-option">
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content">
								<h2>Translation Ready (WPML)</h2>
								<p>Testimonial Pro is fully Translation ready, supporting .po and .mo language files! You can translate into your language with the following popular plugins like WPML, Polylang, qTranslate-x, GTranslate, Google Language Translator, WPGlobus – Multilingual Everything! </p>
								<p>If you want the administration of the plugin in another language, you just need to create your own language files.</p>
							</div>
						</div>
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content"><img src="
							<?php
							echo SP_TFREE_URL . 'admin/assets/images/upgrade-ss22.png'
							?>
								"
															alt=""></div>
						</div>
					</div>
					<div class="tfree-option">
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content"><img src="
							<?php
							echo SP_TFREE_URL . 'admin/assets/images/upgrade-ss23.png'
							?>
								"
															alt=""></div>
						</div>
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content">
								<h2>Duplicate or Clone Options</h2>
								<p>A built-in duplicate or clone option for sliders or showcase is included with Testimonial Pro. You can duplicate or clone testimonial slider or showcase and copy them to new drafts for further editing. It's nice!</p>
							</div>
						</div>
					</div>
					<div class="tfree-option">
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content">
								<h2>Built-in Automatic Updates Notifications</h2>
								<p>You'll get Automatic Updates when you activate the license key in your site. Once you buy the Testimonial Pro, you will get regular update notification to the dashboard. You can see the change logs before update.</p>
							</div>
						</div>
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content"><img src="
							<?php
							echo SP_TFREE_URL . 'admin/assets/images/upgrade-ss24.png'
							?>
							"
															alt=""></div>
						</div>
					</div>
					<div class="tfree-option">
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content"><img src="
							<?php
							echo SP_TFREE_URL . 'admin/assets/images/upgrade-ss25.png'
							?>
								"
															alt=""></div>
						</div>
						<div class="sp-tfree-col-lg-2">
							<div class="sp-tfree-content">
								<h2>Fast & Friendly Support (24x7)</h2>
								<p>We love our valued customers! We always strive to provide 5-star, timely, and comprehensive support whenever you need a helping hand. We've a full time dedidcated support team who are always ready to make you happy!</p>
							</div>
						</div>
					</div>


					<div class="sp-tfree-video-tutorial text-center">
						<h2>Watch the Video</h2>
						<div class="sp-tfree-video-tutorial-video">
							<iframe width="560" height="300" src="https://www.youtube.com/embed/OA7LgaZHwIY?list=PLoUb-7uG-5jM2sjscSqBVj07VXOqt0qHZ" frameborder="0"
									allowfullscreen></iframe>
						</div>
					</div>

				</div>
				<div class="sp-tfree-upgrade-footer text-center">
					<div class="tfree-overlay-color"></div>
					<div class="sp-tfree-client-thumb"><img src="
					<?php
					echo SP_TFREE_URL . 'admin/assets/images/client-thumb.png'
					?>
						"
														alt=""></div>
					<h2>Join Our Happy Customers</h2>
					<p>Unlock all <b>Testimonial Pro</b> features and build an eye-caching <b>Testimonials Showcase</b> for your website today.</p>
					<a href="https://shapedplugin.com/plugin/testimonial-pro"
					class="button button-large free-upgrade-btn upgrade-btn" target="_blank">Upgrade Now for $39 only!</a><a
						href="https://shapedplugin.com/demo/testimonial-pro"
						class="button button-large free-upgrade-btn demo-btn" target="_blank">All Features & Demo</a>
				</div>
			</div>
			<?php

		}

	}
}
