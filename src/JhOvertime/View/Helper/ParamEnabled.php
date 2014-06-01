<?php

namespace JhOvertime\View\Helper;

use Zend\Mvc\Router\Http\RouteMatch;
use Zend\View\Helper\AbstractHelper;
use Zend\Mvc\Router\Http\TreeRouteStack as Router;
use Zend\Http\Request;

/**
 * Class ParamEnabled
 * @package JhOvertime\View\Helper
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class ParamEnabled extends AbstractHelper
{

    /**
     * @var RouteMatch
     */
    protected $routeMatch;

    /**
     * @param Router $router
     * @param Request $request
     */
    public function __construct(Router $router, Request $request)
    {
        $this->routeMatch = $router->match($request);
    }

    /**
     * @param $param
     * @return bool
     */
    public function __invoke($param)
    {
        return (bool) $this->routeMatch->getParam($param);
    }
}
