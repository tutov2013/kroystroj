<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) { die(); }

use Bitrix\Main\Localization\Loc;
use Yandex\Market;

/** @var array $box */
/** @var array $boxInputName */

?>
<div class="is--hidden js-yamarket-box-size__modal">
	<table
		border="0"
		cellspacing="0"
		cellpadding="0"
		width="100%"
		class="adm-detail-content-table edit-table js-yamarket-box-size__field"
		data-plugin="OrderView.BoxSizes"
	>
		<?php
		foreach ($arResult['BOX_DIMENSIONS'] as $dimensionName => $dimensionDescription)
		{
			$dimensionValue = isset($box['DIMENSIONS'][$dimensionName])
				? $box['DIMENSIONS'][$dimensionName]['VALUE']
				: null;

			?>
			<tr>
				<td class="adm-detail-content-cell-l" width="40%"><?php
					echo $dimensionDescription['NAME'];

					if (isset($dimensionDescription['UNIT_FORMATTED']))
					{
						echo ', ' . $dimensionDescription['UNIT_FORMATTED'];
					}

					echo ':';
				?></td>
				<td class="adm-detail-content-cell-r">
					<input
						class="js-yamarket-box-size__input"
						type="text"
						<?php
						if (!isset($box['PLACEHOLDER']))
						{
							?>
							name="<?= $boxInputName . '[DIMENSIONS][' . $dimensionName . ']'; ?>"
							value="<?= htmlspecialcharsbx($dimensionValue); ?>"
							<?
						}
						?>
						data-name="<?= $dimensionName; ?>"
					/>
				</td>
			</tr>
			<?
		}
		?>
	</table>
</div>
