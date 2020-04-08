<?php


namespace Labelco;


class SizeModel
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var int
     */
    private $number;

    /**
     * @var float
     */
    private $height;

    /**
     * @var float
     */
    private $width;

    /**
     * @var string
     */
    private $shapeSymbol;

    /**
     * @var float
     */
    private $shapeDescription;


    public function __construct(
        int $id,
        int $number,
        float $height,
        float $width,
        string $shapeSymbol,
        string $shapeDescription
    ) {

        $this->id = $id;
        $this->number = $number;
        $this->height = $height;
        $this->width = $width;
        $this->shapeSymbol = $shapeSymbol;
        $this->shapeDescription = $shapeDescription;
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
    public function getNumber(): int
    {
        return $this->number;
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
     * @return string
     */
    public function getShapeSymbol(): string
    {
        return $this->shapeSymbol;
    }

    /**
     * @return float
     */
    public function getShapeDescription(): float
    {
        return $this->shapeDescription;
    }

    public function toArray()
    {
        return get_object_vars( $this );
    }
}
