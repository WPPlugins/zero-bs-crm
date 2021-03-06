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


class zeroBSCRM_Quote_List extends WP_List_Table {

    
    public function __construct() {

        parent::__construct( array(
            'singular' => __w( 'Quote', 'zerobscrm' ),             'plural'   => __w( 'Quotes', 'zerobscrm' ),             'ajax'     => false         ) );

    }


    
    public static function get_quotes( $per_page = 10, $page_number = 1 ) {

                return zeroBS_getQuotes(true,$per_page,$page_number,true); 
    }


    
    public static function delete_quote( $id ) {

                
    }


    
    public static function record_count() {
      
                return zeroBS_getQuoteCount();

    }


    
    public function no_items() {
        _we( 'No Quotes avaliable.', 'zerobscrm' );
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


    
    function column_qj( $item ) {
        
        $qc = 0;

        $quoteOffset = zeroBSCRM_getQuoteOffset();
        $invOffset = zeroBSCRM_getInvoiceOffset();


                        if (isset($item['zbsid']) && !empty($item['zbsid'])) $qc = $item['zbsid'];       


        if (isset($item['meta']) && isset($item['meta']['name'])) $qc .= ' - '.$item['meta']['name'];

        $makeInvoiceStr = '<br />';
        if (isset($item['meta']) && (!isset($item['meta']['invlink']) || empty($item['meta']['invlink']) || !is_array($item['meta']['invlink']))) {

                        $makeInvoiceStr .= '<a href="post-new.php?post_type=zerobs_invoice&zbsinherit='.$item['id'].'">Make Invoice</a>';

        } else {

                        if (is_array($item['meta']['invlink'])){

                if (count($item['meta']['invlink']) > 0){

                    $invListStr = ''; $invLinkCount = 0;

                    foreach ($item['meta']['invlink'] as $invLinkID){
                        $invID = (int)$invLinkID;
                        if (!empty($invID)) {

                            if (!empty($invListStr)) $invListStr .= ', ';

                            $invListStr .= '<a href="post.php?post='.$invID.'&action=edit">#'.($invOffset+$invID).'</a>';
                            $invLinkCount++;

                        }
                    }

                    if (!empty($invListStr)){
                        $makeInvoiceStr .= 'View Invoice';
                        if ($invLinkCount > 1) $makeInvoiceStr .= 's';
                        $makeInvoiceStr .= ': '.$invListStr;
                    }

                } else {

                                    }

            }
        }

                return '<a href="post.php?post='.$item['id'].'&action=edit">'.$qc.'</a>'.$makeInvoiceStr;

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

    
    function column_accepted( $item ) {

                $acceptedArr = false;
        if (isset($item['meta']) && isset($item['meta']['accepted'])) $acceptedArr = $item['meta']['accepted'];

                                
        if (is_array($acceptedArr)){

            $td = '<strong>'.__w('Accepted','zerobscrm').' ' . date(zeroBSCRM_getDateFormat(),$acceptedArr[0]) . '</strong>';

        } else {
                
                        $zbsTemplated = get_post_meta($item['id'], 'templated', true);
            if (!empty($zbsTemplated)) {

                                $td = '<strong>'.__w('Published, not yet accepted','zerobscrm').'</strong>';

            } else {

                                $td = '<strong>'.__w('Not yet published','zerobscrm').'</strong>';

            }


        }


        return $td;
    }


    
    function get_columns() {

        global $useQuoteBuilder; if (!isset($useQuoteBuilder)) $useQuoteBuilder = zeroBSCRM_getSetting('usequotebuilder');

        $columns = array(
                        'qj'    => __w( 'QuoteRef', 'zerobscrm' ),
            'customername'    => __w( 'Customer Name', 'zerobscrm' ),
            'val' => __w( 'Value', 'zerobscrm' ),
            'date' => __w( 'Date', 'zerobscrm' )
        );

        if ($useQuoteBuilder == "1") $columns['accepted'] = __w('Accepted','zerobscrm');

        return $columns;
    }


    
    public function get_sortable_columns() {

        global $useQuoteBuilder; if (!isset($useQuoteBuilder)) $useQuoteBuilder = zeroBSCRM_getSetting('usequotebuilder');

        $sortable_columns = array(
            'customername' => array( 'customername', true ),
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

        $per_page     = $this->get_items_per_page( 'quotes_per_page', 10 );
        $current_page = $this->get_pagenum();
        $total_items  = self::record_count();

        $this->set_pagination_args( array(
            'total_items' => $total_items,             'per_page'    => $per_page         ) );

        $this->items = self::get_quotes( $per_page, $current_page );

    }

    public function process_bulk_action() {

        
    }

}





function zeroBSCRM_render_quoteslist_page(){

    $option = 'per_page';
    $args   = array(
        'label'   => 'Quotes',
        'default' => 10,
        'option'  => 'quotes_per_page'
    );

    add_screen_option( $option, $args );
    
    $quoteListTable = new zeroBSCRM_Quote_List();
   
                        
                $normalLoad = true;
        
        
        
        if ($normalLoad){

                                    

            

                        $quoteListTable->prepare_items();

            ?><div class="wrap">
                <h1>Quotes<?php 
                                        if ( zeroBSCRM_permsQuotes() ) {
                        echo ' <a href="' . esc_url( 'post-new.php?post_type=zerobs_quote' ) . '" class="page-title-action">' . esc_html( 'Add New' ) . '</a>';
                    }
                ?></h1>
                <?php 

                   

                $quoteListTable->views(); ?>
                <?php  ?>
                <form method="post">
                    <?php  ?>
                    <?php $quoteListTable->display(); ?>
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
