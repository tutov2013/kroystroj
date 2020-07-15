<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?
if(!empty($arResult["SECTIONS"]))
{
	global $PHOENIX_TEMPLATE_ARRAY;

	foreach($arResult["SECTIONS"] as $key => $arSection)
	{

		
		if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["HIDE_EMPTY_CATALOG"]["VALUE"]["ACTIVE"]=="Y")
		{
			if($arSection["ELEMENT_CNT"] == "0")
			{
				unset($arResult["SECTIONS"][$key]);
				continue;
			}
		}


		$arResult["SECTIONS"][$key]["PICTURE_SRC"] = SITE_TEMPLATE_PATH."/images/ufo.jpg";

		if($arSection["UF_PHX_MENU_PICT"] > 0)
		{
			$img = CFile::ResizeImageGet($arSection["UF_PHX_MENU_PICT"], array('width'=>100, 'height'=>100), BX_RESIZE_IMAGE_PROPORTIONAL, false);

			$arResult["SECTIONS"][$key]["PICTURE_SRC"] = $img["src"];
		}


	}
 	
}