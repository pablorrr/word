<?php


namespace Labelco;


class MaterialModel
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $slugWooCommerce;

    /**
     * MaterialModel constructor.
     * @param int $id
     * @param string $description
     * @param int $slugWooCommerce
     */
    public function __construct(
        int $id,
        string $description,
        int $slugWooCommerce

    ) {
        $this->id = $id;
        $this->description = $description;
        $this->slugWooCommerce = $slugWooCommerce;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    public function toArray()
    {
        return get_object_vars( $this );
    }

    /**
     * @return string
     */
    public function getSlugWooCommerce(): string
    {
        return $this->slugWooCommerce;
    }
}
