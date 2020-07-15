<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$APPLICATION->AddHeadString('<meta property="og:title" content="'.$arResult["NAME"].'" />');
$APPLICATION->AddHeadString('<meta property="og:type" content="website" />');
$APPLICATION->AddHeadString('<meta property="og:url" content="'.(CMain::IsHTTPS() ? "https://" : "http://").SITE_SERVER_NAME.$arResult["DETAIL_PAGE_URL"].'" />');
if(!empty($arResult["PREVIEW_TEXT"])){
	$APPLICATION->AddHeadString('<meta property="og:description" content="'.htmlspecialcharsbx($arResult["PREVIEW_TEXT"]).'" />');
}
if(!empty($arResult["DETAIL_PICTURE"]["SRC"])){
	$APPLICATION->AddHeadString('<meta property="og:image" content="'.(CMain::IsHTTPS() ? "https://" : "http://").SITE_SERVER_NAME.$arResult["DETAIL_PICTURE"]["SRC"].'" />');
}
?>