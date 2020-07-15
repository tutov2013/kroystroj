<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
global $PHOENIX_TEMPLATE_ARRAY;
$arProductsID = array();
$arOffersID = array();

foreach ($arResult['BASKET'] as $key => $arItem){
	
	if(isset($arItem["PARENT"])){
		$arProductsID[$arItem["PARENT"]["ID"]] = $arItem["PARENT"]["ID"];
		$arResult['BASKET'][$key]["DETAIL_PAGE_URL"] = $arItem["DETAIL_PAGE_URL"]."?oID=".$arItem["PRODUCT_ID"];
		$arOffersID[] = $arItem["PRODUCT_ID"];
	}
}


if(!empty($arOffersID))
{
	$arOffersName = CPhoenix::GetOffersName($arOffersID);

	$newArProduct = array();
	$arItem = array();

	$arSelect = array("ID", "NAME");
	$arFilter = Array("IBLOCK_ID"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"], "ID" => $arProductsID);
    $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, false, $arSelect);

    while($ob = $res->GetNextElement())
    {
        $arItem = $ob->GetFields();
        $arItem["NAME"] = strip_tags($arItem["~NAME"]);

        $newArProduct[$arItem["ID"]] = $arItem;
    }

    foreach($arResult['BASKET'] as $key => $arItem){
	
		if(isset($arItem["PARENT"]))
		{
			$arResult['BASKET'][$key]["NAME"] = $newArProduct[$arItem["PARENT"]["ID"]]["NAME"];
			$arResult['BASKET'][$key]["NAME_OFFERS"] = $arOffersName[$arItem["PRODUCT_ID"]];
		}
	}




}