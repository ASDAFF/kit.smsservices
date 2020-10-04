<?
/**
 * Copyright (c) 4/10/2020 Created By/Edited By ASDAFF asdaff.asad@yandex.ru
 */

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

$module_id = "kit.smsservices";

\Bitrix\Main\Loader::includeModule($module_id);
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$POST_RIGHT = $APPLICATION->GetGroupRight($module_id);
if ($POST_RIGHT == "D")
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

$APPLICATION->SetAdditionalCSS("/bitrix/css/".$module_id."/style.css");
	
class KitRowListAdmin extends \Kit\Smsservices\Main {
	
	public function __construct($params) {
		parent::__construct($params);
	}
	
	public function getKitRowListAdminCustomRow($row){
		
		$row->AddViewField("SENDER", ($row->arRes['SENDER']) ? $row->arRes['SENDER'] : \Bitrix\Main\Config\Option::get("kit.smsservices","sender","",""));
		$row->AddViewField("TEMPLATE", '<font style="font-size:12px;">'.$row->arRes['TEMPLATE'].'</font>');
		$row->AddCheckField("ACTIVE");
		
		$params = unserialize(htmlspecialcharsBack($row->arRes['PARAMS']));
		$html = '';
		foreach($params as $name=>$val){
			$html .= $name.': '.$val.';<br/>';
		}
		$row->AddViewField("PARAMS", '<font style="font-size:12px;">'.$html.'</font>');
		$row->AddInputField("NAME", array("size"=>20));
		$row->AddInputField("SENDER", array("size"=>20));
		
		
		$sHTML = '<textarea rows="7" cols="50" name="FIELDS['.$row->arRes['ID'].'][TEMPLATE]">'.htmlspecialcharsBack($row->arRes['TEMPLATE']).'</textarea>';
		$row->AddEditField("TEMPLATE", $sHTML);
		
	}
	
}

$arParams = array(
	"PRIMARY" => "ID",
	"ENTITY" => "\\Kit\\Smsservices\\EventlistTable",
	"FILE_EDIT" => 'kit_smsservices_eventlist_edit.php',
	"BUTTON_CONTECST" => array(),
	"ADD_GROUP_ACTION" => array("delete","edit"),
	"COLS" => true,
	"FIND" => array(
		"NAME","EVENT","SITE_ID"
	),
	"LIST" => array("ACTIONS" => array("delete","edit")),
	"CALLBACK_ACTIONS" => array()
);

$adminCustom = new KitRowListAdmin($arParams);
$adminCustom->defaultInterface();