<?php

//labelcoVariables
namespace Labelco;

use Labelco\Labelco as Labelco;
use Labelco\SizesService as SizesService;
use Labelco\SizeModel as SizeModel;
use Labelco\FinalPriceValidator as FinalPriceValidator;

class Ajax
{
    /**
     * @var Labelco
     */
    private $labelco;

    public function __construct()
    {
        $this->hooks();
        $this->labelco = Labelco::getInstance();
    }

    private function hooks()
    {    //odniesienia do pliku lanelco .js - powiazania z kluczem action w js
        add_action('wp_ajax_labelco_get_sizes', [$this, 'getSizes']);//powiazanie z hakiem akcji //labelco.js
        add_action('wp_ajax_nopriv_labelco_get_sizes', [$this, 'getSizes']);
        add_action('wp_ajax_nopriv_labelco_get_final_price', [$this, 'getFinalPrice']);
        add_action('wp_ajax_labelco_get_final_price', [$this, 'getFinalPrice']);
    }

    /**
     *
     */
	 //jest to njprwd przechwyt danych z calc 
    public function getSizes()
    {
        try {
            $sizesSrv = $this->labelco->getSizesService();
            $widthRangeRaw = $_POST['labelco_calc_width'];
            $heightRangeRaw = $_POST['labelco_calc_height'];
			//linijka dopisana 
			$matrialRangeRaw = $_POST['labelco_calc_material'];
			//
			
			//alternatywa 
			//$matrialRangeRaw = $sizesSrv->mapShape($matrialRangeRaw);
			//
			
            $shape = $_POST['labelco_calc_shape'];
			
			//linijka dopisana 
			 $materialRange = $this->labelco->getSizesService()->extractValuesFromDropdown($matrialRangeRaw);
			//
			
			
            $widthRange = $this->labelco->getSizesService()->extractValuesFromDropdown($widthRangeRaw);
            $heightRange = $this->labelco->getSizesService()->extractValuesFromDropdown($heightRangeRaw);
            $shape = $sizesSrv->mapShape($shape);
            $validator = $this->labelco->getSizesValidator();
			//linijka zmodyfikowana dopisanie parametru z pola material
            $validator->validate($heightRange, $widthRange, $shape, $materialRange );
			//
			//linijka zmodyfikowana dodanie paramtru material
            $results = $sizesSrv->getSizes($heightRange[0],$heightRange[1], $widthRange[0], $widthRange[1],$materialRange[0],$materialRange[1],$shape);
            $this->sendJson($results);
            exit;
        } catch (\Exception $e) {
            $this->sendError($e);
        }
    }

    public function getFinalPrice()
    {
        try {
            $results = $this->getSimulatedFinalPrice();
            $this->sendJson([$results]);
        } catch (\Exception $e) {
            $this->sendError($e);
        }
    }

    /**
     * @return FinalPriceModel|null
     * @throws \Exception
     */
    private function getSimulatedFinalPrice()
    {
        $finalPriceSrv = $this->labelco->getFinalPriceService();
        $materialSrv = $this->labelco->getMaterialService();
        $size = $_POST['labelco_calc_size'];
        $currentProductId = (int)$_POST['labelco_product_id'];
        $post = get_post($currentProductId);
        $materialId = $materialSrv->getMaterialIdByWooProductSlug($post->post_name);
        $quantities = $_POST['labelco_calc_quantities'];
		
        //linijka dodana
        $currentMaterialId = (int)$_POST['labelco_material_id'];
        
		//linika dodana
		$material = $_POST['labelco_calc_material'];
		//
		
        $validator = $this->labelco->getFinalPriceValidator();
		
		//linijka modyfikowana dodoanie parametru material 
        $validator->validate($materialId, $size, $quantities, $material);
		
         //linijka modyfikowana dodanie parametru material 
        return  $finalPriceSrv->getSummary((int)$materialId,(int)$size,(string)$material,(int)$quantities);
    }

    /**
     * @param array|null $results
     * @return void
     */
	 
	 //njpwrd wysylanie danych do json , formatowanie
    private function sendJson($results)
    {
        if (null === $results) {
            wp_send_json([], 200);
            exit;
        }
        $return = [];
        foreach ($results as $object) {
            $return[] = $object->toArray();
        }

        wp_send_json($return, 200);
        exit;
    }

    /**
     * @param \Exception $exception
     */
    private function sendError(\Exception $exception)
    {
        wp_send_json(['error' => $exception->getMessage()], 200);
        exit;
    }
}