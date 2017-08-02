<?php /* !
 * Zero BS CRM
 * http://www.epicplugins.com
 * V1.0
 *
 * Copyright 2017, Epic Plugins, StormGate Ltd.
 *
 * Date: 07/03/2017
 */



    if ( ! defined( 'ZEROBSCRM_PATH' ) ) exit;




    
        add_action('admin_menu', 	'zeroBSCRM_CSVImporterLiteadmin_menu'); 

			$zeroBSCRM_CSVImporterconfigkey = 'csvimporterlite';
	$zeroBSCRM_extensions[] = $zeroBSCRM_CSVImporterconfigkey;

	global $zeroBSCRM_CSVImporterLiteslugs; $zeroBSCRM_CSVImporterLiteslugs = array();
	$zeroBSCRM_CSVImporterLiteslugs['app'] = 'zerobscrm-csvimporterlite-app';

	global $zeroBSCRM_CSVImporterLiteversion;
	$zeroBSCRM_CSVImporterLiteversion = '1.0';




function zeroBSCRM_CSVImporterLite_extended_upload ( $mime_types =array() ) {
  
   $mime_types['csv']  = "text/csv";
  
   return $mime_types;
}
  
add_filter('upload_mimes', 'zeroBSCRM_CSVImporterLite_extended_upload');


function zeroBSCRM_CSVImporterLiteadmin_menu() {

	global $zeroBSCRM_slugs,$zeroBSCRM_CSVImporterLiteslugs; 
	wp_register_style('zerobscrm-csvimporter-admcss', ZEROBSCRM_URL.'css/ZeroBSCRM.admin.csvimporter.min.css' );
    $csvAdminPage = add_submenu_page( $zeroBSCRM_slugs['datatools'], 'CSV Importer', 'CSV Importer', 'admin_zerobs_customers', $zeroBSCRM_CSVImporterLiteslugs['app'], 'zeroBSCRM_CSVImporterLitepages_app' );
	add_action( "admin_print_styles-{$csvAdminPage}", 'zeroBSCRM_CSVImporter_lite_admin_styles' );
	add_action( "admin_print_styles-{$csvAdminPage}", 'zeroBSCRM_global_admin_styles' );     

}
function zeroBSCRM_CSVImporter_lite_admin_styles(){
	wp_enqueue_style( 'zerobscrm-csvimporter-admcss' );
}



function zeroBSCRM_CSVImporterLitepages_header($subpage=''){

	global $wpdb, $zeroBSCRM_urls, $zeroBSCRM_CSVImporterLiteversion;		
	if (!current_user_can('admin_zerobs_customers'))  { wp_die( __w('You do not have sufficient permissions to access this page.','zerobscrm') ); }
    
    
?>
<div id="sgpBody">
    <div class="wrap"> 
	    <div id="icon-sb" class="icon32"><br /></div><h2><?php _e('CSV Importer Lite','zerobscrm'); if (!empty($subpage)) echo ': '.$subpage; ?></h2> 
    </div>
    <div id="sgpHeader">
		<a href="<?php echo $zeroBSCRM_urls['home']; ?>" title="Zero BS CRM Plugin Homepage" target="_blank">ZeroBSCRM.com</a> | 
		<?php if (!empty($zeroBSCRM_urls['support'])) { ?><a href="<?php echo $zeroBSCRM_urls['support']; ?>" title="Zero BS CRM Plugin <?php _we('Support Page','zerobscrm'); ?>" target="_blank"><?php _we('Support','zerobscrm'); ?></a> | <?php } ?>
		<?php if (!empty($zeroBSCRM_urls['docs'])) { ?><a href="<?php echo $zeroBSCRM_urls['docs']; ?>" title="View Documentation" target="_blank">Documentation</a> | <?php } ?>
		<?php if (!empty($zeroBSCRM_urls['products'])) { ?><a href="<?php echo $zeroBSCRM_urls['products']; ?>" title="<?php _we('More Extensions','zerobscrm'); ?>" target="_blank"><?php _we('Extension Store','zerobscrm'); ?></a> | <?php } ?>
		<?php if (!empty($zeroBSCRM_urls['extcsvimporterpro'])) { ?><a href="<?php echo $zeroBSCRM_urls['extcsvimporterpro']; ?>" title="<?php _we('UPGRADE','zerobscrm'); ?>" target="_blank" class="zbs-upgrade-label"><?php _we('Upgrade to CSV Importer PRO','zerobscrm'); ?></a> | <?php } ?>
	
        Version <?php echo $zeroBSCRM_CSVImporterLiteversion; ?>        
    </div>
    <div id="ZeroBSCRMAdminPage">
    <?php 	
	
			
}


