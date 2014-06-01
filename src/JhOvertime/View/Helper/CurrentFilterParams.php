<?php

namespace JhOvertime\View\Helper;

use Doctrine\Common\Persistence\ObjectRepository;
use Zend\Mvc\Router\Http\RouteMatch;
use Zend\View\Helper\AbstractHelper;
use Zend\Mvc\Router\Http\TreeRouteStack as Router;
use Zend\Http\Request;

/**
 * Class CurrentFilterParams
 * @package JhOvertime\View\Helper
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class CurrentFilterParams extends AbstractHelper
{

    /**
     * @var RouteMatch
     */
    protected $routeMatch;

    /**
     * @var ObjectRepository
     */
    protected $userRepository;

    /**
     * @var ObjectRepository
     */
    protected $stateRepository;

    /**
     * @var array
     */
    protected $btnClasses = [
        'btn',
        'btn-info',
        'btn-xs',
    ];

    /**
     * @var string
     */
    protected $closeBtn = '<span class="glyphicon glyphicon-remove"></span>';

    /**
     * @var string
     */
    protected $btnGrpTemplate = '<div class="filter-btn btn-group">%s</div>';

    /**
     * @var string
     */
    protected $btnTemplate = '<button class="%s">%s </button><a href="%s" type="button" class="%s">%s</a>';

    /**
     * @param Router $router
     * @param Request $request
     * @param ObjectRepository $stateRepository
     * @param ObjectRepository $userRepository
     */
    public function __construct(
        Router $router,
        Request $request,
        ObjectRepository $stateRepository,
        ObjectRepository $userRepository
    ) {
        $this->routeMatch = $router->match($request);
        $this->userRepository = $userRepository;
        $this->stateRepsoitory = $stateRepository;
    }

    public function __invoke($route)
    {
        $html = '';
        foreach ($this->routeMatch->getParams() as $paramName => $paramVal) {

            switch($paramName) {
                case "user":
                    $value = $this->userRepository->find($paramVal)->getDisplayName();
                    $label = sprintf("<b>%s</b>: %s", ucfirst($paramName), $value);
                    $url = $this->view->url($route, [$paramName => null], true);
                    break;
                case "state":
                    $value = $this->stateRepsoitory->find($paramVal)->getState();
                    $label = sprintf("<b>%s</b>: %s", ucfirst($paramName), $value);
                    $url = $this->view->url($route, [$paramName => null], true);
                    break;
                case "to":
                    $from   = $this->formatDate($this->routeMatch->getParam('from'));
                    $to     = $this->formatDate($this->routeMatch->getParam('to'));
                    $label  = sprintf("<b>From:</b> %s <b>To:</b> %s", $from, $to);
                    $url    = $this->view->url($route, ['from' => null, 'to' => null], true);
                    break;
                default:
                    //skip the switch & foreach
                    continue 2;
            }

            $html .= sprintf(
                $this->btnGrpTemplate,
                sprintf(
                    $this->btnTemplate,
                    implode(" ", $this->btnClasses),
                    $label,
                    $url,
                    implode(" ", $this->btnClasses),
                    $this->closeBtn
                )
            );
        }
        return $html;
    }

    /**
     * @param string $dateStr
     * @param string $format
     * @return string
     */
    public function formatDate($dateStr, $format = 'd-m-Y')
    {
        $date = \DateTime::createFromFormat($format, $dateStr);
        return $date->format("F jS, Y");
    }
}
