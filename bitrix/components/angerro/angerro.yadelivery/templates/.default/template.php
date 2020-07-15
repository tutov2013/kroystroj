<?
$sModuleId = 'angerro.yadelivery';
$api_key = COption::GetOptionString($sModuleId, 'api_key');
$apiGetParams = $api_key ? '&apikey='.$api_key : '';
$APPLICATION->AddHeadString('<script src="https://api-maps.yandex.ru/2.1/?load=package.full&lang=ru_RU'.$apiGetParams.'" type="text/javascript"></script>',true);
?>

<script type="text/javascript">
ymaps.ready(function () {
	// преобразуем в массив из строки date
	date = '<?=$arResult['MAP_DATA']?>';
	data = JSON.parse(date);

	// Создание экземпляра карты и его привязка к контейнеру с id = angerro_map
	var center = data[0].map.center;
	var zoom = data[0].map.zoom;
	
	myMap = new ymaps.Map('angerro_map_<?=$arParams["MAP_ID"]?>', {
		center: center,
		zoom: zoom,
		controls: ['zoomControl']
	});

	// Создание коллекций геообъектов
	//в этой коллекции хранятся зоны доставки (полигоны)
	myGeoObjectsCollection_map = new ymaps.GeoObjectCollection();

	/*
	 * добавим в коллекция myGeoObjectsCollection_map полигоны из массива data
	 */
	for (var k = 0; k < data[0].delivery_areas.length; k++) {
		var areaCoord = data[0].delivery_areas[k].area_coordinates;
		var areaColor = data[0].delivery_areas[k].settings.color;
		var areaTitle = data[0].delivery_areas[k].settings.title;
		myGeoObjectsCollection_map.add(new ymaps.Polygon(areaCoord,
			{pointColor: areaColor, deliveryZone: areaTitle, hintContent: areaTitle}, {fillColor: areaColor, opacity: 0.5}));

		//добавим названия зон доставки:		
	    var delivery_area = document.createElement('div');
		delivery_area.className = "angerro_map_block_description";
		delivery_area.innerHTML = '<div class="angerro_map_block_color" style="background-color:'+areaColor+';"></div><div class="angerro_map_block_text">'+areaTitle+'</div><div class="angerro_map_block_clear"></div>';
		angerro_map_<?=$arParams["MAP_ID"]?>_description.className = "angerro_map_block_description";
		angerro_map_<?=$arParams["MAP_ID"]?>_description.appendChild(delivery_area);

		
	}
	// Устанавливаем опции всем геообъектам в коллекции прямо через коллекцию
	myGeoObjectsCollection_map.options
		.set({
			draggable: false,
			inderactive: 'none'
		});

	// Добавление коллекций геообъектов на карту
	myMap.geoObjects
		.add(myGeoObjectsCollection_map);
});			
</script>


<div>
	<div id="angerro_map_<?=$arParams["MAP_ID"]?>" style="width: <?=$arParams["WIDTH"]?>px; height: <?=$arParams["HEIGHT"]?>px;">
	</div>
	<div id="angerro_map_<?=$arParams["MAP_ID"]?>_description">
	</div>
</div>