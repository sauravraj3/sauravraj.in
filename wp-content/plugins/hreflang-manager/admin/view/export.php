<?php

if (!current_user_can(get_option($this->shared->get('slug') . "_export_menu_capability"))) {
    wp_die(esc_attr__('You do not have sufficient permissions to access this page.', 'dahm'));
}

?>

<!-- output -->

<div class="wrap">

    <h2><?php esc_attr_e('Hreflang Manager - Export', 'dahm'); ?></h2>

    <div id="daext-menu-wrapper">

        <p><?php esc_attr_e('Click the Export button to generate an XML file that includes all your connections.', 'dahm'); ?></p>

        <!-- the data sent through this form are handled by the export_xml_controller() method called with the WordPress init action -->
        <form method="POST" action="admin.php?page=dahm-export">

            <div class="daext-widget-submit">
                <input name="dahm_export" class="button button-primary" type="submit"
                       value="<?php esc_attr_e('Export', 'dahm'); ?>" <?php if ($this->shared->number_of_connections() == 0) {
                    echo 'disabled="disabled"';
                } ?>>
            </div>

        </form>

    </div>

</div>