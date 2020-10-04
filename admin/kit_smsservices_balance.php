<?
/**
 * Copyright (c) 4/10/2020 Created By/Edited By ASDAFF asdaff.asad@yandex.ru
 */

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
$module_id = "kit.smsservices";
$MODULE_RIGHT = $APPLICATION->GetGroupRight($module_id);
if (! ($MODULE_RIGHT >= "R"))
	$APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
	
$APPLICATION->SetTitle(Loc::getMessage("KITSS_BALANCE_TITLE"));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
$APPLICATION->SetAdditionalCSS("/bitrix/css/kit.smsservices/style.css");

\Bitrix\Main\Loader::includeModule($module_id);
$smsServices = new \Kit\Smsservices\Sender();
$arrBalance = $smsServices->getBalance();
?>
<?foreach($arrBalance as $key=>$val){?>
<?if($val){?>
<div class="balance">
<div class="titleTransport"><?=Loc::getMessage("KITSS_BALANCE_TRANSPORT_".ToUpper($key))?></div>
<?
if($val->error) {
?>
<?=Loc::getMessage("KITSS_BALANCE_ERR_".$val->error_code)?> (<?=$val->error?>)
<?}else{?>
<?=Loc::getMessage("KITSS_BALANCE_OST")?>: <strong><?=$val->balance?></strong>

<?}?>
</div>
<?}?>
<?}?>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>