<tr><td>
<!--вывод формы просмотра карты с зонами доставки-->
<form method="post" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=htmlspecialchars($mid)?>&amp;lang=<?echo LANG?>&amp;mid_menu=1">
	<select name="show_map_id">
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
	<input class="angerro_yadelivery_tab1_show" type="submit" value="<?=GetMessage('VIEW_MAP')?>">
</form>

<div id="angerro_yadelivery_tab1_map_preview">

</div>
</td></tr>