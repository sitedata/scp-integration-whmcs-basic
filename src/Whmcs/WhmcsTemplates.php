<?php

namespace Scp\WhmcsBasic\Whmcs;
use Scp\WhmcsBasic\Server\ServerService;
use Scp\WhmcsBasic\Client\ClientService;
use Scp\WhmcsBasic\Api;
use Scp\WhmcsBasic\Database\Database;

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

    public function __construct(
        Api $api,
        Database $database,
        ClientService $client,
        ServerService $server
    ) {
        $this->api = $api;
        $this->client = $client;
        $this->server = $server;
        $this->database = $database;
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

        return [
            'templatefile' => 'clientarea',
            'vars' => [
                'password' => $password,
                'url_action' => $urlAction,
                'server' => $server,
                'MODULE_FOLDER' => '/modules/servers/synergycpbasic',
                'apiKey' => $apiKey->key,
                'apiUrl' => $urlApi,
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

    public static function functions()
    {
        return [
            static::CLIENT_AREA => 'clientArea',
        ];
    }
}
