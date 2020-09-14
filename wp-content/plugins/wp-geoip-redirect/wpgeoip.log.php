<?php
/*
 * Log pin results
 */
 
 function wpgeoip_log()
 {
     
     global $wpdb;
     
     $logs = $wpdb->get_results("SELECT * FROM wpgeoip_log ORDER BY logID DESC LIMIT 0, 100");
     
     ?>
     <div id="wrap" class="wpgeoip-wrapper">
        <img src="<?= plugin_dir_url(__FILE__) ?>/assets/images/icon32x32.png" style="float:left;"/> 
        <h1 style="float:left;margin-top: 10px;margin-left:10px;">WP GeoIP Log</h1>
        <div style="clear:both;"></div>
        <hr />
        <br />

        <div class="updated below-h2">Showing most recent entries</div>
            <br />
     <?php
     
     if($logs) {
         
         print '<table class="widefat">';
         
         print '<thead>
                  <tr>
                    <th style="font-weight: bold;color: #01708C;">Action</th>
                    <th style="font-weight: bold;color: #01708C;">Result</th>
                  </tr>
                </thead>
                <tbody>';
         
         foreach($logs as $log) {
             print '<tr>
                    <td>'.$log->post.'</td>
                    <td>'.$log->message.'</td>
                    </tr>';
         }
         
         print '</tbody>
                </table>';
         
     }else{
         print '<div class="updated updated-red">Nothing logged yet.</div>';
     }
     
     ?>
     </div>
     <?php
     
 }
