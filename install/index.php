<?
/**
 * Copyright (c) 4/10/2020 Created By/Edited By ASDAFF asdaff.asad@yandex.ru
 */

IncludeModuleLangFile(__FILE__);

Class kit_smsservices extends CModule
{
        var $MODULE_ID = "kit.smsservices";
        var $MODULE_VERSION;
        var $MODULE_VERSION_DATE;
        var $MODULE_NAME;
        var $MODULE_DESCRIPTION;

        function kit_smsservices() {
				$path = str_replace("\\", "/", __FILE__);
				$path = substr($path, 0, strlen($path) - strlen("/index.php"));
				include($path."/version.php");
				
				$this->MODULE_VERSION = $arModuleVersion["VERSION"];
				$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
				$this->PARTNER_NAME = 'ASDAFF';
				$this->PARTNER_URI = 'https://asdaff.github.io/';
				$this->MODULE_NAME = GetMessage("KITSS_MODULE_NAME");
				$this->MODULE_DESCRIPTION = GetMessage("KITSS_MODULE_DESC");
				
				if(GetMessage("KITSS_PARTNER_NAME")){
					$this->PARTNER_NAME = GetMessage("KITSS_PARTNER_NAME");
				}
				if(GetMessage("KITSS_PARTNER_URI")){
					$this->PARTNER_URI = GetMessage("KITSS_PARTNER_URI");
				}
				
			return true;
        }

        function DoInstall() {
			
			CopyDirFiles(
			$_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$this->MODULE_ID."/install/admin",
			$_SERVER["DOCUMENT_ROOT"]."/bitrix/admin",true,true);
			
			CopyDirFiles(
			$_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$this->MODULE_ID."/install/css",
			$_SERVER["DOCUMENT_ROOT"]."/bitrix/css",true,true);
			
			RegisterModule($this->MODULE_ID);
			$this->createTable();
			$this->createAgents();
			
			$eventManager = \Bitrix\Main\EventManager::getInstance();
			$eventManager->registerEventHandlerCompatible('main', 'OnAdminTabControlBegin', $this->MODULE_ID, '\Kit\Smsservices\Events', 'OnAdminTabControlBegin');
			
			RegisterModuleDependences("sale", "OnSaleStatusOrder", $this->MODULE_ID, "\\Kit\\Smsservices\\Handlers", "OnSaleStatusOrderHandler");
			RegisterModuleDependences("sale", "OnSaleCancelOrder", $this->MODULE_ID, "\\Kit\\Smsservices\\Handlers", "OnSaleCancelOrderHandler");
			RegisterModuleDependences("sale", "OnSaleComponentOrderOneStepComplete", $this->MODULE_ID, "\\Kit\\Smsservices\\Handlers", "OnSaleComponentOrderOneStepCompleteHandler");
			RegisterModuleDependences("sale", "OnSaleComponentOrderComplete", $this->MODULE_ID, "\\Kit\\Smsservices\\Handlers", "OnSaleComponentOrderOneStepCompleteHandler");
			RegisterModuleDependences("sale", "OnSaleCancelOrder", $this->MODULE_ID, "\\Kit\\Smsservices\\Handlers", "OnSaleCancelOrderHandler");
			RegisterModuleDependences("sale", "OnSaleDeliveryOrder", $this->MODULE_ID, "\\Kit\\Smsservices\\Handlers", "OnSaleDeliveryOrderHandler");

            //Редирект на настройки приложения
			LocalRedirect('/bitrix/admin/settings.php?lang=ru&mid='.$this->MODULE_ID.'&mid_menu=1');
        }

        function DoUninstall() {
            //Удаление файлов визуальной части админ панели
			DeleteDirFiles(
			$_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$this->MODULE_ID."/install/admin",
			$_SERVER["DOCUMENT_ROOT"]."/bitrix/admin");
			
			DeleteDirFiles(
			$_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$this->MODULE_ID."/install/css",
			$_SERVER["DOCUMENT_ROOT"]."/bitrix/css");
			
			$this->deleteTable();
			$this->deleteAgents();
			
			//UnRegisterModuleDependences("sale", "OnSaleStatusOrder", $this->MODULE_ID, "CkitCmsServicesHandlers", "OnSaleStatusOrderHandler");
			//UnRegisterModuleDependences("sale", "OnSaleCancelOrder", $this->MODULE_ID, "CkitCmsServicesHandlers", "OnSaleCancelOrderHandler");
			//UnRegisterModuleDependences("sale", "OnSaleComponentOrderOneStepComplete", $this->MODULE_ID, "CkitCmsServicesHandlers", "OnSaleComponentOrderOneStepCompleteHandler");
			//UnRegisterModuleDependences("sale", "OnSaleComponentOrderComplete", $this->MODULE_ID, "CkitCmsServicesHandlers", "OnSaleComponentOrderOneStepCompleteHandler");
			//UnRegisterModuleDependences("sale", "OnSaleCancelOrder", $this->MODULE_ID, "CkitCmsServicesHandlers", "OnSaleCancelOrderHandler");
			//UnRegisterModuleDependences("sale", "OnSaleDeliveryOrder", $this->MODULE_ID, "CkitCmsServicesHandlers", "OnSaleDeliveryOrderHandler");
			
			UnRegisterModuleDependences("main", "OnAdminTabControlBegin", $this->MODULE_ID, '\Kit\Smsservices\Events', "OnAdminTabControlBegin");
			
			UnRegisterModuleDependences("sale", "OnSaleStatusOrder", $this->MODULE_ID, "\\Kit\\Smsservices\\Handlers", "OnSaleStatusOrderHandler");
			UnRegisterModuleDependences("sale", "OnSaleCancelOrder", $this->MODULE_ID, "\\Kit\\Smsservices\\Handlers", "OnSaleCancelOrderHandler");
			UnRegisterModuleDependences("sale", "OnSaleComponentOrderOneStepComplete", $this->MODULE_ID, "\\Kit\\Smsservices\\Handlers", "OnSaleComponentOrderOneStepCompleteHandler");
			UnRegisterModuleDependences("sale", "OnSaleComponentOrderComplete", $this->MODULE_ID, "\\Kit\\Smsservices\\Handlers", "OnSaleComponentOrderOneStepCompleteHandler");
			UnRegisterModuleDependences("sale", "OnSaleCancelOrder", $this->MODULE_ID, "\\Kit\\Smsservices\\Handlers", "OnSaleCancelOrderHandler");
			UnRegisterModuleDependences("sale", "OnSaleDeliveryOrder", $this->MODULE_ID, "\\Kit\\Smsservices\\Handlers", "OnSaleDeliveryOrderHandler");
			
			\Bitrix\Main\Loader::includeModule("kit.smsservices");
			\Kit\Smsservices\EventlistTable::removeAllEvent();
			
			UnRegisterModule($this->MODULE_ID);
        }
	
	function createTable() {
		global $DB;
		$sql = "
		CREATE TABLE IF NOT EXISTS `kit_smsservices_list` (
		  `ID` int(18) NOT NULL AUTO_INCREMENT,
		  `PROVIDER` varchar(50) DEFAULT NULL,
		  `SMSID` varchar(100) DEFAULT NULL,
		  `SENDER` varchar(50) DEFAULT NULL,
		  `PHONE` varchar(20) DEFAULT NULL,
		  `TIME` int(11) NOT NULL,
		  `TIME_ST` int(11) NOT NULL,
		  `MEWSS` varchar(2655) NOT NULL,
		  `PRIM` varchar(655) DEFAULT NULL,
		  `STATUS` int(2) NOT NULL DEFAULT '0',
		  `EVENT` varchar(100) NULL DEFAULT 'DEFAULT',
		  `EVENT_NAME` varchar(100) NULL DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) AUTO_INCREMENT=1 ;
		";
		if(strtolower($DB->type)=="mysql") $res = $DB->Query($sql);
		$sql = "
		CREATE TABLE IF NOT EXISTS `kit_smsservices_eventlist` (
		`ID` int(9) NOT NULL AUTO_INCREMENT,
		`SITE_ID` varchar(10) NOT NULL,
		`SENDER` varchar(50) NULL,
		`EVENT` varchar(50) NOT NULL,
		`NAME` varchar(255) NOT NULL,
		`TEMPLATE` varchar(2500) NULL,
		`PARAMS` varchar(6255) NULL,
		`ACTIVE` varchar(1) NOT NULL DEFAULT 'N',
		 PRIMARY KEY (`ID`)
		);
		";
		if(strtolower($DB->type)=="mysql") $res = $DB->Query($sql);
		//ALTER TABLE  `kit_smsservices_list` CHANGE  `MEWSS`  `MEWSS` VARCHAR( 2655 ) NOT NULL
	}
	
	function deleteTable () {
		global $DB;
		//$sql = 'DROP TABLE IF EXISTS `b_kit_smsservices_list`';
		$sql = 'DROP TABLE IF EXISTS `kit_smsservices_list`';
		$res = $DB->Query($sql);
		$sql = 'DROP TABLE IF EXISTS `kit_smsservices_eventlist`';
		$res = $DB->Query($sql);
	}
	
	function createAgents() {
		CAgent::AddAgent(
		"\\Kit\\Smsservices\\Agent::statusSms();",
		$this->MODULE_ID,
		"N",
		600);
		CAgent::AddAgent(
		"\\Kit\\Smsservices\\Agent::turnSms();",
		$this->MODULE_ID,
		"N",
		300);
	}
	
	function deleteAgents() {
		//CAgent::RemoveAgent("CKitSmsServicesAgentStatusSms();", "kit.smsservices");
		//CAgent::RemoveAgent("CKitSmsServicesAgentTurnSms();", "kit.smsservices");
		CAgent::RemoveAgent("\\Kit\\Smsservices\\Agent::turnSms();", $this->MODULE_ID);
		CAgent::RemoveAgent("\\Kit\\Smsservices\\Agent::statusSms();", $this->MODULE_ID);
	}
}

?>

