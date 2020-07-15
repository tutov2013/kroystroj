<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) { die(); }

/** @var \Yandex\Market\Components\TradingPrintForm $component */

foreach ($arResult['SETTINGS'] as $setting)
{
	?>
	<div class="yamarket-shipment-print-setting">
		<label class="yamarket-shipment-print-setting__label" for="YABERU_PRINT_SIZE"><?= $setting['NAME']; ?></label>
		<?= $component->getSettingHtml($setting); ?>
	</div>
	<?php
}