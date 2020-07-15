<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?
if(!empty($arResult["SECTIONS"]))
{
	global $PHOENIX_TEMPLATE_ARRAY;
	if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["HIDE_EMPTY_CATALOG"]["VALUE"]["ACTIVE"]=="Y")
	{
		foreach ($arResult["SECTIONS"] as $key => $arItem){

			if($arItem["ELEMENT_CNT"] === "0")
				unset($arResult["SECTIONS"][$key]);
		
		}
	}
}