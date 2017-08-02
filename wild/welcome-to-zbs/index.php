<?php /* 


        .zbs-sync-ext
        .zbs-show-paysync
        .zbs-show-woosync
        .zbs-show-starterbundle
        .wizopt 
        .zbs-extrainfo 
        .switchBox
        .zbs-psst


        */
        global $zeroBSCRM_killDenied; $zeroBSCRM_killDenied = true;
    
        
         include( dirname(dirname(dirname(dirname(dirname( dirname ( __FILE__ ))) )))."/wp-config.php" );  

       global $zeroBSCRM_Settings; $settings = $zeroBSCRM_Settings->getAll();

?><!DOCTYPE html>
<html lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width">
	<title>Welcome to Zero BS CRM</title>
	<script type="text/javascript" src="./assets/jquery.min.js"></script>
	<script type="text/javascript" src="./assets/jquery.blockUI.min.js"></script>
	<script type="text/javascript" src="./assets/bootstrap.min.js"></script>
	<script type="text/javascript" src="./assets/wizard2.js"></script>
	<style type="text/css">img.wp-smiley,img.emoji{display:inline !important;border:none !important;box-shadow:none !important;height:1em !important;width:1em !important;margin:0 .07em !important;vertical-align:-0.1em !important;background:none !important;padding:0 !important}#zbscrm-logo img{max-width:20% !important}#feedbackPage{display:none}.zbscrm-setup .zbscrm-setup-actions .button-primary{background-color:#408bc9 !important;border-color:#408bc9 !important;-webkit-box-shadow:inset 0 1px 0 rgba(255,255,255,.25),0 1px 0 #408bc9 !important;box-shadow:inset 0 1px 0 rgba(255,255,255,.25),0 1px 0 #408bc9 !important;text-shadow:0 -1px 1px #408bc9,1px 0 1px #408bc9,0 1px 1px #408bc9,-1px 0 1px #408bc9 !important;float:right;margin:0;opacity:1}</style>
	<link rel="stylesheet" id="bs" href="./assets/bootstrap.min.css" type="text/css" media="all">
	<link rel="stylesheet" href="./assets/loadstyles.min.css" type="text/css" media="all">
	<link rel="stylesheet" id="open-sans-css" href="./assets/styles.css" type="text/css" media="all">
	<link rel="stylesheet" id="woocommerce_admin_styles-css" href="./assets/admin.min.css" type="text/css" media="all">
	<link rel="stylesheet" id="zbscrm-setup-css" href="./assets/zbs-exitform.css" type="text/css" media="all">
	<link rel="stylesheet" id="woocommerce-activation-css" href="./assets/activation.css" type="text/css" media="all">
	<link rel="stylesheet" id="wizzy" href="./assets/wizard.css" type="text/css" media="all">
	<style type="text/css" media="print">#wpadminbar { display:none; }</style>
    <script type="text/javascript">
        var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    </script>
    <style type="text/css">
    <?php ?>
        .zbscrm-setup-content {

            max-width: 800px;
            width: 800px;
            margin-left: auto;
            margin-right: auto;

        }
        @media (min-width: 992px) {
            .container {
                width: 100%;
            }
        }
        @media (min-width: 768px) {
            .container {
                width: 100%;
            }
        }


        .setup-content h3 {
            margin-top:10px !important;
        }
        .stepwizard-step {
            width:25%;
        }

        .wizopt {
            margin-bottom: 24px;
        }
        .zbs-menu-opt {
            width: 31%;
            display: inline-block;
            margin-left: 1%;
            text-align: center;
            padding:7px 0; padding-top:12px;
            margin-bottom:10px;
            border-radius: 4px;
        }
        .zbs-menu-opt:hover {
            cursor:pointer;
            background:#d9e5fb;
        }
        .zbs-menu-opt img {
            max-height:280px;
        }
        .zbs-menu-opt-desc {
            font-size: 16px;
            font-weight: 600;
            margin-top: 6px;
            margin-bottom: 6px;
        }
        .zbs-extrainfo {
            padding: 12px;
            background: #d9e5fb;
            border-radius: 4px;
            color: #000;
        }

        #zbs-lead-header{
            color:#CCC;
        }
        p.lead {
            font-size:18px;
            color:#000;
        }  
        .zbs-sync-wrap {

            padding: 12px;
            background: #d9e5fb;
            border-radius: 4px;
            color: #000;

        }
        .zbs-sync-img {
            text-align: center;         
            width: 50%;
            float:right;
            margin:10px;
        }
        .zbs-sync-wrap img {           
            width: 100%; 
            margin-left: auto;
            margin-right: auto;
        }
        .zbs-sync-wrap h3 {
            color:#000;
            margin-top: 8px !important;
            margin-bottom: 14px !important;
        }
        .zbs-sync-wrap p.lft {            
            width: 42%;
            margin-left: 16px;
            text-align: justify;
        }
        .zbs-sync-wrap .butt {
            text-align: center;
            margin: 10px 0 10px 0;
            padding-top: 16px;
        }
        .zbs-psst {
            padding: 12px;
            background: #d9e5fb;
            border-radius: 4px;
            color: #000;
            text-align: center
        }

        .zbs-extra {

            background: #2780e3;
            color: #ffce77 !important;
            padding: 5px 12px;
            border-radius: 4px;
            text-align: center;
       }
       .zbs-extra a {
            border-bottom: 2px #FFF dotted;
        }


        .switchbox-right {
            width:20%;
            float:right;
            text-align:right;
        }


        /*


        .zbs-sync-ext
        .zbs-show-paysync
        .zbs-show-woosync
        .zbs-show-starterbundle
        .wizopt 
        .zbs-extrainfo 
        .switchBox
        .zbs-psst


        */

    </style>
