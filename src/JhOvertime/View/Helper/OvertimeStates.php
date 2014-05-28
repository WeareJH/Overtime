<?php

namespace JhOvertime\View\Helper;

use Doctrine\Common\Persistence\ObjectRepository;
use Zend\View\Helper\AbstractHelper;

/**
 * Class OvertimeStates
 * @package JhOvertime\View\Helper
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class OvertimeStates extends AbstractHelper
{

    /**
     * @var string
     */
    protected $ulFormat = '<ul>%s</ul>';

    /**
     * @var string
     */
    protected $liFormat = '<li><a href="%s">%s</a></li>';

    /**
     * @var string
     */
    protected $route;

    /**
     * @param ObjectRepository $stateRepository
     */
    public function __construct(ObjectRepository $stateRepository)
    {
        $this->stateRepository = $stateRepository;
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
    public function render()
    {
        $html = sprintf($this->liFormat, $this->view->url($this->route, ['state' => null], true), 'All');
        foreach ($this->stateRepository->findAll() as $state) {
            $url = $this->view->url($this->route, ['state' => $state->getId()], true);
            $html .= sprintf($this->liFormat, $url, $state->getState());
        }

        return sprintf($this->ulFormat, $html);
    }

    /**
     * @param string $ulFormat
     * @return self
     */
    public function setUlFormat($ulFormat)
    {
        $this->ulFormat = $ulFormat;
        return $this;
    }
}
