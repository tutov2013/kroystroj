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
?>
<?
global $PHOENIX_TEMPLATE_ARRAY;
?>

<?if( !empty($arResult["ITEMS"]) ):?>

<?
$firstItem = array_shift($arResult["ITEMS"]);
array_unshift($arResult["ITEMS"], $firstItem);
?>
<div class="img-for-lazyload-parent">
	<img class="lazyload img-for-lazyload slider-start" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png" data-id="<?=$firstItem["ID"]?>">

    <?
        $showBtnBasketOption = ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_ON"]["VALUE"]["ACTIVE"] === "Y" ) ? 1 : 0;
        $countItems = count($arResult["ITEMS"]);
    ?>

    <div class="catalog-block">

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

        <div class="catalog-list catalog-list-slider FLAT SLIDER universal-slider parent-slider-item-js">

            <?foreach ($arResult['ITEMS'] as $keyItem => $arItem):?>

                <?

                    $haveOffers = !empty($arItem["OFFERS"]);



    				$skuProps = array();

                    $measureRatio = $arItem["FIRST_ITEM"]['PRICE']['MIN_QUANTITY'];
                    $showDiscount = $arItem["FIRST_ITEM"]['PRICE']['PERCENT'] > 0;
                    
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

                <div class="item catalog-item <?if($keyItem!=0) echo 'noactive-slide-lazyload';?>" id="<?=$mainId?>" data-entity="item">



                    <div class="item-inner item-board-right">

					    <div class="wrapper-top">

					        <div class="wrapper-image row no-gutters align-items-center">

					            <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="d-block col" id="<?=$itemIds["DETAIL_URL_IMG"]?>">

					                <img class="img-fluid d-block mx-auto lazyload" id="<?=$itemIds["PICT"]?>" data-src="<?=$arItem["FIRST_ITEM"]["PREVIEW_PICTURE_SRC"]?>" alt="<?=$arItem["FIRST_ITEM"]["PREVIEW_PICTURE_DESC"]?>"/>
					                
					            </a>

					            <?if(!empty($arItem["PROPERTIES"]["LABELS"]["VALUE_XML_ID"])):?>

					                <div class="wrapper-board-label">

					                    <?foreach($arItem["PROPERTIES"]["LABELS"]["VALUE_XML_ID"] as $k=>$xml_id):?>
					                        <div class="mini-board <?=$xml_id?>" title="<?=$item["PROPERTIES"]["LABELS"]["VALUE"][$k]?>"><?=$arItem["PROPERTIES"]["LABELS"]["VALUE"][$k]?></div>
					                    <?endforeach;?>

					                </div>

					            <?endif;?>
					            

					            <?if (!isset($arItem['OFFER_WITHOUT_SKU'])):?>
					                <span id="<?=$itemIds['DSC_PERC']?>" class="sale <?=($haveOffers)?"hidden-md hidden-sm hidden-xs":"";?>" <?=($arItem["FIRST_ITEM"]['PRICE']["PERCENT"] > 0 ? '' : 'style="display: none;"')?>><?=-$arItem["FIRST_ITEM"]['PRICE']["PERCENT"]?>%</span>

					                
					                <?if( $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["DELAY_ON"]["VALUE"]["ACTIVE"] == "Y" 
					                  || $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["ACTIVE"]["VALUE"]["ACTIVE"] == "Y" ):?>

					                    <div class="wrapper-delay-compare-icons <?=($haveOffers)?"hidden-md hidden-sm hidden-xs":"";?>">

					                        <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["DELAY_ON"]["VALUE"]["ACTIVE"] == "Y"):?>
					                            <div title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_DELAY_TITLE"]?>" class="icon delay add2delay" id = "<?=$itemIds["DELAY"]?>" data-item="<?=$arItem["ID"]?>"></div>
					                        <?endif;?>

					                        <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["ACTIVE"]["VALUE"]["ACTIVE"] == "Y"):?>
					                            <div title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_COMPARE_TITLE"]?>" class="icon compare add2compare" id = "<?=$itemIds["COMPARE"]?>" data-item="<?=$arItem["ID"]?>"></div>
					                        <?endif;?>
					                    </div>
					                <?endif;?>

					            <?endif;?>


					        </div>

					        <div class="wrapper-article-available row no-gutters d-none d-lg-flex">
					            <?=$arItem["FIRST_ITEM"]["QUANTITY_HTML"]?>
					            <div class="detail-article italic"><?=(isset($arItem["FIRST_ITEM"]["ARTICLE"]{0}))?$PHOENIX_TEMPLATE_ARRAY["MESS"]["ARTICLE_SHORT"].$arItem["FIRST_ITEM"]["ARTICLE"]:""?></div>
					        </div>

					        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="name-element" title="<?=strip_tags($arItem["~NAME"])?>">
					            <?=$arItem["~NAME"]?>
					        </a>

	        		        <div <?if( $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['USE_PRICE_COUNT']['VALUE']["ACTIVE"] == 'Y' ):?>class="wrapper-board-price"<?endif;?>>


	        			        <?if($haveOffers):?>

	        			        	<div class="board-price row no-gutters" data-entity = "block-price" style='display: <?=($arItem["FIRST_ITEM"]['MODE_ARCHIVE']=="Y" ? 'none' : '')?>;'>
	        			                <div class="actual-price">
	        			                    <span class="price-value" id="<?=$itemIds['PRICE_ID']?>"><?if($arItem["DIFF"] > 0):?><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_PREFIX_FROM"]?><?endif;?> <?=$arItem["MIN_PRICE"]["PRINT_DISCOUNT_VALUE"]?></span><span class="unit" id="<?=$itemIds['QUANTITY_MEASURE']?>" style='display: <?=(isset($arItem['MEASURE_HTML']{0}) ? '' : 'none')?>;'><?=$arItem['MEASURE_HTML']?></span>
	        			                </div>
	        			                <?if($arItem["DIFF"] <= 0 && $arItem["MIN_PRICE"]["DISCOUNT_DIFF"] > 0):?>
							                <div class="old-price align-self-end" id="<?=$itemIds['PRICE_OLD']?>">
					                            <?=$arItem["MIN_PRICE"]["PRINT_VALUE"]?>
					                        </div>
				                        <?endif;?>
	        			            </div>


	        			        <?else:?>

	        		                <div class="board-price row no-gutters" data-entity = "block-price" style='display: <?=($arItem["FIRST_ITEM"]['MODE_ARCHIVE']=="Y" ? 'none' : '')?>;'>
	        		                    <div class="actual-price">

	        		                        <div class="<?=($haveOffers)?"d-none d-lg-block":""?>">

	        		                            <span class="price-value" id="<?=$itemIds['PRICE_ID']?>"><?=$arItem["FIRST_ITEM"]['PRICE']['PRINT_PRICE']?></span><span class="unit" id="<?=$itemIds['QUANTITY_MEASURE']?>" style='display: <?=(isset($arItem["FIRST_ITEM"]['MEASURE_PRICE']{0}) ? '' : 'none')?>;'><?=$arItem["FIRST_ITEM"]['MEASURE_PRICE']?></span>
	        		                        </div>

	        		                    </div>
	        		                    
	        	                        <div class="old-price align-self-end" id="<?=$itemIds['PRICE_OLD']?>"
	        	                            style="display: <?=($showDiscount ? '' : 'none')?>;">
	        	                            <?=($showDiscount ? $arItem["FIRST_ITEM"]['PRICE']['PRINT_BASE_PRICE'] : '')?>
	        	                        </div>

	        		                </div>

	        			        <?endif;?>

	        		    	</div>


	        		        <?if( $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['USE_PRICE_COUNT']['VALUE']["ACTIVE"] == 'Y' ):?>
	        		                    
	                            <?if($haveOffers):?>

	                                <div class="wrapper-matrix-block col-12" id= "<?=$itemIds["PRICE_MATRIX"]?>"></div>

	                            <?else:?>

	                                <?=CPhoenix::showPriceMatrix($arItem, $arItem['ITEM_MEASURE']['TITLE']);?>

	                            <?endif;?>
	                
	                        <?endif;?>

					    </div>


					    <div class="wrapper-bot part-hidden">

					    	<div class="wrapper-list-info">

						        <?if(!empty($arItem["OFFERS"])):?>

			                        <div class="count-offers">
			                            <?=count($arItem["OFFERS"])." ".CPhoenix::getTermination(count($arItem["OFFERS"]), $PHOENIX_TEMPLATE_ARRAY["TERMINATIONS_OFFERS"])?>
			                        </div>

			                    <?else:?>

				                    <?
			                            if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_VOTE"]["VALUE"]["ACTIVE"] == "Y")
			                            {?>
			                                <?if($arResult["RATING_VIEW"] == "simple"):?>
			                                                    
			                                    <?=CPhoenix::GetRatingVoteHTML(array("ID"=>$arItem['ID'], "CLASS"=>"simple-rating hover"));?>

			                                <?elseif($arResult["RATING_VIEW"] == "full"):?>

			                                    <?=CPhoenix::GetRatingVoteHTML(array("ID"=>$arItem['ID'], "VIEW"=>"rating-reviewsCount", "HREF"=>$arItem["DETAIL_PAGE_URL"]."#rating-block"));?>

			                                <?endif;?>

			                            <?}
			                        ?>

			                    <?endif;?>

		                    </div>

		                    <?
					        	$showBtnBasket = $showBtnBasketOption;

					            if(!$arItem["FIRST_ITEM"]["CAN_BUY"] || $arItem["FIRST_ITEM"]["SHOWPREORDERBTN"] || $arItem["FIRST_ITEM"]["MODE_DISALLOW_ORDER"] || $arItem["FIRST_ITEM"]["MODE_ARCHIVE"] || $haveOffers)
					                $showBtnBasket = false;
					        ?>


		                    <div class="wrapper-inner-bot row no-gutters <?=($showBtnBasket)?"":"d-none"?>" data-entity = "btns-quantity">

					        	<div class="btn-container align-items-center col-md-6 col-12" id="<?=$itemIds['BASKET_ACTIONS']?>">
					                <a
					                    id = "<?=$itemIds['ADD2BASKET']?>"
					                    href="javascript:void(0);"
					                    data-item = "<?=$arItem["ID"]?>"

					                class="main-color add2basket bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADD_NAME"]["~VALUE"]?></a>

					                <a
					                    id = "<?=$itemIds['MOVE2BASKET']?>"
					                    href="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_URL"]["VALUE"]?>"
					                    data-item = "<?=$arItem["ID"]?>"

					                class="move2basket"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADDED_NAME"]["~VALUE"]?></a>
					            </div>

					            <div class="col-md-6 col-12">

					                <div class="quantity-container row no-gutters align-items-center justify-content-between hidden-sm hidden-xs"
					                     data-entity="quantity-block" 
					                     data-item="<?=$arItem['ID']?>" 
					                     style='display: <?=($arItem['CAN_BUY'] ? '' : 'none')?>;'>

											<span class="product-item-amount-field-btn-minus no-select" id="<?=$itemIds['QUANTITY_DOWN']?>">&minus;</span>

											<input class="product-item-amount-field" id="<?=$itemIds['QUANTITY']?>" type="number" value="<?=$measureRatio?>">
											<span class="product-item-amount-field-btn-plus no-select" id="<?=$itemIds['QUANTITY_UP']?>">&plus;</span>

											<span class="d-none" id="<?=$itemIds['QUANTITY_MEASURE']?>"><?=$arItem['ITEM_MEASURE']['TITLE']?></span>
											<span class="d-none" id="<?=$itemIds['PRICE_TOTAL']?>"></span>
									</div>
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
					                <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="main-color bold"><?=($haveOffers)?$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["LINK_2_DETAIL_PAGE_NAME_OFFER"]["VALUE"]:$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["LINK_2_DETAIL_PAGE_NAME"]["VALUE"]?></a>
					            </div>

					        </div>

					        <?if($haveOffers):?>
					        	<div class="d-lg-none">
					                <div class="wrapper-inner-bot row no-gutters">

					                    <div class="btn-container align-items-center col-12">
					                        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>"

					                        class="main-color bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["LINK_2_DETAIL_PAGE_NAME_OFFER_MOB"]["VALUE"]?></a>
					                    </div>

					                </div>
					            </div>
					        <?endif;?>
					        

					    </div>

					</div>

                    <?CPhoenix::admin_setting($arItem, false)?>

                    <?
	                    if ($haveOffers)
	                    {
	                        $jsParams = array(
	                            'PRODUCT_TYPE' => $arItem['CATALOG_TYPE'],
	                            'TEMPLATE' => $arResult["VIEW"],
	                            'SHOW_QUANTITY' => "Y",
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
	                            'SHOW_QUANTITY' => "Y",
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
	                            'SHOW_QUANTITY' => "Y",
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
                </div>

            <?endforeach;?>
          
        </div>

    </div>

	<img class="lazyload img-for-lazyload slider-finish" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png" data-id="<?=$firstItem["ID"]?>">
</div>


	<?(!isset($arParams["BTNS_ACTIVE"]) || $arParams["BTNS_ACTIVE"] == "") ?"Y":$arParams["BTNS_ACTIVE"];?>

    <?if($arParams["BTNS_ACTIVE"]=="Y"):?>

		<?
		$countItems = count( $arResult["ITEMS"] );

		$class_arrows = "";


		if($countItems>3)
		    $class_arrows .= " visible-xxl visible-xl visible-lg hidden-xs";

		if($countItems>2)
		    $class_arrows .= " visible-md visible-sm hidden-xs";
		?>

		<?if(isset($class_arrows{0})):?>
		    <script>

		        $(document).ready(function(){
		            $("#<?=$arParams["BLOCK_ID"]?>").find(".wr-arrows-slick").addClass('<?=$class_arrows?>').removeClass('d-none');
		        });
		        
		    </script>
		<?endif;?>

	<?endif;?>

<?endif;?>