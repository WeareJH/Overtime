<?php

namespace JhOvertimeTest\Controller;

use JhOvertime\Controller\OvertimeAdminController;

use JhOvertime\Entity\Overtime;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use JhOvertimeTest\Util\ServiceManagerFactory;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Paginator\Paginator;
use Zend\Stdlib\Parameters;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Class OvertimeAdminControllerTest
 * @package JhOvertimeTest\Controller
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class OvertimeAdminControllerTest extends AbstractHttpControllerTestCase
{

    protected $controller;
    protected $routeMatch;
    protected $event;
    protected $request;
    protected $response;
    protected $user;
    protected $overtimeService;
    protected $overtimeRepository;
    protected $form;

    public function setUp()
    {
        $this->overtimeService = $this->getMockBuilder('JhOvertime\Service\OvertimeService')
            ->disableOriginalConstructor()
            ->getMock();

        $this->overtimeRepository = $this->getMock('\JhOvertime\Repository\OvertimeRepositoryInterface');
        $this->form = $this->getMock('Zend\Form\FormInterface');

        $this->controller = new OvertimeAdminController(
            $this->overtimeService,
            $this->overtimeRepository,
            $this->form
        );

        $this->request      = new Request();
        $this->routeMatch   = new RouteMatch(array());
        $this->event        = new MvcEvent();

        $serviceManager     = ServiceManagerFactory::getServiceManager();
        $config             = $serviceManager->get('Config');
        $routerConfig       = isset($config['router']) ? $config['router'] : array();
        $router             = HttpRouter::factory($routerConfig);
        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);

        $this->mockAuth();
    }

    public function mockAuth()
    {
        $ZfcUserMock = $this->getMock('ZfcUser\Entity\UserInterface');

        $ZfcUserMock->expects($this->any())
            ->method('getId')
            ->will($this->returnValue('1'));

        $authMock = $this->getMock('ZfcUser\Controller\Plugin\ZfcUserAuthentication');

        $authMock->expects($this->any())
            ->method('hasIdentity')
            -> will($this->returnValue(true));

        $authMock->expects($this->any())
            ->method('getIdentity')
            ->will($this->returnValue($ZfcUserMock));

        $this->controller->getPluginManager()
            ->setService('zfcUserAuthentication', $authMock);

        $this->user = $ZfcUserMock;
    }

    public function testGetListCanBeAccessed()
    {
        $this->overtimeRepository
            ->expects($this->once())
            ->method('findByCriteriaAndDateRange')
            ->with(
                [],
                [
                    new \DateTime("first day of this month 00:00:00"),
                    new \DateTime("first day of next month 00:00:00")
                ]
            )
            ->will($this->returnValue($this->paginate()));

        $this->routeMatch->setParam('action', 'list');
        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
    }

    public function testGetListCanBeFilteredByState()
    {
        $this->overtimeRepository
            ->expects($this->once())
            ->method('findByCriteriaAndDateRange')
            ->with(
                ['state' => 'whut'],
                [
                    new \DateTime("first day of this month 00:00:00"),
                    new \DateTime("first day of next month 00:00:00")
                ]
            )
            ->will($this->returnValue($this->paginate()));

        $this->routeMatch->setParam('action', 'list');
        $this->routeMatch->setParam('state', 'whut');
        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
    }

    public function testGetListCanBeFilteredByUser()
    {
        $userId = 12;

        $this->overtimeRepository
            ->expects($this->once())
            ->method('findByCriteriaAndDateRange')
            ->with(
                ['state' => 'whut', 'user' => $userId],
                [
                    new \DateTime("first day of this month 00:00:00"),
                    new \DateTime("first day of next month 00:00:00")
                ]
            )
            ->will($this->returnValue($this->paginate()));

        $this->routeMatch->setParam('action', 'list');
        $this->routeMatch->setParam('state', 'whut');
        $this->routeMatch->setParam('user', $userId);
        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
    }

    public function testEditActionOverridesParentAndSetsValidationGroup()
    {
        $this->form
            ->expects($this->once())
            ->method('setValidationGroup')
            ->with([
                'overtime' => [
                    'state',
                    'date',
                    'duration',
                    'notes',
                ]
            ]);

        $this->routeMatch->setParam('action', 'edit');
        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        //then should redirect as no ID present
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/admin/overtime/list', $response->getHeaders()->get('location')->getFieldValue());
    }

    /**
     * @param array $data
     * @return Paginator
     */
    public function paginate(array $data = [])
    {
        return new Paginator(new ArrayAdapter($data));
    }
}
