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

		$row->AddViewField("TIME", \ConvertTimeStamp($row->arRes['TIME'],'FULL'));
		if($row->arRes['TIME_ST']) {
			$row->AddViewField("TIME_ST", \ConvertTimeStamp($row->arRes['TIME_ST'],'FULL'));
		} else {
			$row->AddViewField("TIME_ST", Loc::getMessage("KITSS_LIST_TIME_ST_L"));
		}
		$row->AddViewField("STATUS", '<font class="status_'.(($row->arRes['STATUS']==14 || $row->arRes['STATUS']==15) ? 4 :$row->arRes['STATUS']).'">'.Loc::getMessage("KITSS_LIST_STATUS_".$row->arRes['STATUS']).'</font>');
	}
	
}

$arParams = array(
	"PRIMARY" => "ID",
	"ENTITY" => "\\Kit\\Smsservices\\ListTable",
	"FILE_EDIT" => 'kit_smsservices_sendform.php',
	"BUTTON_CONTECST" => array(),
	"ADD_GROUP_ACTION" => array("delete"),
	"COLS" => true,
	"FIND" => array(
		"SENDER","PROVIDER","PHONE",
		array("NAME"=>"STATUS", "KEY"=>"STATUS", "GROUP"=>"STATUS", "FILTER_TYPE"=>"=", "TYPE"=>"LIST",
			"VALUES"=> array(
				"reference" => array(
					"-",
					Loc::getMessage("KITSS_LIST_STATUS_1"),Loc::getMessage("KITSS_LIST_STATUS_2"),
					Loc::getMessage("KITSS_LIST_STATUS_3"),Loc::getMessage("KITSS_LIST_STATUS_4"),
					Loc::getMessage("KITSS_LIST_STATUS_14"),Loc::getMessage("KITSS_LIST_STATUS_15"),
					Loc::getMessage("KITSS_LIST_STATUS_5"),Loc::getMessage("KITSS_LIST_STATUS_6"),
					Loc::getMessage("KITSS_LIST_STATUS_7"),Loc::getMessage("KITSS_LIST_STATUS_8"),
					Loc::getMessage("KITSS_LIST_STATUS_9"),Loc::getMessage("KITSS_LIST_STATUS_10"),
					Loc::getMessage("KITSS_LIST_STATUS_11"),Loc::getMessage("KITSS_LIST_STATUS_12"),
				),
				"reference_id" => array("",1,2,3,4,14,15,5,6,7,8,9,10,11,12)
			)
		)
	),
	"LIST" => array("ACTIONS" => array("delete")),
	"CALLBACK_ACTIONS" => array()
);

$adminCustom = new KitRowListAdmin($arParams);
$adminCustom->defaultInterface();