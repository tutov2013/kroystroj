<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?\Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("side-menu-back");?>

<?
global $PHOENIX_TEMPLATE_ARRAY, $res;

$res = $arResult;

$host = CPhoenixHost::getHost($_SERVER);

$ref_url = explode("?", $_SERVER['HTTP_REFERER']);
$uri = explode("?", $host.$_SERVER['REQUEST_URI']);
?>

<?if(strlen($ref_url[0]) && $ref_url[0] != $uri[0]):?>

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $("ul.nav").append('<li class="col-12 back"><a href="<?=$_SERVER["HTTP_REFERER"]?>"> <span class="text"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["MENU_SIDE_BACK"]?></span></a></li>');
        });

    </script>

<?endif;?>

<?\Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("side-menu-back");?>


<?$GLOBALS["h1_main"] = $arResult["H1_MAIN"];?>

<?$temp = $arResult["CACHED_TPL"]?>



<?if(!empty($arResult["COMPONENTS"])):?>

    <?foreach($arResult["COMPONENTS"] as $key=>$arItem):?>
        
        <?$path = $arItem["PROPERTIES"]["COMPONENT_PATH"]["VALUE"];?>

        <?$temp = preg_replace_callback(
                    "/#DYNAMIC".$key."#/",
                    create_function('$matches', 'ob_start();
                    $GLOBALS["APPLICATION"]->IncludeFile("'.$path.'", array(),
                        array(
                            "MODE"  => "php",
                        )
                    );
                    $retrunStr = @ob_get_contents();
                    ob_get_clean();
                    return $retrunStr;'),
                    $temp);
        ?>

    <?endforeach;?>

<?endif;?>

<?=$temp;?>