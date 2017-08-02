<?php /* !
 * Zero BS CRM
 * http://www.epicplugins.com
 * V1.2+
 *
 * Copyright 2016, Epic Plugins, StormGate Ltd.
 *
 * Date: 22/12/2016
 */



    if ( ! defined( 'ZEROBSCRM_PATH' ) ) exit;






if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}


class zeroBSCRM_QuoteTemplate_List extends WP_List_Table {

    
    public function __construct() {

        parent::__construct( array(
            'singular' => __w( 'Quote Template', 'zerobscrm' ),             'plural'   => __w( 'Quote Templates', 'zerobscrm' ),             'ajax'     => false         ) );

    }


    
    public static function get_quotetemplates( $per_page = 10, $page_number = 1 ) {

                return zeroBS_getQuoteTemplates(true,$per_page,$page_number);

    }


    
    public static function delete_quotetemplate( $id ) {

                
    }


    
    public static function record_count() {
      
                return zeroBS_getQuoteTemplateCount();

    }


    
    public function no_items() {
        _we( 'No Quote Templates avaliable.', 'zerobscrm' );
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

    
    function column_templatename( $item ) {
        
        $colStr = '';
        if (isset($item['name'])){
            $colStr = '<a href="post.php?post='.$item['id'].'&action=edit"><strong>'.$item['name'].'</strong></a>';
                    }
        $colStr .= ' (<span>#'.$item['id'].'</span>)';

                if (isset($item['zbsdefault']) && $item['zbsdefault'] == 1){

            $colStr .= '<br />(Default Template)';

        }

        return $colStr;

    }


    
    function column_quotetemplateno( $item ) {
        
        return '<a href="post.php?post='.$item['id'].'&action=edit">'.$item['id'].'</a>';

    }

    function column_date( $item ) {

        $d = $item['created'];

        
        
        
        return $d;

    }



    
    

    
    function get_columns() {
        $columns = array(
                                    'templatename' => __w( 'Template', 'zerobscrm' ),
            'date' => __w( 'Created', 'zerobscrm' )
        );

        return $columns;
    }


    
    public function get_sortable_columns() {
        $sortable_columns = array(
                        'templatename' => array( 'templatename', true ),
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

        $per_page     = $this->get_items_per_page( 'quotetemplates_per_page', 10 );
        $current_page = $this->get_pagenum();
        $total_items  = self::record_count();

        $this->set_pagination_args( array(
            'total_items' => $total_items,             'per_page'    => $per_page         ) );

        $this->items = self::get_quotetemplates( $per_page, $current_page );

    }

    public function process_bulk_action() {

        
    }

}





function zeroBSCRM_render_quotetemplateslist_page(){

    $option = 'per_page';
    $args   = array(
        'label'   => 'Quote Templates',
        'default' => 10,
        'option'  => 'quotetemplates_per_page'
    );

    add_screen_option( $option, $args );
    
    $quotetemplateListTable = new zeroBSCRM_QuoteTemplate_List();
   
                        
                $normalLoad = true;
        
        
        
        if ($normalLoad){

                                    

            

                        $quotetemplateListTable->prepare_items();

            ?><div class="wrap">
                <h1>QuoteTemplates<?php 
                                        if ( zeroBSCRM_permsCustomers() ) {
                        echo ' <a href="' . esc_url( 'post-new.php?post_type=zerobs_quo_template' ) . '" class="page-title-action">' . esc_html( 'Add New' ) . '</a>';
                    }
                ?></h1>
                <?php 

                   

                $quotetemplateListTable->views(); ?>
                <?php  ?>
                <form method="post">
                    <?php  ?>
                    <?php $quotetemplateListTable->display(); ?>
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
