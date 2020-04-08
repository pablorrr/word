<?php


namespace Labelco;


class QuantityModel
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $quantities;


    public function __construct(
        int $id,
        int $quantities
    ) {
        $this->id = $id;
        $this->quantities = $quantities;
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
    public function getQuantities(): int
    {
        return $this->quantities;
    }

    public function toArray()
    {
        return get_object_vars( $this );
    }
}
