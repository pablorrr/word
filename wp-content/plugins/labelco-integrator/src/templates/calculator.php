<?php $labelco = get_labelco(); 
   /*
* Dostepnosc do prywatnych skladnikow klasy Material Service - description
*/

//https://stackoverflow.com/questions/1646270/oo-php-protected-properties-not-available-in-foreach-loop
//funkcja pobiera wszytskie skladniki obiektu - rowniez prytwatne wraz z kluczami oraz konwertuje obiekt na tablice

function get_object_vars_all( $obj ) {
    $objArr = substr( str_replace( get_class($obj)."::__set_state(","",var_export($obj,true))  ,0,-1 );
    eval("\$values = $objArr;");
    
    return $values;
}
?>

<table class="variations" cellspacing="0">
    <tbody class="div-variations-flex">
    
    <tr class="div-item-variation material">
        <td class="label">
            <div class="div-flex-label-info">
                <label class="tb-variation" for="pa_material">Materiał</label>
            </div>

        </td>
        <td class="value">
       <select id="labelco_calc_material" class="variation-select w-select labelco_calc_select" name="labelco_calc_material"
                    data-attribute_name="attribute_pa_material" data-show_option_none="yes">
                <option value="">Wybierz opcję</option>
                
                 <?php foreach ( $labelco->getMaterialService()-> getMaterials() as  $objMaterial ) : ?>

                    <?php
                                 //objMaterial convert to arrayMaterial to get acces private properties 
                                $arrayMaterial =  get_object_vars_all( $objMaterial); 
                                //TODO find other chars from ASCII/ UTF-8
                                $find = array ( "³","¹","¿" );
                                $change = array("ł", "ą","ż" );
                                $descMaterial = str_replace( $find, $change,  utf8_encode( $arrayMaterial ['description'] )  );
                               ?>
                    <option value="<?php echo esc_attr($descMaterial ); ?>"><?php echo $descMaterial ; ?></option>
                <?php endforeach; ?>
            </select></td>
    </tr>
    
    <tr class="div-item-variation form-etik">
        <td class="label">
            <div class="div-flex-label-info">
                <label class="tb-variation" for="pa_ksztalt-etykiety">Kształt etykiety</label>
                <div class="div-info-trigger">
                    <div class="tb-fomr-info">Elipsa</div>
                </div>
            </div>
            </div>
            </div>
            </div>

        </td>
        <td class="value">
            <select id="labelco_calc_shape" class="variation-select w-select labelco_calc_select" name="labelco_calc_shape"
                    data-attribute_name="attribute_pa_ksztalt-etykiety" data-show_option_none="yes">
                <option value="">Wybierz opcję</option>
                <option value="elipsa">Elipsa</option>
                <option value="kolo">Koło</option>
                <option value="prostokat">Prostokąt</option>
            </select></td>
    </tr>
    <tr class="div-item-variation height">
        <td class="label">
            <div class="div-flex-label-info">
                <label class="tb-variation" for="pa_wysokosc">Wysokość</label>
            </div>

        </td>
        <td class="value">
            <select id="labelco_calc_height" class="variation-select w-select labelco_calc_select" name="labelco_calc_height"
                    data-attribute_name="attribute_pa_wysokosc" data-show_option_none="yes">
                <option value="">Wybierz opcję</option>
                <?php foreach ($labelco->getSizesService()->getHeightValues() as $k => $valueArr): ?>
                    <option value="<?php echo implode('|', $valueArr)?>"><?php echo $valueArr[0] . '-' . $valueArr[1]?></option>
                <?php endforeach; ?>
            </select></td>
    </tr>
    
    <tr class="div-item-variation length">
        <td class="label">
            <div class="div-flex-label-info">
                <label class="tb-variation" for="pa_dlugosc">Długość</label>
            </div>

        </td>
        <td class="value">
            <select id="labelco_calc_width" class="variation-select w-select labelco_calc_select" name="labelco_calc_width"
                    data-attribute_name="attribute_pa_dlugosc" data-show_option_none="yes">
                <option value="">Wybierz opcję</option>
                <?php foreach ($labelco->getSizesService()->getWidthValues() as $k => $valueArr): ?>
                    <option value="<?php echo implode('|', $valueArr)?>"><?php echo $valueArr[0] . '-' . $valueArr[1]?></option>
                <?php endforeach; ?>
            </select></td>
    </tr>
    
    <tr class="div-item-variation size">
        <td class="label">
            <div class="div-flex-label-info">
                <label class="tb-variation" for="pa_rozmiar-etykiety">Rozmiar etykiety</label>
            </div>

        </td>
        <td class="value">
            <select id="labelco_size_select" name="labelco_size_select" class="variation-select w-select"
                    data-attribute_name="attribute_pa_rozmiar-etykiety" data-show_option_none="yes">
                <option value="">Wybierz opcję</option>
            </select>
        </td>
    </tr>

<tr class="div-item-variation quantity">
        <td class="label">
            <div class="div-flex-label-info">
                <label class="tb-variation" for="Quantities">Ilość</label>
            </div>

        </td>
        <td class="value">
            <select id="labelco_quantities_select" name="labelco_quantities_select" class="variation-select w-select"
                    data-attribute_name="attribute_pa_rozmiar-etykiety" data-show_option_none="yes">
                <option value="no">Wybierz ilość</option>
                <?php foreach ($labelco->getQuantitiesService()->getQuantities() as $quantity): ?>
                    <option value="<?php echo $quantity->getId()?>"><?php echo $quantity->getQuantities()?></option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
    </tbody>
</table>

<div><span id="labelco_size_select_msg" style="color:lightcoral"></span></div>

<!--  <div class="div-pricings labelco-pricings hidden">-->
<div class="div-pricings labelco-pricings">

    <div class="tb-prices">Cena za 1 sztukę (netto): <span class="span-price" id="labelco_price_one">-</span>

        <br>Koszt łączny druku (netto): <span class="span-price" id="labelco_price_total">wybierz odpowiedni wariant żeby uzyskać cenę</span>
    </div>
</div>
<div>
<?php 
printf ( wp_kses( __('<a href="%1$s" class="%2$s">add to cart</a>', 'fixolabels'),
                                                 array( 'a' => array( 'href' => array(),   'class' => array()) ) //array
                                       ),//wp kses
                                get_site_url() .'/?add-to-cart=9477',//prod id jako chwilowo staly element TODO: dynamizacja
                                'button product_type_simple add_to_cart_button ajax_add_to_cart' 
);?>
</div>
<?php 
require "add-new-product.php";
?>