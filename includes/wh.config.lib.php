<?php /* !
 * Zero BS CRM
 * http://www.zerobscrm.com
 * V1.0
 *
 * Copyright 2016, ZeroBSCRM.com, StormGate Ltd., Epic Plugins Ltd.
 *
 * Date: 26/05/16
 */


	class WHWPConfigLib {
		
				private $settings;
		private $settingsKey = false;
		private $settingsVer = false;
		private $settingsDefault = false;	
		private $settingsPlugin = false;
		private $settingsPluginVer = false;
		private $settingsPluginDBVer = false;
		
				private $settingsDMZRegister;
		private $settingsDMZKey = false;
		private $settingsDMZ;

				private $whlibVer = '2.0';	

				private $settingsProtected = false;
		
				function __construct($config=array()) {

						if (is_array($config)){

				if (isset($config['conf_key'])) 			$this->settingsKey = $config['conf_key'];
				if (isset($config['conf_ver'])) 			$this->settingsVer = $config['conf_ver'];
				if (isset($config['conf_defaults'])) 		$this->settingsDefault = $config['conf_defaults'];
				if (isset($config['conf_plugin'])) 			$this->settingsPlugin = $config['conf_plugin'];
				if (isset($config['conf_pluginver'])) 		$this->settingsPluginVer = $config['conf_pluginver'];
				if (isset($config['conf_plugindbver'])) 	$this->settingsPluginDBVer = $config['conf_plugindbver'];
				if (isset($config['conf_dmzkey'])) 			$this->settingsDMZKey = $config['conf_dmzkey'];
				if (isset($config['conf_protected'])) 		$this->settingsProtected = $config['conf_protected'];

			} else exit('WHConfigLib initiated incorrectly.');

									
						$this->loadFromDB(); $this->loadDMZFromDB();
			
						$this->validateAndUpdate();
			
						if (empty($this->settings)) $this->initCreate();
			
		}
		
				function validateAndUpdate(){
			$defaultsAdded = 0;
			foreach ($this->settingsDefault as $key => $val) 
				if (!isset($this->settings[$key])) {
					$this->settings[$key] = $val;
					$defaultsAdded++;
				}
			
			if ($defaultsAdded > 0) $this->saveToDB();

		}
		
				function initCreate(){

						if ($settingsKey !== false && $settingsVer !== false && $settingsDefault !== false && $settingsPlugin !== false && $settingsPluginVer !== false){
			
												$defaultOptions = $this->settingsDefault;
				$defaultOptions['settingsID'] = $this->settingsVer; 
				$defaultOptions['plugin'] = $this->settingsPlugin;
				$defaultOptions['version'] = $this->settingsPluginVer; 
				$defaultOptions['db_version'] = $this->settingsPluginDBVer; 

								$this->settings = $defaultOptions;
				$this->saveToDB();

						} else exit('WHConfigLib initiated incorrectly.');
			
		}

				function resetToDefaults(){
			
						
						$existingSettings = $this->settings;
			$newSettings = $this->settingsDefault;
			if (isset($this->settingsProtected) && is_array($this->settingsProtected)) foreach ($this->settingsProtected as $protectedKey){
				
								if (isset($existingSettings[$protectedKey])){

										$newSettings[$protectedKey] = $existingSettings[$protectedKey];

				}
			}

						$this->settings = $newSettings;
			$this->saveToDB();

		}
		
				function getAll(){
			
			return $this->settings;
			
		}
		
				function get($key){
			
			if (empty($key) === true) return false;
			
			if (isset($this->settings[$key]))
				return $this->settings[$key];
			else
				return false;
			
		}
		
				function update($key,$val=''){
			
			if (empty($key) === true) return false;
			
						$this->settings[$key] = $val;		
			
						$this->saveToDB();
		}		
		
				function delete($key){
			
			if (empty($key) === true) return false;
			
			$newSettings = array();
			foreach($this->settings as $k => $v)
				if ($k != $key) $newSettings[$k] = $v;
				
						$this->settings = $newSettings;	
			
						$this->saveToDB();
						
		}


												
				function dmzGet($dmzKey,$confKey){
			
			if (empty($dmzKey) === true || empty($confKey) === true) return false;
			
						if (isset($this->settingsDMZ[$dmzKey])){

				if (isset($this->settingsDMZ[$dmzKey][$confKey])) {

					return $this->settingsDMZ[$dmzKey][$confKey];

				}

			} 
			
			return false;
			
		}		
		
				function dmzDelete($dmzKey,$confKey){
			
			if (empty($dmzKey) === true || empty($confKey) === true) return false;
			
			$existingSettings = $this->dmzGetConfig($dmzKey);
			$newSettings = array();
			if (isset($existingSettings) && is_array($existingSettings)) { foreach($existingSettings as $k => $v) {
					if ($k != $confKey) $newSettings[$k] = $v;
				}
			}
				
						$this->settingsDMZ[$dmzKey] = $newSettings;	
			
						$this->saveToDB();
						
		}
		
				function dmzUpdate($dmzKey,$confKey,$val=''){
			
			if (empty($dmzKey) === true || empty($confKey) === true) return false;
			
						if (!isset($this->settingsDMZ[$dmzKey])){

								$this->settingsDMZRegister[$dmzKey] = $dmzKey;

								$this->settingsDMZ[$dmzKey] = array();

			}

						$this->settingsDMZ[$dmzKey][$confKey] = $val;		
			
						$this->saveToDB();
		}
		
				function dmzGetConfig($dmzKey){
			
			if (empty($dmzKey) === true) return false;
			
						if (isset($this->settingsDMZ[$dmzKey])){

				return $this->settingsDMZ[$dmzKey];

			} 
			
			return false;
			
		}	
		
				function dmzDeleteConfig($dmzKey){
			
			if (empty($dmzKey) === true) return false;
				
						unset($this->settingsDMZ[$dmzKey]);
			unset($this->settingsDMZRegister[$dmzKey]);
			
						$this->saveToDB();
						
		}	
		
				function dmzUpdateConfig($dmzKey,$config){
			
			if (empty($dmzKey) === true || empty($config) === true) return false;
			
						if (!isset($this->settingsDMZ[$dmzKey])){

								$this->settingsDMZRegister[$dmzKey] = $dmzKey;

			}

						$this->settingsDMZ[$dmzKey] = $config;		
			
						$this->saveToDB();
		}	
		
				function loadDMZFromDB(){
			
						$this->settingsDMZRegister = get_option($this->settingsDMZKey);

						if (is_array($this->settingsDMZRegister) && count($this->settingsDMZRegister) > 0) { foreach ($this->settingsDMZRegister as $regEntry){

										$this->settingsDMZ[$regEntry] = get_option($this->settingsDMZKey.'_'.$regEntry);

				}
			}
			return $this->settingsDMZ;
			
		}

		

		
				function saveToDB(){
		
			$u = array();
			$u[] = update_option($this->settingsKey, $this->settings);				

			
								update_option($this->settingsDMZKey,$this->settingsDMZRegister);
				if (isset($this->settingsDMZRegister) && is_array($this->settingsDMZRegister)) foreach ($this->settingsDMZRegister as $dmzKey){ 
					$u[] = update_option($this->settingsDMZKey.'_'.$dmzKey, $this->settingsDMZ[$dmzKey]);	

				}

			return $u;
			
		}
		
				function loadFromDB(){
			
			$this->settings = get_option($this->settingsKey);
			return $this->settings;
			
		}		
		
				function uninstall(){
			
						$this->settings['uninstall'] = time();
			
						$this->createBackup('Pre-UnInstall Backup');
			
						$this->settings = NULL;
			
						return delete_option($this->settingsKey);
			
		}
		
				function createBackup($backupLabel=''){
			
			$existingBK = get_option($this->settingsKey.'_bk'); if (!is_array($existingBK)) $existingBK = array();
			$existingBK[time()] = array(
				'main' => $this->settings,
				'dmzreg' => $this->settingsDMZRegister,
				'dmz' => $this->settingsDMZ
			);
			if (!empty($backupLabel)) $existingBK[time()]['backupLabel'] = sanitize_text_field($backupLabel); 			update_option($this->settingsKey.'_bk',$existingBK);
			return $existingBK[time()];
			
		}
		
				function killBackups(){
		
			return delete_option($this->settingsKey.'_bk');
			
		}
		
				function getBKs(){
			
			$x = get_option($this->settingsKey.'_bk');
			
			if (is_array($x)) return $x; else return array();
			
		}
		
				function reloadFromBK($bkkey){
		
			$backups = get_option($this->settingsKey.'_bk');
			
			if (isset($backups[$bkkey])) if (is_array($backups[$bkkey])) {
				
								$this->settings = $backups[$bkkey];
				
								$this->saveToDB();
			
				return true;	
				
			} 
			
			return false;
				
			
		}
		
		
		
	}


		class WHWPConfigExtensionsLib {

				private $extperma = false;
		private $settingsObj = false;
		private $existingSettings = false;

				function __construct($extperma='',$defaultConfig=array()) {

			if (!empty($extperma)){

								$this->extperma = 'ext_'.$extperma;

								global $zeroBSCRM_Settings;
				$existingSettings = $zeroBSCRM_Settings->dmzGetConfig($this->extperma);

								if (!is_array($existingSettings)){

										$zeroBSCRM_Settings->dmzUpdateConfig($this->extperma,$defaultConfig);

				}

			} else exit('WHConfigLib initiated incorrectly.');

		}

		
		function get($key){

			global $zeroBSCRM_Settings;
			return $zeroBSCRM_Settings->dmzGet($this->extperma,$key);

		}

		function delete($key){

			global $zeroBSCRM_Settings;
			return $zeroBSCRM_Settings->dmzDelete($this->extperma,$key);


		}

		function update($key,$val=''){

			global $zeroBSCRM_Settings;
			return $zeroBSCRM_Settings->dmzUpdate($this->extperma,$key,$val);


		}

		function getAll(){

			global $zeroBSCRM_Settings;
			return $zeroBSCRM_Settings->dmzGetConfig($this->extperma);

		}

	}


	
	
?>