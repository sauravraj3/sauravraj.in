<?php

    echo'<div class="mo_idp_divided_layout mo-idp-full">';
            is_customer_registered_idp($registered);
	echo'   <form style="display:none;" id="mo_idp_request_quote_form" action="admin.php?page=idp_support" method="post">
                <input type="text" name="plan_name" id="plan-name" value="" />
                <input type="text" name="plan_users" id="plan-users" value="" />
            </form>
            
            <form style="display:none;" id="mocf_loginform" action="'.$login_url.'" target="_blank" method="post">
				<input type="email" name="username" value="'.$username.'" />
				<input type="text" name="redirectUrl" value="'.$payment_url.'" />
				<input type="text" name="requestOrigin" id="requestOrigin"  />
			</form>
            
            <div class="mo_idp_pricing_layout mo-idp-center">
                <h2>LICENSING PLANS
                    <span style="float:right">
                        <input  type="button" 
                                name="ok_btn" 
                                id="ok_btn" 
                                class="button button-primary button-large" 
                                value="OK, Got It" 
                                onclick="window.location.href=\''.$okgotit_url.'\'" />
                    </span>
                </h2>
                <hr>  
                <br>
                    <table class="mo_idp_license_plan mo_idp_license_table">
                        <tr>
                            <td class="license_plan_points" style="border-radius:12px 12px 0 0; width: 13%;"><b>Licensing Plan Name</b></td>
                            <td colspan=2 class="license_plan_title" style="width: 25%;"><span class="license_plan_name">LITE PLAN</span><br><p style="font-size:20px;">(Users hosted in miniOrange Cloud)</p></td>
                            <td class="license_plan_title" style="width: 25%;"><span class="license_plan_name">PREMIUM PLAN</span><br><p style="font-size:20px;">(Users stored in your own WordPress Database)</p></td>
                            <td class="license_plan_title"><span class="license_plan_name">ALL-INCLUSIVE PLAN</span><br><p style="font-size:20px;">(Users hosted in miniOrange or Enterprise Directory like Azure AD, Active Directory, LDAP, Office365, Google Apps or any 3rd party providers using SAML, OAuth, Database, APIs etc)</p></td>
                        </tr>
                        <tr style="background-color:#95d5ba;">
                            <td class="license_plan_points" rowspan=2><b>User Slabs / Pricing</b></td>
                            <td><b>Monthly Pricing</b></td>
                            <td><b>Yearly Pricing</b></td>
                            <td style="padding: 20px; line-height: 1.8;">
                                <b>Yearly Pricing <span class="dashicons dashicons-info mo-info-icon"><span class="mo-info-text">Number of users indicate any user that authenticated during a given <b><u>month</u></b></span></span>
                                <br><span style="color: red;">(50% from 2nd year onwards)</span></b>
                            </td>
                            <td><b>Monthly / Yearly Pricing</b></td>
                        </tr>
                        <tr>
                            <td class="mo_license_upgrade_button"><a onclick="mo2f_upgradeform(\'mo_idp_lite_monthly_plan\')" style="display: block; width: 100%; text-decoration: none; color:white;"><b style="font-weight:700; letter-spacing:2px;">UPGRADE NOW</b></a></td>
                            <td class="mo_license_upgrade_button"><a onclick="mo2f_upgradeform(\'mo_idp_lite_yearly_plan\')" style="display: block; width: 100%; text-decoration: none; color:white;"><b style="font-weight:700; letter-spacing:2px;">UPGRADE NOW</b></a></td>
                            <td class="mo_license_upgrade_button"><a onclick="mo2f_upgradeform(\'wp_saml_idp_premium_plan\')" style="display: block; width: 100%; text-decoration: none; color:white;"><b style="font-weight:700; letter-spacing:2px;">UPGRADE NOW</b></a></td>
                            <td class="mo_license_upgrade_button"><a onclick="gatherplaninfo(\'all_inclusive\',\'5K\')" style="display: block; width: 100%; text-decoration: none; color:white;"><b style="font-weight:700; letter-spacing:2px;">REQUEST A QUOTE</b></a></td>
                        </tr>
                        <tr>
                            <td class="license_plan_points">
                                <select id="mo_idp_users_dd" style="text-align: center; font-size:20px; color: #0071a1; border-color: #0071a1;">
                                    <option value="100" selected>1 - 100</option>
                                    <option value="200">101 - 200</option>
                                    <option value="300">201 - 300</option>
                                    <option value="400">301 - 400</option>
                                    <option value="500">401 - 500</option>
                                    <option value="1000">501 - 1000</option>
                                    <option value="2000">1001 - 2000</option>
                                    <option value="3000">2001 - 3000</option>
                                    <option value="4000">3001 - 4000</option>
                                    <option value="5000">4001 - 5000</option>
                                    <option value="5000+">5000+</option>
                                    <option value="UL">Unlimited</option>
                            </td>
                            <td class="mo_idp_price_row mo_idp_price_slab_100" style="display: table-cell;"><b>$</b>15<span style="font-size: 15px;"> / month</span></td>
                            <td class="mo_idp_price_row mo_idp_price_slab_100" style="display: table-cell;"><b>$</b>165<span style="font-size: 15px;"> / year</span></td>
                            <td class="mo_idp_price_row mo_idp_price_slab_100" style="display: table-cell;"><b>$</b>450<span style="font-size: 15px;"> for the 1<sup>st</sup> year<br><b style="font-size: 18px;">$225</b> from 2<sup>nd</sup> year onwards</span></td>
                            <td class="mo_idp_all_inc_cell mo_idp_price_slab_100" style="display: table-cell;">Starts from <b>$</b>0.5/user/month<br><u style="color:orange; font-size: 21px;"><a onclick="gatherplaninfo(\'all_inclusive\',\'5K\')" style="color:orange;"><b>Request a Quote</b></a></u></td>
                        
                            <td class="mo_idp_price_row mo_idp_price_slab_200" style="display: none;"><b>$</b>16<span style="font-size: 15px;"> / month</span></td>
                            <td class="mo_idp_price_row mo_idp_price_slab_200" style="display: none;"><b>$</b>176<span style="font-size: 15px;"> / year</span></td>
                            <td class="mo_idp_price_row mo_idp_price_slab_200" style="display: none;"><b>$</b>550<span style="font-size: 15px;"> for the 1<sup>st</sup> year<br><b style="font-size: 18px;">$275</b> from 2<sup>nd</sup> year onwards</span></td>
                            <td class="mo_idp_all_inc_cell mo_idp_price_slab_200" style="display: none;">Starts from <b>$</b>0.5/user/month<br><u style="color:orange; font-size: 21px;"><a onclick="gatherplaninfo(\'all_inclusive\',\'5K\')" style="color:orange;"><b>Request a Quote</b></a></u></td>

                            <td class="mo_idp_price_row mo_idp_price_slab_300" style="display: none;"><b>$</b>17<span style="font-size: 15px;"> / month</span></td>
                            <td class="mo_idp_price_row mo_idp_price_slab_300" style="display: none;"><b>$</b>187<span style="font-size: 15px;"> / year</span></td>
                            <td class="mo_idp_price_row mo_idp_price_slab_300" style="display: none;"><b>$</b>650<span style="font-size: 15px;"> for the 1<sup>st</sup> year<br><b style="font-size: 18px;">$325</b> from 2<sup>nd</sup> year onwards</span></td>
                            <td class="mo_idp_all_inc_cell mo_idp_price_slab_300" style="display: none;">Starts from <b>$</b>0.5/user/month<br><u style="color:orange; font-size: 21px;"><a onclick="gatherplaninfo(\'all_inclusive\',\'5K\')" style="color:orange;"><b>Request a Quote</b></a></u></td>

                            <td class="mo_idp_price_row mo_idp_price_slab_400" style="display: none;"><b>$</b>18<span style="font-size: 15px;"> / month</span></td>
                            <td class="mo_idp_price_row mo_idp_price_slab_400" style="display: none;"><b>$</b>198<span style="font-size: 15px;"> / year</span></td>
                            <td class="mo_idp_price_row mo_idp_price_slab_400" style="display: none;"><b>$</b>750<span style="font-size: 15px;"> for the 1<sup>st</sup> year<br><b style="font-size: 18px;">$375</b> from 2<sup>nd</sup> year onwards</span></td>
                            <td class="mo_idp_all_inc_cell mo_idp_price_slab_400" style="display: none;">Starts from <b>$</b>0.5/user/month<br><u style="color:orange; font-size: 21px;"><a onclick="gatherplaninfo(\'all_inclusive\',\'5K\')" style="color:orange;"><b>Request a Quote</b></a></u></td>

                            <td class="mo_idp_price_row mo_idp_price_slab_500" style="display: none;"><b>$</b>19<span style="font-size: 15px;"> / month</span></td>
                            <td class="mo_idp_price_row mo_idp_price_slab_500" style="display: none;"><b>$</b>209<span style="font-size: 15px;"> / year</span></td>
                            <td class="mo_idp_price_row mo_idp_price_slab_500" style="display: none;"><b>$</b>850<span style="font-size: 15px;"> for the 1<sup>st</sup> year<br><b style="font-size: 18px;">$425</b> from 2<sup>nd</sup> year onwards</span></td>
                            <td class="mo_idp_all_inc_cell mo_idp_price_slab_500" style="display: none;">Starts from <b>$</b>0.5/user/month<br><u style="color:orange; font-size: 21px;"><a onclick="gatherplaninfo(\'all_inclusive\',\'5K\')" style="color:orange;"><b>Request a Quote</b></a></u></td>

                            <td class="mo_idp_price_row mo_idp_price_slab_1000" style="display: none;"><b>$</b>22<span style="font-size: 15px;"> / month</span></td>
                            <td class="mo_idp_price_row mo_idp_price_slab_1000" style="display: none;"><b>$</b>242<span style="font-size: 15px;"> / year</span></td>
                            <td class="mo_idp_price_row mo_idp_price_slab_1000" style="display: none;"><b>$</b>1250<span style="font-size: 15px;"> for the 1<sup>st</sup> year<br><b style="font-size: 18px;">$625</b> from 2<sup>nd</sup> year onwards</span></td>
                            <td class="mo_idp_all_inc_cell mo_idp_price_slab_1000" style="display: none;">Starts from <b>$</b>0.5/user/month<br><u style="color:orange; font-size: 21px;"><a onclick="gatherplaninfo(\'all_inclusive\',\'5K\')" style="color:orange;"><b>Request a Quote</b></a></u></td>

                            <td class="mo_idp_price_row mo_idp_price_slab_2000" style="display: none;"><b>$</b>44<span style="font-size: 15px;"> / month</span></td>
                            <td class="mo_idp_price_row mo_idp_price_slab_2000" style="display: none;"><b>$</b>484<span style="font-size: 15px;"> / year</span></td>
                            <td class="mo_idp_price_row mo_idp_price_slab_2000" style="display: none;"><b>$</b>1600<span style="font-size: 15px;"> for the 1<sup>st</sup> year<br><b style="font-size: 18px;">$800</b> from 2<sup>nd</sup> year onwards</span></td>
                            <td class="mo_idp_all_inc_cell mo_idp_price_slab_2000" style="display: none;">Starts from <b>$</b>0.5/user/month<br><u style="color:orange; font-size: 21px;"><a onclick="gatherplaninfo(\'all_inclusive\',\'5K\')" style="color:orange;"><b>Request a Quote</b></a></u></td>

                            <td class="mo_idp_price_row mo_idp_price_slab_3000" style="display: none;"><b>$</b>66<span style="font-size: 15px;"> / month</span></td>
                            <td class="mo_idp_price_row mo_idp_price_slab_3000" style="display: none;"><b>$</b>726<span style="font-size: 15px;"> / year</span></td>
                            <td class="mo_idp_price_row mo_idp_price_slab_3000" style="display: none;"><b>$</b>1900<span style="font-size: 15px;"> for the 1<sup>st</sup> year<br><b style="font-size: 18px;">$950</b> from 2<sup>nd</sup> year onwards</span></td>
                            <td class="mo_idp_all_inc_cell mo_idp_price_slab_3000" style="display: none;">Starts from <b>$</b>0.5/user/month<br><u style="color:orange; font-size: 21px;"><a onclick="gatherplaninfo(\'all_inclusive\',\'5K\')" style="color:orange;"><b>Request a Quote</b></a></u></td>

                            <td class="mo_idp_price_row mo_idp_price_slab_4000" style="display: none;"><b>$</b>88<span style="font-size: 15px;"> / month</span></td>
                            <td class="mo_idp_price_row mo_idp_price_slab_4000" style="display: none;"><b>$</b>968<span style="font-size: 15px;"> / year</span></td>
                            <td class="mo_idp_price_row mo_idp_price_slab_4000" style="display: none;"><b>$</b>2150<span style="font-size: 15px;"> for the 1<sup>st</sup> year<br><b style="font-size: 18px;">$1075</b> from 2<sup>nd</sup> year onwards</span></td>
                            <td class="mo_idp_all_inc_cell mo_idp_price_slab_4000" style="display: none;">Starts from <b>$</b>0.5/user/month<br><u style="color:orange; font-size: 21px;"><a onclick="gatherplaninfo(\'all_inclusive\',\'5K\')" style="color:orange;"><b>Request a Quote</b></a></u></td>

                            <td class="mo_idp_price_row mo_idp_price_slab_5000" style="display: none;"><b>$</b>110<span style="font-size: 15px;"> / month</span></td>
                            <td class="mo_idp_price_row mo_idp_price_slab_5000" style="display: none;"><b>$</b>1155<span style="font-size: 15px;"> / year</span></td>
                            <td class="mo_idp_price_row mo_idp_price_slab_5000" style="display: none;"><b>$</b>2400<span style="font-size: 15px;"> for the 1<sup>st</sup> year<br><b style="font-size: 18px;">$1200</b> from 2<sup>nd</sup> year onwards</span></td>
                            <td class="mo_idp_all_inc_cell mo_idp_price_slab_5000" style="display: none;"><span style="">Starts from <b>$</b>0.5/user/month<br><u style="color:orange; font-size: 21px;"><a onclick="gatherplaninfo(\'all_inclusive\',\'5K\')" style="color:orange;"><b>Request a Quote</b></a></u></td>

                            <td class="mo_idp_price_row mo_idp_price_slab_5000p" style="display: none;"><u style="color:orange; font-size: 21px;"><a id="lite_monthly_5K" onclick="gatherplaninfo(\'lite_monthly\',\'5K\')" style="color:orange;"><b>Request a Quote</b></a></u></td>
                            <td class="mo_idp_price_row mo_idp_price_slab_5000p" style="display: none;"><u style="color:orange; font-size: 21px;"><a id="lite_yearly_5K" onclick="gatherplaninfo(\'lite_yearly\',\'5K\')" style="color:orange;"><b>Request a Quote</b></a></u></td>
                            <td class="mo_idp_price_row mo_idp_price_slab_5000p" style="display: none;"><u style="color:orange; font-size: 21px;"><a id="wp_yearly_5K" onclick="gatherplaninfo(\'wp_yearly\',\'5K\')" style="color:orange;"><b>Request a Quote</b></a></u></td>
                            <td class="mo_idp_all_inc_cell mo_idp_price_slab_5000p" style="display: none;">Starts from <b>$</b>0.5/user/month<br><u style="color:orange; font-size: 21px;"><a onclick="gatherplaninfo(\'all_inclusive\',\'5K\')" style="color:orange;"><b>Request a Quote</b></a></u></td>

                            <td class="mo_idp_price_row mo_idp_price_slab_ul" style="display: none;">N/A</td>
                            <td class="mo_idp_price_row mo_idp_price_slab_ul" style="display: none;">N/A</td>
                            <td class="mo_idp_price_row mo_idp_price_slab_ul" style="display: none;"><u style="color:orange; font-size: 21px;"><a id="wp_yearly_UL" onclick="gatherplaninfo(\'wp_yearly\',\'UL\')" style="color:orange;"><b>Request a Quote</b></a></u></td>
                            <td class="mo_idp_all_inc_cell mo_idp_price_slab_ul" style="display: none;">Starts from <b>$</b>0.5/user/month<br><u style="color:orange; font-size: 21px;"><a onclick="gatherplaninfo(\'all_inclusive\',\'5K\')" style="color:orange;"><b>Request a Quote</b></a></u></td>
                        </tr>
 
                        <tr>
                            <td class="license_plan_points"><b>User Storage Location</b></td>
                            <td colspan=2 class="license_plan_wp_premium">Keep Users in miniOrange Database</td>
                            <td class="license_plan_miniorange">Keep Users in WordPress Database</td>
                            <td class="license_plan_miniorange">Keep Users in miniOrange Database or Enterprise Directory like Azure AD, Active Directory, LDAP, Office 365, Google Apps  or any 3rd party providers using SAML, OAuth, Database, APIs etc.</td>
                        </tr>
                        <tr>
                            <td class="license_plan_points"><b>Password Management</b></td>
                            <td colspan=2 class="license_plan_wp_premium">Passwords will be hosted in miniOrange</td>
                            <td class="license_plan_miniorange">Passwords will be stored in your WordPress Database</td>
                            <td class="license_plan_miniorange">Passwords can be managed by miniOrange or by the 3rd party Identity Provider</td>
                        </tr>
                        <tr>
                            <td class="license_plan_points"><b>SSO Support</b></td>
                            <td colspan=2 class="license_plan_wp_premium">Cross-Protocol SSO Support<br>SAML<br>OAuth<br>OpenID Connect<br>JWT</td>
                            <td class="license_plan_miniorange">Single-Protocol SSO Support<br>&nbsp;<br>SAML<br>&nbsp;<br>&nbsp;</td>
                            <td class="license_plan_miniorange">Cross-Protocol SSO Support<br>SAML<br>OAuth<br>OpenID Connect<br>JWT</td>
                        </tr>
                        <tr>
                            <td class="license_plan_points"><b>User Registration</b></td>
                            <td colspan=2 class="license_plan_wp_premium">Sign-up via miniOrange Login Page</td>
                            <td class="license_plan_miniorange">Use your own existing WordPress Sign-up form</td>
                            <td class="license_plan_miniorange">Sign-up via miniOrange Login Page</td>
                        </tr> 
                        <tr>
                            <td class="license_plan_points"><b>Login Page</b></td>
                            <td colspan=2 class="license_plan_wp_premium">Embed miniOrange Login Widget on your WordPress Site<br>OR<br>Use Login Page hosted on miniOrange</td>
                            <td class="license_plan_miniorange">Use your own existing WordPress Login Page</td>
                            <td class="license_plan_miniorange">Fully customizable miniOrange Login Page</td>
                        </tr>
                        <tr>
                            <td class="license_plan_points"><b>Custom Domains</b></td>
                            <td colspan=2 class="license_plan_wp_premium">miniOrange sub-domain will be provided</td>
                            <td class="license_plan_miniorange">Use your own WordPress domain</td>
                            <td class="license_plan_miniorange">Fully Custom Domain is provided</td>
                        </tr>
                        <tr>
                            <td class="license_plan_points"><b>Social Providers</b></td>
                            <td colspan=2 class="license_plan_wp_premium">Included<br>(Facebook, Twitter, Google+, etc)</td>
                            <td class="license_plan_miniorange"><a href="https://plugins.miniorange.com/social-login-social-sharing#pricing" target="_blank" style="color:orange;">Click here</a> to purchase Social Login Plugin seperately</td>
                            <td class="license_plan_miniorange">Included<br>(Facebook, Twitter, Google+, etc)</td>
                        </tr>                                            
                        <tr>
                            <td class="license_plan_points"><b>Multi-Factor Authentication</b></td>
                            <td colspan=2 class="license_plan_wp_premium">Not Included</td>
                            <td class="license_plan_miniorange"><a href="https://plugins.miniorange.com/2-factor-authentication-for-wordpress#pricing" target="_blank" style="color:orange;">Click here</a> to purchase Multi-Factor Plugin seperately</td>
                            <td class="license_plan_miniorange">Included</td>
                        </tr>                          
                        <tr>
                            <td class="license_plan_points" style="border-radius:0 0 12px 12px;"><b>User Provisioning</b></td>
                            <td colspan=2 class="license_plan_wp_premium" style="border-radius:0 0 12px 12px;">Not Included</td>
                            <td class="license_plan_miniorange" style="border-radius:0 0 12px 12px;">Not Included</td>
                            <td class="license_plan_miniorange" style="border-radius:0 0 12px 12px;">Included</td>
                        </tr>
                    
                    </table>
