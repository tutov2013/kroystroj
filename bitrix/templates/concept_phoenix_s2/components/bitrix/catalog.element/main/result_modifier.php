<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>

<?
/*
$arResult["PROPERTIES"]["MODE_HIDE"]["VALUE"]
*/


global $PHOENIX_TEMPLATE_ARRAY;

$haveOffers = !empty($arResult['OFFERS']);

CPhoenix::SetElementProperties($arResult);


if($haveOffers)
{
    
    CPhoenixSku::getHIBlockOptions();

    CPhoenix::SetElementOffersProperties($PHOENIX_TEMPLATE_ARRAY["ITEMS"]['OFFERS']["IBLOCK_ID"], $arResult);

    if($PHOENIX_TEMPLATE_ARRAY["GLOBAL_SKU"] == "Y")
    {
        $arSKU = CPhoenix::GetSkuProperties(
            array(
                "IBLOCK_ID" => $arParams['IBLOCK_ID'],
                "OFFERS" => $arResult['OFFERS'],
                "SKU_FIELDS" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['VALUE_'],
                "SITE_ID" => SITE_ID,
            )
        );

        $arResult['SKU_PROPS'] = $arSKU["SKU_PROP_LIST"];

    }
}


$arResult["GLOBAL_SHOWPREORDERBTN"] = ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["AUTO_MODE_PREORDER"]["VALUE"]["ACTIVE"]=="Y" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["MODE_PREORDER_FORM"]["VALUE"] != "N")? true:false;



$strBaseCurrency = '';
$boolConvert = isset($arResult['CONVERT_CURRENCY']['CURRENCY_ID']);
    
if (!$boolConvert)
    $strBaseCurrency = CCurrency::GetBaseCurrency();

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



$arResult['CHECK_QUANTITY'] = false;
if (!isset($arResult['CATALOG_MEASURE_RATIO']))
    $arResult['CATALOG_MEASURE_RATIO'] = 1;
if (!isset($arResult['CATALOG_QUANTITY']))
    $arResult['CATALOG_QUANTITY'] = 0;
$arResult['CATALOG_QUANTITY'] = (
    0 < $arResult['CATALOG_QUANTITY'] && is_float($arResult['CATALOG_MEASURE_RATIO'])
    ? (float)$arResult['CATALOG_QUANTITY']
    : (int)$arResult['CATALOG_QUANTITY']
);
$arResult['CATALOG'] = false;

if ($arResult['MODULES']['catalog'])
{
    $arResult['CATALOG'] = true;
    if (!isset($arResult['CATALOG_TYPE']))
        $arResult['CATALOG_TYPE'] = CCatalogProduct::TYPE_PRODUCT;
    if (
        (CCatalogProduct::TYPE_PRODUCT == $arResult['CATALOG_TYPE'] || CCatalogProduct::TYPE_SKU == $arResult['CATALOG_TYPE'])
        && !empty($arResult['OFFERS'])
    )
    {
        $arResult['CATALOG_TYPE'] = CCatalogProduct::TYPE_SKU;
    }
    switch ($arResult['CATALOG_TYPE'])
    {
        case CCatalogProduct::TYPE_SET:
            $arResult['OFFERS'] = array();
            $arResult['CHECK_QUANTITY'] = ('Y' == $arResult['CATALOG_QUANTITY_TRACE'] && 'N' == $arResult['CATALOG_CAN_BUY_ZERO']);
            break;
        case CCatalogProduct::TYPE_SKU:
            break;
        case CCatalogProduct::TYPE_PRODUCT:
        default:
            $arResult['CHECK_QUANTITY'] = ('Y' == $arResult['CATALOG_QUANTITY_TRACE'] && 'N' == $arResult['CATALOG_CAN_BUY_ZERO']);
            break;
    }
}
else
{
    $arResult['CATALOG_TYPE'] = 0;
    $arResult['OFFERS'] = array();
}


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

$arResult['NO_PHOTO'] = SITE_TEMPLATE_PATH.'/images/ufo.png';
$arResult["ARTICLE"] = $arResult["PROPERTIES"]["ARTICLE"]["VALUE"];
$arResult["~ARTICLE"] = $arResult["PROPERTIES"]["ARTICLE"]["~VALUE"];

$arResult["MORE_PHOTOS"] = array();


$newArPhotos = array();
$newArDesc = array();

if($arResult["DETAIL_PICTURE"])
{
    $newArPhotos[0] = $arResult["DETAIL_PICTURE"]["ID"];
    $newArDesc[0] = $arResult["DETAIL_PICTURE"]["DESCRIPTION"];
}

if(!empty($arResult["PROPERTIES"]["MORE_PHOTO"]["VALUE"]))
{
    $newArPhotos = array_merge($newArPhotos, $arResult["PROPERTIES"]["MORE_PHOTO"]["VALUE"]);
    $newArDesc = array_merge($newArDesc, $arResult["PROPERTIES"]["MORE_PHOTO"]["DESCRIPTION"]);
}


