<?php
//if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 
    exit();


//drop a custom db table
global $wpdb;
$table_name = $wpdb->prefix . 'analyticaAnalytics';
$wpdb->query( "DROP TABLE IF EXISTS $table_name" );

//note in multisite looping through blogs to delete options on each blog does not scale. You'll just have to leave them.