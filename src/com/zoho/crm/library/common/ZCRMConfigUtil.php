<?php
require_once 'CommonUtil.php';
require_once realpath(dirname(__FILE__)."/../../../oauth/client/ZohoOAuth.php");
require_once 'ZCRMFileConfig.php';
require_once 'ZCRMInMemoryConfig.php';

class ZCRMConfigUtil
{
    private static $configProperties=array();
    /**
     * @var IZCRMConfig
     */
    private static $config;

    public static function getInstance()
    {
        return new ZCRMConfigUtil();
    }

    /**
     * @param bool $initializeOAuth
     * @param IZCRMConfig $config
     * @param IZohoOAuthConfig $authConfig
     */
    public static function initialize($initializeOAuth, $config = null, $authConfig = null)
    {
        self::$config = $config;

        if(self::$config == null) {
            self::$config = new ZCRMFileConfig();
        }

        self::$configProperties = self::$config->getZCRMConfigProperties();

        if($initializeOAuth)
        {
            if($authConfig == null) {
                ZohoOAuth::initializeWithOutInputStream();
            }
            else {
                ZohoOAuth::initializeWithConfig($authConfig);
            }
        }
    }

    public static function loadConfigProperties($fileHandler)
    {
        $configMap=CommonUtil::getFileContentAsMap($fileHandler);
        foreach($configMap as $key=>$value)
        {
            self::$configProperties[$key]=$value;
        }
    }

    public static function getConfigValue($key)
    {
        return isset(self::$configProperties[$key])?self::$configProperties[$key]:'';
    }

    public static function setConfigValue($key,$value)
    {
        self::$configProperties[$key]=$value;
    }

    public static function getAPIBaseUrl()
    {
        return self::getConfigValue("apiBaseUrl");
    }

    public static function getAPIVersion()
    {
        return self::getConfigValue("apiVersion");
    }
    public static function getAccessToken()
    {
        $currentUserEmail= ZCRMRestClient::getCurrentUserEmailID();

        if ($currentUserEmail == null && self::getConfigValue("currentUserEmail") == null)
        {
            throw new ZCRMException("Current user should either be set in ZCRMRestClient or in configuration.properties file");
        }
        else if ($currentUserEmail == null)
        {
            $currentUserEmail = self::getConfigValue("currentUserEmail");
        }
        $oAuthCliIns = ZohoOAuth::getClientInstance();
        return $oAuthCliIns->getAccessToken($currentUserEmail);
    }

    public static function getAllConfigs()
    {
        return self::$configProperties;
    }
}
?>