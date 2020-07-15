<tr><td>
<!--view form-->
<form method="post" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=htmlspecialchars($mid)?>&amp;lang=<?echo LANG?>&amp;mid_menu=1">
	<select name="show_map_id_tab_3">
		<?
		$db = new angerro_yadelivery_db();
		$map_list = $db->get_map_list();
		?>
		<?foreach ($map_list as $map):?>
			<?
			$selected = false;
			if ((!isset($_POST['show_map_id']))&&($map['id']=='1')){
				$selected = true;
			}
			if ($_POST['show_map_id']==$map['id']){
				$selected = true;
			}
			?>
			<option value="<?=$map['id']?>" <?if ($selected) echo 'selected';?>>[<?=$map['id']?>] <?=$map['name']?></option>
		<?endforeach;?>
	</select>
	<input class="angerro_yadelivery_tab3_show" type="submit" value="<?=GetMessage('EDIT_MESS')?>">
	<input class="angerro_yadelivery_tab3_delete" type="submit" value="<?=GetMessage('DELETE_MESS')?>" disabled="disabled">
</form>

<div id="angerro_yadelivery_tab3_map_preview" map_id = "1">

</div>

<!--map editor-->

<!--info texts-->
<div class="angerro_yadelivery_text_info_tab3">
<?=GetMessage('STEP_1')?>
</div>
<div class="angerro_yadelivery_text_info_tab3">
<?=GetMessage('STEP_2')?>
</div>
<div class="angerro_yadelivery_text_info_tab3">
<?=GetMessage('STEP_3')?>
</div>
<div class="angerro_yadelivery_text_info_tab3">
<?=GetMessage('STEP_SUCCESS')?>
</div>
<div class="angerro_yadelivery_text_info_tab3">
<?=GetMessage('STEP_EDIT_GEOMETRY')?>
</div>	
<div class="angerro_yadelivery_text_info_tab3">
<?=GetMessage('CAN_DRAW_TEXT')?>
</div>	
<div class="angerro_yadelivery_text_info_tab3">
<?=GetMessage('ZONE_DELIVERY')?>
</div>
<div class="angerro_yadelivery_text_info_tab3">
<?=GetMessage('COLOR_DELIVERY_ZONE')?>
</div>
<div class="angerro_yadelivery_text_info_tab3">
<?=GetMessage('NAME_DELIVERY_ZONE')?>
</div>
<!--//info texts-->

<div id="angerro_yadelivery_tab3_hint" class="adm-info-message">
</div>

<div id="angerro_yadelivery_tab3_delivery_description">
	<div class="angerro_yadelivery_tab3_header"><?=GetMessage('COLOR_DELIVERY_ZONE')?></div>
	<div class="angerro_yadelivery_tab3_header"><?=GetMessage('NAME_DELIVERY_ZONE')?></div>
	<div class="angerro_yadelivery_tab3_clear"></div>
</div>

<div class="angerro_yadelivery_tab3_map_name_block">
	<div class="angerro_yadelivery_tab3_header"><?=GetMessage('NAME_MAP_W_DELIVERY_ZONE')?></div>
	<input id="angerro_yadelivery_tab3_delivery_map_name" type="text" value="<?=GetMessage('MAP_W_ZONES')?>">
	<div class="angerro_yadelivery_tab3_clear"></div>
</div>

<div class="angerro_yadelivery_tab3_buttons_block">
	<?=bitrix_sessid_post()?>
	<input id="angerro_yadelivery_tab3_create_description" class="adm-btn-save" type="button" value="<?=GetMessage('EDIT_COLORS')?>">
	<input id="angerro_yadelivery_tab3_back_on_step_1" value="<?=GetMessage('BACK_MESS')?>" style="display: none;" type="button">
	<input id="angerro_yadelivery_tab3_back_on_step_2" value="<?=GetMessage('BACK_MESS')?>" style="display: none;" type="button">
	<input id="angerro_yadelivery_tab3_save_description" class="adm-btn-save" type="button" value="<?=GetMessage('APPROVE_COLOR')?>" style="display: none;">
	<input id="angerro_yadelivery_tab3_send" class="adm-btn-save" type="button" value="<?=GetMessage('SAVE_MAP')?>" style="display: none;">
</div>

<!--//map editor-->

</td></tr>