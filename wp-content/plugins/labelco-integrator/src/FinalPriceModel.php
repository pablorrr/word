<?php


namespace Labelco;


class FinalPriceModel
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $material;

    /**
     * @var int
     */
    private $size;

    /**
     * @var int
     */
    private $quantityId;

    /**
     * @var int
     */
    private $quantity;

    /**
     * @var string
     */
    private $price_per_1;

    /**
     * @var float
     */
    private $price_total;

    /**
     * @var string
     */
    private $price_total_formatted;

    /**
     * @var float
     */
    private $height;

    /**
     * @var float
     */
    private $width;

    /**
     * @var int
     */
    private $weight;

    /**
     * @param int $id
     * @param int $material
     * @param int $size
     * @param int $quantityId
     * @param string $price_per_1
     * @param float $price_total
     * @param string $price_total_formatted
     * @param int $weight
     * @param float $height
     * @param float $width
     * @param int $quantity
     */
    public function __construct(
        int $id,
        int $material,
        int $size,
        int $quantityId,
        string $price_per_1,
        float $price_total,
        string $price_total_formatted,
        int $weight,
        float $height,
        float $width,
        int $quantity

    ) {
        $this->id = $id;
        $this->material = $material;
        $this->size = $size;
        $this->quantityId = $quantityId;
        $this->price_per_1 = $price_per_1;
        $this->price_total = $price_total;
        $this->price_total_formatted = $price_total_formatted;
        $this->weight = $weight;
        $this->height = $height;
        $this->width = $width;
        $this->quantity = $quantity;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getMaterial(): int
    {
        return $this->material;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @return int
     */
    public function getQuantityId(): int
    {
        return $this->quantityId;
    }

    /**
     * @return string
     */
    public function getPricePer1(): string
    {
        return $this->price_per_1;
    }

    /**
     * @return float
     */
    public function getPriceTotal(): float
    {
        return $this->price_total;
    }

    /**
     * @return string
     */
    public function getPriceTotalFormatted(): string
    {
        return $this->price_total_formatted;
    }

    /**
     * @return int
     */
    public function getWeight(): int
    {
        return $this->weight;
    }

    public function toArray()
    {
        return get_object_vars($this);
    }

    /**
     * @return float
     */
    public function getHeight(): float
    {
        return $this->height;
    }

    /**
     * @return float
     */
    public function getWidth(): float
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
