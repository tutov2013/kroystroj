<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();?>

<?
global $PHOENIX_TEMPLATE_ARRAY;

if( !empty($arResult["ITEMS"]) )
{

    $arrItems = array();
    $arrItemsOffers = array();
    $getProperties = false;
    $getPropertiesOffers = false;

    foreach ($arResult["ITEMS"] as $arItem){

          if(empty($arItem["OFFERS"]))
          {
                if(empty($arItem["OFFERS"][0]["PROPERTIES"]))
                      $getPropertiesOffers = true;
          }

          if(empty($arItem["PROPERTIES"]))
                $getProperties = true;
    }

    
    foreach ($arResult["ITEMS"] as $arItem)
    {
          $arrItems[] = $arItem["ID"];

          if(!empty($arItem['OFFERS']) && $PHOENIX_TEMPLATE_ARRAY["GLOBAL_SKU"] == "Y")
          {
                foreach ($arItem['OFFERS'] as $arOffers)
                {
                   $arrItemsOffers[] = $arOffers["ID"];
                }
          }
    }


    if(!empty($arrItems) && $getProperties)
    {

          $arFilter = Array("IBLOCK_ID"=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"], "ID" => $arrItems);
          $res = CIBlockElement::GetList(Array(), $arFilter, false, false);



          $arProperties = array();
          $fields = array();

          while($ob = $res->GetNextElement())
          {
                $fields = $ob->GetFields();
                $arProperties[$fields["ID"]] = $ob->GetProperties();
          }

    }


    if(!empty($arrItemsOffers) && $getPropertiesOffers)
    {

          $arFilter = Array("IBLOCK_ID"=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['OFFERS']["IBLOCK_ID"], "ID" => $arrItemsOffers);
          $res = CIBlockElement::GetList(Array(), $arFilter, false, false);


          $arPropertiesOffers = array();
          $fields = array();

          while($ob = $res->GetNextElement())
          {
            $fields = $ob->GetFields();
            $arPropertiesOffers[$fields["ID"]] = $ob->GetProperties();
          }

    }

    if($getProperties || $getPropertiesOffers)
    {
          foreach ($arResult["ITEMS"] as $itemKey => $arItem)
          {

                if($getProperties)
                      $arResult["ITEMS"][$itemKey]["PROPERTIES"] = $arProperties[$arItem["ID"]];


                if( !empty($arItem['OFFERS']) && $PHOENIX_TEMPLATE_ARRAY["GLOBAL_SKU"] == "Y" && $getPropertiesOffers)
                {

                      foreach ($arItem['OFFERS'] as $offerKey => $value)
                      {
                            $arResult["ITEMS"][$itemKey]['OFFERS'][$offerKey]["PROPERTIES"] = $arPropertiesOffers[$value["ID"]];
                      }
                }
          }
    }

    $strBaseCurrency = '';
    $boolConvert = isset($arResult['CONVERT_CURRENCY']['CURRENCY_ID']);
        
    if (!$boolConvert)
        $strBaseCurrency = CCurrency::GetBaseCurrency();

    $arResult['NO_PHOTO'] = SITE_TEMPLATE_PATH.'/images/ufo.jpg';



    foreach ($arResult["ITEMS"] as $itemKey => $arItem)
    {
        $arItem["ACTUAL_PRICE"] = $arItem["MIN_PRICE"]["PRINT_DISCOUNT_VALUE"];

        if($arItem["MIN_PRICE"]["DISCOUNT_DIFF"])
            $arItem["OLD_PRICE"] = $arItem["MIN_PRICE"]["PRINT_VALUE"];


        $photo = 0;
        $desc = "";


        if($arItem["DETAIL_PICTURE"])
            $photo = $arItem["DETAIL_PICTURE"]["ID"];
        
        else if(!empty($arItem["PROPERTIES"]["MORE_PHOTO"]["VALUE"]))
            $photo = $arItem["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0];
        


        if($photo)
        {
            $arImg = CFile::ResizeImageGet($photo, array('width'=>120, 'height'=>80), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

            $arItem["PREVIEW_PICTURE_SRC"] = $arImg["src"];
        }
        else
            $arItem["PREVIEW_PICTURE_SRC"] = $arResult['NO_PHOTO'];


        if(isset($arItem['OFFERS']) && !empty($arItem['OFFERS']))
        {

            $arItem["ACTUAL_PRICE"] = "";


            $minPrice = CPhoenix::getMinPriceFromOffersExt(
                $arItem['OFFERS'],
                $boolConvert ? $arResult['CONVERT_CURRENCY']['CURRENCY_ID'] : $strBaseCurrency
            );



            if($minPrice["DIFF"]>0)
                $arItem['ACTUAL_PRICE'] .= $PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_PREFIX_FROM"]." ";

            $arItem['ACTUAL_PRICE'] .= $minPrice["VALUE"]["PRINT_DISCOUNT_VALUE"];
            $arItem['DETAIL_PAGE_URL'] = $arItem['DETAIL_PAGE_URL']."?oID=".$minPrice["OFFER_ID"];

            foreach ($arItem['OFFERS'] as $keyOffer => $arOffer)
            {
                if($arOffer["ID"] == $minPrice["OFFER_ID"])
                {

                    $photo = 0;
                    $desc = "";


                    if($arOffer["DETAIL_PICTURE"])
                        $photo = $arOffer["DETAIL_PICTURE"]["ID"];
                    
                    else if(!empty($arOffer["PROPERTIES"]["MORE_PHOTO"]["VALUE"]))
                        $photo = $arOffer["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0];
                    

                    if($photo)
                    {
                        $arImg = CFile::ResizeImageGet($photo, array('width'=>120, 'height'=>80), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

                        $arItem["PREVIEW_PICTURE_SRC"] = $arImg["src"];
                    }
                    else
                    {
                        if($arOffer["PROPERTIES"]["NO_MERGE_PHOTOS"]["VALUE"] != "Y")
                        {
                            if($arItem["PREVIEW_PICTURE_SRC"])
                                $arItem["PREVIEW_PICTURE_SRC"] = $arItem["PREVIEW_PICTURE_SRC"];
                        }
                        else
                            $arItem["PREVIEW_PICTURE_SRC"] = $arResult['NO_PHOTO'];
                    }
                }
            }

        }

        $arResult["ITEMS"][$itemKey] = $arItem;

    }


    if(isset($arParams["SEARCH_ELEMENTS_ID"]) && !empty($arParams["SEARCH_ELEMENTS_ID"]))
    {

        foreach ($arParams["SEARCH_ELEMENTS_ID"] as $key => $value) {
           $arParams["SEARCH_ELEMENTS_ID"][$key] = intval($value);
        }


        $newArResult = array();

        foreach ($arResult["ITEMS"] as $key => $value)
        {
            $newKey = array_search(intval($value["ID"]), $arParams["SEARCH_ELEMENTS_ID"]);

            $newArResult[$newKey] = $value;
        }
        
        ksort($newArResult);

        $arResult["ITEMS"] = array_values($newArResult);
    }


}