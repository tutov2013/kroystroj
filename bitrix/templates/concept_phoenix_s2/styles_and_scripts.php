<?use \Bitrix\Main\Page\Asset as Asset;?>


<?
global $PhoenixCssFullList, $USER, $PHOENIX_TEMPLATE_ARRAY; 


$PhoenixCssList = Array();
$PhoenixCssTrueList = Array();

$PhoenixCssList[] = SITE_TEMPLATE_PATH."/css/bootstrap.min.css";
$PhoenixCssList[] = SITE_TEMPLATE_PATH."/css/font-awesome.css";
$PhoenixCssList[] = SITE_TEMPLATE_PATH."/css/animate.min.css";
$PhoenixCssList[] = SITE_TEMPLATE_PATH."/css/xloader.css";
$PhoenixCssList[] = SITE_TEMPLATE_PATH."/css/blueimp-gallery.min.css";
$PhoenixCssList[] = SITE_TEMPLATE_PATH."/slick/slick.css";
$PhoenixCssList[] = SITE_TEMPLATE_PATH."/slick/slick-theme.css";
$PhoenixCssList[] = SITE_TEMPLATE_PATH."/css/jquery.datetimepicker.min.css";
$PhoenixCssList[] = SITE_TEMPLATE_PATH."/css/farbtastic.css";
$PhoenixCssList[] = SITE_TEMPLATE_PATH."/css/concept.css";

$PhoenixCssTrueList[] = SITE_TEMPLATE_PATH."/css/jquery.countdown.css";
$PhoenixCssTrueList[] = SITE_TEMPLATE_PATH."/css/responsive.css";

$PhoenixCssFullList = array_merge($PhoenixCssList, $PhoenixCssTrueList);



global $PhoenixJSFullList;

$PhoenixJSFullList = Array();

$PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/js/jqueryConcept.min.js";
$PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/js/bootstrap.min.js";
$PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/js/bootstrap.bundle.min.js";

$PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/js/jquery.plugin.min.js";
$PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/js/jquery.countdown.min.js";
$PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/lang/ru/jquery.countdown-ru.js";
$PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/js/device.min.js";
$PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/js/wow.js";
//$PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/js/detectmobilebrowser.js";
$PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/js/jquery.enllax.js";
$PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/js/jquery.maskedinputConcept.min.js";
$PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/js/jquery.blueimp-gallery.min.js";
$PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/slick/slick.min.js";
$PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/lang/ru/jquery.datetimepicker.full.min.js";
$PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/js/typed.min.js";
$PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/js/sly.js";
$PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/js/lazyload.min.js";
$PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/js/jquery.zoom.min.js";
$PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/js/script.js";
$PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/js/forms.js";
$PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/js/custom.js";
?>


<?
CJSCore::Init(array('ajax', 'fx', 'currency'));
\Bitrix\Main\Loader::includeModule('currency');
?>

<?foreach($PhoenixCssList as $css):?>
    <?Asset::getInstance()->addCss($css);?>
<?endforeach;?>

<?foreach($PhoenixCssTrueList as $css):?>
    <?Asset::getInstance()->addCss($css, true);?>
<?endforeach;?>


<?foreach($PhoenixJSFullList as $js):?>
    <?Asset::getInstance()->addJs($js);?>
<?endforeach;?>

<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["CAPTCHA"]["VALUE"]["ACTIVE"]=="Y" && isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["CAPTCHA_SITE_KEY"]["VALUE"]{0})):?>

    <?Asset::getInstance()->addJs('https://www.google.com/recaptcha/api.js?render='.$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["CAPTCHA_SITE_KEY"]["VALUE"]);?>

<?endif;?>


<?\Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("composit_styles");?>


    <?if($PHOENIX_TEMPLATE_ARRAY["IS_ADMIN"]):?>

        <?Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/css/fonts/fontAdmin.css", true);?>
        <?Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/css/settings.css");?>

        <?Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/farbtastic.js");?>
        <?Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/zero-clipboard.js");?>
        <?Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/settings.js");?>
        
    <?endif;?>
    
<?\Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("composit_styles");?>