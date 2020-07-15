<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if (method_exists($this, 'setFrameMode')) {
    $this->setFrameMode(true);
}
$yaMetrika = ($arParams['CMSG_YANDEX_METRIKA_ACTIVE'] && strlen($arParams['CMSG_YANDEX_METRIKA_COUNTER'])) ? $arParams['CMSG_YANDEX_METRIKA_COUNTER'] : false;

ob_start(); ?>
<? if ($arParams["CMSG_POSITION"] == 'bottom' || $arParams["CMSG_POSITION"] == 'top') : ?>
    <div class="ctweb-quick-message <?= $arResult["CLASS"] ?>">
        <table>
            <tbody>
            <tr>
                <? foreach ($arResult["ITEMS"] as $key => $item) : ?>
                    <td style="width: <?= (100 / sizeof($arResult["ITEMS"])) ?>px; background: <?= $item['bg_color'] ?>;">
                        <a href="<?= $item["link"] ?>"
                            <?= (strlen($item["target"])) ? ' target="' . $item["target"] . '"' : '' ?>
                            <?= ($yaMetrika && strlen(trim($item['goal']))) ? ('onclick=\''.$yaMetrika.'.reachGoal("'.trim($item['goal']).'");\'') : '' ?>
                        >
                            <i class="cqm-<?= strtolower($key); ?>" style="color: <?= $item['color'] ?>"></i>
                        </a>
                    </td>
                <? endforeach; ?>
            </tr>
            </tbody>
        </table>
    </div>
<? else: ?>
    <div class="ctweb-quick-message vertical <?= $arResult["CLASS"] ?>" style="margin-top: -<?= ((sizeof($arResult["ITEMS"]) * 48) / 2) ?>px;">
        <a href="javascript:void(0);" class="cqm-toggle" onclick="toggleBar();"><i class="cqm-comments"></i></a>
        <ul>
            <? foreach ($arResult["ITEMS"] as $key => $item) : ?>
                <li style="background: <?= $item['bg_color'] ?>;">
                    <a href="<?= $item["link"] ?>"
                        <?= (strlen($item["target"])) ? ' target="' . $item["target"] . '"' : '' ?>
                        <?= ($yaMetrika && strlen(trim($item['goal']))) ? ('onclick=\''.$yaMetrika.'.reachGoal("'.trim($item['goal']).'");\'') : '' ?>
                    >
                        <i class="cqm-<?= strtolower($key); ?>" style="color: <?= $item['color'] ?>"></i>
                    </a>
                </li>
            <? endforeach; ?>
        </ul>
    </div>
<? endif;

$moduleTemplate = ob_get_clean();
$moduleTemplate = json_encode($moduleTemplate);

$script = <<<TPL
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        var div = document.createElement('div');
        div.innerHTML = {$moduleTemplate};
        document.body.appendChild(div);
    });
</script>
TPL;

$APPLICATION->AddHeadString($script);
?>