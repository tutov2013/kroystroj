<tr><td>
<!--вывод формы редактирования настроек модуля-->
<form method="post" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=htmlspecialchars($mid)?>&amp;lang=<?echo LANG?>&amp;mid_menu=1">
    <div>
        <div class="angerro_yadelivery_tab0_header"><?=GetMessage('API_KEY')?></div>
        <input id="angerro_yadelivery_tab0_yandex_key" name="opt[api_key]" type="text" value="<?=COption::GetOptionString($sModuleId, 'api_key')?>">
        <div class="angerro_yadelivery_tab0_clear"></div>
    </div>
    <?=bitrix_sessid_post()?>
    <input id="angerro_yadelivery_tab0_send" class="adm-btn-save" type="submit" value="<?=GetMessage('SAVE_MODULE_SETTINGS')?>">
</form>
</td></tr>