if(!empty($newArPhotos))
{

    $arResult["MORE_PHOTOS"] = CPhoenixSku::getPhotos($newArPhotos, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"], $arWaterMark, $newArDesc, $arResult["NAME"]);

    $arResult["MORE_PHOTOS_COUNT"] = count($arResult["MORE_PHOTOS"]);

}

$arResult["FIRST_PICTURE_SMALL_SRC"] = (!empty($arResult["MORE_PHOTOS"]))? $arResult["MORE_PHOTOS"][0]["SMALL"]["SRC"]: $arResult['NO_PHOTO'];




$arOfferSet = array();
$arResult['OFFER_GROUP'] = false;

$arResult["SKU_EMPTY"] = empty($arSKU["SKU_MATRIX"]);



if( $haveOffers )
{

    if(isset($arResult['SKU_PROPS']) && !empty($arResult['SKU_PROPS']))
    {

        $arIDS = array($arResult['ID']);
        $arNewOffers = array();

        foreach ($arResult['OFFERS'] as $keyOffer => $arOffer)
        {

            $arOffer['OFFER_GROUP'] = false;
            $arIDS[] = $arOffer['ID'];

            $photo = 0;

            if($arOffer["DETAIL_PICTURE"])
                $photo = $arOffer["DETAIL_PICTURE"]["ID"];

            else if(!empty($arOffer["PROPERTIES"]["MORE_PHOTO"]["VALUE"]))
                $photo = $arOffer["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0];

            if( $photo )
            {
                
                $arOffer["PHOTO"] = CFile::ResizeImageGet($photo, array('width'=>290, 'height'=>240), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);
            }
            else
            {

                if($arOffer["PROPERTIES"]["NO_MERGE_PHOTOS"]["VALUE_XML_ID"] != "Y")
                {
                    if( isset($arResult["PHOTO"]) )
                        $arOffer["PHOTO"] = $arResult["PHOTO"];
                }
            }

            $arOffer["ARTICLE"] = $arOffer["PROPERTIES"]["ARTICLE"]["~VALUE"];

            
            if( !strlen($arOffer["PREVIEW_TEXT"]) )
            {
                if(strlen($arResult["PREVIEW_TEXT"]))
                {
                    $arOffer["PREVIEW_TEXT"] = $arResult["PREVIEW_TEXT"];
                    $arOffer["~PREVIEW_TEXT"] = $arResult["~PREVIEW_TEXT"];
                }
                
            }

            $arNewOffers[$keyOffer] = $arOffer;
        }


        $arResult['OFFERS'] = $arNewOffers;




        if(!empty($arSKU["SKU_MATRIX"]))
        {
            foreach ($arSKU["SKU_MATRIX"] as $keyMatrix => $valueMatrix)
            {
                if (!isset($arResult['OFFERS'][$keyMatrix]['TREE']))
                {
                    $arResult['OFFERS'][$keyMatrix]['TREE'] = array();
                    $arResult['OFFERS'][$keyMatrix]['TREE_INFO'] = array();
                }

                if(!empty($valueMatrix))
                {

                    foreach ($valueMatrix as $keyValueMatrix => $valueValueMatrix)
                    {
                        
                        $arResult['OFFERS'][$keyMatrix]['TREE']['PROP_'.$valueValueMatrix["PROP_ID"]] = $valueValueMatrix['VALUE'];
                        $arResult['OFFERS'][$keyMatrix]['TREE_INFO'][$valueValueMatrix["PROP_ID"]] = $valueValueMatrix;
                        $arResult['OFFERS'][$keyMatrix]['SKU_SORT_'.$keyValueMatrix] = $arSKU["SKU_MATRIX"][$keyMatrix][$keyValueMatrix]['SORT'];
                    }

                }
            }        
        }




        $offerSet = array();
        if (!empty($arIDS) && CBXFeatures::IsFeatureEnabled('CatCompleteSet'))
        {
            $offerSet = array_fill_keys($arIDS, false);
            $rsSets = CCatalogProductSet::getList(
                array(),
                array(
                    '@OWNER_ID' => $arIDS,
                    '=SET_ID' => 0,
                    '=TYPE' => CCatalogProductSet::TYPE_GROUP
                ),
                false,
                false,
                array('ID', 'OWNER_ID')
            );
            while ($arSet = $rsSets->Fetch())
            {
                $arSet['OWNER_ID'] = (int)$arSet['OWNER_ID'];
                $offerSet[$arSet['OWNER_ID']] = true;
                $arResult['OFFER_GROUP'] = true;
            }
            if ($offerSet[$arResult['ID']])
            {
                foreach ($offerSet as &$setOfferValue)
                {
                    if ($setOfferValue === false)
                    {
                        $setOfferValue = true;
                    }
                }
                unset($setOfferValue);
                unset($offerSet[$arResult['ID']]);
            }
            if ($arResult['OFFER_GROUP'])
            {
                $offerSet = array_filter($offerSet);
                $arResult['OFFER_GROUP_VALUES'] = array_keys($offerSet);
            }
        }



        $intSelected = -1;
        $arResult['MIN_PRICE'] = false;
        $arResult['MIN_BASIS_PRICE'] = false;

        foreach ($arResult['OFFERS'] as $keyOffer => $arOffer)
        {
            if ($arResult['OFFER_ID_SELECTED'] > 0)
                $foundOffer = ($arResult['OFFER_ID_SELECTED'] == $arOffer['ID']);
            else
                $foundOffer = $arOffer['CAN_BUY'];
            if ($foundOffer && $intSelected == -1)
            {
                $intSelected = $keyOffer;
                $arResult['MIN_PRICE'] = (isset($arOffer['RATIO_PRICE']) ? $arOffer['RATIO_PRICE'] : $arOffer['MIN_PRICE']);

                $arResult['MIN_BASIS_PRICE'] = $arOffer['MIN_PRICE'];
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

            if ($arOffer['ID']==$_GET['oID'] && !empty($arOffer['TREE']) && is_array($arOffer['TREE']))
                $intSelected = $keyOffer;
            


            $sPriceMatrix = '';



            if($arParams['USE_PRICE_COUNT'] == 'Y')
            {

                if(function_exists('CatalogGetPriceTableEx') && (isset($arOffer['PRICE_MATRIX'])) && !$arOffer['PRICE_MATRIX'] && $arPriceTypeID)
                {


                    $arOffer["PRICE_MATRIX"] = CatalogGetPriceTableEx($arOffer["ID"], 0, $arPriceTypeID, 'Y', $arConvertParams);


                    if(count($arOffer['PRICE_MATRIX']['ROWS']) <= 1)
                        $arOffer['PRICE_MATRIX'] = '';
                    

                }


                $arOffer = array_merge($arOffer, CPhoenix::formatPriceMatrix($arOffer));

                $sPriceMatrix = CPhoenix::showPriceMatrixInDetail($arOffer, $arOffer['~CATALOG_MEASURE_NAME']);
            }

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

                $arOffer["MORE_PHOTOS"] = CPhoenixSku::getPhotos($newArPhotos, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"], $arWaterMark, $newArDesc, $arResult["NAME"]);


              
                if($arOffer["PROPERTIES"]["NO_MERGE_PHOTOS"]["VALUE_XML_ID"] != "Y")
                    $arOffer["MORE_PHOTOS"] = array_merge($arOffer["MORE_PHOTOS"], $arResult["MORE_PHOTOS"] );
                

                $arOffer["MORE_PHOTOS_COUNT"] = count($arOffer["MORE_PHOTOS"]);

            }
            else
            {
                $arOffer["MORE_PHOTOS"] = $arOffer["MORE_PHOTOS_ID"] = array();
                $arOffer["MORE_PHOTOS_COUNT"] = $arOffer["MORE_PHOTOS_COUNT"] = 0;

                if($arOffer["PROPERTIES"]["NO_MERGE_PHOTOS"]["VALUE_XML_ID"] != "Y")
                {
                    $arOffer["MORE_PHOTOS"] = $arResult["MORE_PHOTOS"];
                    $arOffer["MORE_PHOTOS_COUNT"] = $arResult["MORE_PHOTOS_COUNT"];
                }
            }


            $skuProps = array();
            

            if(!empty($PHOENIX_TEMPLATE_ARRAY['SHOW_SKU_ITEMS_IN_DETAIL']))
            {
                foreach ($PHOENIX_TEMPLATE_ARRAY['SHOW_SKU_ITEMS_IN_DETAIL'] as $keySkuList => $valueSkuList)
                {

                    if($arOffer["PROPERTIES"][$valueSkuList]["VALUE"])
                        $skuProps[] = $arOffer["PROPERTIES"][$valueSkuList]["ID"];
                    
                    
                }
            }

            $arOffer["SKU_LIST_CHARS"] = CPhoenix::getPropsCharsCatalogItem($arOffer["PROPERTIES"], $skuProps);

         

            $measureHTML = "";

            if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["MEASURE"]["VALUE"][$arOffer['ITEM_MEASURE']['ID']] == "Y")
                $measureHTML = "&nbsp;/&nbsp;".$arOffer['ITEM_MEASURE']['TITLE'];


            if (isset($arOfferSet[$arOffer['ID']]))
            {
                $arOffer['OFFER_GROUP'] = true;
                $arResult['OFFERS'][$keyOffer]['OFFER_GROUP'] = true;
            }

            $arOffer['DETAIL_PAGE_URL'] = $arResult["DETAIL_PAGE_URL"]."?oID=".$arOffer["ID"];

            

            $preBtn = false;


            $preBtnOnly = ($arResult["PROPERTIES"]["PREORDER_ONLY"]["VALUE"]=="Y")?$arResult["PROPERTIES"]["PREORDER_ONLY"]["VALUE"]:$arOffer["PROPERTIES"]["PREORDER_ONLY"]["VALUE"];

            if($preBtnOnly=="Y" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["MODE_PREORDER_FORM"]["VALUE"] != "N")
                $preBtn = true;
            
            else
                $preBtn = $arResult["GLOBAL_SHOWPREORDERBTN"] && !$arOffer["CAN_BUY"];


            $arStoreProductResult = array();

            if($arParams["SHOW_STORE_BLOCK"])
            {

                $rsStoreProduct = \Bitrix\Catalog\StoreProductTable::getList(array(
                    'filter' => array('=PRODUCT_ID'=>$arOffer["ID"],'STORE.ACTIVE'=>'Y'),
                ));

                while($arStoreProduct=$rsStoreProduct->fetch())
                {
                    $arStoreProduct["MEASURE"] = (isset($measureHTML{0})? $arOffer['ITEM_MEASURE']['TITLE']: "");
                    $arStoreProductResult[$arStoreProduct["STORE_ID"]] = $arStoreProduct;
                }
            }




            $htmlQuantity = "";
            $modeArchive = "";
            $avail = "";

            if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_ON"]["VALUE"]["ACTIVE"] == "Y")
            {
                $desc_quantity = CPhoenixSku::getDescQuantity($arOffer["PRODUCT"]["QUANTITY"], $arOffer['ITEM_MEASURE']['TITLE'], !empty($arStoreProductResult));

             
                $quantity=floatval($arOffer["PRODUCT"]["QUANTITY"]);

                if($quantity>0)
                    $avail = "InStock";

                else
                    $avail = "OutOfStock";

            }

            
            if($arResult["PROPERTIES"]["MODE_ARCHIVE"]["VALUE"]=="Y")
            {
                $modeArchive = "Y";
                $desc_quantity = array(
                    "QUANTITY_STYLE"=> "",
                    "TEXT"=>$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_MODE_ARCHIVE"]

                );
                $preBtn = false;
            }

            else if($arOffer["PROPERTIES"]["MODE_ARCHIVE"]["VALUE"]=="Y")
            {
                $modeArchive = "Y";
                $desc_quantity = array(
                    "QUANTITY_STYLE"=> "",
                    "TEXT"=>$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_MODE_ARCHIVE"]

                );
                $preBtn = false;
            }

            else if($preBtn)
                $desc_quantity = array(
                    "QUANTITY_STYLE"=> "",
                    "TEXT"=>$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_PREORDER_ONLY"]

                );

            if($preBtn)
                $avail = "PreOrder";
            


            $quantity = "";
            if(isset($desc_quantity))
            {
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
                "CURRENCY" => $arResult["ORIGINAL_PARAMETERS"]["CURRENCY_ID"],
                "MIN_QUANTITY" => "1",
                "DISCOUNT_DIFF" => 0
            );

            if(isset($arOffer["ITEM_PRICES"][$arOffer['ITEM_PRICE_SELECTED']]))
                $arPrice = $arOffer["ITEM_PRICES"][$arOffer['ITEM_PRICE_SELECTED']];
            

            
            $arOneRow = array(
            	'STORE'=> $arStoreProductResult,
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
                'MODE_DISALLOW_ORDER'=>($arResult["PROPERTIES"]["MODE_DISALLOW_ORDER"]["VALUE"]=="Y")?$arResult["PROPERTIES"]["MODE_DISALLOW_ORDER"]["VALUE"]:$arOffer["PROPERTIES"]["MODE_DISALLOW_ORDER"]["VALUE"],
                'MODE_ARCHIVE'=>$modeArchive,
                'QUANTITY_HTML'=>$htmlQuantity,
                'SHOWPREORDERBTN'=>$preBtn,
            );

            unset($arPrice);


            if($arOneRow["PRICE"]["DISCOUNT_DIFF"]){
                $percent=round(($arOneRow["PRICE"]["DISCOUNT_DIFF"]/$arOneRow["PRICE"]["VALUE"])*100, 2);
                $arOneRow["PRICE"]["DISCOUNT_DIFF_PERCENT_RAW"]="-".$percent."%";
            }
            $arSKU["SKU_MATRIX"][$keyOffer] = $arOneRow;

        }


        if (-1 == $intSelected)
            $intSelected = 0;


        $arResult['JS_OFFERS'] = $arSKU["SKU_MATRIX"];
        $arResult['OFFERS_SELECTED'] = $intSelected;
        $arResult['OFFERS_PROPS_DISPLAY'] = $boolSKUDisplayProperties;

    }
    

}

else
{
    if($arParams['USE_PRICE_COUNT'] == 'Y')
        $arResult = array_merge($arResult, CPhoenix::formatPriceMatrix($arResult));

    
    
}

if($haveOffers)
    $GLOBALS["OG_IMAGE_DEF"]=$arResult['JS_OFFERS'][$arResult['OFFER_ID_SELECTED']]["MORE_PHOTOS"][0]["MIDDLE"]["SRC"];

else
    $GLOBALS["OG_IMAGE_DEF"]=$arResult["MORE_PHOTOS"][0]["MIDDLE"]["SRC"];



if ($arResult['MODULES']['catalog'] && $arResult['CATALOG'])
{
    if ($arResult['CATALOG_TYPE'] == CCatalogProduct::TYPE_PRODUCT || $arResult['CATALOG_TYPE'] == CCatalogProduct::TYPE_SET)
    {
        CIBlockPriceTools::setRatioMinPrice($arResult, false);
        $arResult['MIN_BASIS_PRICE'] = $arResult['MIN_PRICE'];
    }
    if (
        CBXFeatures::IsFeatureEnabled('CatCompleteSet')
        && (
            $arResult['CATALOG_TYPE'] == CCatalogProduct::TYPE_PRODUCT
            || $arResult['CATALOG_TYPE'] == CCatalogProduct::TYPE_SET
        )
    )
    {
        $rsSets = CCatalogProductSet::getList(
            array(),
            array(
                '@OWNER_ID' => $arResult['ID'],
                '=SET_ID' => 0,
                '=TYPE' => CCatalogProductSet::TYPE_GROUP
            ),
            false,
            false,
            array('ID', 'OWNER_ID')
        );
        if ($arSet = $rsSets->Fetch())
        {
            $arResult['OFFER_GROUP'] = true;
        }
    }
}

$arResult['ZOOM_ON'] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['ZOOM_ON']['VALUE']['ACTIVE'] == "Y" ? 'zoom' : '';


$arSelect = Array("ID", "UF_*");
$arFilter = Array("IBLOCK_ID"=>$arParams["IBLOCK_ID"], "ACTIVE"=>"Y", "GLOBAL_ACTIVE"=>"Y", "ID"=>$arResult["IBLOCK_SECTION_ID"]);
$db_list = CIBlockSection::GetList(Array("SORT"=>"ASC"), $arFilter, false, $arSelect);

$ar_result = $db_list->GetNext();


if(!$arResult['ZOOM_ON'])
    $arResult['ZOOM_ON'] = $ar_res["UF_ZOOM_ON"] ? 'zoom' : '';

if($ar_result["UF_CHARS_VIEW"] > 0)
{
    $arResult["UF_CHARS_VIEW_ENUM"] = CUserFieldEnum::GetList(array(), array(
    "ID" => $ar_result["UF_CHARS_VIEW"],
    ))->GetNext();
}
else
    $arResult["UF_CHARS_VIEW_ENUM"]["XML_ID"] = "col-one";


if($haveOffers)
    $arResult["FIRST_ITEM"] = $arResult['JS_OFFERS'][$arResult["OFFERS_SELECTED"]];
else
{
    $measureHTML = '';

    if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["MEASURE"]["VALUE"][$arResult['ITEM_MEASURE']['ID']] == "Y")
        $measureHTML = "&nbsp;/&nbsp;".$arResult['ITEM_MEASURE']['TITLE'];



    $preBtn = false;

    if($arResult["PROPERTIES"]["PREORDER_ONLY"]["VALUE"]=="Y" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["MODE_PREORDER_FORM"]["VALUE"] != "N")
        $preBtn = true;
    
    else
        $preBtn = $arResult["GLOBAL_SHOWPREORDERBTN"] && !$arResult["CAN_BUY"];


    $arResult["ITEMPROP_AVAILABLE"] = "";


    $arStoreProductResult = array();

    if($arParams["SHOW_STORE_BLOCK"])
    {
        $rsStoreProduct = \Bitrix\Catalog\StoreProductTable::getList(array(
            'filter' => array('=PRODUCT_ID'=>$arResult["ID"],'STORE.ACTIVE'=>'Y'),
        ));

        while($arStoreProduct=$rsStoreProduct->fetch())
        {
            $arStoreProduct["MEASURE"] = (isset($measureHTML{0})? $arResult['ITEM_MEASURE']['TITLE']: "");
            $arStoreProductResult[$arStoreProduct["STORE_ID"]] = $arStoreProduct;
        }
    }

    $htmlQuantity = "";

    if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_ON"]["VALUE"]["ACTIVE"] == "Y")
    {

        $desc_quantity = CPhoenixSku::getDescQuantity($arResult["PRODUCT"]["QUANTITY"], $arResult['ITEM_MEASURE']['TITLE'], !empty($arStoreProductResult));

        $quantity=floatval($arResult["PRODUCT"]["QUANTITY"]);
        if($quantity>0)
            $arResult["ITEMPROP_AVAILABLE"] = "InStock";

        else
            $arResult["ITEMPROP_AVAILABLE"] = "OutOfStock";
    }
        
     
    if($arResult["PROPERTIES"]["MODE_ARCHIVE"]["VALUE"]=="Y")
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
        $arResult["ITEMPROP_AVAILABLE"] = "PreOrder";
        


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
        "CURRENCY" => $arResult["ORIGINAL_PARAMETERS"]["CURRENCY_ID"],
        "MIN_QUANTITY" => "1"
    );



    if(isset($arResult["ITEM_PRICES"][$arResult['ITEM_PRICE_SELECTED']]))
        $arPrice = $arResult["ITEM_PRICES"][$arResult['ITEM_PRICE_SELECTED']];

    $arResult["FIRST_ITEM"] = array(
    	'STORE'=> $arStoreProductResult,
        'ID' => $arResult['ID'],
        'NAME' => $arResult['~NAME'],
        'ARTICLE' => $arResult['~ARTICLE'],
        'PRICE' => $arPrice,
        'CAN_BUY' => $arResult['CAN_BUY'],
        'ITEMPROP_AVAILABLE'=>$arResult["ITEMPROP_AVAILABLE"],
        'PRICES' => $arResult['PRICES'],
        'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
        'SHOW_DISCOUNT_PERCENT_NUMBER' => $arParams['SHOW_DISCOUNT_PERCENT_NUMBER'],
        'MORE_PHOTOS' => $arResult['MORE_PHOTOS'],
        'MORE_PHOTOS_COUNT' => $arResult['MORE_PHOTOS_COUNT'],
        'CHECK_QUANTITY' => $arResult['CHECK_QUANTITY'],
        'MAX_QUANTITY' => $arResult["PRODUCT"]["QUANTITY"],
        'STEP_QUANTITY' => $arResult['CATALOG_MEASURE_RATIO'],
        'QUANTITY_FLOAT' => is_double($arResult['CATALOG_MEASURE_RATIO']),
        'MEASURE_PRICE' => $measureHTML,
        'MEASURE' => $arResult['ITEM_MEASURE']['TITLE'],
        'URL' => $arResult['DETAIL_PAGE_URL'],
        'SHOW_MEASURE' => ($arParams["SHOW_MEASURE"]=="Y" ? "Y" : "N"),
        'SHOW_ONE_CLICK_BUY' => "N",
        'OFFER_PROPS' => $arResultProps,
        'PRODUCT_QUANTITY_VARIABLE' => $arParams["PRODUCT_QUANTITY_VARIABLE"],
        'SUBSCRIPTION' => true,
        'ITEM_PRICE_MODE' => $arResult['ITEM_PRICE_MODE'],
        'ITEM_PRICES' => $arResult['ITEM_PRICES'],
        'ITEM_PRICE_SELECTED' => $arResult['ITEM_PRICE_SELECTED'],
        'ITEM_QUANTITY_RANGES' => $arResult['ITEM_QUANTITY_RANGES'],
        'ITEM_QUANTITY_RANGE_SELECTED' => $arResult['ITEM_QUANTITY_RANGE_SELECTED'],
        'ITEM_MEASURE_RATIOS' => $arResult['ITEM_MEASURE_RATIOS'],
        'ITEM_MEASURE_RATIO_SELECTED' => $arResult['ITEM_MEASURE_RATIO_SELECTED'],
        'SHORT_DESCRIPTION' => $arResult['SHORT_DESCRIPTION'],
        'PREVIEW_TEXT' => $arResult['~PREVIEW_TEXT'],
        'CATALOG_SUBSCRIBE'=>$arResult["CATALOG_SUBSCRIBE"],
        'PREORDER_ONLY'=>$arResult["PROPERTIES"]["PREORDER_ONLY"]["VALUE"],
        'MODE_DISALLOW_ORDER'=>$arResult["PROPERTIES"]["MODE_DISALLOW_ORDER"]["VALUE"],
        'MODE_ARCHIVE'=>$arResult["PROPERTIES"]["MODE_ARCHIVE"]["VALUE"],
        'MODE_HIDE'=>$arResult["PROPERTIES"]["MODE_HIDE"]["VALUE"],
        'QUANTITY_HTML'=> $htmlQuantity,
        'SHOWPREORDERBTN'=> $preBtn
    );

    unset($arPrice);


}




