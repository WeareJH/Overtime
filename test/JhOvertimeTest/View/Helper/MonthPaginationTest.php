<?php

namespace JhOvertimeTest\View\Helper;

use JhOvertime\View\Helper\MonthPagination;
use JhOvertime\Entity\OvertimeState;
use ReflectionClass;
use Zend\Mvc\Router\Console\RouteMatch;

/**
 * Class MonthPaginationTest
 * @package JhOvertimeTest\View\Helper
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class MonthPaginationTest extends \PHPUnit_Framework_TestCase
{
    protected $helper;
    protected $routeMatch;
    protected $renderer;

    public function setUp()
    {
        $this->routeMatch = new RouteMatch([]);
        $this->helper = new MonthPagination($this->routeMatch);
        $this->renderer = $this->getMock('Zend\View\Renderer\PhpRenderer', ['url']);
        $this->helper->setView($this->renderer);
    }

    public function testInvokeReturnsSelf()
    {
        $route = 'test';
        $this->assertSame($this->helper, $this->helper->__invoke($route));
    }

    public function testPrevMonthReturnsUrlForLastMonthIfNoRouteParams()
    {
        $route = 'test';

        $from = new \DateTime('first day of previous month');
        $to = new \DateTime("last day of previous month");

        $this->renderer
            ->expects($this->once())
            ->method('url')
            ->with($route, ['from' => $from->format('d-m-Y'), 'to' => $to->format('d-m-Y')], true);

        $this->helper->__invoke($route)->prevMonth();
    }

    public function testPrevMonthReturnsUrlForPrevMonthIfRouteParamsSet()
    {
        $route = 'test';

        $this->routeMatch->setParam('to', '12-04-2014');
        $this->routeMatch->setParam('from', '1-04-2014');

        $this->renderer
            ->expects($this->once())
            ->method('url')
            ->with($route, ['from' => '01-03-2014', 'to' => '31-03-2014', ], true);

        $this->helper->__invoke($route)->prevMonth();
    }

    public function testNextMonthReturnsUrlForNextMonthIfNoRouteParams()
    {
        $route = 'test';

        $from   = new \DateTime('first day of next month');
        $to     = new \DateTime('last day of next month');

        $this->renderer
            ->expects($this->once())
            ->method('url')
            ->with($route, ['from' => $from->format('d-m-Y'), 'to' => $to->format('d-m-Y'), ], true);

        $this->helper->__invoke($route)->nextMonth();
    }

    public function testNextMonthReturnsUrlForNextMonthIfRouteParamsSet()
    {
        $route = 'test';

        $this->routeMatch->setParam('to', '12-04-2014');
        $this->routeMatch->setParam('from', '1-04-2014');

        $this->renderer
            ->expects($this->once())
            ->method('url')
            ->with($route, ['from' => '01-05-2014', 'to' => '31-05-2014'], true);

        $this->helper->__invoke($route)->nextMonth();
    }
}