</head>
<body class="zbscrm-setup wp-core-ui">
			<h1 id="zbscrm-logo"><a href="https://zerobscrm.com" target="_blank"><img src="./assets/zbs_logo.png" alt="Zero BS CRM"></a></h1>
		<div class="zbscrm-setup-content" id="firstPage">
<div class="container">
<div class="stepwizard">
    <div class="stepwizard-row setup-panel">
        <div class="stepwizard-step">
            <a href="#step-1" type="button" class="btn btn-primary btn-circle">1</a>
            <p>Essentials</p>
        </div>
        <div class="stepwizard-step">
            <a href="#step-2" type="button" class="btn btn-default btn-circle">2</a>
            <p>Your Customers</p>
        </div>
        <div class="stepwizard-step">
            <a href="#step-3" type="button" class="btn btn-default btn-circle">3</a>
            <p>Which Power-ups?</p>
        </div>
        <div class="stepwizard-step">
            <a href="#step-4" type="button" class="btn btn-default btn-circle">4</a>
            <p>Finish</p>
        </div>
    </div>
</div>
    <div class="row setup-content" id="step-1">
        <div class="col-xs-12">
            <div class="col-md-12">
                <h3><?php _we('Essential Details','zerobscrm'); ?></h3>

                <div class="wizopt">

                    <label><?php _we('Company Name / CRM Name:','zerobscrm');?></label>
                    <p style="margin-bottom:0">This name will be shown at the top right of your CRM (as shown below). E.g. "Epic Freelance Ltd. CRM"</p>
                    <div style="width:90%;">
                        <div style="width:48%;float:left">                        
                            <input class='form-control' type="text" name="zbs_crm_name" id='zbs_crm_name' value="" placeholder="Name of your CRM (e.g Zero BS CRM)" style="width:90%" onchange = "zbs_crm_name_change();"/>
                        </div>
                        <div style="width:48%;float:right;overflow:hidden;border: 1px solid #ccc;" class='pos-rel'>
                            <img src="./assets/crm-name.png" alt="Zero BS CRM" id="crm-name-img" style="border:0;margin-bottom: 0;" />
                            <div id='crm-name'>Zero BS CRM</div>
                        </div>                        
                    </div>

                    <div class='clear'></div>
                
                </div>

                <div class="wizopt">
                    
                    <label><?php _we('What Currency should ZBS use?','zerobscrm');?></label>
                    <br/>
                    <select class='form-control' id='zbs_crm_curr' name='zbs_crm_curr'>
                        <?php                       

                                                        $currSetting = ''; if (isset($settings['currency']) && isset($settings['currency']['strval']) && $settings['currency']['strval']) $currSetting = $settings['currency']['strval'];

                            if (empty($currSetting)){
                             
                                                                $locale = get_locale(); 

                                if ($locale == 'en_US') $currSetting = 'USD';
                                if ($locale == 'en_GB') $currSetting = 'GBP';
                                
                            }

                                                        global $whwpCurrencyList;
                            if(!isset($whwpCurrencyList)) require_once(ZEROBSCRM_PATH . 'includes/wh.currency.lib.php');

                            

                        ?>
                        <option value="" disabled="disabled" selected="selected">Select...</option>
                        <?php foreach ($whwpCurrencyList as $currencyObj){ ?>
                            ?><option value="<?php echo $currencyObj[1]; ?>"<?php if ($currSetting == $currencyObj[1]) echo ' selected="selected"'; ?>><?php echo $currencyObj[0].' ('.$currencyObj[1].')'; ?></option>
                        <?php } ?>
                    </select>

                </div>

                <div class="wizopt">

                    <label><?php _we('What sort of business do you do?','zerobscrm');?></label>
                    <select class="form-control" id="zbs_crm_type" name="zbs_crm_type" onchange = "zbs_biz_select();">
                      <option value="" disabled="disabled" selected="selected">Select a type...</option>
                      <option value="Freelance">Freelance</option>
                      <option value="FreelanceDev">Freelance (Developer)</option>
                      <option value="FreelanceDes">Freelance (Design)</option>
                      <option value="SmallBLocal">Small Business: Local Service (e.g. Hairdresser)</option>
                      <option value="SmallBWeb">Small Business: Web Business</option>
                      <option value="SmallBOther">Small Business (Other)</option>
                      <option value="ecommerceWoo">eCommerce (WooCommerce)</option>
                      <option value="ecommerceShopify">eCommerce (Shopify)</option>
                      <option value="ecommerceOther">eCommerce (Other)</option>
                      <option value="Other">Other</option>
                    </select>
                    <label class='hide' id='zbs_other_label'><?php _we("Please let us know more details about how you intend to your Zero BS CRM so we can refine the product","zerobscrm"); ?></label>
                    <textarea class='form-control' name='zbs_other_details' id='zbs_other_details'></textarea>

                </div>


                <div class="wizopt">

                    <label><?php _we('ZBS Menu Style'); ?></label>
                    <p>Zero BS CRM can override the WordPress menu, or sit nicely amongst the existing options. Which of the following best suits your use?</p>

                    <div class="zbs-menu-opts">

                        <div class="zbs-menu-opt" data-select="zbs-menu-opt-choice-override">

                            <div class="zbs-menu-opt-porthole override">
                                <img src="<?php echo ZEROBSCRM_WILDURL; ?>welcome-to-zbs/assets/zbs-menu-override.png" alt="Override Menu" />
                            </div>
                            <div class="zbs-menu-opt-desc">Zero BS Override</div>
                            <input type="radio" name="zbs-menu-opt-choice" id="zbs-menu-opt-choice-override" value="override" checked="checked" />
                            <!-- zbs override mode + menu layout ZBS only -->

                        </div>

                        <div class="zbs-menu-opt" data-select="zbs-menu-opt-choice-slimline">

                            <div class="zbs-menu-opt-porthole slimline">
                                <img src="<?php echo ZEROBSCRM_WILDURL; ?>welcome-to-zbs/assets/zbs-menu-slimline.png" alt="Slim Menu" />
                            </div>
                            <div class="zbs-menu-opt-desc">Zero BS Slimline</div>
                            <input type="radio" name="zbs-menu-opt-choice" id="zbs-menu-opt-choice-slimline" value="slimline" />
                            <!-- zbs override mode off + menu layout ZBS Slimline-->

                        </div>

                        <div class="zbs-menu-opt" data-select="zbs-menu-opt-choice-full">

                            <div class="zbs-menu-opt-porthole full">
                                <img src="<?php echo ZEROBSCRM_WILDURL; ?>welcome-to-zbs/assets/zbs-menu-full.png" alt="Full WP Menu" />
                            </div>
                            <div class="zbs-menu-opt-desc">Zero BS &amp; WordPress</div>
                            <input type="radio" name="zbs-menu-opt-choice" id="zbs-menu-opt-choice-full" value="full" />
                            <!-- zbs override mode off + menu layout "Full"-->

                        </div>

                        <div class='clear'></div>

                    </div> 

                    <div class="zbs-extrainfo">
                        Override mode clears up the admin menu and hides 'posts', 'pages', etc.<br />
                        <em><strong>This is super useful if you intend to use the CRM on it's own domain (e.g. crm.yourdomain.com).</strong></em><br />
                        We recommend that you try this mode - you can change it in the ZBS CRM settings at any time.
                    </div>

                </div>


                <!-- for now keep lean, ignore b2b -->
                <div class="wizopt" style="display:none">

                    <label><?php _we('B2B Mode'); ?></label>
                    <p>Zero BS Can run in "B2B Mode", which allows you to manage "Contacts" under "Companies", instead of just "Customers". For most small businesses and freelancers, this isn't necessary.</p>
                    
                    <div>  
                        
                        <div class="switchBox">
                            <div class="switchBoxLabel">B2B Mode</div>
                            <div class="switchCheckbox">
                                <input type="checkbox" id="zbs_b2b" value="zbs_b2b" />
                                <label for="zbs_b2b"></label>
                            </div>
                        </div>                        

                    </div>

                </div>



                <div class="wizopt">

                    <label for="zbs_ess"><?php _we('Share essentials'); ?></label>

                    <div style="width:100%;">
                        <div style="width:25%;float:left;">
                            <div class='yesplsess'><p><?php _we('Share essentials'); ?> <input type="checkbox" id="zbs_ess" value="zbs_ess" checked='checked'/></p></div>
                        </div>
                        <div style="width:75%;float:right;">
                            <div class="zbs-extrainfo">
                                Sharing these essentials helps to build a better CRM that fits your needs. No confidential information is ever shared with us. Highly recommended.
                            </div>
                        </div>
                        <div class='clear'></div>
                    </div>

                </div>

                <hr />

                <div class='clear'></div>
                <button class="btn btn-primary nextBtn btn-lg pull-right" type="button" >Next</button>
            </div>
        </div>
    </div>


    <div class="row setup-content" id="step-2" style="display:none">
        <div class="col-xs-12">
            <div class="col-md-12">

                <!-- ingest -->
                <h3 id="zbs-lead-header"><span class="zbs-nob2b-show"><?php _we('Getting Customers into ZBS','zerobscrm'); ?></span><span class="zbs-b2b-show"><?php _we('Getting Contacts into ZBS','zerobscrm'); ?></span></h3>

                <p class="lead zbs-freelancer-lead">
                    <strong>Freelancing is hard enough without having to manage a customer list as well!</strong><br />
                    We freelanced for years before we started ZBS, so we feel your pain.<br /><br />
                    To make life easier we've built PayPal sync, which automatically pulls through all of your customers who've ever paid via PayPal, and keeps them up to date. 
                    If you're using PayPal for payments, this is a HUGE timesaver when combined with ZBS.
                </p>
                <p class="lead zbs-smallbiz-lead">
                    <strong>Running a small business is hard work...</strong><br />
                    It's busy. Time passes and you forget to add a customer detail... <em>Then when you need it, it's not there!</em>. We've run businesses for years, <strong>we feel your pain</strong>.<br /><br />
                    To make life easier we've built a few extensions which take a lot of the pain out of this. PayPal sync &amp; WooSync automatically pull through all customers, and then it keeps their details up to date. 
                    If you're using PayPal for payments, or WooCommerce for sales, this is a HUGE timesaver when combined with ZeroBS CRM.
                </p>
                <p class="lead zbs-ecomm-lead">
                    <strong>Running an ecommerce business is hard work...</strong><br />
                    We've tried our hands at eCommerce (with some success, and a chunk of fail), so we feel your pain.<br /><br />
                    To make life easier we've built a few extensions which take a lot of the pain out of this. PayPal sync &amp; WooSync automatically pull through all customers and transactions, and then it keeps their details up to date. 
                    If you're using PayPal for payments, or WooCommerce for sales, this is a HUGE timesaver when combined with ZeroBS CRM.
                </p>

                <div class="zbs-sync-ext">

                    <div class="zbs-show-paysync zbs-sync-wrap">

                        <div id="zbs-paysync-img" class="zbs-sync-img">
                            <a href="http://zerobscrm.com/product/paypal-sync/?utm_source=zbsplugin&utm_medium=welcomewiz" target="_blank"><img src="<?php echo ZEROBSCRM_WILDURL; ?>welcome-to-zbs/assets/paypal-sync.png" alt="PayPal Sync" /></a>
                        </div>

                        <h3>PayPal Sync (PRO)</h3>
                        <p class="lft"><em>PayPal Sync is amazingly easy to use.</em> In 5 minutes you can set up your PayPal API information and start your import. After that all PayPal sales automatically add to your CRM. <strong>Auto-pilot stuff, set and forget!</strong></p>
                        <!--<ul>
                            <li>Super quick install &amp; setup</li>
                            <li>Runs automatically after setup</li>
                            <li>Makes use of your existing PayPal Data</li>
                            <li>See which of your customers repeat buys &amp; identify "VIPs"</li>
                            <li>Metrics, too! (With optional <a href="http://zerobscrm.com/extensions/a-starter-bundle/?utm_source=zbsplugin&utm_medium=welcomewiz" target="_blank">Sales Dashboard Extension</a>)</li>
                        </ul>-->

                        <p class="butt"><a href="http://zerobscrm.com/product/paypal-sync/?utm_source=zbsplugin&utm_medium=welcomewiz" target="_blank" class="btn btn-warning btn-lg"><i class="fa fa-paypal" aria-hidden="true"></i> Get PayPal Sync</a></p>
                        <p style="text-align:center;margin-bottom:10px;"><em>Get PayPal Sync now and have your customers inside ZBS in 10 minutes time!</em></p>

                    </div>

                    <p style="text-align:center;padding:10px;font-size:16px;" class="zbs-show-paysync"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Sync is also available just for WooCommerce, in <a href="http://zerobscrm.com/product/woo-sync/?utm_source=zbsplugin&utm_medium=welcomewiz" target="_blank" style="color:#0073aa !important">Woo Sync</a>!</p>


                    <!-- removed this, too many options :) 2.0.2 wh optimise <hr class="zbs-show-woosync" />

                    <div class="zbs-show-woosync zbs-sync-wrap">

                        <div id="zbs-woosync-img" class="zbs-sync-img">
                            <a href="http://zerobscrm.com/product/woo-sync/?utm_source=zbsplugin&utm_medium=welcomewiz" target="_blank"><img src="<?php echo ZEROBSCRM_WILDURL; ?>welcome-to-zbs/assets/woo-sync.png" alt="WooSync" /></a>
                        </div>

                        <h3>WooCommerce Sync (PRO)</h3>
                        <p class="lft">The Woo Sync extension is great if you run a WooCommerce store. Import your customers and transactions into the CRM and manage everything in one place. Superb when used with Sales Dashboard and Mail Campaigns extensions.</p>
                        <ul>
                            <li>Super quick install &amp; setup</li>
                            <li>Runs automatically after setup</li>
                            <li>Centralises all of your customer data</li>
                            <li>See which of your customers repeat buys &amp; identify "VIPs"</li>
                            <li>Works with all forms of Payment via WooCommerce</li>
                            <li>Metrics, too! (With optional <a href="http://zerobscrm.com/extensions/a-starter-bundle/?utm_source=zbsplugin&utm_medium=welcomewiz" target="_blank">Sales Dashboard Extension</a>)</li>
                        </ul>

                        <p class="butt" style="padding-top:0;"><a href="http://zerobscrm.com/product/woo-sync/?utm_source=zbsplugin&utm_medium=welcomewiz" target="_blank" class="btn btn-warning btn-lg">Get Woo Sync</a></p>
                        <p style="text-align:center;margin-bottom:10px;"><em>Get PayPal Sync now and have your customers inside ZBS in 10 minutes time!</em></p>

                    </div>

                    -->

                    <hr />
                    <div class="zbs-show-starterbundle zbs-sync-wrap">

                        <div id="zbs-starterbundle-img" class="zbs-sync-img">
                            <a href="http://zerobscrm.com/product/a-starter-bundle/?utm_source=zbsplugin&utm_medium=welcomewiz" target="_blank"><img src="<?php echo ZEROBSCRM_WILDURL; ?>welcome-to-zbs/assets/starter-bundle.png" alt="Entrepreneur'sBundle" /></a>
                        </div>

                        <h3>Entrepreneur's Bundle</h3>
                        <p>Want all that juice, and then some? Well if you want the real <em>big daddy</em> kit, our bundles will knock your socks off.</p>
                        <!--<ul>
                            <li>Comes with PayPal Sync, Mail Campaigns, and Sales Dashboard extensions</li>
                            <li>Auto-import of customers &amp; orders</li>
                            <li>Check all your <em>EPIC</em> Metrics on one beautiful dash</li>
                            <li>Mail specific segments to make repeat sales</li>
                        </ul>-->

                        <p class="butt" style="padding-top:0;"><a href="http://zerobscrm.com/extensions/?utm_source=zbsplugin&utm_medium=welcomewiz" target="_blank" class="btn btn-warning btn-lg">Get Entrepreneur's Bundle</a></p>
                        <p style="text-align:center;margin-bottom:10px;"><em>Immediately tool up your CRM with PayPal Sync, Mail Campaigns, and Sales Dashboard (epic metrics!)</em></p>

                        

                    </div>

                    <hr />                    

                    <div class="zbs-psst">
                        Pssstt: Use the coupon code "WELCOMEWIZ" to get an extra $10 off
                    </div>
                    
                    <div class='clear'></div>

                </div>

                <hr />


                <div class='clear'></div>
                <button class="btn btn-primary nextBtn btn-lg pull-right" type="button" >Next</button>
            </div>
        </div>
    </div>

    <div class="row setup-content" id="step-3" style="display:none">
        <div class="col-xs-12">
            <div class="col-md-12">

                <h3>Optional Features</h3>

                <div class="wizopt">

                    <div class="switchbox-right">  
                        
                        <div class="switchBox">
                            <div class="switchCheckbox">
                                <input type="checkbox" id="zbs_quotes" value="zbs_quotes" checked="checked" />
                                <label for="zbs_quotes"></label>
                            </div>
                        </div>                        

                    </div>

                    <label><?php _we('Enable Quotes'); ?></label>
                    <p>Quotes (or proposals) are a super powerful part of Zero BS CRM. We recommend you use this feature, but if you don't want quotes you can turn it off here.</p>
                    

                </div>

                <hr />

                <div class="wizopt">

                    <div class="switchbox-right">  
                        
                        <div class="switchBox">
                            <div class="switchCheckbox">
                                <input type="checkbox" id="zbs_invoicing" value="zbs_invoicing" checked="checked" />
                                <label for="zbs_invoicing"></label>
                            </div>
                        </div>                        

                    </div>

                    <label><?php _we('Enable Invoices'); ?></label>
                    <p>You can run Zero BS with or without Invoicing. We recommend you use this though, as it's very useful (you can invoice online!)</p>
                    <p class="zbs-extra">(We also have a <a href="http://zerobscrm.com/product/invoicing-pro/?utm_source=zbsplugin&utm_medium=welcomewiz" target="_blank">Invoicing Pro Extension</a> if you want pay via PayPal and other extras.)</p>
                    

                </div>

                <hr />

                <div class="wizopt">

                    <div class="switchbox-right">  
                        
                        <div class="switchBox">
                            <div class="switchCheckbox">
                                <input type="checkbox" id="zbs_forms" value="zbs_forms" />
                                <label for="zbs_forms"></label>
                            </div>
                        </div>                        

                    </div>

                    <label><?php _we('Enable Forms'); ?></label>
                    <p>ZBS Forms allow you to embed customer forms into the front-end of your WordPress site. This is useful for lead generation. Recommendation: enable this if you want to collect leads via forms.</p>
                    <p class="zbs-extra">(We also have a <a href="http://zerobscrm.com/product/gravity-forms/?utm_source=zbsplugin&utm_medium=welcomewiz" target="_blank">Gravity Forms Extension</a> and a Contact Form 7 integration coming soon.)</p>
                    

                </div>

                <hr />

                <div class="wizopt">

                    <div class="zbs-extrainfo">
                        <strong>Hint:</strong> You can enable/disable any of these features via the "Extensions" page under Zero BS CRM from your WordPress admin area
                    </div>

                </div>


                <hr />

                <div class='clear'></div>

                <button class="btn btn-primary nextBtn btn-lg pull-right" type="button" >Next</button>
            </div>
        </div>
    </div>



    <div class="row setup-content" id="step-4" style="display:none">
        <div class="col-xs-12">
            <div class="col-md-12 laststage">
                <?php
                                global $current_user;
                wp_get_current_user();
                $fname  = $current_user->user_firstname;
                $lname  = $current_user->user_lastname;
                $em     = $current_user->user_email;
                ?>
                <h3><?php _we("Leverage your new CRM! (BONUSES)", "zerobscrm"); ?></h3>
                <!-- wh 2.0.2 optimising

                <p style="font-size:16px;margin-bottom:10px;">We are constantly working on making ZBS CRM the best it can be. Keep up to date with developments and learn how to get the most out of your CRM:</p>
                <ul style="color:#2780e3">
                    <li>Our short email course on how to get the most out of ZBS CRM, <em>including adding your first customer</em></li>
                    <li>The scoop on new features as we develop Zero BS CRM.</li>
                    <li>Coupons and discounts on our extensions</li>
                </ul>
                <p style="font-size:16px;color:#000"><strong>Join the Zero BS CRM community today:</strong><br />Where the pro's go, because they know ;) (Zero Bullshit, Useful Tools.)</p>
                -->
                <p style="font-size:16px;color:#e06d17"><strong>Join the Zero BS CRM community today:</strong><br />(Zero Bullshit, Bonuses, Critical update notifications.)</p>

                <p style="text-align:center">
                    <input type="hidden" id="zbs_crm_subblogname" name="zbs_crm_subblogname" value="<?php bloginfo('name'); ?>" />
                    <input class='form-control' style="width:40%;margin-right:5%;display:inline-block;font-size:15px;line-height:16px;" type="text" name="zbs_crm_first_name" id="zbs_crm_first_name" value="<?php echo $fname; ?>" placeholder="Type your first name..." />                    
                    <input class='form-control' style="width:40%;margin-right:5%;display:inline-block;font-size:15px;line-height:16px;"  type="text" name="zbs_crm_email" id="zbs_crm_email" value="<?php echo $em; ?>" placeholder="Enter your best email..." />

                    <input class='form-control' style="display:none !important"  type="text" name="zbs_crm_last_name" id="zbs_crm_last_name" value="<?php echo $lname; ?>" placeholder="And you last name..." />
                </p>

                <div class='clear'></div>
                <div class='yespls'><p style="text-align: center;margin-top: 6px;"><?php _we('Get updates.'); ?> <input type="checkbox" id="zbs_sub" value="zbs_sub" checked='checked'/></p></div>



                <hr />

                <div class='clear'></div>

                <button class="btn btn-primary btn-lg pull-right zbs-gogogo" type="button" >Next</button>
            </div>

            <div class="col-md-12 finishingupblock" style="display:none">
                <h3>Configuring your ZBS CRM</h3>
                <div style='text-align:center'>
                <img src="http://demo.zbscrm.com/_i/go.gif" alt="Zero BS CRM" style="margin:40px">
                <p>Just sorting out your new Zero BS CRM setup using the information you have provided, this shouldn't take a moment...</p>
                
                </div>
            </div>

            <div class="col-md-12 finishblock" style="display:none">
                <h3> Finished</h3>
                <div style='text-align:center'>
                <p>That’s it, you’re good to go. Get cracking with using your new CRM and rock on!</p>
                <img src="http://demo.zbscrm.com/_i/bear.gif" alt="Zero BS CRM">
                </div>
                <?php
                    global  $zeroBSCRM_slugs;
                    $loc = 'admin.php?page=' . $zeroBSCRM_slugs['settings'];
                    echo '<input type="hidden" name="zbswf-ajax-nonce" id="zbswf-ajax-nonce" value="' . wp_create_nonce( 'zbswf-ajax-nonce' ) . '" />';
                    echo '<input type="hidden" name="phf-finish" id="phf-finish" value="' . admin_url($loc) . '" />';  
                ?>
                <a class="btn btn-success btn-lg pull-right zbs-finito" href="<?php echo admin_url($loc); ?>">Finish and go to Settings!</a>
            </div>
        </div>
    </div>



</div>
</div>			
</body></html>