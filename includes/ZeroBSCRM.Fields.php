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







    
                
            global $zbsFieldsEnabled;

        global $zbsFieldSorts; $zbsFieldSorts = array();

        global $zbsAddressFields;

        $zbsAddressFields = array(

            'addr1' => array('text','Address Line 1','','area'=>'Main Address'),
            'addr2' => array('text','Address Line 2','','area'=>'Main Address'),
            'city' => array('text','City','e.g. London','area'=>'Main Address'),
            'county' => array('text','County','e.g. Greater London','area'=>'Main Address'),
            'postcode' => array('text','Postcode','e.g. E1 9XJ','area'=>'Main Address')

        );

                $zbsFieldSorts['address'] = array(

                                'default' => array(
                        'addr1',
                        'addr2',
                        'city',
                        'county',
                        'postcode'
                    )

            );



    global $zbsCustomerFields;

        

        $zbsCustomerFields = array(

            'status' => array(
                'select', 'Status','',array(
                    'Lead','Customer','Refused','Blacklisted'
                ), 'essential' => true
            ),

            'prefix' => array('select','Prefix','',array(
                'Mr', 'Mrs', 'Ms', 'Miss', 'Dr', 'Prof','Mr & Mrs'
            ), 'essential' => true),
            'fname' => array('text','First Name','e.g. John', 'essential' => true),
            'lname' => array('text','Last Name','e.g. Doe', 'essential' => true),
                        'addr1' => array('text','Address Line 1','','area'=>'Main Address','migrate'=>'addresses'),
            'addr2' => array('text','Address Line 2','','area'=>'Main Address','migrate'=>'addresses'),
            'city' => array('text','City','e.g. London','area'=>'Main Address','migrate'=>'addresses'),
            'county' => array('text','County','e.g. Greater London','area'=>'Main Address','migrate'=>'addresses'),
            'postcode' => array('text','Postcode','e.g. E1 9XJ','area'=>'Main Address','migrate'=>'addresses'),

            'secaddr_addr1' => array('text','Address Line 1','','area'=>'Second Address','opt'=>'secondaddress','migrate'=>'addresses'),
            'secaddr_addr2' => array('text','Address Line 2','','area'=>'Second Address','opt'=>'secondaddress','migrate'=>'addresses'),
            'secaddr_city' => array('text','City','e.g. London','area'=>'Second Address','opt'=>'secondaddress','migrate'=>'addresses'),
            'secaddr_county' => array('text','County','e.g. Greater London','area'=>'Second Address','opt'=>'secondaddress','migrate'=>'addresses'),
            'secaddr_postcode' => array('text','Postcode','e.g. E1 9XJ','area'=>'Second Address','opt'=>'secondaddress','migrate'=>'addresses'),

            'hometel' => array('tel','Home Telephone','e.g. 01234 567 891'),
            'worktel' => array('tel','Work Telephone','e.g. 01234 567 891'),
            'mobtel' => array('tel','Mobile Telephone','e.g. 07123 580 543'),
            'email' => array('email','Email Address','e.g. john@yahoo.com', 'essential' => true),
            'notes' => array('textarea','Notes','')

        );


                $zbsFieldSorts['customer'] = array(

                                'default' => array(

                        'status',
                        'prefix',
                        'fname',
                        'lname',
                        
                        'addresses',                         'hometel',
                        'worktel',
                        'mobtel',
                        'email',
                        'notes'
                    )

            );




    global $zbsCompanyFields;
        
        $zbsCompanyFields = array(

            'status' => array(
                'select', 'Status','',array(
                    'Lead','Customer','Refused','Blacklisted'
                ), 'essential' => true
            ),

            'coname' => array('text','Name','e.g. Dell', 'essential' => true),

            'addr1' => array('text','Address Line 1','','area'=>'Main Address','migrate'=>'addresses'),
            'addr2' => array('text','Address Line 2','','area'=>'Main Address','migrate'=>'addresses'),
            'city' => array('text','City','e.g. London','area'=>'Main Address','migrate'=>'addresses'),
            'county' => array('text','County','e.g. Greater London','area'=>'Main Address','migrate'=>'addresses'),
            'postcode' => array('text','Postcode','e.g. E1 9XJ','area'=>'Main Address','migrate'=>'addresses'),

            'secaddr_addr1' => array('text','Address Line 1','','area'=>'Second Address','opt'=>'secondaddress','migrate'=>'addresses'),
            'secaddr_addr2' => array('text','Address Line 2','','area'=>'Second Address','opt'=>'secondaddress','migrate'=>'addresses'),
            'secaddr_city' => array('text','City','e.g. London','area'=>'Second Address','opt'=>'secondaddress','migrate'=>'addresses'),
            'secaddr_county' => array('text','County','e.g. Greater London','area'=>'Second Address','opt'=>'secondaddress','migrate'=>'addresses'),
            'secaddr_postcode' => array('text','Postcode','e.g. E1 9XJ','area'=>'Second Address','opt'=>'secondaddress','migrate'=>'addresses'),

            'maintel' => array('tel','Main Telephone','e.g. 01234 567 891'),
            'sectel' => array('tel','Secondary Telephone','e.g. 01234 567 891'),
            'email' => array('email','Main Email Address','e.g. helpdesk@dell.com'),
            'notes' => array('textarea','Notes','')

        );


                $zbsFieldSorts['company'] = array(

                                'default' => array(

                        'status',
                        'coname',
                        
                        'addresses',                         'maintel',
                        'sectel',
                        'mobtel',
                        'email',
                        'notes'
                    )

            );


    global $zbsCustomerQuoteFields;

        $zbsCustomerQuoteFields = array(

            'name' => array('text','Quote Title','e.g. New Website', 'essential' => true),
            'val'=> array('price','Quote Value','e.g. 500.00', 'essential' => true),
            'date' => array('date','Quote Date','', 'essential' => true),
            'notes' => array('textarea','Notes','')

        );


                $zbsFieldSorts['quote'] = array(

                                'default' => array(

                        'name',
                        'val',
                        'date', 
                        'notes'
                    )

            );


    global $zbsCustomerInvoiceFields;

        $zbsCustomerInvoiceFields = array(

            'status' => array(
                'select', 'Status','',array(
                    'Draft', 'Unpaid','Paid','Overdue'
                ), 'essential' => true
            ),

            
                        'no' => array('text','Invoice Number','e.g. 123456', 'essential' => true),             'val'=> array('hidden','Invoice Value','e.g. 500.00', 'essential' => true),
            'date' => array('date','Invoice Date','', 'essential' => true),
            'notes' => array('textarea','Notes',''),
            'ref' => array('text', 'Reference Number', 'e.g. Ref-123'),
            'due' => array('text', 'Invoice Due', ''),
            'logo' => array('text', 'logo url', 'e.g. URL'),

            'bill' => array('text','invoice to', 'e.g. mike@epicplugins.com'),
            'ccbill' => array('text','copy invoice to', 'e.g. you@you.com'),

        );


                $zbsFieldSorts['invoice'] = array(

                                'default' => array(

                        'status',
                        'no',
                        'date', 
                        'notes', 
                        'ref', 
                        'due', 
                        'logo', 
                        'bill', 
                        'ccbill'
                    )

            );


    global $zbsFormFields;

    $zbsFormFields = array(

        'header' => array('text', 'Header', 'Want to find out more'),
        'subheader' => array('text', 'Sub Header', 'Drop us a line. We follow up on all contacts'),
        'fname' => array('text', 'First Name Placeholder', 'First Name'),
        'lname' => array('text', 'Last Name Placeholder' , 'Last Name'),
        'email' => array('text', 'Email Placeholder', 'Email'),
        'notes' => array('text','Message Placeholder', 'Your Message'),
        'submit' => array('text', 'Submit Button', 'Submit'),
        'spam' => array('textarea', 'Spam Message', 'We will not send you spam. Our team will be in touch within 24 to 48 hours Mon-Fri (but often much quicker)' ),
        'success' => array('text', 'Success Message', 'Thanks. We will be in touch.')

        );


                $zbsFieldSorts['form'] = array(

                                'default' => array(

                        'header',
                        'subheader',
                        'fname', 
                        'lname', 
                        'email', 
                        'notes', 
                        'submit', 
                        'spam', 
                        'success'
                    )

            );


    global $zbsTransactionFields;

    

        $zbsTransactionFields = array(


                
                'orderid'   => array('text','Transaction ID','e.g. 123456', 'essential' => true),
                'customer'  => array('text','Customer ID','e.g. 1234', 'essential' => true),
                'status'    => array(
                            'select', 'Status','',array(
                                'Completed'
                            ), 'essential' => true),
                'total'     => array('price','Total Value','e.g. 100.99', 'essential' => true),

                
                'customer_name'=> array('text','Customer Name','e.g. John Doe'),
                'date'      => array('date','Transaction Date',''),
                'currency'  => array('currency','Currency','e.g. USD'),
                'item'      => array('text','Transaction Title','e.g. Product ABC'),
                'net'       => array('price','Net Value','e.g. 100.99'),
                'tax'       => array('price','Tax Value','e.g. 100.99'),
                'fee'       => array('price','Fee Value','e.g. 100.99'),
                'discount'  => array('price','Discount Value','e.g. 100.99'),
                'tax_rate'  => array('price','Tax Rate','e.g. 10')

                                        );










        function zeroBSCRM_internalAddressFieldMods(){

        global $zeroBSCRM_Settings;

        $addCountries = $zeroBSCRM_Settings->get('countries');
        if (isset($addCountries) && $addCountries){

                        global $zbsAddressFields, $zbsFieldSorts;
            $zbsAddressFields['country'] = array('selectcountry','Country','e.g. United Kingdom','area'=>'Main Address');

                        $zbsFieldSorts['address']['default'][] = 'country';

        }
        
    }

        function zeroBSCRM_unpackCustomFields(){

                zeroBSCRM_internalAddressFieldMods();

        global $zeroBSCRM_Settings;

        $customfields = $zeroBSCRM_Settings->get('customfields');


                if (isset($customfields['addresses'])){

            global $zbsAddressFields;

            if (count($customfields['addresses']) > 0){

                $cfIndx = 1;
                foreach ($customfields['addresses'] as $fieldKey => $field){

                    $fieldO = $field;
                                        if ($fieldO[0] == 'select'){

                                                                        $fieldO[2] = '';
                        $fieldO[3] = explode(',',$field[2]);

                    }

                                        $zbsAddressFields['cf'.$cfIndx] = $fieldO;

                                        $cfIndx++;

                                        $zbsFieldSorts['address']['default'][] = $fieldKey;

                }

            }

        }


                if (isset($customfields['customers'])){

            global $zbsCustomerFields;

            if (count($customfields['customers']) > 0){

                $cfIndx = 1;
                foreach ($customfields['customers'] as $fieldKey => $field){

                    $fieldO = $field;
                                        if ($fieldO[0] == 'select'){

                                                                        $fieldO[2] = '';
                        $fieldO[3] = explode(',',$field[2]);

                    }

                                        $zbsCustomerFields['cf'.$cfIndx] = $fieldO;

                                        $cfIndx++;

                                        $zbsFieldSorts['customer']['default'][] = $fieldKey;

                }

            }

        }

                if (isset($customfields['companies'])){

            global $zbsCompanyFields;

            if (count($customfields['companies']) > 0){

                $cfIndx = 1;
                foreach ($customfields['companies'] as $fieldKey => $field){

                    $fieldO = $field;
                                        if ($fieldO[0] == 'select'){

                                                                        $fieldO[2] = '';
                        $fieldO[3] = explode(',',$field[2]);

                    }

                                        $zbsCompanyFields['cf'.$cfIndx] = $fieldO;

                                        $cfIndx++;

                                        $zbsFieldSorts['company']['default'][] = $fieldKey;
                }

            }

        }

    
                if (isset($customfields['quotes'])){

            global $zbsCustomerQuoteFields;

            if (count($customfields['quotes']) > 0){

                $cfIndx = 1;
                foreach ($customfields['quotes'] as $fieldKey => $field){

                    $fieldO = $field;
                                        if ($fieldO[0] == 'select'){

                                                                        $fieldO[2] = '';
                        $fieldO[3] = explode(',',$field[2]);

                    }

                                        $zbsCustomerQuoteFields['cf'.$cfIndx] = $fieldO;

                                        $cfIndx++;

                                        $zbsFieldSorts['quote']['default'][] = $fieldKey;
                }

            }

        }



                if (isset($customfields['invoices'])){

            global $zbsCustomerInvoiceFields;

            if (count($customfields['invoices']) > 0){

                $cfIndx = 1;
                foreach ($customfields['invoices'] as $fieldKey => $field){

                    $fieldO = $field;
                                        if ($fieldO[0] == 'select'){

                                                                        $fieldO[2] = '';
                        $fieldO[3] = explode(',',$field[2]);

                    }

                                        $zbsCustomerInvoiceFields['cf'.$cfIndx] = $fieldO;

                                        $cfIndx++;

                                        $zbsFieldSorts['invoice']['default'][] = $fieldKey;
                }

            }

        }


    }


        function zeroBSCRM_unpackCustomisationsToFields(){

        global $zeroBSCRM_Settings;

        $customisedfields = $zeroBSCRM_Settings->get('customisedfields');

        $allowedCustomisation = array(

            'customers' => array(
                    'status',
                    'prefix'
            ),
            'quotes' => array(),
            'invoices' => array(),
            'transactions' => array(),
            'addresses' => array()

        );

        if (isset($customisedfields)) {
            
            foreach ($allowedCustomisation as $allowKey => $allowFields){

                if (count($allowFields)) foreach ($allowFields as $field){

                                        if (isset($customisedfields) && isset($customisedfields[$allowKey]) && isset($customisedfields[$allowKey][$field])){

                                                

                                                                        switch ($allowKey){

                            case 'customers':

                                global $zbsCustomerFields;

                                if ($field == 'status'){

                                                                        $opts = explode(',',$customisedfields[$allowKey][$field][1]);
                                    $zbsCustomerFields['status'][3] = $opts;

                                }

                                if ($field == 'prefix'){

                                                                        $opts = explode(',',$customisedfields[$allowKey][$field][1]);
                                    $zbsCustomerFields['prefix'][3] = $opts;

                                }

                                break;
                            case 'quotes':
                                                                break;
                            case 'invoices':
                                                                break;



                        }

                    }

                }

            } 
        } 
    }

        function zeroBSCRM_applyFieldSorts(){

                global $zeroBSCRM_Settings, $zbsFieldSorts, $zbsCustomerFields, $zbsFieldsEnabled, $zbsCompanyFields, $zbsCustomerQuoteFields, $zbsCustomerInvoiceFields, $zbsFormFields, $zbsAddressFields;

                        $fieldSortOverrides = $zeroBSCRM_Settings->get('fieldsorts');

                
                $exclusions = array('addresses');


                                                $addressDefaultsPresent = false;
            if (isset($zbsFieldSorts['address']) && isset($zbsFieldSorts['address']['default']) && is_array($zbsFieldSorts['address']['default']) && count($zbsFieldSorts['address']['default']) > 0){

                                $addressFieldSortSource = $zbsFieldSorts['address']['default'];                 if (isset($fieldSortOverrides['address']) && is_array($fieldSortOverrides['address']) && count($fieldSortOverrides['address']) > 0) $addressFieldSortSource = $fieldSortOverrides['address'];


                                $newAddressFieldsArr = array(); 

                                foreach ($addressFieldSortSource as $key){

                                        if (!in_array($key, $exclusions) && isset($zbsAddressFields[$key])){

                                                $newAddressFieldsArr[$key] = $zbsAddressFields[$key];

                    } else {

                        
                        
                    }


                }

                                foreach ($zbsAddressFields as $key => $field){

                    if (!array_key_exists($key, $newAddressFieldsArr)){

                                                $newAddressFieldsArr[$key] = $field;

                    }

                }

                                $zbsAddressFields = $newAddressFieldsArr;

                $addressDefaultsPresent = true;

            }


                        



                                                if (isset($zbsFieldSorts['customer']) && isset($zbsFieldSorts['customer']['default']) && is_array($zbsFieldSorts['customer']['default']) && count($zbsFieldSorts['customer']['default']) > 0){

                                $customerFieldSortSource = $zbsFieldSorts['customer']['default'];
                if (isset($fieldSortOverrides['customer']) && is_array($fieldSortOverrides['customer']) && count($fieldSortOverrides['customer']) > 0) $customerFieldSortSource = $fieldSortOverrides['customer'];

                                $newCustomerFieldsArr = array(); 

                                foreach ($customerFieldSortSource as $key){

                                        if (!in_array($key, $exclusions) && isset($zbsCustomerFields[$key])){

                                                                        $newCustomerFieldsArr[$key] = $zbsCustomerFields[$key];
                        

                    } else {

                        
                        if ($key == 'addresses'){

                                                        
                                                        if ($addressDefaultsPresent){

                                
                                                                foreach ($addressFieldSortSource as $addrFieldKey){

                                                                        
                                                                        $adaptedFieldKey = $addrFieldKey; if (substr($addrFieldKey,0,2) == "cf") $adaptedFieldKey = 'addr_'.$addrFieldKey;

                                    if (isset($zbsCustomerFields[$adaptedFieldKey])){

                                                                                $newCustomerFieldsArr[$adaptedFieldKey] = $zbsCustomerFields[$adaptedFieldKey];

                                    } else {

                                                                                                                        if (isset($zbsAddressFields[$addrFieldKey])) $newCustomerFieldsArr[$adaptedFieldKey] = $zbsAddressFields[$addrFieldKey];

                                    }

                                                                                                            if (!isset($newCustomerFieldsArr[$adaptedFieldKey]['area'])) $newCustomerFieldsArr[$adaptedFieldKey]['area'] = 'Main Address';

                                }

                                                                foreach ($addressFieldSortSource as $addrFieldKey){

                                                                        
                                    if (isset($zbsCustomerFields['secaddr_'.$addrFieldKey])){

                                                                                $newCustomerFieldsArr['secaddr_'.$addrFieldKey] = $zbsCustomerFields['secaddr_'.$addrFieldKey];

                                    } else {

                                                                                if (isset($zbsAddressFields[$addrFieldKey])) {

                                                                                        $newCustomerFieldsArr['secaddr_'.$addrFieldKey] = $zbsAddressFields[$addrFieldKey];

                                                                                                                                    $newCustomerFieldsArr['secaddr_'.$addrFieldKey]['area'] = 'Second Address';
                                            $newCustomerFieldsArr['secaddr_'.$addrFieldKey]['opt'] = 'secondaddress';

                                        }

                                    }

                                }



                            }

                        }

                    }


                }

                                foreach ($zbsCustomerFields as $key => $field){

                    if (!array_key_exists($key, $newCustomerFieldsArr)){

                                                $newCustomerFieldsArr[$key] = $field;

                    }

                }

                                $zbsCustomerFields = $newCustomerFieldsArr;


            }



                                                if (isset($zbsFieldSorts['company']) && isset($zbsFieldSorts['company']['default']) && is_array($zbsFieldSorts['company']['default']) && count($zbsFieldSorts['company']['default']) > 0){

                                $companyFieldSortSource = $zbsFieldSorts['company']['default'];
                if (isset($fieldSortOverrides['company']) && is_array($fieldSortOverrides['company']) && count($fieldSortOverrides['company']) > 0) $companyFieldSortSource = $fieldSortOverrides['company'];

                                $newCompanyFieldsArr = array(); 

                                foreach ($companyFieldSortSource as $key){

                                        if (!in_array($key, $exclusions) && isset($zbsCompanyFields[$key])){

                                                $newCompanyFieldsArr[$key] = $zbsCompanyFields[$key];

                    } else {

                        
                        if ($key == 'addresses'){

                                                        
                                                        if ($addressDefaultsPresent){

                                
                                                                foreach ($addressFieldSortSource as $addrFieldKey){

                                                                        
                                                                        $adaptedFieldKey = $addrFieldKey; if (substr($addrFieldKey,0,2) == "cf") $adaptedFieldKey = 'addr_'.$addrFieldKey;

                                    if (isset($zbsCompanyFields[$adaptedFieldKey])){

                                                                                $newCompanyFieldsArr[$adaptedFieldKey] = $zbsCompanyFields[$adaptedFieldKey];

                                    } else {

                                                                                                                        if (isset($zbsAddressFields[$addrFieldKey])) $newCompanyFieldsArr[$adaptedFieldKey] = $zbsAddressFields[$addrFieldKey];

                                    }

                                                                                                            if (!isset($newCompanyFieldsArr[$adaptedFieldKey]['area'])) $newCompanyFieldsArr[$adaptedFieldKey]['area'] = 'Main Address';

                                }

                                                                foreach ($addressFieldSortSource as $addrFieldKey){

                                                                        
                                    if (isset($zbsCompanyFields['secaddr_'.$addrFieldKey])){

                                                                                $newCompanyFieldsArr['secaddr_'.$addrFieldKey] = $zbsCompanyFields['secaddr_'.$addrFieldKey];

                                    } else {

                                                                                if (isset($zbsAddressFields[$addrFieldKey])){

                                                                                        $newCompanyFieldsArr['secaddr_'.$addrFieldKey] = $zbsAddressFields[$addrFieldKey];

                                                                                                                                    $newCompanyFieldsArr['secaddr_'.$addrFieldKey]['area'] = 'Second Address';
                                            $newCompanyFieldsArr['secaddr_'.$addrFieldKey]['opt'] = 'secondaddress';

                                        }

                                    }

                                }



                            }

                        }

                    }


                }

                                foreach ($zbsCompanyFields as $key => $field){

                    if (!array_key_exists($key, $newCompanyFieldsArr)){

                                                $newCompanyFieldsArr[$key] = $field;

                    }

                }

                                $zbsCompanyFields = $newCompanyFieldsArr;


            }



                                                if (isset($zbsFieldSorts['quote']) && isset($zbsFieldSorts['quote']['default']) && is_array($zbsFieldSorts['quote']['default']) && count($zbsFieldSorts['quote']['default']) > 0){

                                $quoteFieldSortSource = $zbsFieldSorts['quote']['default'];
                if (isset($fieldSortOverrides['quote']) && is_array($fieldSortOverrides['quote']) && count($fieldSortOverrides['quote']) > 0) $quoteFieldSortSource = $fieldSortOverrides['quote'];

                                $newQuoteFieldsArr = array(); 

                                foreach ($quoteFieldSortSource as $key){

                                        if (!in_array($key, $exclusions) && isset($zbsCustomerQuoteFields[$key])){

                                                $newQuoteFieldsArr[$key] = $zbsCustomerQuoteFields[$key];

                    } else {

                        
                        if ($key == 'addresses'){

                            
                        }

                    }


                }

                                foreach ($zbsCustomerQuoteFields as $key => $field){

                    if (!array_key_exists($key, $newQuoteFieldsArr)){

                                                $newQuoteFieldsArr[$key] = $field;

                    }

                }

                                $zbsCustomerQuoteFields = $newQuoteFieldsArr;


            }



                                                if (isset($zbsFieldSorts['invoice']) && isset($zbsFieldSorts['invoice']['default']) && is_array($zbsFieldSorts['invoice']['default']) && count($zbsFieldSorts['invoice']['default']) > 0){

                                $invoiceFieldSortSource = $zbsFieldSorts['invoice']['default'];
                if (isset($fieldSortOverrides['invoice']) && is_array($fieldSortOverrides['invoice']) && count($fieldSortOverrides['invoice']) > 0) $invoiceFieldSortSource = $fieldSortOverrides['invoice'];

                                $newInvoiceFieldsArr = array(); 

                                foreach ($invoiceFieldSortSource as $key){

                                        if (!in_array($key, $exclusions) && isset($zbsCustomerInvoiceFields[$key])){

                                                $newInvoiceFieldsArr[$key] = $zbsCustomerInvoiceFields[$key];

                    } else {

                        
                        if ($key == 'addresses'){

                            
                        }

                    }


                }

                                foreach ($zbsCustomerInvoiceFields as $key => $field){

                    if (!array_key_exists($key, $newInvoiceFieldsArr)){

                                                $newInvoiceFieldsArr[$key] = $field;

                    }

                }

                                $zbsCustomerInvoiceFields = $newInvoiceFieldsArr;


            }



                                                if (isset($zbsFieldSorts['form']) && isset($zbsFieldSorts['form']['default']) && is_array($zbsFieldSorts['form']['default']) && count($zbsFieldSorts['form']['default']) > 0){

                                $formFieldSortSource = $zbsFieldSorts['form']['default'];
                if (isset($fieldSortOverrides['form']) && is_array($fieldSortOverrides['form']) && count($fieldSortOverrides['form']) > 0) $formFieldSortSource = $fieldSortOverrides['form'];

                                $newFormFieldsArr = array(); 

                                foreach ($formFieldSortSource as $key){

                                        if (!in_array($key, $exclusions) && isset($zbsFormFields[$key])){

                                                $newFormFieldsArr[$key] = $zbsFormFields[$key];

                    } else {

                        
                        if ($key == 'addresses'){

                            
                        }

                    }


                }

                                foreach ($zbsFormFields as $key => $field){

                    if (!array_key_exists($key, $newFormFieldsArr)){

                                                $newFormFieldsArr[$key] = $field;

                    }

                }

                                $zbsFormFields = $newFormFieldsArr;


            }


    }





        define('ZBSCRM_INC_FIELDS',true);