<?php
echo'
    <div class="mo-visual-tour-overlay" id="overlay" hidden></div>
    <div class="wrap">
            <div><img style="float:left;" src="'.MSI_LOGO_URL.'"></div>
            <h1>
                WP IDP Single Sign On
                <div id="idp-quicklinks">
                    <a class="add-new-h2" href="'.$help_url.'" target="_blank">FAQs</a>
                    <a class="license-button add-new-h2" href="'.$license_url.'">Upgrade</a>
                    <a class="add-new-h2" href="'.$support_url.'">Stuck? Need Help?</a>
                </div>
            </h1>			
    </div>';

    check_is_curl_installed();

echo'<div id="tab">
        <h2 class="nav-tab-wrapper">
            <a  class="nav-tab 
                '.($active_tab == $spSettingsTabDetails->_menuSlug ? 'nav-tab-active' : '').'" 
                href="'.$idp_settings.'">
                '.$spSettingsTabDetails->_tabName.'
            </a>
            <a  class="nav-tab 
                '.($active_tab == $metadataTabDetails->_menuSlug ? 'nav-tab-active' : '').'" 
                href="'.$sp_settings.'">
                '.$metadataTabDetails->_tabName.'
            </a>
            <a class="nav-tab 
                '.($active_tab == $attrMapTabDetails->_menuSlug ? 'nav-tab-active' : '').'" 
                href="'.$attr_settings.'">
                '.$attrMapTabDetails->_tabName.'
            </a>
            <a  class="nav-tab 
                '.($active_tab == $settingsTabDetails->_menuSlug ? 'nav-tab-active' : '').'" 
                href="'.$login_settings.'">
                '.$settingsTabDetails->_tabName.'
            </a>
            <a class="nav-tab 
                '.($active_tab == $licenseTabDetails->_menuSlug	? 'nav-tab-active' : '').'" 
                href="'.$license_url.'">
                '.$licenseTabDetails->_tabName.'
            </a>
            <a class="nav-tab 
                '.($active_tab == $profileTabDetails->_menuSlug ? 'nav-tab-active' : '').'" 
                href="'.$register_url.'">
                '.$profileTabDetails->_tabName.'
            </a>
    </div>';

    if (!get_option("mo_idp_new_certs"))    
    echo"<div style='display:block; width:62%; margin:auto; margin-top:10px; color:black; background-color:rgba(251, 232, 0, 0.15); 
    padding:15px 15px 15px 15px; border:solid 1px rgba(204, 204, 0, 0.36); font-size:large; line-height:normal'>
    <span style='color:red;'><span class='dashicons dashicons-warning'></span> <b>WARNING</b>:</span> The existing certificates have expired. Please update the certificates ASAP to secure your SSO.<br> Go to the <a href='admin.php?page=idp_metadata'><b>IDP Metadata</b></a> tab
    of the plugin to update your certificates. Make sure to update your Service Provider with the new certificate to ensure your SSO does not break.
</div>";