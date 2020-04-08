<?php

namespace Labelco;

use \Labelco\Validator as Validator;

class SizesValidator extends Validator
{
    const INVALID_PARAMETER_GENERAL = 'Wypełnij poprawnie wszystkie pola';

    /**
     * @param $heightRange
     * @param $widthRange
     * @param $shape
     * @throws \Exception
     */
    public function validate($heightRange, $widthRange, $shape)
    {
        $this->validateHeightRange($heightRange);
        $this->validateWidthRange($widthRange);
        $this->validateShape($shape);

        $this->throwExceptionIfErrorsOccurs();
    }

    /**
     * @param $heightRange
     */
    public function validateHeightRange($heightRange)
    {
        if (false === is_array($heightRange)) {
            $this->addToMessage(self::INVALID_PARAMETER_GENERAL);
        }
        
        if (false === (true === isset($heightRange[0]) && true === isset($heightRange[1]))) {
            $this->addToMessage(self::INVALID_PARAMETER_GENERAL);
        }

        if (false === (true === is_numeric($heightRange[0]) && true === is_numeric($heightRange[1]))) {
            $this->addToMessage(self::INVALID_PARAMETER_GENERAL);
        }
    }

    /**
     * @param $widthRange
     */
    public function validateWidthRange($widthRange)
    {
        if (false === is_array($widthRange)) {
            $this->addToMessage(self::INVALID_PARAMETER_GENERAL);
        }

        if (false === (true === isset($widthRange[0]) && true === isset($widthRange[1]))) {
            $this->addToMessage(self::INVALID_PARAMETER_GENERAL);
        }

        if (false === (true === is_numeric($widthRange[0]) && true === is_numeric($widthRange[1]))) {
            $this->addToMessage(self::INVALID_PARAMETER_GENERAL);
        }
    }

    /**
     * @param $shape
     */
    public function validateShape($shape)
    {
        if (false === is_string($shape)) {
            $this->addToMessage(self::INVALID_PARAMETER_GENERAL);
        }
    }
}