unset($ar_result);

$arResult["SECTIONS_ID"] = array();
$similarCategory = array();
$advantages = array();


if(!empty($arResult["PROPERTIES"]["SIMILAR_CATEGORY"]["VALUE"]) && $arResult["PROPERTIES"]["SIMILAR_CATEGORY_INHERIT"]["VALUE_XML_ID"] == "own")
    $similarCategory = $arResult["PROPERTIES"]["SIMILAR_CATEGORY"]["VALUE"];



if(!empty($arResult["PROPERTIES"]["ADVANTAGES"]["VALUE"]) && $arResult["PROPERTIES"]["PARENT_ADV"]["VALUE_XML_ID"] == "own")
    $advantages = $arResult["PROPERTIES"]["ADVANTAGES"]["VALUE"];



$parent_section_id = $arResult["IBLOCK_SECTION_ID"];

if(in_array($parent_section_id, $arParams["NEW_GROUPS"]))
{
    while($parent_section_id != 0)
    {
        $arSelect = Array("ID", "DETAIL_PICTURE", "IBLOCK_SECTION_ID", "UF_*");
        $arFilter = Array("IBLOCK_ID"=>$arParams["IBLOCK_ID"], "ID"=>$parent_section_id, "ACTIVE"=>"Y", "GLOBAL_ACTIVE"=>"Y");
        $db_list = CIBlockSection::GetList(Array("SORT"=>"ASC"), $arFilter, false, $arSelect);
        
        while($ar_res = $db_list->GetNext())
        {
          
            if($arResult["PROPERTIES"]["SIMILAR_CATEGORY_INHERIT"]["VALUE_XML_ID"] == "parent")
            {
                if(empty($similarCategory) && !empty($ar_res["UF_SIMILAR_CATEGORY"]))
                    $similarCategory = $ar_res["UF_SIMILAR_CATEGORY"];
            }

            if($arResult["PROPERTIES"]["PARENT_ADV"]["VALUE_XML_ID"] == "parent")
            {
                if(empty($advantages) && !empty($ar_res["UF_PHX_CTLG_ADV"]))
                    $advantages = $ar_res["UF_PHX_CTLG_ADV"];
            }



            if($ar_res["UF_DESC_FOR_PRICE"] && !isset($arResult["UF_DESC_FOR_PRICE"])){
                $arResult["UF_DESC_FOR_PRICE"] = $ar_res["~UF_DESC_FOR_PRICE"];
            }

            if($ar_res["UF_TIT_FOR_QUANTITY"] && !isset($arResult["UF_TIT_FOR_QUANTITY"])){
                $arResult["UF_TIT_FOR_QUANTITY"] = $ar_res["~UF_TIT_FOR_QUANTITY"];
            }

            if($ar_res["UF_COMM_FOR_QUANTITY"] && !isset($arResult["UF_TIT_FOR_QUANTITY"])){
                $arResult["UF_COMM_FOR_QUANTITY"] = $ar_res["~UF_COMM_FOR_QUANTITY"];
            }

            if(strlen($ar_res["UF_PREVIEW_TEXT_POS"]) && !isset($arResult["UF_PREVIEW_TEXT_POS"])){

                $arResult["UF_PREVIEW_TEXT_POS"] = CUserFieldEnum::GetList(array(), array(
                    "ID" => $ar_res["UF_PREVIEW_TEXT_POS"],
                ))->GetNext();
            }

            $arResult["SECTIONS_ID"][] = $ar_res["ID"];
            $parent_section_id = $ar_res["IBLOCK_SECTION_ID"];

        }
        
    } 
}



