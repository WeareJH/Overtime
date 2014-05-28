<?php

namespace JhOvertimeTest;

use JhOvertime\Module;

/**
 * Class ModuleTest
 * @package JhOvertimeTest
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class ModuleTest extends \PHPUnit_Framework_TestCase
{
    public function testGetConfig()
    {
        $module = new Module();

        $this->assertInternalType('array', $module->getConfig());
        $this->assertSame($module->getConfig(), unserialize(serialize($module->getConfig())), 'Config is serializable');
    }

    public function testGetAutoloaderConfig()
    {
        $module = new Module;
        $this->assertInternalType('array', $module->getAutoloaderConfig());
    }
}
