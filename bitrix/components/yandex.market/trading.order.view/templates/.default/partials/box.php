<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) { die(); }

use Bitrix\Main\Localization\Loc;
use Yandex\Market;

/** @var string $boxInputName */
/** @var int $boxNumber */
/** @var array $box */

$isBoxItemsEmpty = empty($box['ITEMS']);
$boxItemsIterator = $isBoxItemsEmpty ? [] : (array)$box['ITEMS'];

if ($isBoxItemsEmpty && $arResult['SHIPMENT_EDIT'])
{
	$boxItemsIterator[] = [
		'PLACEHOLDER' => true,
	];
}

?>
<table
	class="yamarket-box-table adm-s-order-table-ddi-table adm-s-bus-ordertable-option js-yamarket-box <?= isset($box['PLACEHOLDER']) ? 'is--hidden' : ''; ?>"
	data-plugin="OrderView.Box"
	data-name="BOX"
>
	<thead>
		<tr>
			<td
				class="yamarket-box-header js-yamarket-box__child"
				colspan="5"
				data-plugin="OrderView.BoxSizeSummary"
				data-name="DIMENSIONS"
			>
				<h3 class="yamarket-box-header__title">
					<?= Loc::getMessage('YANDEX_MARKET_T_TRADING_ORDER_VIEW_BOX'); ?>
					<span class="js-yamarket-box__number">&numero;<?= $box['NUMBER']; ?></span>
				</h3>
				<div class="yamarket-box-header__properties">
					<?php
					foreach ($arResult['BOX_PROPERTIES'] as $boxPropertyName => $boxProperty)
					{
						$boxPropertyValue = isset($box['PROPERTIES'][$boxPropertyName]) ? $box['PROPERTIES'][$boxPropertyName] : null;
						$isBoxPropertyEmpty = ((string)$boxPropertyValue === '');

						?>
						<div
							class="yamarket-box-property <?= $isBoxPropertyEmpty ? 'is--hidden' : ''; ?> js-yamarket-box-size__property"
							data-name="<?= $boxPropertyName; ?>"
						>
							<?= $boxProperty['NAME'] . ': '; ?>
							<span class="js-yamarket-box-size__property-value"><?= $boxPropertyValue; ?></span>
							<?= isset($boxProperty['UNIT_FORMATTED']) ? $boxProperty['UNIT_FORMATTED'] : ''; ?>
						</div>
						<?php
					}
					?>
				</div>
				<?php
				if ($arResult['SHIPMENT_EDIT'])
				{
					foreach (['FULFILMENT_ID'] as $fieldName)
					{
						?>
						<input
							class="is--persistent js-yamarket-box__input"
							type="hidden"
							<?php
							if (!isset($box['PLACEHOLDER']))
							{
								?>
								name="<?= $boxInputName . '[' . $fieldName .']'; ?>"
								value="<?= $box[$fieldName]; ?>"
								<?
							}
							?>
							data-name="<?= $fieldName; ?>"
						/>
						<?php
					}

					?>
					<div class="yamarket-box-header__actions">
						<input
							class="yamarket-box-action adm-btn adm-btn-edit js-yamarket-box-size__edit"
							type="button"
							value="<?= Loc::getMessage('YANDEX_MARKET_T_TRADING_ORDER_VIEW_BOX_EDIT'); ?>"
						/>
						<input
							class="yamarket-box-action adm-btn adm-btn-delete js-yamarket-box__delete"
							type="button"
							value="<?= Loc::getMessage('YANDEX_MARKET_T_TRADING_ORDER_VIEW_BOX_DELETE'); ?>"
						/>
					</div>
					<?
					include __DIR__ . '/box-dimensions-modal.php';
				}
				?>
			</td>
		</tr>
	</thead>
	<tbody class="js-yamarket-box__child" data-plugin="OrderView.BoxItemCollection" data-name="ITEM">
		<tr></tr><?php // hack for bitrix css ?>
		<tr class="<?= $isBoxItemsEmpty ? '' : 'is--hidden'; ?> js-yamarket-box-item-collection__empty">
			<td class="for--empty" colspan="4"><?= Loc::getMessage('YANDEX_MARKET_T_TRADING_ORDER_VIEW_BOX_EMPTY'); ?></td>
		</tr>
		<?php
		$boxItemIndex = 0;

		foreach ($boxItemsIterator as $boxItem)
		{
			$boxItemInputName = $boxInputName . '[ITEM][' . $boxItemIndex . ']';
			$isBoxItemPlaceholder = isset($boxItem['PLACEHOLDER']);

			if ($arResult['SHIPMENT_EDIT'])
			{
				?>
				<tr class="bdb-line <?= $isBoxItemPlaceholder ? 'is--hidden' : ''; ?> js-yamarket-box-item" data-plugin="OrderView.BoxItem" data-id="<?= $boxItem['ID']; ?>">
					<td class="tal for--name js-yamarket-box-item__data" data-name="NAME"><?= $boxItem['NAME']; ?></td>
					<td class="tal for--price js-yamarket-box-item__data" data-name="PRICE"><?= isset($boxItem['PRICE_FORMATTED']) ? $boxItem['PRICE_FORMATTED'] : '&mdash;'; ?></td>
					<td class="tal for--count">
						<input
							class="yamarket-box-item__quantity adm-input js-yamarket-box-item__data"
							type="number"
							<?
							if (!$isBoxItemPlaceholder)
							{
								?>
								name="<?= $boxItemInputName . '[COUNT]'; ?>"
								value="<?= (float)$boxItem['COUNT']; ?>"
								<?
							}
							?>
							min="0"
							<?= isset($boxItem['COUNT_TOTAL']) ? 'max="' . $boxItem['COUNT_TOTAL'] . '"' : ''; ?>
							data-name="COUNT"
						/>
						<?= Loc::getMessage('YANDEX_MARKET_T_TRADING_ORDER_VIEW_BASKET_ITEM_MEASURE'); ?>
					</td>
					<td>
						<?php
						$boxItemHiddenFields = [ 'ID' ];

						foreach ($boxItemHiddenFields as $fieldName)
						{
							?>
							<input
								class="js-yamarket-box-item__data"
								type="hidden"
								<?php
								if (!$isBoxItemPlaceholder)
								{
									?>
									name="<?= $boxItemInputName . '[' . $fieldName .']'; ?>"
									value="<?= $boxItem[$fieldName]; ?>"
									<?
								}
								?>
								data-name="<?= $fieldName; ?>"
							/>
							<?php
						}
						?>
						<a class="yamarket-trash js-yamarket-box-item__delete" href="#"><?= Loc::getMessage('YANDEX_MARKET_T_TRADING_ORDER_VIEW_BOX_ITEM_DELETE'); ?></a>
					</td>
				</tr>
				<?php
			}
			else
			{
				?>
				<tr class="bdb-line js-yamarket-box-item" data-plugin="OrderView.BoxItem" data-id="<?= $boxItem['ID']; ?>">
					<td class="tal for--name js-yamarket-box-item__data" data-name="NAME"><?= $boxItem['NAME']; ?></td>
					<td class="tal for--price js-yamarket-box-item__data" data-name="PRICE"><?= isset($boxItem['PRICE_FORMATTED']) ? $boxItem['PRICE_FORMATTED'] : '&mdash;'; ?></td>
					<td class="tal for--count">
						<span class=""><?= (float)$boxItem['COUNT']; ?></span>
						<?= Loc::getMessage('YANDEX_MARKET_T_TRADING_ORDER_VIEW_BASKET_ITEM_MEASURE'); ?>
					</td>
				</tr>
				<?php
			}

			++$boxItemIndex;
		}
		?>
	</tbody>
</table>
