<?php /* !
 * Zero BS CRM
 * http://www.epicplugins.com
 * V1.0
 *
 * Copyright 2016, Epic Plugins, StormGate Ltd.
 *
 * Date: 26/05/16
 */



    if ( ! defined( 'ZEROBSCRM_PATH' ) ) exit;






if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}


class zeroBSCRM_Customer_ListNoQJ extends WP_List_Table {

        private $customViewArr = false;

    
    public function __construct() {

        parent::__construct( array(
            'singular' => __w( 'Customer', 'zerobscrm' ),             'plural'   => __w( 'Customers', 'zerobscrm' ),             'ajax'     => false         ) );

    }


    
    public static function get_customers( $per_page = 10, $page_number = 1, $possibleSearchTerm = '' ) {

                        return zeroBS_getCustomersNoQJ(true,$per_page,$page_number,true,true,$possibleSearchTerm,true);

    }


    
    public static function get_customers_filtered( $per_page = 10, $page_number = 1 ) {

                return zbs_customerFiltersRetrieveCustomers($per_page,$page_number,false);

    }


    
    public static function delete_customer( $id ) {

                
    }


    
    public static function record_count() {
      
                        return zeroBS_getCustomerCount();

    }

    
    public static function record_count_filtered() {
      
                return zeroBS__customerFiltersRetrieveCustomerCount();

    }


    
    public function no_items() {
        _we( 'No Customers avaliable.', 'zerobscrm' );
    }


    
    public function column_default( $item, $column_name ) {

        

       

                if (is_array($this->customViewArr)){ 

                        if (isset($this->customViewArr[$column_name])){

                                $colFuncName = ''; 
                if (isset($this->customViewArr[$column_name][1])) $colFuncName = $this->customViewArr[$column_name][1];

                                if (substr($colFuncName,0,18) == 'zbsDefault_column_'){

                                        return $this->$colFuncName($item);




                } else {

                    
                                        if (!empty($colFuncName) && function_exists($colFuncName))
                        return $colFuncName($item);                     else
                                                return 'Custom Column Function Not Found!';

                }


            } else {

                                return '?';

            }


        } else {

            
            
            switch ($column_name){

                case 'customername':
                    return $this->zbsDefault_column_customername($item);
                    break;
                case 'customeremail':
                    return $this->zbsDefault_column_customeremail($item);
                    break;
                case 'status':
                    return $this->zbsDefault_column_status($item);
                    break;
                case 'quotecount':
                    return $this->zbsDefault_column_quotecount($item);
                    break;
                case 'invcount':
                    return $this->zbsDefault_column_invcount($item);
                    break;
                case 'transcount':
                    return $this->zbsDefault_column_transcount($item);
                    break;
                case 'totalval':
                    return $this->zbsDefault_column_totalval($item);
                    break;
                case 'added':
                    return $this->zbsDefault_column_added($item);
                    break;
                default:
                    return print_r($item,true);                     break;


            }

                        return '?';

        }

    }

    
    function zbsDefault_column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']
        );
    }

    
    function zbsDefault_column_customername( $item ) {
        
        $colStr = '';
        if (isset($item['meta'])){
            $colStr = '<strong><a href="post.php?post='.$item['id'].'&action=edit">'.zeroBS_customerName($item['id'],$item['meta'],false,false).'</a></strong><br />';
            if (isset($item['meta']['addr1']) && isset($item['meta']['city']))
                                $colStr .= '<div>'.zeroBS_customerAddr($item['id'],$item['meta'],'short',', ').'</div>';
        }
        
        return $colStr;

    }

    
    function zbsDefault_column_customeremail( $item ) {

                $email = ''; if (isset($item['meta']) && isset($item['meta']['email']) && !empty($item['meta']['email'])) $email = $item['meta']['email'];
        
        $colStr = '-';
        if (!empty($email)){
            $colStr = '<strong><a href="post.php?post='.$item['id'].'&action=edit">'.$email.'</a></strong><br />';
            $colStr .= '<a href="mailto:'.$email.'" target="_blank">Send Email</a>';
        }
        
        return $colStr;

    }

    
    function zbsDefault_column_status( $item ) {
        
        $status = '?'; $styles = '';

        if (isset($item['meta']) && isset($item['meta']['status'])){

            switch($item['meta']['status']){

                case 'Lead':
                    $status = $item['meta']['status'];
                    $styles = 'color:green';

                    break;
                case 'Customer':
                    $status = $item['meta']['status'];
                    $styles = 'color:#7F7FE4';

                    break;
                default:
                    $status = $item['meta']['status'];
                    break;


            }

        }


        return '<strong style="'.$styles.'">'.$status.'</strong>';
    }

    
    function zbsDefault_column_quotecount( $item ) {
        
        $qc = 0;

        if (isset($item['quotes'])) $qc = count($item['quotes']);

        return zeroBSCRM_prettifyLongInts($qc);

    }
    function zbsDefault_column_invoicecount( $item ) {
        
        $iC = 0;

        if (isset($item['invoices'])) $iC = count($item['invoices']);

        return zeroBSCRM_prettifyLongInts($iC);

    }
    function zbsDefault_column_transactioncount( $item ) {
        
        $tC = 0;

        if (isset($item['transactions'])) $tC = count($item['transactions']);

        return zeroBSCRM_prettifyLongInts($tC);

    }
    function zbsDefault_column_totalvalue( $item ) {
        
        $totalVal = 0;

        

                $totalVal = zeroBS_customerTotalValue($item['id'],$item['invoices'],$item['transactions']);

        return zeroBSCRM_getCurrencyChr().zeroBSCRM_prettifyLongInts($totalVal).'<!--CUSTDEET:'.json_encode($item).'-->';

    }
    function zbsDefault_column_added( $item ) {

        return date(zeroBSCRM_getDateFormat(),strtotime($item['created']));

    }



    
    function zbsDefault_column_name( $item ) {

        $delete_nonce = wp_create_nonce( 'tbp_delete_customer' );

        $title = '<strong>' . $item['name'] . '</strong>';

        $actions = array(
            'delete' => sprintf( '<a href="?page=%s&action=%s&booking=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['id'] ), $delete_nonce )
        );

        return $title . $this->row_actions( $actions );
    }


    
    function get_columns() {


        
        $columns = array(
                        'customername'    => __w( 'Name', 'zerobscrm' ),
            'status' => __w( 'Status', 'zerobscrm' ),
            'quotecount'    => __w( 'Quotes', 'zerobscrm' ),
            'invcount' => __w( 'Invoices', 'zerobscrm' ),
            'transcount' => __w( 'Transactions', 'zerobscrm' ),
            'totalval' => __w( 'Total Value', 'zerobscrm' ),
            'added' => __w( 'Added', 'zerobscrm' )
        );

        
                        global $zeroBSCRM_Settings;
            $settings = $zeroBSCRM_Settings->getAll();

                        if (isset($settings['customviews']) && isset($settings['customviews']['customer'])){

                                $this->customViewArr = $settings['customviews']['customer'];

                $columns = array();

                                if (count($settings['customviews']['customer']) > 0) foreach ($settings['customviews']['customer'] as $colname => $coldeets){

                                        $columns[$colname] = __w($coldeets[0],'zerobscrm');

                }                                                



            }


        return $columns;
    }


    
    public function get_sortable_columns() {
        $sortable_columns = array(
            'customername' => array( 'customername', true ),
            'totalval' => array( 'totalval', true ),
            'added' => array( 'added', false )
        );

        return $sortable_columns;
    }

    
    public function get_bulk_actions() {
        $actions = array(
                    );

        return $actions;
    }


    
    public function prepare_items() {

                
                $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        
        
        $this->process_bulk_action();

        $per_page     = $this->get_items_per_page( 'customers_per_page', 10 );
        $current_page = $this->get_pagenum();
        $total_items  = self::record_count();

        $this->set_pagination_args( array(
            'total_items' => $total_items,             'per_page'    => $per_page         ));

                $possibleSearch = ''; if (isset($_POST['s'])) $possibleSearch = sanitize_text_field($_POST['s']);

        $this->items = self::get_customers( $per_page, $current_page, $possibleSearch);

    }

    
    public function prepare_items_filtered() {

                
                $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        
        
        $this->process_bulk_action();

        $per_page     = $this->get_items_per_page( 'customers_per_page', 10 );
        $current_page = $this->get_pagenum();
        $total_items  = self::record_count_filtered();

        $this->set_pagination_args( array(
            'total_items' => $total_items,             'per_page'    => $per_page         ));

                
                $this->items = self::get_customers_filtered( $per_page, $current_page);

    }

    public function process_bulk_action() {

        
    }

}





