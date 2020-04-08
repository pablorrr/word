<?php


namespace Labelco;

use function GuzzleHttp\Psr7\mimetype_from_extension;
use Labelco\DB as DB;
use Labelco\SizeModel as SizeModel;

class SizesService
{
    const SHAPES = ['elipsa', 'kolo', 'prostokat'];

    /**
     * @var DB
     */
    private $db;

    public function __construct(DB $db)
    {
        $this->db = $db;
    }

    /**
     * @param int $heightMin
     * @param int $heightMax
     * @param int $widthMin
     * @param int $widthMax
     * @param string $shape
     * @return array
     * @throws \Exception
     */
    private function getSizesFromDB(
        int $heightMin,
        int $heightMax,
        int $widthMin,
        int $widthMax,
        string $shape
    ) {

        $sql = str_replace(

            [
                '{table_punch}',
                '{column_height}',
                '{column_length}',
                '{column_shape_shortcut}',

            ],
            [
                LABELCO_DB_TABLE_PUNCH,
                LABELCO_DB_COLUMN_HEIGHT,
                LABELCO_DB_COLUMN_LENGTH,
                LABELCO_DB_COLUMN_SHAPE_SHORTCUT

            ],
            '
        SELECT * FROM [dbo].[{table_punch}]
        WHERE {column_height} >= ? AND {column_height} <= ? 
        AND {column_length} >= ? AND {column_length} <= ? 
        AND {column_shape_shortcut} = ? ORDER BY {column_height}, {column_length}'

        );

        $fromDB = $this->db->run(
            $sql,
            [
                $heightMin,
                $heightMax,
                $widthMin,
                $widthMax,
                $shape
            ]
        )->fetchAll();

        if (empty($fromDB)) {
            throw new \Exception('Nie można odnaleść rozmiaru dla podanych parametrów');
        }

        return $fromDB;
    }

    /**
     * @param int $heightMin
     * @param int $heightMax
     * @param int $widthMin
     * @param int $widthMax
     * @param string $shape
     * @return QuantityModel[]|null
     * @throws \Exception
     */
    public function getSizes(
        int $heightMin,
        int $heightMax,
        int $widthMin,
        int $widthMax,
        string $shape
    ) {
        $fromDB = $this->getSizesFromDB(
            $heightMin,
            $heightMax,
            $widthMin,
            $widthMax,
            $shape
        );

        if (empty($fromDB)) {
            return null;
        }

        $return = [];
        foreach ($fromDB as $sizes) {
            $return[] = new SizeModel(
                $sizes->wyk_id,
                $sizes->Numer,
                $sizes->Wysokosc,
                $sizes->Dlugosc,
                $sizes->Ksztalt_skrot,
                $sizes->Ksztalt_opis
            );
        }

        return $return;
    }

    /**
     * @return mixed
     */
    private function getMaxWidth()
    {
        $sql = str_replace(
            [
                '{table_punch}',
                '{column_length}'
            ],
            [
                LABELCO_DB_TABLE_PUNCH,
                LABELCO_DB_COLUMN_LENGTH

            ],
            '
        SELECT MAX({column_length}) FROM [dbo].[{table_punch}]'
        );

        return $this->db->run(
            $sql
        )->fetch();
    }

    /**
     * @return mixed
     */
    private function getMaxHeight()
    {
        $sql = str_replace(
            [
                '{table_punch}',
                '{column_height}'
            ],
            [
                LABELCO_DB_TABLE_PUNCH,
                LABELCO_DB_COLUMN_HEIGHT

            ],
            '
        SELECT MAX({column_height}) FROM [dbo].[{table_punch}]'
        );

        return $this->db->run(
            $sql
        )->fetch();
    }

    /**
     * @return array
     */
    public function getWidthValues()
    {
        $return = [];
        $max = $this->getMaxWidth()->computed;
        for ($from = 25; $from <= $max; $from += 25) {
            $return[] = [$from, $from + 24];
        }
        return $return;
    }

    /**
     * @return array
     */
    public function getHeightValues()
    {
        $return = [];
        $max = $this->getMaxWidth()->computed;
        for ($from = 25; $from <= $max; $from += 25) {
            $return[] = [$from, $from + 24];
        }
        return $return;
    }

    /**
     * @param string $data
     * @return array
     */
    public function extractValuesFromDropdown(string $data)
    {
        if (empty($data)) {
            return null;
        }

        $exploded = explode('|', $data);
        return [$exploded[0], $exploded[1]];
    }

    /**
     * @param string $data
     * @return string
     * @throws \Exception
     */
    public function mapShape(string $data)
    {

        switch ($data) {
            case 'elipsa':
                return 'O';
            case 'kolo':
                return 'K';
            case 'prostokat':
                return 'P';
        }

        throw new \Exception('Wypełnij poprawnie wszystkie pola');
    }
}