<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Loader;

?>

    <?if(isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BODY_BG"]["SRC"]{0}) || $KRAKEN_TEMPLATE_ARRAY["ITEMS"]["BODY_BG_CLR"]["VALUE"] !="transparent"):?>
        <style>
            body{
                <?if(isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BODY_BG"]["SRC"]{0})):?>background-image: url(<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BODY_BG"]["SRC"]?>);<?endif;?>
                background-attachment: <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BODY_BG_POS"]["VALUE"]?>;
                background-repeat: <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BODY_BG_REPEAT"]["VALUE"]?>;
                background-position: top center;
                background-color: <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BODY_BG_CLR"]["VALUE"]?>;
            }
        </style>
    <?endif;?>


    <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_ON"]["VALUE"]["ACTIVE"] == "Y"):?>
        <?
        $footer_style = "";

       

            if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_COLOR_BG"]['VALUE'])>0)
            {
                $arColor = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_COLOR_BG"]['VALUE'];
                $percent = 1;

                if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_COLOR_BG"]['VALUE'] == "transparent")
                    $footer_style .= 'background-color: transparent; ';

             
                else if(preg_match('/^\#/', $arColor))
                {
                    $arColor = CPhoenix::convertHex2rgb($arColor);
                    $arColor = implode(',',$arColor);

                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_OPACITY_BG"]['VALUE'])>0)
                    $percent = (100 - $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_OPACITY_BG"]['VALUE'])/100;

                
                    $footer_style .= ' background-color: rgba('.$arColor.', '.$percent.')";';
                }

                else
                    $footer_style .= 'background-color: '.$arColor.'; ';
            }
        ?>
    

        <footer class="tone-<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_TONE"]["VALUE"]?> <?if(!$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_BG"]["VALUE"] && !isset($footer_style{0})):?>default_bg<?endif;?> lazyload" <?if(strlen($footer_style)>0):?> style="<?=$footer_style?>" <?endif;?>

            <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_BG"]["VALUE"]):?>
                <?$footer_bg = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_BG"]["VALUE"], array('width'=>1600, 'height'=>1200), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);?>
                data-src="<?=$footer_bg['src']?>"
            <?endif;?>
        >

            <div class="shadow-tone"></div>

            <div class="container">

                <div class="container-top">
                    <div class="row">
                        
                        <div class="col-lg-3 col-sm-6 col-12 column-1">

                            <?=$PHOENIX_TEMPLATE_ARRAY["LOGOTYPE_HTML"]?>

                            <?if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["VALUE"])):?>
                                
                                <?foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["~VALUE"] as $keyPhone => $arPnone):?>
                                    <div class="contact-item"><div class="phone"><div class="phone-value"><?=$arPnone['name']?></div></div></div>
                                    <?//if($keyPhone >= 1) break;?>
                                <?endforeach;?>
                                
                            <?endif;?>

                            <?if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"])):?>
                                <div class="contact-item"><div class="email"><a href="mailto:<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"][0]['name']?>"><span class="bord-bot white"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"][0]['name']?></span></a></div>
                                </div>
                            <?endif;?>

                            <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['CALLBACK']['VALUE'] != "N" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['CALLBACK_SHOW']["VALUE"]["ACTIVE"] == "Y"):?>
                                <div class="button-wrap">
                                    <a class="button-def main-color <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]["VALUE"]?> call-modal callform" 
                                    data-header="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FOOTER_DATA_HEADER"]?>"
                                    data-call-modal="form<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['CALLBACK']['VALUE']?>"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["CALLBACK_NAME"]["VALUE"]?></a>
                                </div>
                            <?endif;?>
                            
                        </div>

                        <div class="col-lg-3 col-sm-6 col-12 column-2">
                            <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_DESC"]["VALUE"])>0):?>
                                <div class="footer-description-item"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_DESC"]["~VALUE"]?></div>
                            <?endif;?>

                            <?if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENT_FOOTER'])):?>
                                <div class="political">

                                    <?$boderB = ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_TONE"]["VALUE"]=="dark")?"white":""?>
                    
                                    <?foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENT_FOOTER'] as $arAgr):?>

                                        <div class="agreement-item">
                                            <a class="call-modal callagreement" data-call-modal="agreement<?=$arAgr['ID']?>"><span class="bord-bot <?=$boderB?>"><?=$arAgr['NAME']?></span></a>
                                        </div>  
                                        
                                    <?endforeach;?>
                                   
                                </div>
                            <?endif;?>

                            <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_COPYRIGHT_ON"]["VALUE"]["ACTIVE"] == "Y"):?>

                                <div class="copytright-item d-none d-md-block">

                                    <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_COPYRIGHT_TYPE"]["VALUE"] == "user"):?>

                                        <a<?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_COPYRIGHT_USER_URL"]["VALUE"])>0):?> href="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_COPYRIGHT_USER_URL"]["VALUE"]?>" target="_blank"<?endif;?> class="copyright">

                                        <table>
                                            <tr>
                                                <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_COPYRIGHT_USER_DESC"]["VALUE"])>0):?>

                                                    <td>

                                                        <span class="text"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_COPYRIGHT_USER_DESC"]["~VALUE"]?></span>

                                                    </td>

                                                <?endif;?>

                                                <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_COPYRIGHT_USER_PIC"]["VALUE"])>0):?>

                                                     <td>

                                                        <?$logotypeResize = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_COPYRIGHT_USER_PIC"]["VALUE"], array('width'=>100, 'height'=>40), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);?>
                                    
                                                        <img class="img-fluid lazyload" data-src="<?=$logotypeResize['src']?>" alt="logotype" />

                                                    </td>

                                                <?endif;?>

                                                
                                            </tr>
                                        </table>

                                        </a>

                                    <?else:?>

                                        <a href="http://marketplace.1c-bitrix.ru/solutions/concept.phoenix/" target="_blank" class="copyright">

                                            <table>
                                                <tr>
                                                    <td>

                                                        <img class="lazyload" data-src="<?=SITE_TEMPLATE_PATH?>/images/copyright_phoenix.png" alt="copyright">

                                                    <td>
                                                </tr>
                                            </table>

                                        </a>
                                    <?endif;?>

                                </div>

                            <?endif;?>


                        </div>

                        <div class="col-lg-3 col-sm-6 col-12 column-3 parent-tool-settings hidden-xs">
                            
                            <?
                                $APPLICATION->IncludeComponent(
                                    "concept:phoenix.menu",
                                    "footer",
                                    Array(
                                        "COMPONENT_TEMPLATE" => "footer",
                                        "COMPOSITE_FRAME_MODE" => "N",
                                        "CACHE_TIME" => "36000000",
                                        "CACHE_TYPE" => "A",
                                        "CACHE_USE_GROUPS" => "Y"
                                    )
                                );
                            ?>

                            <?if($PHOENIX_TEMPLATE_ARRAY["IS_ADMIN"] && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["MODE_FAST_EDIT"]['VALUE']["ACTIVE"] == 'Y'):?>

                                <div class="tool-settings">

                                    <a 

                                        href='/bitrix/admin/iblock_list_admin.php?IBLOCK_ID=<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER_MENU"]["IBLOCK_ID"]?>&type=<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER_MENU"]["IBLOCK_TYPE"]?>&lang=ru&find_section_section=0' 
                                        class="tool-settings <?if($center):?>in-center<?endif;?>"
                                        data-toggle="tooltip"
                                        target="_blank"
                                        data-placement="right"
                                        title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["EDIT"]?> &quot;<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FOOTER_MENU"]?>&quot;"
                                    >
                                            
                                    </a>

                                </div>

                            <?endif;?>
                        </div>

                        <div class="col-lg-3 col-sm-6 col-12 column-4">

                            <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["BANNER_1"]["VALUE"]) || strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["BANNER_2"]["VALUE"])):?>

                                <div class="banner-items">

                                    <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["BANNER_1"]["VALUE"])):?>
                                    
                                        <div class="banner-item">
                                            <?$banner1 = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["BANNER_1"]["VALUE"], array('width'=>350, 'height'=>130), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);?>

                                            <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["BANNER_1_URL"]["VALUE"])):?>
                                                <a href="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["BANNER_1_URL"]["VALUE"]?>" target="_blank">
                                            <?endif;?>

                                                <img class="img-fluid lazyload" data-src="<?=$banner1["src"]?>" alt="" />

                                            <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["BANNER_1_URL"]["VALUE"])):?>
                                                </a>
                                            <?endif;?>
                                        </div>

                                    <?endif;?>

                                    <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["BANNER_2"]["VALUE"])):?>
                                    
                                        <div class="banner-item">
                                            <?$banner2 = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["BANNER_2"]["VALUE"], array('width'=>350, 'height'=>130), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);?>

                                            <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["BANNER_2_URL"]["VALUE"])):?>
                                                <a href="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["BANNER_2_URL"]["VALUE"]?>" target="_blank">
                                            <?endif;?>

                                                <img class="img-fluid lazyload" data-src="<?=$banner2["src"]?>" alt="" />

                                            <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["BANNER_2_URL"]["VALUE"])):?>
                                                </a>
                                            <?endif;?>
                                        </div>

                                    <?endif;?>

                                </div>

                            <?endif;?>

                            <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["GROUP_POS"]["VALUE"]["FOOTER"] == 'Y')
                                echo CPhoenix::CreateSoc($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]);
                            ?>

                            <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["SUBSCRIPE"]["VALUE"]["ACTIVE"] == "Y" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["SECTIONS"]["VALUE"]["SUBSCRIBE"] == "Y"):?>


                                <?$APPLICATION->IncludeComponent(
                                    "bitrix:subscribe.form",
                                    "main",
                                    Array(
                                        "CACHE_TIME" => "3600",
                                        "CACHE_TYPE" => "A",
                                        "COMPOSITE_FRAME_MODE" => "N",
                                        "COMPOSITE_FRAME_TYPE" => "AUTO",
                                        "PAGE" => SITE_DIR."personal/subscribe/",
                                        "SHOW_HIDDEN" => "N",
                                        "USE_PERSONALIZATION" => "Y"
                                    )
                                );?>

                               

                            <?endif;?>
                        </div>

                    </div>
                </div>

                <div class="container-bottom row align-items-center">

                    <div class="text-item col-lg-6 col-12">
                        <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_INFO"]["~VALUE"]?>
                    </div>


                    <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["PAYMENT_PIC"]["VALUE"])):?>

                        <div class="icon-items col-lg-6 col-12">

                            <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["PAYMENT_URL"]["VALUE"])):?>
                                <a href="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["PAYMENT_URL"]["VALUE"]?>">
                            <?endif;?>

                            <img class="lazyload" data-src="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["PAYMENT_PIC"]["SETTINGS"]["SRC"]?>" alt="" title="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["PAYMENT_HINT"]["VALUE"]?>"/>

                            <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["PAYMENT_URL"]["VALUE"])):?>
                                </a>
                            <?endif;?>
                            
                        </div>

                    <?endif;?>
                          

                    
                </div>
            </div>
            
        </footer>

    <?endif;?>

    
    

    <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["WIDGET_FAST_CALL_ON"]["VALUE"]["ACTIVE"]):?>
        <div id="callphone-mob" class="callphone-wrap">
            <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["WIDGET_FAST_CALL_DESC"]["VALUE"])>0):?>
                <span class="callphone-desc"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["WIDGET_FAST_CALL_DESC"]["VALUE"]?></span>
            <?endif;?>
            <a class='callphone' href='tel:<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["WIDGET_FAST_CALL_NUM"]["VALUE"]?>'></a>
        </div>
    <?endif;?>

