<?php

namespace JhOvertimeTest\Controller;

use JhOvertime\Controller\OvertimeController;

use JhOvertime\Entity\Overtime;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use JhOvertimeTest\Util\ServiceManagerFactory;
use Zend\Stdlib\Parameters;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Class OvertimeControllerTest
 * @package JhOvertimeTest\Controller
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class OvertimeControllerTest extends AbstractHttpControllerTestCase
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

        $this->controller = new OvertimeController(
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
                ['user' => $this->user],
                [
                    new \DateTime("first day of this month 00:00:00"),
                    new \DateTime("first day of next month 00:00:00")
                ]
            )
            ->will($this->returnValue([]));

        $this->routeMatch->setParam('action', 'list');
        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
        $this->assertTrue(isset($result->user));
    }

    public function testGetListCanBeFilteredByState()
    {
        $this->overtimeRepository
            ->expects($this->once())
            ->method('findByCriteriaAndDateRange')
            ->with(
                ['state' => 'whut', 'user' => $this->user],
                [
                    new \DateTime("first day of this month 00:00:00"),
                    new \DateTime("first day of next month 00:00:00")
                ]
            )
            ->will($this->returnValue([]));

        $this->routeMatch->setParam('action', 'list');
        $this->routeMatch->setParam('state', 'whut');
        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
        $this->assertTrue(isset($result->user));
    }

    public function testAddActionShowsFormIfNotPost()
    {
        $this->routeMatch->setParam('action', 'add');
        $this->request->setMethod('GET');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
        $this->assertEquals($this->user, $result->getVariable('user'));
        $this->assertEquals($this->form, $result->getVariable('form'));
    }

    public function testAddActionReturnsFormOnValidationFail()
    {
        $this->routeMatch->setParam('action', 'add');
        $this->request->setMethod('POST');

        $post = new Parameters([
            'data1' => 'yeah',
        ]);
        $this->request->setPost($post);

        $this->form
            ->expects($this->once())
            ->method('setData')
            ->with($post);

        $this->form
            ->expects($this->once())
            ->method('bind')
            ->with($this->isInstanceOf('JhOvertime\Entity\Overtime'));

        $this->form
            ->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
        $this->assertEquals($this->user, $result->getVariable('user'));
        $this->assertEquals($this->form, $result->getVariable('form'));
    }

    public function testAddActionAddOvertimeRecordAndRedirects()
    {
        $this->routeMatch->setParam('action', 'add');
        $this->request->setMethod('POST');

        $this->overtimeService
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf('JhOvertime\Entity\Overtime'));

        $post = new Parameters([
            'data1' => 'yeah',
        ]);
        $this->request->setPost($post);

        $this->form
            ->expects($this->once())
            ->method('setData')
            ->with($post);

        $this->form
            ->expects($this->once())
            ->method('bind')
            ->with($this->isInstanceOf('JhOvertime\Entity\Overtime'));

        $this->form
            ->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals($response->getHeaders()->get('location')->getFieldValue(), '/overtime/list');
    }

    public function testAddActionReturnsFormIfSaveFails()
    {
        $this->routeMatch->setParam('action', 'add');
        $this->request->setMethod('POST');

        $e = new \Exception;
        $this->overtimeService
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf('JhOvertime\Entity\Overtime'))
            ->will($this->throwException($e));

        $post = new Parameters([
            'data1' => 'yeah',
        ]);
        $this->request->setPost($post);

        $this->form
            ->expects($this->once())
            ->method('setData')
            ->with($post);

        $this->form
            ->expects($this->once())
            ->method('bind')
            ->with($this->isInstanceOf('JhOvertime\Entity\Overtime'));


        $this->form
            ->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
        $this->assertEquals($this->user, $result->getVariable('user'));
        $this->assertEquals($this->form, $result->getVariable('form'));
    }

    public function testEditActionRedirectsIfNoId()
    {
        $this->routeMatch->setParam('action', 'edit');
        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals($response->getHeaders()->get('location')->getFieldValue(), '/overtime/list');
    }

    public function testEditActionRedirectsIfIdNotValid()
    {
        $id = 1;
        $this->routeMatch->setParam('action', 'edit');
        $this->routeMatch->setParam('id', $id);

        $e = new \Exception;
        $this->overtimeRepository
            ->expects($this->once())
            ->method('findOneByUserAndId')
            ->with($this->user, $id)
            ->will($this->throwException($e));

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals($response->getHeaders()->get('location')->getFieldValue(), '/overtime/list');
    }

    public function testEditActionEditsOvertimeRecordIfFoundAndRedirects()
    {
        $id = 1;
        $this->routeMatch->setParam('action', 'edit');
        $this->routeMatch->setParam('id', $id);

        $overtime = new Overtime();
        $this->overtimeRepository
            ->expects($this->once())
            ->method('findOneByUserAndId')
            ->with($this->user, $id)
            ->will($this->returnValue($overtime));

        $this->overtimeService
            ->expects($this->once())
            ->method('save')
            ->with($overtime);

        $this->request->setPost(new Parameters([
            'data1' => 'yeah',
        ]));

        $this->form
            ->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $this->request->setMethod('POST');
        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals($response->getHeaders()->get('location')->getFieldValue(), '/overtime/list');
    }

    public function testEditActionReturnsFormIfValidationFails()
    {
        $id = 1;
        $this->routeMatch->setParam('action', 'edit');
        $this->routeMatch->setParam('id', $id);

        $overtime = new Overtime();
        $this->overtimeRepository
            ->expects($this->once())
            ->method('findOneByUserAndId')
            ->with($this->user, $id)
            ->will($this->returnValue($overtime));

        $this->overtimeService
            ->expects($this->never())
            ->method('save');

        $this->request->setPost(new Parameters([
            'data1' => 'yeah',
        ]));

        $this->form
            ->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));

        $this->request->setMethod('POST');
        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
        $this->assertEquals($this->user, $result->getVariable('user'));
        $this->assertEquals($this->form, $result->getVariable('form'));
        $this->assertEquals($overtime, $result->getVariable('overtime'));
    }

    public function testEditActionReturnsFormIfSaveFails()
    {
        $id = 1;
        $this->routeMatch->setParam('action', 'edit');
        $this->routeMatch->setParam('id', $id);

        $overtime = new Overtime();
        $this->overtimeRepository
            ->expects($this->once())
            ->method('findOneByUserAndId')
            ->with($this->user, $id)
            ->will($this->returnValue($overtime));

        $e = new \Exception;
        $this->overtimeService
            ->expects($this->once())
            ->method('save')
            ->will($this->throwException($e));

        $this->request->setPost(new Parameters([
            'data1' => 'yeah',
        ]));

        $this->form
            ->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $this->request->setMethod('POST');
        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
        $this->assertEquals($this->user, $result->getVariable('user'));
        $this->assertEquals($this->form, $result->getVariable('form'));
        $this->assertEquals($overtime, $result->getVariable('overtime'));
    }

    public function testDeleteActionRedirectsIfNoId()
    {
        $this->routeMatch->setParam('action', 'delete');
        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals($response->getHeaders()->get('location')->getFieldValue(), '/overtime/list');
    }

    public function testDeleteActionRedirectsIfIdNotValid()
    {
        $id = 1;
        $this->routeMatch->setParam('action', 'delete');
        $this->routeMatch->setParam('id', $id);

        $e = new \Exception;
        $this->overtimeRepository
            ->expects($this->once())
            ->method('findOneByUserAndId')
            ->with($this->user, $id)
            ->will($this->throwException($e));

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals($response->getHeaders()->get('location')->getFieldValue(), '/overtime/list');
    }

    public function testDeleteActionDeletesOvertimeRecordIfFound()
    {
        $id = 1;
        $this->routeMatch->setParam('action', 'delete');
        $this->routeMatch->setParam('id', $id);

        $overtime = new Overtime();
        $this->overtimeRepository
            ->expects($this->once())
            ->method('findOneByUserAndId')
            ->with($this->user, $id)
            ->will($this->returnValue($overtime));

        $this->overtimeService
            ->expects($this->once())
            ->method('delete')
            ->with($overtime);

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals($response->getHeaders()->get('location')->getFieldValue(), '/overtime/list');

    }

    public function testValidateDateReturnsDateTimeObjectRepresentingDateIfValid()
    {
        $validString = '12-04-2012';
        $defaultReturn = new \DateTime("30 May 2015");
        $ret = $this->controller->validateDate($validString, $defaultReturn);
        $this->assertNotEquals($defaultReturn, $ret);
        $this->assertEquals($validString, $ret->format('d-m-Y'));
    }

    public function testValidateDateReturnsDefaultDateIfValidationFails()
    {
        $invalidDate = new \stdClass();
        $defaultReturn = new \DateTime("30 May 2015");
        $ret = $this->controller->validateDate($invalidDate, $defaultReturn);
        $this->assertSame($defaultReturn, $ret);
    }
}
