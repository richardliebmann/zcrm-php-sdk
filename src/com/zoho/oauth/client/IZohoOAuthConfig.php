<?php
interface IZohoOAuthConfig
{
    /**
     * @return array
     *   client_id=
     *   client_secret=
     *   redirect_uri=
     *   accounts_url=https://accounts.zoho.com
     *   token_persistence_path=
     *   access_type=offline
     *   persistence_handler_class=ZohoOAuthPersistenceHandler
     */
    function getOAuthConfigProperties();
}