</div>


<?

if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_ON"]["VALUE"]["ACTIVE"] == "Y")
{
    $APPLICATION->IncludeFile(
        SITE_TEMPLATE_PATH."/include/cart.php",
        array(),
        array("MODE"=>"text")
    );
}
?>

<input type="hidden" id="shop-mode" value="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_ON"]["VALUE"]["ACTIVE"]?>">


<?$APPLICATION->IncludeComponent(
    "bitrix:system.auth.form", 
    "auth", 
    array(
        "COMPONENT_TEMPLATE" => "auth",
        "PROFILE_URL" => "/personal/",
        "SHOW_ERRORS" => "N",
        "COMPOSITE_FRAME_MODE" => "N"
    ),
    false
);?>

<?\Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("set-area");?>

    <?if($PHOENIX_TEMPLATE_ARRAY["IS_ADMIN"]):?>

        <?\Bitrix\Main\Data\StaticHtmlCache::getInstance()->markNonCacheable();?>

        <?$APPLICATION->IncludeFile(
            SITE_TEMPLATE_PATH."/include/alert_errors.php",
            array(),
            array("MODE"=>"text")
        );?>

        <?$APPLICATION->IncludeFile(
            SITE_TEMPLATE_PATH."/include/settings-panel.php",
            array(),
            array("MODE"=>"text")
        );?>
        
    <?endif;?>