function zeroBSCRM_render_customersNoQJlist_page(){

    $option = 'per_page';
    $args   = array(
        'label'   => 'Customers',
        'default' => 10,
        'option'  => 'customers_per_page'
    );

    add_screen_option( $option, $args );

    $customerListTable = new zeroBSCRM_Customer_ListNoQJ();

                        
                $normalLoad = true;
        
        
        
        if ($normalLoad){

                                    

            

                        $customerListTable->prepare_items();

            ?><div class="wrap">
                <h1>Customers (Without Quote)<?php 
                                        if ( zeroBSCRM_permsCustomers() ) {
                        echo ' <a href="' . esc_url( 'post-new.php?post_type=zerobs_customer' ) . '" class="page-title-action">' . esc_html( 'Add New' ) . '</a>';
                    }
                ?></h1>
                <?php 

                                        if (isset($_POST['s']) && !empty($_POST['s'])) {

                        $searchTerm = sanitize_text_field($_POST['s']);

                        echo '<div id="zbsSearchTerm">Searching: "'.$searchTerm.'" <button type="button" class="button" id="clearSearch">Cancel Search</button></div>';

                    }

                $customerListTable->views(); ?>
                <?php  ?>
                <form method="post">
                    <?php $customerListTable->search_box('Search Customers','customersearch'); ?>
                    <?php $customerListTable->display(); ?>
                </form>
                <!--<br class="clear">-->
            </div>

                <script type="text/javascript">
                    jQuery(document).ready(function(){

                        jQuery('#clearSearch').click(function(){

                            jQuery('#customersearch-search-input').val('');
                            jQuery('#search-submit').click();

                        });

                    });
                </script>
                
            <?php

        }

}



function zeroBSCRM_render_filtered_customersNoQJlist_page(){

    $option = 'per_page';
    $args   = array(
        'label'   => 'Customers',
        'default' => 10,
        'option'  => 'customers_per_page'
    );

    add_screen_option( $option, $args );
    
    $customerListTable = new zeroBSCRM_Customer_ListNoQJ();
   
                        
                $normalLoad = true;
        
        
        
        if ($normalLoad){

                                    

            

                            
                                global $zbsCustomerFiltersPosted;
                if (!isset($zbsCustomerFiltersPosted)) {
                                        $customerListTable->prepare_items();
                    $recordCount = $customerListTable->record_count();
                } else {
                                        $customerListTable->prepare_items_filtered();
                    $recordCount = $customerListTable->record_count_filtered();
                }

            ?><div class="wrap">
                <h1><?php  
                    
                                        echo zeroBSCRM_prettifyLongInts($recordCount);
                    

                ?> Customers (Without Quote)<?php 
                                                                                                ?></h1>
                <br class="clear">
                <form method="post">
                    <?php $customerListTable->display(); ?>
                </form>
                <br class="clear">
            </div>

                <script type="text/javascript">
                    jQuery(document).ready(function(){

                        

                    });
                </script>
                
            <?php
    
        }


}