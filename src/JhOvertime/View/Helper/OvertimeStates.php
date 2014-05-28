<?php

namespace JhOvertime\View\Helper;

use Doctrine\Common\Persistence\ObjectRepository;
use Zend\View\Helper\AbstractHelper;

class OvertimeStates extends AbstractHelper
{

    protected $ulFormat = '<ul>%s</ul>';
    protected $liFormat = '<li><a href="%s">%s</a></li>';
    protected $route;


    /**
     * @param ObjectRepository $stateRepository
     */
    public function __construct(ObjectRepository $stateRepository, $router, $request)
    {
        $this->stateRepository = $stateRepository;
        $this->router= $router;
        $this->request = $request;
    }

    public function render()
    {
        $html = sprintf($this->liFormat, $this->view->url($this->route, ['state' => null], true), 'All');
        foreach($this->stateRepository->findAll() as $state) {
            $url = $this->view->url($this->route, ['state' => $state->getId()], true);
            $html .= sprintf($this->liFormat, $url, $state->getState());
        }

        return sprintf($this->ulFormat, $html);
    }

    public function __invoke($route)
    {
        $this->route = $route;
        return $this;
    }

    public function setUlFormat($string)
    {
        $this->ulFormat = $string;
        return $this;
    }

    public function setBaseRoute($route)
    {
        $this->route = $route;
        return $this;
    }
} 