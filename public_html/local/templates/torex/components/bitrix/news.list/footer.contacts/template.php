<?
$this->setFrameMode(true);
?>
<? foreach ($arResult["ITEMS"] as $ind => $arItem):?>
    <div>
    <?=$arItem['PREVIEW_TEXT']?>
    </div>
<?endforeach;?>
