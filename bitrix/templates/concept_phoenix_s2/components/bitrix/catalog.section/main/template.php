<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Main;

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

global $PHOENIX_TEMPLATE_ARRAY;
?>


<?if( !empty($arResult["ITEMS"]) ):?>

    <?
    
        $showBtnBasket = $showBtnBasketOption = ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_ON"]["VALUE"]["ACTIVE"] === "Y" ) ? 1 : 0;



        $countItems = count($arResult["ITEMS"]);

        if($hide_items = isset($arParams["MAX_VISIBLE_ITEMS"]))
            $max_visible_items = intval($arParams["MAX_VISIBLE_ITEMS"]);


    ?>

    <div class="catalog-block">

        <?


            $classItem = "";
            

            if( $arResult['VIEW'] == "FLAT" )
            {
                $colsitem = "4";
                
                if($arParams["COLS"] == "4")
                    $colsitem = "3";

                $colsitemLg = "4";

                if(isset($arParams["COLS_LG"]))
                {
                    if($arParams["COLS_LG"] == "2")
                        $colsitemLg = "6";

                    if($arParams["COLS_LG"] == "3")
                        $colsitemLg = "4";

                    if($arParams["COLS_LG"] == "4")
                        $colsitemLg = "3";
                    
                }
            }
            
        ?>


        <div class="catalog-list <?=$arResult["VIEW"]?> <?=($hide_items)? "show-hidden-parent":"";?>">
            <div class="row">

                <script>
                    BX.message({
                        PRICE_TOTAL_PREFIX: '<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PRICE_TOTAL_PREFIX"]?>',
                        RELATIVE_QUANTITY_MANY: '<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_MANY"]["DESCRIPTION_2"]?>',
                        RELATIVE_QUANTITY_FEW: '<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_FEW"]["DESCRIPTION_2"]?>',
                        RELATIVE_QUANTITY: '<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_MANY"]["DESCRIPTION_NOEMPTY"]?>',
                        RELATIVE_QUANTITY_EMPTY: '<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_MANY"]["DESCRIPTION_EMPTY"]?>',
                        ARTICLE: '<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["ARTICLE_SHORT"]?>',
                    });
                      
                </script>
	

                <?$i = 0;?>
                <?foreach ($arResult['ITEMS'] as $keyItem => $arItem):?>

                    <?
                        $haveOffers = !empty($arItem["OFFERS"]) && !empty($arItem["SKU_PROPS"]);

                        $skuProps = array();

                        $measureRatio = $arItem["FIRST_ITEM"]['PRICE']['MIN_QUANTITY'];
                        $showDiscount = $arItem["FIRST_ITEM"]['PRICE']['PERCENT'] > 0;

                        $showBtnBasket = $showBtnBasketOption;


                        if( $arResult['VIEW'] == "FLAT" )
                            $classItem = "col-xl-".$colsitem." col-lg-".$colsitemLg." col-md-4 col-6 border-r";
                        
                        

                        $mainId = $this->GetEditAreaId($arItem['ID']).$arParams["OBJ_NAME"];
                        $obName = 'ob_'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $mainId);

                        $productTitle = isset($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] != ''
                        ? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
                        : $arItem['NAME'];
                        
                        $itemIds = array(
                            'ID' => $mainId,
                            'PICT' => $mainId.'_pict',
                            'QUANTITY' => $mainId.'_quantity',
                            'QUANTITY_DOWN' => $mainId.'_quant_down',
                            'QUANTITY_UP' => $mainId.'_quant_up',
                            'QUANTITY_MEASURE' => $mainId.'_quant_measure',
                            'PRICE_ID' => $mainId.'_price',
                            'PRICE_OLD' => $mainId.'_price_old',
                            'PRICE_TOTAL' => $mainId.'_price_total',
                            'DSC_PERC' => $mainId.'_dsc_perc',
                            'SKU_TREE' => $mainId.'_sku_tree',
                            'PROP' => $mainId.'_prop_',
                            'ARTICLE' => $mainId.'_article',
                            'AVAILABLE' => $mainId.'_available',
                            'DETAIL_URL_IMG' => $mainId.'_detail_url_img',
                            'SKU_CHARS' => $mainId.'_sku_chars',
                            'SHORT_DESCRIPTION' => $mainId.'_short_desc',
                            'PREVIEW_TEXT' => $mainId.'_preview_text',
                            'PRICE_MATRIX' => $mainId.'_price_matrix',
                            'BASKET_ACTIONS' => $mainId.'_basket_actions',
				            'ADD2BASKET' => $mainId.'_add2basket',
				            'MOVE2BASKET' => $mainId.'_move2basket',
                            'DELAY' => $mainId.'_delay',
                            'COMPARE' => $mainId.'_compare',

                        );
                    ?>



                    <?if($arResult['VIEW'] == "LIST" || $arResult['VIEW'] == "TABLE"):?>
                        <div class="col-12">
                    <?endif;?>

                        <div class="<?=$classItem?> item catalog-item <?=($countItems == ($keyItem+1)) ? 'last-item' : ''?>

                            <?if($hide_items && ($i+1) > $max_visible_items):?>
                                show-hidden-child
                                hidden
                            <?endif;?>

                        " id="<?=$mainId?>" data-entity="item">

                            <?
                            	$documentRoot = Main\Application::getDocumentRoot();
        						$templatePath = strtolower($arResult['VIEW']).'/template.php';

        						
        						$file = new Main\IO\File($documentRoot.$templateFolder.'/'.$templatePath);
        						if ($file->isExists())
        						{
        							include($file->getPath());
        						}

                            ?>
                        </div>

                    <?if($arResult['VIEW'] == "LIST" || $arResult['VIEW'] == "TABLE"):?>
                        </div>
                    <?endif;?>



                    <?
                        if ($haveOffers)
                        {
                            $jsParams = array(
                                'PRODUCT_TYPE' => $arItem['CATALOG_TYPE'],
                                'TEMPLATE' => $arResult["VIEW"],
                                'SHOW_QUANTITY' => ($showBtnBasketOption)?"Y":"",
                                'SHOW_ABSENT' => "Y",
                                'SHOW_OLD_PRICE' => true,
                                'SHOW_DISCOUNT_PERCENT' => "Y",
                                'NO_PHOTO_SRC' => $arResult["NO_PHOTO"],
                                'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
                                'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
                                'STORE_QUANTITY_ON' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_ON"]["VALUE"]["ACTIVE"],
                                'VIEW_STORE_QUANTITY' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_VIEW"]["VALUE"],
                                'ADD2BASKET_SHOW' => $showBtnBasketOption,
                                'VISUAL' => array(
                                    'ID' => $itemIds['ID'],
                                    'PICT_ID' => $itemIds['PICT'],
                                    'QUANTITY_ID' => $itemIds['QUANTITY'],
                                    'QUANTITY_UP_ID' => $itemIds['QUANTITY_UP'],
                                    'QUANTITY_DOWN_ID' => $itemIds['QUANTITY_DOWN'],
                                    'QUANTITY_MEASURE' => $itemIds['QUANTITY_MEASURE'],
                                    'PRICE_ID' => $itemIds['PRICE_ID'],
                                    'PRICE_OLD_ID' => $itemIds['PRICE_OLD'],
                                    'DSC_PERC' => $itemIds['DSC_PERC'],
                                    'DETAIL_URL_IMG' => $itemIds['DETAIL_URL_IMG'],
                                    'SKU_CHARS' => $itemIds['SKU_CHARS'],
                                    'SHORT_DESCRIPTION' => ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]["PROPS_IN_LIST_FOR_".$arResult["VIEW"]]["VALUE"]["DESCRIPTION"] == "Y")?$itemIds['SHORT_DESCRIPTION']:"",
                                    'PREVIEW_TEXT' => ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]["PROPS_IN_LIST_FOR_".$arResult["VIEW"]]["VALUE"]["PREVIEW_TEXT"] == "Y")?$itemIds['PREVIEW_TEXT']:"",
                                    'ARTICLE' => $itemIds['ARTICLE'],
                                    'AVAILABLE' => $itemIds['AVAILABLE'],
                                    'SKU_TREE' => $itemIds['SKU_TREE'],
                                    'PRICE_MATRIX' => $itemIds['PRICE_MATRIX'],
                                    'BASKET_ACTIONS' => $itemIds['BASKET_ACTIONS'],
						            'ADD2BASKET' => $itemIds['ADD2BASKET'],
						            'MOVE2BASKET' => $itemIds['MOVE2BASKET'],
                                    'DELAY' => $itemIds['DELAY'],
                                    'COMPARE' => $itemIds['COMPARE'],
                                ),
                                'PRODUCT' => array(
                                    'ID' => $arItem['ID'],
                                    'NAME' => $productTitle,
                                    'DETAIL_PAGE_URL' => $arItem['DETAIL_PAGE_URL'],
                                    'QUANTITY_FOR_MANY' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_MANY"]["VALUE"],
                                    'QUANTITY_FOR_MANY_IS_FLOAT' => CPhoenix::isStringFloat($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_MANY"]["VALUE"]),
                                    'QUANTITY_FOR_FEW' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_FEW"]["VALUE"],
                                    'QUANTITY_FOR_FEW_IS_FLOAT' => CPhoenix::isStringFloat($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_FEW"]["VALUE"]),

                                ),
                                'COMPARE_URL' => SITE_DIR.'catalog/compare/',
                                'OFFERS' => array(),
                                'OFFER_SELECTED' => 0,
                                'TREE_PROPS' => array(),
                                'USE_DELAY' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["DELAY_ON"]["VALUE"]["ACTIVE"],
                                'USE_COMPARE' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["ACTIVE"]["VALUE"]["ACTIVE"],
                                'OFFERS' => $arItem['JS_OFFERS'],
                                'OFFER_SELECTED' => $arItem['OFFERS_SELECTED'],
                                'TREE_PROPS' => $skuProps,
                                'VIEW_BLOCK' => $arResult["VIEW"]

                            );

                        }
                        else
                        {
                            $jsParams = array(
                                'PRODUCT_TYPE' => $arItem['CATALOG_TYPE'],
                                'TEMPLATE' => $arResult["VIEW"],
                                'SHOW_QUANTITY' => ($showBtnBasketOption)?"Y":"",
                                'SHOW_ABSENT' => "Y",
                                'SHOW_OLD_PRICE' => true,
                                'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
                                'SHOW_DISCOUNT_PERCENT' => "Y",
                                'ADD2BASKET_SHOW' => $showBtnBasketOption,
                                'PRODUCT' => array(
                                    'ID' => $arItem['ID'],
                                    'NAME' => $productTitle,
                                    'DETAIL_PAGE_URL' => $arItem['DETAIL_PAGE_URL'],
                                    'PICT' => "",
                                    'CAN_BUY' => $arItem['CAN_BUY'],
                                    'CHECK_QUANTITY' => $arItem['CHECK_QUANTITY'],
                                    'MAX_QUANTITY' => $arItem['CATALOG_QUANTITY'],
                                    'STEP_QUANTITY' => $arItem['ITEM_MEASURE_RATIOS'][$arItem['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'],
                                    'QUANTITY_FLOAT' => is_float($arItem['ITEM_MEASURE_RATIOS'][$arItem['ITEM_MEASURE_RATIO_SELECTED']]['RATIO']),
                                    'ITEM_PRICE_MODE' => $arItem['ITEM_PRICE_MODE'],
                                    'ITEM_PRICES' => $arItem['ITEM_PRICES'],
                                    'ITEM_PRICE_SELECTED' => $arItem['ITEM_PRICE_SELECTED'],
                                    'ITEM_QUANTITY_RANGES' => $arItem['ITEM_QUANTITY_RANGES'],
                                    'ITEM_QUANTITY_RANGE_SELECTED' => $arItem['ITEM_QUANTITY_RANGE_SELECTED'],
                                    'ITEM_MEASURE_RATIOS' => $arItem['ITEM_MEASURE_RATIOS'],
                                    'ITEM_MEASURE_RATIO_SELECTED' => $arItem['ITEM_MEASURE_RATIO_SELECTED'],
                                ),
                                'VISUAL' => array(
                                    'ID' => $itemIds['ID'],
                                    'PICT_ID' => $itemIds['PICT'],
                                    'QUANTITY_ID' => $itemIds['QUANTITY'],
                                    'QUANTITY_UP_ID' => $itemIds['QUANTITY_UP'],
                                    'QUANTITY_DOWN_ID' => $itemIds['QUANTITY_DOWN'],
                                    'PRICE_ID' => $itemIds['PRICE_ID'],
                                    'PRICE_OLD_ID' => $itemIds['PRICE_OLD'],
                                    'BASKET_ACTIONS' => $itemIds['BASKET_ACTIONS'],
						            'ADD2BASKET' => $itemIds['ADD2BASKET'],
						            'MOVE2BASKET' => $itemIds['MOVE2BASKET'],
                                    'DELAY' => $itemIds['DELAY'],
                                    'COMPARE' => $itemIds['COMPARE'],
                                ),
                                'COMPARE_URL' => SITE_DIR.'catalog/compare/',
                                'USE_DELAY' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["DELAY_ON"]["VALUE"]["ACTIVE"],
                                'USE_COMPARE' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["ACTIVE"]["VALUE"]["ACTIVE"],
                                'VIEW_BLOCK' => $arResult["VIEW"]
                                
                            );
                        }
                        
                    ?>

                    <script>
                      var <?=$obName?> = new JCCatalogItem(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);
                    </script>


                    <?if($arResult['VIEW'] == "FLAT"):?>

                        <?if($countItems != ($keyItem+1)):?>


                            <?if($arParams["COLS"] == "4"):?>

                                <?if( ($keyItem+1) % 4 == 0  ):?>
                                    <span class="col-12 break-line visible-xxl visible-xl visible-lg">
                                        <div class="<?if($hide_items && ($i+1) > $max_visible_items):?>show-hidden-child hidden<?endif;?>"></div>
                                    </span>
                                <?endif;?>

                                <?if( ($keyItem+1) % 3 == 0  ):?>
                                    <span class="col-12 break-line visible-md">
                                        <div></div>
                                    </span>
                                <?endif;?>

                            <?endif;?>

                            <?if($arParams["COLS"] == "3"):?>

                                <?if( ($keyItem+1) % 3 == 0  ):?>
                                    <span class="col-12 break-line visible-xxl visible-xl visible-lg visible-md">
                                        <div class="<?if($hide_items && ($i+1) > $max_visible_items):?>show-hidden-child hidden<?endif;?>"></div>
                                    </span>
                                <?endif;?>

                            <?endif;?>

                            <?if( ($keyItem+1) % 2 == 0 ):?>
                                <span class="col-12 break-line visible-sm visible-xs">
                                    <div class="<?if($hide_items && ($i+1) > $max_visible_items):?>show-hidden-child hidden<?endif;?>"></div>
                                </span>
                            <?endif;?>

                        <?endif;?>

                    <?endif;?>

                    <?$i++;?>

                <?endforeach;?>


                <?if($hide_items && $countItems > $max_visible_items):?>

                    <div class="col-12">

                        <div class="catalog-button-wrap center" >

                            <a class="button-def secondary big show-hidden <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]['VALUE']?>">
                                <?if(strlen($arParams["MAX_VISIBLE_ITEMS_BTN_NAME"])):?>
                                    <?=$arParams["MAX_VISIBLE_ITEMS_BTN_NAME"]?>
                                <?else:?>
                                    <?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SHOW_ALL"]?>
                                <?endif;?>
                            </a>
                            
                        </div>
                    </div>
               
                <?endif;?>

            </div>
        </div>

    </div>
    
    <?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
        <?=$arResult["NAV_STRING"]?>
    <?endif;?>

<?else:?>

    <?if($arParams["FROM"] == "section"):?>


        <?

            $showAlert = true;

            if($arParams["INCLUDE_SUBSECTIONS"] == "N")
            {
                $showAlert = false;

                $arSelect = Array("ID");
                $arFilter = Array("IBLOCK_ID"=>$arParams["IBLOCK_ID"],"SECTION_ID"=>$arParams["SECTION_ID"], "ACTIVE"=>"Y", "SECTION_ACTIVE" => "Y", "SECTION_SCOPE" => "iblock", "INCLUDE_SUBSECTIONS" => "Y");

                $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, array("nTopCount" => 1), $arSelect);

                if(intval($res->SelectedRowsCount())<=0)
                    $showAlert = true;

            }

        ?>

        <?if($showAlert):?>


            <div class="attention"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SECTION_CATALOG_EMPTY"]?></div>


            <?if($PHOENIX_TEMPLATE_ARRAY["IS_ADMIN"]):?>
                <a target="_blank" href="/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=<?=$arResult["IBLOCK_ID"]?>&type=<?=$arResult["IBLOCK_TYPE_ID"]?>&ID=0&lang=ru&IBLOCK_SECTION_ID=<?=$arResult["ID"]?>&find_section_section=<?=$arResult["ID"]?>&from=iblock_list_admin&PRODUCT_TYPE=P" class="button-def main-color big"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SECTION_CATALOG_BTN_ADD"]?></a>
            <?endif;?>

        
        <?endif;?>

    <?endif;?>
<?endif;?>