<?\Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("set-area");?>


<a href="#body" class="up scroll"></a>

<div class="blueimp-gallery blueimp-gallery-controls" id="blueimp-gallery">
    <div class="slides"></div>
    <h3 class="title bold"></h3>
    <a class="prev"></a> 
    <a class="next"></a> 
    <a class="close"></a>
</div>

<div class="popup-slider" id="sliderPopup" data-current-image=''>
    
    <div class="gallery-container wrapper-picture">
        <div class="wrapper-big-picture"></div>
        <div class="controls-pictures"></div>
    </div>

    <a class="close-popup-slider-style" onclick = "destroyPopupGallery();"></a>
    <div class="popup-slider-nav">
        <div class="nav-item action_prev"></div>
        <div class="nav-item action_next"></div>
    </div>

</div>


<div class="shadow-detail"></div>
<div class="wrap-modal">

    <div class="modal-container"></div> 

</div>



<div class="modalArea shadow-modal-wind-contact"> 
    <div class="shadow-modal"></div>

    <div class="phoenix-modal window-modal">

        <div class="phoenix-modal-dialog">
            
            <div class="dialog-content">
                <a class="close-modal wind-close"></a>

                <div class="content-in">
                    <div class="list-contacts-modal">
                        <table>

                            <?$flagcallback = true;?>

                            <?foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["~VALUE"] as $key => $val):?>
                            
                                <tr>
                                    <td>
                                        <div class="phone"><span ><?=$val['name']?></span></div>
                                        <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["~VALUE"][$key]["desc"]) > 0):?>
                                            <div class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["~VALUE"][$key]["desc"]?></div>
                                        <?endif;?>
                                    </td>
                                </tr>

                                <?if($key == 0):?>

                                    <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['CALLBACK']['VALUE'] != "N" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['CALLBACK_SHOW']["VALUE"]["ACTIVE"] == "Y"):?>
                                        <tr class="no-border-top">
                                            <td>

                                                <div class="button-wrap">
                                                    <a class="button-def main-color d-block <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]["VALUE"]?> call-modal callform" data-from-open-modal='open-menu' data-header="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FOOTER_DATA_HEADER"]?>" data-call-modal="form<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['CALLBACK']['VALUE']?>"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["CALLBACK_NAME"]["VALUE"]?></a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?endif;?>

                                    <?$flagcallback = false;?>

                                <?endif;?>

                            <?endforeach;?>

                            <?if($flagcallback):?>

                                <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['CALLBACK']['VALUE'] != "N" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['CALLBACK_SHOW']["VALUE"]["ACTIVE"] == "Y"):?>
                                    <tr>
                                        <td>

                                            <div class="button-wrap">
                                                <a class="button-def main-color d-block <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]["VALUE"]?> call-modal callform" data-from-open-modal='open-menu' data-header="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FOOTER_DATA_HEADER"]?>" data-call-modal="form<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['CALLBACK']['VALUE']?>"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["CALLBACK_NAME"]["VALUE"]?></a>
                                            </div>
                                        </td>
                                    </tr>
                                <?endif;?>

                            <?endif;?>


                            <?foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"] as $key => $val):?>

                                <tr>
                                    <td>
                                        <div class="email"><a href="mailto:<?=$val['name']?>"><span class="bord-bot"><?=$val['name']?></span></a></div>
                                        <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"][$key]["desc"]) > 0):?>
                                            <div class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"][$key]["desc"]?></div>
                                        <?endif;?>
                                    </td>
                                </tr>

                            <?endforeach;?>

                            <?if( strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['DIALOG_MAP']['VALUE']) || strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['ADDRESS']['VALUE']) ):?>
                                <tr>
                                    <td>
                                        <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['ADDRESS']['VALUE'])):?>

                                            <div class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['ADDRESS']['VALUE']?></div>

                                        <?endif;?>

                                        <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['DIALOG_MAP']['VALUE'])):?>

                                            
                                            <a class="btn-map-ic show-dialog-map"><i class="concept-icon concept-location-5"></i> <span class="bord-bot"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CONTACTS_DIALOG_MAP_BTN_NAME"]?></span></a>
                                            

                                        <?endif;?>
                                    </td>
                                </tr>
                            <?endif;?>

                            <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["GROUP_POS"]["VALUE"]["CONTACT"] == 'Y' && $PHOENIX_TEMPLATE_ARRAY["DISJUNCTIO"]["SOC_GROUP"]["VALUE"]):?>
                                <tr>
                                    <td>
                                        <?CPhoenix::CreateSoc($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"])?>
                                    </td>
                                </tr>
                            
                            <?endif;?>

                            <?/*foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["~VALUE"] as $key => $val):?>
                            
                                <tr>
                                    <td>
                                        <div class="phone bold"><span ><?=$val['name']?></span></div>
                                        <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["~VALUE"][$key]["desc"]) > 0):?>
                                            <div class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["~VALUE"][$key]["desc"]?></div>
                                        <?endif;?>
                                    </td>
                                </tr>

                            <?endforeach;?>
                            <?foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"] as $key => $val):?>

                                <tr>
                                    <td>
                                        <div class="email"><a href="mailto:<?=$val['name']?>"><span class="bord-bot"><?=$val['name']?></span></a></div>
                                        <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"][$key]["desc"]) > 0):?>
                                            <div class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"][$key]["desc"]?></div>
                                        <?endif;?>
                                    </td>
                                </tr>


                            <?endforeach;?>
                            
                            <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["GROUP_POS"]["VALUE"]["CONTACT"] == 'Y' && $PHOENIX_TEMPLATE_ARRAY["DISJUNCTIO"]["SOC_GROUP"]["VALUE"]):?>
                                <tr>
                                    <td>
                                        <?CPhoenix::CreateSoc($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"])?>
                                    </td>
                                </tr>
                            
                            <?endif;?>

                            <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['CALLBACK']['VALUE'] != "N"):?>
                                <tr>
                                    <td>

                                        <div class="button-wrap">
                                            <a class="button-def main-color d-block <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]["VALUE"]?> call-modal callform from-modal" data-from-open-modal='open-menu' data-header="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["HEADER_DATA_HEADER"]?>" data-call-modal="form<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['CALLBACK']['VALUE']?>"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["CALLBACK_NAME"]["VALUE"]?></a>
                                        </div>
                                    </td>
                                </tr>
                            <?endif;*/?>

                                
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>


<input type="hidden" id="catalogIblockID" value ="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"]?>">


