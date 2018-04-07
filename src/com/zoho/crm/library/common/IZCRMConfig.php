<?php

interface IZCRMConfig
{
    /**
     * @return array
     *   apiBaseUrl=www.zohoapis.com
     *   apiVersion=v2
     *   sandbox=false
     *   applicationLogFilePath=
     *   currentUserEmail=
     */
    public function getZCRMConfigProperties();
}