<?php

namespace JhOvertimeTest\Form;

use JhOvertime\Form\OvertimeForm;

/**
 * Class OvertimeFormTest
 * @package JhFlexiTimeTest\Form
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class OvertimeFormTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test Form Elements
     */
    public function testFormElements()
    {
        $objectManager  = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $fieldset = $this->getMockBuilder('JhOvertime\Form\OvertimeFieldset')
            ->disableOriginalConstructor()
            ->getMock();

        $form = new OvertimeForm($objectManager, $fieldset);

        $this->assertTrue($form->has("overtime"));
        $this->assertTrue($form->has("submit"));
    }
}
