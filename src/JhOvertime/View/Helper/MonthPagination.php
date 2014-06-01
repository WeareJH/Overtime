<?php

namespace JhOvertime\View\Helper;

use Zend\Mvc\Router\RouteMatch;
use Zend\View\Helper\AbstractHelper;

/**
 * Class MonthPagination
 * @package JhOvertime\View\Helper
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class MonthPagination extends AbstractHelper
{

    /**
     * @var string
     */
    protected $route;

    /**
     * @var \Zend\Mvc\Router\RouteMatch
     */
    protected $routeMatch;

    /**
     * @param RouteMatch $routeMatch
     */
    public function __construct(RouteMatch $routeMatch)
    {
        $this->routeMatch = $routeMatch;
    }

    /**
     * @param string $route
     * @return self
     */
    public function __invoke($route)
    {
        $this->route = $route;
        return $this;
    }

    /**
     * @return string
     */
    public function prevMonth()
    {
        if ($this->routeMatch->getParam('to')) {
            $toDate = \DateTime::createFromFormat('d-m-Y', $this->routeMatch->getParam('to'));
            $toDate->modify("last day of previous month");
            $fromDate = clone $toDate;
            $fromDate->modify("first day of this month");

        } else {
            $toDate     = new \DateTime("last day of previous month");
            $fromDate   = new \DateTime("first day of previous month");
        }

        return $this->view->url(
            $this->route,
            ['to' => $toDate->format('d-m-Y'), 'from' => $fromDate->format('d-m-Y')],
            true
        );
    }

    /**
     * @return string
     */
    public function nextMonth()
    {
        if ($this->routeMatch->getParam('to')) {
            $fromDate = \DateTime::createFromFormat('d-m-Y', $this->routeMatch->getParam('to'));
            $fromDate->modify("first day of next month");
            $toDate = clone $fromDate;
            $toDate->modify("last day of this month");
        } else {
            $fromDate   = new \DateTime("first day of next month");
            $toDate     = new \DateTime("last day of next month");
        }

        return $this->view->url(
            $this->route,
            ['to' => $toDate->format('d-m-Y'), 'from' => $fromDate->format('d-m-Y')],
            true
        );
    }
}