function zeroBSCRM_CSVImporterLitepages_footer(){
    
	?></div><?php 	
	
}


function zeroBSCRM_CSVImporterLitepages_app() {
	
	global $wpdb, $zeroBSCRM_urls, $zeroBSCRM_CSVImporterLiteversion;		
	if (!current_user_can('admin_zerobs_customers'))  { wp_die( __w('You do not have sufficient permissions to access this page.','zerobscrm') ); }
    
	    
		zeroBSCRM_CSVImporterLitehtml_app(); 

		zeroBSCRM_CSVImporterLitepages_footer();

?>
</div>
<?php 
}


function zeroBSCRM_CSVImporterLitehtml_app(){

	global $zbsCustomerFields, $zeroBSCRM_CSVImporterLiteslugs,  $zeroBSCRM_urls;
		$settings = array('savecopy'=>false,'defaultcustomerstatus'=>'Customer'); 	$saveCopyOfCSVFile = false; 
						$stage = 0; if (isset($_POST['zbscrmcsvimpstage']) && !empty($_POST['zbscrmcsvimpstage']) && in_array($_POST['zbscrmcsvimpstage'],array(1,2,3))){ $stage = (int)$_POST['zbscrmcsvimpstage']; }
	$nonceOkay = true;

			
		    	if (! isset( $_POST['zbscrmcsvimportnonce'] ) || ! wp_verify_nonce( $_POST['zbscrmcsvimportnonce'], 'zbscrm_csv_import' )) {
    		$nonceOkay = false;
    	}

				if ($stage == 1){

						if (!$nonceOkay) {

								$stage = 0; 
				$stageError = 'There was an error uploading your CSV file. Please try again.';
			}

									if ( ! function_exists( 'wp_handle_upload' ) ) {
			    require_once( ABSPATH . 'wp-admin/includes/file.php' );
			}

			$uploadedfile = $_FILES['zbscrmcsvfile'];

			$upload_overrides = array( 'test_form' => false );

			$movefile = wp_handle_upload( $uploadedfile, $upload_overrides ); 
			if ( $movefile && ! isset( $movefile['error'] ) ) {
			    			    				
								$fileDetails = $movefile;

				
										$fileName = basename($fileDetails['file']);
					$fileURL = $fileDetails['url']; 					$extension = pathinfo($fileName, PATHINFO_EXTENSION);
					if (strtolower($extension) != "csv"){

												unlink( $fileDetails['file'] );
			    		
			    								$stage = 0; 
						$stageError = 'Your file is not a ".csv" file. If you are having continued problems please email support with a copy of your CSV file attached.';

					}
		
										if ($fileDetails['type'] != "text/csv") {

												unlink( $fileDetails['file'] );
			    		
			    								$stage = 0; 
						$stageError = 'Your file is not a correctly formatted CSV, please check your file format. If you are having continued problems please email support with a copy of your CSV file attached.';

					}

										$fullCSV = file_get_contents($fileDetails['file']);
										$csvLines = preg_split("/\\r\\n|\\r|\\n/", $fullCSV);
					if (count($csvLines) <= 0){
			    		
			    								$stage = 0; 
						$stageError = 'Your file does not appear to be a correctly formatted CSV File. We did not find any usable lines to import. If you are having continued problems please email support with a copy of your CSV file attached.';

					}


			} else {
			    
			    
			    				$stage = 0; 
				$stageError = $movefile['error'];
			}

		}

				if ($stage == 2){

						$ignoreFirstLine = false; if (isset($_POST['zbscrmcsvimpignorefirst'])) $ignoreFirstLine = true;

						if (!isset($_POST['zbscrmcsvimpf']) || !file_exists($_POST['zbscrmcsvimpf'])){

			    				$stage = 0;
				$stageError = 'There was an error maintaining a link to your uploaded CSV file. Please contact support if this error persists, quoting error code #457'; 
			} else {

								$csvFileLoca = $_POST['zbscrmcsvimpf'];
				$csvFileURL = $_POST['zbscrmcsvimpfurl'];

								$fullCSV = file_get_contents($csvFileLoca);
								$csvLines = preg_split("/\\r\\n|\\r|\\n/", $fullCSV);

								$totalCSVLines = count($csvLines);
				if ($ignoreFirstLine) $totalCSVLines--;

			}

						$fieldMap = array(); $realFields = 0;
			for ($fieldI = 1; $fieldI <= 30; $fieldI++){

								$mapTo = 'ignorezbs';

								if (isset($_POST['zbscrm-csv-fieldmap-'.$fieldI]) && !empty($_POST['zbscrm-csv-fieldmap-'.$fieldI]) && $_POST['zbscrm-csv-fieldmap-'.$fieldI] !== -1){

										$mapTo = sanitize_text_field($_POST['zbscrm-csv-fieldmap-'.$fieldI]);
					
										if ($mapTo != 'ignorezbs') $realFields++;

										$fieldMap[$fieldI] = $mapTo;

				} 

			}

			if ($realFields == 0) $stageError = 'No fields were matched. You cannot import customers without at least one field mapped to a customer attribute.';


		}

				if ($stage == 3){

						$ignoreFirstLine = false; if (isset($_POST['zbscrmcsvimpignorefirst'])) $ignoreFirstLine = true;

						if (!isset($_POST['zbscrmcsvimpf']) || !file_exists($_POST['zbscrmcsvimpf'])){

			    				$stage = 0;
				$stageError = 'There was an error maintaining a link to your uploaded CSV file. Please contact support if this error persists, quoting error code #457'; 
			} else {

								$csvFileLoca = $_POST['zbscrmcsvimpf'];
				$csvFileURL = $_POST['zbscrmcsvimpfurl'];
				$csvFileName = basename($csvFileLoca);

								$fullCSV = file_get_contents($csvFileLoca);
								$csvLines = preg_split("/\\r\\n|\\r|\\n/", $fullCSV);

			}

						$fieldMap = array(); $realFields = 0;
			for ($fieldI = 0; $fieldI <= 30; $fieldI++){

								$mapTo = 'ignorezbs';

								if (isset($_POST['zbscrm-csv-fieldmap-'.$fieldI]) && !empty($_POST['zbscrm-csv-fieldmap-'.$fieldI]) && $_POST['zbscrm-csv-fieldmap-'.$fieldI] !== -1){

										$mapTo = sanitize_text_field($_POST['zbscrm-csv-fieldmap-'.$fieldI]);
					
										if ($mapTo != 'ignorezbs') $realFields++;

										$fieldMap[$fieldI] = $mapTo;

				} 

			}

			if ($realFields == 0) {
				
								$stage = 0;
				$stageError = 'No fields were matched. You cannot import customers without at least one field mapped to a customer attribute.';

			}


		}



	switch ($stage){

		case 1:

						zeroBSCRM_CSVImporterLitepages_header('2. Map Fields');

			?><div class="zbscrm-csvimport-wrap">
				<h2>Map Columns from your CSV to Customer Fields</h2>
				<?php if (isset($stageError) && !empty($stageError)){ zeroBSCRM_html_msg(-1,$stageError); } ?>
				<div class="zbscrm-csv-map">
					<p class="zbscrm-csv-map-help">Your CSV File has been successfully uploaded. Before we can complete your import, you'll need to specify which field in your CSV file matches which field in ZBS.<br />You can do so by using the drop down options below:</p>
					<form method="post" class="zbscrm-csv-map-form">
						<input type="hidden" id="zbscrmcsvimpstage" name="zbscrmcsvimpstage" value="2" />
						<input type="hidden" id="zbscrmcsvimpf" name="zbscrmcsvimpf" value="<?php echo $fileDetails['file']; ?>" />
						<input type="hidden" id="zbscrmcsvimpfurl" name="zbscrmcsvimpfurl" value="<?php echo $fileDetails['url']; ?>" />
   						<?php wp_nonce_field( 'zbscrm_csv_import', 'zbscrmcsvimportnonce' ); ?>

						<hr />
						<div class="zbscrm-csv-map-ignorefirst">
							<label>Ignore first line of CSV file when running import.<br />(Use this if you have a "header line" in your CSV file.)</label>
							<input type="checkbox" id="zbscrmcsvimpignorefirst" name="zbscrmcsvimpignorefirst" value="1" />
						</div>
						<hr />

   						<?php 
   							   							   							$firstLine = $csvLines[0];
   							$firstLineParts = explode(",",$firstLine); 

   							   							$possibleFields = array();
   							foreach ($zbsCustomerFields as $fieldKey => $fieldDeets){
   								$possibleFields[$fieldKey] = $fieldDeets[1];
   							}

   							   							$indx = 1;
   							foreach ($firstLineParts as $userField){

   								   								if (substr($userField,0,1) == '"' && substr($userField,-1) == '"'){
   									$userField = substr($userField,1,strlen($userField)-2);
   								}
   								   								if (substr($userField,0,1) == "'" && substr($userField,-1) == "'"){
   									$userField = substr($userField,1,strlen($userField)-2);
   								}

   								?>
   								<div class="zbscrm-csv-map-field">
   									<span>Map:</span> <div class="zbscrm-csv-map-user-field">"<?php echo $userField; ?>"</div><br />
   									<div class="zbscrm-csv-map-zbs-field">
   										<span class="to">To:</span> <select name="zbscrm-csv-fieldmap-<?php echo $indx; ?>" id="zbscrm-csv-fieldmap-<?php echo $indx; ?>">
	   										<option value="-1" disabled="disabled">Select a Field</option>
	   										<option value="-1" disabled="disabled">==============</option>
	   										<option value="ignorezbs" selected="selected">Ignore This Field</option>
	   										<option value="-1" disabled="disabled">==============</option>
	   										<?php foreach ($possibleFields as $fieldID => $fieldTitle){ ?>
	   										<option value="<?php echo $fieldID; ?>"><?php echo $fieldTitle; ?></option>
	   										<?php } ?>
	   									</select>
	   								</div>
   								</div>
   								<?php

   								$indx++;

   							}



   						?>
   						<hr />
						<div style="text-align:center">
							<button type="submit" name="csv-map-submit" id="csv-map-submit" class="button button-primary button-large" type="submit">Continue</button>	
						</div>
					</form>
				</div>
			</div><?php


			break;
		case 2:

						zeroBSCRM_CSVImporterLitepages_header('3. Run Import');

						?><div class="zbscrm-csvimport-wrap">
				<h2>Complete Customer Import</h2>
				<?php if (isset($stageError) && !empty($stageError)){ zeroBSCRM_html_msg(-1,$stageError); } ?>
				<div class="zbscrm-confirmimport-csv">
					<p class="zbscrm-csv-help">Ready to run the import.<br />Please confirm the following is correct <i>before</i> continuing.<br /></p>
					<div style=""><?php echo zeroBSCRM_html_msg(1,'Note: There is no easy way to "undo" a CSV import, to remove any customers that have been added you will need to manually remove them.'); ?>
					<form method="post" enctype="multipart/form-data" class="zbscrm-csv-import-form">
						<input type="hidden" id="zbscrmcsvimpstage" name="zbscrmcsvimpstage" value="3" />
						<input type="hidden" id="zbscrmcsvimpf" name="zbscrmcsvimpf" value="<?php echo $csvFileLoca; ?>" />
						<input type="hidden" id="zbscrmcsvimpfurl" name="zbscrmcsvimpfurl" value="<?php echo $csvFileURL; ?>" />
   						<?php wp_nonce_field( 'zbscrm_csv_import', 'zbscrmcsvimportnonce' ); ?>
   						<h3>Import <?php echo zeroBSCRM_prettifyLongInts($totalCSVLines); ?> Customers</h3>
   						<hr />
	   					<?php if ($ignoreFirstLine){ ?>
	   					<p style="font-size:16px;text-align:center;">Ignore first line of CSV <i class="fa fa-check"></i></p>
	   					<hr />
						<input type="hidden" id="zbscrmcsvimpignorefirst" name="zbscrmcsvimpignorefirst" value="1" />
						<?php } ?>   						
   						<?php if ($realFields > 0){ ?>
	   						<p style="font-size:16px;text-align:center;">Map the following fields:</p>
	   						<?php

   							   							   							$firstLine = $csvLines[0];
   							$firstLineParts = explode(",",$firstLine); 

	   						foreach ($fieldMap as $fieldID => $fieldTarget){

	   							$fieldTargetName = $fieldTarget; if (isset($zbsCustomerFields[$fieldTarget]) && isset($zbsCustomerFields[$fieldTarget][1]) && !empty($zbsCustomerFields[$fieldTarget][1])) $fieldTargetName = $zbsCustomerFields[$fieldTarget][1];

	   							$fromStr = '';
	   							if (isset($firstLineParts[$fieldID-1])) $fromStr = $firstLineParts[$fieldID-1];

   								   								if (substr($fromStr,0,1) == '"' && substr($fromStr,-1) == '"'){
   									$fromStr = substr($fromStr,1,strlen($fromStr)-2);
   								}
   								   								if (substr($fromStr,0,1) == "'" && substr($fromStr,-1) == "'"){
   									$fromStr = substr($fromStr,1,strlen($fromStr)-2);
   								}



	   							?>
								<input type="hidden" id="zbscrm-csv-fieldmap-<?php echo ($fieldID-1); ?>" name="zbscrm-csv-fieldmap-<?php echo ($fieldID-1); ?>" value="<?php echo $fieldTarget; ?>" />
	   							<div class="zbscrm-impcsv-map">
	   								<div class="zbscrm-impcsv-from"><?php if (!empty($fromStr)) echo '"'.$fromStr.'"'; else echo 'Field #'.$fieldID; ?></div>
	   								<div class="zbscrm-impcsv-arrow"><?php if ($fieldTarget != "ignorezbs") echo '<i class="fa fa-long-arrow-right"></i>'; else echo '-'; ?></div>
	   								<div class="zbscrm-impcsv-to"><?php if ($fieldTarget != "ignorezbs") echo '"'.$fieldTargetName.'"'; else echo 'Ignore'; ?></div>
	   							</div><?php

	   						} 

	   						?>
   						<hr />
						<div style="text-align:center">
							<button type="submit" name="csv-map-submit" id="csv-map-submit" class="button button-primary button-large" type="submit">Run Import</button>	
						</div>
   						<?php } else { 
   							   							?><button type="button" class="button button-primary button-large" onclick="javascript:window.location='?page=<?php echo $zeroBSCRM_CSVImporterLiteslugs['app']; ?>';"><?php _we('Back','zerobscrm'); ?></button><?php 
   						} ?>
					</form>
				</div>
			</div><?php

			break;
		case 3:

						zeroBSCRM_CSVImporterLitepages_header('4. Import');


			?><div class="zbscrm-csvimport-wrap">
				<h2>Running Import...</h2>
				<?php if (isset($stageError) && !empty($stageError)){ zeroBSCRM_html_msg(-1,$stageError); } ?>
				<div class="zbscrm-final-stage">
					<div class="zbscrm-import-log">
						<div class="zbscrm-import-log-line">Loading CSV File "<?php echo $csvFileName; ?>"... <i class="fa fa-check"></i></div>
						<div class="zbscrm-import-log-line">Parsing rows... <i class="fa fa-check"></i></div>
						<div class="zbscrm-import-log-line">Beginning Import of <?php echo zeroBSCRM_prettifyLongInts(count($csvLines)); ?> rows... </div>
						<?php 

														$lineIndx = 0; $linesAdded = 0;
							if (count($csvLines) > 0) foreach ($csvLines as $line){

																if ($lineIndx == 0 && $ignoreFirstLine){

									echo '<div class="zbscrm-import-log-line">Skipping header row... <i class="fa fa-check"></i></div>';

								} else {

									   									$lineParts = explode(",",$line); 
	   								
   									   									$customerFields = array();
   									
	   								foreach ($fieldMap as $fieldID => $fieldTarget){

	   										   									$fieldIndx = $fieldID;

	   										   									if (
	   										
		   												   										isset($lineParts[$fieldIndx]) && !empty($lineParts[$fieldIndx]) &&

		   												   										$fieldTarget != "ignorezbs"

	   										) {

	   										$cleanUserField = trim($lineParts[$fieldIndx]);

			   											   								if (substr($cleanUserField,0,1) == '"' && substr($cleanUserField,-1) == '"'){
			   									$cleanUserField = substr($cleanUserField,1,strlen($cleanUserField)-2);
			   								}
			   											   								if (substr($cleanUserField,0,1) == "'" && substr($cleanUserField,-1) == "'"){
			   									$cleanUserField = substr($cleanUserField,1,strlen($cleanUserField)-2);
			   								}

			   								if ($cleanUserField == 'NULL') $cleanUserField = '';

	   									
	   											   										$customerFields['zbsc_'.$fieldTarget] = $cleanUserField;
	   									
	   									}


	   								}

	   									   								if (count($customerFields) > 0){

	   										   									$userUniqueID = md5($line.'#'.$csvFileName);

	   											   										if (isset($customerFields['zbsc_email']) && !empty($customerFields['zbsc_email'])) $userUniqueID = $customerFields['zbsc_email'];

	   										
	   										   									if (!isset($customerFields['zbsc_status'])) {
	   									
	   											   										if (isset($settings['defaultcustomerstatus']) && !empty($settings['defaultcustomerstatus'])) 
	   											$customerFields['zbsc_status'] = $settings['defaultcustomerstatus'];
	   										else
	   											$customerFields['zbsc_status'] = 'Customer';

	   									}

	   										   									$newCustID = zeroBS_integrations_addOrUpdateCustomer('csv',$userUniqueID,$customerFields);

	   									
	   										   									if (!empty($newCustID)) $linesAdded++;

	   																			echo '<div class="zbscrm-import-log-line">Successfully added customer #'.$newCustID.'... <i class="fa fa-user"></i><span>+1</span></div>';

	   								} else {

										echo '<div class="zbscrm-import-log-line">Skipping row (no usable fields)... <i class="fa fa-check"></i></div>';

	   								}

								}

								$lineIndx++;

							}	


														if (!$saveCopyOfCSVFile){

								if (file_exists($csvFileLoca)) unlink($csvFileLoca);
								echo '<div class="zbscrm-import-log-line">CSV Upload File Deleted... <i class="fa fa-check"></i></div>';

							} else {
								

							}

						?>
						<hr />
						<button type="button" class="button button-primary button-large" onclick="javascript:window.location='admin.php?page=zerobscrm-datatools';"><?php _we('Finish','zerobscrm'); ?></button>
					</div>
				</div>
			</div><?php


			break;
		default: 
						zeroBSCRM_CSVImporterLitepages_header('1. Upload');

						?><div class="zbscrm-csvimport-wrap">
				<h2>Import Customers from a CSV File</h2>
				<?php if (isset($stageError) && !empty($stageError)){ zeroBSCRM_html_msg(-1,$stageError); } ?>
				<div class="zbscrm-upload-csv">
					<p class="zbscrm-csv-import-help">If you have a CSV file of customers that you would like to import into ZBS, you can start the import wizard by uploading your .CSV file here.</p>
					<form method="post" enctype="multipart/form-data" class="zbscrm-csv-import-form">
						<input type="hidden" id="zbscrmcsvimpstage" name="zbscrmcsvimpstage" value="1" />
   						<?php wp_nonce_field( 'zbscrm_csv_import', 'zbscrmcsvimportnonce' ); ?>
						<label class="screen-reader-text" for="csvfile">.CSV file</label>
						<input type="file" id="zbscrmcsvfile" name="zbscrmcsvfile">
						<input type="submit" name="csv-file-submit" id="csv-file-submit" class="button" value="Start CSV Import Now">	
					</form>
				</div>
			</div><?php

						?>
			<hr style="margin-top:40px" />
			<div class="zbscrm-lite-notice">
				<h2>CSV Importer: Lite Version</h2>
				<p>If you would like to benefit from more features (such as logging your imports, automatically creating companies (B2B), and direct support etc. then please purchase a copy of our <a href="<?php echo $zeroBSCRM_urls['extcsvimporterpro']; ?>" target="_blank">CSV Importer PRO</a> extension).<br /><br /><a href="<?php echo $zeroBSCRM_urls['extcsvimporterpro']; ?>" target="_blank" class="button button-primary xl button-large">Get CSV Importer PRO</a></p>

			</div><?php

			break;


	}


}

define('ZBSCRM_INC_CSVIMPORTERLITE',1);
?>