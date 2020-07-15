<?
use Bitrix\Main\Type\Collection;
use Bitrix\Currency\CurrencyTable;
use Bitrix\Iblock;
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();?>

<?
global $PHOENIX_TEMPLATE_ARRAY;
CPhoenixSku::getHIBlockOptions();


$arResult["GLOBAL_SHOWPREORDERBTN"] = ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["AUTO_MODE_PREORDER"]["VALUE"]["ACTIVE"]=="Y" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["MODE_PREORDER_FORM"]["VALUE"] != "N")? true:false;

if( !empty($arResult["ITEMS"]) )
{

    $getProperties = false;
    $i = 0;
    foreach ($arResult["ITEMS"] as $arItem) {
        if($i==2)
            break;

        if(empty($arItem["PROPERTIES"]))
            $getProperties = true;

        $i++;
    }

    if($getProperties)
    {
        $arrItems = array();
        $arrItemsOffers = array();
        foreach ($arResult["ITEMS"] as $arItem)
        {
            $arrItems[] = $arItem["ID"];

            if(!empty($arItem['OFFERS']))
            {
                foreach ($arItem['OFFERS'] as $arOffers)
                   $arrItemsOffers[] = $arOffers["ID"];
            }
        }


        $arFilter = Array("IBLOCK_ID"=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"], "ID" => $arrItems);
        $res = CIBlockElement::GetList(Array(), $arFilter, false);


        $arProperties = array();
        $fields = array();

        while($ob = $res->GetNextElement())
        {
            $fields = $ob->GetFields();
            $arProperties[$fields["ID"]] = $ob->GetProperties();
        }


        $arFilter = Array("IBLOCK_ID"=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['OFFERS']["IBLOCK_ID"], "ID" => $arrItemsOffers);
        $res = CIBlockElement::GetList(Array(), $arFilter, false);


        $arPropertiesOffers = array();
        $fields = array();

        while($ob = $res->GetNextElement())
        {
            $fields = $ob->GetFields();
            $arPropertiesOffers[$fields["ID"]] = $ob->GetProperties();
        }


        foreach ($arResult["ITEMS"] as $itemKey => $arItem)
        {

            $arResult["ITEMS"][$itemKey]["PROPERTIES"] = $arProperties[$arItem["ID"]];
           

            if( !empty($arItem['OFFERS']))
            {

                foreach ($arItem['OFFERS'] as $offerKey => $value)
                {
                    $arResult["ITEMS"][$itemKey]['OFFERS'][$offerKey]["PROPERTIES"] = $arPropertiesOffers[$value["ID"]];
                }
            }
        }

    }

    $arConvertParams = array();

    if ('Y' == $arParams['CONVERT_CURRENCY'])
    {
        if (!CModule::IncludeModule('currency'))
        {
            $arParams['CONVERT_CURRENCY'] = 'N';
            $arParams['CURRENCY_ID'] = '';
        }
        else
        {
            $arResultModules['currency'] = true;
            if($arResult['CURRENCY_ID'])
            {
                $arConvertParams['CURRENCY_ID'] = $arResult['CURRENCY_ID'];
            }
            else
            {
                $arCurrencyInfo = CCurrency::GetByID($arParams['CURRENCY_ID']);
                if (!(is_array($arCurrencyInfo) && !empty($arCurrencyInfo)))
                {
                    $arParams['CONVERT_CURRENCY'] = 'N';
                    $arParams['CURRENCY_ID'] = '';
                }
                else
                {
                    $arParams['CURRENCY_ID'] = $arCurrencyInfo['CURRENCY'];
                    $arConvertParams['CURRENCY_ID'] = $arCurrencyInfo['CURRENCY'];
                }
            }
        }
    }



    $arEmptyPreview = false;
    $strEmptyPreview = SITE_TEMPLATE_PATH.'/images/ufo.jpg';
    if (file_exists($_SERVER['DOCUMENT_ROOT'].$strEmptyPreview))
    {
        $arSizes = getimagesize($_SERVER['DOCUMENT_ROOT'].$strEmptyPreview);
        if (!empty($arSizes))
        {
            $arEmptyPreview = array(
                'SRC' => $strEmptyPreview,
                'WIDTH' => intval($arSizes[0]),
                'HEIGHT' => intval($arSizes[1])
            );
        }
        unset($arSizes);
    }
    unset($strEmptyPreview);

    $strBaseCurrency = '';
    $boolConvert = isset($arResult['CONVERT_CURRENCY']['CURRENCY_ID']);
        
    if (!$boolConvert)
        $strBaseCurrency = CCurrency::GetBaseCurrency();


    

    if( $PHOENIX_TEMPLATE_ARRAY["GLOBAL_SKU"] == "Y")
    {
        foreach ($arResult["ITEMS"] as $itemKey => $arItem)
        {
            $arSKU = CPhoenix::GetSkuProperties(
                array(
                    "IBLOCK_ID" => $arParams['IBLOCK_ID'],
                    "OFFERS" => $arItem['OFFERS'],
                    "SKU_FIELDS" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUE_"],
                    "SITE_ID" => SITE_ID,
                )
            );

            
            $arResult["ITEMS"][$itemKey]['SKU_PROPS'] = $arSKU["SKU_PROP_LIST"];
            $arResult["ITEMS"][$itemKey]['SKU_MATRIX'] = $arSKU["SKU_MATRIX"];
        }
    }




    $arNewItemsList = array();
    $arResult['NO_PHOTO'] = SITE_TEMPLATE_PATH.'/images/ufo.jpg';

    $arWaterMark = Array();

    if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["WATERMARK"]["VALUE"] > 0)
    {

        $arWaterMark = Array(
            array(
                "name" => "watermark",
                "position" => "center",
                "type" => "image",
                "size" => "real",
                "file" => $_SERVER["DOCUMENT_ROOT"].CFile::GetPath($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["WATERMARK"]["VALUE"]),
                "fill" => "exact",
            )
        );
    }


    foreach ($arResult['ITEMS'] as $key => $arItem)
    {
        $arItem['CHECK_QUANTITY'] = false;
        if (!isset($arItem['CATALOG_MEASURE_RATIO']))
            $arItem['CATALOG_MEASURE_RATIO'] = 1;
        if (!isset($arItem['CATALOG_QUANTITY']))
            $arItem['CATALOG_QUANTITY'] = 0;

        $arItem['CATALOG_QUANTITY'] = (
            0 < $arItem['CATALOG_QUANTITY'] && is_float($arItem['CATALOG_MEASURE_RATIO'])
            ? floatval($arItem['CATALOG_QUANTITY'])
            : intval($arItem['CATALOG_QUANTITY'])
        );

        $arItem["ARTICLE"] = $arItem["PROPERTIES"]["ARTICLE"]["~VALUE"];

        $arItem["SHORT_DESCRIPTION"] = "";

        if( isset($arItem["PROPERTIES"]["SHORT_DESCRIPTION"]["VALUE"]["TEXT"]))
        {
            $arItem["SHORT_DESCRIPTION"] = $arItem["PROPERTIES"]["SHORT_DESCRIPTION"]["VALUE"]["TEXT"];
            $arItem["~SHORT_DESCRIPTION"] = $arItem["PROPERTIES"]["SHORT_DESCRIPTION"]["~VALUE"]["TEXT"];
        }

        $arItem["MORE_PHOTOS"] = array();
        $arItem["MORE_PHOTOS_ID"] = array();


        $newArPhotos = array();
        $newArDesc = array();

        if($arItem["DETAIL_PICTURE"])
        {
            $newArPhotos[0] = $arItem["DETAIL_PICTURE"]["ID"];
            $newArDesc[0] = $arItem["DETAIL_PICTURE"]["DESCRIPTION"];
        }

        if(!empty($arItem["PROPERTIES"]["MORE_PHOTO"]["VALUE"]))
        {
            $newArPhotos = array_merge($newArPhotos, $arItem["PROPERTIES"]["MORE_PHOTO"]["VALUE"]);
            $newArDesc = array_merge($newArDesc, $arItem["PROPERTIES"]["MORE_PHOTO"]["DESCRIPTION"]);
        }


        if( !empty($newArPhotos) )
        {
            $arItem["MORE_PHOTOS"] = CPhoenixSku::getPhotos($newArPhotos, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"], $arWaterMark, $newArDesc, $arItem["NAME"]);

            $arItem["MORE_PHOTOS_COUNT"] = count($arItem["MORE_PHOTOS"]);
        }

        if(!empty($arItem["PROPERTIES"]["CHARS"]["VALUE"]))
        {
            $arItem["CHARACTERISTICS"] = CPhoenix::getCharacteristicsCatalogItem($arResult["PROPERTIES"], $PHOENIX_TEMPLATE_ARRAY["SHOW_CHARS_ITEMS_IN_DETAIL"]);
        }



        $arItem["PROPERTIES"]["BTN_ACTION"]["VALUE_XML_ID"] = ($arItem["PROPERTIES"]["BTN_ACTION"]["VALUE_XML_ID"]=="")?"form":$arItem["PROPERTIES"]["BTN_ACTION"]["VALUE_XML_ID"];


        $arItem["BTN"] = array(
            "BTN_NAME" => $arItem["PROPERTIES"]["BTN_NAME"]["VALUE"],
            "~BTN_NAME" => $arItem["PROPERTIES"]["BTN_NAME"]["~VALUE"],
            "ACTION" => $arItem["PROPERTIES"]["BTN_ACTION"]["VALUE_XML_ID"],
            "FORM_ID" => $arItem["PROPERTIES"]["BTN_FORM"]["VALUE"],
            "POPUP_ID" => $arItem["PROPERTIES"]["BTN_POPUP"]["VALUE"],
            "PRODUCT_ID" => ($arItem["PROPERTIES"]["BTN_OFFER_ID"]["VALUE"])?$arItem["PROPERTIES"]["BTN_OFFER_ID"]["VALUE"]:$arItem["PROPERTIES"]["BTN_PRODUCT_ID"]["VALUE"],
            "VIEW" => $arItem["PROPERTIES"]["BTN_VIEW"]["VALUE_XML_ID"],
            "BG_COLOR" => $arItem["PROPERTIES"]["BTN_BG_COLOR"]["VALUE"],
            "QUIZ_ID" => $arItem["PROPERTIES"]["BTN_QUIZ_ID"]["VALUE"],
            "LAND_ID" => $arItem["PROPERTIES"]["BTN_LAND"]["VALUE"],
            "URL" => $arItem["PROPERTIES"]["BTN_URL"]["VALUE"],
            "TARGET_BLANK" => $arItem["PROPERTIES"]["BTN_TARGET_BLANK"]["VALUE"],
            "HEADER" => strip_tags($arItem["~NAME"]),
            "ONCLICK" => $arItem["PROPERTIES"]["BTN_ONCLICK"]["VALUE"]
        );

        


        
        if ($arResult['MODULES']['catalog'])
        {
            $arItem['CATALOG'] = true;
            if (!isset($arItem['CATALOG_TYPE']))
                $arItem['CATALOG_TYPE'] = CCatalogProduct::TYPE_PRODUCT;
            if (
                (CCatalogProduct::TYPE_PRODUCT == $arItem['CATALOG_TYPE'] || CCatalogProduct::TYPE_SKU == $arItem['CATALOG_TYPE'])
                && !empty($arItem['OFFERS'])
            )
            {
                $arItem['CATALOG_TYPE'] = CCatalogProduct::TYPE_SKU;
            }
            switch ($arItem['CATALOG_TYPE'])
            {
                case CCatalogProduct::TYPE_SET:
                    $arItem['OFFERS'] = array();
                    $arItem['CHECK_QUANTITY'] = ('Y' == $arItem['CATALOG_QUANTITY_TRACE'] && 'N' == $arItem['CATALOG_CAN_BUY_ZERO']);
                    break;
                case CCatalogProduct::TYPE_SKU:
                    break;
                case CCatalogProduct::TYPE_PRODUCT:
                default:
                    $arItem['CHECK_QUANTITY'] = ('Y' == $arItem['CATALOG_QUANTITY_TRACE'] && 'N' == $arItem['CATALOG_CAN_BUY_ZERO']);
                    break;
            }
        }
        else
        {
            $arItem['CATALOG_TYPE'] = 0;
            $arItem['OFFERS'] = array();
        }

        $arItem['MEASURE_HTML'] = '';

        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["MEASURE"]["VALUE"][$arItem['ITEM_MEASURE']['ID']] == "Y")
            $arItem['MEASURE_HTML'] = "&nbsp;/&nbsp;".$arItem['ITEM_MEASURE']['TITLE'];


        if (isset($arItem['OFFERS']) && !empty($arItem['OFFERS']))
        {
            if(isset($arItem['SKU_PROPS']) && !empty($arItem["SKU_PROPS"]))
            {
                $arUsedFields = array();
                $arSortFields = array();

                $arNewOffers = array();


                foreach ($arItem['OFFERS'] as $keyOffer => $arOffer)
                {

                    $newArPhotos = array();
                    $newArDesc = array();

                    if($arOffer["DETAIL_PICTURE"])
                    {
                        $newArPhotos[0] = $arOffer["DETAIL_PICTURE"]["ID"];
                        $newArDesc[0] = $arOffer["DETAIL_PICTURE"]["DESCRIPTION"];
                    }

                    if(!empty($arOffer["PROPERTIES"]["MORE_PHOTO"]["VALUE"]))
                    {
                        $newArPhotos = array_merge($newArPhotos, $arOffer["PROPERTIES"]["MORE_PHOTO"]["VALUE"]);
                        $newArDesc = array_merge($newArDesc, $arOffer["PROPERTIES"]["MORE_PHOTO"]["DESCRIPTION"]);
                    }
                    

                    if( !empty($newArPhotos) )
                    {

                        $arOffer["MORE_PHOTOS"] = CPhoenixSku::getPhotos($newArPhotos, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"], $arWaterMark, $newArDesc);

                      
                        if($arOffer["PROPERTIES"]["NO_MERGE_PHOTOS"]["VALUE_XML_ID"] != "Y")
                            $arOffer["MORE_PHOTOS"] = array_merge($arOffer["MORE_PHOTOS"], $arItem["MORE_PHOTOS"] );

                        $arOffer["MORE_PHOTOS_COUNT"] = count($arOffer["MORE_PHOTOS"]);

                    }
                    else
                    {
                        $arOffer["MORE_PHOTOS"] = array();
                        $arOffer["MORE_PHOTOS_COUNT"] = $arOffer["MORE_PHOTOS_COUNT"] = 0;

                        if($arOffer["PROPERTIES"]["NO_MERGE_PHOTOS"]["VALUE_XML_ID"] != "Y")
                        {
                            $arOffer["MORE_PHOTOS"] = $arItem["MORE_PHOTOS"];
                            $arOffer["MORE_PHOTOS_COUNT"] = $arItem["MORE_PHOTOS_COUNT"];
                        }


                    }

                   

                    $arOffer["ARTICLE"] = $arOffer["PROPERTIES"]["ARTICLE"]["VALUE"];

                    if( strlen($arOffer["PREVIEW_TEXT"]) <= 0 )
                    {
                        $arOffer["PREVIEW_TEXT"] = $arItem["PREVIEW_TEXT"];
                        $arOffer["~PREVIEW_TEXT"] = $arItem["~PREVIEW_TEXT"];
                    }

                    $arOffer["SHORT_DESCRIPTION"] = "";

                    if( isset($arOffer["PROPERTIES"]["SHORT_DESCRIPTION"]["~VALUE"]["TEXT"]) )
                    {
                        $arOffer["SHORT_DESCRIPTION"] = $arOffer["PROPERTIES"]["SHORT_DESCRIPTION"]["VALUE"]["TEXT"];
                        $arOffer["~SHORT_DESCRIPTION"] = $arOffer["PROPERTIES"]["SHORT_DESCRIPTION"]["~VALUE"]["TEXT"];
                    }
                    else
                    {
                        if( isset($arItem["PROPERTIES"]["SHORT_DESCRIPTION"]["VALUE"]["TEXT"]))
                        {
                            $arOffer["SHORT_DESCRIPTION"] = $arItem["PROPERTIES"]["SHORT_DESCRIPTION"]["VALUE"]["TEXT"];
                            $arOffer["~SHORT_DESCRIPTION"] = $arItem["PROPERTIES"]["SHORT_DESCRIPTION"]["~VALUE"]["TEXT"];
                        }
                    }

                    $arNewOffers[$keyOffer] = $arOffer;
                }

                $arItem['OFFERS'] = $arNewOffers;

                if(!empty($arItem["SKU_MATRIX"]))
                {
                    foreach ($arItem["SKU_MATRIX"] as $keyMatrix => $valueMatrix)
                    {

                        if (!isset($arItem['OFFERS'][$keyMatrix]['TREE']))
                        {
                            $arItem['OFFERS'][$keyMatrix]['TREE'] = array();
                            $arItem['OFFERS'][$keyMatrix]['TREE_INFO'] = array();
                        }

                        if(!empty($valueMatrix))
                        {
                            foreach ($valueMatrix as $keyValueMatrix => $valueValueMatrix)
                            {

                                $arItem['OFFERS'][$keyMatrix]['TREE']['PROP_'.$valueValueMatrix["PROP_ID"]] = $valueValueMatrix['VALUE'];

                                $arItem['OFFERS'][$keyMatrix]['TREE_INFO'][$valueValueMatrix["PROP_ID"]] = $valueValueMatrix;

                                $arItem['OFFERS'][$keyMatrix]['SKU_SORT_'.$keyValueMatrix] = $arItem["SKU_MATRIX"][$keyMatrix][$keyValueMatrix]['SORT'];
                            }
                        }
                    }
                }


                $intSelected = -1;
                $arItem['MIN_PRICE'] = false;
                $arItem['MIN_BASIS_PRICE'] = false;

                foreach ($arItem['OFFERS'] as $keyOffer => $arOffer)
                {

                    if ($arItem['OFFER_ID_SELECTED'] > 0)
                        $foundOffer = ($arItem['OFFER_ID_SELECTED'] == $arOffer['ID']);
                    else
                        $foundOffer = $arOffer['CAN_BUY'];


                    

                    if ($foundOffer && $intSelected == -1)
                    {
                        $intSelected = $keyOffer;

                        $arItem['MIN_PRICE'] = (isset($arOffer['PRICE']) ? $arOffer['PRICE'] : $arOffer['MIN_PRICE']);

                        $arItem['MIN_BASIS_PRICE'] = $arOffer['MIN_PRICE'];
                    }
                    unset($foundOffer);

                    $arOffer['IS_OFFER'] = 'Y';
                    $arOffer['IBLOCK_ID'] = $arResult['IBLOCK_ID'];

                    $arPriceTypeID = array();
                    if(!empty($arOffer['PRICES']))
                    {
                        foreach($arOffer['PRICES'] as $priceKey => $arOfferPrice)
                        {
                            if($arOfferPrice['CAN_BUY'] == 'Y')
                                $arPriceTypeID[] = $arOfferPrice['PRICE_ID'];
                            if($arOffer['CATALOG_GROUP_NAME_'.$arOfferPrice['PRICE_ID']])
                                $arOffer['PRICES'][$priceKey]['GROUP_NAME'] = $arOffer['CATALOG_GROUP_NAME_'.$arOfferPrice['PRICE_ID']];
                        }
                    }

                    $sPriceMatrix = '';



                    if($arParams['USE_PRICE_COUNT'] == 'Y')
                    {

                        if(function_exists('CatalogGetPriceTableEx') && (isset($arOffer['PRICE_MATRIX'])) && !$arOffer['PRICE_MATRIX'] && $arPriceTypeID)
                        {


                            $arOffer["PRICE_MATRIX"] = CatalogGetPriceTableEx($arOffer["ID"], 0, $arPriceTypeID, 'Y', $arConvertParams);


                            if(count($arOffer['PRICE_MATRIX']['ROWS']) <= 1)
                            {
                                $arOffer['PRICE_MATRIX'] = '';
                            }

                        }


                        $arOffer = array_merge($arOffer, CPhoenix::formatPriceMatrix($arOffer));

                        $sPriceMatrix = CPhoenix::showPriceMatrix($arOffer, $arOffer['~CATALOG_MEASURE_NAME']);
                    }

                    $measureHTML = '';

                    if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["MEASURE"]["VALUE"][$arOffer['ITEM_MEASURE']['ID']] == "Y")
                        $measureHTML = "&nbsp;/&nbsp;".$arOffer['ITEM_MEASURE']['TITLE'];

                    $arOffer['DETAIL_PAGE_URL'] = $arItem["DETAIL_PAGE_URL"]."?oID=".$arOffer["ID"];



                    $preBtn = false;

                    $preBtnOnly = ($arItem["PROPERTIES"]["PREORDER_ONLY"]["VALUE"]=="Y")?$arItem["PROPERTIES"]["PREORDER_ONLY"]["VALUE"]:$arOffer["PROPERTIES"]["PREORDER_ONLY"]["VALUE"];

                    if($preBtnOnly=="Y" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["MODE_PREORDER_FORM"]["VALUE"] != "N")
                        $preBtn = true;
                    
                    else
                        $preBtn = $arResult["GLOBAL_SHOWPREORDERBTN"] && !$arOffer["CAN_BUY"];


                   


                    $htmlQuantity = "";
                    $modeArchive = "";

                    if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_ON"]["VALUE"]["ACTIVE"] == "Y")
                    {
                        $desc_quantity = CPhoenixSku::getDescQuantity($arOffer["PRODUCT"]["QUANTITY"], $arOffer['ITEM_MEASURE']['TITLE']);


                        $quantity=floatval($arOffer["PRODUCT"]["QUANTITY"]);

                        if($quantity>0)
                            $avail = "InStock";

                        else
                            $avail = "OutOfStock";
                    }

                    

                    if($arItem["PROPERTIES"]["MODE_ARCHIVE"]["VALUE"]=="Y")
                    {
                        $modeArchive = "Y";
                        $desc_quantity = array(
                            "QUANTITY_STYLE"=> "",
                            "TEXT"=>$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_MODE_ARCHIVE"]

                        );

                        $preBtn=false;
                    }

                    else if($arOffer["PROPERTIES"]["MODE_ARCHIVE"]["VALUE"]=="Y")
                    {
                        $modeArchive = "Y";
                        $desc_quantity = array(
                            "QUANTITY_STYLE"=> "",
                            "TEXT"=>$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_MODE_ARCHIVE"]

                        );

                        $preBtn=false;
                    }
                    else if($preBtn)
                        $desc_quantity = array(
                            "QUANTITY_STYLE"=> "",
                            "TEXT"=>$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_PREORDER_ONLY"]

                        );
                        
                    if($preBtn)
                        $avail = "PreOrder";



                    if(isset($desc_quantity))
                    {
                        $quantity = "";

                        if(strlen($desc_quantity["QUANTITY"]))
                            $quantity = ": <span class='quantity bold'>".$desc_quantity["QUANTITY"]."</span>";
                        

                        $htmlQuantity = '<div class="detail-available '.$desc_quantity["QUANTITY_STYLE"].'"><span class="text">'.$desc_quantity["TEXT"].'</span>'.$quantity.'</div>';
                    }

                    unset($desc_quantity);


                    $arPrice = Array(
                        "UNROUND_BASE_PRICE" => NULL,
                        "UNROUND_PRICE" => NULL,
                        "BASE_PRICE" => NULL,
                        "PRICE" => "-1",
                        "CURRENCY" => (isset($arResult["ORIGINAL_PARAMETERS"]["CURRENCY_ID"]))?$arResult["ORIGINAL_PARAMETERS"]["CURRENCY_ID"]:"",
                        "MIN_QUANTITY" => "1",
                        "DISCOUNT_DIFF" => 0
                    );

                    if(isset($arOffer["ITEM_PRICES"][$arOffer['ITEM_PRICE_SELECTED']]))
                        $arPrice = $arOffer["ITEM_PRICES"][$arOffer['ITEM_PRICE_SELECTED']];
                    


                    $arOneRow = array(
                        'ID' => $arOffer['ID'],
                        'NAME' => $arOffer['~NAME'],
                        'TREE' => $arOffer['TREE'],
                        'TREE_INFO' => $arOffer['TREE_INFO'],
                        'ARTICLE' => $arOffer['ARTICLE'],
                        'PRICE' => $arPrice,
                        'CAN_BUY' => $arOffer['CAN_BUY'],
                        'SKU_LIST_CHARS' => $arOffer["SKU_LIST_CHARS"],
                        'ITEMPROP_AVAILABLE'=>$avail,
                        'IN_BASKET' => '',
                        'IN_DELAY' => '',
                        'IN_COMPARE' => '',
                        'PRICES' => $arOffer['PRICES'],
                        'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
                        'SHOW_DISCOUNT_PERCENT_NUMBER' => $arParams['SHOW_DISCOUNT_PERCENT_NUMBER'],
                        'PRICE_MATRIX' => $sPriceMatrix,
                        'MORE_PHOTOS' => $arOffer['MORE_PHOTOS'],
                        'MORE_PHOTOS_COUNT' => $arOffer['MORE_PHOTOS_COUNT'],
                        'CHECK_QUANTITY' => $arOffer['CHECK_QUANTITY'],
                        'MAX_QUANTITY' => $arOffer["PRODUCT"]["QUANTITY"],
                        'STEP_QUANTITY' => $arOffer['CATALOG_MEASURE_RATIO'],
                        'QUANTITY_FLOAT' => is_double($arOffer['CATALOG_MEASURE_RATIO']),
                        'MEASURE_PRICE' => $measureHTML,
                        'MEASURE' => $arOffer['ITEM_MEASURE']['TITLE'],
                        'URL' => $arOffer['DETAIL_PAGE_URL'],
                        'SHOW_MEASURE' => ($arParams["SHOW_MEASURE"]=="Y" ? "Y" : "N"),
                        'SHOW_ONE_CLICK_BUY' => "N",
                        'ONE_CLICK_BUY' => GetMessage("ONE_CLICK_BUY"),
                        'OFFER_PROPS' => $arOfferProps,
                        'PRODUCT_QUANTITY_VARIABLE' => $arParams["PRODUCT_QUANTITY_VARIABLE"],
                        'SUBSCRIPTION' => true,
                        'ITEM_PRICE_MODE' => $arOffer['ITEM_PRICE_MODE'],
                        'ITEM_PRICES' => $arOffer['ITEM_PRICES'],
                        'ITEM_PRICE_SELECTED' => $arOffer['ITEM_PRICE_SELECTED'],
                        'ITEM_QUANTITY_RANGES' => $arOffer['ITEM_QUANTITY_RANGES'],
                        'ITEM_QUANTITY_RANGE_SELECTED' => $arOffer['ITEM_QUANTITY_RANGE_SELECTED'],
                        'ITEM_MEASURE_RATIOS' => $arOffer['ITEM_MEASURE_RATIOS'],
                        'ITEM_MEASURE_RATIO_SELECTED' => $arOffer['ITEM_MEASURE_RATIO_SELECTED'],
                        'SHORT_DESCRIPTION' => $arOffer['SHORT_DESCRIPTION'],
                        'PREVIEW_TEXT' => $arOffer['~PREVIEW_TEXT'],
                        'OFFER_GROUP' => (isset($offerSet[$arOffer['ID']]) && $offerSet[$arOffer['ID']]),
                        'CATALOG_SUBSCRIBE'=>$arOffer["CATALOG_SUBSCRIBE"],
                        'PREORDER_ONLY'=>$preBtnOnly,
                        'MODE_DISALLOW_ORDER'=>($arItem["PROPERTIES"]["MODE_DISALLOW_ORDER"]["VALUE"]=="Y")?$arItem["PROPERTIES"]["MODE_DISALLOW_ORDER"]["VALUE"]:$arOffer["PROPERTIES"]["MODE_DISALLOW_ORDER"]["VALUE"],
                        'MODE_ARCHIVE'=>$modeArchive,
                        'QUANTITY_HTML'=>$htmlQuantity,
                        'SHOWPREORDERBTN'=>$preBtn,
                    );
                    

                    if($arOneRow["PRICE"]["DISCOUNT_DIFF"]){
                        $percent=round(($arOneRow["PRICE"]["DISCOUNT_DIFF"]/$arOneRow["PRICE"]["VALUE"])*100, 2);
                        $arOneRow["PRICE"]["DISCOUNT_DIFF_PERCENT_RAW"]="-".$percent."%";
                    }
                    
                    $arItem["SKU_MATRIX"][$keyOffer] = $arOneRow;

                    unset($arPrice);

                }


                if (-1 == $intSelected)
                    $intSelected = 0;



                $arItem['JS_OFFERS'] = $arItem["SKU_MATRIX"];
                $arItem['OFFERS_SELECTED'] = $intSelected;
                $arItem['OFFERS_PROPS_DISPLAY'] = $boolSKUDisplayProperties;
             

            }

            else
            {
                $arItem["CATALOG_AVAILABLE"] = "";

                $minPrice = CPhoenix::getMinPriceFromOffersExt(
                    $arItem['OFFERS'],
                    $boolConvert ? $arResult['CONVERT_CURRENCY']['CURRENCY_ID'] : $strBaseCurrency
                );
                $arItem['MIN_PRICE'] = $minPrice["VALUE"];
                $arItem['DIFF'] = $minPrice["DIFF"];

                unset($minPrice);

                $arItem['OFFER_WITHOUT_SKU'] = 'Y';
            }

            

        }
        else
        {
            if($arParams['USE_PRICE_COUNT'] == 'Y')
                $arItem = array_merge($arItem, CPhoenix::formatPriceMatrix($arItem));
            
        }


        $arItem["MODAL_WINDOWS"] = 
        $arItem["FORMS"] =
        $arItem["QUIZS"] = array();


        if(!empty($arItem["PROPERTIES"]["MODAL_WINDOWS"]["VALUE"]))
        {
            $arElements = Array();

            $arSelect = Array("ID", "NAME");
            $arFilter = Array("ID"=>$arItem["PROPERTIES"]["MODAL_WINDOWS"]["VALUE"], "ACTIVE"=>"Y");

            $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, false, $arSelect);
            while($ob = $res->GetNextElement())
            { 
                $arElem = $ob->GetFields();  
                $arElem["PROPERTIES"] = $ob->GetProperties();
                
                $arElements[] = $arElem;
            }

            $arItem["MODAL_WINDOWS"] = $arElements;
        }


        if(!empty($arItem["PROPERTIES"]["FORMS"]["VALUE"]))
        {
            $arElements = Array();

            $arSelect = Array("ID", "NAME");
            $arFilter = Array("ID"=>$arItem["PROPERTIES"]["FORMS"]["VALUE"], "ACTIVE"=>"Y");

            $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, false, $arSelect);
            while($ob = $res->GetNextElement())
            { 
                $arElem = $ob->GetFields();  
                $arElem["PROPERTIES"] = $ob->GetProperties();
                
                $arElements[] = $arElem;
            }

            $arItem["FORMS"] = $arElements;
        }


        if(IsModuleInstalled("concept.quiz"))
        {

            if(!empty($arItem["PROPERTIES"]["QUIZS"]["VALUE"]))
            {
                $arElements = Array();

                $arSelect = Array("ID", "NAME");
                $arFilter = Array("ID"=>$arItem["PROPERTIES"]["QUIZS"]["VALUE"], "ACTIVE"=>"Y");

                $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, false, $arSelect);
                while($ob = $res->GetNextElement())
                { 
                    $arElem = $ob->GetFields();  
                    $arElem["PROPERTIES"] = $ob->GetProperties();
                    
                    $arElements[] = $arElem;
                }

                $arItem["QUIZS"] = $arElements;
            }
        }





        $arNewItemsList[$key] = $arItem;

    }

    $arResult['ITEMS'] = $arNewItemsList;

    unset($arNewItemsList);

    $arResult["ITEMS_COUNT"] = (!empty($arResult['ITEMS']))?count($arResult['ITEMS']):0;



    foreach ($arResult['ITEMS'] as $key => $arItem)
    {
        $haveOffers = !empty($arItem['OFFERS']);

        if($haveOffers)
            $arResult['ITEMS'][$key]["FIRST_ITEM"] = $arItem['JS_OFFERS'][$arItem["OFFERS_SELECTED"]];
        else
        {
            $measureHTML = '';

            if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["MEASURE"]["VALUE"][$arItem['ITEM_MEASURE']['ID']] == "Y")
                $measureHTML = "&nbsp;/&nbsp;".$arItem['ITEM_MEASURE']['TITLE'];



            $preBtn = false;

            if($arItem["PROPERTIES"]["PREORDER_ONLY"]["VALUE"]=="Y" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["MODE_PREORDER_FORM"]["VALUE"] != "N")
                $preBtn = true;
            
            else
                $preBtn = $arResult["GLOBAL_SHOWPREORDERBTN"] && !$arItem["CAN_BUY"];


            $arResult['ITEMS'][$key]["ITEMPROP_AVAILABLE"] = "";
            $htmlQuantity = "";

            if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_ON"]["VALUE"]["ACTIVE"] == "Y")
            {

                $desc_quantity = CPhoenixSku::getDescQuantity($arItem["PRODUCT"]["QUANTITY"], $arItem['ITEM_MEASURE']['TITLE']);

                $quantity=floatval($arResult['ITEMS'][$key]["PRODUCT"]["QUANTITY"]);
                if($quantity>0)
                    $arResult['ITEMS'][$key]["ITEMPROP_AVAILABLE"] = "InStock";

                else
                    $arResult['ITEMS'][$key]["ITEMPROP_AVAILABLE"] = "OutOfStock";
            }
                
                
                if($arItem["PROPERTIES"]["MODE_ARCHIVE"]["VALUE"]=="Y")
                {
                    $desc_quantity = array(
                        "QUANTITY_STYLE"=> "",
                        "TEXT"=>$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_MODE_ARCHIVE"]

                    );

                    $preBtn = false;
                    
                }

                else if($preBtn)
                    $desc_quantity = array(
                        "QUANTITY_STYLE"=> "",
                        "TEXT"=> $PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_PREORDER_ONLY"]

                    );

                if($preBtn)
                    $arResult['ITEMS'][$key]["ITEMPROP_AVAILABLE"] = "PreOrder";

                    

                if(isset($desc_quantity))
                {

                    $quantity = "";

                    if(strlen($desc_quantity["QUANTITY"]))
                        $quantity = ": <span class='quantity bold'>".$desc_quantity["QUANTITY"]."</span>";
                    

                    $htmlQuantity = '<div class="detail-available '.$desc_quantity["QUANTITY_STYLE"].'"><span class="text">'.$desc_quantity["TEXT"].'</span>'.$quantity.'</div>';

                }

                unset($desc_quantity);
           

            $arPrice = Array(
                "UNROUND_BASE_PRICE" => NULL,
                "UNROUND_PRICE" => NULL,
                "BASE_PRICE" => NULL,
                "PRICE" => "-1",
                "CURRENCY" => (isset($arItem["ORIGINAL_PARAMETERS"]["CURRENCY_ID"]))?$arItem["ORIGINAL_PARAMETERS"]["CURRENCY_ID"]:"",
                "MIN_QUANTITY" => "1"
            );

            if(isset($arItem["ITEM_PRICES"][$arItem['ITEM_PRICE_SELECTED']]))
                $arPrice = $arItem["ITEM_PRICES"][$arItem['ITEM_PRICE_SELECTED']];

            $arResult['ITEMS'][$key]["FIRST_ITEM"] = array(
                'ID' => $arItem['ID'],
                'NAME' => $arItem['~NAME'],
                'ARTICLE' => $arItem['~ARTICLE'],
                'PRICE' => $arPrice,
                'CAN_BUY' => $arItem['CAN_BUY'],
                'ITEMPROP_AVAILABLE'=>$arItem["ITEMPROP_AVAILABLE"],
                'PRICES' => $arItem['PRICES'],
                'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
                'SHOW_DISCOUNT_PERCENT_NUMBER' => $arParams['SHOW_DISCOUNT_PERCENT_NUMBER'],
                'MORE_PHOTOS' => $arItem['MORE_PHOTOS'],
                'MORE_PHOTOS_COUNT' => $arItem['MORE_PHOTOS_COUNT'],
                'CHECK_QUANTITY' => $arItem['CHECK_QUANTITY'],
                'MAX_QUANTITY' => $arItem["PRODUCT"]["QUANTITY"],
                'STEP_QUANTITY' => $arItem['CATALOG_MEASURE_RATIO'],
                'QUANTITY_FLOAT' => is_double($arItem['CATALOG_MEASURE_RATIO']),
                'MEASURE_PRICE' => $measureHTML,
                'MEASURE' => $arItem['ITEM_MEASURE']['TITLE'],
                'URL' => $arItem['DETAIL_PAGE_URL'],
                'SHOW_MEASURE' => ($arParams["SHOW_MEASURE"]=="Y" ? "Y" : "N"),
                'SHOW_ONE_CLICK_BUY' => "N",
                'OFFER_PROPS' => $arItemProps,
                'PRODUCT_QUANTITY_VARIABLE' => $arParams["PRODUCT_QUANTITY_VARIABLE"],
                'SUBSCRIPTION' => true,
                'ITEM_PRICE_MODE' => $arItem['ITEM_PRICE_MODE'],
                'ITEM_PRICES' => $arItem['ITEM_PRICES'],
                'ITEM_PRICE_SELECTED' => $arItem['ITEM_PRICE_SELECTED'],
                'ITEM_QUANTITY_RANGES' => $arItem['ITEM_QUANTITY_RANGES'],
                'ITEM_QUANTITY_RANGE_SELECTED' => $arItem['ITEM_QUANTITY_RANGE_SELECTED'],
                'ITEM_MEASURE_RATIOS' => $arItem['ITEM_MEASURE_RATIOS'],
                'ITEM_MEASURE_RATIO_SELECTED' => $arItem['ITEM_MEASURE_RATIO_SELECTED'],
                'SHORT_DESCRIPTION' => $arItem['SHORT_DESCRIPTION'],
                'PREVIEW_TEXT' => $arItem['~PREVIEW_TEXT'],
                'CATALOG_SUBSCRIBE'=>$arItem["CATALOG_SUBSCRIBE"],
                'PREORDER_ONLY'=>$arItem["PROPERTIES"]["PREORDER_ONLY"]["VALUE"],
                'MODE_DISALLOW_ORDER'=>$arItem["PROPERTIES"]["MODE_DISALLOW_ORDER"]["VALUE"],
                'MODE_ARCHIVE'=>$arItem["PROPERTIES"]["MODE_ARCHIVE"]["VALUE"],
                'MODE_HIDE'=>$arItem["PROPERTIES"]["MODE_HIDE"]["VALUE"],
                'QUANTITY_HTML'=> $htmlQuantity,
                'SHOWPREORDERBTN'=> $preBtn,
                'MODE_DISALLOW_ORDER'=>$arItem["PROPERTIES"]["MODE_DISALLOW_ORDER"]["VALUE"],
                'MODE_ARCHIVE'=>$arItem["PROPERTIES"]["MODE_ARCHIVE"]["VALUE"]
            );

            unset($arPrice);
        }
        
    }
}


$arResult["rating"] = array();

if(!empty($arResult["ITEMS"]) && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_VOTE"]["VALUE"]["ACTIVE"] == "Y")
{
    
    if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_REVIEW"]["VALUE"]["ACTIVE"] == "Y")
    {
        $arResult["RATING_VIEW"] = "full";
        $arResult["ITEMS_ID"] = array();
        foreach ($arResult["ITEMS"] as $key => $arItem){
           $arResult["ITEMS_ID"][] = $arItem["ID"];
        }
    }
    else
    {
        $arResult["RATING_VIEW"] = "simple";

        foreach ($arResult["ITEMS"] as $key => $arItem){
           $arResult["rating"][$arItem["ID"]] = (strlen($arItem["PROPERTIES"]["rating"]["VALUE"]))?round($arItem["PROPERTIES"]["rating"]["VALUE"]):"0";
        }
    }
    

    $cp = $this->__component;
 
    if (is_object($cp))
    {
        $cp->SetResultCacheKeys(array('ITEMS_ID', 'rating'));
    }
}
