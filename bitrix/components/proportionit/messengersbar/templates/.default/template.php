<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?use Bitrix\Main\Page\Asset;

$arMess = $arResult["ITEMS"]["ITEMS"];
$genParams = $arResult["ITEMS"]["GENERAL"];

if ($genParams['ACTIVE'] == Y):

Asset::getInstance()->addString('<link href="https://fonts.googleapis.com/css?family=Roboto|Source+Sans+Pro:300,300i" rel="stylesheet">');  



$templateFolder = $this->GetFolder();

CJSCore::Init(array("jquery"));

//css params
$count = count($arMess);
$count = (round(100/$count) - 1).".5%";
$styles="<style> .pro-messbar-wrap-item { width: ".$count .";}"
        . "@media (min-width:".$genParams['MEDIA_WIDTH']."px) { .pro-messbar { display:none !important;}}</style>";

$APPLICATION->AddHeadString($styles);

?>
    <div class="pro-messbar">
        <div class="pro-messbar-wrap">

            <? foreach ( $arMess as $item ):?>
            <a href="<?=$item['HREF'];?>" 
                <?if($item['KEY'] != "cart" ):?>target="_blank"<?endif;?> 
                <?=$item['HREF'];?>
                <?if(!empty($item['TARGET'])) {?> onclick="<?=$item['TARGET']?>" <?}?>>
                <div class="pro-messbar-wrap-item">

                            <div class="pro-messbar-wrap-item-img">
                                
                                <img src="<?=$templateFolder;?>/images/<?=$item['KEY'];?>.svg" />
                            </div>
                            <div class="pro-messbar-wrap-item-text">
                                <?=$item['TEXT'];?>
                            </div>
                        </div>
                    </a>
            <? endforeach;?>

        </div>
    </div> 
<?endif;?>    



