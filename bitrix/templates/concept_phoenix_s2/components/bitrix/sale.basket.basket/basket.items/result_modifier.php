<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
use Bitrix\Main;
global $PHOENIX_TEMPLATE_ARRAY;


$arrItems = array();

if( !empty($arResult["GRID"]["ROWS"]) )
{
	foreach ($arResult["GRID"]["ROWS"] as $key => $arItem)
	{
		$arrItems[] = $arItem["PRODUCT_ID"];
	}
}


CModule::IncludeModule("concept.phoenix");
CPhoenix::phoenixOptionsValues(SITE_ID, array("design", "menu", "shop", "catalog"));
CPhoenix::setMainMess();


if(!empty($arrItems))
{
	CPhoenixSku::getHIBlockOptions();


	$arFilter = Array("ID" => $arrItems);
	$arSelect = array("IBLOCK_ID", "PROPERTY_CML2_LINK", "ID");
	$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

	$arProduct = array();
	$arOffers = array();
	$arOffer2Propduct = array();

	while($ob = $res->GetNext())
	{
		
		if(strlen($ob["PROPERTY_CML2_LINK_VALUE"]))
		{
			$arProduct[] = $ob["PROPERTY_CML2_LINK_VALUE"];
			$arOffers[] = $ob["ID"];
			$arOffer2Propduct[$ob["ID"]] = $ob["PROPERTY_CML2_LINK_VALUE"];
		}
		else
			$arProduct[] = $ob["ID"];

	}


	if( !empty($arProduct) )
	{
		CPhoenix::getIblockIDs(array("concept_phoenix_catalog_".SITE_ID));

		$arFilter = Array("IBLOCK_ID"=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"], "ID" => $arProduct);
		$res = CIBlockElement::GetList(Array(), $arFilter, false);


		$arProductFields = array();

		while($ob = $res->GetNextElement())
		{
			$fields = array();
			$fields = $ob->GetFields();

		    $arProductFields[$fields["ID"]] = $fields;
		    $arProductFields[$fields["ID"]]["PROPERTIES"] = $ob->GetProperties();

		}

	}


	if( !empty($arOffers) )
	{
		CPhoenix::getIblockIDs(array("concept_phoenix_catalog_offers_".SITE_ID));
		$arFilter = Array("IBLOCK_ID"=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['OFFERS']["IBLOCK_ID"], "ID" => $arOffers);
		$res = CIBlockElement::GetList(Array(), $arFilter, false);


		$arOffersFields = array();

		while($ob = $res->GetNextElement())
		{
			$fields = array();
			$fields = $ob->GetFields();

		    $arOffersFields[$fields["ID"]] = $fields;
		    $arOffersFields[$fields["ID"]]["PROPERTIES"] = $ob->GetProperties();
		}

	}


	$arResult["BASKET_FILTER"] = $arResult["DELAY_FILTER"] = array();

	

	foreach ($arResult["GRID"]["ROWS"] as $key => $arItem)
	{

		$arResult["GRID"]["ROWS"][$key]["NAME_OFFERS"] = "";
		$isOffer = isset($arOffer2Propduct[$arItem["PRODUCT_ID"]]);

		if($isOffer)
		{
			$productIdOfOrder = $arOffer2Propduct[$arItem["PRODUCT_ID"]];

			$photo = 0;

            if($arOffersFields[$arItem["PRODUCT_ID"]]["DETAIL_PICTURE"])
                $photo = $arOffersFields[$arItem["PRODUCT_ID"]]["DETAIL_PICTURE"];
            

            else if(!empty($arOffersFields[$arItem["PRODUCT_ID"]]["PROPERTIES"]["MORE_PHOTO"]["VALUE"]))
                $photo = $arOffersFields[$arItem["PRODUCT_ID"]]["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0];

            else if($arOffersFields[$arItem["PRODUCT_ID"]]["PROPERTIES"]["NO_MERGE_PHOTOS"]["VALUE"] != "Y")
            {

            	if($arProductFields[$arOffersFields[$arItem["PRODUCT_ID"]]["PROPERTIES"]["CML2_LINK"]["VALUE"]]["DETAIL_PICTURE"])
	                $photo = $arProductFields[$arOffersFields[$arItem["PRODUCT_ID"]]["PROPERTIES"]["CML2_LINK"]["VALUE"]]["DETAIL_PICTURE"];
	            

	            else if(!empty($arProductFields[$arOffersFields[$arItem["PRODUCT_ID"]]["PROPERTIES"]["CML2_LINK"]["VALUE"]]["PROPERTIES"]["MORE_PHOTO"]["VALUE"]))
	                $photo = $arProductFields[$arOffersFields[$arItem["PRODUCT_ID"]]["PROPERTIES"]["CML2_LINK"]["VALUE"]]["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0];
            }



			if($photo)
			{
				$img = CFile::ResizeImageGet($photo, array('width'=>290, 'height'=>240), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

				$arResult["GRID"]["ROWS"][$key]["PREVIEW_PICTURE_SRC"] = $img["src"];

				
			}
			else
				$arResult["GRID"]["ROWS"][$key]["PREVIEW_PICTURE_SRC"] = SITE_TEMPLATE_PATH."/images/ufo.png";


			$arResult["GRID"]["ROWS"][$key]["DETAIL_PAGE"] = $arOffersFields[$arItem["PRODUCT_ID"]]["DETAIL_PAGE_URL"]."?oID=".$arItem["PRODUCT_ID"];
			$arResult["GRID"]["ROWS"][$key]["ARTICLE"] = strip_tags($arOffersFields[$arItem["PRODUCT_ID"]]["PROPERTIES"]["ARTICLE"]["~VALUE"]);

			$arResult["GRID"]["ROWS"][$key]["NAME"] = strip_tags($arProductFields[$productIdOfOrder]["~NAME"]);


			if( !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUE_"]) )
            {

            	foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUE_"] as $arOfferField)
                {
                	if( $arOffersFields[$arItem["PRODUCT_ID"]]["PROPERTIES"][$arOfferField]["USER_TYPE"] == "directory" && strlen($arOffersFields[$arItem["PRODUCT_ID"]]["PROPERTIES"][$arOfferField]["VALUE"]) && !empty($PHOENIX_TEMPLATE_ARRAY["SKU_PROP_LIST"]) )
                    {
                    	foreach ($PHOENIX_TEMPLATE_ARRAY["SKU_PROP_LIST"] as $arSKUProp)
                        {

	                    	if( $arOffersFields[$arItem["PRODUCT_ID"]]["PROPERTIES"][$arOfferField]['USER_TYPE_SETTINGS']['TABLE_NAME'] == $arSKUProp['TABLE_NAME'])
	                        {
	                        	if(strlen($arSKUProp["VALUE_NAME"][$arOffersFields[$arItem["PRODUCT_ID"]]["PROPERTIES"][$arOfferField]["VALUE"]]))
	                        		$arResult["GRID"]["ROWS"][$key]["NAME_OFFERS"] .= $arOffersFields[$arItem["PRODUCT_ID"]]["PROPERTIES"][$arOfferField]["NAME"].":&nbsp;".$arSKUProp["VALUE_NAME"][$arOffersFields[$arItem["PRODUCT_ID"]]["PROPERTIES"][$arOfferField]["VALUE"]]."<br/>";

	                        }
	                    }
                    }
                    else if(strlen($arOffersFields[$arItem["PRODUCT_ID"]]["PROPERTIES"][$arOfferField]["VALUE"]))
                    {
                        $arResult["GRID"]["ROWS"][$key]["NAME_OFFERS"] .= $arOffersFields[$arItem["PRODUCT_ID"]]["PROPERTIES"][$arOfferField]["NAME"].":&nbsp;".$arOffersFields[$arItem["PRODUCT_ID"]]["PROPERTIES"][$arOfferField]["VALUE"]."<br/>";
                    }

                }

                
            }

		}

		else
		{

			$photo = 0;

            if($arProductFields[$arItem["PRODUCT_ID"]]["DETAIL_PICTURE"])
                $photo = $arProductFields[$arItem["PRODUCT_ID"]]["DETAIL_PICTURE"];
            

            else if(!empty($arProductFields[$arItem["PRODUCT_ID"]]["PROPERTIES"]["MORE_PHOTO"]["VALUE"]))
                $photo = $arProductFields[$arItem["PRODUCT_ID"]]["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0];


			if($photo)
			{
				$img = CFile::ResizeImageGet($photo, array('width'=>290, 'height'=>240), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

				$arResult["GRID"]["ROWS"][$key]["PREVIEW_PICTURE_SRC"] = $img["src"];
			}

			else
				$arResult["GRID"]["ROWS"][$key]["PREVIEW_PICTURE_SRC"] = SITE_TEMPLATE_PATH."/images/ufo.png";

			$arResult["GRID"]["ROWS"][$key]["DETAIL_PAGE"] = $arProductFields[$arItem["PRODUCT_ID"]]["DETAIL_PAGE_URL"];
			$arResult["GRID"]["ROWS"][$key]["ARTICLE"] = strip_tags($arProductFields[$arItem["PRODUCT_ID"]]["PROPERTIES"]["ARTICLE"]["~VALUE"]);

		}

		if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_FILTER"]["VALUE"]["ACTIVE"]=="Y")
		{

			if($arItem["DELAY"] == "N" && $arItem["CAN_BUY"] == "Y")
				$arResult["BASKET_FILTER"]["id_".$arItem["ID"]] = array(
					"NAME"=>$arResult["GRID"]["ROWS"][$key]["NAME"],
					"QUERY"=>strtolower($arResult["GRID"]["ROWS"][$key]["NAME"])
				);

			if($arItem["DELAY"] == "Y" && $arItem["CAN_BUY"] == "Y")
				$arResult["DELAY_FILTER"]["id_".$arItem["ID"]] = array(
					"NAME" => $arResult["GRID"]["ROWS"][$key]["NAME"],
					"QUERY" => strtolower($arResult["GRID"]["ROWS"][$key]["NAME"])
				);
		}
	}

}