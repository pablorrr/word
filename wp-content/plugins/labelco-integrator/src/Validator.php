<?php


namespace Labelco;


use \Exception;

class Validator
{
    /**
     * @var array
     */
    private $message = [];

    /**
     * @param string $lineEnding
     * @return string
     */
    public function getMessageText(string $lineEnding = PHP_EOL):string
    {
        $m = $this->message;
        return implode($lineEnding, $this->message);
    }

    /**
     * @param string $message
     */
    protected function addToMessage(string $message)
    {
        if (false === in_array($message, $this->message)) {
            $this->message[] = $message;
        }
    }

    /**
     * @throws Exception
     */
    protected function throwExceptionIfErrorsOccurs()
    {
        if (false === empty($this->getMessageText())) {
            $this->throw();
        }
    }

    /**
     * @throws Exception
     */
    private function throw()
    {
        throw(new Exception($this->getMessageText()));
    }
}
