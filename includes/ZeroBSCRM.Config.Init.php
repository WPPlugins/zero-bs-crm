<?php /* !
 * Zero BS CRM
 * http://www.zerobscrm.com
 * V1.0
 *
 * Copyright 2016, ZeroBSCRM.com, StormGate Ltd., Epic Plugins Ltd.
 *
 * Date: 26/05/16
 */


    if ( ! defined( 'ZEROBSCRM_PATH' ) ) exit;







	
		
	
		global $zeroBSCRM_Conf_Setup,$zeroBSCRM_db_version,$zeroBSCRM_version;
		$zeroBSCRM_Conf_Setup = array(

						'conf_key' => 'zerobscrmsettings', 

						'conf_ver' => 'v2.2//29.06.2017', 

						'conf_plugin' => 'zeroBSCRM', 
			'conf_pluginver' => $zeroBSCRM_version, 		
			'conf_plugindbver' => $zeroBSCRM_db_version,

						'conf_dmzkey' => 'zbscrm_dmz',

									'conf_protected' => array(
				'whlang',
				'customfields',
				'customisedfields',
							)

		);

	

	
				
	
								$zeroBSCRM_Conf_Def = array( 

																						'wptakeovermode' => 		0, 											'wptakeovermodeforall' => 	0,
											'customheadertext' => 		'',
											'killfrontend' => 			0, 											'loginlogourl' => 			'',
											'showneedsquote' => 		1,
											'filetypesupload' => 		array(
												'pdf' => 1,
												'doc' => 0,
												'docx' => 0,
												'ppt' => 0,
												'pptx' => 0,
												'xls' => 0,
												'xlsx' => 0,
												'csv' => 0,
												'png' => 0,
												'jpg' => 0,
												'mp3' => 0,
												'txt' => 0,
												'zip' => 0,
												'all' => 0
											),
											'showaddress' => 			1,
											'secondaddress' => 			1,
											'countries' => 				1,
											'companylevelcustomers' => 	0,
											'coororg' => 'co',
											'showthanksfooter' => 1,

																						'menulayout' => 2,  
											
																						'currency' => 	array(
												'chr'	=> '$',
												'strval' => 'USD'
											),
											
																						'businessname' => '',
											'businessyourname' => '',
											'businessyouremail' => '',
											'businessyoururl' => '',
											'businessextra' => '',
											'invfromemail' => '',
											'invfromname' => '',


																						'invpro_pay' => 2,   											'stripe_sk' => '',
											'stripe_pk' => '',
											'ppbusinessemail' => '',


											'invtax' => 0,
											'invdis' => 0,
											'invpandp' => 0,

																						'quoteoffset' => 0,
											'invoffset' => 0,
											'invallowoverride' => 1,

																																												'usequotebuilder' => 1, 
											'showpoweredbyquotes' => 1,

											

																						'autolog_customer_new' => 1,
											'autolog_company_new' => 1,
											'autolog_quote_new' => 1,
											'autolog_invoice_new' => 1,
											'autolog_transaction_new' => 1,
																						
																						'showpoweredby' => 1,
											'usegcaptcha' => 	0,
											'gcaptchasitekey' => '',
											'gcaptchasitesecret' => '',
																						

																						'feat_quotes' => 1,
											'feat_invs' => 1,
											'feat_forms' =>  1,
											'feat_pdfinvs' => -1,
											'feat_csvimporterlite' => 1,
											'feat_portal' => 1,   
											'feat_custom_fields' =>  1,
											'feat_api'			 =>  0,

																						
																						'showid' => 1,
											'customfields' => array(

												'customers' => array(
													array('select','Source','Google,Word of mouth,Local Newspaper')
												),
												'quotes' => array(),
												'invoices' => array(),
												'transactions' => array()

											),		
											'defaultstatus' => 'Lead',
											'fieldsorts' => array(

												'address' => false,
												'customers' => false,
												'quotes' => false,
												'invoices' => false,
												'transactions' => false

											),	
											'fieldhides' => array(

												'address' => false,
												'customers' => false,
												'quotes' => false,
												'invoices' => false,
												'transactions' => false

											),										
											
																						'migrations' => array(),
																						
																						'customisedfields' => array(

												'customers' => array(
																																																																	'status'=> array(
														1,'Lead,Customer,Refused,Blacklisted,Cancelled by Customer,Cancelled by Us (Pre-Quote),Cancelled by Us (Post-Quote)'
													),
													'prefix'=> array(
														1,'Mr,Mrs,Ms,Miss,Dr,Prof,Mr & Mrs'
													)
												),
												'quotes' => array(),
												'invoices' => array(),
												'transactions' => array()

											),										
																						
																						'perusercustomers' => 0,
											'usercangiveownership' => 0,
																						
																						'customviews' => array(

																								'customer' => array(

													'name' => array('Name','zbsDefault_column_customername'),
																										'email' => array('Email','zbsDefault_column_customeremail'),
													'status' => array('Status','zbsDefault_column_status'),
													'quotes' => array('Quotes','zbsDefault_column_quotecount'),
													'invoices' => array('Invoices','zbsDefault_column_invoicecount'),
													'transactions' => array('Transactions','zbsDefault_column_transactioncount'),
													'totalvalue' => array('Total Value','zbsDefault_column_totalvalue'),
													'added' => array('Added','zbsDefault_column_added')
																										

												)

											),										
																						
																						'customviews2' => array(

																								'customer' => array(

													'name' => array('Name','zbsDefault_column_customername'),
																										'email' => array('Email','zbsDefault_column_customeremail'),
													'status' => array('Status','zbsDefault_column_status'),
													'quotes' => array('Quotes','zbsDefault_column_quotecount'),
													'invoices' => array('Invoices','zbsDefault_column_invoicecount'),
													'transactions' => array('Transactions','zbsDefault_column_transactioncount'),
													'totalvalue' => array('Total Value','zbsDefault_column_totalvalue'),
													'added' => array('Added','zbsDefault_column_added')
																										

												)

											),										
											




																						'whlang' => array(

												

																								
'c9a28e7f0dbc3ed20a161351c4f29a7b' => array("","Quotes"),
'fce9a6a1bd2a2050eb86d33103f46fd3' => array("","Invoices"),
'31112aca11d0e9e6eb7db96f317dda49' => array("","Transactions"),
'6450242531912981c3683cae88a32a66' => array("","Forms"),
'b31f70681aeac4e32644a84f906f4895' => array("","Needs a Quote"),
'07e52faa437619aa92129e06a2aba5f3' => array("","Manage Quotes"),
'3ebacd5afbe086d19b8eb223f5b1f93b' => array("","Quote Templates"),
'f0a0521d580d21820cfbb00654e9906d' => array("","Manage Invoices"),
'3be6da2472b130867c9ffa2921c4c04d' => array("","Manage Transactions"),
'6566a47e11a89dea995ef6ebe12206c9' => array("","Proposal Accepted"),
'e6d0e1c8fc6a4fcf47869df87e04cd88' => array("","Customers"),
'08e0d3a60c8e3c0dd63d1e0e0bee4bc3' => array("","Manage your customers here"),
'c404676a273878de86626e3d0d73e48b' => array("","Manage Customers"),
'3c7f57f3a7cb39afd0698d342cf31ae9' => array("","Customer Tags"),
'571d870fa35a66e99404ed6db77105df' => array("","Customer Search"),
'114bdd243ddaad32a5cb6bccff6a3391' => array("","Manage your quotes here"),
'07ca20d50fbcf27343b6d4a4ee7bf4b3' => array("","Manage your invoices here"),
'9781ef2ce9086f360046a086dc08ea01' => array("","Manage Forms"),

'386c339d37e737a436499d423a77df0c' => array("","Currency"),
 
'4a45a03e664dce2fe8d235854f6290e9' => array("","Customer updated."),
'0dcfd40b7e9b28853d3fcfefd772a6f8' => array("","Customer field deleted."),
'28b156e53a20b18d98444fa10ea93bab' => array("","Customer restored to revision from %s"),
'51edd4286db75a409bfeced25be7de10' => array("","Customer saved."),

'054027a49d448cdbc42c35e1223bac1b' => array("","Invoice updated."),
'060a13afae61f932286d8a63bcb42729' => array("","Invoice field deleted."),
'81bb41758b45db769509360d832e5432' => array("","Invoice restored to revision from %s"),
'34f244d11444ab5635ab213b112cbeb2' => array("","Invoice saved."),
'9b8bb39366e8e99803b36e4ee0847701' => array("","Quote updated."),
'f77dba3e80e23221ed5cf1d8f45fa351' => array("","Quote field deleted."),
'e44a6c2ff6c3df2585bbab3fda5b6e4a' => array("","Quote restored to revision from %s"),
'3d20fbdf3f5977f44591b9317eedb613' => array("","Quote saved."),
'93a819dd0a063c78d0e5250fdd165bcb' => array("","Transaction updated."),
'5e1b47f335f8fb6e7b607dcca9fa8fb1' => array("","Transaction field deleted."),
'c07130bdc9f81deb4a7c2a4a54189bae' => array("","Transaction restored to revision from %s"),
'5911fcb9b89531c4069c7156ce42c252' => array("","Transaction saved."),
'60ab1db11a61ef45087279c8cd8fe1f0' => array("","Customer Tag"),

'ce26601dac0dea138b7295f02b7620a7' => array("","Customer"),
'54f4d274c9b140c3d95a6814b5a1cb23' => array("","Customer Archives"),
'5e5f0a863cd129317037e3def37b4097' => array("","Company:"),
'1c76cbfe21c6f44c1d1e59d54f3e4420' => array("","Company"),
'4bade83d287e61e971fb0c41c4e51d91' => array("","All Customers"),
'67f40e9ea508b472f924b9c03ad70684' => array("","Add New Customer"),
'c28639657b4e3352241f83d8b6021e4e' => array("","New Customer"),
'e5d63551ca2d2842661e49a25fd12a67' => array("","Edit Customer"),
'1cb64d6dfd854958085fbbf502d02422' => array("","Update Customer"),
'475007c00d7c166cca01a44ecfd068ff' => array("","View Customer"),
'8b4a1bc5d57e6921d9cc8df069530500' => array("","Search Customer"),
'6361300ab197a391c9dee85b903833b1' => array("","Customer Image"),
'f65cec2567103de4bc04f36dba083cb8' => array("","Set Customer image"),
'7686d22f05661357f4f4e29c3d2cb71d' => array("","Remove Customer image"),
'e0ab8a0e73ac980443ac43de4c8c399b' => array("","Use as Customer image"),
'876537f189686a4aad5a7d54ad96d2a1' => array("","Insert into Customer"),
'8157184c36d9c0a0316d1beaa7563331' => array("","Uploaded to this Customer"),
'2fecaa039bd30f28e0ed19543b99873b' => array("","Customers list"),
'2472d87592476c5df5f937e464040447' => array("","Customers list navigation"),
'3049347a91b30c8f1159fdc2507e7421' => array("","Filter Customers list"),
'4a3f58bfff342dde5731391c28762153' => array("","Zero-BS Customer"),
'963f44466c9a5ac4d562c5402e9d2f8d' => array("","Contact Tag"),
'b31b7f76cbf7e2bb3d7e72e787baf903' => array("","Contact Tags"),
'9aa698f602b1e5694855cee73a683488' => array("","Contacts"),
'bbaff12800505b22a853e8b7f4eb6a22' => array("","Contact"),
'e1a302a847193410df63d73dc9ea3a19' => array("","Contact Archives"),
'2b2edc156a7f3e035b3d234fee57daf2' => array("","All Contacts"),
'0e2c3d4638d6b306264afc5c9abddbb2' => array("","Add New Contact"),
'636c575a94e9a0c957277e432fb1d49b' => array("","New Contact"),
'2c5258c2bdca06cc49e9cc029551455d' => array("","Edit Contact"),
'ca915747dddca50b4387b83ab6d091d6' => array("","Update Contact"),
'6a223e8d774ced51dd13052772b278cc' => array("","View Contact"),
'8ad402f286f5336fcd14bf42ec8c180e' => array("","Search Contact"),
'fb809e711af7ec3afcd133ecf7736b1f' => array("","Contact Image"),
'a84748ca78aa55b802df65083cf81d46' => array("","Set Contact image"),
'08f0bf38a9e4492a2efddc515546f363' => array("","Remove Contact image"),
'f7cb9fdecf463fa6be3d1897bf317c15' => array("","Use as Contact image"),
'0ab84f8e68119f3d970098da6c2e8507' => array("","Insert into Contact"),
'1cc82a31421d5804be8b9695b33aea1a' => array("","Uploaded to this Contact"),
'22948498d23c63ee0582342e312de294' => array("","Contacts list"),
'b4f5dd5e3d4e04e8b5ebce2fc8c94163' => array("","Contacts list navigation"),
'5411f17840a531b542db1943026400cd' => array("","Filter Contacts list"),
'fbd743a920fde978789fe77bcbdb7db0' => array("","Zero-BS Contact"),

'c48e929b2b1eabba2ba036884433345e' => array("","Quote"),
'8ecdba00e65f53a1a92bc25be4cadd5f' => array("","Quote Archives"),
'20155be522964f78d2f161b8a8bacc4f' => array("","Parent Quote:"),
'7875909de073022b71448d3157bf2823' => array("","All Quotes"),
'b18db2f7f015354ac728d926f3fd60cb' => array("","Add New Quote"),
'3da2e4bc30bf7633039443a4f7bca843' => array("","New Quote"),
'5a4c350f322c2f6e190af5049786a3d5' => array("","Edit Quote"),
'2eb854241a920405c04b5f79c0daf58b' => array("","Update Quote"),
'46bec1485413c08b864e8e4975d25b93' => array("","View Quote"),
'87eee69beae338a10f3fa8280703c119' => array("","Search Quote"),
'2503b2519cecd47998e812c82d505624' => array("","Quote Image"),
'708c42fabc1d5ab54b4674388b926b97' => array("","Set Quote image"),
'b7cf20b088549e09904f4e42bce78ef7' => array("","Remove Quote image"),
'2508ceea892bdcac0f3716847589f01f' => array("","Use as Quote image"),
'4d27150c76d9eab724d35218a2a69f12' => array("","Insert into Quote"),
'f95742db74ec507e5b91bb1e73f885b3' => array("","Uploaded to this Quote"),
'119d6bef0e3a1126bc35edc27f162d28' => array("","Quotes list"),
'a84af752db7bf6e6e35a5609d94fc219' => array("","Quotes list navigation"),
'e7af0c36f944afc83458bdac719385a6' => array("","Filter Quotes list"),
'cc8477ccbee593637c9677b6a9d3e880' => array("","Zero-BS Quote"),
'b9c6c910f96fa10ad734d1270422f9d2' => array("","Quote Template"),
'9f316f371f5bbe9b0665d52b8ea1b6e1' => array("","Quote Template Archives"),
'a96c4c0a20d22112af4683be8fd2b78c' => array("","Parent Quote Template:"),
'6261af5cfb5d3e0a255c9542ffbe2347' => array("","All Quote Templates"),
'c7fb5ba1d780391a425b606ca6af818b' => array("","Add New Quote Template"),
'38e5f237c5ad236663f741e4f3d0f496' => array("","New Quote Template"),
'b78b80488a28d3c1f2118df579abb59f' => array("","Edit Quote Template"),
'f026cc9f9909261ab0dc3a560d01f712' => array("","Update Quote Template"),
'7676b7aa41926d87427e42c96b0a4b32' => array("","View Quote Template"),
'dd0a99c2e8b934861fa278ec41d5097e' => array("","Search Quote Template"),
'e805a6d8b8eb4c9c3339b0dd241a48c6' => array("","Quote Template Image"),
'4d474793863b8cb77ffea96b516b58c5' => array("","Set Quote Template image"),
'596d40897d544c5aab14d5521458f06a' => array("","Remove Quote Template image"),
'd2857d289e507aaf8480bc1d6d247233' => array("","Use as Quote Template image"),
'fcbcee6b9f9c532ba9eed02e9b629ef5' => array("","Insert into Quote Template"),
'f0e721969f39708023efd0423775b286' => array("","Uploaded to this Quote Template"),
'942b3596c507a59f76cb1ec25325b33e' => array("","Quote Templates list"),
'dfa0934b32d014ccacf095c3bcf5a600' => array("","Quote Templates list navigation"),
'005a60172be2920fed50de5d3d4ef172' => array("","Filter Quote Templates list"),
'8b354b3aed9c4365e084b7487584398f' => array("","Zero-BS Quote Template"),
'466eadd40b3c10580e3ab4e8061161ce' => array("","Invoice"),
'b41cd694f14e40617e3cd0bb048bce5c' => array("","Invoice Archives"),
'70db043a644cffb909cc98b39049bc75' => array("","Parent Invoice:"),
'cf44752e975b1cfe62d08c487e5e8f16' => array("","All Invoices"),
'd009190ed3f7fc14f382f436351166f9' => array("","Add New Invoice"),
'4093583f2e4e6fff497269705d77a058' => array("","New Invoice"),
'f121527ac9f0e351e7ed79280833c86b' => array("","Edit Invoice"),
'600a9fc69fa6ac6dec6fa0fe7192823e' => array("","Update Invoice"),
'bcdb0f9b4945b81851713a688bd5f39a' => array("","View Invoice"),
'c164521e72356d0b65da6505c03bd936' => array("","Search Invoice"),
'7748fb8f4ee00f1710a6eef020c11c56' => array("","Invoice Image"),
'4f088b8a90acc54e63107762a5cec7f7' => array("","Set Invoice image"),
'3bcf151ae74d6886eeddb6eb20bf9e30' => array("","Remove Invoice image"),
'6ef4335d12d2d9b738915e7bada72a30' => array("","Use as Invoice image"),
'ae4d327cedf3bf9c71f374961cef07bd' => array("","Insert into Invoice"),
'a2113eca17ab12a425f1571df01aa56c' => array("","Uploaded to this Invoice"),
'6db9f068ad8aa54a5d2a93b4b41e1a89' => array("","Invoices list"),
'50e9cd473a6ca0289b4bf29484522d36' => array("","Invoices list navigation"),
'449b2926f309e1a2760110a679b3023f' => array("","Filter Invoices list"),
'6a5a4ae680619cfa668fbb072f78eac7' => array("","Zero-BS Invoice"),
'57ee780ac9b53372013d02bb6ceb095f' => array("","Transaction Tags"),
'c621483061f0881b14636d032222a0f2' => array("","Transaction Tag"),
'1b0ffaae3973401f71cd000ab9f7856e' => array("","Transaction"),
'78a9fd877d4e209e26640e8b1106efa0' => array("","Transactions Archives"),
'4cfc9cc5bc11c034ccffb73a2c19cc5c' => array("","Parent Transaction:"),
'0862519fafe4be7813213bc1ce852e97' => array("","All Transactions"),
'c6c787ec0a19126010e53896b7e26cfb' => array("","Add New Transaction"),
'b12d75cbb84fae7163e2451aafbeb401' => array("","New Transaaction"),
'ee6a813c6131ee336ad8f890ed6c9b70' => array("","Edit Transaction"),
'8370970f97bf5e7cef07f969c43f729e' => array("","Update Transaction"),
'fc097f5ea2d877e3573fba1527521261' => array("","View Transaction"),
'8c34cfa15359a861864694c1993d4e42' => array("","Search Transactions"),
'5f726073a285dbd335274e87ec110dfd' => array("","Zero-BS Transactions"),
'd359c6df99b25183d81f7d728b71de0e' => array("","Form"),
'8467889a7765b3973be7aa7a2ec240c1' => array("","Form Archives"),
'21954bb58748b202abb46efc081f6b0d' => array("","All Forms"),
'45949de1c2ecf085d1b1c9d450214fad' => array("","Add New Form"),
'171dbbede1ac7b23357ff0f7a48557ac' => array("","New Form"),
'39741d4aeec6b7cce662056a3e7f8713' => array("","Edit Form"),
'6c3c61f6e00719e6c1aff139cd98220a' => array("","Update Form"),
'd06c5d208391ab6442c0bd75904a87d7' => array("","View Form"),
'96b74c49a42ae2c72bada15f266b465c' => array("","Search Form"),
'983b3dc7bd93a4d10447160abca960d6' => array("","Form Image"),
'a549a15abb7437ee09059eb1cb01060f' => array("","Set Form image"),
'b533597c7924bcc0ef6b5a0a72fe1bad' => array("","Remove Form image"),
'e581c0499ee10c8c70a26798fd1e900f' => array("","Use as Form image"),
'8b03b0f546bc7d1476ed613506180392' => array("","Insert into Form"),
'd21e98f35a9500f9f5ec2b2990119da0' => array("","Uploaded to this Form"),
'1a375dc1ef3223398aa6266ebc6bab4f' => array("","Forms list"),
'5c530f3de0192ad65f26c5f4e2465913' => array("","Forms list navigation"),
'7c8f2180b658f918672a1d3b0643916b' => array("","Filter Forms list"),
'e591cc05a46ef90ed1c59606bff0e617' => array("","Zero-BS Form"),
'352a3330d7709ba9b8cace394f68227e' => array("","Log Tags"),
'd1cda2aebd847c996c34685c1191f4e0' => array("","Log Tag"),
'b2d37ae1cedf42ff874289b721860af2' => array("","Logs"),
'ce0be71e33226e4c1db2bcea5959f16b' => array("","Log"),
'708dda46c772404be18bc0d9ec693f84' => array("","Log Archives"),
'eeb41be937965dcb49c6d428805a16ba' => array("","All Logs"),
'869ac9cd483a537286baf3d3a3d3d34c' => array("","Add New Log"),
'048b4efa899d4e9a6e8ae01e015b4751' => array("","New Log"),
'fa8487cc276114ec2c4949c6d2b89fac' => array("","Edit Log"),
'cbc07012d1b7fb1134acf8aa301f52dc' => array("","Update Log"),
'8f96241822e718c6be629d036f586ca6' => array("","View Log"),
'2a4c81a5d9f9f89efdfe8e528bf01e3a' => array("","Search Log"),
'2ea94e761482284e53797907afbd25b5' => array("","Log Image"),
'45362872d71d21448304da894880995b' => array("","Set Log image"),
'02564dec8012ece1c1c529f20aa0e6b4' => array("","Remove Log image"),
'c5478c1633a3a240373b04f534238b8f' => array("","Use as Log image"),
'f8f7a1f1d11c07ba232e9e63238528b1' => array("","Insert into Log"),
'1bc2259fd472a532108cc942d952e799' => array("","Uploaded to this Log"),
'0be4f6f9c42dbfd8617e4784fa45cf42' => array("","Logs list"),
'7be4a7c3b36b391b021111301a1b4415' => array("","Logs list navigation"),
'ebf67bc0d1be4eb9c57009fd8cbd285a' => array("","Filter Logs list"),
'b8191734e3146f3709e1de66ed659b0b' => array("","Zero-BS Log"),

'7c0716622a67c8b6a97d031aedf483ac' => array("","Has Tag(s)"),
'26756e997d02227c40bb39936427a57e' => array("","Has Quote"),
'7065f1649dbf4edd1a55e6976e5873ab' => array("","Has Invoice"),
'9e77ea865570bf6e936e80325382fbf8' => array("","Has Transaction"),
'f31bbdd1b3e85bccd652680e16935819' => array("","Source"),
'1326081f601173f95c58fda3b907cb72' => array("","Within Postal Code"),
'f95ebaa6cdba5145ac027457e0b436af' => array("","e.g. AL1 or 90012"),

'edefbda3a2bdd979e42d8944b7325b79' => array("","Companies"),
'9e3b7b76f16760f3a9515303ad543d55' => array("","Export Companies"),
'12fc2f74e36b4adf96ed6b991abb5d8f' => array("","Export Invoices"),
'149c76064c471e03884e6be350d7a78c' => array("","Export Quotes"),
'8f6e74f8720baac742630d5246fa01dc' => array("","Export Transactions"),
'e4c3da18c66c0147144767efeb59198f' => array("","Conversion Rate"),
'6332798b12e537b25b1c6ad254e14f54' => array("","Conversions"),
'882c8fe3f428b57a286f4ae07af2ac87' => array("","Embed a lead capture form to your website"),
'02d4482d332e1aef3437cd61c9bcc624' => array("","Contact us"),
'694e8d1f2ee056f98ee488bdc4982d73' => array("","Quantity"),
'3601146c4e948c32b6424d2c0a7f0118' => array("","Price"),
'6a7e73161603d87b26a8eac49dab0a9c' => array("","Hours"),
'dcb66ff6e4a2517ade22183779939c9d' => array("","Rate"),
'2194aaec8cc7086ab0e93f74547e5f53' => array("","Subtotal"),
'104d9898c04874d0fbac36e125fa1369' => array("","Discount"),
'54822f9a495d5bb4cbd46ca4a1eb160a' => array("","Postage and packaging"),
'4b78ac8eb158840e9638a3aeb26c4a9d' => array("","Tax"),
'96b0141273eabab320119c467cdcaf17' => array("","Total"),
'909055edfbd3ce359313fc8ba74f0b5b' => array("","Payment (transaction ID:"),
'ab9d12668f24ecd2e4f58933bb74b8db' => array("","Amount due"),
'015644aefe409d46e0ebbdff15c4c932' => array("","Invoice Number:"),
'440ac606343b8a66acad0d2978786d2a' => array("","Invoice Date"),
'b2f40690858b404ed10e62bdf422c704' => array("","Amount"),

'41589395bab73b8149f5f35ae676a330' => array("","No Customers avaliable."),
'5200308ee5244a6656d582d569103fea' => array("","Total Value"),
'addabf58ae7c8ea2936f9b717f1cf722' => array("","No Invoices avaliable."),
'684926b2145a391dd19d373ca602be52' => array("","Invoice No#"),
'2ea989f83006e233627987293f4bde0a' => array("","Customer Name"),
'e3d3ff66ba9494134fad22e766ae0212' => array("","No Quotes avaliable."),
'a41927e01b160b1405d34709e042f2c3' => array("","QuoteRef"),
'ce1f5708827a26758a7a116d7e0fb318' => array("","No Transactions avaliable."),
'ce1986ed311f1fb5e39232873e54d35b' => array("","Transaction No#"),
'e2e3da9d7b176fc315897d97a1c28cde' => array("","Select a Company"),

'849621c2e580be636b14dcdf42471d0d' => array("","Your business information"),
'f51487aa10abac1530325e8ea2a87188' => array("","Edit or add details"),
'd6e132571e2dba49eb8bb9a25012727a' => array("","Send invoice to:"),
'41a9974103f899932d28848eaf6dfee7' => array("","Copy to:"),
'b04c14d121175868a4c42504c3442be6' => array("","Assign to (Customer):"),
'df3627c52e6854eb64cd9a049b9eb36e' => array("","Assign to (Company):"),
'7c780d3d62661a9e6c647e1ba6b1dae4' => array("","Tax on postage (%)"),

'eb36e51dfa28ff6128d25f934a2e5cf8' => array("","Transaction Value"),
'be9e626f6b2f23373d5543d6bfb19ca8' => array("","Transaction Name:"),
'8fedb9069c04a88f28ab4f9dc645f303' => array("","Assign to invoice:"),

'49085e8876116129ea3322d5fabd3ac4' => array("","Accept Proposal?"),
'a39e05288c839e2af484676f497d4a38' => array("","Please enter your email to confirm you accept this proposal:"),
'b610db72f8e8fb7b7d5d0f94e967b927' => array("","Your email is required in order to accept a proposal"),
'c892f9fa94e9ad64a0290051ffdefb27' => array("","Thank you for accepting this proposal, we'll be in touch shortly."),
'840f35eb12b064482ef0dd8932e71648' => array("","There was an error accepting this proposal, please email us to let us know."),





											) 

			);




	
		$zeroBSCRM_Conf_Setup['conf_defaults'] = $zeroBSCRM_Conf_Def;


		define('ZBSCRM_INC_CONFINIT',true);