<!--
                    <table class="mo_idp_pricing_table" style="margin:auto;">
                        <tr>
                            <td><h2>Choose your Plan : </h2></td>
                            <td>
                                <select style="width:85%">
                                    <option>WordPress Premium Plan</option>
                                    <option>miniOrange Lite Plan</option>
                                    <option>miniOrange All Inclusive Plan</option>
                                </select>
                            </td>
                            <td>
                                <select style="width:75%">
                                    <option>Pay Monthly</option>
                                    <option>Pay Yearly</option>
                                </select>
                            </td>
                            <td>
                                <a href="https://www.google.com" target="_blank">Proceed to Payment Page</a> <span class="dashicons dashicons-arrow-right-alt2"></span>
                            </td>
                        </tr>
                    </table>
-->
            </div>
            <div id="disclamer" class="mo_idp_pricing_layout mo-idp-center">
                <h3>* Steps to Upgrade to Premium Plugin -</h3>
                <p>
                    1. You will be redirected to miniOrange Login Console. 
                    Enter your password with which you created an account with us. 
                    After that you will be redirected to payment page.
                </p>
                <p>
                    2. Enter you card details and complete the payment. 
                    On successful payment completion, you will see the link to download the premium plugin.
                </p>
                <p>
                    3. Once you download the premium plugin, just unzip it and replace the folder with existing plugin. <br>
                    <b>Note: Do not first delete and upload again from wordpress admin panel as your already saved settings will get lost.</b></p>
                    <p>4. From this point on, do not update the plugin from the Wordpress store.</p>
                    <h3>** End to End Integration - </h3>
                    <p> 
                        We will setup a Conference Call / Gotomeeting and do end to end configuration for you. 
                        We provide services to do the configuration on your behalf. 
                    </p>
                    If you have any doubts regarding the licensing plans, you can mail us at 
                    <a href="mailto:info@xecurify.com"><i>info@xecurify.com</i></a> 
                    or submit a query using the <b>support form</b>.
                </p>
            </div>
            <div class="mo_idp_pricing_layout mo-idp-center">
                <h3>10 Days Return Policy</h3>
                <p>
                    At miniOrange, we want to ensure you are 100% happy with your purchase.  If the Premium plugin you purchased is not working as
                    advertised and you have attempted to resolve any feature issues with our support team, which couldn\'t get resolved, then we will
                    refund the whole amount within 10 days of the purchase. Please email us at
                    <a href="mailto:info@xecurify.com">info@xecurify.com</a> for any queries regarding the return policy.
                    <br> If you have any doubts regarding the licensing plans, you can mail us at 
                    <a href="mailto:info@xecurify.com">info@xecurify.com</a> or submit a query using the support form.
                </p>
            </div>
        </div>';