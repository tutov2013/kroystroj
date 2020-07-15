<?

	//check params
	if(empty($arParams["IBLOCK_ID"])){
		return false;
	}

	//default params
	$arParams["CACHE_TIME"] = !empty($arParams["CACHE_TIME"]) ? $arParams["CACHE_TIME"] : "36000000";
	$arParams["IMAGE_QUALITY"] = !empty($arParams["IMAGE_QUALITY"]) ? $arParams["IMAGE_QUALITY"] : "100";
	$arParams["PICTURE_WIDTH"] = !empty($arParams["PICTURE_WIDTH"]) ? $arParams["PICTURE_WIDTH"] : "60";
	$arParams["PICTURE_HEIGHT"] = !empty($arParams["PICTURE_HEIGHT"]) ? $arParams["PICTURE_HEIGHT"] : "60";

	//cache id
	$cacheId = array(
		"IBLOCK_ID" => $arParams["IBLOCK_ID"]
	);

	//vars
	$arResult = array();

	//start cache
	if($this->StartResultCache($arParams["CACHE_TIME"], serialize($cacheId))){

		$arSelect = array("ID", "IBLOCK_ID", "NAME", "DETAIL_PICTURE", "DETAIL_TEXT");
		$arFilter = array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE_DATE" => "Y", "ACTIVE" => "Y");
		$res = CIBlockElement::GetList(array("SORT" => "DESC", "NAME" => "ASC"), $arFilter, false, array("nTopCount" => 24, "iNumPage" => 0), $arSelect);

		//each items
		while($nextObject = $res->GetNextElement()){

			//get item fields
			$arElement = $nextObject->GetFields();

			//set picture from parent product
			if(!empty($arElement["DETAIL_PICTURE"])){
				$arElement["DETAIL_PICTURE"] = CFile::ResizeImageGet($arElement["DETAIL_PICTURE"], array("width" => $arParams["PICTURE_WIDTH"], "height" => $arParams["PICTURE_HEIGHT"]), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $arParams["IMAGE_QUALITY"]);
			}

			//push element
			$arResult["ITEMS"][$arElement["ID"]] = $arElement;

		}

		$this->setResultCacheKeys(array());
		$this->IncludeComponentTemplate();

	}

?>