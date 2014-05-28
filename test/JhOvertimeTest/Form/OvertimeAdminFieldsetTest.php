<?php

namespace JhOvertimeTest\Form;

use JhOvertime\Form\OvertimeAdminFieldset;

/**
 * Class OvertimeAdminFieldsetTest
 * @package JhOvertimeTest\Form
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class OvertimeAdminFieldsetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test elements exist
     */
    public function testFieldsetHasAllElements()
    {
        $objectManager = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $fieldset = new OvertimeAdminFieldset($objectManager);

        $this->assertTrue($fieldset->has("id"));
        $this->assertTrue($fieldset->has("date"));
        $this->assertTrue($fieldset->has("duration"));
        $this->assertTrue($fieldset->has("notes"));
        $this->assertTrue($fieldset->has("user"));
        $this->assertTrue($fieldset->has("state"));
    }

    /**
     * Test Input Filter Spec is Valid
     */
    public function testFieldSetInputFilterSpec()
    {
        $objectManager      = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $fieldset           = new OvertimeAdminFieldset($objectManager);
        $inputFilterFactory = new \Zend\InputFilter\Factory();

        $inputFilter = $inputFilterFactory->createInput($fieldset->getInputFilterSpecification());
        $this->assertInstanceOf('Zend\InputFilter\Input', $inputFilter);
    }
}
