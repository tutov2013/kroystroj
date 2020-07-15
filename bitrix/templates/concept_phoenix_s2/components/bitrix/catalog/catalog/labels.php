<?global $PHOENIX_TEMPLATE_ARRAY, $html_constructor_labels;
	$html_constructor_labels = array();
	CPhoenixSku::getHIBlockOptions();


$show_setting = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["MODE_FAST_EDIT"]['VALUE']["ACTIVE"];

$property_enums = CIBlockPropertyEnum::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"], "CODE"=>"LABELS"));
$arLabels = array();

while($enum_fields = $property_enums->GetNext()){
    $arLabels[] = array(
    	"ID" => $enum_fields["ID"],
    	"NAME" => getMessage("PHOENIX_MESS_LABEL_".$enum_fields["XML_ID"]),
    	"XML_ID" => $enum_fields["XML_ID"],
    );
}

?>
<?if(!empty($arLabels)):?>
<?foreach($arLabels as $labelKey => $arLabel):?>

<?$arFilter = Array("IBLOCK_ID"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y",  "PROPERTY_LABELS"=>$arLabel["ID"], "INCLUDE_SUBSECTIONS" => "Y", "SECTION_ACTIVE"=>"Y","SECTION_GLOBAL_ACTIVE" => "Y", "SECTION_SCOPE"=>"Y");

	$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, array("nTopCount"=>1), array("ID"));

	if($res->SelectedRowsCount() <=0)
		unset($arLabels[$labelKey]);
?>
<?endforeach;?>
<?endif;?>

<?$showBtnBasket = $showBtnBasketOption = ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_ON"]["VALUE"]["ACTIVE"] === "Y" ) ? 1 : 0;?>

