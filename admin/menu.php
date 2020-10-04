<?
/**
 * Copyright (c) 4/10/2020 Created By/Edited By ASDAFF asdaff.asad@yandex.ru
 */

IncludeModuleLangFile(__FILE__);

$aMenu = Array(
	"parent_menu" => "global_menu_marketing",
		"section" => "kit.smsservices",
		"sort" => 100,
		"module_id" => "kit.smsservices",
		"text" => GetMessage("KITSS_MENU_MODULE_NAME"),
		"title" => GetMessage("KITSS_MENU_MODULE_DESC"),
		"items_id" => "menu_smsservices",
		"icon" => "fileman_sticker_icon",
		"items" => array(
			array(
				"text" => GetMessage("KITSS_MENU_SENSMS"),
				"url" => "kit_smsservices_sendform.php?lang=".LANGUAGE_ID,
				"more_url" => Array(),
				"title" => GetMessage("KITSS_MENU_SENSMS")
			),
			array(
				"text" => GetMessage("KITSS_MENU_BALANCE"),
				"url" => "kit_smsservices_balance.php?lang=".LANGUAGE_ID,
				"more_url" => Array(),
				"title" => GetMessage("KITSS_MENU_BALANCE")
			),
			array(
				"text" => GetMessage("KITSS_MENU_HISTORY"),
				"url" => "kit_smsservices_list.php?lang=".LANGUAGE_ID,
				"more_url" => Array(),
				"title" => GetMessage("KITSS_MENU_HISTORY")
			),
			array(
				"text" => GetMessage("KITSS_MENU_EVENTLIST"),
				"url" => "kit_smsservices_eventlist.php?lang=".LANGUAGE_ID,
				"more_url" => Array('kit_smsservices_eventlist_edit.php?lang='.LANGUAGE_ID),
				"title" => GetMessage("KITSS_MENU_EVENTLIST")
			)
		)
);
return $aMenu;
?>
