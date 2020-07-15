<tr><td>

<div id="angerro_yadelivery_tab2_map_preview">
</div>

<!--info texts-->
<div class="angerro_yadelivery_text_info_tab2">
<?=GetMessage('STEP_1')?>
</div>
<div class="angerro_yadelivery_text_info_tab2">
<?=GetMessage('STEP_2')?>
</div>
<div class="angerro_yadelivery_text_info_tab2">
<?=GetMessage('STEP_3')?>
</div>
<div class="angerro_yadelivery_text_info_tab2">
<?=GetMessage('STEP_SUCCESS')?>
</div>
<div class="angerro_yadelivery_text_info_tab2">
<?=GetMessage('STEP_EDIT_GEOMETRY')?>
</div>	
<div class="angerro_yadelivery_text_info_tab2">
<?=GetMessage('CAN_DRAW_TEXT')?>
</div>	
<div class="angerro_yadelivery_text_info_tab2">
<?=GetMessage('ZONE_DELIVERY')?>
</div>
<div class="angerro_yadelivery_text_info_tab2">
<?=GetMessage('COLOR_DELIVERY_ZONE')?>
</div>
<div class="angerro_yadelivery_text_info_tab2">
<?=GetMessage('NAME_DELIVERY_ZONE')?>
</div>
<!--//info texts-->

<div id="angerro_yadelivery_tab2_hint" class="adm-info-message">
</div>

<div id="angerro_yadelivery_tab2_delivery_description">
	<div class="angerro_yadelivery_tab2_header"><?=GetMessage('COLOR_DELIVERY_ZONE')?></div>
	<div class="angerro_yadelivery_tab2_header"><?=GetMessage('NAME_DELIVERY_ZONE')?></div>
	<div class="angerro_yadelivery_tab2_clear"></div>
</div>

<div class="angerro_yadelivery_tab2_map_name_block">
	<div class="angerro_yadelivery_tab2_header"><?=GetMessage('NAME_MAP_W_DELIVERY_ZONE')?></div>
	<input id="angerro_yadelivery_tab2_delivery_map_name" type="text" value="<?=GetMessage('MAP_W_ZONES')?>">
	<div class="angerro_yadelivery_tab2_clear"></div>
</div>

<div class="angerro_yadelivery_tab2_buttons_block">
	<?=bitrix_sessid_post()?>
	<input id="angerro_yadelivery_tab2_create_description" class="adm-btn-save" type="button" value="<?=GetMessage('EDIT_COLORS')?>">
	<input id="angerro_yadelivery_tab2_back_on_step_1" value="<?=GetMessage('BACK_MESS')?>" style="display: none;" type="button">
	<input id="angerro_yadelivery_tab2_back_on_step_2" value="<?=GetMessage('BACK_MESS')?>" style="display: none;" type="button">
	<input id="angerro_yadelivery_tab2_save_description" class="adm-btn-save" type="button" value="<?=GetMessage('APPROVE_COLOR')?>" style="display: none;">
	<input id="angerro_yadelivery_tab2_send" class="adm-btn-save" type="button" value="<?=GetMessage('SAVE_MAP')?>" style="display: none;">
</div>

</td></tr>