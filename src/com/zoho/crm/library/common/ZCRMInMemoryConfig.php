<?php
class ZCRMInMemoryConfig implements IZCRMConfig
{
    private $settings;

    public function __construct($settings)
    {
        $this->settings = $settings;
    }

    public function getZCRMConfigProperties()
    {
        return $this->settings;
    }
}