<?php

namespace JhOvertime\View\Helper;

use Doctrine\Common\Persistence\ObjectRepository;
use Zend\View\Helper\AbstractHelper;

class Users extends AbstractHelper
{

    protected $ulFormat = '<ul>%s</ul>';
    protected $liFormat = '<li><a href="%s">%s</a></li>';
    protected $userRepository;
    protected $route;

    /**
     * @param ObjectRepository $userRepository
     * @param $router
     * @param $request
     */
    public function __construct(ObjectRepository $userRepository, $router, $request)
    {
        $this->userRepository = $userRepository;
        $this->router= $router;
        $this->request = $request;
    }



    public function render()
    {
        $html = sprintf($this->liFormat, $this->view->url($this->route, ['user' => 'all'], true), 'All');
        foreach($this->userRepository->findAll() as $user) {
            $url = $this->view->url($this->route, ['user' => $user->getId()], true);
            $html .= sprintf($this->liFormat, $url, $user->getDisplayName());
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