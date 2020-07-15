<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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


<?if(!empty($arResult)):?>
    <?
        $terminations = Array();

        $terminations[] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_CNT_1"];
        $terminations[] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_CNT_2"];
        $terminations[] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_CNT_3"];
        $terminations[] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_CNT_4"];



        
        $styleBg = '';


        if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_BG_COLOR"]['VALUE']) >= 4)
        {

            $arColor = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_BG_COLOR"]['VALUE'];
            $percent = 1;

            if($arColor != '#')
            {

                if(preg_match('/^\#/', $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_BG_COLOR"]['VALUE']))
                {
                    $arColor = CPhoenix::convertHex2rgb($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_BG_COLOR"]['VALUE']);
                    $arColor = implode(',',$arColor);
                }

                if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_BG_OPACITY"]['VALUE'])>0)
                    $percent = (100 - $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_BG_OPACITY"]['VALUE'])/100;
                

                if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_TYPE"]['VALUE'] == "on_board")
                    $styleBg= 'style="background-color: rgba('.$arColor.', '.$percent.');"';

                if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_TYPE"]['VALUE'] == "on_line")
                    $styleBg= 'style="border-bottom: 2px solid rgba('.$arColor.', '.$percent.');"';
            }
            
        }
    ?>


    <div 

        class=
        "
            wrap-main-menu 
            active 
            <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_TEXT_COLOR"]['VALUE']?> 
            <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["DROPDOWN_MENU_WIDTH"]['VALUE']?>

        "

        <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_VIEW"]['VALUE'] == "full"):?> 

            <?=$styleBg?>

        <?endif;?>

    >
        <div class="container <?=($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["DROPDOWN_MENU_WIDTH"]['VALUE'] == "full")?"pos-static":""?>">

            <div class="main-menu-inner parent-tool-settings"

                <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_VIEW"]['VALUE'] == "content"):?> 
                    <?=$styleBg?>
                <?endif;?>
            >


                <?if($PHOENIX_TEMPLATE_ARRAY["IS_ADMIN"] && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["MODE_FAST_EDIT"]['VALUE']["ACTIVE"] == 'Y'):?>

                    <div class="tool-settings">

                        <a 
                            href='/bitrix/admin/iblock_list_admin.php?IBLOCK_ID=<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["IBLOCK_ID"]?>&type=<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["IBLOCK_TYPE"]?>&lang=ru&find_section_section=0' 
                            class="tool-settings <?if($center):?>in-center<?endif;?>"
                            data-toggle="tooltip"
                            target="_blank"
                            data-placement="right"
                            title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["EDIT"]?> &quot;<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["NAME"]?>&quot;"
                        >
                                
                        </a>

                    </div>

                <?endif;?>



                <table class="main-menu-board">
                    <tr>

                        <?if( $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['SEARCH']["ITEMS"]['ACTIVE']['VALUE']['ACTIVE'] == "Y" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['SEARCH']["ITEMS"]['SHOW_IN']['VALUE']['IN_MENU'] == "Y" ):?>

                            <td class="wrapper-search">
                                <div class="mini-search-style open-search-top"></div>
                            </td>
                        <?endif;?>


                        <td class="wrapper-menu">
                            
                            <nav class="main-menu">

                                <?foreach($arResult as $arItem):?>

                                    <?

                                        $colorText = '';
                                        $icon = '';

                                        if(strlen($arItem['MENU_COLOR'])>0)
                                            $colorText = ' style="color: '.$arItem['MENU_COLOR'].';"';
                                        

                                        if($arItem['MENU_IC_US']>0)
                                        {
                                            $iconResize = CFile::ResizeImageGet($arItem['MENU_IC_US'], array('width'=>15, 'height'=>15), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                                            $icon = '<img class="img-fluid img-icon lazyload" data-src="'.$iconResize['src'].'" alt="icon" />';
                                        }

                                        elseif(strlen($arItem['MENU_ICON'])>0)
                                            $icon = '<i class="concept-icon '.$arItem['MENU_ICON'].'"></i>';
                                        
                                    ?>


                                    <li class=

                                        "
                                            lvl1 
                                            <?=$arItem["VIEW"]?>
                                            <?if($arItem["SELECTED"]):?>selected<?endif;?>
                                            <?if($arItem["ACCENTED"]):?><?endif;?>
                                            <?if(strlen($arItem["ID"])):?>section-menu-id-<?=$arItem["ID"]?><?endif;?>
                                            <?if(!empty($arItem["SUB"])):?>parent<?endif;?>
                                        ">


                                        <a 

                                            <?if($arItem['NOLINK']):?>

                                                <?=CPhoenix::phoenixMenuAttr($arItem, $arItem['TYPE'])?>

                                            <?else:?>

                                                <?if(strlen($arItem["LINK"]) > 0  && !$arItem["NONE"]):?> 

                                                    href='<?=$arItem['LINK']?>'


                                                    <?if($arItem['BLANK']):?>

                                                        target='_blank'

                                                    <?endif;?>

                                                <?endif;?>

                                            <?endif;?>

                                            class=
                                            "

                                                <?if(strlen($arItem["LINK"]) <= 0 && $arItem["NONE"]):?>
                                                    empty-link
                                                <?endif;?>
                                            
                                                <?if($arItem['NOLINK']):?>
                                                    <?=CPhoenix::phoenixMenuClass($arItem, $arItem['TYPE'])?>
                                                <?endif;?>

                                                

                                            " 

                                            <?=$colorText?> 

                                        >

                                            <span class="wrap-name">
                                                <span class="

                                                    <?/*if($arItem["ACCENTED"]):?>
                                                        bold
                                                    <?endif;*/?>

                                                ">
                                                    <?=$icon?><?=$arItem['NAME']?>
                                                    <div class="bord"></div>
                                                </span>
                                            </span>

                                        </a>



                                        <?if(!empty($arItem["SUB"])):?>

                                            <?if($arItem["VIEW"] == "view_1"):?>

                                                <ul class="child">

                                                    <li class="wrap-shadow"></li>

                                                    <?if(!empty($arItem["SUB"])):?>

                                                        <?foreach($arItem["SUB"] as $arMenuChild):?>

                                                            <li class="

                                                                <?if($arMenuChild["SELECTED"]):?>selected<?endif;?>

                                                                <?if(strlen($arMenuChild["ID"])):?>

                                                                    section-menu-id-<?=$arMenuChild["ID"]?>

                                                                <?endif;?>

                                                                <?if(!empty($arMenuChild['SUB'])):?>parent2<?endif;?>

                                                            ">

                                                                <a 

                                                                    <?if($arMenuChild['NOLINK']):?>

                                                                        <?=CPhoenix::phoenixMenuAttr($arMenuChild, $arMenuChild['TYPE'])?>

                                                                    <?else:?>

                                                                        <?if(strlen($arMenuChild["LINK"]) > 0 && !$arMenuChild["NONE"]):?> 

                                                                            href='<?=$arMenuChild['LINK']?>'

                                                                            <?if($arMenuChild['BLANK']):?>

                                                                                target='_blank'

                                                                            <?endif;?>

                                                                        <?endif;?>

                                                                    <?endif;?>


                                                                    class=
                                                                    "

                                                                        <?if(strlen($arMenuChild["LINK"]) <= 0  && $arMenuChild["NONE"]):?>empty-link<?endif;?>

                                                                        

                                                                        <?if($arMenuChild['NOLINK']):?>

                                                                            <?=CPhoenix::phoenixMenuClass($arMenuChild, $arMenuChild['TYPE'])?>

                                                                        <?endif;?>

                                                                    "
                                                                >
                                                                    <?=$arMenuChild['NAME']?><div></div> <span class="act"></span>

                                                                </a> 

                                                               

                                                                <?if( !empty($arMenuChild['SUB']) ):?>
                                                                
                                                                    <ul class="child2">

                                                                        <li class="wrap-shadow"></li>

                                                                        <?foreach($arMenuChild['SUB'] as $keyChild2 => $arMenuChild2):?>

                                                                            <li class="

                                                                                <?if($arMenuChild2["SELECTED"]):?>selected<?endif;?>

                                                                                <?if(strlen($arMenuChild2["ID"])):?>

                                                                                    section-menu-id-<?=$arMenuChild2["ID"]?>

                                                                                <?endif;?>
                                                                            ">

                                                                                <a 
                                                                                    

                                                                                    <?if($arMenuChild2['NOLINK']):?>

                                                                                        <?=CPhoenix::phoenixMenuAttr ($arMenuChild2, $arMenuChild2['TYPE'])?>

                                                                                    <?else:?>

                                                                                        <?if(strlen($arMenuChild2["LINK"]) > 0 && !$arMenuChild2["NONE"]):?> 
                                                                                        
                                                                                            href='<?=$arMenuChild2['LINK']?>'

                                                                                            <?if($arMenuChild2['BLANK']):?>

                                                                                                target='_blank'

                                                                                            <?endif;?>

                                                                                        <?endif;?>

                                                                                    <?endif;?>

                                                                                    class=
                                                                                    "
                                                                                        <?if(strlen($arMenuChild2["LINK"]) <= 0 && $arMenuChild2["NONE"]):?>empty-link<?endif;?>

                                                                                        

                                                                                        <?if($arMenuChild2['NOLINK']):?>

                                                                                            <?=CPhoenix::phoenixMenuClass($arMenuChild2, $arMenuChild2['TYPE'])?>

                                                                                        <?endif;?>
                                                                                    "
                                                                                >

                                                                                    <?=$arMenuChild2['NAME']?>
                                                                                    <div></div>
                                                                                    <span class="act"></span>

                                                                                </a>
                                                                            </li>


                                                                        <?endforeach;?>
                                                             
                                                                    </ul>

                                                                <?endif;?>

                                                            </li>

                                                        <?endforeach;?>

                                                    <?endif;?>

                                                </ul>



                                            <?elseif($arItem["VIEW"] == "view_2"):?>


                                                <div class="dropdown-menu-view-2">

                                                    <div class="container">

                                                        <div class="inner">
                                                            <div class="row">   

                                                                <?$count = 0;?>
                                                                <?foreach($arItem["SUB"] as $arMenuChild):?>

                                                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                   
                                                                        <table class="item">
                                                                            <tr>
                                                                                <td class="left">
                                                                                
                                                                                    <?if(isset($arMenuChild["PICTURE_SRC"])):?>
                                                                                        
                                                                                        <img data-src="<?=$arMenuChild["PICTURE_SRC"]?>" class="lazyload img-fluid" alt="">
                                                                                        
                                                                                    <?else:?>
                                                                                    
                                                                                        <span></span>
                                                                                        
                                                                                    <?endif;?>
                                                         
                                                                                </td>
                                                                                
                                                                                <td class="right">

                                                                                    <a class="name 

                                                                                        <?if(strlen($arMenuChild["ID"])):?>

                                                                                            section-menu-id-<?=$arMenuChild["ID"]?>

                                                                                        <?endif;?>

                                                                                        <?if($arMenuChild["SELECTED"]):?>selected<?endif;?>

                                                                                    " <?if(isset($arMenuChild["LINK"]{0})):?>href="<?=$arMenuChild["LINK"]?>"<?endif;?> title="<?=$arMenuChild["NAME"]?>">
                                                                                        <?=$arMenuChild['NAME']?>
                                                                                            
                                                                                    </a>

                                                                                    <?if($arItem["TYPE"] == 'catalog' && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["SUBSECTIONS_HIDE_COUNT"]["VALUE"]["ACTIVE"] != "Y"):?>

                                                                                        <?if($arMenuChild["ELEMENT_CNT"]!="0"):?>
                                                                                        
                                                                                            <div class="count-sect-elem"><?=$arMenuChild['ELEMENT_CNT']?> <?=CPhoenix::getTermination($arMenuChild["ELEMENT_CNT"], $terminations)?></div>
                                                                                        <?endif;?>

                                                                                    <?endif;?>

                                                                                    <?if( !empty($arMenuChild['SUB']) ):?>

                                                                                        <ul class="lvl2">

                                                                                            <?
                                                                                                $j = 1;

                                                                                                $breakCount = intval($arItem["MAX_QUANTITY_SECTION_SHOW"]);
                                                                                            ?>

                                                                                            <?foreach($arMenuChild['SUB'] as $arMenuChild2):?>

                                                                                                <?if($j > $breakCount) break;?>

                                                                                                <li class=
                                                                                                "
                                                                                                    <?if($arMenuChild2['SELECTED']):?>selected<?endif;?>

                                                                                                    <?if(strlen($arMenuChild2["ID"])):?>

                                                                                                        section-menu-id-<?=$arMenuChild2["ID"]?>

                                                                                                    <?endif;?>


                                                                                                ">

                                                                                                    <a title = "<?=$arMenuChild2['NAME']?>" <?if(isset($arMenuChild2['LINK']{0})):?>href='<?=$arMenuChild2['LINK']?>'<?endif;?>>

                                                                                                        <?=$arMenuChild2['NAME']?>

                                                                                                    </a>
                                                                                                </li>

                                                                                                <?$j++;?>

                                                                                            <?endforeach;?>

                                                                                            <?if(count($arMenuChild['SUB']) > $breakCount):?>
                                                                                                <li class="last">
                                                                                                    <a <?if(isset($arMenuChild["LINK"]{0})):?>href="<?=$arMenuChild["LINK"]?>"<?endif;?>>
                                                                                                        <span><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["MENU_TEMPL_ALL_SECTIONS"];?></span>
                                                                                                    </a>
                                                                                                </li>

                                                                                            <?endif;?>
                                                                                        </ul>

                                                                                    <?endif;?>


                                                                                </td>
                                                                                
                                                                            </tr>
                                                                        </table>
                                                                        
                                                                    </div>

                                                                    <?

                                                                        $count++;
                                                                    ?>


                                                                <?endforeach;?>

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="blur-shadow-top"></div>
                                                    <div class="blur-shadow-bottom"></div>


                                                </div>



                                                

                                            <?endif;?>

                                        <?endif;?>
                                     
                                    </li>
                                    

                                <?endforeach;?>


                            </nav>

                        </td>

                        <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["GROUP_POS"]["VALUE"]["MAIN_MENU"] == 'Y' && $PHOENIX_TEMPLATE_ARRAY["DISJUNCTIO"]["SOC_GROUP"]["VALUE"]):?>


                            <td class="wrapper-social">

                                <a class="show-soc-groups d-block"

                                    <?=(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['SOC_GROUP_ICON']["SRC"]))?"style='background-image: url(\"".$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['SOC_GROUP_ICON']["SRC"]."\");'":"";?>

                                ></a>
                                <div class="soc-groups-in-menu d-none">
                                    <div class="desc">  
                                    <?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SOC_GROUPS_DESCRIPTION_IN_MENU"]?>
                                    </div>  
                                    <?CPhoenix::CreateSoc($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"])?>

                                    <div class="close-soc-groups"></div>
                                </div>
                                
                                

                                <?/*if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_IN_MENU_ON']['VALUE']["ACTIVE"] == "Y"):?>

                                    <div class="mini-cart-style mini-cart-js active">
                                        <div class="area_for_widget-in-menu hidden-xs">
                                            <?
                                                $APPLICATION->IncludeComponent(
                                                    "concept:phoenix.mini_cart",
                                                    "widget-in-menu",
                                                    Array(
                                                        "CURRENT_SITE" => SITE_ID,
                                                        "MESSAGE_404" => "",
                                                        "SET_STATUS_404" => "N",
                                                        "SHOW_404" => "N",
                                                        "MODE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_MINICART_MODE"]["VALUE"],
                                                        "DESC_EMPTY" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_MINICART_DESC_EMPTY"]["VALUE"],
                                                        "DESC_NOEMPTY" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_MINICART_DESC_NOEMPTY"]["VALUE"],
                                                        "LINK" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_MINICART_LINK_PAGE"]["VALUE"]
                                                    )
                                                );
                                            ?>
                                        </div>
                                    </div>

                                <?endif;*/?>

                            </td>

                        <?endif;?>
                    </tr>
                </table>

            </div>

            

        </div>
    </div>

<?endif;?>