if( strlen($arResult["PROPERTIES"]["BRAND"]["VALUE"]) )
{
    $arElements = Array();

    $arSelect = Array("ID", "NAME", "PREVIEW_PICTURE", "PREVIEW_TEXT", "DETAIL_PAGE_URL");
    $arFilter = Array("ID"=>$arResult["PROPERTIES"]["BRAND"]["VALUE"], "ACTIVE"=>"Y");

    $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, false, $arSelect);
    while($ob = $res->GetNextElement())
    { 
        $arResult["BRAND"] = $ob->GetFields();  
    }

    if( strlen($arResult["BRAND"]["PREVIEW_PICTURE"]) && isset($arResult["BRAND"]["PREVIEW_PICTURE"]) && $arResult["BRAND"]["PREVIEW_PICTURE"] > 0 )
    {
        $arPicture = CFile::ResizeImageGet($arResult["BRAND"]["PREVIEW_PICTURE"], array('width'=>143, 'height'=>32), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

        $arResult["BRAND"]["PREVIEW_PICTURE_SRC"] = $arPicture["src"];

        unset($arPicture);

        $arPicture = CFile::ResizeImageGet($arResult["BRAND"]["PREVIEW_PICTURE"], array('width'=>286, 'height'=>64), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

        $arResult["BRAND"]["PREVIEW_PICTURE_SRC_XS"] = $arPicture["src"];

        unset($arPicture);
    }
    
}


if(!empty($arResult["PROPERTIES"]["MODAL_WINDOWS"]["VALUE"]))
{
    $arElements = Array();

    $arSelect = Array("ID", "NAME");
    $arFilter = Array("ID"=>$arResult["PROPERTIES"]["MODAL_WINDOWS"]["VALUE"], "ACTIVE"=>"Y");

    $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, false, $arSelect);
    while($ob = $res->GetNextElement())
    { 
        $arElem = $ob->GetFields();  
        $arElem["PROPERTIES"] = $ob->GetProperties();
        
        $arElements[] = $arElem;
    }

    $arResult["MODAL_WINDOWS"] = $arElements;
}


if(!empty($arResult["PROPERTIES"]["FORMS"]["VALUE"]))
{
    $arElements = Array();

    $arSelect = Array("ID", "NAME");
    $arFilter = Array("ID"=>$arResult["PROPERTIES"]["FORMS"]["VALUE"], "ACTIVE"=>"Y");

    $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, false, $arSelect);
    while($ob = $res->GetNextElement())
    { 
        $arElem = $ob->GetFields();  
        $arElem["PROPERTIES"] = $ob->GetProperties();
        
        $arElements[] = $arElem;
    }

    $arResult["FORMS"] = $arElements;
}


if(IsModuleInstalled("concept.quiz"))
{

    if(!empty($arResult["PROPERTIES"]["QUIZS"]["VALUE"]))
    {
        $arElements = Array();
        $arSelect = Array("ID", "NAME");
        $arFilter = Array("ACTIVE"=>"Y", "ID"=>$arResult["PROPERTIES"]["QUIZS"]["VALUE"]);
        $db_list = CIBlockSection::GetList(Array("SORT"=>"ASC"), $arFilter, false, $arSelect);

        while ($arElements = $db_list->GetNext())
        {
            $arResult["QUIZS"][] = $arElements;
        }
    }
}


if(!empty($advantages))
{
   
    $arElements = Array();

    $arSizes = Array(
        "small" => array(
                "width" => 80,
                "height" => 80
            ),
        "big" => array(
                "width" => 200,
                "height" => 200
            ),
    );

    $arSelect = Array("ID", "IBLOCK_ID", "IBLOCK_SECTION_ID", "IBLOCK_TYPE_ID", "NAME", "PREVIEW_PICTURE", "PROPERTY_*");
    $arFilter = Array("ID"=>$advantages, "ACTIVE"=>"Y");

    $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, false, $arSelect);
    while($ob = $res->GetNextElement())
    { 
        $arElem = $ob->GetFields();  
        $arElem["PROPERTIES"] = $ob->GetProperties();

        if(!$arElem["PROPERTIES"]["SIZE"]["VALUE_XML_ID"])
            $arElem["PROPERTIES"]["SIZE"]["VALUE_XML_ID"] = "small";

       

        $arElem["PREVIEW_PICTURE_SRC"] = "";
        $file = array();


        if($arElem["PREVIEW_PICTURE"])
        {
            $file = CFile::ResizeImageGet($arElem["PREVIEW_PICTURE"], 
                $arSizes[$arElem["PROPERTIES"]["SIZE"]["VALUE_XML_ID"]],
                BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

            $arElem["PREVIEW_PICTURE_SRC"] = $file["src"];
        }
        
        $arElements[] = $arElem;
    }
    
    $arResult["ADVANTAGES"] = $arElements;

}



//setProducts