<?
    $arMask["rus"] = "+7 (999) 999-99-99";
    $arMask["ukr"] = "+380 (99) 999-99-99";
    $arMask["blr"] = "+375 (99) 999-99-99";
    $arMask["kz"] = "+7 (999) 999-99-99";
    $arMask["user"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['USER_MASK']["VALUE"];
?>
<input type="hidden" class="mask-phone" value ="<?=$arMask[$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['MAIN_MASK']["VALUE"]]?>">
<script type="text/javascript">
    initPhoneMask();
</script>



<?$APPLICATION->IncludeComponent("concept:phoenix.seo", "", Array());?>



<?for($i=1;$i<=10;$i++):?>
    <input type="hidden" id="custom-input-<?=$i?>" name="custom-input-<?=$i?>" value="">
<?endfor;?>


<div class="loading"></div>
<div class="loading-top-right circleG-area"><div class="circleG circleG_1"></div><div class="circleG circleG_2"></div><div class="circleG circleG_3"></div></div>



<?if(isset($PHOENIX_TEMPLATE_ARRAY["LAZY_SCRIPTS"]{0})):?>

    <script>

        $(window).on("load", function()
        {
            var timerService = setTimeout(function()
            {
                <?if(strlen($PHOENIX_TEMPLATE_ARRAY["LAZY_SCRIPTS"])):?>
                    $("body").append('<?=str_replace(array("/","'", "\r\n"), array("\/", '"', ""), $PHOENIX_TEMPLATE_ARRAY["LAZY_SCRIPTS"])?>');
                <?endif;?>
                

                clearTimeout(timerService);
            },  <?=intval($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["LAZY_SERVICE_TIME"]["VALUE"])?> * 1000);

        });
    </script>
<?endif;?>

<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["USE_RUB"]["VALUE"]["ACTIVE"]=="Y"):?>

    <script>
        $(window).on("load", function()
        {
            $("body").append("<link href=\"https://fonts.googleapis.com/css?family=PT+Sans+Caption&amp;display=swap&amp;subset=latin-ext\" type=\"text/css\" rel=\"stylesheet\">");
        });
        
    </script>

