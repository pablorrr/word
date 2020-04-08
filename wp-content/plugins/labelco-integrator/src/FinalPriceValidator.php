<?php

namespace Labelco;

use \Labelco\Validator as Validator;

class FinalPriceValidator extends Validator
{
    const INVALID_PARAMETER_GENERAL = 'Wypełnij poprawnie wszystkie pola';

    /**
     * @param $materialId
     * @param $size
     * @param $quantities
     * @throws \Exception
     */
    public function validate($materialId, $size, $quantities)
    {
        $this->validateMaterialId($materialId);
        $this->validateSize($size);
        $this->validateQuantities($quantities);

        $this->throwExceptionIfErrorsOccurs();
    }

    /**
     * @param $materialId
     */
    public function validateMaterialId($materialId)
    {
        if (false === is_numeric($materialId)) {
            $this->addToMessage(self::INVALID_PARAMETER_GENERAL);
        }
    }

    /**
     * @param $size
     */
    public function validateSize($size)
    {
        if (false === is_numeric($size)) {
            $this->addToMessage(self::INVALID_PARAMETER_GENERAL);
        }
    }

    /**
     * @param $quantities
     */
    public function validateQuantities($quantities)
    {
        if (false === is_numeric($quantities)) {
            $this->addToMessage(self::INVALID_PARAMETER_GENERAL);
        }
    }
}