if($arResult["PROPERTIES"]["SHOW_BLOCK_SET_PRODUCT"]["VALUE"] == "Y")
{
    if($arResult["CATALOG_TYPE"] == 2)
    {

        $currentSet = false;
        $allSets = CCatalogProductSet::getAllSetsByProduct($arResult['ID'], CCatalogProductSet::TYPE_SET);

        foreach ($allSets as &$oneSet)
        {
            if ($oneSet['ACTIVE'] == 'Y')
            {
                $currentSet = $oneSet;
                break;
            }
        }
        unset($oneSet, $allSets);


        Bitrix\Main\Type\Collection::sortByColumn($currentSet['ITEMS'], array('SORT' => SORT_ASC), '', null, true);



        $arSetItemsID = array();
        $productQuantity = array(
            $arResult['ID'] => 1
        );
        foreach ($currentSet['ITEMS'] as $index => $item)
        {
            $id = $item['ITEM_ID'];
            $arSetItemsID[] = $id;
            $productLink[$id] = $index;
            $productQuantity[$id] = $item['QUANTITY'];
            unset($id);
        }
        unset($index, $item);



        $ratioResult = Bitrix\Catalog\ProductTable::getCurrentRatioWithMeasure($arSetItemsID);

        foreach ($ratioResult as $ratioProduct => $ratioData)
        {
            $arResult['SET_ITEMS_RATIO'][$ratioProduct] = $ratioData['RATIO'];
            $ratioResult[$ratioProduct]["QUANTITY"] = $productQuantity[$ratioProduct] *= $ratioData['RATIO'];
        }
        unset($ratioProduct, $ratioData);


        $arrItems = array();


        $select = array(
            'ID',
            'CATALOG_TYPE'
        );
        $filter = array(
            'ID' => $arSetItemsID,
            'IBLOCK_LID' => SITE_ID,
            'ACTIVE_DATE' => 'Y',
            'ACTIVE' => 'Y',
            'CHECK_PERMISSIONS' => 'Y',
            'MIN_PERMISSION' => 'R'
        );

        $itemsIterator = CIBlockElement::GetList(
            array(),
            $filter,
            false,
            false,
            $select
        );
        while ($item = $itemsIterator->GetNext())
        {
            $arrItems[$item['ID']] = $item['ID'];
        }

        $arProduct = array();
        $arOffers = array();
        $arOffer2Product = array();

        if(!empty($arrItems))
        {
            $arFilter = Array("ID" => $arrItems);
            $arSelect = array("IBLOCK_ID", "PROPERTY_CML2_LINK", "ID");
            $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

            $arProduct = array();
            $arOffers = array();
            $arOffer2Product = array();

            while($ob = $res->GetNext())
            {
                
                if(strlen($ob["PROPERTY_CML2_LINK_VALUE"]))
                {
                    $arProduct[] = $ob["PROPERTY_CML2_LINK_VALUE"];
                    $arOffers[] = $ob["ID"];
                    $arOffer2Product[$ob["ID"]] = $ob["PROPERTY_CML2_LINK_VALUE"];
                }
                else
                    $arProduct[] = $ob["ID"];
            }
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

            if(empty($PHOENIX_TEMPLATE_ARRAY["SKU_PROP_LIST"]))
                CPhoenixSku::getHIBlockOptions();

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



        $arResult["SET_ITEMS"] = array();

        foreach ($currentSet['ITEMS'] as $key => $arItem)
        {

            $isOffer = isset($arOffer2Product[$arItem["ITEM_ID"]]);

            $arResult["SET_ITEMS"][$key]["ID"] = $arItem["ITEM_ID"];


            $arResult["SET_ITEMS"][$key]["MEASURE"] = $ratioResult[$arItem["ITEM_ID"]]["QUANTITY"]."&nbsp;".$ratioResult[$arItem["ITEM_ID"]]["MEASURE"]["SYMBOL"];

            if($isOffer)
            {
                $productIdOfOrder = $arOffer2Product[$arItem["ITEM_ID"]];

                
                $arResult["SET_ITEMS"][$key]["NAME"] = strip_tags($arProductFields[$productIdOfOrder]["~NAME"]);


                $arResult["SET_ITEMS"][$key]["IBLOCK_TYPE_ID"] = $arOffersFields[$arItem["ITEM_ID"]]["IBLOCK_TYPE_ID"];
                $arResult["SET_ITEMS"][$key]["IBLOCK_SECTION_ID"] = $arOffersFields[$arItem["ITEM_ID"]]["IBLOCK_SECTION_ID"];
                $arResult["SET_ITEMS"][$key]["IBLOCK_ID"] = $arOffersFields[$arItem["ITEM_ID"]]["IBLOCK_ID"];


                $arResult["SET_ITEMS"][$key]["IS_OFFER"] = "Y";
                $arResult["SET_ITEMS"][$key]["ID_MAIN"] = $productIdOfOrder;
                $arResult["SET_ITEMS"][$key]["IBLOCK_TYPE_ID_MAIN"] = $arProductFields[$productIdOfOrder]["IBLOCK_TYPE_ID"];
                $arResult["SET_ITEMS"][$key]["IBLOCK_SECTION_ID_MAIN"] = $arProductFields[$productIdOfOrder]["IBLOCK_SECTION_ID"];
                $arResult["SET_ITEMS"][$key]["IBLOCK_ID_MAIN"] = $arProductFields[$productIdOfOrder]["IBLOCK_ID"];

                $photo = 0;

                if($arOffersFields[$arItem["ITEM_ID"]]["DETAIL_PICTURE"])
                    $photo = $arOffersFields[$arItem["ITEM_ID"]]["DETAIL_PICTURE"];
                

                else if(!empty($arOffersFields[$arItem["ITEM_ID"]]["PROPERTIES"]["MORE_PHOTO"]["VALUE"]))
                    $photo = $arOffersFields[$arItem["ITEM_ID"]]["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0];

                else if($arOffersFields[$arItem["ITEM_ID"]]["PROPERTIES"]["NO_MERGE_PHOTOS"]["VALUE"] != "Y")
                {

                    if($arProductFields[$arOffersFields[$arItem["ITEM_ID"]]["PROPERTIES"]["CML2_LINK"]["VALUE"]]["DETAIL_PICTURE"])
                        $photo = $arProductFields[$arOffersFields[$arItem["ITEM_ID"]]["PROPERTIES"]["CML2_LINK"]["VALUE"]]["DETAIL_PICTURE"];
                    

                    else if(!empty($arProductFields[$arOffersFields[$arItem["ITEM_ID"]]["PROPERTIES"]["CML2_LINK"]["VALUE"]]["PROPERTIES"]["MORE_PHOTO"]["VALUE"]))
                        $photo = $arProductFields[$arOffersFields[$arItem["ITEM_ID"]]["PROPERTIES"]["CML2_LINK"]["VALUE"]]["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0];
                }



                if($photo)
                {
                    $img = CFile::ResizeImageGet($photo, array('width'=>290, 'height'=>240), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

                    $arResult["SET_ITEMS"][$key]["PREVIEW_PICTURE_SRC"] = $img["src"];

                    
                }
                else
                    $arResult["SET_ITEMS"][$key]["PREVIEW_PICTURE_SRC"] = SITE_TEMPLATE_PATH."/images/ufo.png";


                $arResult["SET_ITEMS"][$key]["DETAIL_PAGE"] = $arOffersFields[$arItem["ITEM_ID"]]["DETAIL_PAGE_URL"]."?oID=".$arItem["ITEM_ID"];
                /*$arResult["SET_ITEMS"][$key]["ARTICLE"] = strip_tags($arOffersFields[$arItem["ITEM_ID"]]["PROPERTIES"]["ARTICLE"]["~VALUE"]);*/

                



                if( !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUE_"]) )
                {

                    foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUE_"] as $arOfferField)
                    {
                        if( $arOffersFields[$arItem["ITEM_ID"]]["PROPERTIES"][$arOfferField]["USER_TYPE"] == "directory" && strlen($arOffersFields[$arItem["ITEM_ID"]]["PROPERTIES"][$arOfferField]["VALUE"]) && !empty($PHOENIX_TEMPLATE_ARRAY["SKU_PROP_LIST"]) )
                        {

                            foreach ($PHOENIX_TEMPLATE_ARRAY["SKU_PROP_LIST"] as $arSKUProp)
                            {

                                if( $arOffersFields[$arItem["ITEM_ID"]]["PROPERTIES"][$arOfferField]['USER_TYPE_SETTINGS']['TABLE_NAME'] == $arSKUProp['TABLE_NAME'])
                                {
                                    if(strlen($arSKUProp["VALUE_NAME"][$arOffersFields[$arItem["ITEM_ID"]]["PROPERTIES"][$arOfferField]["VALUE"]]))
                                    {

                                        $arResult["SET_ITEMS"][$key]["NAME_OFFERS"] .= $arOffersFields[$arItem["ITEM_ID"]]["PROPERTIES"][$arOfferField]["NAME"].":&nbsp;".$arSKUProp["VALUE_NAME"][$arOffersFields[$arItem["ITEM_ID"]]["PROPERTIES"][$arOfferField]["VALUE"]]."<br/>";
                                    }

                                }
                            }
                        }
                        else if(strlen($arOffersFields[$arItem["ITEM_ID"]]["PROPERTIES"][$arOfferField]["VALUE"]))
                        {
                            $arResult["SET_ITEMS"][$key]["NAME_OFFERS"] .= $arOffersFields[$arItem["ITEM_ID"]]["PROPERTIES"][$arOfferField]["NAME"].":&nbsp;".$arOffersFields[$arItem["ITEM_ID"]]["PROPERTIES"][$arOfferField]["VALUE"]."<br/>";
                        }

                    }

                    
                }

            }

            else
            {
                $arResult["SET_ITEMS"][$key]["NAME"] = strip_tags($arProductFields[$arItem["ITEM_ID"]]["~NAME"]);


                $arResult["SET_ITEMS"][$key]["IBLOCK_TYPE_ID"] = $arProductFields[$arItem["ITEM_ID"]]["IBLOCK_TYPE_ID"];
                $arResult["SET_ITEMS"][$key]["IBLOCK_SECTION_ID"] = $arProductFields[$arItem["ITEM_ID"]]["IBLOCK_SECTION_ID"];
                $arResult["SET_ITEMS"][$key]["IBLOCK_ID"] = $arProductFields[$arItem["ITEM_ID"]]["IBLOCK_ID"];

                $photo = 0;

                if($arProductFields[$arItem["ITEM_ID"]]["DETAIL_PICTURE"])
                    $photo = $arProductFields[$arItem["ITEM_ID"]]["DETAIL_PICTURE"];
                

                else if(!empty($arProductFields[$arItem["ITEM_ID"]]["PROPERTIES"]["MORE_PHOTO"]["VALUE"]))
                    $photo = $arProductFields[$arItem["ITEM_ID"]]["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0];


                if($photo)
                {
                    $img = CFile::ResizeImageGet($photo, array('width'=>290, 'height'=>240), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

                    $arResult["SET_ITEMS"][$key]["PREVIEW_PICTURE_SRC"] = $img["src"];
                }

                else
                    $arResult["SET_ITEMS"][$key]["PREVIEW_PICTURE_SRC"] = SITE_TEMPLATE_PATH."/images/ufo.png";

                $arResult["SET_ITEMS"][$key]["DETAIL_PAGE"] = $arProductFields[$arItem["ITEM_ID"]]["DETAIL_PAGE_URL"];
                /*$arResult["SET_ITEMS"][$key]["ARTICLE"] = strip_tags($arProductFields[$arItem["ITEM_ID"]]["PROPERTIES"]["ARTICLE"]["~VALUE"]);*/

            }
        }
    }

    $arResult["SHOW_SET_PRODUCT"] = isset($arResult["SET_ITEMS"]) && !empty($arResult["SET_ITEMS"]) && $arResult["PROPERTIES"]["SHOW_BLOCK_SET_PRODUCT"]["VALUE"] == "Y";

}





// BLOG, NEWS, ACTIONS SECTIONS

$code = array('concept_phoenix_blog_'.SITE_ID, 'concept_phoenix_news_'.SITE_ID, 'concept_phoenix_action_'.SITE_ID);

$SectList = CIBlockSection::GetList(array("SORT"=>"ASC"), array("IBLOCK_CODE"=>$code, "ACTIVE"=>"Y"), false, array("ID","NAME","SECTION_PAGE_URL"));
while ($SectListGet = $SectList->GetNext())
{
    if(strlen($SectListGet["UF_CATEGORY_LIST"]))
    {
        $SectListGet["UF_CATEGORY_LIST_ENUM"] = CUserFieldEnum::GetList(array(), array(
            "ID" => $SectListGet["UF_CATEGORY_LIST"],
        ))->GetNext();
    }
    else
        $SectListGet["UF_CATEGORY_LIST_ENUM"]["XML_ID"] = "icon-default-opinion";
    
    $arResult["BNA"][$SectListGet["ID"]]=$SectListGet;
}

$arNews = array();

if(!empty($arResult["PROPERTIES"]["NEWS"]["VALUE"]))
    $arNews = array_merge($arNews, $arResult["PROPERTIES"]["NEWS"]["VALUE"]);

if(!empty($arResult["PROPERTIES"]["BLOGS"]["VALUE"]))
    $arNews = array_merge($arNews, $arResult["PROPERTIES"]["BLOGS"]["VALUE"]);

if(!empty($arResult["PROPERTIES"]["SPECIALS"]["VALUE"]))
    $arNews = array_merge($arNews, $arResult["PROPERTIES"]["SPECIALS"]["VALUE"]);

$arResult["PROPERTIES"]["NEWS_LIST"]["VALUE"] = $arNews;

//faq
if(!empty($arResult["PROPERTIES"]["FAQ_ELEMENTS"]["VALUE"]))
{
    $code_faq = "concept_phoenix_faq_".SITE_ID;
    $arFilter = Array("IBLOCK_CODE"=>$code_faq, "SECTION_ID" => $arResult["PROPERTIES"]["FAQ_ELEMENTS"]["VALUE"], "ACTIVE"=>"Y", "INCLUDE_SUBSECTIONS" => "N");
    $res = CIBlockElement::GetList(Array("sort" => "asc"), $arFilter);

    while($ob = $res->GetNextElement())
    {

        $arElement = Array();
        
        $arElement = $ob->GetFields();  
        $arElement["PROPERTIES"] = $ob->GetProperties();

        $arResult["PROPERTIES"]["FAQ"]["VALUE"][] = $arElement;
       
    } 
}

//similar
if($arResult["PROPERTIES"]["SHOWBLOCK5"]["VALUE"] == "Y")
{

 
    $arResult["SIMILAR"] = array();

    if($arResult["PROPERTIES"]["SIMILAR_SOURCE"]["VALUE_XML_ID"] == "")
        $arResult["PROPERTIES"]["SIMILAR_SOURCE"]["VALUE_XML_ID"] = "random_category_inherit";

    if(trim($arResult["PROPERTIES"]["SIMILAR_MAX_QUANTITY"]["VALUE"]) == "" || intval($arResult["PROPERTIES"]["SIMILAR_MAX_QUANTITY"]["VALUE"]) == 0)
        $arResult["PROPERTIES"]["SIMILAR_MAX_QUANTITY"]["VALUE"] = "4";


    if($arResult["PROPERTIES"]["SIMILAR_SOURCE"]["VALUE_XML_ID"] == "random_category_inherit" && $arResult['IBLOCK_SECTION_ID']>0)
    {
        $arSelect = array("ID");
        $arFilter = Array("IBLOCK_ID"=>$arResult["IBLOCK_ID"], "INCLUDE_SUBSECTIONS"=>"Y", "SECTION_GLOBAL_ACTIVE"=>"Y", "SECTION_ID" => $arResult['IBLOCK_SECTION_ID'], "!ID" => $arResult['ID']);
        $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, Array("nPageSize"=>$arResult["PROPERTIES"]["SIMILAR_MAX_QUANTITY"]["VALUE"]), $arSelect);

        while($ob = $res->GetNextElement())
        {
            $aritem = array();
            $aritem = $ob->GetFields();
            $arResult["SIMILAR"][] = $aritem["ID"];
        } 

    }

    if($arResult["PROPERTIES"]["SIMILAR_SOURCE"]["VALUE_XML_ID"] == "random_category_list" && !empty($arResult["PROPERTIES"]["SIMILAR_CATEGORY_LIST"]["VALUE"]) )
    {
        $arSelect = array("ID");
        $arFilter = Array("IBLOCK_ID"=>$arResult["IBLOCK_ID"], "INCLUDE_SUBSECTIONS"=>"Y", "SECTION_GLOBAL_ACTIVE"=>"Y", "SECTION_ID" => $arResult["PROPERTIES"]["SIMILAR_CATEGORY_LIST"]["VALUE"], "!ID" => $arResult['ID']);
        $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, Array("nPageSize"=>$arResult["PROPERTIES"]["SIMILAR_MAX_QUANTITY"]["VALUE"]), $arSelect);

        while($ob = $res->GetNextElement())
        {
            $aritem = array();
            $aritem = $ob->GetFields();
            $arResult["SIMILAR"][] = $aritem["ID"];
        }
    }

    if($arResult["PROPERTIES"]["SIMILAR_SOURCE"]["VALUE_XML_ID"] == "elements")
        $arResult["SIMILAR"] = $arResult["PROPERTIES"]["SIMILAR"]["VALUE"];
}

//accessory
if($arResult["PROPERTIES"]["SHOWBLOCK13"]["VALUE"] == "Y")
{
    $arResult["ACCESSORY"] = array();

    if($arResult["PROPERTIES"]["ACCESSORY_SOURCE"]["VALUE_XML_ID"] == "")
        $arResult["PROPERTIES"]["ACCESSORY_SOURCE"]["VALUE_XML_ID"] = "random_category_inherit";

    if($arResult["PROPERTIES"]["ACCESSORY_MAX_QUANTITY"]["VALUE"] == "")
        $arResult["PROPERTIES"]["ACCESSORY_MAX_QUANTITY"]["VALUE"] = "4";


    if($arResult["PROPERTIES"]["ACCESSORY_SOURCE"]["VALUE_XML_ID"] == "random_category_inherit" && $arResult['IBLOCK_SECTION_ID']>0)
    {
        $arSelect = array("ID");
        $arFilter = Array("IBLOCK_ID"=>$arResult["IBLOCK_ID"], "INCLUDE_SUBSECTIONS"=>"Y", "SECTION_GLOBAL_ACTIVE"=>"Y", "SECTION_ID" => $arResult['IBLOCK_SECTION_ID'], "!ID" => $arResult['ID']);
        $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, Array("nPageSize"=>$arResult["PROPERTIES"]["ACCESSORY_MAX_QUANTITY"]["VALUE"]), $arSelect);

        while($ob = $res->GetNextElement())
        {
            $aritem = array();
            $aritem = $ob->GetFields();
            $arResult["ACCESSORY"][] = $aritem["ID"];
        } 

    }


    if($arResult["PROPERTIES"]["ACCESSORY_SOURCE"]["VALUE_XML_ID"] == "random_category_list" && !empty($arResult["PROPERTIES"]["ACCESSORY_CATEGORY_LIST"]["VALUE"]) )
    {
        $arSelect = array("ID");
        $arFilter = Array("IBLOCK_ID"=>$arResult["IBLOCK_ID"], "INCLUDE_SUBSECTIONS"=>"Y", "SECTION_GLOBAL_ACTIVE"=>"Y", "SECTION_ID" => $arResult["PROPERTIES"]["ACCESSORY_CATEGORY_LIST"]["VALUE"], "!ID" => $arResult['ID']);
        $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, Array("nPageSize"=>$arResult["PROPERTIES"]["ACCESSORY_MAX_QUANTITY"]["VALUE"]), $arSelect);

        while($ob = $res->GetNextElement())
        {
            $aritem = array();
            $aritem = $ob->GetFields();
            $arResult["ACCESSORY"][] = $aritem["ID"];
        }
    }

    if($arResult["PROPERTIES"]["ACCESSORY_SOURCE"]["VALUE_XML_ID"] == "elements")
        $arResult["ACCESSORY"] = $arResult["PROPERTIES"]["ACCESSORY"]["VALUE"];
}

//similar category

if(!empty($similarCategory))
{

    $arSelect = Array("ID", "SECTION_PAGE_URL", "NAME", "UF_PHX_MENU_PICT");
    $arFilter = Array("IBLOCK_ID"=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"], "ID"=>$similarCategory, "ACTIVE"=>"Y", "GLOBAL_ACTIVE"=>"Y");

    $db_list = CIBlockSection::GetList(Array("SORT"=>"ASC"), $arFilter, false, $arSelect);
    
    $arResult["SIMILAR_CATEGORY"] = array();
    while($ar_res = $db_list->GetNext())
    {
        $img = CFile::ResizeImageGet($ar_res["UF_PHX_MENU_PICT"], array('width'=>140, 'height'=>140), BX_RESIZE_IMAGE_PROPORTIONAL, false);

        $ar_res["PICTURE_SRC"] = $img["src"];
        $arResult["SIMILAR_CATEGORY"][] = $ar_res;
    }

    $arResult["SIMILAR_CATEGORY_CNT"] = count($arResult["SIMILAR_CATEGORY"]);

}


unset($similarCategory);

$arMenu = Array();
$arTmpMenu = Array();
$arTmpMenuSort = Array();

$arSort = Array();

$arBlocks = false;

//main
if($arResult["PROPERTIES"]["SHOWMENU_BLOCK1"]["VALUE"] == "Y")
{
    $arMenu["main"]["NAME"] = $arResult["PROPERTIES"]["MENUTITLE_BLOCK1"]["~VALUE"];
}


//advantages
if($arResult["PROPERTIES"]["SHOWMENU_BLOCK2"]["VALUE"] == "Y" && isset($arResult["ADVANTAGES"]) && !empty($arResult["ADVANTAGES"]))
{
    $arTmpMenu["advantages"]["NAME"] = $arResult["PROPERTIES"]["MENUTITLE_BLOCK2"]["~VALUE"];
    $arTmpMenuSort["advantages"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK2"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK2"]["VALUE"] : 10);
    
    $arBlocks = true;
}

$arSort["advantages"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK2"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK2"]["VALUE"] : 10);


//set_product
if($arResult["PROPERTIES"]["SHOW_BLOCK_SET_PRODUCT"]["VALUE"] == "Y" && $arResult["PROPERTIES"]["SHOWMENU_BLOCK_SET_PRODUCT"]["VALUE"] == "Y" && isset($arResult["SET_ITEMS"]) && !empty($arResult["SET_ITEMS"]))
{
    $arTmpMenu["set_product"]["NAME"] = $arResult["PROPERTIES"]["MENUTITLE_BLOCK_SET_PRODUCT"]["~VALUE"];
    $arTmpMenuSort["set_product"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK_SET_PRODUCT"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK_SET_PRODUCT"]["VALUE"] : 5);
    
    $arBlocks = true;
}


$arSort["set_product"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK_SET_PRODUCT"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK_SET_PRODUCT"]["VALUE"] : 5);



