<?php

namespace JhOvertime\View\Helper;

use Doctrine\Common\Persistence\ObjectRepository;
use Zend\View\Helper\AbstractHelper;

/**
 * Class Users
 * @package JhOvertime\View\Helper
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class Users extends AbstractHelper
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
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    protected $userRepository;

    /**
     * @var string
     */
    protected $route;

    /**
     * @param ObjectRepository $userRepository
     */
    public function __construct(ObjectRepository $userRepository)
    {
        $this->userRepository = $userRepository;
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
        $html = sprintf($this->liFormat, $this->view->url($this->route, ['user' => 'all'], true), 'All');
        foreach ($this->userRepository->findAll() as $user) {
            $url = $this->view->url($this->route, ['user' => $user->getId()], true);
            $html .= sprintf($this->liFormat, $url, $user->getDisplayName());
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
