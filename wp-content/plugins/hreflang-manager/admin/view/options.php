<?php

if ( !current_user_can( 'manage_options' ) )  {
    wp_die( __( 'You do not have sufficient capabilities to access this page.' ) );
}

?>

<div class="wrap">

    <h2><?php _e('Hreflang Manager - Options', 'dahm'); ?></h2>

    <?php

    //settings errors
    if( isset($_GET['settings-updated']) and $_GET['settings-updated'] == 'true' ){
        settings_errors();
    }

    ?>

    <div id="daext-options-wrapper">

        <?php
        //get current tab value
        $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general_options';
        ?>

        <div class="nav-tab-wrapper">
            <a href="?page=da_hm_options&tab=general_options" class="nav-tab <?php echo $active_tab == 'general_options' ? 'nav-tab-active' : ''; ?>"><?php _e('General', 'dahm'); ?></a>
            <a href="?page=da_hm_options&tab=defaults_options" class="nav-tab <?php echo $active_tab == 'defaults_options' ? 'nav-tab-active' : ''; ?>"><?php _e('Defaults', 'dahm'); ?></a>
        </div>

        <form method='post' action='options.php'>

            <?php

            if( $active_tab == 'general_options' ) {

                settings_fields( $this->shared->get('slug') . '_general_options' );
                do_settings_sections( $this->shared->get('slug') . '_general_options' );

            }

            if( $active_tab == 'defaults_options' ) {

                settings_fields( $this->shared->get('slug') . '_defaults_options' );
                do_settings_sections( $this->shared->get('slug') . '_defaults_options' );

            }

            ?>

            <div class="daext-options-action">
                <input type="submit" name="submit" id="submit" class="button" value="<?php _e('Save Changes', 'dahm'); ?>">
            </div>

        </form>

    </div>

</div>