<?endif;?>

<?if(isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["LAZY_SCRIPTS"]["VALUE"]{0})):?>

    <script>

        $(window).on("load", function()
        {
            var timerService2 = setTimeout(function()
            {

                $("body").append('<?=str_replace(array("/","'", "\r\n"), array("\/", '"', ""), $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["LAZY_SCRIPTS"]["~VALUE"])?>');
            
                clearTimeout(timerService2);
            },  <?=intval($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["LAZY_SCRIPTS_TIME"]["VALUE"])?> * 1000);

        });
    </script>
<?endif;?>




<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_MIN_SUM']['VALUE']):?>
    <input type="hidden" id="min_sum" value="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_MIN_SUM']['VALUE']?>" />
<?endif;?>


<?\Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("css-js-area");?>
<?=(strlen($PHOENIX_TEMPLATE_ARRAY["SITE_CONTENT_JS"]))? "<script type='text/javascript' phx>".$PHOENIX_TEMPLATE_ARRAY["SITE_CONTENT_JS"]."</script>":"";?>
<?=(strlen($PHOENIX_TEMPLATE_ARRAY["SITE_CONTENT_CSS"]))? "<style>".$PHOENIX_TEMPLATE_ARRAY["SITE_CONTENT_CSS"]."</style>":"";?>
<?\Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("css-js-area");?>

<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_VOTE"]["VALUE"]["ACTIVE"] == "Y"):?>
    <div id="alert-vote" style="display: none;"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["RATE_VOTE_SUCCESS_ALERT"]?></div>
<?endif;?>

<input class="domen-url-for-cookie" type="hidden" value="<?=CPhoenixHost::getHostTranslit()?>">

<?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CUSTOMS"]["ITEMS"]['STYLES']['VALUE'])>0)
{?>
    <style type="text/css">
    <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CUSTOMS"]["ITEMS"]['STYLES']['~VALUE']?>
    </style>
    
<?}?>

<?
if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CUSTOMS"]["ITEMS"]['SCRIPTS']['VALUE'])>0)
{?>
    <script type='text/javascript'>
        <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CUSTOMS"]["ITEMS"]['SCRIPTS']['~VALUE'];?>
    </script>
<?}?>

<?
    if(isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["INCLOSEBODY"]['VALUE']{0}))
        echo $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["INCLOSEBODY"]['~VALUE'];

    $APPLICATION->ShowViewContent("service_close_body");
?>


</body>
</html>