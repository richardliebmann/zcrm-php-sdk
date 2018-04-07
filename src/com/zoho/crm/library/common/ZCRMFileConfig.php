<?php
require_once "IZCRMConfig.php";

class ZCRMFileConfig implements IZCRMConfig
{
    public function __construct()
    {
    }

    public  function getZCRMConfigProperties()
    {
        $path=realpath(dirname(__FILE__)."/../../../../../resources/configuration.properties");
        $fileHandler=fopen($path,"r");
        if(!$fileHandler)
        {
            return;
        }
        return CommonUtil::getFileContentAsMap($fileHandler);
    }
}