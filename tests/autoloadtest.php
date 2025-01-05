<?php

use PHPUnit\Framework\TestCase;
use PodloveSubscribeButton\Settings\Buttons;
use PodloveSubscribeButton\Model\Button;
use PodloveSubscribeButton\Model\NetworkButton;
use PodloveSubscribeButton\Utils\MediaTypes;
use PodloveSubscribeButton\PodloveSubscribeButton;

class AutoLoadTest extends TestCase
{
    public function testSettingsButtonsClassCanBeInstantiated()
    {
        $buttons = new Buttons();
        $this->assertInstanceOf(Buttons::class, $buttons);
    }

    public function testModelButtonCanBeInstantiated()
    {
        $button = new Button();
        $this->assertInstanceOf(Button::class, $button);
    }

    public function testModelNetworkButtonCanBeInstantiated()
    {
        $button = new NetworkButton();
        $this->assertInstanceOf(NetworkButton::class, $button);
    }

    public function testUtilMediaTypesCanBeInstantiated()
    {
        $types = new MediaTypes();
        $this->assertInstanceOf(MediaTypes::class, $types);
    }

    public function testPodloveSubscribeButtonCanBeInstantiated()
    {
        $psb = new PodloveSubscribeButton();
        $this->assertInstanceOf(PodloveSubscribeButton::class, $psb);
    }

}