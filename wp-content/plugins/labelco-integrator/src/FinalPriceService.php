<?php

namespace Labelco;

use \Labelco\FinalPriceModel as FinalPriceModel;

class FinalPriceService
{
    /**
     * @var DB
     */
    private $db;

    public function __construct(DB $db)
    {
        $this->db = $db;
    }

    /**
     * @param int $size
     * @param int $quantities
     * @return bool|\PDOStatement
     */
    private function getSummaryFromDB(int $material, int $size, int $quantities)
    {
        $sql= str_replace(
            [
                '{column_hight}',//Wysokosc
                '{column_length}',//Dlugosc
                '{column_quantities}',//Quantities
                '{column_id_punch}',//ID_Wykrojnik
                '{column_punch_id}',//wyk_id
                '{column_id_quantities}',//ID_Quantities
                '{column_id_qty}',//ID_Qty
                //'{column_id_material}',//ID_Material
                '{table_price_list}',//labelco_price_list
                '{table_punch}',//labelco_vw_wykrojniki
                '{table_quantities}',//labelco_quantities
            ],
            [
                LABELCO_DB_COLUMN_HEIGHT,
                LABELCO_DB_COLUMN_LENGTH,
                LABELCO_DB_COLUMN_QUANTITIES,
                LABELCO_DB_COLUMN_ID_PUNCH,
                LABELCO_DB_COLUMN_PUNCH_ID,
                LABELCO_DB_COLUMN_ID_QUANTITIES,
                LABELCO_DB_COLUMN_ID_QTY,
               // LABELCO_DB_COLUMN_ID_MATERIAL,
                LABELCO_DB_TABLE_PRICE_LIST,
                LABELCO_DB_TABLE_PUNCH,
                LABELCO_DB_TABLE_QUANTITIES

            ],'SELECT p.*, w.{column_hight}, w.{column_length}, q.{column_quantities} FROM [dbo].[{table_price_list}]
        AS p JOIN [dbo].[{table_punch}]
        AS w
        ON (p.{column_id_punch} = w.{column_punch_id}) JOIN [dbo].[{table_quantities}] 
        AS q
        ON (p.{column_id_quantities} = q.{column_id_qty})
        WHERE p.ID_Material = ? AND p.{column_id_punch} = ? AND {column_id_quantities} = ?' );



        $return = $this->db->run(
            $sql,
            [
                $material,
                $size,
                $quantities,
            ]
        )->fetch();


        return $return;
    }

    /**
     * @param int $material
     * @param int $size
     * @param int $quantities
     * @return \Labelco\FinalPriceModel|null
     */
    public function getSummary(int $material, int $size, int $quantities)
    {
        $fromDB = $this->getSummaryFromDB($material, $size, $quantities);
        if (empty($fromDB)) {
            return null;
        }

        return new FinalPriceModel(
            $fromDB->ID_Price,
            $fromDB->ID_Material,
            $fromDB->ID_Wykrojnik,
            $fromDB->ID_Quantities,
            wc_price((float)$fromDB->Price_per_1, ['decimals' => 4]),
            (float)$fromDB->Price_Total,
            wc_price((float)$fromDB->Price_Total, ['decimals' => 2]),
            $fromDB->Weight,
            $fromDB->Wysokosc,
            $fromDB->Dlugosc,
            $fromDB->Quantities
        );
    }
}
