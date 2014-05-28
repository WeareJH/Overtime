<?php

namespace JhOvertime\Hydrator\Strategy;

use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;
use DateTime;

/**
 * Class DateToStringStrategy
 * @package JhOvertime\Hydrator\Strategy
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class DateToStringStrategy implements StrategyInterface
{
    /**
     * @param mixed $value
     * @return mixed|string
     * @throws \InvalidArgumentException
     */
    public function extract($value)
    {
        if (!is_null($value)) {
            if (!($value instanceof DateTime)) {
                throw new \InvalidArgumentException(sprintf('Field "%s" is not a valid DateTime object', $value));
            }

            //this is the format that Chrome wants the date in for
            //HTML5 elements
            return $value->format('Y-m-d');
        }

        return $value;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function hydrate($value)
    {
        return $value;
    }
}
