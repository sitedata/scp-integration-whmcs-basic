<?php

namespace Scp\WhmcsBasic\Whmcs;
use Scp\WhmcsBasic\Server\ServerService;
use Scp\WhmcsBasic\Client\ClientService;
use Scp\WhmcsBasic\Api;
use Scp\WhmcsBasic\Database\Database;
use Scp\Api\ApiSingleSignOn;
use Scp\WhmcsBasic\Whmcs\WhmcsConfig;

class WhmcsTemplates
{
    const CLIENT_AREA = 'ClientArea';

    const ALPHA_NUM = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * @var Api
     */
    protected $api;

    /**
     * @var ClientService
     */
    protected $client;

    /**
     * @var ServerService
     */
    protected $server;

    /**
     * @var Database
     */
    protected $database;

    /**
     * @var WhmcsConfig
     */
    protected $config;

    /**
     * @param Api                       $api
     * @param Database                  $database
     * @param ClientService             $client
     * @param ServerService             $server
     * @param WhmcsConfig               $config
     */
    public function __construct(
        Api $api,
        Database $database,
        ClientService $client,
        ServerService $server,
        WhmcsConfig $config
    ) {
        $this->api = $api;
        $this->client = $client;
        $this->server = $server;
        $this->database = $database;
        $this->config = $config;
    }

    public function clientArea()
    {
        if (!$server = $this->server->current()) {
            return;
        }

        $server = $server->full();
        $billingId = $this->server->currentBillingId();
        $urlAction = sprintf(
            'clientarea.php?action=productdetails&id=%d&modop=custom&a=',
            $billingId
        );
        $apiKey = $this->client->apiKey();
        $urlApi = $this->api->baseUrl();
        $password = $this->generatePassword(10);
        $manage = $this->config->option(WhmcsConfig::CLIENT_MANAGE_BUTTON);
        $embed = $this->config->option(WhmcsConfig::CLIENT_EMBEDDED_SERVER_MANAGE);

        return [
            'templatefile' => 'clientarea',
            'vars' => [
                'password' => $password,
                'url_action' => $urlAction,
                'server' => $server,
                'MODULE_FOLDER' => '/modules/servers/synergycpbasic',
                'apiKey' => $apiKey->key,
                'apiUrl' => $urlApi,
                'manage' => $manage,
                'embed' => $embed,
                'embedUrl' => $this->getEmbeddedUrl(),
            ],
        ];
    }

    private function generatePassword($length, $characters = self::ALPHA_NUM)
    {
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    /**
     * @return string
     *
     */
    public function getEmbeddedUrl()
    {
        $apiKey = $this->client->apiKey();
        $server = $this->server->currentOrFail();
        return (new ApiSingleSignOn($apiKey))
          ->view($server)
          ->embed()
          ->url();
    }

    public static function functions()
    {
        return [
            static::CLIENT_AREA => 'clientArea',
        ];
    }
}
