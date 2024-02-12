<?php
error_reporting(E_ALL);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");

        if (is_file('../../../php-query-master/phpQuery/phpQuery.php')) {
            require_once('../../../php-query-master/phpQuery/phpQuery.php');
        }
        else { die('Required phpQueryLib is missing'); }


        $log = [];



$config = json_decode(file_get_contents('reviews.json'));
$config->iteration++;

$log[] = 'Iteration ' . $config->iteration;


       

    # Processing
        # 1 stage
            if( $config->stage == 1 ) {
              $domain = 'https://tech.torex.shop/';
                // ---
                    $log[] = 'STEP: Create [one] stage map';

                    # Load page
                        $page = 'libs/automatic/catalog/review/torex_review.php';
                        
                        $opts = array('http' => array('header' => 'Accept-Charset: UTF-8, *;q=0'));
                        $context = stream_context_create($opts);

                        $source = file_get_contents($domain.$page, false, $context);
                        $html = phpQuery::newDocument($source);
                    # ...

                    # Processing
                        $categories = [];
                        $pq_categories = pq($html)->find('.reviews-answer a');
                        $cnt_category = 0;

                        foreach ($pq_categories as $key => $pq_category) {
                            $categories[] = array(
                                'href' => pq($pq_category)->attr('href'),
                            );

                            $cnt_category++;
                        }

                        $config->categories = $categories;
                        $config->marker->one = array('all' => $cnt_category, 'current' => 0);

                        $log[] = 'Has been create ' . $cnt_category . ' elements';
                             
                         echo "<pre>";
                         print_r($categories);
                         echo "</pre>";
                        $config->stage++;
                        file_put_contents('reviews.json', json_encode($config));

                        $config_res['result'] = true;
                        $config_res['log'] = $log;

                       
                        exit;
                   
                
            }
        # ...

        # 2 stage
        
            if( $config->stage == 2 ) {
                 $domain = 'https://torex.ru';
                // ---
                    $log[] = 'STEP: Create [two] stage map';
                    $review_url = $domain.$config->categories[$config->marker->one->current]->href;
                    echo $review_url;
                    echo "<br>";
                    $review_code = str_replace( ["https://torex.ru/about/reviews/","/"], "", $review_url);
                    echo $review_code;
                    echo "<br>";
                    $idIBlock = 10;
                    $arFilter = array(
                        'IBLOCK_ID' => $idIBlock,
                        'XML_ID'=>$review_code
                );
               $review_obj = CIBlockElement::GetList(array(), $arFilter, false, [], []);
              if (intval($review_obj->SelectedRowsCount())==0){
                
                    # Load page
                        $opts = array('http' => array('header' => 'Accept-Charset: UTF-8, *;q=0'));
                        $context = stream_context_create($opts);
                      
                        $source = file_get_contents($review_url, false, $context);
                       
                        $html = phpQuery::newDocument($source);
                        $title = pq($html)->find('h2')->html();
                        $name = pq($html)->find('h3')->html();
                        
                        $review = pq($html)->find('.col-lg-9')->text();
                        $review = str_replace($title, "", $review);
                        if (!$review){
                            $review =' ';
                        }
                         $o_review_pos      = strripos($review, "Ответ производителя:");
                        if ($o_review_pos!==false){
                        $o_review = substr($review, $o_review_pos);   
                        
                        $review = str_replace($o_review, "", $review);
                        $o_review = str_replace("Ответ производителя:", "", $o_review);
                        }
                        
                        $image_array = pq($html)->find('.col-lg-9 img');
                        if ($image_array){
                            foreach ($image_array as $key => $image_element) {
                               $image_url = pq($image_element)->attr('src');
                            $image = downloadFile($domain.$image_url,$image_url);
                            if ($image){
                                $img_text="<div><img src=\"".str_replace("../../../../", "/", $image["location"])."\"></div>";
                                $review .=$img_text;  
                            }
                        }
                        }
                 
                       echo $review;
                        
                       
                         
                        $review_scores = pq($html)->find('.col-lg-3 div'); 
                        
                        
                        foreach ($review_scores as $key => $pq_score) {
                           
                                $score = pq($pq_score)->attr('data-reviews');
                                echo "<br/>";
                                $score_text = pq($pq_score)->text();
                                if ($score){
                                if ( $score_text =='Качество монтажа'){
                                    $score_montage = $score;
                                    echo "<br/>";
                                    echo "Качество монтажа = ".$score;
                                    echo "<br/>";
                                }
                                elseif ( $score_text =='Обслуживание в салоне'){
                                    $score_service = $score;
                                    echo "<br/>";
                                    echo "Обслуживание в салоне = ".$score;
                                    echo "<br/>";
                                }
                                elseif ( $score_text =='Качество двери'){
                                    $score_door = $score;
                                    echo "<br/>";
                                    echo "Качество двери = ".$score;
                                    echo "<br/>";
                                }
                                else{
                                    $score_full = $score;
                                    echo "<br/>";
                                    echo "Общяя оценка = ".$score;
                                    echo "<br/>";
                                }
                                }
                                else{
                                     
                                     
                                    if ( strripos($score_text, "Город:") !== false){    
                                        $review_city_pos      = strripos($score_text, "Город:");
                                        $review_city = substr($score_text, $review_city_pos);   
                                        $review_city = str_replace( "Город:", "", $review_city);
                                        echo $review_city;
                                        echo "<br/>";
                                    }
                                    elseif ( strripos($score_text, "Салон:") !== false){    
                                        $review_salon_pos      = strripos($score_text, "Салон:");
                                        $review_salon = substr($score_text, $review_salon_pos);   
                                        $review_salon = str_replace( "Салон:", "", $review_salon);
                                        echo $review_salon;
                                        echo "<br/>";
                                    }
                                   elseif ( strripos($score_text, "Отзывы:") !== false){    
                                        $review_seria_pos      = strripos($score_text, "Отзывы:");
                                        $review_seria = substr($score_text, $review_seria_pos);   
                                        $review_seria = str_replace( "Отзывы:", "", $review_seria);
                                        echo $review_seria;
                                        echo "<br/>";
                                    }
                                     elseif ( strripos($score_text, "Срок исполнения:") !== false){    
                                        $review_term_pos      = strripos($score_text, "Срок исполнения:");
                                        $review_term = substr($score_text, $review_term_pos);   
                                        $review_term = str_replace( "Срок исполнения:", "", $review_term);
                                        echo $review_term;
                                        echo "<br/>";
                                    }
                                         elseif ( strripos($score_text, "Получен:") !== false){    
                                        $review_data_pos      = strripos($score_text, "Получен:");
                                        $review_data = substr($score_text, $review_data_pos);   
                                        $review_data = str_replace( "Получен:", "", $review_data);
                                        echo $review_data;
                                        echo "<br/>";
                                    }
                                   
                                }
                                
                            
                        }
                        $review_seo = $review_code;
                        $el = new CIBlockElement;
                        echo $review;
                        $arLoadProductArray = array(
                            "MODIFIED_BY" => 1,
                            "IBLOCK_ID" => 10,
                            "CODE" => $review_seo,
                            "NAME" => $title,
                            "ACTIVE" => "Y",
                            "PREVIEW_TEXT" => $o_review,
                            "PREVIEW_TEXT_TYPE" => "html",
                            "DETAIL_TEXT" => $review,
                            "DETAIL_TEXT_TYPE" => "html",
                            "XML_ID" => $review_code,
                        );
                        
                   
                        if ($ID = $el->Add($arLoadProductArray)){
                            echo 'id '.$ID;
                            $new_props = [];
                            if ($review_seria){
                                $model_feature = getModel(["IBLOCK_ID" => 9, 'PROPERTY_240_VALUE' => strtolower(trim($review_seria))]);
                                if ($model_feature){
                                    $new_props['models']=$model_feature;
                                }
                             $new_props["score_montage"] = $score_montage;
                             $new_props["score_door"] = $score_door;
                             
                             $new_props["score_service"] = $score_service;
                             $new_props["model"] = $review_seria;
                             $new_props["period"] = $review_term; 
                             
                             $new_props["address"] = $review_salon;
                             $new_props["client"] = $name;
                             $new_props["score"] = $score_full; 
                             $new_props["score_date"] = $review_data; 
                             
                             
                    
                        CIBlockElement::SetPropertyValuesEx($ID, false, $new_props);
                        }
                        else
                        echo "Error: ".$el->LAST_ERROR;
              }
              else{
                   while ($review = $review_obj->GetNextElement()) {
                      
                      
                       
                       }
                   }
              }
                      
                            if($config->marker->one->current + 1 >= $config->marker->one->all){
                                $config->stage++;
                            }
                            else {
                                $config->marker->one->current++;
                            }
                    # ...

                    # Result
                        file_put_contents('reviews.json', json_encode($config));

                $config_res['result'] = true;
                $config_res['log'] = $log;
                $config_res['config'] = $config;
                echo json_encode($config_res);
                        exit;
                    # ...
                // ---
                
            }
            
            function getModel($modelFilter){
    if (!is_array($modelFilter))
        return false;
    $result=[];
    $model_obj = CIBlockElement::GetList(array(), $modelFilter, false, [], []);
    if ($model_obj->SelectedRowsCount() > 0) {
        while ($model = $model_obj->GetNextElement()) {
            $modelFields = $model->GetFields();
            $result[]=$modelFields["ID"];
            }
        } 
    return $result;
    
}
            /*
             # 3 stage
            if( $config->stage == 3 ) {
                $idIBlock = 2;
                $arFilter = array(
                    'IBLOCK_ID' => $idIBlock,
                
                );
                $rsProperty = CIBlockProperty::GetList(
                    array(),
                    $arFilter
                );
                $filters = [];
                while ($element = $rsProperty->Fetch()) {
                    $filters[$element["CODE"]] = [
                        "PROPERTY_TYPE" => $element["PROPERTY_TYPE"],
                        "NAME" => $element["NAME"],
                        "HINT" => $element["HINT"]
                    ];
                }
          
                
                $log[] = 'STEP: Create [three] parsing';

                # Processing
                $opts = array('http' => array(
                    "header" => "Accept-Charset: UTF-8, *;q=0\r\n" .
                        "Cookie: BX_USER_ID=a56558cac586d0dd260c0eab4d4699c3; _gcl_au=1.1.1300718587.1614579606; _ym_uid=1614579606217820058; _ym_d=1614579606; _ga=GA1.2.1457822494.1614579606; _fbp=fb.1.1614579605960.522145161; privacy-policy=1; marquiz__url_params={}; _gid=GA1.2.664976448.1615274354; PHPSESSID=g922ft7ls5q506bjptq2j4f4d5; _dveromat_first_site_open=1615354032044; _ym_isad=1; PHPSESSID=c4acbe0dbb9ba2ea2f4b8252ab66621a; _ym_visorc=w; _cmg_csstWnDLN=1615361870; _comagic_idWnDLN=4119242956.6351560764.1615361870; CITYCODE=moskva; CITY=%D0%9C%D0%BE%D1%81%D0%BA%D0%B2%D0%B0; BITRIX_SM_PK=page_region_moskva\r\n"
                ));
                $context = stream_context_create($opts);

                if (get_http_response_code($domain . $config->categories[$config->marker->two->current]->products[$config->marker->two->part]->href) == "200") {
                    $source = file_get_contents($domain . $config->categories[$config->marker->two->current]->products[$config->marker->two->part]->href, false, $context);
                    $html = phpQuery::newDocument($source);

                    # Update parts
                    if ($config->marker->two->part == 0) {
                        $config->marker->two->parts = count($config->categories[$config->marker->two->current]->products);
                    }
                    $product_id = pq($html)->find('meta[itemprop="productID]')->attr('content');
                    $product_title = pq($html)->find('.represent meta[itemprop="name"]')->attr('content');
                    $product_preview = pq($html)->find('.represent meta[itemprop="description"]')->attr('content');
                    $product_specifications = $product_lock_specifications = '';
                    $product_specifications = pq($html)->find('.card-detail__specification .tab-block_inside[data-tab="doortab"] .specification-line__lists')->html();
                    $product_lock_specifications = pq($html)->find('.card-detail__specification .tab-block_inside[data-tab="locktab"] .specification-line__lists')->html();

                    $product_specifications_arr =[];
                    foreach (pq($product_specifications)->find('.specification-line') as $key => $pq_product) {
                        $products_title = trim(pq($pq_product)->find('.specification-line_left')->text());
                        $products_value = trim(pq($pq_product)->find('.specification-line_right')->text());
                        $prop_key = - 1;
                        $product_specifications_arr[$products_title] =$products_value;
                    }
                    $product_specifications_lock_arr =[];
                    foreach (pq($product_lock_specifications)->find('.specification-line') as $key => $pq_product) {
                        $products_title = trim(pq($pq_product)->find('.specification-line_left')->text());
                        $products_value = trim(pq($pq_product)->find('.specification-line_right')->text());
                        $prop_key = - 1;
                        $product_specifications_lock_arr[$products_title] =$products_value;
                    }
                        
                    $seria_id = $model_id = 0;
                    $model_name = "";
                          echo $product_id;   
                             
                    #get model
                    $modelFilter = array("IBLOCK_ID" => 33);
                    $model_obj = CIBlockElement::GetList(array(), $modelFilter, false, [], []);
                    while ($model = $model_obj->GetNextElement()) {
                            $modelFields = $model->GetFields();
                            
                            if (strtoupper($modelFields["NAME"]) == strtoupper($product_specifications_arr['Модель'])){
                                $text_preview = $modelFields['PREVIEW_TEXT'];
                                $text_detail = $modelFields['DETAIL_TEXT'];
                                $model_name = $modelFields["NAME"];
                                $model_id = $modelFields['ID'];
                                $modelProps = $model->GetProperties();
                                $seria_id = $modelProps['parent']["VALUE"];
                                
                                
                                if (!$modelProps["filled"]["VALUE"]){
                                    
                                    echo "Не заполнено";    
                                    $new_props =[];
                                    foreach ($modelProps as $modelProp){
                                    if ($product_specifications_arr[$modelProp["NAME"]]){
                                        $new_props[$modelProp["CODE"]] = $product_specifications_arr[$modelProp["NAME"]];
                                    }
                                    elseif ($product_specifications_lock_arr[$modelProp["NAME"]]){
                                        $new_props[$modelProp["CODE"]] = $product_specifications_lock_arr[$modelProp["NAME"]];
                                    }
                                    }
                                    foreach(pq($html)->find('.document-wrap a') as $element){
                                        $image = donwloadFile(pq($element)->attr('href'));
                                        $main_file_array = CFile::MakeFileArray($image['location']);
                                        $main_file_array["description"] = trim(pq($element)->text());
                                        $new_props["serts"][] = $main_file_array;
                                    }
                                    $new_props["filled"] = 4467;
                                    CIBlockElement::SetPropertyValuesEx($modelFields["ID"], false, $new_props);
                                }
                                break;
                            }
                            
                        }
                         $color_outside = $product_specifications_arr['Цвет внешней отделки'];
                         if ($color_outside){
                             $hlbl = 5; 
                             $hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch(); 
                             $entity = HL\HighloadBlockTable::compileEntity($hlblock); 
                             $entity_data_class = $entity->getDataClass(); 
                             $rsData = $entity_data_class::getList(array(
                                 "select" => array("*"),
                                 "order" => array("ID" => "ASC"),
                                 "filter" => array("UF_COLOR_SYNONYMS"=>[mb_strtolower($color_outside)])  
                            ));  
                            
                            while($arData = $rsData->Fetch()){
                                $color_outside_code = $arData["UF_XML_ID"];
                                $color_outside_id = $arData["ID"];
                                $color_outside_name = $arData["UF_NAME"];
                               
                            }
                        }
                        $design_outside = $product_specifications_arr['Рисунок внешней отделки'];
                        if ($design_outside){
                            $hlbl = 6; 
                            $hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch(); 
                            $entity = HL\HighloadBlockTable::compileEntity($hlblock); 
                            $entity_data_class = $entity->getDataClass(); 
                            $rsData = $entity_data_class::getList(array(
                                "select" => array("*"),
                                "order" => array("ID" => "ASC"),
                                "filter" => array("UF_PIC_SYNONYMS"=>[mb_strtolower($design_outside)])  
                            ));  
                            while($arData = $rsData->Fetch()){
                                
                                $design_outside_code = $arData["UF_XML_ID"];
                                $design_outside_id = $arData["ID"];
                                $design_outside_name = $arData["UF_NAME"];
                            }
                            }
                            $color_inside = $product_specifications_arr['Цвет внутренней отделки'];
                            if ($color_inside){
                                $hlbl = 5; 
                                $hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch(); 
                                $entity = HL\HighloadBlockTable::compileEntity($hlblock); 
                                $entity_data_class = $entity->getDataClass(); 
                                $rsData = $entity_data_class::getList(array(
                                    "select" => array("*"),
                                    "order" => array("ID" => "ASC"),
                                    "filter" => array("UF_COLOR_SYNONYMS"=>[mb_strtolower($color_inside)])
                                ));  
                         
                                while($arData = $rsData->Fetch()){
                                    $color_inside_code = $arData["UF_XML_ID"];
                                    $color_inside_id = $arData["ID"];
                                    $color_inside_name = $arData["UF_NAME"];
                                   
                                }
                            }
                            $design_inside =$product_specifications_arr['Рисунок внутренней отделки'];
                            if ($design_inside){
                                $hlbl = 6; 
                                $hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch(); 
                                $entity = HL\HighloadBlockTable::compileEntity($hlblock); 
                                $entity_data_class = $entity->getDataClass(); 
                                $rsData = $entity_data_class::getList(array(
                                    "select" => array("*"),
                                    "order" => array("ID" => "ASC"),
                                    "filter" => array("UF_PIC_SYNONYMS"=>[mb_strtolower($design_inside)])  
                                ));  
                                while($arData = $rsData->Fetch()){
                                    $design_inside_code = $arData["UF_XML_ID"];
                                    $design_inside_id = $arData["ID"];
                                    $design_inside_name = $arData["UF_NAME"];
                                }
                            }
                            $fitting = $product_specifications_arr['Цвет фурнитуры'];
                            if ($fitting){
                                $hlbl = 7;
                                $hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch(); 
                                $entity = HL\HighloadBlockTable::compileEntity($hlblock); 
                                $entity_data_class = $entity->getDataClass(); 
                                $rsData = $entity_data_class::getList(array(
                                    "select" => array("*"),
                                    "order" => array("ID" => "ASC"),
                                    "filter" => array("UF_SYNONYMS"=>mb_strtolower($fitting))  
                                ));  
                                while($arData = $rsData->Fetch()){
                                    $fitting_code = $arData["UF_XML_ID"];
                                    $fitting_id = $arData["ID"];
                                    $fitting_name = $arData["UF_NAME"];
                                }
                                
                            }
                                 $trim_color = $product_specifications_arr['Цвет наличника'];
                            if ($trim_color){
                                $hlbl = 5;
                                $hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch(); 
                                $entity = HL\HighloadBlockTable::compileEntity($hlblock); 
                                $entity_data_class = $entity->getDataClass(); 
                                $rsData = $entity_data_class::getList(array(
                                    "select" => array("*"),
                                    "order" => array("ID" => "ASC"),
                                    "filter" => array("UF_COLOR_SYNONYMS"=>mb_strtolower($trim_color))  
                                ));  
                                while($arData = $rsData->Fetch()){
                                    $trim_color_code = $arData["UF_XML_ID"];
                                    $trim_color_id = $arData["ID"];
                                    $trim_color_name = $arData["UF_NAME"];
                                }
                            }
                            
                            
                        
                   
                   $prodFilter = array("IBLOCK_ID" => 2, "XML_ID" => $product_id);
                    $new_props = [];
                    $prod_res = CIBlockElement::GetList(array(), $prodFilter, false, [], []);
                    
                    if ($prod_res->SelectedRowsCount() > 0) {
              
                        while ($prod_obj = $prod_res->GetNextElement()) {
                            $prodFields = $prod_obj->GetFields();
                            $prodProps = $prod_obj->GetProperties();
                           
                            if ($prodProps['color_outside_id']['VALUE'] == $color_outside_code &&
                            $prodProps['design_outside_id']['VALUE'] == $design_outside_code &&
                            $prodProps['color_inside_id']['VALUE'] == $color_inside_code &&
                            $prodProps['design_inside_id']['VALUE'] == $design_inside_code &&
                            $prodProps['fitting_id']['VALUE'] == $fitting_code
                            ){
                                echo $prodProps['trim_color_id']['VALUE'] .' '. $trim_color_code;
                                echo $ID = $prodFields["ID"];    
                            }
                            else{
                                 echo $color_outside.' 1 '.$prodProps['color_outside_id']['VALUE'].' 2 '.$color_outside_code;
                            echo "<br/>";
                            echo $design_outside.' 1 '.$prodProps['design_outside_id']['VALUE'].' 2 '.$design_outside_code;
                            echo "<br/>";
                            echo $color_inside.' 1 '.$prodProps['color_inside_id']['VALUE'].' 2 '.$color_inside_code;
                            echo "<br/>";
                            echo $design_inside.' 1 '.$prodProps['design_inside_id']['VALUE'].' 2 '.$design_inside_code;
                            echo "<br/>";
                            echo $fitting.' 1 '.$prodProps['fitting_id']['VALUE'].' 2 '.$fitting_code;
                              echo "<br/>";
                                echo $prodFields["ID"].' Не та позиция';
                            }
                             echo "<br/>";
                            
                            echo "<br/>";
                            echo $fitting.' '.$prodProps['fitting_id']['VALUE'].' '.$fitting_name;
                            echo "<br/>";
                            echo $trim_color.' '.$prodProps['trim_color_id']['VALUE'].' '.$trim_color_name;
                              echo "<br/>";
                        }
                    }
                      
                                if (!$ID) {
                                              $product_seo = str_replace(" ","-",$model_name)."-".generateRandomString(8);
                        $product_search = $product_title . ' ' . $product_preview;
                        $el = new CIBlockElement;

                        $arLoadProductArray = array(
                            "MODIFIED_BY" => 1,
                            "IBLOCK_SECTION_ID" => $seria_id,
                            "IBLOCK_ID" => 2,
                            "CODE" => $product_seo,
                            "NAME" => $product_title,
                            "ACTIVE" => "Y",
                            "PREVIEW_TEXT" => $text_preview,
                            "DETAIL_TEXT" => $text_detail,
                             "PREVIEW_TEXT_TYPE" => "html",
                            "DETAIL_TEXT_TYPE" => "html",
                            "SEARCHABLE_CONTENT" => $product_search,
                            "XML_ID" => $product_id,
                        );
                   
                        if ($ID = $el->Add($arLoadProductArray))
                            echo  'Success insert b_iblock_element ' . $product_title;
                        else
                            echo 'Error insert b_iblock_element' . $db->error;
                            
                        $new_props["sku"] = strtoupper(generateRandomString(8));
                        
                        $new_props["color_outside_id"] = $color_outside_code;
                        $new_props["design_outside_id"] = $design_outside_code;
                        $new_props["color_inside_id"] = $color_inside_code;
                        $new_props["design_inside_id"] = $design_inside_code;
                        $new_props["fitting_id"] = $fitting_code;
                        $new_props["trim_color_id"] = $trim_color_code;
                        
                        echo 'Создали новую позицию';

                    }
                     echo "<br/>";
                    echo $ID;
                    echo "<br/>";
                    $product_price = floatval(pq($html)->find('[itemprop="offers"] meta[itemprop="price"]')->attr('content'));
                        

                        foreach ($filters as $filter_id => $filter_info) {
                            if ($filter_id == 'image_outside' && $new_props["sku"]) {
                                $image_url = pq($html)->find('.card-product__represent .card-product__outside .card-product__picture a img')->attr('src');
                                $image = donwloadFile($image_url);
                                $main_file_array = CFile::MakeFileArray($image['location']);
                                $new_props[$filter_id] = $main_file_array;
                            } elseif ($filter_id == 'image_inside' && $new_props["sku"]) {
                                $image_url = pq($html)->find('.card-product__represent .card-product__inside .card-product__picture a img')->attr('src');
                                $image = donwloadFile($image_url);
                                $main_file_array = CFile::MakeFileArray($image['location']);
                                $new_props[$filter_id] = $main_file_array;
                            }
                            elseif ($filter_id == 'model'){
                                $new_props["model"] = $model_id;
                            }
                            else{
                                $product_specifications_val = '';
                                if ($product_specifications_lock_arr[$filter_info["NAME"]]){
                                    $product_specifications_val = $product_specifications_lock_arr[$filter_info["NAME"]];
                                }
                                elseif ($product_specifications_lock_arr[$filter_info["HINT"]]){
                                    $product_specifications_val = $product_specifications_lock_arr[$filter_info["HINT"]];
                                }
                                elseif ($product_specifications_arr[$filter_info["NAME"]]){
                                    $product_specifications_val = $product_specifications_arr[$filter_info["NAME"]];
                                }
                                elseif ($product_specifications_arr[$filter_info["HINT"]]){
                                    $product_specifications_val = $product_specifications_arr[$filter_info["HINT"]];
                                }
                                if ($filter_info["PROPERTY_TYPE"] == 'L'){
                                    $property_enums = CIBlockPropertyEnum::GetList([],Array("IBLOCK_ID"=>2, "CODE"=>$filter_id));
                                    while ($enum_fields = $property_enums->GetNext()) {
                                        if ($enum_fields["VALUE"] == $product_specifications_val){
                                            $new_props[$filter_id] = $enum_fields["ID"] ;
                                        }

                                    }
                                }
                                else{
                                    if ($product_specifications_val)
                                        $new_props[$filter_id] = $product_specifications_val;
                                }

                            }
                        }
                        $new_props["integration"] = 3963;
                        $new_props["donor_code"] = $product_id;
                        $new_props["city"] = [745,747,750,746,749];
                        print_r($new_props);
                        CIBlockElement::SetPropertyValuesEx($ID, false, $new_props);

                        $arFields_new = array("ACTIVE"=>"Y","QUANTITY"=>1);
                        $obEl = new CIBlockElement();
                        $boolResult = $obEl->Update($ID,$arFields_new);
                        $arFieldsStoreAmount = Array(
                            "PRODUCT_ID" => $ID,
                            "STORE_ID" => 2,
                            "AMOUNT" => 1
                        );
                        $amount = \CCatalogStoreProduct::Add($arFieldsStoreAmount);
                        if ($new_props["sku"]){
                            echo '<br/>Цены ';
                             setprice($ID,1,$product_price);
                        
                           $opts_kaluga = array('http' => array(
        "header" => "Accept-Charset: UTF-8, *;q=0\r\n" .
            "Cookie: BX_USER_ID=f0572d9bd63f640fca583267d5a8fa43; _ga=GA1.2.783765956.1625486705; _ym_uid=162548670592192337; privacy-policy=1; _gcl_au=1.1.1866649110.1641383027; _ym_d=1641383028; PHPSESSID=37s9s2fk2t4n5q08d5n773j5h2; promosys_opened_params=a%3A0%3A%7B%7D; _dveromat_first_site_open=1643356699396; _gid=GA1.2.461249296.1643356705; _ym_visorc=w; _ym_isad=1; marquiz__url_params={}; CITYCODE=kaluga; CITY=%D0%9A%D0%B0%D0%BB%D1%83%D0%B3%D0%B0; BITRIX_SM_PK=page_region_kaluga; _dc_gtm_UA-26925054-1=1; _gat_UA-26925054-6=1; _gat_UA-26925054-1=1\r\n"
    ));
                $context_kaluga = stream_context_create($opts_kaluga);
                $source_kaluga = file_get_contents($domain . $config->categories[$config->marker->two->current]->products[$config->marker->two->part]->href, false, $context_kaluga);
                $html_kaluga = phpQuery::newDocument($source_kaluga);
                $kaluga_price = floatval(pq($html_kaluga)->find('[itemprop="offers"] meta[itemprop="price"]')->attr('content'));
                setprice($ID,3,$kaluga_price);
                        
                        
                        $opts_spb = array('http' => array(
        "header" => "Accept-Charset: UTF-8, *;q=0\r\n" .
            "Cookie: BX_USER_ID=f0572d9bd63f640fca583267d5a8fa43; _ga=GA1.2.783765956.1625486705; _ym_uid=162548670592192337; privacy-policy=1; _gcl_au=1.1.1866649110.1641383027; _ym_d=1641383028; PHPSESSID=37s9s2fk2t4n5q08d5n773j5h2; promosys_opened_params=a%3A0%3A%7B%7D; _dveromat_first_site_open=1643356699396; _gid=GA1.2.461249296.1643356705; _ym_visorc=w; _ym_isad=1; marquiz__url_params={}; CITYCODE=sankt-peterburg; CITY=%D0%A1%D0%B0%D0%BD%D0%BA%D1%82-%D0%9F%D0%B5%D1%82%D0%B5%D1%80%D0%B1%D1%83%D1%80%D0%B3; BITRIX_SM_PK=page_region_sankt-peterburg; _dc_gtm_UA-26925054-1=1; _gat_UA-26925054-6=1; _gat_UA-26925054-1=1\r\n"
    ));
                $context_spb = stream_context_create($opts_spb);
                $source_spb = file_get_contents($domain . $config->categories[$config->marker->two->current]->products[$config->marker->two->part]->href, false, $context_spb);
                $html_spb = phpQuery::newDocument($source_spb);
                $spb_price = floatval(pq($html_spb)->find('[itemprop="offers"] meta[itemprop="price"]')->attr('content'));
                setprice($ID,4,$spb_price);
                
                  $opts_arh = array('http' => array(
        "header" => "Accept-Charset: UTF-8, *;q=0\r\n" .
            "Cookie: BX_USER_ID=f0572d9bd63f640fca583267d5a8fa43; _ga=GA1.2.783765956.1625486705; _ym_d=1625486705; _ym_uid=162548670592192337; _fbp=fb.1.1625486705555.1328147552; privacy-policy=1; _gcl_au=1.1.2014156727.1633598043; _gid=GA1.2.994941324.1633598043; _ym_isad=1; promosys_opened_params=a%3A0%3A%7B%7D; PHPSESSID=qn519fsv1kcujbdfku77cfjt73; _dveromat_first_site_open=1633618057732; _ym_visorc=w; marquiz__url_params={}; _dc_gtm_UA-26925054-1=1; _gat_UA-26925054-6=1; _gat_UA-26925054-1=1; CITYCODE=arkhangelsk; CITY=%D0%90%D1%80%D1%85%D0%B0%D0%BD%D0%B3%D0%B5%D0%BB%D1%8C%D1%81%D0%BA; BITRIX_SM_PK=page_region_arhangelsk\r\n"
    ));
                $context_arh = stream_context_create($opts_arh);
                $source_arh = file_get_contents($domain . $config->categories[$config->marker->two->current]->products[$config->marker->two->part]->href, false, $context_arh);
                $html_arh = phpQuery::newDocument($source_arh);
                $arh_price = floatval(pq($html_arh)->find('[itemprop="offers"] meta[itemprop="price"]')->attr('content'));
                setprice($ID,5,$arh_price);
                
                
                        $opts_kgd =  array('http' => array(
        "header" => "Accept-Charset: UTF-8, *;q=0\r\n" .
            "Cookie: BX_USER_ID=f0572d9bd63f640fca583267d5a8fa43; _ga=GA1.2.783765956.1625486705; _ym_uid=162548670592192337; privacy-policy=1; _gcl_au=1.1.1866649110.1641383027; _ym_d=1641383028; PHPSESSID=37s9s2fk2t4n5q08d5n773j5h2; promosys_opened_params=a%3A0%3A%7B%7D; _dveromat_first_site_open=1643356699396; _gid=GA1.2.461249296.1643356705; _ym_visorc=w; _ym_isad=1; CITYCODE=kaliningrad; CITY=%D0%9A%D0%B0%D0%BB%D0%B8%D0%BD%D0%B8%D0%BD%D0%B3%D1%80%D0%B0%D0%B4; BITRIX_SM_PK=page_region_kaliningrad; _gat_UA-26925054-1=1; _dc_gtm_UA-26925054-1=1; _gat_UA-26925054-6=1; marquiz__url_params={}\r\n"
    ));
                $context_kgd = stream_context_create($opts_kgd);
                $source_kgd = file_get_contents($domain . $config->categories[$config->marker->two->current]->products[$config->marker->two->part]->href, false, $context_kgd);
                $html_kgd = phpQuery::newDocument($source_kgd);
                $kgd_price = floatval(pq($html_kgd)->find('[itemprop="offers"] meta[itemprop="price"]')->attr('content'));
                setprice($ID,6,$kgd_price);
                        }
                }      
                    

                    # End
                    echo "<br/>";
                    echo $config->marker->two->part + 1;
                    echo " ";
                    echo $config->marker->two->parts;
                    if ($config->marker->two->part + 1 >= $config->marker->two->parts) {
                        if ($config->marker->two->current + 1 >= $config->marker->two->all) {
                            $config->stage++;
                        } else {
                            $config->marker->two->current++;

                            $config->marker->two->parts = 0;
                            $config->marker->two->part = 0;
                        }
                    } else {
                        $config->marker->two->part++;
                    }

                    # ...

                    # Result
                    file_put_contents('catalog.json', json_encode($config));

                    $config_res['result'] = true;
                    $config_res['log'] = $log;
                    $config_res['config'] = $config;

                    
                   
                    exit;
                    # ...
                    // ---
                
                # ...
                
            }
*/
      
            function setprice($prod_id,$price_id,$price){
                        $arFields_price = array(
                            "PRODUCT_ID" => $prod_id,
                            "CATALOG_GROUP_ID" => $price_id,
                            "PRICE" => $price,
                            "CURRENCY" => "RUB"
                        );

                        $res_price = CPrice::GetList(
                            array(),
                            array(
                                "PRODUCT_ID" => $prod_id,
                                "CATALOG_GROUP_ID" => $price_id
                            )
                        );

                        if ($arr = $res_price->Fetch()) {
                            CPrice::Update($arr["ID"], $arFields_price);
                        } else {

                            CPrice::Add($arFields_price);
                            CCatalogProduct::Add(["ID" => $prod_id]);

                        }   
            }
            # Functions
        function get_http_response_code($url) {
            $headers = get_headers($url);
            return substr($headers[0], 9, 3);
        }

        function generateRandomString($length) {
            $bytes = random_bytes($length);
            return bin2hex($bytes);
        }
    
        function downloadFile($url,$file_path) {
            $info = pathinfo($file_path);
            $ch = curl_init($url);
              
            // Init
            $file_name = generateRandomString(10).'.'.$info['extension'];
            $file_name_original = basename($url);

            $dir = $info['dirname'];
            
            if (!file_exists('../../../..'.$dir)) {
                mkdir('../../../..'.$dir, 0777, true);
            }
            $location = '../../../..'.$file_path;
    
            // Open file  
            $fp = fopen($location, 'wb'); 
              
            curl_setopt($ch, CURLOPT_FILE, $fp); 
            curl_setopt($ch, CURLOPT_HEADER, 0); 
            curl_exec($ch); 
            curl_close($ch); 
              
            fclose($fp);

            // Create response
            $info = pathinfo($location);

            if( exif_imagetype($location) ){
                $params = getimagesize($location);

                $height = $params[1];
                $width = $params[0];
            }
            else {
                $height = 0;
                $width = 0;
            }

            $size = filesize($location);
            $type = mime_content_type($location);

            return array(
                'location' => $location,
                'subdir' => $path,
                'name' => $file_name,
                'original' => addslashes($file_name_original),
                'height' => $height,
                'width' => $width,
                'size' => $size,
                'type' => $type
            );
        }



    # ...
?>