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
?>
<?global $PHOENIX_TEMPLATE_ARRAY?>

<?/*if(!empty($arResult["SECTIONS"])):*/?>

<?$countSections = (!empty($arResult["SECTIONS"])) ? count($arResult["SECTIONS"]):0?>

    <div class="section-with-hidden-items">

        <?if($countSections>1):?>

            <div class="btn-click click-animate-slide-down noactive noactive-mob catlist-icon" data-show = "catalog-sections"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["OTHER_CATEGORY"]?><i class="down concept-down-open-mini"></i><i class="up concept-up-open-mini"></i></div>
        <?endif;?>

        <div class="body content-animate-slide-down <?=($countSections>1)? "noactive":"active";?> noactive-mob" data-show = "catalog-sections" style="display:<?=($countSections>1)? "":"block";?>">

            <div class="menu-navigation static">

                <div class="menu-navigation-wrap">
                    <div class="menu-navigation-inner no-padding-top" id="navigation">

                        <div class="row">

                            <ul class="nav">

                                <?if($countSections>1):?>
                                
                                    <?foreach($arResult["SECTIONS"] as $arSection):?>
                                    
                                        <li class="col-12" data-id="<?=$arSection["ID"]?>">
                                            <a href="<?=$arSection["SECTION_PAGE_URL"]?>">
                                                <span class="text">
                                                    <table>
                                                        <tr>
                                                            <td class="name"><?=strip_tags($arSection["~NAME"])?></td>

                                                            <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["SUBSECTIONS_HIDE_COUNT"]["VALUE"]["ACTIVE"] != "Y" && $arSection["ELEMENT_CNT"]!="0"):?>
                                                            
                                                                <td class="count"><?=$arSection["ELEMENT_CNT"]?></td>

                                                            <?endif;?>
                                                        </tr>
                                                    </table>
                                                </span>
                                            </a>
                                        </li>
                                    
                                    <?endforeach;?>

                                <?endif;?>

                                <?if(isset($arResult["SECTION_BACK"]{0})):?>
                                
                                    <li class="col-12 back">
                                        <a href="<?=$arResult["SECTION_BACK"]?>"><span class="text"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["BACK_TO_LVL_UP"]?></span></a>
                                    </li>

                                <?endif;?>
                                
                            </ul>
                        </div>
                    </div>
                
                </div>
            
            </div>

        </div>

    </div>

<?/*endif;*/?>