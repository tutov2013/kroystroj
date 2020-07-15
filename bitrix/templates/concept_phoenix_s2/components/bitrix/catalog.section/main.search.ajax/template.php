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

<?global $PHOENIX_TEMPLATE_ARRAY;?>

<?if(!empty($arResult['ITEMS'])):?>

<?$countItems = count($arResult["ITEMS"]);?>

<?foreach ($arResult['ITEMS'] as $keyItem => $arItem):?>

    <div class="<?=$arParams["COLS_GOODS"]?>">
        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="d-block search-item">
            <table class="search-item m-bottom-sm">
                <tr>
                    <td class="search-item-img">
                        <div class="search-item-img row no-gutters align-items-center">
                            <div class="col-12">
                                <img src="<?=$arItem['PREVIEW_PICTURE_SRC']?>" alt=""/>
                            </div>
                        </div>
                    </td>
                    <td class="search-item-name">

                        <div class="search-item-name" title="<?=strip_tags($arItem["~NAME"])?>">
                            <?=strip_tags($arItem["~NAME"])?>
                        </div>

                        <div class="search-item-prices">

                            <?if(isset($arItem["OLD_PRICE"])):?>
                                <div class="search-item-old-price">
                                    <?=$arItem["OLD_PRICE"]?>
                                </div>
                            <?endif;?>
                            
                            <div class="search-item-actual-price bold">
                                <?=$arItem["ACTUAL_PRICE"]?>
                            </div>
                            
                        </div>
                    </td>
                </tr>
            </table>
        </a>
    </div>

<?endforeach;?>

<?endif;?>