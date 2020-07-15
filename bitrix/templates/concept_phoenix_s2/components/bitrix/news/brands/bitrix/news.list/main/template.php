<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?if(!empty($arResult["ITEMS"])):?>
	<div class="brands-list">
        <div class="row">
            <?foreach($arResult["ITEMS"] as $arItem):?>
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="item row no-gutters align-items-center">
                        <div class="col-12">
                            <a class = "d-block" href="<?=$arItem['DETAIL_PAGE_URL']?>">
                                <img src="<?=$arItem["PREVIEW_PICTURE_SRC"]?>" alt="" class="d-block mx-auto img-fluid">
                             </a>
                        </div>
                        <?CPhoenix::admin_setting($arItem, false)?>
                    </div>
                </div>
            <?endforeach;?>
        </div>
    </div>

    <?=$arResult["NAV_STRING"]?>
<?endif?>
