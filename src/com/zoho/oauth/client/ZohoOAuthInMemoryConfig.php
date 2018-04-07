<?php
class ZohoOAuthInMemoryConfig implements IZohoOAuthConfig
{
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    function getOAuthConfigProperties()
    {
        return $this->config;
    }
}