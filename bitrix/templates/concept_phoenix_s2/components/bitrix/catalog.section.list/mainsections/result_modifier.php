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
	    
	$this->__component->arResultCacheKeys = array_merge($this->__component->arResultCacheKeys, array('SECTIONS'));


}

if(strlen($arResult["SECTION"]["SECTION_PAGE_URL"]) > 0)
    $arResult["SECTION_BACK"] = $arResult["SECTION"]["SECTION_PAGE_URL"];
else
    $arResult["SECTION_BACK"] = SITE_DIR."catalog/";
?>