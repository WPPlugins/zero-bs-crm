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


class zeroBSCRM_Invoice_List extends WP_List_Table {

    
    public function __construct() {

        parent::__construct( array(
            'singular' => __w( 'Invoice', 'zerobscrm' ),             'plural'   => __w( 'Invoices', 'zerobscrm' ),             'ajax'     => false         ) );

    }


    
    public static function get_invoices( $per_page = 10, $page_number = 1 ) {

                return zeroBS_getInvoices(true,$per_page,$page_number,true); 
    }


    
    public static function delete_invoice( $id ) {

                
    }


    
    public static function record_count() {
      
                return zeroBS_getInvoiceCount();

    }


    
    public function no_items() {
        _we( 'No Invoices avaliable.', 'zerobscrm' );
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


    
    function column_invoiceno( $item ) {
        
        $qc = 0;
        
                        if (isset($item['zbsid']) && !empty($item['zbsid'])) $qc = $item['zbsid'];

                return '<a href="post.php?post='.$item['id'].'&action=edit">'.$qc.'</a>';


    }
    function column_status( $item ) {
        
        $status = 'Draft'; $styles = 'color:#999';

        if (isset($item['meta']) && isset($item['meta']['status'])){

            switch($item['meta']['status']){

                case 'Unpaid':
                    $status = $item['meta']['status'];
                    $styles = 'color:green';

                    break;
                case 'Paid':
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
    function column_val( $item ) {
        
        $qc = 0;

        if (isset($item['meta']) && isset($item['meta']['val'])) $qc = $item['meta']['val'];

        return zeroBSCRM_getCurrencyChr().zeroBSCRM_prettifyLongInts($qc);

    }
    function column_date( $item ) {

        $d = $item['created'];

        if (isset($item['meta']) && isset($item['meta']['date'])) $d = $item['meta']['date'];

        return date(zeroBSCRM_getDateFormat(),strtotime($d));

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
                        'invoiceno'    => __w( 'Invoice No#', 'zerobscrm' ),
            'status'    => __w( 'Status', 'zerobscrm' ),
            'customername'    => __w( 'Customer Name', 'zerobscrm' ),
            'val' => __w( 'Value', 'zerobscrm' ),
            'date' => __w( 'Date', 'zerobscrm' )
        );

        return $columns;
    }


    
    public function get_sortable_columns() {
        $sortable_columns = array(
            'customername' => array( 'customername', true ),
            'invoiceno' => array( 'invoiceno', true ),
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

        $per_page     = $this->get_items_per_page( 'invoices_per_page', 10 );
        $current_page = $this->get_pagenum();
        $total_items  = self::record_count();

        $this->set_pagination_args( array(
            'total_items' => $total_items,             'per_page'    => $per_page,         ) );

        $this->items = self::get_invoices( $per_page, $current_page );

    }

    public function process_bulk_action() {

        
    }

}





function zeroBSCRM_render_invoiceslist_page(){

    $option = 'per_page';
    $args   = array(
        'label'   => 'Invoices',
        'default' => 10,
        'option'  => 'invoices_per_page'
    );

    add_screen_option( $option, $args );
    
    $invoiceListTable = new zeroBSCRM_Invoice_List();
   
                        
                $normalLoad = true;
        
        
        
        if ($normalLoad){

                                    

            

                        $invoiceListTable->prepare_items();

            ?><div class="wrap">
                <h1>Invoices<?php 
                                        if ( zeroBSCRM_permsInvoices() ) {
                        echo ' <a href="' . esc_url( 'post-new.php?post_type=zerobs_invoice' ) . '" class="page-title-action">' . esc_html( 'Add New' ) . '</a>';
                    }
                ?></h1>
                <?php 

                   

                $invoiceListTable->views(); ?>
                <?php  ?>
                <form method="post">
                    <?php  ?>
                    <?php $invoiceListTable->display(); ?>
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
