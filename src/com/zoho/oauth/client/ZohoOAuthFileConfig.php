<?php
require_once "IZohoOAuthConfig.php";

class ZohoOAuthFileConfig implements  IZohoOAuthConfig
{
    private $configFilePointer;

    public function __construct($configFilePointer)
    {
        $this->configFilePointer = $configFilePointer;
    }

    public function getOAuthConfigProperties()
    {
        $configPath=realpath(dirname(__FILE__)."/../../../../resources/oauth_configuration.properties");
        $filePointer=fopen($configPath,"r");

        $configProperties = ZohoOAuthUtil::getFileContentAsMap($filePointer);

        if($this->$configFilePointer!=false)
        {
            $properties=ZohoOAuthUtil::getFileContentAsMap($configFilePointer);
            foreach($properties as $key=>$value)
            {
                $configProperties[$key]=$value;
            }
        }

        return $configProperties;
    }
}