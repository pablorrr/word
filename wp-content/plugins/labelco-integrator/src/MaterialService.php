<?php


namespace Labelco;


use mysql_xdevapi\Exception;

class MaterialService
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
     * @param int $id
     * @return bool|\PDOStatement
     */
    private function getMaterialFromDB(int $id)
    {
        $sql = str_replace(
        [
            //'{column_id_material}'
            '{table_materials_list}'
        ],
        [
           // LABELCO_DB_COLUMN_ID_MATERIAL,
            LABELCO_DB_TABLE_MATERIALS_LIST
        ],
        'SELECT * FROM [dbo].[{table_materials_list}] WHERE ID_Material = ?'
        );

        return $this->db->run(
            $sql,
            [
                $id,
            ]
        )->fetch();
    }

    /**
     * @param string $slug
     * @return mixed
     */
    private function getMaterialByWooProductSlugFromDB(string $slug)
    {

        $sql = str_replace(
            [
                '{table_materials_list}',
                '{column_slug_wc}'
            ],
            [
                LABELCO_DB_TABLE_MATERIALS_LIST,
                LABELCO_DB_COLUMN_SLUG_WC
            ],
            'SELECT * FROM [dbo].[{table_materials_list}] WHERE {column_slug_wc} = ?'
        );

        return $this->db->run(
            $sql,
            [
                $slug,
            ]
        )->fetch();
    }

    /**
     * @return array
     */
    private function getMaterialsFromDB()
    {
        $sql = str_replace(
            [
                '{table_materials_list}'
            ],
            [
                LABELCO_DB_TABLE_MATERIALS_LIST
            ],

            'SELECT * FROM [dbo].[{table_materials_list}]'

        );

        return $this->db->run(
            $sql
        )->fetchAll();
    }

    /**
     * @param int $id
     * @return MaterialModel|null
     */
    public function getMaterial(int $id)
    {
        $fromDB = $this->getMaterialFromDB($id);
        if (empty($fromDB)) {
            return null;
        }

        return new MaterialModel(
            $fromDB->ID_Material,
            $fromDB->Description,
            $fromDB->Link
        );
    }

    /**
     * @param string $slug
     * @return MaterialModel|null
     * @throws \Exception
     */
    private function getMaterialByWooProductSlug(string $slug):MaterialModel
    {
        $fromDB = $this->getMaterialByWooProductSlugFromDB($slug);
        if (empty($fromDB)) {
            throw new \Exception('Nie znaleziono materiału');
        }

        return new MaterialModel(
            $fromDB->ID_Material,
            $fromDB->Description,
            $fromDB->Link
        );
    }

    /**
     * @param string $slug
     * @return int
     */
    public function getMaterialIdByWooProductSlug(string $slug):int
    {
        $material = $this->getMaterialByWooProductSlug($slug);
        return $material->getId();
    }

    /**
     * @return MaterialModel[]|null
     */
    public function getMaterials()
    {
        $fromDB = $this->getMaterialsFromDB();
        if (empty($fromDB)) {
            return null;
        }

        $return = [];
        foreach ($fromDB as $material) {
            $return[] = new MaterialModel(
                $material->ID_Material,
                $material->Description,
                $material->Link
            );
        }

        return $return;
    }
}
