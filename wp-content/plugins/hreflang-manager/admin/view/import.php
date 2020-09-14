<?php

if (!current_user_can(get_option($this->shared->get('slug') . "_import_menu_capability"))) {
    wp_die(esc_attr__('You do not have sufficient permissions to access this page.', 'dahm'));
}

?>

<!-- output -->

<div class="wrap">

    <h2><?php esc_attr_e('Hreflang Manager - Import', 'dahm'); ?></h2>

    <div id="daext-menu-wrapper">

        <?php

        //process the xml file upload
        if (isset($_FILES['file_to_upload']) and
            isset($_FILES['file_to_upload']['name']) and
            preg_match('/^.+\.xml$/', $_FILES['file_to_upload']['name'], $matches) === 1
        ) {

            $counter = 0;

            if (file_exists($_FILES['file_to_upload']['tmp_name'])) {

                global $wpdb;

                //read xml file
                $xml = simplexml_load_file($_FILES['file_to_upload']['tmp_name']);

                foreach ($xml->connect as $single_connect) {

                    //convert object to array
                    $single_connect_a = get_object_vars($single_connect);

                    //remove the id key
                    unset($single_connect_a['id']);

                    /**
                     * Generate the 'url_to_connect' value based on the 'Import Language' and 'Import Locale' options if
                     * the "Import Mode" option is set to "Based on Import Options".
                     */
                    if(get_option("da_hm_import_mode") == 'import_options'){
                        $single_connect_a['url_to_connect'] = $this->shared->generate_url_to_connect($single_connect_a);
                    }

                    $table_name = $wpdb->prefix . $this->shared->get('slug') . "_connect";
                    $wpdb->insert(
                        $table_name,
                        $single_connect_a
                    );
                    $inserted_table_id = $wpdb->insert_id;

                    $counter++;

                }

                echo '<div class="updated settings-error notice is-dismissible below-h2"><p>' . $counter . ' ' . esc_attr__('connections have been added.', 'dahm') . '</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">' . esc_attr__('Dismiss this notice.', 'dahm') . '</span></button></div>';

            }

        }

        ?>

        <p><?php esc_attr_e('Import the connections stored in your XML file by clicking the Upload file and import button.', 'dahm'); ?></p>
        <form enctype="multipart/form-data" id="import-upload-form" method="post" class="wp-upload-form" action="">
            <p>
                <label for="upload"><?php esc_attr_e('Choose a file from your computer:', 'dahm'); ?></label>
                <input type="file" id="upload" name="file_to_upload">
            </p>
            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary"
                                     value="<?php esc_attr_e('Upload file and import', 'dahm'); ?>"></p>
        </form>
        <p><strong><?php esc_attr_e('IMPORTANT: This menu should only be used to import the XML files generated with the "Export" menu.', 'dahm'); ?></strong></p>

    </div>

</div>