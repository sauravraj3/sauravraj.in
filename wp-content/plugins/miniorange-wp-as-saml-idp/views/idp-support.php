<?php

	echo '

	<div class="mo_idp_divided_layout mo-idp-full">
        <div class="mo_idp_table_layout mo-idp-center">
            <h2>SUPPORT</h2><hr>
            <p>Need any help? Just send us a query so we can help you.</p>
            <form method="post" action="">
                <input type="hidden" name="option" value="mo_idp_contact_us_query_option" />
                <table class="mo_idp_settings_table">
                    <tr>
                        <td colspan=4>
                            <input  type="email" 
                                    class="mo_idp_table_contact" required 
                                    placeholder="Enter your Email" 
                                    name="mo_idp_contact_us_email" 
                                    value="'.$email.'">
                        </td>
                    </tr>
                    <tr>
                        <td colspan=4>
                            <input  type="tel" 
                                    id="contact_us_phone" 
                                    pattern="[\+]\d{11,14}|[\+]\d{1,4}[\s]\d{9,10}" 
                                    placeholder="Enter your phone number with country code (+1)" 
                                    class="mo_idp_table_contact" 
                                    name="mo_idp_contact_us_phone" 
                                    value="'.$phone.'">
                        </td>
                    </tr>';
    if (!empty($plan))
    {
        echo '      <tr>
                        <td style="padding:10px; width: auto;">
                            <label for="plan-name-dd">Choose a plan:</label>
                        </td>
                        <td style="padding:10px; width: auto;">    
                            <select name="mo_idp_upgrade_plan_name" id="plan-name-dd">
                                <option value="lite_monthly"
                                '.(!empty($plan) && strpos($plan,'lite_monthly') ? 'selected' : '').'>
                                    Cloud IDP Lite - Monthly Plan
                                </option>
                                <option value="lite_yearly"
                                '.(!empty($plan) && strpos($plan,'lite_yearly') ? 'selected' : '').'>
                                    Cloud IDP Lite - Yearly Plan
                                </option>
                                <option value="wp_yearly"
                                '.(!empty($plan) && strpos($plan,'wp_yearly') ? 'selected' : '').'>
                                    WordPress Premium - Yearly Plan
                                </option>
                                <option value="all_inclusive"
                                '.(!empty($plan) && strpos($plan,'all_inclusive') ? 'selected' : '').'>
                                    All Inclusive Plan
                                </option>
                            </select>
                        </td>
                        <td style="padding:10px; width: auto;">
                            Number of users: 
                        </td>
                        <td style="padding:10px; width: auto;">    
                            <input  type="text"
                                    name="mo_idp_upgrade_plan_users"
                                    value="'.(!empty($users)? $users : '').'">
                        </td>
                    </tr>';
    }
    echo '          <tr>
                        <td colspan=4>
                            <textarea   class="mo_idp_table_contact" 
                                        onkeypress="mo_idp_valid_query(this)" 
                                        onkeyup="mo_idp_valid_query(this)" 
                                        placeholder="Write your query here" 
                                        onblur="mo_idp_valid_query(this)" required 
                                        name="mo_idp_contact_us_query" 
                                        rows="4" 
                                        style="resize: vertical;">'.$request_quote.'</textarea>
                        </td>
                    </tr>
                </table>
                <br>
                <input  type="submit" 
                        name="submit" 
                        value="Submit Query" 
                        style="width:110px;" 
                        class="button button-primary button-large" />
    
            </form>
            <p>
                If you want custom features in the plugin, just drop an email to 
                <a href="mailto:info@xecurify.com">info@xecurify.com</a>.
            </p>
        </div>
    </div>
    
        <script>
            function moSharingSizeValidate(e){
                var t=parseInt(e.value.trim());t>60?e.value=60:10>t&&(e.value=10)
            }
            function moSharingSpaceValidate(e){
                var t=parseInt(e.value.trim());t>50?e.value=50:0>t&&(e.value=0)
            }
            function moLoginSizeValidate(e){
                var t=parseInt(e.value.trim());t>60?e.value=60:20>t&&(e.value=20)
            }
            function moLoginSpaceValidate(e){
                var t=parseInt(e.value.trim());t>60?e.value=60:0>t&&(e.value=0)
            }
            function moLoginWidthValidate(e){
                var t=parseInt(e.value.trim());t>1000?e.value=1000:140>t&&(e.value=140)
            }
            function moLoginHeightValidate(e){
                var t=parseInt(e.value.trim());t>50?e.value=50:35>t&&(e.value=35)
            }
        </script>';