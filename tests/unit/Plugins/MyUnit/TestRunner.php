<?php

namespace QualityCheck\Plugins\MyUnit;

/**
 * Mock class for testing purposes.
 */
class TestRunner implements \Epa\Api\Observer
{
    public function notify(\Epa\Api\Event $event)
    {
        $event->addLogFile('foo log', sys_get_temp_dir());
    }
}
