<?php
require_once realpath(dirname(__FILE__)."/../common/ZohoOAuthUtil.php");
require_once realpath(dirname(__FILE__)."/../common/ZohoOAuthConstants.php");
require_once realpath(dirname(__FILE__)."/../common/ZohoOAuthParams.php");
require_once realpath(dirname(__FILE__)."/../clientapp/ZohoOAuthPersistenceHandler.php");
require_once realpath(dirname(__FILE__)."/../clientapp/ZohoOAuthPersistenceByFile.php");
require_once realpath(dirname(__FILE__)."/../common/OAuthLogger.php");
require_once 'ZohoOAuthClient.php';
require_once 'ZohoOAuthFileConfig.php';
require_once 'ZohoOAuthInMemoryConfig.php';

class ZohoOAuth
{

	private static $configProperties =array();

    /**
     * @param $config IZohoOAuthConfig
     */
	public static function initializeWithConfig($config)
    {
        if($config == null) {
            $config = new ZohoOAuthFileConfig(false);
        }

        self::$configProperties = $config->getOAuthCOnfigProperties();

        $oAuthParams=new ZohoOAuthParams();

        $oAuthParams->setAccessType(self::getConfigValue(ZohoOAuthConstants::ACCESS_TYPE));
        $oAuthParams->setClientId(self::getConfigValue(ZohoOAuthConstants::CLIENT_ID));
        $oAuthParams->setClientSecret(self::getConfigValue(ZohoOAuthConstants::CLIENT_SECRET));
        $oAuthParams->setRedirectURL(self::getConfigValue(ZohoOAuthConstants::REDIRECT_URL));
        ZohoOAuthClient::getInstance($oAuthParams);
    }
	
	public static function initializeWithOutInputStream()
	{
		self::initialize(false);
	}
	
	public static function initialize($configFilePointer)
	{
        try
        {
            $config = new ZohoOAuthFileConfig($configFilePointer);

            self::initializeWithConfig($config);
        }
        catch (IOException $ioe)
        {
            OAuthLogger::warn("Exception while initializing Zoho OAuth Client.. ". ioe);
            throw ioe;
        }
	}
	
	public static function getConfigValue($key)
	{
		return self::$configProperties[$key];
	}
	
	public static function getAllConfigs()
	{
		return self::$configProperties;
	}
	
	public static function getIAMUrl()
	{
		return self::getConfigValue(ZohoOAuthConstants::IAM_URL);
	}
	
	public static function getGrantURL()
	{
		return self::getIAMUrl()."/oauth/v2/auth";
	}
	
	public static function getTokenURL()
	{
		return self::getIAMUrl()."/oauth/v2/token";
	}
	
	public static function getRefreshTokenURL()
	{
		return self::getIAMUrl()."/oauth/v2/token";
	}
	
	public static function getRevokeTokenURL()
	{
		return self::getIAMUrl()."/oauth/v2/token/revoke";
	}
	
	public static function getUserInfoURL()
	{
		return self::getIAMUrl()."/oauth/user/info";
	}
	
	public static function getClientID()
	{
		return self::getConfigValue(ZohoOAuthConstants::CLIENT_ID);
	}
	
	public static function getClientSecret()
	{
		return self::getConfigValue(ZohoOAuthConstants::CLIENT_SECRET);
	}
	
	public static function getRedirectURL()
	{
		return self::getConfigValue(ZohoOAuthConstants::REDIRECT_URL);
	}
	
	public static function getAccessType()
	{
		return self::getConfigValue(ZohoOAuthConstants::ACCESS_TYPE);
	}
	
	public static function getPersistenceHandlerInstance()
	{
		try
		{
		    if(ZohoOAuth::getConfigValue("token_persistence_path")!="")
            {
                new ZohoOAuthPersistenceByFile();
            }
            else if(ZohoOAuth::getConfigValue("persistence_handler_instance") != null) {
                $handler = ZohoOAuth::getConfigValue("persistence_handler_instance");

                return $handler;
            }
            else if(ZohoOAuth::getConfigValue("persistence_handler_class") != "") {
		        $handler = ZohoOAuth::getConfigValue("persistence_handler_class");

		        return new $handler();
            }
            else {
		        return ZohoOAuthPersistenceHandler();
            }
		}
		catch (Exception $ex)
		{
			throw new ZohoOAuthException($ex);
		}
	}

    /**
     * @return ZohoOAuthClient
     * @throws ZohoOAuthException
     */
	public static function getClientInstance()
	{
		if(ZohoOAuthClient::getInstanceWithOutParam() == null)
		{
			throw new ZohoOAuthException("ZohoOAuth.initializeWithOutInputStream() must be called before this.");
		}
		return ZohoOAuthClient::getInstanceWithOutParam();
	}
	
}
?>