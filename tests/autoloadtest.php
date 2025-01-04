<?php

use PHPUnit\Framework\TestCase;
use PodloveSubscribeButton\Settings\Buttons;

class AutoLoadTest extends TestCase
{
    public function testSettingsButtonClassCanBeInstantiated()
    {
        $buttons = new Buttons();
        $this->assertInstanceOf(Buttons::class, $buttons);
    }
}