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


class zeroBSCRM_Company_List extends WP_List_Table {

        private $customViewArr = false;

    
    public function __construct() {

        parent::__construct( array(
            'singular' => __w( zeroBSCRM_getCompanyOrOrg(), 'zerobscrm' ),             'plural'   => __w( zeroBSCRM_getCompanyOrOrgPlural(), 'zerobscrm' ),             'ajax'     => false         ) );

    }


    
    public static function get_companies( $per_page = 10, $page_number = 1, $possibleSearchTerm = '' ) {

                        return zeroBS_getCompanies(true,$per_page,$page_number,$possibleSearchTerm);

    }


    
    public static function get_companies_filtered( $per_page = 10, $page_number = 1 ) {

                return false; 
    }


    
    public static function delete_company( $id ) {

                
    }


    
    public static function record_count() {
      
                        return zeroBS_getCompanyCount();

    }

    
    public static function record_count_filtered() {
      
                return 0; 
    }


    
    public function no_items() {
        _we( 'No '.zeroBSCRM_getCompanyOrOrgPlural().' avaliable.', 'zerobscrm' );
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

                case 'companyname':
                    return $this->zbsDefault_column_companyname($item);
                    break;
                case 'companyemail':
                    return $this->zbsDefault_column_companyemail($item);
                    break;
                case 'status':
                    return $this->zbsDefault_column_status($item);
                    break;
                case 'contactcount':
                    return $this->zbsDefault_column_contactcount($item);
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

    
    function zbsDefault_column_companyname( $item ) {
        
        $colStr = '';


        if (isset($item['meta'])){
            $colStr .= '<strong><a href="post.php?post='.$item['id'].'&action=edit">'.zeroBS_companyName($item['id'],$item['meta'],false,false).'</a></strong><br />';
            if (isset($item['meta']['addr1']) && isset($item['meta']['city']))
                                $colStr .= '<div>'.zeroBS_companyAddr($item['id'],$item['meta'],'short',', ').'</div>';
        }
        

            global $zeroBSCRM_Settings;
            $zbsShowID = $zeroBSCRM_Settings->get('showid');
            if ($zbsShowID == "1"){
                $colStr .= '<div>#'.$item['id'].'</div>';
            }


        return $colStr;

    }

    
    function zbsDefault_column_companyemail( $item ) {

                $email = ''; if (isset($item['meta']) && isset($item['meta']['email']) && !empty($item['meta']['email'])) $email = $item['meta']['email'];
        
        $colStr = '-';
        if (!empty($email)){
            $colStr = '<strong><a href="post.php?post='.$item['id'].'&action=edit">'.$email.'</a></strong><br />';
            $colStr .= '<a href="mailto:'.$email.'" target="_blank">Send Email</a>';
        }
        
        return $colStr;

    }


    function zbsDefault_column_contactcount( $item ) {
        
        $qc = 0;

        if (isset($item['contacts'])) $qc = count($item['contacts']);

        if ($qc > 0){

            return '<a href="edit.php?post_type=zerobs_customer&page=manage-customers&co='.$item['id'].'">'.zeroBSCRM_prettifyLongInts($qc).'</a>';

        }
        return 0;
        

    }

    
    function zbsDefault_column_status( $item ) {
        
        $status = '?'; $styles = '';

        if (isset($item['meta']) && isset($item['meta']['status'])){

            switch($item['meta']['status']){

                case 'Lead':
                    $status = $item['meta']['status'];
                    $styles = 'color:green';

                    break;
                case 'Company':
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


    function zbsDefault_column_added( $item ) {

        return date(zeroBSCRM_getDateFormat(),strtotime($item['created']));

    }



    
    function zbsDefault_column_name( $item ) {

        $delete_nonce = wp_create_nonce( 'tbp_delete_company' );

        $title = '<strong>' . $item['name'] . '</strong>';

        $actions = array(
            'delete' => sprintf( '<a href="?page=%s&action=%s&booking=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['id'] ), $delete_nonce )
        );

        return $title . $this->row_actions( $actions );
    }


    
    function get_columns() {


        
        $columns = array(
                        'companyname'    => __w( 'Name', 'zerobscrm' ),
            'status' => __w( 'Status', 'zerobscrm' ),
            'contactcount'    => __w( zeroBSCRM_getContactOrCustomer().' Count', 'zerobscrm' ),
            'added' => __w( 'Added', 'zerobscrm' )
        );

        
                        global $zeroBSCRM_Settings;
            $settings = $zeroBSCRM_Settings->getAll();

            $zbsShowID = $zeroBSCRM_Settings->get('showid');
            if ($zbsShowID == "1"){
                $columns['companyname']  = __w( 'Name & ID', 'zerobscrm' );
            }

                        if (isset($settings['customviews']) && isset($settings['customviews']['company'])){

                                $this->customViewArr = $settings['customviews']['company'];

                $columns = array();

                                if (count($settings['customviews']['company']) > 0) foreach ($settings['customviews']['company'] as $colname => $coldeets){

                                        $columns[$colname] = __w($coldeets[0],'zerobscrm');

                }                                                



            }


        return $columns;
    }


    
    public function get_sortable_columns() {
        $sortable_columns = array(
            'companyname' => array( 'companyname', true ),
            'contactcount' => array( 'contactcount', true ),
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

        $per_page     = $this->get_items_per_page( 'companys_per_page', 10 );
        $current_page = $this->get_pagenum();
        $total_items  = self::record_count();

        $this->set_pagination_args( array(
            'total_items' => $total_items,             'per_page'    => $per_page         ) );

                $possibleSearch = ''; if (isset($_POST['s'])) $possibleSearch = sanitize_text_field($_POST['s']);

        $this->items = self::get_companies( $per_page, $current_page, $possibleSearch);

    }

    
    

    public function process_bulk_action() {

        
    }

}





function zeroBSCRM_render_companyslist_page(){

    $option = 'per_page';
    $args   = array(
        'label'   => zeroBSCRM_getCompanyOrOrgPlural(),
        'default' => 10,
        'option'  => 'companys_per_page'
    );

    add_screen_option( $option, $args );

    $companyListTable = new zeroBSCRM_Company_List();

                        
                $normalLoad = true;
        
        
        
        if ($normalLoad){

                                    

            

                        $companyListTable->prepare_items();

            ?><div class="wrap">
                <h1><?php echo zeroBSCRM_getCompanyOrOrgPlural();
                                        if ( zeroBSCRM_permsCustomers() ) {
                        echo ' <a href="' . esc_url( 'post-new.php?post_type=zerobs_company' ) . '" class="page-title-action">' . esc_html( 'Add New' ) . '</a>';
                    }
                ?></h1>
                <?php 

                                        if (isset($_POST['s']) && !empty($_POST['s'])) {

                        $searchTerm = sanitize_text_field($_POST['s']);

                        echo '<div id="zbsSearchTerm">Searching: "'.$searchTerm.'" <button type="button" class="button" id="clearSearch">Cancel Search</button></div>';

                    }

                $companyListTable->views(); ?>
                <?php  ?>
                <form method="post">
                    <?php $companyListTable->search_box('Search '.zeroBSCRM_getCompanyOrOrgPlural(),'companysearch'); ?>
                    <?php $companyListTable->display(); ?>
                </form>
                <!--<br class="clear">-->
            </div>

                <script type="text/javascript">
                    jQuery(document).ready(function(){

                        jQuery('#clearSearch').click(function(){

                            jQuery('#companysearch-search-input').val('');
                            jQuery('#search-submit').click();

                        });

                    });
                </script>
                
            <?php

        }

}


