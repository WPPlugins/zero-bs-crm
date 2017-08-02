<?php /* !
 * Zero BS CRM
 * http://www.epicplugins.com
 * V1.20
 *
 * Copyright 2016, Epic Plugins, StormGate Ltd.
 *
 * Date: 01/11/16
 */



    if ( ! defined( 'ZEROBSCRM_PATH' ) ) exit;




global $wpdb, $ZBSCRM_t;
$ZBSCRM_t['keys'] = $wpdb->prefix . "zbscrm_api_keys";    

function zeroBSCRM_database_check(){
  
    zeroBSCRM_checkTablesExist();

}


function zeroBSCRM_createTables(){
  global $wpdb, $ZBSCRM_t;
  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    
    $sql = "CREATE TABLE IF NOT EXISTS ". $ZBSCRM_t['keys'] ." (
        `zbs_id` INT NOT NULL AUTO_INCREMENT ,
        `zbs_key` VARCHAR(200) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NULL ,
        `zbs_perm` INT(1) NULL ,       
        PRIMARY KEY (`zbs_id`) );";
  dbDelta($sql); 
}

function zeroBSCRM_checkTablesExist(){
  global $ZBSCRM_t,$wpdb;
  $tablesExist = $wpdb->get_results("SHOW TABLES LIKE '".$ZBSCRM_t['keys']."'");
  if (count($tablesExist) < 1) zeroBSCRM_createTables();
}

 




define('ZBSCRM_INC_DB',true);