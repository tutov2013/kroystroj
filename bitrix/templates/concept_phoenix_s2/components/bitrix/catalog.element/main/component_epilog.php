<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?
global $PHOENIX_TEMPLATE_ARRAY;

if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_VOTE"]["VALUE"]["ACTIVE"] == "Y" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_REVIEW"]["VALUE"]["ACTIVE"] == "Y")  
{
    
	CPhoenix::setMess(array("rating"));
	
    $arReview = CPhoenix::getReviewsInfo(array("PRODUCT_ID"=>$arResult["ID"], "SITE_ID"=> SITE_ID, "select"=> array("PRODUCT_ID","VOTE_SUM", "VOTE_COUNT", "REVIEWS_COUNT")));


    $reviewCount = 0;

    $arResult["rating"][$arResult["ID"]]["VALUE"] = $arResult["rating"][$arResult["ID"]]["COUNT"] = 0;

    if(!empty($arReview[$arResult["ID"]]))
    {
        if($arReview[$arResult["ID"]]["VOTE_SUM"] && $arReview[$arResult["ID"]]["VOTE_COUNT"])
        {
            $arResult["rating"][$arResult["ID"]]["VALUE"] = round($arReview[$arResult["ID"]]["VOTE_SUM"] / $arReview[$arResult["ID"]]["VOTE_COUNT"]);

            $arResult["rating"][$arResult["ID"]]["COUNT"] = $arReview[$arResult["ID"]]["VOTE_COUNT"];
        }

        $reviewCount = $arReview[$arResult["ID"]]["REVIEWS_COUNT"];
    }

    $arResult["reviews"][$arResult["ID"]] = $reviewCount."&nbsp;".CPhoenix::getTermination(
        $reviewCount, 
        array(
            $PHOENIX_TEMPLATE_ARRAY["MESS"]["RATING"]["CONTAINER_DESC_CNT_1"],
            $PHOENIX_TEMPLATE_ARRAY["MESS"]["RATING"]["CONTAINER_DESC_CNT_2"],
            $PHOENIX_TEMPLATE_ARRAY["MESS"]["RATING"]["CONTAINER_DESC_CNT_3"],
            $PHOENIX_TEMPLATE_ARRAY["MESS"]["RATING"]["CONTAINER_DESC_CNT_4"]
        )
    );
}
?>

<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_VOTE"]["VALUE"]["ACTIVE"] == "Y" && intval($arResult["rating"][$arResult["ID"]]["COUNT"]) > 0):?>
    <div style="display: none;" itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
        <meta itemprop="ratingValue" content="<?=$arResult["rating"][$arResult["ID"]]["VALUE"]?>">
        <meta itemprop="reviewCount" content="<?=$arResult["rating"][$arResult["ID"]]["COUNT"]?>">
        <meta itemprop="bestRating" content="5">
        <meta itemprop="worstRating" content="0">
    </div>
<?endif;?>


<?\Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("area");?>

<?
if (!empty($arResult['TITLE_PROPS']))
{
    $propsStr = implode(', ', $arResult['TITLE_PROPS']);
    $newName = $arResult['NAME'].' '.$propsStr;
    $arResult["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"] = str_replace($arResult['NAME'], $newName, $arResult["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"]);
    $arResult["IPROPERTY_VALUES"]["ELEMENT_META_DESCRIPTION"] = str_replace($arResult['NAME'], $newName, $arResult["IPROPERTY_VALUES"]["ELEMENT_META_DESCRIPTION"]);
}

if(strlen($GLOBALS["TITLE"]) <= 0)
    $GLOBALS["TITLE"] = $arResult["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"];

if(strlen($GLOBALS["KEYWORDS"]) <= 0)
    $GLOBALS["KEYWORDS"] = $arResult["IPROPERTY_VALUES"]["ELEMENT_META_KEYWORDS"];

if(strlen($GLOBALS["DESCRIPTION"]) <= 0)
    $GLOBALS["DESCRIPTION"] = $arResult["IPROPERTY_VALUES"]["ELEMENT_META_DESCRIPTION"];

if(strlen($GLOBALS["H1"]) <= 0)
    $GLOBALS["H1"] = $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"];
     
?>


<script>
	
	$(document).ready(function() {
		<?if(!empty($arResult["SECTIONS_ID"])):?>
			<?foreach ($arResult["SECTIONS_ID"] as $value):?>

				$(".section-menu-id-<?=$value?>").addClass('selected');
				
			<?endforeach;?>
		<?endif;?>

		<?if(!empty($arResult["rating"])):?>
	        <?foreach ($arResult["rating"] as $key => $value){?>
	            setRatingProduct(<?=$key?>, <?=$value["VALUE"]?>);
	        <?}?>

		<?endif;?>

		<?if(!empty($arResult["reviews"])):?>

			<?foreach ($arResult["reviews"] as $key => $value){?>
	            setReviewsCountProduct(<?=$key?>, "<?=$value?>");
	        <?}?>

        <?endif;?>

	});

</script>
<?\Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("area");?>

