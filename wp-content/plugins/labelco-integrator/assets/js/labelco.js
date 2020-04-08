jQuery(document).ready(function () {

    jQuery('.labelco_calc_select').change(function() {//wykonuj instr w momencie zmiany wart formularza


        jQuery.ajax({
            url: labelcoVariables.ajaxurl,
            type: 'post',
            data: {
                action: 'labelco_get_sizes',//powiazanie z hakiem labelco_get_sizes plik ajax linia 26
               // dodana linijka labelco_calc_material:jQuery('#labelco_calc_material').val(),
                labelco_calc_material:jQuery('#labelco_calc_material').val(),
                labelco_calc_shape: jQuery('#labelco_calc_shape').val(),
                labelco_calc_height: jQuery('#labelco_calc_height').val(),
                labelco_calc_width: jQuery('#labelco_calc_width').val()
            },
            success: function (response) {
                jQuery('#labelco_size_select')
                    .find('option')
                    .remove()
                    .end()
                    .append('<option value="no">Wybierz opcję</option>')
                ;
                jQuery('#labelco_size_select_msg').html('');
                if (typeof response !== 'undefined' && response.length > 0 && typeof response['error'] === 'undefined') {
                    enableSelect('#labelco_size_select');
                    enableSelect('#labelco_quantities_select');
                    response.forEach(function(elem){
                        jQuery('#labelco_size_select').append(`<option value="${elem.id}">${elem.height}x${elem.width}</option>`);
                    });
                    jQuery('.labelco-pricings').hide();
                } else if (typeof response['error'] !== 'undefined') {
                    jQuery('#labelco_size_select_msg').html(response['error']);
                    disableSelect('#labelco_size_select');
                    disableSelect('#labelco_quantities_select');
                    jQuery('#labelco_price_total').html('');
                    jQuery('#labelco_price_one').html('');
                    jQuery('.labelco-pricings').hide();
                } else {
                    jQuery('#labelco_size_select_msg').html('Wypełnij poprawnie wszystkie pola');
                    disableSelect('#labelco_size_select');
                    disableSelect('#labelco_quantities_select');
                    jQuery('#labelco_price_total').html('');
                    jQuery('#labelco_price_one').html('');
                    jQuery('.labelco-pricings').hide();
                }
            }
        });
    })

    jQuery('#labelco_size_select').change(function() {
        if (jQuery('#labelco_quantities_select').val() !== 'no') {
            getFinalPrice();
        }
    })

    jQuery('#labelco_quantities_select').change(function() {
        getFinalPrice();
    })

    function getFinalPrice()
    {
        jQuery('#labelco_price_total').html('');
        jQuery('#labelco_price_one').html('');
        jQuery.ajax({
            url: labelcoVariables.ajaxurl,
            type: 'post',
            data: {
                action: 'labelco_get_final_price',
				
				//dodana linijka
				labelco_calc_material: jQuery('#labelco_calc_material').val(),
				//
				labelco_calc_size: jQuery('#labelco_size_select').val(),
				labelco_calc_quantities: jQuery('#labelco_quantities_select').val(),
				
                labelco_product_id: labelcoVariables.productId,
				//MatrialId(linijka dodana)
				labelco_material_id:labelcoVariables.materialId
				
				
            },//display na console js
            success: function (response) {
                console.log(response);

                if (typeof response !== 'undefined' && response.length > 0 && typeof response['error'] === 'undefined') {
                    jQuery('.labelco-pricings').show();
                    response = response[0];
                    jQuery('#labelco_price_total').html(response.price_total_formatted);
                    jQuery('#labelco_price_one').html(response.price_per_1);
                    jQuery('#labelco_size_select_msg').html('');
                }else if (typeof response['error'] !== 'undefined') {
                    jQuery('.labelco-pricings').hide();
                    jQuery('#labelco_size_select_msg').html(response['error']);
                    jQuery('#labelco_price_total').html('');
                    jQuery('#labelco_price_one').html('');
                } else {
                    jQuery('.labelco-pricings').hide();
                    jQuery('#labelco_size_select_msg').html('Wypełnij poprawnie wszystkie pola');
                    jQuery('#labelco_price_total').html('');
                    jQuery('#labelco_price_one').html('');
                }
            }
        });
    }

    function disableSelect(idJqueryHandler)
    {
        jQuery(idJqueryHandler + " option:selected").removeAttr('selected');
        jQuery(idJqueryHandler + " option[value='no']").attr('selected', 'selected')
        jQuery(idJqueryHandler).addClass('select-grayed');
        jQuery(idJqueryHandler).prop('disabled', true);
        jQuery(idJqueryHandler).val('no');

    }

    function enableSelect(idJqueryHandler)
    {
        jQuery(idJqueryHandler + " option[value='no']").attr('selected', 'selected')
        jQuery(idJqueryHandler).removeClass('select-grayed');
        jQuery(idJqueryHandler).prop('disabled', false);
        jQuery(idJqueryHandler).val('no');
    }
});
