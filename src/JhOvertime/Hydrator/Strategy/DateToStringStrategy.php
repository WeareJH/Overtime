<?php

namespace JhOvertime\Hydrator\Strategy;

use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;
use DateTime;

class DateToStringStrategy implements StrategyInterface
{
    public function extract($value)
    {
        if(!is_null($value)) {
            if (!($value instanceof DateTime)) {
                throw new \InvalidArgumentException(sprintf('Field "%s" is not a valid DateTime object', $value));
            }

            //this is the format that Chrome wants the date in for
            //HTML5 elements
            return $value->format('Y-m-d');
        }

        return $value;
    }

    public function hydrate($value)
    {
        return $value;
    }
} 