<?php

namespace Labelco;

use Labelco\DB as DB;

class QuantitiesService
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
     * @return array
     */
    private function getQuantitiesFromDB()
    {
        //LABELCO_DB_TABLE_QUANTITIES
        $sql = str_replace(
            [
                '{table_quantities}'
            ],
            [
                LABELCO_DB_TABLE_QUANTITIES
            ],
            'SELECT * FROM [dbo].[{table_quantities}]'

        );

        return $this->db->run(
            $sql
        )->fetchAll();
    }

    /**
     * @return QuantityModel[]|null
     */
    public function getQuantities()
    {
        $fromDB = $this->getQuantitiesFromDB();
        if (empty($fromDB)) {
            return null;
        }

        $return = [];
        foreach ($fromDB as $quantity) {
            $return[] = new QuantityModel(
                $quantity->ID_Qty,
                $quantity->Quantities
            );
        }

        return $return;
    }
}