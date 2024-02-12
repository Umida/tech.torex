<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

$this->setFrameMode(true);

if (empty($arResult["ALL_ITEMS"]))
    return;

CUtil::InitJSCore();


$menuBlockId = "catalog_menu_" . $this->randString();
$menu_tmp_key = 0;
$menu_list = $menu_types = [];
foreach ($arResult["MENU_STRUCTURE"] as $itemID => $arColumns):
    if (isset($arResult["ALL_ITEMS"][$itemID]["PARAMS"]["cities"]) && mb_stripos($arResult["ALL_ITEMS"][$itemID]["PARAMS"]["cities"],$arParams['FILTER_CITY']) === false)
        continue;
    $menu_key = array_search($arResult["ALL_ITEMS"][$itemID]["PARAMS"]["type"], $menu_types);
    if ($menu_key === FALSE) {
        $menu_types[$menu_tmp_key] = $arResult["ALL_ITEMS"][$itemID]["PARAMS"]["type"];
        $menu_key = $menu_tmp_key;
        ++$menu_tmp_key;
    }
    $menu_list_tmp = '<a href=';
    $menu_list_tmp .= $arResult["ALL_ITEMS"][$itemID]["LINK"];
    
   /* if ($curPage != SITE_DIR . "index.php"): 
    $menu_list_tmp .= 'rel="nofollow"';
    endif;*/
    
    $menu_list_tmp .= ' class="';
    if ($arResult["ALL_ITEMS"][$itemID]["SELECTED"]):
        $menu_list_tmp .= ' bx-active ';
    endif;

    if ($arResult["ALL_ITEMS"][$itemID]["PARAMS"]["class"]):
        $menu_list_tmp .= ' ' . $arResult["ALL_ITEMS"][$itemID]["PARAMS"]["class"] . ' ';
    endif;
    $menu_list_tmp .= 'menu_link">';

    if ($arResult["ALL_ITEMS"][$itemID]["PARAMS"]["svg"] && $arResult["ALL_ITEMS"][$itemID]["PARAMS"]["svg"] == 'Y') {
        $menu_list_tmp .= ' <i class="svg" data-svg="' . $arResult["ALL_ITEMS"][$itemID]["TEXT"] . '"></i>';
    } else {
        $menu_list_tmp .= '<span'.($arResult["ALL_ITEMS"][$itemID]["PARAMS"]["color"]?' style="color:'.$arResult["ALL_ITEMS"][$itemID]["PARAMS"]["color"].'"':'').'>' . $arResult["ALL_ITEMS"][$itemID]["TEXT"] . '</span>';
    }

    $menu_list_tmp .= '</a>';
    $menu_list[$menu_key][] = $menu_list_tmp;
    $menu_list_tmp = '';
endforeach;
?>
                        <ul class="bottom_menu">
                            <?
                            foreach ($menu_list[$menu_key] as $key => $menu_item): ?>
                                <li>
                                    <? echo $menu_item; ?>
                                </li>
                            <? endforeach; ?>
                        </ul>
       