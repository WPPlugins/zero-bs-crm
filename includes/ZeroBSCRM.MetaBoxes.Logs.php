<?php /* !
 * Zero BS CRM
 * http://www.epicplugins.com
 * V1.1.19
 *
 * Copyright 2016, Epic Plugins, StormGate Ltd.
 *
 * Date: 25/10/16
 */



    if ( ! defined( 'ZEROBSCRM_PATH' ) ) exit;







    global $zeroBSCRM_logTypes; 
    $zeroBSCRM_logTypes = array(

        'zerobs_customer' => array(
        
                    'note'=> array("label" => "Note", "ico" => "fa-sticky-note-o"),
                    'call'=> array("label" => "Call", "ico" => "fa-phone-square"),
                    'email'=> array("label" => "Email", "ico" => "fa-envelope-o"),
                    'meeting'=> array("label" => "Meeting", "ico" => "fa-users"),
                    'quote__sent'=> array("label" => "Quote: Sent", "ico" => "fa-share-square-o"),
                    'quote__accepted'=> array("label" => "Quote: Accepted", "ico" => "fa-thumbs-o-up"),
                    'quote__refused'=> array("label" => "Quote: Refused", "ico" => "fa-ban"),
                    'invoice__sent'=> array("label" => "Invoice: Sent", "ico" => "fa-share-square-o"),
                    'invoice__part_paid'=> array("label" => "Invoice: Part Paid", "ico" => "fa-money"),
                    'invoice__paid'=> array("label" => "Invoice: Paid", "ico" => "fa-money"),
                    'invoice__refunded'=> array("label" => "Invoice: Refunded", "ico" => "fa-money"),
                    'transaction'=> array("label" => "Transaction", "ico" => "fa-credit-card"),
                    'tweet'=> array("label" => "Tweet", "ico" => "fa-twitter"),
                    'facebook_post'=> array("label" => "Facebook Post", "ico" => "fa-facebook-official"),
                    'created'=> array("locked" => true, "label" => "Created", "ico" => "fa-plus-circle"),
                    'updated'=> array("locked" => true,"label" => "Updated", "ico" => "fa-pencil-square-o"),
                    'quote_created'=> array("locked" => true,"label" => "Quote Created", "ico" => "fa-plus-circle"),
                    'invoice_created'=> array("locked" => true,"label" => "Invoice Created", "ico" => "fa-plus-circle"),
                    'transaction_created'=> array("locked" => true,"label" => "Transaction Created", "ico" => "fa-credit-card"),
                    'transaction_updated'=> array("locked" => true,"label" => "Transaction Updated", "ico" => "fa-credit-card"),
                    'form_filled'=> array("locked" => true,"label" => "Form Filled", "ico" => "fa-wpforms"),
                    'api_action'=> array("locked" => true,"label" => "API Action", "ico" => "fa-random")

        ),

        'zerobs_company' => array(
        
                    'note'=> array("label" => "Note", "ico" => "fa-sticky-note-o"),
                    'created'=> array("locked" => true,"label" => "Created", "ico" => "fa-plus-circle"),
                    'updated'=> array("locked" => true,"label" => "Updated", "ico" => "fa-pencil-square-o")

        ),

    );









    class zeroBS__Metabox_Logs {

        static $instance;
                private $postTypes;

        public function __construct( $plugin_file ) {
                                                   self::$instance = $this;
            
                                                $this->postTypes = array('zerobs_customer','zerobs_company');        
            add_action( 'add_meta_boxes', array( $this, 'initMetaBox' ) );

            
            add_filter( 'save_post', array( $this, 'save_meta_box' ), 10, 2 );
        }

        public function initMetaBox(){

            if (count($this->postTypes) > 0) foreach ($this->postTypes as $pt){

                                $callBackArr = array($this,$pt);

                add_meta_box(
                    'wpzbsc_logdetails_'.$pt,
                    'Activity Log',
                    array( $this, 'print_meta_box' ),
                    $pt,
                    'normal',
                    'high',
                    $callBackArr
                );

            }

        }
        

        public function print_meta_box( $post, $metabox ) {

                
            

                                $postType = ''; if (isset($metabox['args']) && isset($metabox['args'][1]) && !empty($metabox['args'][1])) $postType = $metabox['args'][1];

                                if (in_array($postType,array('zerobs_customer','zerobs_company'))){

                    
                                        $zbsLogs = zeroBSCRM_getLogs($post->ID,true);

            
            ?>
                <input type="hidden" name="meta_box_ids[]" value="<?php echo $metabox['id']; ?>" />
                <?php wp_nonce_field( 'save_' . $metabox['id'], $metabox['id'] . '_nonce' ); ?>
                <?php wp_nonce_field( 'save_zbslog', 'save_zbslog_nce' ); ?>
                <?php ?><script type="text/javascript">var zbscrmjs_logsSecToken = '<?php echo wp_create_nonce( "zbscrmjs-ajax-nonce-logs" ); ?>';</script><?php ?>

                <table class="form-table wh-metatab wptbp" id="wptbpMetaBoxLogs">
                    
                    <tr>
                        <td><h2><span id="zbsActiveLogCount"><?php echo zeroBSCRM_prettifyLongInts(count($zbsLogs)); ?></span> Logs</h2></td>
                        <td><button type="button" class="button button-primary button-large" id="zbscrmAddLog">Add Log</button></td>
                    </tr>

                    <!-- this line will pop/close with "add log" button -->
                    <tr id="zbsAddLogFormTR" style="display:none"><td colspan="2">


                        <div id="zbsAddLogForm">

                            <div id="zbsAddLogIco">
                                <!-- this will change with select changing... -->
                                <i class="fa fa-sticky-note-o" aria-hidden="true"></i>
                            </div>

                            <label for="zbsAddLogType">Activity Type:</label>
                            <select id="zbsAddLogType" class="form-control zbsUpdateTypeAdd">
                                <?php global $zeroBSCRM_logTypes; 
                                if (isset($zeroBSCRM_logTypes[$postType]) && count($zeroBSCRM_logTypes[$postType]) > 0) foreach ($zeroBSCRM_logTypes[$postType] as $logKey => $logType){

                                    ?><option><?php echo $logType['label']; ?></option><?php 
                                } 

                                

                                ?>
                            </select>

                            <br />

                            <label for="zbsAddLogMainDesc">Activity Description:</label>
                            <input type="text" class="form-control" id="zbsAddLogMainDesc" placeholder="e.g. 'Called and talked to Todd about service x, seemed keen'" autocomplete="off" />

                            <label for="zbsAddLogDetailedDesc">Activity Detailed Notes:</label>
                            <textarea class="form-control" id="zbsAddLogDetailedDesc" autocomplete="off"></textarea>

                            <div id="zbsAddLogActions">
                                <div id="zbsAddLogUpdateMsg"></div>
                                <button type="button" class="button button-info button-large" id="zbscrmAddLogCancel">Cancel</button>
                                <button type="button" class="button button-primary button-large" id="zbscrmAddLogSave">Save Log</button>
                            </div>

                        </div>



                        <!-- edit log form is to be moved about by edit routines :) -->
                        <div id="zbsEditLogForm">

                            <div id="zbsEditLogIco">
                                <!-- this will change with select changing... -->
                                <i class="fa fa-sticky-note-o" aria-hidden="true"></i>
                            </div>

                            <label for="zbsEditLogType">Activity Type:</label>
                            <select id="zbsEditLogType" class="form-control zbsUpdateTypeEdit" autocomplete="off">
                                <?php global $zeroBSCRM_logTypes; 
                                if (isset($zeroBSCRM_logTypes[$postType]) && count($zeroBSCRM_logTypes[$postType]) > 0) foreach ($zeroBSCRM_logTypes[$postType] as $logKey => $logType){

                                    ?><option><?php echo $logType['label']; ?></option><?php 
                                } 

                                

                                ?>
                            </select>

                            <br />

                            <label for="zbsEditLogMainDesc">Activity Description:</label>
                            <input type="text" class="form-control" id="zbsEditLogMainDesc" placeholder="e.g. 'Called and talked to Todd about service x, seemed keen'" autocomplete="off" />

                            <label for="zbsEditLogDetailedDesc">Activity Detailed Notes:</label>
                            <textarea class="form-control" id="zbsEditLogDetailedDesc" autocomplete="off"></textarea>

                            <div id="zbsEditLogActions">
                                <div id="zbsEditLogUpdateMsg"></div>
                                <button type="button" class="button button-info button-large" id="zbscrmEditLogCancel">Cancel</button>
                                <button type="button" class="button button-primary button-large" id="zbscrmEditLogSave">Save Log</button>
                            </div>

                        </div>




                    </td></tr>

                    <?php if (isset($wDebug)) { ?><tr><td colspan="2"><pre><?php print_r($zbsLogs) ?></pre></td></tr><?php } ?>

                    <tr><td colspan="2">

                        <?php 
                            
                        ?>
                        <div id="zbsAddLogOutputWrap"></div>

                    </td></tr>

                </table>


            <style type="text/css">
                #submitdiv {
                    display:none;
                }
            </style>
            <script type="text/javascript">

                var zbsLogAgainstID = <?php echo $post->ID; ?>; var zbsLogProcessingBlocker = false;

                <?php 
                                global $zeroBSCRM_logTypes; 

                                $lockedLogs = array();
                if (isset($zeroBSCRM_logTypes[$postType]) && count($zeroBSCRM_logTypes[$postType]) > 0) foreach ($zeroBSCRM_logTypes[$postType] as $logTypeKey => $logTypeDeet){
                    if (isset($logTypeDeet['locked']) && $logTypeDeet['locked']) $lockedLogs[$logTypeKey] = true;
                }
                echo 'var zbsLogsLocked = '.json_encode($lockedLogs).';';

                 

                if (isset($zeroBSCRM_logTypes[$postType]) && count($zeroBSCRM_logTypes[$postType]) > 0) {

                    echo 'var zbsLogTypes = '.json_encode($zeroBSCRM_logTypes[$postType]).';';

                } 

                 ?>

                var zbsLogIndex = <?php

                                        if (count($zbsLogs) > 0 && is_array($zbsLogs)) {
                      
                        $zbsLogsExpose = array();
                        foreach ($zbsLogs as $zbsLog){

                            $retLine = $zbsLog;
                            if (isset($retLine['meta']) && isset($retLine['meta']['longdesc'])) $retLine['meta']['longdesc'] = nl2br(zeroBSCRM_textExpose($retLine['meta']['longdesc']));

                            $zbsLogsExpose[] = $retLine;

                        }

                        echo json_encode($zbsLogsExpose);
                    } else
                        echo json_encode(array());

                ?>;

                var zbsLogEditing = -1;

                // def ico
                var zbsLogDefIco = 'fa-sticky-note-o'; 

                jQuery(document).ready(function(){

                    // build log ui
                    zbscrmjs_buildLogs();

                    // add log button
                    jQuery('#zbscrmAddLog').click(function(){


                        if (jQuery(this).css('display') == 'block'){

                            jQuery('#zbsAddLogFormTR').slideDown('400', function() {
                                
                            });

                            jQuery(this).hide();

                        } else {

                            jQuery('#zbsAddLogFormTR').hide();

                            jQuery(this).show();

                        }


                    });

                    // cancel
                    jQuery('#zbscrmAddLogCancel').click(function(){

                            jQuery('#zbsAddLogFormTR').hide();

                            jQuery('#zbscrmAddLog').show();

                    });

                    // save
                    jQuery('#zbscrmAddLogSave').click(function(){

                            //jQuery('#zbsAddLogFormTR').hide();

                            //jQuery('#zbscrmAddLog').show();

                            /* 
                            zbsnagainstid
                            zbsntype
                            zbsnshortdesc            
                            zbsnlongdesc
                            zbsnoverwriteid
                            */

                            // get / check data
                            var data = {sec:window.zbscrmjs_logsSecToken}; var errs = 0;
                            if ((jQuery('#zbsAddLogType').val()).length > 0) data.zbsntype = jQuery('#zbsAddLogType').val();
                            if ((jQuery('#zbsAddLogMainDesc').val()).length > 0) data.zbsnshortdesc = jQuery('#zbsAddLogMainDesc').val();
                            if ((jQuery('#zbsAddLogDetailedDesc').val()).length > 0) 
                                data.zbsnlongdesc = jQuery('#zbsAddLogDetailedDesc').val();
                            else
                                data.zbsnlongdesc = '';

                            // post id & no need for overwrite id as is new
                            data.zbsnagainstid = parseInt(window.zbsLogAgainstID);

                            // debug console.log('posting new note: ',data);

                            // validate
                            var msgOut = '';
                            if (typeof data.zbsntype == "undefined" || data.zbsntype == '') {
                                errs++;
                                msgOut = 'Note Type is required!'; 
                                jQuery('#zbsAddLogType').css('border','2px solid orange');
                                setTimeout(function(){

                                    jQuery('#zbsAddLogUpdateMsg').html('');
                                    jQuery('#zbsAddLogType').css('border','1px solid #ddd');

                                },1500);
                            }
                            if (typeof data.zbsnshortdesc == "undefined" || data.zbsnshortdesc == '') {
                                errs++;
                                if (msgOut == 'Note Type is required!') 
                                    msgOut = 'Note Type and Description are required!'; 
                                else
                                    msgOut += 'Note Description is required!'; 
                                jQuery('#zbsAddLogMainDesc').css('border','2px solid orange');
                                setTimeout(function(){

                                    jQuery('#zbsAddLogUpdateMsg').html('');
                                    jQuery('#zbsAddLogMainDesc').css('border','1px solid #ddd');

                                },1500);
                            }

                            if (errs === 0){

                                // add action
                                data.action = 'zbsaddlog';

                                zbscrmjs_addNewNote(data,function(newLog){

                                    // success

                                        // msg
                                        jQuery('#zbsAddLogUpdateMsg').html('Saved!');

                                        // then hide form, build new log gui, clear form

                                            // hide + clear form
                                            jQuery('#zbsAddLogFormTR').hide();
                                            jQuery('#zbscrmAddLog').show();
                                            jQuery('#zbsAddLogType').val('Note');
                                            jQuery('#zbsAddLogMainDesc').val('');
                                            jQuery('#zbsAddLogDetailedDesc').val('');
                                            jQuery('#zbsAddLogUpdateMsg').html('');

                                        // add it (build example obj)
                                        var newLogObj = {
                                            id: newLog.logID,
                                            created: '', //moment(),
                                            meta: {

                                                type: newLog.zbsntype,
                                                shortdesc: newLog.zbsnshortdesc,
                                                longdesc: zbscrmjs_nl2br(newLog.zbsnlongdesc)

                                            }
                                        }
                                        zbscrmjs_addNewNoteLine(newLogObj,true);

                                        // also add to window obj
                                        window.zbsLogIndex.push(newLogObj);


                                        // bind ui
                                        setTimeout(function(){
                                            zbscrmjs_bindNoteUIJS();
                                            zbscrmjs_updateLogCount();
                                        },0);


                                },function(){

                                    // failure

                                        // msg + do nothing
                                        jQuery('#zbsAddLogUpdateMsg').html('There was an error when saving this note!');

                                });

                            } else {
                                if (typeof msgOut !== "undefined" && msgOut != '') jQuery('#zbsAddLogUpdateMsg').html(msgOut); 
                            }

                    });


                    // note ico - works for both edit + add
                    jQuery('#zbsAddLogType, #zbsEditLogType').change(function(){

                        // get perm
                        var logPerm = zbscrmjs_permify(jQuery(this).val()); // jQuery('#zbsAddLogType').val()

                        var thisIco = window.zbsLogDefIco;
                        // find ico
                        if (typeof window.zbsLogTypes[logPerm] != "undefined") thisIco = window.zbsLogTypes[logPerm].ico;

                        // override all existing classes with ones we want:
                        if (jQuery(this).hasClass('zbsUpdateTypeAdd')) jQuery('#zbsAddLogIco i').attr('class','fa ' + thisIco);
                        if (jQuery(this).hasClass('zbsUpdateTypeEdit')) jQuery('#zbsEditLogIco i').attr('class','fa ' + thisIco);

                    });


                });

                function zbscrmjs_updateLogCount(){

                    var count = 0; 
                    if (window.zbsLogIndex.length > 0) count = parseInt(window.zbsLogIndex.length);
                    jQuery('#zbsActiveLogCount').html(zbscrmjs_prettifyLongInts(count));

                }

                // build log ui
                function zbscrmjs_buildLogs(){

                    // get from obj
                    var theseLogs = window.zbsLogIndex;


                    jQuery.each(theseLogs,function(ind,ele){

                        zbscrmjs_addNewNoteLine(ele);

                    });

                    // bind ui
                    setTimeout(function(){
                        zbscrmjs_bindNoteUIJS();
                        zbscrmjs_updateLogCount();
                    },0);

                }

                function zbscrmjs_addNewNoteLine(ele,prepflag,replaceExisting){

                        // localise
                        var logMeta = ele.meta;

                        // get perm
                        var logPerm = zbscrmjs_permify(logMeta.type);

                        // build it
                        var thisLogHTML = '<div class="zbsLogOut" data-logid="' + ele.id + '" id="zbsLogOutLine' + ele.id + '" data-logtype="' + logPerm + '">';


                            // type ico
                                
                                var thisIco = window.zbsLogDefIco;
                                // find ico
                                if (typeof window.zbsLogTypes[logPerm] != "undefined") thisIco = window.zbsLogTypes[logPerm].ico;

                                // output
                                thisLogHTML += '<div class="zbsLogOutIco"><i class="fa ' + thisIco + '" aria-hidden="true"></i></div>';


                            // created date
                            if (typeof ele.created !== "undefined" && ele.created !== '') {
                                
                                // not req: var offsetStr = zbscrmjs_getTimeZoneOffset();
                                // date, inc any timezone offset as set in wp: zbsCRMTimeZoneOffset
                                //console.log("Creating moment bare",[moment(ele.created + ' ' + offsetStr, 'YYYY-MM-DD HH:mm:ss Z'), moment(ele.created + ' ' + offsetStr, 'YYYY-MM-DD HH:mm:ss Z','en'), offsetStr, moment(ele.created, 'YYYY-MM-DD HH:mm:ss').utcOffset(offsetStr)]);
                                //var createdMoment = moment(ele.created + ' ' + offsetStr, 'YYYY-MM-DD HH:mm:ss Z', 'en');
                                //var createdMoment = moment(ele.created, 'YYYY-MM-DD HH:mm:ss').utcOffset(offsetStr);
                                //var nowAdjusted = moment(); //.utcOffset(offsetStr);
                                //console.log("compare",[createdMoment.format(),nowAdjusted.format(),createdMoment.from(nowAdjusted),createdMoment.fromNow()]);

                                // this works best in the end, just add / - any offset
                                var createdMoment = moment(ele.created, 'YYYY-MM-DD HH:mm:ss').add(window.zbsCRMTimeZoneOffset, 'h');
                                thisLogHTML += '<div class="zbsLogOutCreated" data-zbscreated="' + ele.created + '" title="' + createdMoment.format('llll') + '">' + createdMoment.fromNow() + '</div>';

                            } else {

                                // empty created means just created obj
                                var createdMoment = moment();
                                thisLogHTML += '<div class="zbsLogOutCreated" data-zbscreated="' + createdMoment + '" title="' + createdMoment.format('llll') + '">' + createdMoment.fromNow() + '</div>';

                            }

                            // title

                                var thisTitle = '';
                                // type
                                if (typeof logMeta.type !== "undefined") thisTitle += '<span>' + logMeta.type + '</span>';
                                // desc
                                if (typeof logMeta.shortdesc !== "undefined") {

                                    if (thisTitle != '') thisTitle += ': ';
                                    thisTitle += logMeta.shortdesc;

                                }

                                var logEditElements = '<div class="zbsLogOutEdits"><i class="fa fa-pencil-square-o zbsLogActionEdit" title="Edit Log"></i><i class="fa fa-trash-o zbsLogActionRemove last" title="Delete Log"></i></div>';
                                thisLogHTML += '<div class="zbsLogOutTitle">' + thisTitle + logEditElements + '</div>';

                            // desc
                           if (typeof logMeta.longdesc !== "undefined" && logMeta.longdesc !== '' && logMeta.longdesc !== null) thisLogHTML += '<div class="zbsLogOutDesc">' + logMeta.longdesc + '</div>';

                            thisLogHTML += '</div>';


                        if (typeof replaceExisting == "undefined"){

                            // normal

                            // add it
                            if (typeof prepflag !== "undefined")
                                jQuery('#zbsAddLogOutputWrap').prepend(thisLogHTML);
                            else
                                jQuery('#zbsAddLogOutputWrap').append(thisLogHTML);


                        } else {

                            // replace existing
                            jQuery('#zbsLogOutLine' + ele.id).replaceWith(thisLogHTML);

                        }

                }

                function zbscrmjs_bindNoteUIJS(){

                    // show hide edit controls
                    jQuery('.zbsLogOut').mouseenter(function(){

                        // check if locked log or not! 
                        var logType = jQuery(this).attr('data-logtype');
                        if (typeof logType == "undefined") logType = '';

                        // if log type empty, or has a key in window.zbsLogsLocked, don't allow edits
                        if (logType == '' || window.zbsLogsLocked.hasOwnProperty(logType)){

                            // nope

                        } else {
                                
                            // yep
                            jQuery('.zbsLogOutEdits',jQuery(this)).css('display','inline-block');

                        }

                    }).mouseleave(function(){

                        jQuery('.zbsLogOutEdits',jQuery(this)).not('.stayhovered').css('display','none');

                    });

                    // bind del
                    jQuery('.zbsLogOutEdits .zbsLogActionRemove').unbind('click').click(function(){

                        // append "deleting"
                        jQuery(this).closest('.zbsLogOutEdits').addClass('stayhovered').append('<span>Deleting...</span>');

                        var noteID = parseInt(jQuery(this).closest('.zbsLogOut').attr('data-logid'));

                        if (noteID > 0){

                            var thisEle = this;

                            zbscrmjs_deleteNote(noteID,function(){

                                // success

                                    // localise
                                    var nID = noteID;

                                    // append "deleted" and then vanish
                                    jQuery('span',jQuery(thisEle).closest('.zbsLogOutEdits')).html('Deleted!...');

                                    var that = thisEle;
                                    setTimeout(function(){

                                        // localise
                                        var thisNoteID = nID;

                                        // also del from window obj
                                        zbscrmjs_removeItemFromLogIndx(thisNoteID);

                                        // update count span
                                        zbscrmjs_updateLogCount();

                                        // slide up
                                        jQuery(that).closest('.zbsLogOut').slideUp(400,function(){

                                            // and remove itself?

                                        });
                                    },500);

                            },function(){

                                //TODO: proper error msg
                                console.error('There was an issue retrieving this note for editing/deleting'); 

                            });

                        } else console.error('There was an issue retrieving this note for editing/deleting'); //TODO: proper error msg

                    });

                    // bind edit
                    jQuery('.zbsLogOutEdits .zbsLogActionEdit').unbind('click').click(function(){

                        // one at a time please sir...
                        if (window.zbsLogEditing == -1){

                            // get edit id
                            var noteID = parseInt(jQuery(this).closest('.zbsLogOut').attr('data-logid'));

                            // get edit obj
                            var editObj = zbscrmjs_retrieveItemFromIndex(noteID);

                            // move edit box to before here
                            jQuery('#zbsEditLogForm').insertBefore('#zbsLogOutLine' + noteID);

                            setTimeout(function(){

                                var lObj = editObj.meta;

                                // update edit box texts etc.
                                jQuery('#zbsEditLogMainDesc').val(lObj.shortdesc);
                                jQuery('#zbsEditLogDetailedDesc').val(zbscrmjs_reversenl2br(lObj.longdesc));
                                jQuery('#zbsEditLogType option').each(function(){
                                    if (jQuery(this).text() == lObj.type) {
                                        jQuery(this).attr('selected', 'selected');
                                        return false;
                                    }
                                    return true;
                                });
                            
                                // type ico

                                    // get perm
                                    var logPerm = zbscrmjs_permify(lObj.type);
                                
                                    var thisIco = window.zbsLogDefIco;
                                    // find ico
                                    if (typeof window.zbsLogTypes[logPerm] != "undefined") thisIco = window.zbsLogTypes[logPerm].ico;

                                    // update
                                    jQuery('#zbsEditLogIco i').attr('class','fa ' + thisIco);


                            },10);

                            // set edit vars
                            window.zbsLogEditing = noteID;

                            // hide line / show edit
                            jQuery('#zbsLogOutLine' + noteID).slideUp();
                            jQuery('#zbsEditLogForm').slideDown();

                            // bind
                            zbscrmjs_bindEditNote();

                        }
                            

                    });

                }

                function zbscrmjs_bindEditNote(){


                        // cancel
                        jQuery('#zbscrmEditLogCancel').click(function(){

                                // get note id
                                var noteID = window.zbsLogEditing;

                                // hide edit from
                                jQuery('#zbsEditLogForm').hide();

                                // show back log
                                jQuery('#zbsLogOutLine' + noteID).show();

                                // unset noteID
                                window.zbsLogEditing = -1;

                        });

                        // save
                        jQuery('#zbscrmEditLogSave').click(function(){

                                if (window.zbsLogEditing > -1){

                                        // get note id
                                        var noteID = window.zbsLogEditing;

                                        //jQuery('#zbsEditLogFormTR').hide();

                                        //jQuery('#zbscrmEditLog').show();

                                        /* 
                                        zbsnagainstid
                                        zbsntype
                                        zbsnshortdesc            
                                        zbsnlongdesc
                                        zbsnoverwriteid
                                        */

                                        // get / check data
                                        var data = {sec:window.zbscrmjs_logsSecToken}; var errs = 0;

                                        // same as add code, but with note id:
                                        data.zbsnprevid = noteID;

                                        if ((jQuery('#zbsEditLogType').val()).length > 0) data.zbsntype = jQuery('#zbsEditLogType').val();
                                        if ((jQuery('#zbsEditLogMainDesc').val()).length > 0) data.zbsnshortdesc = jQuery('#zbsEditLogMainDesc').val();
                                        if ((jQuery('#zbsEditLogDetailedDesc').val()).length > 0) 
                                            data.zbsnlongdesc = jQuery('#zbsEditLogDetailedDesc').val();
                                        else
                                            data.zbsnlongdesc = '';

                                        // post id & no need for overwrite id as is new
                                        data.zbsnagainstid = parseInt(window.zbsLogAgainstID);

                                        // validate
                                        var msgOut = '';
                                        if (typeof data.zbsntype == "undefined" || data.zbsntype == '') {
                                            errs++;
                                            msgOut = 'Note Type is required!'; 
                                            jQuery('#zbsEditLogType').css('border','2px solid orange');
                                            setTimeout(function(){

                                                jQuery('#zbsEditLogUpdateMsg').html('');
                                                jQuery('#zbsEditLogType').css('border','1px solid #ddd');

                                            },1500);
                                        }
                                        if (typeof data.zbsnshortdesc == "undefined" || data.zbsnshortdesc == '') {
                                            errs++;
                                            if (msgOut == 'Note Type is required!') 
                                                msgOut = 'Note Type and Description are required!'; 
                                            else
                                                msgOut += 'Note Description is required!'; 
                                            jQuery('#zbsEditLogMainDesc').css('border','2px solid orange');
                                            setTimeout(function(){

                                                jQuery('#zbsEditLogUpdateMsg').html('');
                                                jQuery('#zbsEditLogMainDesc').css('border','1px solid #ddd');

                                            },1500);
                                        }


                                        if (errs === 0){

                                            // add action
                                            data.action = 'zbsupdatelog';

                                            zbscrmjs_updateNote(data,function(newLog){

                                                // success

                                                    // msg
                                                    jQuery('#zbsEditLogUpdateMsg').html('Changes Saved!');

                                                    // then hide form, build new log gui, clear form

                                                        // hide + clear form
                                                        jQuery('#zbsEditLogForm').hide();
                                                        jQuery('#zbsEditLogType').val('Note');
                                                        jQuery('#zbsEditLogMainDesc').val('');
                                                        jQuery('#zbsEditLogDetailedDesc').val('');
                                                        jQuery('#zbsEditLogUpdateMsg').html('');

                                                    // update it (build example obj)
                                                    var newLogObj = {
                                                        id: newLog.logID,
                                                        created: '', //  moment().subtract(window.zbsCRMTimeZoneOffset, 'h')
                                                        meta: {

                                                            type: newLog.zbsntype,
                                                            shortdesc: newLog.zbsnshortdesc,
                                                            // have to replace the nl2br for long desc:
                                                            longdesc: zbscrmjs_nl2br(newLog.zbsnlongdesc)

                                                        }
                                                    }
                                                    zbscrmjs_addNewNoteLine(newLogObj,true,true); // third param here is "replace existing"

                                                    // also add to window obj in prev place
                                                    //window.zbsLogIndex.push(newLogObj);
                                                    zbscrmjs_replaceItemInLogIndx(newLog.logID,newLogObj);


                                                    // bind ui
                                                    setTimeout(function(){
                                                        zbscrmjs_bindNoteUIJS();
                                                        zbscrmjs_updateLogCount();
                                                    },0);


                                            },function(){

                                                // failure

                                                    // msg + do nothing
                                                    jQuery('#zbsEditLogUpdateMsg').html('There was an error when saving this note!');

                                            });

                                        } else {
                                            if (typeof msgOut !== "undefined" && msgOut != '') jQuery('#zbsEditLogUpdateMsg').html(msgOut); 
                                        }


                                } // if note id

                        });


                }

                function zbscrmjs_removeItemFromLogIndx(noteID){

                    var logIndex = window.zbsLogIndex;
                    var newLogIndex = [];

                    jQuery.each(logIndex,function(ind,ele){

                        if (typeof ele.id != "undefined" && ele.id != noteID) newLogIndex.push(ele);

                    });

                    window.zbsLogIndex = newLogIndex;

                    // fini
                    return window.zbsLogIndex;

                }

                function zbscrmjs_replaceItemInLogIndx(noteIDToReplace,newObj){

                    var logIndex = window.zbsLogIndex;
                    var newLogIndex = [];

                    jQuery.each(logIndex,function(ind,ele){

                        if (typeof ele.id != "undefined")
                            if (ele.id != noteIDToReplace) 
                                newLogIndex.push(ele);
                            else
                                // is to replace
                                newLogIndex.push(newObj);

                    });

                    window.zbsLogIndex = newLogIndex;

                    // fini
                    return window.zbsLogIndex;

                }

                function zbscrmjs_retrieveItemFromIndex(noteID){

                    var logIndex = window.zbsLogIndex;
                    var logObj = -1;

                    jQuery.each(logIndex,function(ind,ele){

                        if (typeof ele.id != "undefined" && ele.id == noteID) logObj = ele;

                    });

                    return logObj;
                }

                

                // function assumes a legit dataArr :) (validate above)
                function zbscrmjs_addNewNote(dataArr,cb,errcb){
                    
                    // needs nonce. <!--#NONCENEEDED -->

                    if (!window.zbsLogProcessingBlocker){
                        
                        // blocker
                        window.zbsLogProcessingBlocker = true;

                        // msg
                        jQuery('#zbsAddLogUpdateMsg').html('Saving...');

                         // Send 
                            jQuery.ajax({
                                  type: "POST",
                                  url: ajaxurl, // admin side is just ajaxurl not wptbpAJAX.ajaxurl,
                                  "data": dataArr,
                                  dataType: 'json',
                                  timeout: 20000,
                                  success: function(response) {

                                    // Debug  console.log("RESPONSE",response);

                                    // blocker
                                    window.zbsLogProcessingBlocker = false;

                                    // this also has true/false on update... 
                                    if (typeof response.processed != "undefined" && response.processed){

                                        // callback
                                        // make a merged item... 
                                        var retArr = dataArr; dataArr.logID = response.processed;
                                        if (typeof cb == "function") cb(retArr);

                                    } else {

                                        // .. was an error :)

                                        // callback
                                        if (typeof errcb == "function") errcb(response);

                                    }


                                  },
                                  error: function(response){ 

                                    // Debug  console.error("RESPONSE",response);

                                    // blocker
                                    window.zbsLogProcessingBlocker = false;

                                    // callback
                                    if (typeof errcb == "function") errcb(response);



                                  }

                            });


                    } else {
                        
                        // end of blocker
                        jQuery('#zbsAddLogUpdateMsg').html('... already processing!');
                        setTimeout(function(){

                            jQuery('#zbsAddLogUpdateMsg').html('');

                        },2000);

                    }

                }

                // function assumes a legit dataArr :) (validate above)
                // is almost a clone of _addNote (homogenise later)
                function zbscrmjs_updateNote(dataArr,cb,errcb){
                    
                    // needs nonce. <!--#NONCENEEDED -->

                    if (!window.zbsLogProcessingBlocker){
                        
                        // blocker
                        window.zbsLogProcessingBlocker = true;

                        // msg
                        jQuery('#zbsEditLogUpdateMsg').html('Saving...');

                         // Send 
                            jQuery.ajax({
                                  type: "POST",
                                  url: ajaxurl, // admin side is just ajaxurl not wptbpAJAX.ajaxurl,
                                  "data": dataArr,
                                  dataType: 'json',
                                  timeout: 20000,
                                  success: function(response) {

                                    // Debug  console.log("RESPONSE",response);

                                    // blocker
                                    window.zbsLogProcessingBlocker = false;

                                    // this also has true/false on update... 
                                    if (typeof response.processed != "undefined" && response.processed){

                                        // callback
                                        // make a merged item... 
                                        var retArr = dataArr; dataArr.logID = response.processed;
                                        if (typeof cb == "function") cb(retArr);

                                    } else {

                                        // .. was an error :)

                                        // callback
                                        if (typeof errcb == "function") errcb(response);

                                    }


                                  },
                                  error: function(response){ 

                                    // Debug  console.error("RESPONSE",response);

                                    // blocker
                                    window.zbsLogProcessingBlocker = false;

                                    // callback
                                    if (typeof errcb == "function") errcb(response);



                                  }

                            });


                    } else {
                        
                        // end of blocker
                        jQuery('#zbsEditLogUpdateMsg').html('... already processing!');
                        setTimeout(function(){

                            jQuery('#zbsEditLogUpdateMsg').html('');

                        },2000);

                    }

                }


                // function assumes a legit noteID + perms :) (validate above)
                function zbscrmjs_deleteNote(noteID,cb,errcb){
                    
                    // needs nonce. <!--#NONCENEEDED -->

                    if (!window.zbsLogProcessingBlocker){
                        
                        // blocker
                        window.zbsLogProcessingBlocker = true;

                        // -package
                        var dataArr = {
                            action : 'zbsdellog',
                            zbsnid : noteID,
                            sec:window.zbscrmjs_logsSecToken
                        };

                         // Send 
                            jQuery.ajax({
                                  type: "POST",
                                  url: ajaxurl, // admin side is just ajaxurl not wptbpAJAX.ajaxurl,
                                  "data": dataArr,
                                  dataType: 'json',
                                  timeout: 20000,
                                  success: function(response) {

                                    // Debug  console.log("RESPONSE",response);

                                    // blocker
                                    window.zbsLogProcessingBlocker = false;

                                    // this also has true/false on update... 
                                    if (typeof response.processed != "undefined" && response.processed){

                                        // Debug console.log("SUCCESS");

                                        // callback
                                        if (typeof cb == "function") cb(response);

                                    } else {

                                        // .. was an error :)
                                        // Debug console.log("ERRZ");                                    

                                        // callback
                                        if (typeof errcb == "function") errcb(response);

                                    }


                                  },
                                  error: function(response){ 

                                    // Debug  console.error("RESPONSE",response);

                                    // blocker
                                    window.zbsLogProcessingBlocker = false;

                                    // callback
                                    if (typeof errcb == "function") errcb(response);



                                  }

                            });


                    } else {
                        
                        // end of blocker

                    }

                }







            </script>
             
            <input type="hidden" name="<?php echo $metabox['id']; ?>_fields[]" value="subtitle_text" />


            <?php


            } 
        }

        public function save_meta_box( $post_id, $post ) {
            if( empty( $_POST['meta_box_ids'] ) ){ return; }
            foreach( $_POST['meta_box_ids'] as $metabox_id ){
                if(!isset($_POST[ $metabox_id . '_nonce' ]) ||  ! wp_verify_nonce( $_POST[ $metabox_id . '_nonce' ], 'save_' . $metabox_id ) ){ continue; }
                                if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){ continue; }

                if( $metabox_id == 'wpzbsc_logdetails'  && $post->post_type == $this->postType){

                    
                }
            }

            return $post;
        }
    }

    $zeroBS__Metabox_Logs = new zeroBS__Metabox_Logs( __FILE__ );






        define('ZBSCRM_INC_LOGSMB',true);