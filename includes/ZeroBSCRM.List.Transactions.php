<?php /* !
 * Zero BS CRM
 * http://www.epicplugins.com
 * V1.1.19
 *
 * Copyright 2016, Epic Plugins, StormGate Ltd.
 *
 * Date: 18/10/16
 */



    if ( ! defined( 'ZEROBSCRM_PATH' ) ) exit;






if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}


class zeroBSCRM_Transaction_List extends WP_List_Table {

    
    public function __construct() {

        parent::__construct( array(
            'singular' => __w( 'Transaction', 'zerobscrm' ),             'plural'   => __w( 'Transactions', 'zerobscrm' ),             'ajax'     => false         ) );

    }


    
    public static function get_transactions( $per_page = 10, $page_number = 1 ) {

                return zeroBS_getTransactions(true,$per_page,$page_number,true); 
    }


    
    public static function delete_transaction( $id ) {

                
    }


    
    public static function record_count() {
      
                return zeroBS_getTransactionCount();

    }


    
    public function no_items() {
        _we( 'No Transactions avaliable.', 'zerobscrm' );
    }


    
    public function column_default( $item, $column_name ) {
        switch ( $column_name ) {
                                                default:
                return print_r( $item, true );         }
    }

    
    function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']
        );
    }

    
    function column_customername( $item ) {
        
        $colStr = '';
        if (isset($item['customer']) && isset($item['customer']['meta'])){
            $colStr = '<strong>'.zeroBS_customerName($item['customerid'],$item['customer']['meta'],false,false).'</strong><br />';
            if (isset($item['customer']['meta']['addr1']) && isset($item['customer']['meta']['city']))
                                $colStr .= '<div>'.zeroBS_customerAddr($item['customer']['id'],$item['customer']['meta'],'short',', ').'</div>';
        }
        
        return $colStr;

    }


    
    function column_transactionno( $item ) {
        
        $qc = '';

        if (isset($item['meta']) && isset($item['meta']['orderid'])) $qc = $item['meta']['orderid'];
        return '<a href="post.php?post='.$item['id'].'&action=edit">'.$qc.'</a>';

    }
    function column_val( $item ) {
        
        $qc = 0;

        if (isset($item['meta']) && isset($item['meta']['total'])) $qc = $item['meta']['total'];

        return zeroBSCRM_getCurrencyChr().zeroBSCRM_prettifyLongInts($qc);

    }
    function column_date( $item ) {


        $d = new DateTime($item['created']);
        $d = $d->format(zeroBSCRM_getDateFormat());


        
        
        
        return $d;

    }



    
    function column_name( $item ) {

        $delete_nonce = wp_create_nonce( 'tbp_delete_customer' );

        $title = '<strong>' . $item['name'] . '</strong>';

        $actions = array(
            'delete' => sprintf( '<a href="?page=%s&action=%s&booking=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['id'] ), $delete_nonce )
        );

        return $title . $this->row_actions( $actions );
    }


    
    function get_columns() {
        $columns = array(
                        'transactionno'    => __w( 'Transaction No#', 'zerobscrm' ),
            'val' => __w( 'Value', 'zerobscrm' ),
            'date' => __w( 'Date', 'zerobscrm' )
        );

        return $columns;
    }


    
    public function get_sortable_columns() {
        $sortable_columns = array(
            'transactionno' => array( 'transactionno', true ),
            'val' => array( 'val', true ),
            'date' => array( 'date', false )
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

        $per_page     = $this->get_items_per_page( 'transactions_per_page', 10 );
        $current_page = $this->get_pagenum();
        $total_items  = self::record_count();

        $this->set_pagination_args( array(
            'total_items' => $total_items,             'per_page'    => $per_page         ) );

        $this->items = self::get_transactions( $per_page, $current_page );

    }

    public function process_bulk_action() {

        
    }

}





function zeroBSCRM_render_transactionslist_page(){

    $option = 'per_page';
    $args   = array(
        'label'   => 'Transactions',
        'default' => 10,
        'option'  => 'transactions_per_page'
    );

    add_screen_option( $option, $args );
    
    $transactionListTable = new zeroBSCRM_Transaction_List();
   
                        
                $normalLoad = true;
        
        
        
        if ($normalLoad){

                                    

            

                        $transactionListTable->prepare_items();

            ?><div class="wrap">
                <h1>Transactions<?php 
                                        if ( zeroBSCRM_permsTransactions() ) {
                        echo ' <a href="' . esc_url( 'post-new.php?post_type=zerobs_transaction' ) . '" class="page-title-action">' . esc_html( 'Add New' ) . '</a>';
                    }
                ?></h1>
                <?php 

                   

                $transactionListTable->views(); ?>
                <?php  ?>
                <form method="post">
                    <?php  ?>
                    <?php $transactionListTable->display(); ?>
                </form>
                <br class="clear">
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
