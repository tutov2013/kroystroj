<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


<div id="<?=$itemIds['SKU_TREE']?>q" class="item-inner">

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



        <a style="font-weight: bold" href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="name-element">
            <?=$arItem["~NAME"]?>
        </a>

        <?if(isset($arItem['OFFER_WITHOUT_SKU']) && $arItem['OFFER_WITHOUT_SKU'] == "Y"):?>

            <div class="board-price row no-gutters" data-entity = "block-price" style='display: <?=($arItem["FIRST_ITEM"]['MODE_ARCHIVE']=="Y" || $arItem["FIRST_ITEM"]['PRICE']["PRICE"] == '-1') ? 'none' : ''?>;'>
                <div class="actual-price">
                    <span style="font-weight: bold" class="price-value" id="<?=$itemIds['PRICE_ID']?>"><?if($arItem["DIFF"] > 0):?><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_PREFIX_FROM"]?><?endif;?> <?=$arItem["MIN_PRICE"]["PRINT_DISCOUNT_VALUE"]?></span><span class="unit" id="<?=$itemIds['QUANTITY_MEASURE']?>" style='display: <?=(isset($arItem['MEASURE_HTML']{0}) ? '' : 'none')?>;'><?=$arItem['MEASURE_HTML']?></span>
                </div>
            </div>


        <?else:?>

            <div data-entity = "block-price" <?if( $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['USE_PRICE_COUNT']['VALUE']["ACTIVE"] == 'Y' ):?>class="wrapper-board-price"<?endif;?> style='display: <?=($arItem["FIRST_ITEM"]['MODE_ARCHIVE']=="Y" || $arItem["FIRST_ITEM"]['PRICE']["PRICE"] == '-1') ? 'none' : ''?>;'>

                <div class="board-price row no-gutters">
                    <div class="actual-price">

                        <div class="<?=($haveOffers)?"d-none d-lg-block":""?>">

                            <span style="font-weight: bold"  class="price-value" id="<?=$itemIds['PRICE_ID']?>"><?=$arItem["FIRST_ITEM"]['PRICE']['PRINT_PRICE']?></span><span class="unit" id="<?=$itemIds['QUANTITY_MEASURE']?>" style='display: <?=(isset($arItem["FIRST_ITEM"]['MEASURE_PRICE']{0}) ? '' : 'none')?>;'><?=$arItem["FIRST_ITEM"]['MEASURE_PRICE']?></span>
                        </div>

                        <script>
                            $(document).ready(function() {
                                $('#currentcvet<?=$itemIds['SKU_TREE']?>').text($('#<?=$itemIds['SKU_TREE']?> .detail-color.active').attr('title'));
                                $('#<?=$itemIds['SKU_TREE']?>q').click(function() {
                                    $('#currentcvet<?=$itemIds['SKU_TREE']?>').text($('#<?=$itemIds['SKU_TREE']?> .detail-color.active').attr('title'));

                                })

                            });

                        </script>

                        <?if($haveOffers):?>
                            <div class="d-lg-none">
                                <span style="font-weight: bold"  class="price-value"><?if($arItem["DIFF"] > 0):?><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_PREFIX_FROM"]?><?endif;?> <?=$arItem["MIN_PRICE"]["PRINT_DISCOUNT_VALUE"]?></span><span class="unit" id="<?=$itemIds['QUANTITY_MEASURE']?>" style='display: <?=(isset($arItem['MEASURE_HTML']{0}) ? '' : 'none')?>;'><?=$arItem['MEASURE_HTML']?></span>
                            </div>
                        <?endif;?>

                    </div>

                    <?if(!isset($arItem['OFFER_WITHOUT_SKU'])):?>
                        <div class="old-price align-self-end <?if($haveOffers):?>d-none d-lg-block<?endif;?>" id="<?=$itemIds['PRICE_OLD']?>"
                            style="display: <?=($showDiscount ? '' : 'none')?>;">
                            <?=($showDiscount ? $arItem["FIRST_ITEM"]['PRICE']['PRINT_BASE_PRICE'] : '')?>
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


        <?endif;?>



    </div>


    <div class="wrapper-bot part-hidden">


        <?if(   $haveOffers
                || strlen($arItem["FIRST_ITEM"]["SHORT_DESCRIPTION"])
                || strlen($arItem["FIRST_ITEM"]["PREVIEW_TEXT"])
                || $arItem["CHARS"]["COUNT"]>0
                || $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_VOTE"]["VALUE"]["ACTIVE"] == "Y"

            ):?>

                <div class="wrapper-list-info">

                    <div class="d-none d-lg-block">

                        <?if($haveOffers):?>

                            <div id="<?=$itemIds['SKU_TREE']?>" class="wrapper-skudiv">

                                <?if(!empty($arItem["SKU_PROPS"])):?>

                                    <?foreach ($arItem["SKU_PROPS"] as $skuProperty):?>

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
                <?if (preg_match("/Цвет/",htmlspecialcharsEx($skuProperty['NAME']))): ?>
                                                <div class="wrapper-title row no-gutters">
                                                    <div class="desc-title"><?=htmlspecialcharsEx($skuProperty['NAME'])?><span class="prop-name" <?if( strlen($descTitle) ):?>data-entity="<?=$descTitle?>"<?endif;?>></span>: <span style="font-size: 11px;font-weight: 700;color:black" id="currentcvet<?=$itemIds['SKU_TREE']?>"></span> </div>

                                                </div>
                <?else:?>
                    <div class="wrapper-title row no-gutters">
                        <div class="desc-title"><?=htmlspecialcharsEx($skuProperty['NAME'])?><span class="prop-name" <?if( strlen($descTitle) ):?>data-entity="<?=$descTitle?>"<?endif;?>></span> </div>

                    </div>
                                                    <?endif;?>
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


                                                            <li style="font-weight: bold" title='<?=str_replace("'", "\"", $value['NAME'])?>' class="detail-color"

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

                                                            <li style="font-weight: bold" class="area-for-current-value"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SKU_SELECT_TITLE"]?></li>

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
                                                                <li style="font-weight: bold;font-size: 12px;" title='<?=str_replace("'", "\"", $value['NAME'])?>' class="detail-text"

                                                                    data-treevalue="<?=$propertyId?>_<?=$value['ID']?>"
                                                                    data-onevalue="<?=$value['ID']?>"

                                                                ><?=$value['NAME']?></li>
                                                            <?endforeach;?>
                                                        <span style="" id="vzyattext"><?=str_replace("'", "\"", $value['NAME'])?>
                                                        <?endif;?>
                                                    </ul>


                                                <?endif;?>


                                            </div>

                                        </div>

                                    <?endforeach;?>

                                <?endif;?>

                            </div>

                        <?endif;?>

                        <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]["PROPS_IN_LIST_FOR_".$arResult["VIEW"]]["VALUE"]["DESCRIPTION"] == "Y"):?>

                            <div class="short-description" id="<?=$itemIds["SHORT_DESCRIPTION"]?>"><?=$arItem["FIRST_ITEM"]["SHORT_DESCRIPTION"]?></div>

                        <?endif;?>


                        <?if( $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]["PROPS_IN_LIST_FOR_".$arResult["VIEW"]]["VALUE"]["PREVIEW_TEXT"] == "Y" ):?>

                            <div class="preview-text" id="<?=$itemIds["PREVIEW_TEXT"]?>"><?=$arItem["FIRST_ITEM"]["PREVIEW_TEXT"]?></div>

                        <?endif;?>

                    </div>

                    <div class="<?=($haveOffers)? "d-none d-lg-block":""?>">

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

                    </div>

                    <?if($haveOffers):?>

                        <div class="d-lg-none count-offers">
                            <?=count($arItem["OFFERS"])." ".CPhoenix::getTermination(count($arItem["OFFERS"]), $PHOENIX_TEMPLATE_ARRAY["TERMINATIONS_OFFERS"])?>
                        </div>

                    <?endif;?>

                    <div class="d-none d-lg-block">

                        <?if(!empty($arItem["CHARS_SORT"])):?>

                            <?$countChars = 0;?>

                            <div class="wrapper-characteristics">

                                <div class="characteristics show-hidden-parent">

                                    <?foreach ($arItem["CHARS_SORT"] as $keyChar => $valueChar):?>

                                        <?if($keyChar == "sku_chars"):?>

                                            <div class="sku-chars" id = "<?=$itemIds["SKU_CHARS"]?>"></div>

                                        <?elseif($keyChar == "props_chars"):?>

                                            <?if(!empty($arItem["PROPS_CHARS"])):?>

                                                <?foreach($arItem["PROPS_CHARS"] as $key=>$value):?>

                                                    <div class="characteristics-item show-hidden-child <?if($countChars >= 5):?>hidden<?endif;?>">

                                                        <span class="characteristics-item-name bold"><?=$value["NAME"]?>:</span>&nbsp;<span class="characteristics-item-value"><?=$value["VALUE"]?></span>

                                                    </div>

                                                    <?$countChars++?>

                                                <?endforeach;?>

                                            <?endif;?>



                                        <?elseif($keyChar == "prop_chars"):?>

                                            <?if(!empty($arItem["PROP_CHARS"])):?>

                                                <?foreach($arItem["PROP_CHARS"] as $key=>$value):?>

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


            if(!$arItem["FIRST_ITEM"]["CAN_BUY"] || $arItem["FIRST_ITEM"]["SHOWPREORDERBTN"] || $arItem["FIRST_ITEM"]["MODE_DISALLOW_ORDER"] || $arItem["FIRST_ITEM"]["MODE_ARCHIVE"])
                $showBtnBasket = false;
        ?>



        <div class="wrapper-inner-bot row no-gutters <?=($haveOffers)?"hidden-md hidden-sm hidden-xs":""?> <?=($showBtnBasket)?"":"d-none"?>" data-entity = "btns-quantity">

            <div class="quantity-container row no-gutters align-items-center col-xl-6 quantity-block d-none d-xl-flex"
                 data-entity="quantity-block"
                 data-item="<?=$arItem['ID']?>"
                 style='display: <?=($arItem['CAN_BUY'] ? '' : 'none')?>;'>

                <table>
                    <tr>
                        <td class="btn-quantity"><span class="product-item-amount-field-btn-minus no-select" id="<?=$itemIds['QUANTITY_DOWN']?>">&minus;</span></td>
                        <td><input class="product-item-amount-field" id="<?=$itemIds['QUANTITY']?>" type="number" name="<?=$arParams['PRODUCT_QUANTITY_VARIABLE']?>" value="<?=$measureRatio?>"></td>
                        <td class="btn-quantity"><span class="product-item-amount-field-btn-plus no-select" id="<?=$itemIds['QUANTITY_UP']?>">&plus;</span></td>
                    </tr>
                </table>

                <span class="d-none" id="<?=$itemIds['QUANTITY_MEASURE']?>"><?=$arItem['ITEM_MEASURE']['TITLE']?></span>

                <span class="d-none" id="<?=$itemIds['PRICE_TOTAL']?>"></span>
            </div>

            <div class="btn-container align-items-center col-xl-6 col-12" id="<?=$itemIds['BASKET_ACTIONS']?>">
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