//set_product constructor
if($arResult["PROPERTIES"]["SHOW_BLOCK_SET_PRODUCT_CONSTRUCTOR"]["VALUE"] == "Y" && $arResult["PROPERTIES"]["SHOWMENU_BLOCK_SET_PRODUCT_CONSTRUCTOR"]["VALUE"] == "Y")
{
    $arTmpMenu["set_product_constructor"]["NAME"] = $arResult["PROPERTIES"]["MENUTITLE_BLOCK_SET_PRODUCT_CONSTRUCTOR"]["~VALUE"];
    $arTmpMenuSort["set_product_constructor"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK_SET_PRODUCT_CONSTRUCTOR"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK_SET_PRODUCT_CONSTRUCTOR"]["VALUE"] : 6);
    
    $arBlocks = true;
}


$arSort["set_product_constructor"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK_SET_PRODUCT_CONSTRUCTOR"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK_SET_PRODUCT_CONSTRUCTOR"]["VALUE"] : 6);





if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_REVIEW"]["VALUE"]["ACTIVE"] == "Y")
{
    CPhoenix::setMess(array("review", "rating"));
    
    if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["RATING_SIDEMENU_SHOW"]["VALUE"]["ACTIVE"] == "Y")
    {
        $arSort["rating-block"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK14"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK14"]["VALUE"] : 140);

        $arTmpMenu["rating-block"]["NAME"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["RATING_SIDEMENU_NAME"]["VALUE"];
        $arTmpMenuSort["rating-block"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK14"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK14"]["VALUE"] : 140);
        
        $arBlocks = true;
    }


}

