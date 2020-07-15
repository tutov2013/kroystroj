<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
global $PHOENIX_TEMPLATE_ARRAY;


if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_VOTE"]["VALUE"]["ACTIVE"] == "Y" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_REVIEW"]["VALUE"]["ACTIVE"] == "Y" && !empty($arResult["ITEMS_ID"]))   
{
    CPhoenix::setMess(array("rating"));

    
    $arReview = CPhoenix::getReviewsInfo(array("PRODUCT_ID"=>$arResult["ITEMS_ID"], "SITE_ID"=> SITE_ID, "select"=> array("PRODUCT_ID", "VOTE_SUM", "VOTE_COUNT", "REVIEWS_COUNT")));

    foreach ($arResult["ITEMS_ID"] as $itemID)
    {
        $reviewCount = 0;

        if(!empty($arReview[$itemID]))
        {

            if($arReview[$itemID]["VOTE_SUM"] && $arReview[$itemID]["VOTE_COUNT"])
                $arResult["rating"][$itemID] = round($arReview[$itemID]["VOTE_SUM"] / $arReview[$itemID]["VOTE_COUNT"]);
            else
                $arResult["rating"][$itemID] = 0;


            $reviewCount = $arReview[$itemID]["REVIEWS_COUNT"];
        }

        $arResult["reviews"][$itemID] = $reviewCount."&nbsp;".CPhoenix::getTermination(
            $reviewCount, 
            array(
                $PHOENIX_TEMPLATE_ARRAY["MESS"]["RATING"]["CONTAINER_DESC_CNT_1"],
                $PHOENIX_TEMPLATE_ARRAY["MESS"]["RATING"]["CONTAINER_DESC_CNT_2"],
                $PHOENIX_TEMPLATE_ARRAY["MESS"]["RATING"]["CONTAINER_DESC_CNT_3"],
                $PHOENIX_TEMPLATE_ARRAY["MESS"]["RATING"]["CONTAINER_DESC_CNT_4"]
            )
        );
    }

    
}
?>

<?global $html_constructor_labels;

$ar = array();

$ar = $arResult["LABEL"];
$ar["ITEMS"] = $arResult["ITEMS"];
$ar["RATING_VIEW"] = $arResult["RATING_VIEW"];

$html_constructor_labels[$arResult["LABEL"]["ID"]] = $ar;
?>

<?\Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("area");?>
<?if(!empty($arResult["rating"]) || !empty($arResult["reviews"])):?>
<script>
    $(document).ready(function(){
        <?if(!empty($arResult["rating"])):?>
            <?foreach ($arResult["rating"] as $key => $value){?>
                setRatingProduct(<?=$key?>, <?=$value?>);
            <?}?>
        <?endif;?>

        <?if(!empty($arResult["reviews"])):?>

            <?foreach ($arResult["reviews"] as $key => $value){?>
                setReviewsCountProduct(<?=$key?>, "<?=$value?>");
            <?}?>

        <?endif;?>
    });
</script>
<?endif;?>

<?\Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("area");?>