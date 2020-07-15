<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Main;

/** @var array $arParams */
/** @var array $arItem */
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

$isFloatQuantityForMany = false;
$isFloatQuantityForFew = false;

if(strpos($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_MANY"]["VALUE"], ".") !== false)
    $isFloatQuantityForMany = true;

if(strpos($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_FEW"]["VALUE"], ".") !== false)
    $isFloatQuantityForFew = true;


?>



<?if( !empty($arResult["ITEMS"]) ):?>

    <?
    	$arResult["ITEMS_COUNT"] = intval($arResult["ITEMS_COUNT"]);

    	if($arResult["ITEMS_COUNT"] > 1)
    	{
    		$colHead = "col-xl-8 col-lg-7 col-sm-6";

		    if($arParams["SIDEMENU"])
		        $colHead = "col-xl-7 col-sm-6";
    	}
    	else
    		$colHead = "";

    	/*$this->SetViewTarget($arParams["OBJ_NAME"]);

    		echo "<div class=\"".$colHead." col-12\">";

    	$this->EndViewTarget();*/

        $showBuyBtn = $showBuyBtnOption= ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["FAST_ORDER_IN_PRODUCT_ON"]["VALUE"]["ACTIVE"] === "Y") ? 1 : 0;
		$showBtnBasket = $showBtnBasketOption =($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_ON"]["VALUE"]["ACTIVE"] === "Y" ) ? 1 : 0;

        $arResult['ZOOM_ON'] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['ZOOM_ON']['VALUE']['ACTIVE'] == "Y" ? 'zoom' : '';

        $countItems = count($arResult["ITEMS"]);
        $firstItem = array_shift($arResult["ITEMS"]);
        array_unshift($arResult["ITEMS"], $firstItem);


        $colsBlockPictures = "col-xl-8 col-lg-7 col-sm-6";
        $colsBlockInfo = "col-xl-4 col-lg-5 col-sm-6";

        if($arParams["SIDEMENU"])
        {
        	$colsBlockPictures = "col-xl-7 col-sm-6";
        	$colsBlockInfo = "col-xl-5 col-sm-6";
        }
    ?>

    <div class="img-for-lazyload-parent finish-bottom">
    	<div class="correct-cols-head" data-head-cols="<?=$colHead?>" data-head-target="<?=$arParams["OBJ_NAME"]?>"></div>

    	<img class="lazyload img-for-lazyload slider-start" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png" data-id="<?=$firstItem["ID"]?>">
    	

	    <div class="slider_catalog_big_items cart-info-block parent-slider-item-js slider-dots-style tone-<?=$arParams["CLR_TONE"]?> <?=($arParams["SIDEMENU"])? "min":""?>" data-count = "<?=$arResult["ITEMS_COUNT"]?>">
	       
	        <?foreach ($arResult['ITEMS'] as $keyItem => $arItem):?>



	            <div class="catalog-slide <?=($keyItem != 0) ? 'noactive-slide-lazyload' : ''?>">

	                <?
	                    $mainId = $this->GetEditAreaId($arItem['ID']).'_construct';
	                    $obName = 'ob'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $mainId);
	                ?>


	                <div id="<?=$mainId?>" class="catalog-item <?if($PHOENIX_TEMPLATE_ARRAY["IS_ADMIN"]):?>parent-tool-settings<?endif;?>">

	                    <?

	                        $haveOffers = !empty($arItem['OFFERS']) && !empty($arItem["SKU_PROPS"]);

	                        $skuProps = array();

	                        $measureRatio = $arItem["FIRST_ITEM"]['PRICE']['MIN_QUANTITY'];
	                        $showDiscount = $arItem["FIRST_ITEM"]['PRICE']['PERCENT'] > 0;

	                        $showSubscribe = ($arResult['PRODUCT']['SUBSCRIBE'] === 'Y' || $haveOffers);

	                        

	                        $name = !empty($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'])
	                            ? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
	                            : $arItem['NAME'];
	                        $title = !empty($arItem['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'])
	                            ? $arItem['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE']
	                            : $arItem['NAME'];
	                        $alt = !empty($arItem['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'])
	                            ? $arItem['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT']
	                            : $arItem['NAME'];


	                        $itemIds = array(
	                            'ID' => $mainId,
					            'TREE_ID' => $mainId.'_skudiv',
					            'OLD_PRICE_ID' => $mainId.'_old_price',
					            'PRICE_ID' => $mainId.'_price',
					            'QUANTITY_ID' => $mainId.'_quantity',
					            'QUANTITY_MEASURE' => $mainId.'_quant_measure',
					            'DISCOUNT_PRICE_ID' => $mainId.'_price_discount',
					            'PRICE_TOTAL' => $mainId.'_price_total',
					            'QUANTITY_DOWN_ID' => $mainId.'_quant_down',
					            'QUANTITY_UP_ID' => $mainId.'_quant_up',
					            'BIG_SLIDER_ID' => $mainId.'_big_slider',
					            'BIG_IMG_CONT_ID' => $mainId.'_bigimg_cont',
					            'SLIDER_CONT_ID' => $mainId.'_slider_cont',
					            'SLIDER_CONT_OF_ID' => $mainId.'_slider_cont_',
					            'DISCOUNT_PERCENT_ID' => $mainId.'_dsc_pict',
					            'ARTICLE_AVAILABLE' => $mainId.'_article_available',
					            'PREVIEW_TEXT' => $mainId.'_preview_text',
					            'COMPARE_LINK' => $mainId.'_compare_link',
					            'PREORDER' => $mainId.'_pre_order',
					            'SKU_CHARS' => $mainId.'_sku_chars',
					            'PRICE_MATRIX' => $mainId.'_price_matrix',
					            'BASKET_ACTIONS' => $mainId.'_basket_actions',
					            'ADD2BASKET' => $mainId.'_add2basket',
					            'MOVE2BASKET' => $mainId.'_move2basket',
					            'FAST_ORDER' => $mainId.'_fast_order',
					            'DELAY' => $mainId.'_delay',
					            'COMPARE' => $mainId.'_compare',
					            'OFFER_GROUP' => $mainId.'_set_group_',
					            'SUBSCRIBE_LINK' => $mainId.'_subscribe',
	                        );

	                        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['BETTER_PRICE']['VALUE'] != "N")
	                            $itemIds['CALL_FORM_BETTER_PRICE'] = $mainId.'_call_form_better_price';

	                        $previewTextPos = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['PREVIEW_TEXT_POSITION']['VALUE'];


							if( strlen($arItem["PROPERTIES"]["PREVIEW_TEXT_POSITION"]["VALUE_XML_ID"]) )
							    $previewTextPos = $arItem["PROPERTIES"]["PREVIEW_TEXT_POSITION"]["VALUE_XML_ID"];

							if( strlen($previewTextPos)<=0 )
							    $previewTextPos = "under_pic";

	                    ?>

	                    <div class="row">
	                
	                        
	                        <div class="<?=$colsBlockPictures?> col-12 info-left-side wrapper-delay-compare-icons-parent"> 

	                            <div class="wrapper-picture row" id="<?=$itemIds['BIG_SLIDER_ID']?>">
				                    <link itemprop="image" href="<?=$arItem["FIRST_ITEM"]["MORE_PHOTOS"][0]["BIG"]["SRC"]?>">

				                    <?
				                        $colLeft = "d-none";
				                        $colRight = "col-12";

				                        
				                        if( $arItem["FIRST_ITEM"]['MORE_PHOTOS_COUNT'] > 1 )
				                        {
				                            $colLeft = "col-2";
				                            $colRight = "col-10";
				                        }
				                    ?>

				                    <div class="wrapper-controls <?=$colLeft?> <?if(isset($arItem["PROPERTIES"]['VIDEO_POPUP']["VALUE"]{0})):?>video<?endif;?>">
				                        <div class="controls-pictures">

				                            <?if ( !empty($arItem['MORE_PHOTOS']) ):?>

				                                <?
				                                    $morePhotos = false;
				                                    $quantityPhoto = 0;

				                                ?>

				                                <?foreach ($arItem["FIRST_ITEM"]['MORE_PHOTOS'] as $key => $photo):?>

				                                    <?
				                                        if( ($key+1) > 3 ){
				                                            $morePhotos= true;
				                                            break;
				                                        }
				                                        $quantityPhoto++;
				                                    ?>

				                                    <div class="small-picture <?=($key == 0 ? 'active' : '')?>" data-value="<?=$photo['ID']?>">
				                                        <img class="lazyload" data-src="<?=$photo['SMALL']['SRC']?>" alt="<?=strip_tags($photo['DESC'])?>">
				                                    </div>


				                                <?endforeach;?>

				                               

				                                <?if($morePhotos):?>

				                                    <div class="more">

				                                        <a class="open-popup-gallery"
				                                            data-popup-gallery="<?=$arItem["FIRST_ITEM"]["ID"]?>_<?=$obName?>"
				                                        >
				                                        <?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["MORE"]?> <?echo (intval($arItem["FIRST_ITEM"]['MORE_PHOTOS_COUNT']) - $quantityPhoto);?></a>
				                                    </div>

				                                <?endif;?>


				                            <?endif;?>

				                        </div>

				                        <?if(strlen($arItem["PROPERTIES"]['VIDEO_POPUP']["VALUE"])):?>
				                            <?$iframe = CPhoenix::createVideo($arItem["PROPERTIES"]['VIDEO_POPUP']["VALUE"]);?>

				                            <a class="call-modal callvideo" data-call-modal="<?=$iframe["ID"]?>">
				                                <div class="video-play"></div>
				                                <div class="video-play-desc"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["VIDEO"]?></div>
				                            </a>
				                        <?endif;?>

				                    </div>


				                    <div class="outer-big-picture <?=$colRight?>">
				                        <div class="wrapper-big-picture">

				                            <?if ( !empty($arItem["FIRST_ITEM"]['MORE_PHOTOS']) ):?>
				                                <?foreach ($arItem["FIRST_ITEM"]['MORE_PHOTOS'] as $key => $photo):?>

				                                    <div class="big-picture <?=($key == 0 ? 'active' : '')?>"  data-id="<?=$photo['ID']?>">

				                                        <a 
				                                            class="cursor-loop open-popup-gallery d-block <?=$arItem['ZOOM_ON']?>"
				                                            data-popup-gallery="<?=$arItem["FIRST_ITEM"]["ID"]?>_<?=$obName?>"

				                                        >
				                                            <img <?/*src="<?=$photo['MIDDLE']['SRC']?>"*/?>
				                                                class="d-block mx-auto img-fluid open-popup-gallery-item lazyload"
				                                                data-src = "<?=$photo['BIG']['SRC']?>"
				                                                data-popup-gallery="<?=$arItem["FIRST_ITEM"]["ID"]?>_<?=$obName?>"
				                                                data-small-src = "<?=$photo['SMALL']['SRC']?>"
				                                                data-big-src = "<?=$photo['BIG']['SRC']?>"
				                                                data-desc = "<?=$photo['DESC']?>"
				                                                alt="<?=$photo['DESC']?>"
				                                                >
				                                        </a>

				                                    </div>

				                                <?endforeach;?>
				                            <?else:?>

				                                <div class="big-picture active">
				                                    <img data-src="<?=$no_photo_src?>" class="d-block mx-auto img-fluid lazyload" alt="">
				                                    <link itemprop="image" href="<?=$no_photo_src?>">  
				                                </div>

				                            <?endif;?>

				                        </div>

				                        <?if(!empty($arItem["PROPERTIES"]["LABELS"]["VALUE_XML_ID"])):?>
				                                                    
				                            <div class="wrapper-board-label">
				                                
				                                <?foreach($arItem["PROPERTIES"]["LABELS"]["VALUE_XML_ID"] as $k=>$xml_id):?>
				                                    <div class="mini-board <?=$xml_id?>" title="<?=$arItem["PROPERTIES"]["LABELS"]["VALUE"][$k]?>"><?=$arItem["PROPERTIES"]["LABELS"]["VALUE"][$k]?></div><br/>
				                                <?endforeach;?>

				                            </div>
				                        
				                        <?endif;?>

				                    </div>

				                </div>

	                            <div class="wr-top-part first visivle-sm visible-xs">

									<a href="<?=$arItem["~DETAIL_PAGE_URL"]?>" class="product-name bold" title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["BIG_SLIDER_CATALOG_NAME_TITLE"]?>">
										<?=$arItem["~NAME"]?>
									</a>

									
								</div>


	                            <?if($previewTextPos == "under_pic"):?>

	                            	<div id="<?=$itemIds["PREVIEW_TEXT"]?>">


	                                    <div class="wrapper-description under-pic-pos row no-margin">

	                                    	<div class="col-12 detail-description" data-entity="preview-text" itemprop="description"><?=$arResult["FIRST_ITEM"]["PREVIEW_TEXT"]?></div>



	                                        <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_VOTE"]["VALUE"]["ACTIVE"] == "Y"):?>

	                                            <div class="col-12 board-rating-reviews row no-gutters align-items-center">
	                                                
	                                                <?if($arResult["RATING_VIEW"] == "simple"):?>
                                                    
					                                    <?=CPhoenix::GetRatingVoteHTML(array("ID"=>$arItem['ID'], "CLASS"=>"simple-rating hover"));?>

					                                <?elseif($arResult["RATING_VIEW"] == "full"):?>

					                                    <?=CPhoenix::GetRatingVoteHTML(array("ID"=>$arItem['ID'], "VIEW"=>"rating-reviewsCount", "HREF"=>$arItem["DETAIL_PAGE_URL"]."#rating-block"));?>

					                                <?endif;?>
	                                            </div>
	                                        <?endif;?>

	                                        
	                                    </div>


                                    </div>
	                               

	                            <?endif;?>


	                            <?if( $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["DELAY_ON"]["VALUE"]["ACTIVE"] == "Y" 
	                                  || $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["ACTIVE"]["VALUE"]["ACTIVE"] == "Y" ):?>

	                                <div class="wrapper-delay-compare-icons">

	                                    <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["DELAY_ON"]["VALUE"]["ACTIVE"] == "Y"):?>
	                                        <div title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_DELAY_TITLE"]?>" class="icon delay add2delay" id = "<?=$itemIds["DELAY"]?>" data-item="<?=$arItem["ID"]?>"></div>
	                                    <?endif;?>


	                                    <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["ACTIVE"]["VALUE"]["ACTIVE"] == "Y"):?>
	                                        <div title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_COMPARE_TITLE"]?>" class="icon compare add2compare" id = "<?=$itemIds["COMPARE"]?>" data-item="<?=$arItem["ID"]?>"></div>
	                                    <?endif;?>
	                                
	                                </div>

	                            <?endif;?>
	                            
	                        </div>


	                        <div class="<?=$colsBlockInfo?> col-12 info-right-side" data-entity="parent-sku">  

	                            <div class="info-right-side-inner">
	                            	<div class="ghost-bl"></div>

	                            	<div class="wr-top-part second">

	                            		<div class="hidden-sm hidden-xs">
	
			                            	<a href="<?=$arItem["~DETAIL_PAGE_URL"]?>" class="product-name bold" title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["BIG_SLIDER_CATALOG_NAME_TITLE"]?>">
			                            		<?=$arItem["~NAME"]?>
			                            	</a>
			                            </div>

	                                	<div class="wrapper-article-available row no-gutters justify-content-between" id="<?=$itemIds['ARTICLE_AVAILABLE']?>">

					                        <?if(isset($arItem["FIRST_ITEM"]["ARTICLE"]{0})):?>

					                            <div class="detail-article italic"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["ARTICLE_SHORT"].$arItem["FIRST_ITEM"]["ARTICLE"]?></div>

					                            <div style="display: none;" itemprop="additionalProperty" itemscope="" itemtype="http://schema.org/PropertyValue">
					                                <meta itemprop="name" content="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["ARTICLE_SHORT"]?>">
					                                <meta itemprop="value" content="<?=addslashes($arItem["FIRST_ITEM"]["ARTICLE"])?>">
					                            </div>
					                        <?endif;?>

					                        <?=$arItem["FIRST_ITEM"]["QUANTITY_HTML"]?>
					                    </div>


	                                </div>

	                                <div class="wr-bot-part">


		                                <?if($previewTextPos == "right"):?>

		                                    <div class="wrapper-description right-pos" id="<?=$itemIds["PREVIEW_TEXT"]?>">
					                            <div class="detail-description" data-entity="preview-text" itemprop="description"><?=$arResult["FIRST_ITEM"]["PREVIEW_TEXT"]?></div>

						                      

		                                        <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_VOTE"]["VALUE"]["ACTIVE"] == "Y"):?>

		                                            <div class="board-rating-reviews row no-gutters align-items-center">
		                                                <?if($arResult["RATING_VIEW"] == "simple"):?>
                                                    
						                                    <?=CPhoenix::GetRatingVoteHTML(array("ID"=>$arItem['ID'], "CLASS"=>"simple-rating hover"));?>

						                                <?elseif($arResult["RATING_VIEW"] == "full"):?>

						                                    <?=CPhoenix::GetRatingVoteHTML(array("ID"=>$arItem['ID'], "VIEW"=>"rating-reviewsCount", "HREF"=>$arItem["DETAIL_PAGE_URL"]."#rating-block"));?>

						                                <?endif;?>
		                                            </div>

		                                        <?endif;?>
		                                    </div>

		                                <?endif;?>
		                                


		                                <div class="wrapper-price-sku-props">

		                                	<div id="actual_price" class="wrapper-price" data-entity = "block-price" style='display: <?=($arItem["FIRST_ITEM"]['MODE_ARCHIVE']=="Y" || $arResult["FIRST_ITEM"]['PRICE']["PRICE"] == '-1') ? 'none' : ''?>;'>

					                            <?
					                                $descForActualPrice = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["DESC_FOR_ACTUAL_PRICE"]["~VALUE"];


					                                if( strlen($arItem["PROPERTIES"]["TITLE_FOR_ACTUAL_PRICE"]["VALUE"]) )
					                                    $descForActualPrice = $arItem["PROPERTIES"]["TITLE_FOR_ACTUAL_PRICE"]["~VALUE"];

					                            ?>

					                            <?if( strlen($descForActualPrice) ):?>
					                                <div class="desc-title"><?=$descForActualPrice?></div>
					                            <?endif;?>

					                            <div class="board-price row no-gutters">
					                                <div class="actual-price">
					                                    <span class="price-value bold" id="<?=$itemIds['PRICE_ID']?>"><?=$arItem["FIRST_ITEM"]['PRICE']['PRINT_PRICE']?></span><span class="unit" id="<?=$itemIds['QUANTITY_MEASURE']?>" style='display: <?=(isset($arItem["FIRST_ITEM"]['MEASURE_PRICE']{0}) ? '' : 'none')?>;'><?=$arItem["FIRST_ITEM"]['MEASURE_PRICE']?></span>
					                                </div>

					                                <div class="old-price align-self-end" id="<?=$itemIds['OLD_PRICE_ID']?>" style="display: <?=($showDiscount ? '' : 'none')?>;"><?=($showDiscount ? $arItem["FIRST_ITEM"]['PRICE']['PRINT_BASE_PRICE'] : '')?></div>

					                            </div>


					                            <div class="wrapper-discount-cheaper row no-gutters align-items-center">

					                                <div id="<?=$itemIds['DISCOUNT_PRICE_ID']?>"  class="wrapper-discount" style="display: <?=($showDiscount ? '' : 'none')?>;">

					                                    <?if($showDiscount):?>

					                                        <span class="item-style desc-discount"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["ECONOMY"]?></span><span class="item-style actual-econom bold"><?=$arItem["FIRST_ITEM"]['PRICE']['PRINT_DISCOUNT']?></span>

					                                    <?endif;?>
					                                    
					                                    <span id="<?=$itemIds['DISCOUNT_PERCENT_ID']?>" class="item-style actual-discount" style="display: <?=($showDiscount ? '' : 'none')?>;">
					                                            <?=-$arItem["FIRST_ITEM"]['PRICE']['PERCENT']?>%
					                                        
					                                    </span>
					                                </div>
					                                
					                                <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['BETTER_PRICE']["VALUE"] != "N"):?>
					                                   <span class="cheaper">
					                                        <a id="<?=$itemIds['CALL_FORM_BETTER_PRICE']?>">
					                                            <span class="bord-bot"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FOUND_CHEAPER"]?></span>
					                                        </a>
					                                    </span>
					                                <?endif;?>

					                                
					                            </div>

					                            <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['USE_PRICE_COUNT']['VALUE']["ACTIVE"] == 'Y'):?>

					                                <?if($haveOffers):?>
					                                    <div class="wrapper-matrix-block" id= "<?=$itemIds["PRICE_MATRIX"]?>"></div>
					                                <?else:?>

					                                     <?=CPhoenix::showPriceMatrixInDetail($arItem, $arItem['ITEM_MEASURE']['TITLE']);?>

					                                <?endif;?>

					                            <?endif;?>


					                        </div>


					                        <?if( $haveOffers):?>


					                            <div id="<?=$itemIds['TREE_ID']?>" class="wrapper-skudiv">
					                                
					                                <?foreach ($arItem['SKU_PROPS'] as $skuProperty):?>
					                                    
					                                    <?
					                                        $propertyId = $skuProperty['ID'];
					                                        $skuProps[] = array(
					                                            'ID' => $propertyId,
					                                            'NAME' => $skuProperty['NAME'],
					                                            'VALUES' => $skuProperty['VALUES'],
					                                        );

					                                        $descTitle = "";

					                                        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUES"][$skuProperty["ID"]]["VALUE_2"] == 'pic')
					                                            $descTitle = "desc-title-with-prop-name";

					                                    ?>


					                                    <div class="wrapper-sku-props <?if( strlen($descTitle) ):?>with-desc<?endif;?> clearfix" data-entity="sku-line-block">

					                                        <div class="wrapper-title row no-gutters">
					                                            <div class="desc-title"><?=htmlspecialcharsEx($skuProperty['NAME'])?><span class="prop-name" <?if( strlen($descTitle) ):?>data-entity="<?=$descTitle?>"<?endif;?>></span> 

					                                                <?if(strlen($skuProperty["HINT"])):?>
					                                                    <i class="hint-sku fa fa-question-circle hidden-sm hidden-xs" data-toggle="sku-tooltip" data-placement="bottom" title="" data-original-title='<?=str_replace("'", "\"", $skuProperty["HINT"])?>'></i>
					                                                <?endif;?>
					                                            </div>
					                                            
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

					                                <?endforeach;?> 

					                            </div>

					                        <?endif;?>

		                                    


		                                    <?if( isset($arItem["MODAL_WINDOWS"]) && !empty($arItem["MODAL_WINDOWS"]) || isset($arItem["FORMS"]) && !empty($arItem["FORMS"]) || isset($arItem["QUIZS"]) && !empty($arItem["QUIZS"]) ):?>

		                                        <div class="wrapper-modals-btn row no-gutters">

		                                            <?if( isset($arItem["MODAL_WINDOWS"]) && !empty($arItem["MODAL_WINDOWS"])):?>

		                                                <?foreach ($arItem["MODAL_WINDOWS"] as $arModalWindow):?>

		                                                    <div class="modal-btn"><a class="call-modal callmodal" data-call-modal="modal<?=$arModalWindow["ID"]?>"><span class="bord-bot"><?=$arModalWindow["NAME"]?></span></a></div>

		                                                <?endforeach;?>

		                                            <?endif;?>

		                                            <?if( isset($arItem["FORMS"]) && !empty($arItem["FORMS"])):?>

		                                                <?foreach ($arItem["FORMS"] as $arForm):?>

		                                                    <div class="modal-btn"><a class="call-modal callform" data-call-modal="form<?=$arForm["ID"]?>"><span class="bord-bot"><?=$arForm["NAME"]?></span></a></div>

		                                                <?endforeach;?>

		                                            <?endif;?>

		                                            <?if( isset($arItem["QUIZS"]) && !empty($arItem["QUIZS"])):?>

		                                                <?foreach ($arItem["QUIZS"] as $arQuiz):?>

		                                                    <div class="modal-btn"><a class="call-wqec" data-wqec-section-id="<?=$arQuiz["ID"]?>"><span class="bord-bot"><?=$arQuiz["NAME"]?></span></a></div>

		                                                <?endforeach;?>

		                                            <?endif;?>

		                                        </div>


		                                    <?endif;?>
		                                    

		                                    <?
					                            $hideWrBtns = false;

					                            if(!$arItem["FIRST_ITEM"]["CAN_BUY"] || $arItem["FIRST_ITEM"]["SHOWPREORDERBTN"] || $arItem["FIRST_ITEM"]["MODE_DISALLOW_ORDER"] || $arItem["FIRST_ITEM"]["MODE_ARCHIVE"])
					                            {
					                                $showBtnBasket = false;
					                                $showBuyBtn = false;
					                            }

					                            if(!$showBtnBasket && !$showBuyBtn)
					                                $hideWrBtns = true;
					                        ?>

					                        <div class="wrapper-quantity quantity-block <?=($showBtnBasket)?"":"d-none"?>" data-item="<?=$arItem["FIRST_ITEM"]['ID']?>" data-entity="quantity-block">

					                            <div class="wrapper-title">

					                                <?
					                                    $titForQuantity = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["TIT_FOR_QUANTITY"]["~VALUE"];


					                                    if( strlen($arItem["PROPERTIES"]["TIT_FOR_QUANTITY"]["VALUE"]) )
					                                        $titForQuantity = $arItem["PROPERTIES"]["TIT_FOR_QUANTITY"]["~VALUE"];


					                                    $commForQuantity = "";
					                                    if( $arItem["PROPERTIES"]["COMMENT_HIDE"]["VALUE_XML_ID"] != "Y" )
					                                    {
					                                        $commForQuantity = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["COMMENT_FOR_QUANTITY"]["~VALUE"];


					                                        if( strlen($arItem["PROPERTIES"]["COMMENT_FOR_QUANTITY"]["VALUE"]) )
					                                            $commForQuantity = $arItem["PROPERTIES"]["COMMENT_FOR_QUANTITY"]["~VALUE"];
					                                    }
					                                    
					                                ?>

					                                <?if($titForQuantity):?>
					                                    <div class="desc-title">

					                                        <?=$titForQuantity?>

					                                        <?if( strlen($commForQuantity) ):?>
					                                            <?/*<div class="ic-hint hidden-sm hidden-xs" data-toggle="tooltip" data-placement="right" title="<?=$commForQuantity?>">?</div>*/?>

					                                            <i class="ic-hint fa fa-question-circle hidden-sm hidden-xs" data-toggle="tooltip" data-placement="right" title="<?=str_replace("'", "\"", $commForQuantity)?>"></i>
					                                        <?endif;?>
					                                        

					                                    </div>
					                                <?endif;?>

					                                
					                            </div>

					                         
					                            <div class="wrapper-quantity-total row no-gutters align-items-center">

					                                <div class="col-6">

					                                    <div class="quantity-container row no-gutters align-items-center justify-content-between">
					                                        <span class="product-item-amount-field-btn-minus" id="<?=$itemIds['QUANTITY_DOWN_ID']?>">&minus;</span>
					                                        <input class="product-item-amount-field" id="<?=$itemIds['QUANTITY_ID']?>" type="number"
					                                            value="<?=$measureRatio?>">
					                                        <span class="product-item-amount-field-btn-plus" id="<?=$itemIds['QUANTITY_UP_ID']?>">&plus;</span>
					                                    </div>
					                                </div>

					                                <div class="col-6">

					                                    <div class="total-container" id="<?=$itemIds['PRICE_TOTAL']?>">
					                                        <span class="desc-total"></span> <span class="total-value bold"><?/*=$price['PRINT_RATIO_PRICE']*/?></span>
					                                    </div>

					                                </div>

					                            </div>

					                        </div>


					                        <div class="wrapper-btns row no-gutters justify-content-between <?=($hideWrBtns)?"d-none":""?>" id="<?=$itemIds["BASKET_ACTIONS"]?>">

					                            <div class="col-6 left-btn <?=($showBtnBasket)?"":"d-none"?>" data-entity = "order">
					                                <a 
					                                id = "<?=$itemIds['ADD2BASKET']?>"
					                                href="javascript:void(0);" 
					                                title = "<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADD_NAME"]["~VALUE"]?>" 
					                                data-item="<?=$arItem['ID']?>"
					                                class="main-color bold add-to-cart-style add2basket" ><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADD_NAME"]["~VALUE"]?></a>

					                                <a 
					                                id = "<?=$itemIds['MOVE2BASKET']?>"
					                                href="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_URL"]["VALUE"]?>" 
					                                title = "<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADDED_NAME"]["~VALUE"]?>"
					                                data-item = "<?=$arItem["ID"]?>"

					                                style = "display: none;"

					                                class="bold added-to-cart-style move2basket"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADDED_NAME"]["~VALUE"]?></a>
					                            </div>

					                            <div class="col-6 right-btn <?=($showBuyBtn)?"":"d-none"?>" data-entity = "fast_order">
					                                <a 
					                                    id="<?=$itemIds['FAST_ORDER']?>"
					                                    title = "<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_FAST_ORDER_NAME_IN_CATALOG_DETAIL"]["VALUE"]?>" 
					                                    href="javascript:void(0);"
					                                    class="fast-order second-btn-style"
					                                >
					                                <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_FAST_ORDER_NAME_IN_CATALOG_DETAIL"]["~VALUE"]?>
					                                    
					                                </a>
					                            </div>

					                        </div>



					                        <div class="wr-preorder <?=($arItem["FIRST_ITEM"]["SHOWPREORDERBTN"])?"":"d-none"?>" >

					                            <a  id="<?=$itemIds['PREORDER']?>"
					                                title = "<?=strip_tags($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["MODE_PREORDER_BTN_NAME"]["~VALUE"])?>"
					                                class="btn-transpatent ic-info"
					                            ><span class="icon-load"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["MODE_PREORDER_BTN_NAME"]["~VALUE"]?></span></a>
					                        </div>

					                      

					                        <?
					                            if($showSubscribe)
					                            {
					                                $APPLICATION->IncludeComponent(
					                                    'bitrix:catalog.product.subscribe',
					                                    'main',
					                                    array(
					                                        'CUSTOM_SITE_ID' => isset($arParams['CUSTOM_SITE_ID']) ? $arParams['CUSTOM_SITE_ID'] : null,
					                                        'PRODUCT_ID' => $arItem['ID'],
					                                        'BUTTON_ID' => $itemIds['SUBSCRIBE_LINK'],
					                                        'BUTTON_CLASS' => 'btn-transpatent product-item-detail-buy-button',
					                                        'DEFAULT_DISPLAY' => !$arItem["FIRST_ITEM"]['CAN_BUY'],
					                                        'MESS_BTN_SUBSCRIBE' => "",
					                                    ),
					                                    $component,
					                                    array('HIDE_ICONS' => 'Y')
					                                );
					                          
					                            }
					                        ?>

					                        <?CPhoenix::createBtnHtml($arResult["BTN"])?>


					                        <?if( strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['COMMENT_FOR_DETAIL_CATALOG']['VALUE']) ):?>

					                            <div class="comment-detail-catalog"><span><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['COMMENT_FOR_DETAIL_CATALOG']['~VALUE']?></span></div>

					                        <?endif;?>
		                                        
		                                    

		                                </div>

	                                </div>

	                            </div>

	                           
	                        </div>
	                    </div>

	                    <?CPhoenix::admin_setting($arItem, true)?>
	                </div>

	                <?
	                    if ($haveOffers)
						{

						    $jsParams = array(
						        
						        'OB_NAME' => $obName,
						        'CONFIG' => array(
						        	'USE_URL_PARAM' => "N",
						            'USE_CATALOG' => $arItem['CATALOG'],
						            'SHOW_QUANTITY' => ($showBtnBasket)?"Y":"",
						            'OFFER_GROUP' => $arItem['OFFER_GROUP'],
						            'SHOW_PRICE' => true,
						            'SHOW_DISCOUNT_PERCENT' => true,
						            'SHOW_OLD_PRICE' => true,
						            'ALT' => $alt,
						            'TITLE' => $title,
						            'STORE_QUANTITY_ON' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_ON"]["VALUE"]["ACTIVE"],
						            'VIEW_STORE_QUANTITY' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_VIEW"]["VALUE"],
						            'ADD2BASKET_SHOW' => $showBtnBasketOption,
						            'FAST_ORDER_SHOW' => $showBuyBtnOption,
						            'USE_DELAY' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["DELAY_ON"]["VALUE"]["ACTIVE"],
						            'USE_COMPARE' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["ACTIVE"]["VALUE"]["ACTIVE"],
						            'USE_SUBSCRIBE' => $showSubscribe,

						        ),
						        'PRODUCT_TYPE' => $arItem['CATALOG_TYPE'],
						        'VISUAL' => $itemIds,
						        'PRODUCT' => array(
						            'ID' => $arItem['ID'],
						            'ACTIVE' => $arItem['ACTIVE'],
						            'NAME' => $arItem['~NAME'],
						            'ARTICLE' => $arItem["~ARTICLE"],
						            'CATEGORY' => $arItem['CATEGORY_PATH'],
						            'QUANTITY_FOR_MANY' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_MANY"]["VALUE"],
						            'QUANTITY_FOR_MANY_IS_FLOAT' => CPhoenix::isStringFloat($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_MANY"]["VALUE"]),
						            'QUANTITY_FOR_FEW' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_FEW"]["VALUE"],
						            'QUANTITY_FOR_FEW_IS_FLOAT' => CPhoenix::isStringFloat($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_FEW"]["VALUE"]),
						            'DETAIL_PAGE_URL' => $arItem["~DETAIL_PAGE_URL"],
						            'ZOOM' => $arItem['ZOOM_ON']
						            
						        ),
						        'NO_PHOTO_SRC' => $no_photo_src,
						        'OFFERS' => $arItem['JS_OFFERS'],
						        'OFFER_SELECTED' => $arItem['OFFERS_SELECTED'],
						        'TREE_PROPS' => $skuProps,
						        'COMPARE_URL' => SITE_DIR.'catalog/compare/',
						        'FORM_BETTER_PRICE_ID' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['BETTER_PRICE']['VALUE'],
						        'FORM_PREORDER_ID' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["MODE_PREORDER_FORM"]['VALUE']
						        
						    );

						}

						else
						{

						    $jsParams = array(
						        'OB_NAME' => $obName,
						        'CONFIG' => array(
						            'USE_CATALOG' => $arItem['CATALOG'],
						            'SHOW_QUANTITY' => ($showBtnBasket)?"Y":"",
						            'SHOW_PRICE' => true,
						            'SHOW_DISCOUNT_PERCENT' => true,
						            'SHOW_OLD_PRICE' => true,
						            'ALT' => $alt,
						            'TITLE' => $title,
						            'ADD2BASKET_SHOW' => $showBtnBasketOption,
						            'FAST_ORDER_SHOW' => $showBuyBtnOption,
						            'USE_DELAY' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["DELAY_ON"]["VALUE"]["ACTIVE"],
						            'USE_COMPARE' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["ACTIVE"]["VALUE"]["ACTIVE"],
						            'USE_SUBSCRIBE' => $showSubscribe,

						        ),
						        'PRODUCT_TYPE' => $arItem['CATALOG_TYPE'],
						        'VISUAL' => $itemIds,
						        'PRODUCT' => array(
						            'ID' => $arItem['ID'],
						            'ACTIVE' => $arItem['ACTIVE'],
						            'NAME' => $arItem['~NAME'],
						            'ARTICLE' => $arItem["~ARTICLE"],
						            'CATEGORY' => $arItem['CATEGORY_PATH'],
						            'CAN_BUY' => $arItem['CAN_BUY'],
						            'ITEM_PRICE_MODE' => $arItem['ITEM_PRICE_MODE'],
						            'ITEM_PRICES' => $arItem['ITEM_PRICES'],
						            'ITEM_PRICE_SELECTED' => $arItem['ITEM_PRICE_SELECTED'],
						            'ITEM_QUANTITY_RANGES' => $arItem['ITEM_QUANTITY_RANGES'],
						            'ITEM_QUANTITY_RANGE_SELECTED' => $arItem['ITEM_QUANTITY_RANGE_SELECTED'],
						            'ITEM_MEASURE_RATIOS' => $arItem['ITEM_MEASURE_RATIOS'],
						            'ITEM_MEASURE_RATIO_SELECTED' => $arItem['ITEM_MEASURE_RATIO_SELECTED'],
						            'CHECK_QUANTITY' => $arItem['CHECK_QUANTITY'],
						            'QUANTITY_FLOAT' => is_float($arItem['ITEM_MEASURE_RATIOS'][$arItem['ITEM_MEASURE_RATIO_SELECTED']]['RATIO']),
						            'MAX_QUANTITY' => $arItem['CATALOG_QUANTITY'],
						            'STEP_QUANTITY' => $arItem['ITEM_MEASURE_RATIOS'][$arItem['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'],
						            'DETAIL_PAGE_URL' => $arItem["~DETAIL_PAGE_URL"],
						            'PHOTO' => (!empty($arItem['MORE_PHOTOS']))? $arItem['MORE_PHOTOS'][0]["MIDDLE"]["SRC"] : "",
						            
						        ),
						        'COMPARE_URL' => SITE_DIR.'catalog/compare/',
						        'FORM_BETTER_PRICE_ID' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['BETTER_PRICE']['VALUE'],
						        'FORM_PREORDER_ID' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["MODE_PREORDER_FORM"]['VALUE']

						    );


						}
	                ?>
	                    

	                <script>
	                    var <?=$obName?> = new JCCatalogElement(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);
	                </script>

	            </div>

	        <?endforeach;?>

	    </div>

    	<img class="lazyload img-for-lazyload slider-finish" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png" data-id="<?=$firstItem["ID"]?>">


    </div>



    <script>
        BX.message({
            ECONOMY_INFO_MESSAGE: '<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["ECONOMY"]?>',
            PRICE_TOTAL_PREFIX: '<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PRICE_TOTAL_PREFIX"]?>',
            RELATIVE_QUANTITY_MANY: '<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_MANY"]["DESCRIPTION_2"]?>',
            RELATIVE_QUANTITY_FEW: '<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_FEW"]["DESCRIPTION_2"]?>',
            RELATIVE_QUANTITY: '<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_MANY"]["DESCRIPTION_NOEMPTY"]?>',
            RELATIVE_QUANTITY_EMPTY: '<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_MANY"]["DESCRIPTION_EMPTY"]?>',
            MORE_PHOTOS: '<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["MORE"]?>',
            SITE_ID: '<?=$component->getSiteId()?>',
            ARTICLE: '<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["ARTICLE_SHORT"]?>',
        });
      
    </script>



<?endif;?>