<?php

namespace JhOvertimeTest\Form;

use JhOvertime\Form\OvertimeFieldset;

/**
 * Class OvertimeFieldsetTest
 * @package JhFlexiTimeTest\Form\Fieldset
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class OvertimeFieldsetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test elements exist
     */
    public function testFieldsetHasAllElements()
    {
        $objectManager = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $fieldset = new OvertimeFieldset($objectManager);

        $this->assertTrue($fieldset->has("id"));
        $this->assertTrue($fieldset->has("date"));
        $this->assertTrue($fieldset->has("duration"));
        $this->assertTrue($fieldset->has("notes"));
    }

    /**
     * Test Input Filter Spec is Valid
     */
    public function testFieldSetInputFilterSpec()
    {
        $objectManager      = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $fieldset           = new OvertimeFieldset($objectManager);
        $inputFilterFactory = new \Zend\InputFilter\Factory();

        $inputFilter = $inputFilterFactory->createInput($fieldset->getInputFilterSpecification());
        $this->assertInstanceOf('Zend\InputFilter\Input', $inputFilter);
    }
}