$arResult["rating"] = array();


if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_VOTE"]["VALUE"]["ACTIVE"] == "Y")
        
{
    if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_REVIEW"]["VALUE"]["ACTIVE"] == "Y")
    {
        $arResult["RATING_VIEW"] = "full";
    }
    else
    {
        $arResult["RATING_VIEW"] = "simple";
        $arResult["rating"][$arResult["ID"]]["VALUE"] = (strlen($arResult["PROPERTIES"]["rating"]["VALUE"]))?round($arResult["PROPERTIES"]["rating"]["VALUE"]):"0";
        $arResult["rating"][$arResult["ID"]]["COUNT"] = (strlen($arResult["PROPERTIES"]["vote_count"]["VALUE"]))?round($arResult["PROPERTIES"]["vote_count"]["VALUE"]):"0";
    }
}



$arSort["rating"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK14"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK14"]["VALUE"] : 140);



//chars
if($arResult["PROPERTIES"]["SHOWMENU_BLOCK3"]["VALUE"] == "Y" && (!empty($arResult["PROPERTIES"]["CHARS"]["VALUE"]) || !empty($arResult["PROPERTIES"]["FILES"]["VALUE"])))
{
    $arTmpMenu["chars"]["NAME"] = $arResult["PROPERTIES"]["MENUTITLE_BLOCK3"]["~VALUE"];
    $arTmpMenuSort["chars"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK3"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK3"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK3"]["DEFAULT_VALUE"]);
    
    $arBlocks = true;
}

$arSort["chars"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK3"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK3"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK3"]["DEFAULT_VALUE"]);


// block_chars

$arResult["PROPS_CHARS"] = array();
$arResult["PROP_CHARS"] = array();

$arSortChars = array();

if(!empty($arResult["PROPERTIES"]["CHARS"]["VALUE"]))
{
    $arResult["PROPS_CHARS"] = CPhoenix::getCharacteristicsCatalogItem($arResult["PROPERTIES"]["CHARS"]);

    $arSortChars["props_chars"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_SORT_PROPS_CHARS']["VALUE"];
}

if(!empty($PHOENIX_TEMPLATE_ARRAY["SHOW_CHARS_ITEMS_IN_DETAIL"]))
{
    $arResult["PROP_CHARS"] = CPhoenix::getPropsCharsCatalogItem($arResult["PROPERTIES"], $PHOENIX_TEMPLATE_ARRAY["SHOW_CHARS_ITEMS_IN_DETAIL"]);

    $arSortChars["prop_chars"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_SORT_PROP_CHARS']["VALUE"];
}

$arSortChars["sku_chars"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_SORT_SKU_CHARS']["VALUE"];


$arResult["CHARS"] = array(
    "ITEMS"=>array(),
    "COUNT"=>0
);

$arResult["CHARS"]["ITEMS"] = array_merge($arResult["PROPS_CHARS"], $arResult["PROP_CHARS"]);
$arResult["CHARS"]["COUNT"] = (!empty($arResult["CHARS"]["ITEMS"]))?count($arResult["CHARS"]["ITEMS"]):0;




//video
if($arResult["PROPERTIES"]["SHOWMENU_BLOCK4"]["VALUE"] == "Y" && !empty($arResult["PROPERTIES"]["VIDEO"]["VALUE"]))
{
    $arTmpMenu["video"]["NAME"] = $arResult["PROPERTIES"]["MENUTITLE_BLOCK4"]["~VALUE"];
    $arTmpMenuSort["video"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK4"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK4"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK"]["DEFAULT_VALUE"]);
    
    $arBlocks = true;
}

$arSort["video"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK4"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK4"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK4"]["DEFAULT_VALUE"]);


//similar
if( $arResult["PROPERTIES"]["SHOWMENU_BLOCK5"]["VALUE"] == "Y" && isset($arResult["SIMILAR"]) && !empty($arResult["SIMILAR"]) )
{
    $arTmpMenu["similar"]["NAME"] = $arResult["PROPERTIES"]["MENUTITLE_BLOCK5"]["~VALUE"];
    $arTmpMenuSort["similar"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK5"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK5"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK5"]["DEFAULT_VALUE"]);
    
    $arBlocks = true;
}

$arSort["similar"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK5"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK5"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK5"]["DEFAULT_VALUE"]);


//accessory
if( $arResult["PROPERTIES"]["SHOWMENU_BLOCK13"]["VALUE"] == "Y" && isset($arResult["ACCESSORY"]) && !empty($arResult["ACCESSORY"]) )
{
    $arTmpMenu["accessory"]["NAME"] = $arResult["PROPERTIES"]["MENUTITLE_BLOCK13"]["~VALUE"];
    $arTmpMenuSort["accessory"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK13"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK13"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK13"]["DEFAULT_VALUE"]);
    
    $arBlocks = true;
}

$arSort["accessory"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK13"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK13"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK13"]["DEFAULT_VALUE"]);


//similar category
if($arResult["PROPERTIES"]["SHOWMENU_BLOCK11"]["VALUE"] == "Y" && !empty($arResult["SIMILAR_CATEGORY"]))
{
    $arTmpMenu["similar_category"]["NAME"] = $arResult["PROPERTIES"]["MENUTITLE_BLOCK11"]["~VALUE"];
    $arTmpMenuSort["similar_category"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK11"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK11"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK11"]["DEFAULT_VALUE"]);
    
    $arBlocks = true;
}

$arSort["similar_category"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK11"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK11"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK11"]["DEFAULT_VALUE"]);



//stuff
if($arResult["PROPERTIES"]["SHOWMENU_BLOCK6"]["VALUE"] == "Y" && !empty($arResult["PROPERTIES"]["STUFF"]["VALUE"]))
{
    $arTmpMenu["stuff"]["NAME"] = $arResult["PROPERTIES"]["MENUTITLE_BLOCK6"]["~VALUE"];
    $arTmpMenuSort["stuff"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK6"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK6"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK6"]["DEFAULT_VALUE"]);
    
    $arBlocks = true;
}

$arSort["stuff"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK6"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK6"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK6"]["DEFAULT_VALUE"]);


//news_list
if($arResult["PROPERTIES"]["SHOWMENU_BLOCK7"]["VALUE"] == "Y" && !empty($arResult["PROPERTIES"]["NEWS_LIST"]["VALUE"]))
{
    $arTmpMenu["news_list"]["NAME"] = $arResult["PROPERTIES"]["MENUTITLE_BLOCK7"]["~VALUE"];
    $arTmpMenuSort["news_list"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK7"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK7"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK7"]["DEFAULT_VALUE"]);
    
    $arBlocks = true;
}

$arSort["news_list"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK7"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK7"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK7"]["DEFAULT_VALUE"]);




//faq
if($arResult["PROPERTIES"]["SHOWMENU_BLOCK8"]["VALUE"] == "Y" && !empty($arResult["PROPERTIES"]["FAQ"]["VALUE"]))
{
    $arTmpMenu["faq"]["NAME"] = $arResult["PROPERTIES"]["MENUTITLE_BLOCK8"]["~VALUE"];
    $arTmpMenuSort["faq"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK8"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK8"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK8"]["DEFAULT_VALUE"]);
    
    $arBlocks = true;
}

$arSort["faq"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK8"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK8"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK8"]["DEFAULT_VALUE"]);



//text
if($arResult["PROPERTIES"]["SHOWMENU_BLOCK9"]["VALUE"] == "Y" && strlen($arResult["DETAIL_TEXT"]) > 0)
{
    $arTmpMenu["text"]["NAME"] = $arResult["PROPERTIES"]["MENUTITLE_BLOCK9"]["~VALUE"];
    $arTmpMenuSort["text"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK9"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK9"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK9"]["DEFAULT_VALUE"]);
    
    $arBlocks = true;
}

$arSort["text"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK9"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK9"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK9"]["DEFAULT_VALUE"]);


//text 2
if($arResult["PROPERTIES"]["SHOWMENU_BLOCK12"]["VALUE"] == "Y" && strlen($arResult["DETAIL_TEXT"]) > 0)
{
    $arTmpMenu["text2"]["NAME"] = $arResult["PROPERTIES"]["MENUTITLE_BLOCK12"]["~VALUE"];
    $arTmpMenuSort["text2"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK12"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK12"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK12"]["DEFAULT_VALUE"]);
    
    $arBlocks = true;
}

$arSort["text2"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK12"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK12"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK12"]["DEFAULT_VALUE"]);


//text 3
if($arResult["PROPERTIES"]["SHOWMENU_BLOCK15"]["VALUE"] == "Y" && strlen($arResult["DETAIL_TEXT"]) > 0)
{
    $arTmpMenu["text3"]["NAME"] = $arResult["PROPERTIES"]["MENUTITLE_BLOCK15"]["~VALUE"];
    $arTmpMenuSort["text3"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK15"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK15"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK15"]["DEFAULT_VALUE"]);
    
    $arBlocks = true;
}

$arSort["text3"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK15"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK15"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK15"]["DEFAULT_VALUE"]);


//gallery
if($arResult["PROPERTIES"]["SHOWMENU_BLOCK10"]["VALUE"] == "Y" && !empty($arResult["PROPERTIES"]["GALLERY"]["VALUE"]))
{
    $arTmpMenu["gallery"]["NAME"] = $arResult["PROPERTIES"]["MENUTITLE_BLOCK10"]["~VALUE"];
    $arTmpMenuSort["gallery"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK10"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK10"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK10"]["DEFAULT_VALUE"]);
    
    $arBlocks = true;
}

$arSort["gallery"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK10"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK10"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK10"]["DEFAULT_VALUE"]);






function cmp($a, $b) 
{
    if ($a == $b) {
        return 0;
    }
    return ($a < $b) ? -1 : 1;
}

uasort($arTmpMenuSort, "cmp");
uasort($arSort, "cmp");
uasort($arSortChars, "cmp");


if(!empty($arTmpMenuSort))
{
    foreach($arTmpMenuSort as $key=>$value)
        $arMenu[$key] = $arTmpMenu[$key];   
} 


$arResult["SIDE_MENU"] = $arMenu;
$arResult["BLOCK_SORT"] = $arSort;
$arResult["BLOCKS"] = $arBlocks;
$arResult["CHARS_SORT"] = $arSortChars;



$host = CPhoenixHost::getHost($_SERVER);
$url = $host.$_SERVER["REQUEST_URI"];

$arResult["SHARE_TITLE"] = strip_tags(htmlspecialcharsBack($arResult["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"]));
if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OG_TITLE"])>0)
    $arResult["SHARE_TITLE"] = strip_tags($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OG_TITLE"]);


$arResult["SHARE_DESCRIPTION"] = strip_tags(htmlspecialcharsBack($arResult["IPROPERTY_VALUES"]["ELEMENT_META_DESCRIPTION"]));
if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OG_DESCRIPTION"])>0)
    $arResult["SHARE_DESCRIPTION"] = strip_tags(htmlspecialcharsBack($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OG_DESCRIPTION"]));

$arResult["SHARE_DESCRIPTION"] = str_replace(array("\r\n", "\r", "\n", "<br>", "<br/>"), array(" ", " ", " "," "," "), $arResult["SHARE_DESCRIPTION"]);

if(!empty($arResult['MORE_PHOTOS']))
    $arResult["SHARE_IMG"] = $host.$arResult['MORE_PHOTOS'][0]['MIDDLE']['SRC'];

if($haveOffers)
    $arResult["SHARE_IMG"] = $host.$arResult['JS_OFFERS'][$arResult['OFFERS_SELECTED']]['MORE_PHOTOS'][0]['MIDDLE']['SRC'];

if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OG_IMAGE"])>0)
    $arResult["SHARE_IMG"] = $host.CFile::GetPath($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OG_IMAGE"]);


$arResult["SHARE_URL"] = $url;
if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OG_URL"])>0)
    $arResult["SHARE_URL"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OG_URL"];









$arResult["PROPERTIES"]["BTN_ACTION"]["VALUE_XML_ID"] = ($arResult["PROPERTIES"]["BTN_ACTION"]["VALUE_XML_ID"]=="")?"form":$arResult["PROPERTIES"]["BTN_ACTION"]["VALUE_XML_ID"];


$arResult["BTN"] = array(
    "BTN_NAME" => $arResult["PROPERTIES"]["BTN_NAME"]["VALUE"],
    "~BTN_NAME" => $arResult["PROPERTIES"]["BTN_NAME"]["~VALUE"],
    "ACTION" => $arResult["PROPERTIES"]["BTN_ACTION"]["VALUE_XML_ID"],
    "FORM_ID" => $arResult["PROPERTIES"]["BTN_FORM"]["VALUE"],
    "POPUP_ID" => $arResult["PROPERTIES"]["BTN_POPUP"]["VALUE"],
    "PRODUCT_ID" => ($arResult["PROPERTIES"]["BTN_OFFER_ID"]["VALUE"])?$arResult["PROPERTIES"]["BTN_OFFER_ID"]["VALUE"]:$arResult["PROPERTIES"]["BTN_PRODUCT_ID"]["VALUE"],
    "VIEW" => $arResult["PROPERTIES"]["BTN_VIEW"]["VALUE_XML_ID"],
    "BG_COLOR" => $arResult["PROPERTIES"]["BTN_BG_COLOR"]["VALUE"],
    "QUIZ_ID" => $arResult["PROPERTIES"]["BTN_QUIZ_ID"]["VALUE"],
    "LAND_ID" => $arResult["PROPERTIES"]["BTN_LAND"]["VALUE"],
    "URL" => $arResult["PROPERTIES"]["BTN_URL"]["VALUE"],
    "TARGET_BLANK" => $arResult["PROPERTIES"]["BTN_TARGET_BLANK"]["VALUE"],
    "HEADER" => strip_tags($arResult["~NAME"]),
    "ONCLICK" => $arResult["PROPERTIES"]["BTN_ONCLICK"]["VALUE"]
);

$arPropsTitle = array();

if (isset($_GET['oID']))
{
    foreach ($arResult['OFFERS'] as $offer)
    {
        if ($offer['ID'] == $_GET['oID'])
        {
            foreach ($arResult['SKU_PROPS'] as $propCode => $propValues)
            {
                if (count($propValues['VALUES']) > 2)
                {
                    if ($propValues['PROPERTY_TYPE'] == 'S')
                    {
                        $propId = $propValues['XML_MAP'][$offer['PROPERTIES'][$propCode]['VALUE']];
                        $propToTitle = $propValues['VALUES'][$propId]['NAME'];
                    }
                    else
                    {
                        $propToTitle = $offer['PROPERTIES'][$propCode]['VALUE'];
                    }
                    $arPropsTitle[] = $propToTitle;
                }
            }
        }
    }
}

$arResult['TITLE_PROPS'] = $arPropsTitle;

$cp = $this->__component;

if (is_object($cp))
{
    $cp->arResult['SECTIONS_ID'] = $arResult["SECTIONS_ID"];
    $cp->arResult['TITLE_PROPS'] = $arResult["TITLE_PROPS"];
    $cp->SetResultCacheKeys(array('ID','SECTIONS_ID','TITLE_PROPS','rating'));

    $arResult['SECTIONS_ID'] = $cp->arResult['SECTIONS_ID'];
    $arResult['TITLE_PROPS'] = $cp->arResult['TITLE_PROPS'];
}
?>