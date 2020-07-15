<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="row align-items-center">

    <div class="col-lg-1 col-md-2 col-4 left-body">
        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="d-block" id="<?=$itemIds["DETAIL_URL_IMG"]?>">

            <?if($haveOffers):?>

                <img class="img-fluid d-block mx-auto" id="<?=$itemIds["PICT"]?>"/>

            <?else:?>

                <img class="img-fluid d-block mx-auto lazyload" id="<?=$itemIds["PICT"]?>" data-src="<?=$arItem["PREVIEW_PICTURE_SRC"]?>" alt="<?=$arItem["PREVIEW_PICTURE_DESC"]?>"/>


            <?endif;?>
        </a>
    </div>
    
    <div class="col-lg-5 col-md-6 col-8 center-left-body">
        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="name-element">
            <?=$arItem["~NAME"]?>
        </a>
        
        <?if(strlen($arItem["FIRST_ITEM"]["SHORT_DESCRIPTION"])):?>
            <div class="short-description">
                <?=$arItem["FIRST_ITEM"]["SHORT_DESCRIPTION"]?>
            </div>
        <?endif;?>

        

        <div class="wrapper-delay-compare-available row no-gutters align-items-center">


            <?if( ( !$haveOffers && !isset($arItem['OFFER_WITHOUT_SKU']) && $arItem['OFFER_WITHOUT_SKU'] != "Y")
            && ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["DELAY_ON"]["VALUE"]["ACTIVE"] == "Y" 
                      || $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["ACTIVE"]["VALUE"]["ACTIVE"] == "Y") ):?>
                

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
        
        
    </div>

    <div class="col-xl-3 col-lg-2 col-md-4 col-12 center-right-body">

        <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['USE_PRICE_COUNT']['VALUE']["ACTIVE"] == 'Y'):?>
       
            
            <div class="wrapper-board-price" data-entity = "block-price" style='display: <?=($arItem["FIRST_ITEM"]['MODE_ARCHIVE']=="Y" || $arItem["FIRST_ITEM"]['PRICE']["PRICE"] == '-1') ? 'none' : ''?>;'>

                <?if(isset($arItem['OFFER_WITHOUT_SKU']) && $arItem['OFFER_WITHOUT_SKU'] == "Y"):?>
                    <div class="board-price">
                        <div class="actual-price">
                            <span class="price-value" id="<?=$itemIds['PRICE']?>"><?if($arItem["DIFF"] > 0):?><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_PREFIX_FROM"]?><?endif;?><?=$arItem["MIN_PRICE"]["PRINT_DISCOUNT_VALUE"]?></span><span class="unit" id="<?=$itemIds['QUANTITY_MEASURE']?>" style='display: <?=(isset($arItem['MEASURE_HTML']{0}) ? '' : 'none')?>;'><?=$arItem['MEASURE_HTML']?></span>
                        </div>
                    </div>

                <?elseif($haveOffers || strlen($arItem["FIRST_ITEM"]['PRICE']['PRINT_PRICE'])):?>

                    <div class="board-price">

                        <div class="actual-price">
                            <span class="price-value" id="<?=$itemIds['PRICE']?>"><?=$arItem["FIRST_ITEM"]['PRICE']["PRINT_PRICE"]?></span><span class="unit" id="<?=$itemIds['QUANTITY_MEASURE']?>" style='display: <?=(isset($arItem['MEASURE_HTML']{0}) ? '' : 'none')?>;'><?=$arItem['MEASURE_HTML']?></span>
                        </div>

                        

                        <div class="old-price align-self-end" id="<?=$itemIds['PRICE_OLD']?>" 

                            style='display: 
                                <?
                                    if( $arItem["FIRST_ITEM"]['PRICE']['DISCOUNT'] <= 0 ) 
                                        echo 'none';
                                    else
                                    {
                                        echo '';
                                    }
                                ?>
                            ;'

                        >

                            <?=$arItem["FIRST_ITEM"]['PRICE']['PRINT_BASE_PRICE']?>
                                
                        </div>

                      
                    </div>

                <?endif;?>
         
         
            </div>

        <?else:?>

            <?if(isset($arItem['OFFER_WITHOUT_SKU']) && $arItem['OFFER_WITHOUT_SKU'] == "Y"):?>

                <div class="board-price" data-entity="block-price" style='display: <?=($arItem["FIRST_ITEM"]['MODE_ARCHIVE']=="Y" || $arItem["FIRST_ITEM"]['PRICE']["PRICE"] == '-1') ? 'none' : ''?>;'>
                    <div class="actual-price">
                        <span class="price-value" id="<?=$itemIds['PRICE']?>"><?if($arItem["DIFF"] > 0):?><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_PREFIX_FROM"]?><?endif;?><?=$arItem["MIN_PRICE"]["PRINT_DISCOUNT_VALUE"]?></span><span class="unit" id="<?=$itemIds['QUANTITY_MEASURE']?>" style='display: <?=(isset($arItem['MEASURE_HTML']{0}) ? '' : 'none')?>;'><?=$arItem['MEASURE_HTML']?></span>
                    </div>
                </div>


            <?elseif(isset($arItem['OFFER_WITHOUT_SKU']) || strlen($arItem["FIRST_ITEM"]['PRICE']['PRINT_PRICE'])):?>

                <div class="board-price" data-entity="block-price" style='display: <?=($arItem["FIRST_ITEM"]['MODE_ARCHIVE']=="Y" || $arItem["FIRST_ITEM"]['PRICE']["PRICE"] == '-1') ? 'none' : ''?>;'>
                    <div class="actual-price">
                        <span class="price-value" id="<?=$itemIds['PRICE']?>"><?=$arItem["FIRST_ITEM"]['PRICE']['PRINT_PRICE']?></span><span class="unit" id="<?=$itemIds['QUANTITY_MEASURE']?>" style='display: <?=(isset($arItem['MEASURE_HTML']{0}) ? '' : 'none')?>;'><?=$arItem['MEASURE_HTML']?></span>
                    </div>

                    <?if(!isset($arItem['OFFER_WITHOUT_SKU'])):?>

                        <div class="old-price align-self-end" id="<?=$itemIds['PRICE_OLD']?>" 

                            style='display: 
                                <?
                                    if( $arItem["FIRST_ITEM"]['PRICE']['DISCOUNT'] <= 0 ) 
                                        echo 'none';
                                    else
                                    {
                                        echo '';
                                    }
                                ?>
                            ;'

                        >

                            <?=$arItem["FIRST_ITEM"]['PRICE']['PRINT_BASE_PRICE']?>
                                
                        </div>

                    <?endif;?>
                </div>

            <?endif;?>

        <?endif;?>

    </div>

    <div class="col-xl-3 col-lg-4 col-md-6 col-12 offset-lg-0 offset-md-3 right-body">

        <?
            if(!$arItem["FIRST_ITEM"]["CAN_BUY"] || $arItem["FIRST_ITEM"]["SHOWPREORDERBTN"] || $arItem["FIRST_ITEM"]["MODE_DISALLOW_ORDER"] || $arItem["FIRST_ITEM"]["MODE_ARCHIVE"] || isset($arItem['OFFER_WITHOUT_SKU']))
                $showBtnBasket = false;
        ?>
        

        <?if ( $showBtnBasket ):?>
                <div class="wrapper-inner-bot row no-gutters" >
                    

                    <div class="quantity-container col-8 quantity-block"
                    data-entity="quantity-block" 
                    style='display: <?=($arItem['CAN_BUY'] ? '' : 'none')?>;'
                    data-item="<?=$arItem['ID']?>" 
                    >

                        <div class="inner-quantity-container row no-gutters align-items-center">

                            <table>
                                <tr>
                                    <td class="btn-quantity"><span class="product-item-amount-field-btn-minus no-select" id="<?=$itemIds['QUANTITY_DOWN']?>">&minus;</span></td>
                                    <td><input class="product-item-amount-field" id="<?=$itemIds['QUANTITY']?>" type="number" name="<?=$arParams['PRODUCT_QUANTITY_VARIABLE']?>" value="<?=$measureRatio?>"></td>
                                    <td class="btn-quantity"><span class="product-item-amount-field-btn-plus no-select" id="<?=$itemIds['QUANTITY_UP']?>">&plus;</span></td>
                                </tr>
                            </table>
                        </div>

                        <span class="d-none" id="<?=$itemIds['QUANTITY_MEASURE']?>"><?=$arItem['ITEM_MEASURE']['TITLE']?></span>

                        <span class="d-none" id="<?=$itemIds['PRICE_TOTAL']?>"></span>
                    </div>


                    <?if($showBtnBasket):?>

                        <div class="btn-container align-items-center col-4" id="<?=$itemIds['BASKET_ACTIONS']?>">
                            <a
                                id = "<?=$itemIds['ADD2BASKET']?>"
                                href="javascript:void(0);"
                                data-item = "<?=$arItem["ID"]?>"

                            class="main-color add2basket" title="<?=strip_tags($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADD_NAME"]["~VALUE"])?>"><?/*=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADD_NAME"]["~VALUE"]*/?></a>

                            <a
                                id = "<?=$itemIds['MOVE2BASKET']?>"
                                href="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_URL"]["VALUE"]?>"
                                data-item = "<?=$arItem["ID"]?>"

                            class="move2basket"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADDED_NAME"]["~VALUE"]?></a>
                        </div>

                    <?endif;?>


                </div>

        <?else:?>

            <div class="wrapper-inner-bot row no-gutters">

                <div class="btn-container align-items-center col-12">
                    <a href="<?=$arItem["DETAIL_PAGE_URL"]?>"

                    class="main-color"><?=(isset($arItem['OFFER_WITHOUT_SKU']))? $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["LINK_2_DETAIL_PAGE_NAME_OFFER"]["VALUE"] : $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["LINK_2_DETAIL_PAGE_NAME"]["VALUE"]?></a>
                </div>

            </div>

        <?endif;?>

        
    </div>

    <?CPhoenix::admin_setting($arItem, false)?>
</div> 
