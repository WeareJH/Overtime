<?php

namespace JhOvertime\View\Helper;

use Doctrine\Common\Persistence\ObjectRepository;
use Zend\Mvc\Router\RouteMatch;
use Zend\View\Helper\AbstractHelper;

class MonthPagination extends AbstractHelper
{
    protected $route;



    public function __construct(RouteMatch $routeMatch)
    {
        $this->routeMatch = $routeMatch;
    }

    public function prevMonth()
    {
        if($this->routeMatch->getParam('to')) {
            $toDate = \DateTime::createFromFormat('d-m-Y', $this->routeMatch->getParam('from'));
            $fromDate = clone $toDate;
            $fromDate->modify("-1 month");

        } else {
            $toDate     = new \DateTime("first day of this month");
            $fromDate   = new \DateTime("first day of previous month");
        }

        return $this->view->url($this->route, ['to' => $toDate->format('d-m-Y'), 'from' => $fromDate->format('d-m-Y')], true);
    }

    public function nextMonth()
    {
        if($this->routeMatch->getParam('to')) {
            $fromDate = \DateTime::createFromFormat('d-m-Y', $this->routeMatch->getParam('to'));
            $toDate = clone $fromDate;
            $toDate->modify("+1 month");
        } else {
            $toDate = new \DateTime("first day of next month");
            $fromDate   = new \DateTime("first day of this month");
        }

        return $this->view->url($this->route, ['to' => $toDate->format('d-m-Y'), 'from' => $fromDate->format('d-m-Y')], true);
    }

    public function __invoke($route)
    {
        $this->route = $route;
        return $this;
    }

    public function setBaseRoute($route)
    {
        $this->route = $route;
        return $this;
    }

} 