<?
$arResult["COLS"] = "4";
?>
<?if(!empty($arLabels)):?>

	<?foreach($arLabels as $arLabel)
	{

		$GLOBALS['arFilterSectionLabels'] = array(
				"INCLUDE_SUBSECTIONS" => "Y", 
				"PROPERTY_LABELS" => $arLabel["ID"], 
				"SECTION_ACTIVE"=>"Y",
				"SECTION_GLOBAL_ACTIVE" => "Y",
				"SECTION_SCOPE" => "IBLOCK");

		$intSectionID = $APPLICATION->IncludeComponent(
			"bitrix:catalog.section",
			"labels",
			array(
				
				"OBJ_NAME" => $arLabel["ID"],
				'LABEL' => $arLabel,
				'CURRENCY_ID' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CURRENCY_ID']['VALUE'],
				'CONVERT_CURRENCY' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CONVERT_CURRENCY']['VALUE']["ACTIVE"],
				"USE_PRICE_COUNT" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['USE_PRICE_COUNT']['VALUE']["ACTIVE"] == "Y" ? "Y" : "N",
				"OFFERS_CART_PROPERTIES" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['VALUE_'],
				"OFFERS_PROPERTY_CODE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['VALUE_'],
				"PRICE_CODE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['TYPE_PRICE']["VALUE_"],
				'OFFER_TREE_PROPS' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['VALUE_'],
				"IBLOCK_TYPE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_TYPE"],
				"IBLOCK_ID" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"],
				"PAGE_ELEMENT_COUNT" => (intval($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["LABELS_MAX_COUNT"]["VALUE"]))? $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["LABELS_MAX_COUNT"]["VALUE"] : 12,

				"FILTER_NAME" => "arFilterSectionLabels",
				"COLS" => "4",
				
				"ELEMENT_SORT_FIELD" => "SORT",
		        "ELEMENT_SORT_ORDER" => "ASC",
		        "ELEMENT_SORT_FIELD2" => "ID",
		        "ELEMENT_SORT_ORDER2" => "ASC",
				"CUSTOM_FILTER" => "",
				"COMPONENT_TEMPLATE" => "labels",
				"BASKET_URL" => "",
				"OFFERS_SORT_FIELD" => "sort",
		        "OFFERS_SORT_ORDER" => "id",
		        "OFFERS_SORT_FIELD2" => "asc",
		        "OFFERS_SORT_ORDER2" => "asc",
				"OFFERS_LIMIT" => "0",
				"ADD_SECTIONS_CHAIN" => "N",
				"INCLUDE_SUBSECTIONS" => "Y",
				"SHOW_ALL_WO_SECTION" => "Y",
				"SECTION_ID" => "",
				"SECTION_CODE" => "",
		        "USE_PRODUCT_QUANTITY" => "Y",
		        "SHOW_PRICE_COUNT" => "1",
		        'SHOW_OLD_PRICE' => "Y",
		        'SHOW_MAX_QUANTITY' => "Y",
		        "PRICE_VAT_INCLUDE" => "Y",
		        'SHOW_DISCOUNT_PERCENT' => "Y",
		        "ADD_PROPERTIES_TO_BASKET" => "Y",
		        "PAGER_TEMPLATE" => "phoenix_round",
		        "DISPLAY_TOP_PAGER" => "N",
				"DISPLAY_BOTTOM_PAGER" =>  "N",
				"PAGER_SHOW_ALL" => "N",

				"OFFERS_FIELD_CODE" => array("NAME","PREVIEW_TEXT","PREVIEW_PICTURE","DETAIL_TEXT","DETAIL_PICTURE",""),


				"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
				"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],


				
				"PROPERTY_CODE" => array(),
				"PROPERTY_CODE_MOBILE" => array(),
				"META_KEYWORDS" => '',
				"META_DESCRIPTION" => '',
				"BROWSER_TITLE" => '',
				"SET_LAST_MODIFIED" => '',
				"INCLUDE_SUBSECTIONS" => 'Y',
				"ACTION_VARIABLE" => '',
				"PRODUCT_ID_VARIABLE" =>  '',
				"SECTION_ID_VARIABLE" =>  '',
				"PRODUCT_QUANTITY_VARIABLE" =>'',
				"PRODUCT_PROPS_VARIABLE" =>'',
				"CACHE_TYPE" => 'A',
				"CACHE_TIME" => '36000000',
				"CACHE_FILTER" => 'Y',
				"CACHE_GROUPS" => 'Y',
				"SET_TITLE" => '',
				"MESSAGE_404" => '',
				"SET_STATUS_404" => 'Y',
				"SHOW_404" => '',
				"FILE_404" => '',
				"DISPLAY_COMPARE" => '',
				"LINE_ELEMENT_COUNT" => '',
				"PRICE_VAT_INCLUDE" => "Y",
				"PARTIAL_PRODUCT_PROPERTIES" => '',
				"PRODUCT_PROPERTIES" => '',
				"PRODUCT_PROPERTIES" => '',
				"PAGER_TITLE" => '',
				"PAGER_SHOW_ALWAYS" => '',
				"PAGER_DESC_NUMBERING" => '',
				"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
				"PAGER_BASE_LINK_ENABLE" => '',
				"PAGER_BASE_LINK" =>'',
				"PAGER_PARAMS_NAME" => '',
				"LAZY_LOAD" => '',
				"MESS_BTN_LAZY_LOAD" => '',
				"LOAD_ON_SCROLL" => '',
				"USE_MAIN_ELEMENT_SECTION" => '',
                'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
                'HIDE_NOT_AVAILABLE_OFFERS' => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],
				'LABEL_PROP' => '',
				'LABEL_PROP_MOBILE' => '',
				'LABEL_PROP_POSITION' => '',
				'ADD_PICT_PROP' => '',
				'PRODUCT_DISPLAY_MODE' => '',
				'PRODUCT_BLOCKS_ORDER' => '',
				'PRODUCT_ROW_VARIANTS' => '',
				'ENLARGE_PRODUCT' => '',
				'ENLARGE_PROP' => '',
				'SHOW_SLIDER' => '',
				'SLIDER_INTERVAL' => '',
				'SLIDER_PROGRESS' => '',
				'OFFER_ADD_PICT_PROP' => '',
				'PRODUCT_SUBSCRIPTION' => 'Y',
				'DISCOUNT_PERCENT_POSITION' => '',
				'USE_ENHANCED_ECOMMERCE' => '',
				'DATA_LAYER_NAME' => '',
				'BRAND_PROPERTY' => '',
				'ADD_TO_BASKET_ACTION' => '',
				'SHOW_CLOSE_POPUP' => '',
				'COMPARE_PATH' => '',
				'COMPARE_NAME' => '',
				
			),
			$component
		);
		
	}

	?>
	<div class="catalog-block with-tabs tab-control">
		<div class="container">

			<div class="catalog-tabs hidden-md hidden-sm hidden-xs row">

	    		<?foreach($arLabels as $key => $arLabel):?>

		    		<div class="catalog-tab-element tab-menu col-lg-3 col-md-3 <?if($key == 0):?>active<?endif;?>" 
		    			data-tab='id_<?=$arLabel["ID"]?>'>

		                <div class="name-wrap">
		                    <div class="name ic_<?=$arLabel["XML_ID"]?>">
		                        <?=$arLabel["NAME"]?>
		                    </div>
		                    <div class="line"></div>
		                </div>

		            </div>


	            <?endforeach;?>

	    	</div>


		</div>

		<div class="block-grey-line hidden-md hidden-sm hidden-xs"></div>

		<div class="catalog-content-wrap parent-tool-settings">
	        <div class="container">

	        	<div class="tabb-content-wrap hidden-sm hidden-xs" itemscope itemtype="http://schema.org/ItemList">

	        		<?
	        			$colsitem = "4";

					    if($arResult["COLS"] == "4")
					        $colsitem = "3";


	        			$i = 0;
	        		?>

	        		<?foreach($html_constructor_labels as $keyLabel => $arLabel):?>



	        			<div class="catalog-content tabb-content show-hidden-parent parent-slide-show <?if($i == 0):?>active<?endif;?>" data-tab='id_<?=$arLabel["ID"]?>'>

							<div class="mob-title click-slide-show ">
							    <?=$arLabel["NAME"]?>
							    <div class='main-color'></div>
							    <span></span>
							</div>

							<div class="mob-show content-slide-show ">

								<div class="catalog-block">
								    <div class="catalog-list FLAT">
								        <div class="row">

											<?if(!empty($arLabel["ITEMS"])):?>

												<?
													$j = 0;
													$countItems = count($arLabel["ITEMS"]);
												?>

								        		<?foreach ($arLabel["ITEMS"] as $keyItemLabel => $arItemLabel):?>

								        			<?

								        				$haveOffers = !empty($arItemLabel["OFFERS"]) && !empty($arItemLabel["SKU_PROPS"]);

								        				$skuProps = array();

									                    $measureRatio = $arItemLabel["FIRST_ITEM"]['PRICE']['MIN_QUANTITY'];
								                        $showDiscount = $arItemLabel["FIRST_ITEM"]['PRICE']['PERCENT'] > 0;


									                    $mainId = $arLabel["ID"]."_".$arItemLabel["ID"]."_catalog_labels";
									                    $obName = 'ob_'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $mainId);

									                    $productTitle = isset($arItemLabel['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $arItemLabel['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] != ''
									                        ? $arItemLabel['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
									                        : $arItemLabel['NAME'];

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

								        			<div class="col-xl-<?=$colsitem?> col-lg-3 col-md-4 col-6 item catalog-item border-r" id="<?=$mainId?>" data-entity="item" itemprop="itemListElement" itemscope itemtype="http://schema.org/Product">

								        				<div class="item-inner">

														    <div class="wrapper-top">

														        <div class="wrapper-image row no-gutters align-items-center">

														            <a href="<?=$arItemLabel["DETAIL_PAGE_URL"]?>" class="d-block col" id="<?=$itemIds["DETAIL_URL_IMG"]?>">

														                <img class="img-fluid d-block mx-auto lazyload" id="<?=$itemIds["PICT"]?>" data-src="<?=$arItemLabel["FIRST_ITEM"]["PREVIEW_PICTURE_SRC"]?>" alt="<?=$arItemLabel["FIRST_ITEM"]["PREVIEW_PICTURE_DESC"]?>"/>
														                
														            </a>

														            <?if(!empty($arItemLabel["PROPERTIES"]["LABELS"]["VALUE_XML_ID"])):?>
														                <div class="wrapper-board-label">
														                    <?foreach($arItemLabel["PROPERTIES"]["LABELS"]["VALUE_XML_ID"] as $k=>$xml_id):?>
														                        <div class="mini-board <?=$xml_id?>" title="<?=$item["PROPERTIES"]["LABELS"]["VALUE"][$k]?>"><?=$arItemLabel["PROPERTIES"]["LABELS"]["VALUE"][$k]?></div>
														                    <?endforeach;?>
														                </div>
														            <?endif;?>
														            

														            <?if (!isset($arItemLabel['OFFER_WITHOUT_SKU'])):?>
														                <span id="<?=$itemIds['DSC_PERC']?>" class="sale <?=($haveOffers)?"hidden-md hidden-sm hidden-xs":"";?>" <?=($price["PERCENT"] > 0 ? '' : 'style="display: none;"')?>><?=-$price["PERCENT"]?>%</span>

														                
														                <?if( $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["DELAY_ON"]["VALUE"]["ACTIVE"] == "Y" 
														                  || $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["ACTIVE"]["VALUE"]["ACTIVE"] == "Y" ):?>

														                    <div class="wrapper-delay-compare-icons <?=($haveOffers)?"hidden-md hidden-sm hidden-xs":"";?>">

														                        <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["DELAY_ON"]["VALUE"]["ACTIVE"] == "Y"):?>
														                            <div title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_DELAY_TITLE"]?>" class="icon delay add2delay" id = "<?=$itemIds["DELAY"]?>" data-item="<?=$arItemLabel["ID"]?>"></div>
														                        <?endif;?>

														                        <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["ACTIVE"]["VALUE"]["ACTIVE"] == "Y"):?>
														                            <div title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_COMPARE_TITLE"]?>" class="icon compare add2compare" id = "<?=$itemIds["COMPARE"]?>" data-item="<?=$arItemLabel["ID"]?>"></div>
														                        <?endif;?>
														                    </div>
														                <?endif;?>

														            <?endif;?>

														            
														        </div>

														        
														        
														           
														        <div class="wrapper-article-available row no-gutters d-none d-lg-flex">
														            <?=$arItemLabel["FIRST_ITEM"]["QUANTITY_HTML"]?>
														            <div class="detail-article italic"><?=(isset($arItemLabel["FIRST_ITEM"]["ARTICLE"]{0}))?$PHOENIX_TEMPLATE_ARRAY["MESS"]["ARTICLE_SHORT"].$arItemLabel["FIRST_ITEM"]["ARTICLE"]:""?></div>
														        </div>

														        

														        <a href="<?=$arItemLabel["DETAIL_PAGE_URL"]?>" class="name-element">
														            <?=$arItemLabel["~NAME"]?>
														        </a>

														        <?if(isset($arItemLabel['OFFER_WITHOUT_SKU']) && $arItemLabel['OFFER_WITHOUT_SKU'] == "Y"):?>

														            <div class="board-price row no-gutters" data-entity = "block-price" style='display: <?=($arItemLabel["FIRST_ITEM"]['MODE_ARCHIVE']=="Y" || $arItemLabel["FIRST_ITEM"]['PRICE']["PRICE"] == '-1') ? 'none' : ''?>;'>
														                <div class="actual-price">
														                    <span class="price-value" id="<?=$itemIds['PRICE_ID']?>"><?if($arItemLabel["DIFF"] > 0):?><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_PREFIX_FROM"]?><?endif;?> <?=$arItemLabel["MIN_PRICE"]["PRINT_DISCOUNT_VALUE"]?></span><span class="unit" id="<?=$itemIds['QUANTITY_MEASURE']?>" style='display: <?=(isset($arItemLabel['MEASURE_HTML']{0}) ? '' : 'none')?>;'><?=$arItemLabel['MEASURE_HTML']?></span>
														                </div>
														                <?if($arItemLabel["DIFF"] <= 0):?>
															                <div class="old-price align-self-end" id="<?=$itemIds['PRICE_OLD']?>">
													                            <?=$arItemLabel["MIN_PRICE"]["PRINT_VALUE"]?>
													                        </div>
												                        <?endif;?>
														            </div>


														        <?else:?>

														            <div data-entity = "block-price" <?if( $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['USE_PRICE_COUNT']['VALUE']["ACTIVE"] == 'Y' ):?>class="wrapper-board-price"<?endif;?> style='display: <?=($arItemLabel["FIRST_ITEM"]['MODE_ARCHIVE']=="Y" || $arItemLabel["FIRST_ITEM"]['PRICE']["PRICE"] == '-1') ? 'none' : ''?>;'>

														                <div class="board-price row no-gutters" >
														                    <div class="actual-price">

														                        <div class="<?=($haveOffers)?"d-none d-lg-block":""?>">

														                            <span class="price-value" id="<?=$itemIds['PRICE_ID']?>"><?=$arItemLabel["FIRST_ITEM"]['PRICE']['PRINT_PRICE']?></span><span class="unit" id="<?=$itemIds['QUANTITY_MEASURE']?>" style='display: <?=(isset($arItemLabel["FIRST_ITEM"]['MEASURE_PRICE']{0}) ? '' : 'none')?>;'><?=$arItemLabel["FIRST_ITEM"]['MEASURE_PRICE']?></span>
														                        </div>

														                        <?if($haveOffers):?>
														                            <div class="d-lg-none">
														                                <span class="price-value"><?if($arItemLabel["DIFF"] > 0):?><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_PREFIX_FROM"]?><?endif;?> <?=$arItemLabel["MIN_PRICE"]["PRINT_DISCOUNT_VALUE"]?></span><span class="unit" id="<?=$itemIds['QUANTITY_MEASURE']?>" style='display: <?=(isset($arItemLabel['MEASURE_HTML']{0}) ? '' : 'none')?>;'><?=$arItemLabel['MEASURE_HTML']?></span>
														                            </div>
														                        <?endif;?>

														                    </div>

														                    <?if(!isset($arItemLabel['OFFER_WITHOUT_SKU'])):?>
														                        <div class="old-price align-self-end <?if($haveOffers):?>d-none d-lg-block<?endif;?>" id="<?=$itemIds['PRICE_OLD']?>"
														                            style="display: <?=($showDiscount ? '' : 'none')?>;">
														                            <?=($showDiscount ? $arItemLabel["FIRST_ITEM"]['PRICE']['PRINT_BASE_PRICE'] : '')?>
														                        </div>
														                    <?endif;?>

														                </div>

														                <?if( $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['USE_PRICE_COUNT']['VALUE']["ACTIVE"] == 'Y' ):?>
														                    
														                    <?if($haveOffers):?>

														                        <div class="wrapper-matrix-block col-12" id= "<?=$itemIds["PRICE_MATRIX"]?>"></div>

														                    <?else:?>

														                        <?=CPhoenix::showPriceMatrix($arItem, $arItemLabel['ITEM_MEASURE']['TITLE']);?>

														                    <?endif;?>
														        
														                <?endif;?>
														            </div>


														        <?endif;?>

														        
														        
														    </div>


														    <div class="wrapper-bot part-hidden">


														        <?if(   $haveOffers
														                || strlen($arItemLabel["FIRST_ITEM"]["SHORT_DESCRIPTION"])
														                || strlen($arItemLabel["FIRST_ITEM"]["PREVIEW_TEXT"])
														                || $arItem["CHARS"]["COUNT"]>0
														                || $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_VOTE"]["VALUE"]["ACTIVE"] == "Y"

														            ):?>

														                <div class="wrapper-list-info">

														                    <div class="d-none d-lg-block">

														                        <?if($haveOffers):?>

														                            <div id="<?=$itemIds['SKU_TREE']?>" class="wrapper-skudiv">

														                                <?if(!empty($arItemLabel["SKU_PROPS"])):?>

														                                    <?foreach ($arItemLabel["SKU_PROPS"] as $skuProperty):?>

														                                        <?
														                                            $propertyId = $skuProperty['ID'];

														                                            if(!isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['VALUE_'][$propertyId]))
														                                                continue;

														                                            $skuProperty['NAME'] = htmlspecialcharsbx($skuProperty['NAME']);

														                                            $skuProps[] = array(
														                                                'ID' => $skuProperty['ID'],
														                                                'SHOW_MODE' => $skuProperty['SHOW_MODE'],
														                                                'VALUES' => $skuProperty['VALUES'],
														                                                'VALUES_COUNT' => $skuProperty['VALUES_COUNT']
														                                            );
														                                        ?>

														                                        <div class="wrapper-sku-props clearfix" data-entity="sku-block">
														                                            <div class="product-item-scu-container clearfix" data-entity="sku-line-block">

														                                                <div class="wrapper-title row no-gutters">
														                                                    <div class="desc-title"><?=htmlspecialcharsEx($skuProperty['NAME'])?><span class="prop-name" <?if( strlen($descTitle) ):?>data-entity="<?=$descTitle?>"<?endif;?>></span> </div>
														                                                    
														                                                </div>

														                                                <?if ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUES"][$skuProperty["ID"]]["VALUE_2"] == 'pic' || $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUES"][$skuProperty["ID"]]["VALUE_2"] == 'pic_with_info'):?>

														                                                    <ul class="sku-props clearfix">

														                                                        <?if(!empty($skuProperty['VALUES'])):?>

														                                                        <?foreach ($skuProperty['VALUES'] as $value):?>

														                                                            <?

														                                                                $styleTab = "";
														                                                                $styleHoverBoard = "";


														                                                                if(isset($value["PICT"]) || isset($value["PICT_SEC"]) )
														                                                                {
														                                                                    if(isset($value["PICT_SEC"]))
														                                                                    {
														                                                                        $styleHoverBoard .= "background-image: url('".$value['PICT_SEC']['BIG']."'); ";

														                                                                        if(isset($value["PICT"]))
														                                                                            $styleTab .= "background-image: url('".$value['PICT']['SMALL']."'); ";
														                                                                        else
														                                                                            $styleTab .= "background-image: url('".$value['PICT_SEC']['SMALL']."'); ";

														                                                                    }

														                                                                    else if(isset($value["PICT"]))
														                                                                    {
														                                                                        $styleTab .= "background-image: url('".$value['PICT']['SMALL']."'); ";
														                                                                        $styleHoverBoard .= "background-image: url('".$value['PICT']['BIG']."'); ";
														                                                                    }
														                                                                }

														                                                                if($value["COLOR"])
														                                                                {
														                                                                    $styleTab .= "background-color:".$value["COLOR"]."; ";
														                                                                    $styleHoverBoard .= "background-color:".$value["COLOR"]."; ";
														                                                                }
														                                                            ?>


														                                                            <li title='<?=str_replace("'", "\"", $value['NAME'])?>' class="detail-color"

														                                                                    data-treevalue="<?=$propertyId?>_<?=$value['ID']?>"
														                                                                    data-onevalue="<?=$value['ID']?>"
														                                                                    

														                                                                >

														                                                                <div class="color" style="<?=$styleTab?>"></div>


														                                                                <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUES"][$skuProperty["ID"]]["VALUE_2"] == 'pic_with_info'):?>

														                                                                    <div class="wrapper-hover-board">
														                                                                        <div class="img" style="<?=$styleHoverBoard?>"></div>
														                                                                        <div class="desc"><?=$value['NAME']?></div>
														                                                                        <div class="arrow"></div>
														                                                                    </div>

														                                                                <?endif;?>

														                                                                <span class="active-flag"></span>

														                                                            </li>

														                                                        <?endforeach;?>

														                                                        <?endif;?>
														                                                    </ul>

														                                                <?elseif($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUES"][$skuProperty["ID"]]["VALUE_2"] == 'select'):?>

														                                                    <div class="wrapper-select-input">

														                                                        <ul class="sku-props select-input">

														                                                            <li class="area-for-current-value"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SKU_SELECT_TITLE"]?></li>

														                                                            <?if(!empty($skuProperty['VALUES'])):?>

														                                                                <?foreach ($skuProperty['VALUES'] as $value):?>
														                                                                    <li title='<?=str_replace("'", "\"", $value['NAME'])?>'

														                                                                            data-treevalue="<?=$propertyId?>_<?=$value['ID']?>"
														                                                                            data-onevalue="<?=$value['ID']?>"

														                                                                        ><?=$value['NAME']?></li>
														                                                                <?endforeach;?>

														                                                            <?endif;?>
														                                                           
														                                                        </ul>

														                                                        <div class="ar-down"></div>

														                                                    </div>


														                                                <?else:?>

														                                                    <ul class="sku-props">

														                                                        <?if(!empty($skuProperty['VALUES'])):?>

														                                                            <?foreach ($skuProperty['VALUES'] as &$value):?>
														                                                                <li title='<?=str_replace("'", "\"", $value['NAME'])?>' class="detail-text"

														                                                                    data-treevalue="<?=$propertyId?>_<?=$value['ID']?>"
														                                                                    data-onevalue="<?=$value['ID']?>"

														                                                                ><?=$value['NAME']?></li>
														                                                            <?endforeach;?>

														                                                        <?endif;?>
														                                                    </ul>


														                                                <?endif;?>


														                                            </div>

														                                        </div>

														                                    <?endforeach;?>

														                                <?endif;?>

														                            </div>                            

														                        <?endif;?>

														                        <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]["PROPS_IN_LIST_FOR_FLAT"]["VALUE"]["DESCRIPTION"] == "Y"):?>

														                            <div class="short-description" id="<?=$itemIds["SHORT_DESCRIPTION"]?>"><?=$arItemLabel["FIRST_ITEM"]["SHORT_DESCRIPTION"]?></div>

														                        <?endif;?>


														                        <?if( $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]["PROPS_IN_LIST_FOR_FLAT"]["VALUE"]["PREVIEW_TEXT"] == "Y" ):?>

														                            <div class="preview-text" id="<?=$itemIds["PREVIEW_TEXT"]?>"><?=$arItemLabel["FIRST_ITEM"]["PREVIEW_TEXT"]?></div>

														                        <?endif;?>

														                    </div>

														                    <div class="<?=($haveOffers)? "d-none d-lg-block":""?>">

														                        <?
														                            if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_VOTE"]["VALUE"]["ACTIVE"] == "Y")
														                            {?>
														                                <?if($arLabel["RATING_VIEW"] == "simple"):?>
														                                                    
														                                    <?=CPhoenix::GetRatingVoteHTML(array("ID"=>$arItemLabel['ID'], "CLASS"=>"simple-rating hover"));?>

														                                <?elseif($arLabel["RATING_VIEW"] == "full"):?>

														                                    <?=CPhoenix::GetRatingVoteHTML(array("ID"=>$arItemLabel['ID'], "VIEW"=>"rating-reviewsCount", "HREF"=>$arItemLabel["DETAIL_PAGE_URL"]."#rating-block"));?>

														                                <?endif;?>

														                            <?}
														                        ?>

														                    </div>

														                    <?if($haveOffers):?>

														                        <div class="d-lg-none count-offers">
														                            <?=count($arItemLabel["OFFERS"])." ".CPhoenix::getTermination(count($arItemLabel["OFFERS"]), $PHOENIX_TEMPLATE_ARRAY["TERMINATIONS_OFFERS"])?>
														                        </div>

														                    <?endif;?>

														                    <div class="d-none d-lg-block">

														                        <?if(!empty($arItemLabel["CHARS_SORT"])):?>

									                            <?$countChars = 0;?>

									                            <div class="wrapper-characteristics">

									                                <div class="characteristics show-hidden-parent">

									                                    <?foreach ($arItemLabel["CHARS_SORT"] as $keyChar => $valueChar):?>

									                                        <?if($keyChar == "sku_chars"):?>

									                                            <div class="sku-chars" id = "<?=$itemIds["SKU_CHARS"]?>"></div>

									                                        <?elseif($keyChar == "props_chars"):?>

									                                            <?if(!empty($arItemLabel["PROPS_CHARS"])):?>
									                        
									                                                <?foreach($arItemLabel["PROPS_CHARS"] as $key=>$value):?>

									                                                    <div class="characteristics-item show-hidden-child <?if($countChars >= 5):?>hidden<?endif;?>">
									                                
									                                                        <span class="characteristics-item-name bold"><?=$value["NAME"]?>:</span>&nbsp;<span class="characteristics-item-value"><?=$value["VALUE"]?></span>

									                                                    </div>

									                                                    <?$countChars++?>

									                                                <?endforeach;?>

									                                            <?endif;?>



									                                        <?elseif($keyChar == "prop_chars"):?>

									                                            <?if(!empty($arItemLabel["PROP_CHARS"])):?>
									                        
									                                                <?foreach($arItemLabel["PROP_CHARS"] as $key=>$value):?>

									                                                    <div class="characteristics-item show-hidden-child <?if($countChars >= 5):?>hidden<?endif;?>">
									                                
									                                                        <span class="characteristics-item-name bold"><?=$value["NAME"]?>:</span>&nbsp;<span class="characteristics-item-value"><?=$value["VALUE"]?></span>

									                                                    </div>

									                                                    <?$countChars++?>

									                                                <?endforeach;?>

									                                            <?endif;?>

									                                        <?endif;?>

									                                    <?endforeach;?>



									                                    <?if($countChars > 5):?>

									                                        <div class="show-hidden-wrap">
									                                            <a class="show-hidden btn-style-light"><span class="bord-bot"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SHOW_ALL"]?></span></a>
									                                        </div>

									                                    <?endif;?>

									                                </div>

									                            </div>

									                        <?endif;?>

														                    </div>


														                </div>
														            

														        <?endif;?>

														        <?
														        	$showBtnBasket = $showBtnBasketOption;

														            if(!$arItemLabel["FIRST_ITEM"]["CAN_BUY"] || $arItemLabel["FIRST_ITEM"]["SHOWPREORDERBTN"] || $arItemLabel["FIRST_ITEM"]["MODE_DISALLOW_ORDER"] || $arItemLabel["FIRST_ITEM"]["MODE_ARCHIVE"])
														                $showBtnBasket = false;


														        ?>

														        

														        <div class="wrapper-inner-bot row no-gutters <?=($haveOffers)?"hidden-md hidden-sm hidden-xs":""?> <?=($showBtnBasket)?"":"d-none"?>" data-entity = "btns-quantity">

														            <div class="quantity-container row no-gutters align-items-center col-xl-6 quantity-block d-none d-xl-flex" 
														                 data-entity="quantity-block" 
														                 data-item="<?=$arItemLabel['ID']?>" 
														                 style='display: <?=($arItemLabel['CAN_BUY'] ? '' : 'none')?>;'>

														                <table>
														                    <tr>
														                        <td class="btn-quantity"><span class="product-item-amount-field-btn-minus no-select" id="<?=$itemIds['QUANTITY_DOWN']?>">&minus;</span></td>
														                        <td><input class="product-item-amount-field" id="<?=$itemIds['QUANTITY']?>" type="number" name="<?=$arParams['PRODUCT_QUANTITY_VARIABLE']?>" value="<?=$measureRatio?>"></td>
														                        <td class="btn-quantity"><span class="product-item-amount-field-btn-plus no-select" id="<?=$itemIds['QUANTITY_UP']?>">&plus;</span></td>
														                    </tr>
														                </table>

														                <span class="d-none" id="<?=$itemIds['QUANTITY_MEASURE']?>"><?=$arItemLabel['ITEM_MEASURE']['TITLE']?></span>

														                <span class="d-none" id="<?=$itemIds['PRICE_TOTAL']?>"></span>
														            </div>

														            <div class="btn-container align-items-center col-xl-6 col-12" id="<?=$itemIds['BASKET_ACTIONS']?>">
														                <a
														                    id = "<?=$itemIds['ADD2BASKET']?>"
														                    href="javascript:void(0);"
														                    data-item = "<?=$arItemLabel["ID"]?>"

														                class="main-color add2basket bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADD_NAME"]["~VALUE"]?></a>

														                <a
														                    id = "<?=$itemIds['MOVE2BASKET']?>"
														                    href="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_URL"]["VALUE"]?>"
														                    data-item = "<?=$arItemLabel["ID"]?>"

														                class="move2basket"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADDED_NAME"]["~VALUE"]?></a>
														            </div>


														        </div>

														        <div class="wrapper-inner-bot row no-gutters 

														            <?if($showBtnBasket):?>
														                d-none
														            <?elseif($haveOffers):?>
														                d-none d-lg-block
														            <?endif;?>"

														            data-entity = "link-to-detail-page">

														            <div class="btn-container align-items-center col-12">
														                <a href="<?=$arItemLabel["DETAIL_PAGE_URL"]?>" class="main-color bold"><?=($haveOffers)?$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["LINK_2_DETAIL_PAGE_NAME_OFFER"]["VALUE"]:$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["LINK_2_DETAIL_PAGE_NAME"]["VALUE"]?></a>
														            </div>

														        </div>

														        <?if($haveOffers):?>

														            <div class="d-lg-none">
														                <div class="wrapper-inner-bot row no-gutters">

														                    <div class="btn-container align-items-center col-12">
														                        <a href="<?=$arItemLabel["DETAIL_PAGE_URL"]?>"

														                        class="main-color bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["LINK_2_DETAIL_PAGE_NAME_OFFER_MOB"]["VALUE"]?></a>
														                    </div>

														                </div>
														            </div>
														        <?endif;?>

														    </div>


														</div>
													    

													    <?CPhoenix::admin_setting($arItemLabel, false)?>

													    <?

													        if ($haveOffers)
													        {
													            $jsParams = array(
													                'PRODUCT_TYPE' => $arItemLabel['CATALOG_TYPE'],
													                'TEMPLATE' => "FLAT",
													                'SHOW_QUANTITY' => ($showBtnBasketOption)?"Y":"",
													                'SHOW_ABSENT' => "Y",
													                'SHOW_OLD_PRICE' => "Y",
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
													                    'SHORT_DESCRIPTION' => $itemIds['SHORT_DESCRIPTION'],
													                    'PREVIEW_TEXT' => $itemIds['PREVIEW_TEXT'],
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
													                    'ID' => $arItemLabel['ID'],
													                    'NAME' => $productTitle,
													                    'DETAIL_PAGE_URL' => $arItemLabel['DETAIL_PAGE_URL'],
													                    'QUANTITY_FOR_MANY' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_MANY"]["VALUE"],
													                    'QUANTITY_FOR_MANY_IS_FLOAT' => CPhoenix::isStringFloat($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_MANY"]["VALUE"]),
													                    'QUANTITY_FOR_FEW' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_FEW"]["VALUE"],
													                    'QUANTITY_FOR_FEW_IS_FLOAT' => CPhoenix::isStringFloat($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_FEW"]["VALUE"]),

													                ),
													                'COMPARE_URL' => SITE_DIR.'catalog/compare/',
													                'USE_DELAY' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["DELAY_ON"]["VALUE"]["ACTIVE"],
													                'USE_COMPARE' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["ACTIVE"]["VALUE"]["ACTIVE"],
													                'OFFERS' => $arItemLabel['JS_OFFERS'],
													                'OFFER_SELECTED' => $arItemLabel['OFFERS_SELECTED'],
													                'TREE_PROPS' => $skuProps
													            );

													        }
													        else
													        {
													            
													            $jsParams = array(
													                'PRODUCT_TYPE' => $arItemLabel['CATALOG_TYPE'],
													                'TEMPLATE' => "FLAT",
													                'SHOW_QUANTITY' => ($showBtnBasketOption)?"Y":"",
													                'SHOW_ABSENT' => "Y",
													                'SHOW_OLD_PRICE' => true,
													                'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
													                'SHOW_DISCOUNT_PERCENT' => true,
													                'ADD2BASKET_SHOW' => $showBtnBasketOption,
													                'PRODUCT' => array(
													                    'ID' => $arItemLabel['ID'],
													                    'NAME' => $productTitle,
													                    'DETAIL_PAGE_URL' => $arItemLabel['DETAIL_PAGE_URL'],
													                    'PICT' => "",
													                    'CAN_BUY' => $arItemLabel['CAN_BUY'],
													                    'CHECK_QUANTITY' => $arItemLabel['CHECK_QUANTITY'],
													                    'MAX_QUANTITY' => $arItemLabel['CATALOG_QUANTITY'],
													                    'STEP_QUANTITY' => $arItemLabel['ITEM_MEASURE_RATIOS'][$arItemLabel['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'],
													                    'QUANTITY_FLOAT' => is_float($arItemLabel['ITEM_MEASURE_RATIOS'][$arItemLabel['ITEM_MEASURE_RATIO_SELECTED']]['RATIO']),
													                    'ITEM_PRICE_MODE' => $arItemLabel['ITEM_PRICE_MODE'],
													                    'ITEM_PRICES' => $arItemLabel['ITEM_PRICES'],
													                    'ITEM_PRICE_SELECTED' => $arItemLabel['ITEM_PRICE_SELECTED'],
													                    'ITEM_QUANTITY_RANGES' => $arItemLabel['ITEM_QUANTITY_RANGES'],
													                    'ITEM_QUANTITY_RANGE_SELECTED' => $arItemLabel['ITEM_QUANTITY_RANGE_SELECTED'],
													                    'ITEM_MEASURE_RATIOS' => $arItemLabel['ITEM_MEASURE_RATIOS'],
													                    'ITEM_MEASURE_RATIO_SELECTED' => $arItemLabel['ITEM_MEASURE_RATIO_SELECTED'],
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
													                'USE_COMPARE' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["ACTIVE"]["VALUE"]["ACTIVE"]
													                
													            );
													            
													        }

													    ?>

													    <script>
													        
													      var <?=$obName?> = new JCCatalogItem(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);
													    </script>

													</div>



													<?if($countItems != ($j+1)):?>

													    <?if($arResult["COLS"] == "4"):?>

													        <?if( ($j+1) % 4 == 0  ):?>
													            <span class="col-12 break-line visible-xxl visible-xl visible-lg">
							                                        <div></div>
							                                    </span>
													        <?endif;?>

													        <?if( ($j+1) % 3 == 0 ):?>
													            <span class="col-12 break-line visible-md">
							                                        <div></div>
							                                    </span>
													        <?endif;?>

													    <?endif;?>

													    <?if($arResult["COLS"] == "3"):?>

													        <?if( ($j+1) % 3 == 0  ):?>

													            <span class="col-12 break-line visible-xxl visible-xl visible-lg visible-md">
							                                        <div></div>
							                                    </span>
													        <?endif;?>

													    <?endif;?>

													    <?if( ($j+1) % 2 == 0 ):?>
													        <span class="col-12 break-line visible-sm visible-xs">
							                                    <div></div>
							                                </span>
													    <?endif;?>

													<?endif;?>

													<?$j++;?>

								        		<?endforeach;?>
				        					<?endif;?>
				        				</div>
				        			</div>
				        		</div>

	        				</div>
	        			</div>

	        			<?$i++;?>

	        		<?endforeach;?>

	        	</div>


				<div class="visible-sm visible-xs universal-mobile-arrows" itemscope itemtype="http://schema.org/ItemList">
					

					<?foreach($html_constructor_labels as $keyLabel => $arLabel):?>

						<div class="label-item">

							<div class="title-tab">
							    <?=$arLabel["NAME"]?> 
							    <div class='main-color'></div>
							</div>

							<div class="img-for-lazyload-parent">
								<img class="lazyload img-for-lazyload slider-start" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png" data-id="<?=$arLabel["ID"]?>">

								<div class="catalog-block">

									<div class="catalog-list catalog-list-slider FLAT SLIDER universal-slider parent-slider-item-js">

										<?if(!empty($arLabel["ITEMS"])):?>
											<?$countItems = count($arLabel["ITEMS"]);?>

											<?foreach ($arLabel["ITEMS"] as $keyItemLabel => $arItemLabel):?>

							        			<?

							        				$haveOffers = !empty($arItemLabel["OFFERS"]) && !empty($arItemLabel["SKU_PROPS"]);

							        				$skuProps = array();

								                    $measureRatio = $arItemLabel["FIRST_ITEM"]['PRICE']['MIN_QUANTITY'];
							                        $showDiscount = $arItemLabel["FIRST_ITEM"]['PRICE']['PERCENT'] > 0;


								                    $mainId = $arLabel["ID"]."_".$arItemLabel["ID"]."_slider_".$arItemLabel["ID"];
								                    $obName = 'ob_'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $mainId);

								                    $productTitle = isset($arItemLabel['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $arItemLabel['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] != ''
								                        ? $arItemLabel['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
								                        : $arItemLabel['NAME'];

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

							        			<div class="item catalog-item <?if($keyItemLabel!=0) echo 'noactive-slide-lazyload';?>" id="<?=$mainId?>" data-entity="item" itemprop="itemListElement" itemscope itemtype="http://schema.org/Product">

							        				<div class="item-inner">

													    <div class="wrapper-top">

													        <div class="wrapper-image row no-gutters align-items-center">

													            <a href="<?=$arItemLabel["DETAIL_PAGE_URL"]?>" class="d-block col" id="<?=$itemIds["DETAIL_URL_IMG"]?>">

													                <img class="img-fluid d-block mx-auto lazyload" id="<?=$itemIds["PICT"]?>" data-src="<?=$arItemLabel["FIRST_ITEM"]["PREVIEW_PICTURE_SRC"]?>" alt="<?=$arItemLabel["FIRST_ITEM"]["PREVIEW_PICTURE_DESC"]?>"/>
													                
													            </a>

													            <?if(!empty($arItemLabel["PROPERTIES"]["LABELS"]["VALUE_XML_ID"])):?>
													                <div class="wrapper-board-label">
													                    <?foreach($arItemLabel["PROPERTIES"]["LABELS"]["VALUE_XML_ID"] as $k=>$xml_id):?>
													                        <div class="mini-board <?=$xml_id?>" title="<?=$item["PROPERTIES"]["LABELS"]["VALUE"][$k]?>"><?=$arItemLabel["PROPERTIES"]["LABELS"]["VALUE"][$k]?></div>
													                    <?endforeach;?>
													                </div>
													            <?endif;?>
													            

													            <?if (!isset($arItemLabel['OFFER_WITHOUT_SKU'])):?>
													                <span id="<?=$itemIds['DSC_PERC']?>" class="sale <?=($haveOffers)?"hidden-md hidden-sm hidden-xs":"";?>" <?=($price["PERCENT"] > 0 ? '' : 'style="display: none;"')?>><?=-$price["PERCENT"]?>%</span>

													                
													                <?if( $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["DELAY_ON"]["VALUE"]["ACTIVE"] == "Y" 
													                  || $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["ACTIVE"]["VALUE"]["ACTIVE"] == "Y" ):?>

													                    <div class="wrapper-delay-compare-icons <?=($haveOffers)?"hidden-md hidden-sm hidden-xs":"";?>">

													                        <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["DELAY_ON"]["VALUE"]["ACTIVE"] == "Y"):?>
													                            <div title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_DELAY_TITLE"]?>" class="icon delay add2delay" id = "<?=$itemIds["DELAY"]?>" data-item="<?=$arItemLabel["ID"]?>"></div>
													                        <?endif;?>

													                        <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["ACTIVE"]["VALUE"]["ACTIVE"] == "Y"):?>
													                            <div title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_COMPARE_TITLE"]?>" class="icon compare add2compare" id = "<?=$itemIds["COMPARE"]?>" data-item="<?=$arItemLabel["ID"]?>"></div>
													                        <?endif;?>
													                    </div>
													                <?endif;?>

													            <?endif;?>

													            
													        </div>


													        <a href="<?=$arItemLabel["DETAIL_PAGE_URL"]?>" class="name-element">
													            <?=$arItemLabel["~NAME"]?>
													        </a>

													        <div <?if( $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['USE_PRICE_COUNT']['VALUE']["ACTIVE"] == 'Y' ):?>class="wrapper-board-price"<?endif;?>>


														        <?if($haveOffers):?>

														        	<div class="board-price row no-gutters" data-entity = "block-price" style='display: <?=($arItemLabel["FIRST_ITEM"]['MODE_ARCHIVE']=="Y" || $arItemLabel["FIRST_ITEM"]['PRICE']["PRICE"] == '-1') ? 'none' : ''?>;'>
														                <div class="actual-price">
														                    <span class="price-value" id="<?=$itemIds['PRICE_ID']?>"><?if($arItemLabel["DIFF"] > 0):?><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_PREFIX_FROM"]?><?endif;?> <?=$arItemLabel["MIN_PRICE"]["PRINT_DISCOUNT_VALUE"]?></span><span class="unit" id="<?=$itemIds['QUANTITY_MEASURE']?>" style='display: <?=(isset($arItemLabel['MEASURE_HTML']{0}) ? '' : 'none')?>;'><?=$arItemLabel['MEASURE_HTML']?></span>
														                </div>
														                <?if($arItemLabel["DIFF"] <= 0):?>
															                <div class="old-price align-self-end" id="<?=$itemIds['PRICE_OLD']?>">
													                            <?=$arItemLabel["MIN_PRICE"]["PRINT_VALUE"]?>
													                        </div>
												                        <?endif;?>
														            </div>


														        <?else:?>

													                <div class="board-price row no-gutters" data-entity = "block-price" style='display: <?=($arItemLabel["FIRST_ITEM"]['MODE_ARCHIVE']=="Y" || $arItemLabel["FIRST_ITEM"]['PRICE']["PRICE"] == '-1') ? 'none' : ''?>;'>
													                    <div class="actual-price">

													                        <div class="<?=($haveOffers)?"d-none d-lg-block":""?>">

													                            <span class="price-value" id="<?=$itemIds['PRICE_ID']?>"><?=$arItemLabel["FIRST_ITEM"]['PRICE']['PRINT_PRICE']?></span><span class="unit" id="<?=$itemIds['QUANTITY_MEASURE']?>" style='display: <?=(isset($arItemLabel["FIRST_ITEM"]['MEASURE_PRICE']{0}) ? '' : 'none')?>;'><?=$arItemLabel["FIRST_ITEM"]['MEASURE_PRICE']?></span>
													                        </div>

													                    </div>
													                    
												                        <div class="old-price align-self-end" id="<?=$itemIds['PRICE_OLD']?>"
												                            style="display: <?=($showDiscount ? '' : 'none')?>;">
												                            <?=($showDiscount ? $arItemLabel["FIRST_ITEM"]['PRICE']['PRINT_BASE_PRICE'] : '')?>
												                        </div>

													                </div>

														        <?endif;?>

													    	</div>


													        <?if( $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['USE_PRICE_COUNT']['VALUE']["ACTIVE"] == 'Y' ):?>
													                    
											                    <?if($haveOffers):?>

											                        <div class="wrapper-matrix-block col-12" id= "<?=$itemIds["PRICE_MATRIX"]?>"></div>

											                    <?else:?>

											                        <?=CPhoenix::showPriceMatrix($arItem, $arItemLabel['ITEM_MEASURE']['TITLE']);?>

											                    <?endif;?>
											        
											                <?endif;?>
													        
													    </div>


													    <div class="wrapper-bot part-hidden">


													        <?if($haveOffers || $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_VOTE"]["VALUE"]["ACTIVE"] == "Y"):?>

													                <div class="wrapper-list-info">

													                    <div class="<?=($haveOffers)? "d-none d-lg-block":""?>">

													                        <?
													                            if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_VOTE"]["VALUE"]["ACTIVE"] == "Y")
													                            {?>
													                                <?if($arLabel["RATING_VIEW"] == "simple"):?>
													                                                    
													                                    <?=CPhoenix::GetRatingVoteHTML(array("ID"=>$arItemLabel['ID'], "CLASS"=>"simple-rating hover"));?>

													                                <?elseif($arLabel["RATING_VIEW"] == "full"):?>

													                                    <?=CPhoenix::GetRatingVoteHTML(array("ID"=>$arItemLabel['ID'], "VIEW"=>"rating-reviewsCount", "HREF"=>$arItemLabel["DETAIL_PAGE_URL"]."#rating-block"));?>

													                                <?endif;?>

													                            <?}
													                        ?>

													                    </div>

													                    <?if($haveOffers):?>

													                        <div class="d-lg-none count-offers">
													                            <?=count($arItemLabel["OFFERS"])." ".CPhoenix::getTermination(count($arItemLabel["OFFERS"]), $PHOENIX_TEMPLATE_ARRAY["TERMINATIONS_OFFERS"])?>
													                        </div>

													                    <?endif;?>

													                </div>
													            

													        <?endif;?>

													        <?
													        	$showBtnBasket = $showBtnBasketOption;

													            if(!$arItemLabel["FIRST_ITEM"]["CAN_BUY"] || $arItemLabel["FIRST_ITEM"]["SHOWPREORDERBTN"] || $arItemLabel["FIRST_ITEM"]["MODE_DISALLOW_ORDER"] || $arItemLabel["FIRST_ITEM"]["MODE_ARCHIVE"])
													                $showBtnBasket = false;
													        ?>

													        

													        <div class="wrapper-inner-bot row no-gutters <?=($haveOffers)?"hidden-md hidden-sm hidden-xs":""?> <?=($showBtnBasket)?"":"d-none"?>" data-entity = "btns-quantity">

													            <div class="quantity-container row no-gutters align-items-center col-xl-6 quantity-block d-none" 
													                 data-entity="quantity-block" 
													                 data-item="<?=$arItemLabel['ID']?>" 
													                 style='display: <?=($arItemLabel['CAN_BUY'] ? '' : 'none')?>;'>

													                <table>
													                    <tr>
													                        <td class="btn-quantity"><span class="product-item-amount-field-btn-minus no-select" id="<?=$itemIds['QUANTITY_DOWN']?>">&minus;</span></td>
													                        <td><input class="product-item-amount-field" id="<?=$itemIds['QUANTITY']?>" type="number" name="<?=$arParams['PRODUCT_QUANTITY_VARIABLE']?>" value="<?=$measureRatio?>"></td>
													                        <td class="btn-quantity"><span class="product-item-amount-field-btn-plus no-select" id="<?=$itemIds['QUANTITY_UP']?>">&plus;</span></td>
													                    </tr>
													                </table>

													                <span class="d-none" id="<?=$itemIds['QUANTITY_MEASURE']?>"><?=$arItemLabel['ITEM_MEASURE']['TITLE']?></span>

													                <span class="d-none" id="<?=$itemIds['PRICE_TOTAL']?>"></span>
													            </div>

													            <div class="btn-container align-items-center col-xl-6 col-12" id="<?=$itemIds['BASKET_ACTIONS']?>">
													                <a
													                    id = "<?=$itemIds['ADD2BASKET']?>"
													                    href="javascript:void(0);"
													                    data-item = "<?=$arItemLabel["ID"]?>"

													                class="main-color add2basket bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADD_NAME"]["~VALUE"]?></a>

													                <a
													                    id = "<?=$itemIds['MOVE2BASKET']?>"
													                    href="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_URL"]["VALUE"]?>"
													                    data-item = "<?=$arItemLabel["ID"]?>"

													                class="move2basket"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADDED_NAME"]["~VALUE"]?></a>
													            </div>


													        </div>

													        <div class="wrapper-inner-bot row no-gutters 

													            <?if($showBtnBasket):?>
													                d-none
													            <?elseif($haveOffers):?>
													                d-none d-lg-block
													            <?endif;?>"

													            data-entity = "link-to-detail-page">

													            <div class="btn-container align-items-center col-12">
													                <a href="<?=$arItemLabel["DETAIL_PAGE_URL"]?>" class="main-color bold"><?=($haveOffers)?$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["LINK_2_DETAIL_PAGE_NAME_OFFER"]["VALUE"]:$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["LINK_2_DETAIL_PAGE_NAME"]["VALUE"]?></a>
													            </div>

													        </div>

													        <?if($haveOffers):?>
													        	<div class="d-lg-none">
													                <div class="wrapper-inner-bot row no-gutters">

													                    <div class="btn-container align-items-center col-12">
													                        <a href="<?=$arItemLabel["DETAIL_PAGE_URL"]?>"

													                        class="main-color bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["LINK_2_DETAIL_PAGE_NAME_OFFER_MOB"]["VALUE"]?></a>
													                    </div>

													                </div>
													            </div>
													        <?endif;?>

													    </div>


													</div>
												    

												    <?CPhoenix::admin_setting($arItemLabel, false)?>

												    <?

												        if ($haveOffers)
												        {
												            $jsParams = array(
												                'PRODUCT_TYPE' => $arItemLabel['CATALOG_TYPE'],
												                'TEMPLATE' => "FLAT",
												                'SHOW_QUANTITY' => ($showBtnBasketOption)?"Y":"",
												                'SHOW_ABSENT' => "Y",
												                'SHOW_OLD_PRICE' => "Y",
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
												                    'SHORT_DESCRIPTION' => $itemIds['SHORT_DESCRIPTION'],
												                    'PREVIEW_TEXT' => $itemIds['PREVIEW_TEXT'],
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
												                    'ID' => $arItemLabel['ID'],
												                    'NAME' => $productTitle,
												                    'DETAIL_PAGE_URL' => $arItemLabel['DETAIL_PAGE_URL'],
												                    'QUANTITY_FOR_MANY' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_MANY"]["VALUE"],
												                    'QUANTITY_FOR_MANY_IS_FLOAT' => CPhoenix::isStringFloat($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_MANY"]["VALUE"]),
												                    'QUANTITY_FOR_FEW' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_FEW"]["VALUE"],
												                    'QUANTITY_FOR_FEW_IS_FLOAT' => CPhoenix::isStringFloat($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_FEW"]["VALUE"]),

												                ),
												                'COMPARE_URL' => SITE_DIR.'catalog/compare/',
												                'USE_DELAY' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["DELAY_ON"]["VALUE"]["ACTIVE"],
												                'USE_COMPARE' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["ACTIVE"]["VALUE"]["ACTIVE"],
												                'OFFERS' => $arItemLabel['JS_OFFERS'],
												                'OFFER_SELECTED' => $arItemLabel['OFFERS_SELECTED'],
												                'TREE_PROPS' => $skuProps
												            );

												        }
												        else
												        {
												            
												            $jsParams = array(
												                'PRODUCT_TYPE' => $arItemLabel['CATALOG_TYPE'],
												                'TEMPLATE' => "FLAT",
												                'SHOW_QUANTITY' => ($showBtnBasketOption)?"Y":"",
												                'SHOW_ABSENT' => "Y",
												                'SHOW_OLD_PRICE' => true,
												                'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
												                'SHOW_DISCOUNT_PERCENT' => true,
												                'ADD2BASKET_SHOW' => $showBtnBasketOption,
												                'PRODUCT' => array(
												                    'ID' => $arItemLabel['ID'],
												                    'NAME' => $productTitle,
												                    'DETAIL_PAGE_URL' => $arItemLabel['DETAIL_PAGE_URL'],
												                    'PICT' => "",
												                    'CAN_BUY' => $arItemLabel['CAN_BUY'],
												                    'CHECK_QUANTITY' => $arItemLabel['CHECK_QUANTITY'],
												                    'MAX_QUANTITY' => $arItemLabel['CATALOG_QUANTITY'],
												                    'STEP_QUANTITY' => $arItemLabel['ITEM_MEASURE_RATIOS'][$arItemLabel['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'],
												                    'QUANTITY_FLOAT' => is_float($arItemLabel['ITEM_MEASURE_RATIOS'][$arItemLabel['ITEM_MEASURE_RATIO_SELECTED']]['RATIO']),
												                    'ITEM_PRICE_MODE' => $arItemLabel['ITEM_PRICE_MODE'],
												                    'ITEM_PRICES' => $arItemLabel['ITEM_PRICES'],
												                    'ITEM_PRICE_SELECTED' => $arItemLabel['ITEM_PRICE_SELECTED'],
												                    'ITEM_QUANTITY_RANGES' => $arItemLabel['ITEM_QUANTITY_RANGES'],
												                    'ITEM_QUANTITY_RANGE_SELECTED' => $arItemLabel['ITEM_QUANTITY_RANGE_SELECTED'],
												                    'ITEM_MEASURE_RATIOS' => $arItemLabel['ITEM_MEASURE_RATIOS'],
												                    'ITEM_MEASURE_RATIO_SELECTED' => $arItemLabel['ITEM_MEASURE_RATIO_SELECTED'],
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
												                'USE_COMPARE' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["ACTIVE"]["VALUE"]["ACTIVE"]
												                
												            );
												            
												        }

												    ?>

												    <script>
												        
												      var <?=$obName?> = new JCCatalogItem(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);
												    </script>

												</div>


							        		<?endforeach;?>

										<?endif;?>
									</div>
								</div>

								<img class="lazyload img-for-lazyload slider-finish" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png" data-id="<?=$arLabel["ID"]?>">

							</div>
						</div>

					<?endforeach;?>


				</div>

			</div>
		</div>


		</div>
	</div>

	<script>
	    BX.message({
	        PRICE_TOTAL_PREFIX: '<?=GetMessageJS('CT_CATALOG_MESS_PRICE_TOTAL_PREFIX')?>',
	        RELATIVE_QUANTITY_MANY: '<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_MANY"]["DESCRIPTION_2"]?>',
	        RELATIVE_QUANTITY_FEW: '<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_FEW"]["DESCRIPTION_2"]?>',
	        RELATIVE_QUANTITY: '<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_MANY"]["DESCRIPTION_NOEMPTY"]?>',
	        RELATIVE_QUANTITY_EMPTY: '<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_MANY"]["DESCRIPTION_EMPTY"]?>',
	        ARTICLE: '<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["ARTICLE_SHORT"]?>',
	    });
	      
	</script>
<?endif;?>
