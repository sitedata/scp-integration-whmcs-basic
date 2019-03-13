<?php

namespace Scp\WhmcsBasic\Whmcs;

use Scp\Api\ApiResponse;
use Scp\Server\Server;
use Scp\WhmcsBasic\Client\ClientService;
use Scp\WhmcsBasic\Api;
use Scp\WhmcsBasic\Server\ServerService;
use Scp\Entity\Entity;
use Scp\Api\ApiKey;
use Scp\Api\ApiSingleSignOn;
use function sprintf;

/**
 * Handle the buttons that appear on WHMCS.
 */
class WhmcsButtons
{
    /**
     * Internal Identifiers.
     */
    /**
     * @var string
     */
    const CLIENT_ACTIONS = 'ClientAreaCustomButtonArray';
    /**
     * @var string
     */
    const ADMIN_ACTIONS = 'AdminCustomButtonArray';
    /**
     * @var string
     */
    const CLIENT_FUNCTIONS = 'ClientAreaAllowedFunctions';
    /**
     * @var string
     */
    const ADMIN_LOGIN_LINK = 'LoginLink';

    /**
     * @var string
     */
    const MANAGE = 'btn_manage';
    /**
     * @var string
     */
    const CREATE_CLIENT = 'btn_create_client';

    /**
     * @var Api
     */
    protected $api;

    /**
     * @var ServerService
     */
    protected $server;

    /**
     * @var ClientService
     */
    protected $clients;

    /**
     * @param Api                 $api
     * @param ClientService       $clients
     * @param ServerService       $server
     */
    public function __construct(
        Api $api,
        ServerService $server,
        ClientService $clients
    ) {
        $this->api = $api;
        $this->server = $server;
        $this->clients = $clients;
    }

    /**
     * @return array
     */
    public function client()
    {
        if (!$server = $this->server->current()) {
            return [];
        }

        return $this->otherActions();
    }

    /**
     * @return array
     */
    public static function admin()
    {
        return [
            'Create Client' => static::CREATE_CLIENT,
        ];
    }

    /**
     * @return array
     */
    protected function otherActions()
    {
        return [
            'Manage on SynergyCP' => static::MANAGE,
        ];
    }

    /**
     * @return array
     */
    public static function functions()
    {
        return [
            static::MANAGE => 'manage',
            static::CLIENT_ACTIONS => 'client',
            static::ADMIN_LOGIN_LINK => 'loginLink',
            //
            // // Admin only.
            static::CREATE_CLIENT => 'createClient',
        ];
    }

    /**
     * @return string
     */
    public function createClient()
    {
        $this->clients->getOrCreate();

        return 'success';
    }

    /**
     * Displayed on the view product page of WHMCS Admin.
     */
    public function loginLink()
    {
        if (isset($_GET['login_client'])) {
            $this->manage();
        }

        if (isset($_GET['login_admin'])) {
            $this->manageAsAdmin();
        }
        ?>

        <div class="btn-group" style="margin-bottom: 10px">
            <div class="btn-dropdown">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Manage on SynergyCP
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="?<?php echo $_SERVER['QUERY_STRING'] ?>&login_client" target="_blank">As Client</a></li>
                    <li><a href="?<?php echo $_SERVER['QUERY_STRING'] ?>&login_admin" target="_blank">As Administrator</a></li>
                </ul>
            </div>
        </div>

        <?php

    }

    /**
     * Manage Single Sign On.
     */
    public function manage()
    {
        $client = $this->clients->getOrCreate();
        $server = $this->getServer();

        // Generate single sign on for client
        $apiKey = with(new ApiKey())->owner($client)->save();
        $sso = new ApiSingleSignOn($apiKey);

        if ($server) {
            $sso->view($server);
        }

        $url = $sso->url();

        $this->transferTo($url);
    }

    /**
     * Manage as admin Single Sign On.
     */
    public function manageAsAdmin()
    {
        try {
            $server = $this->server->currentOrFail();
        } catch (\RuntimeException $exc) {
            $this->exitWithMessage($exc->getMessage());
        }

        $this->transferTo(
            $this->api->getAdminUrlFromApi(sprintf(
                'hardware/server/%d',
                $server->id
            ))
        );
    }

    /**
     * @param string $url
     * @param string $linkText
     */
    protected function transferTo($url, $linkText = 'SynergyCP')
    {
        $this->exitWithMessage(sprintf(
            '<script type="text/javascript">window.location.href="%s"</script>'.
            'Transfer to <a href="%s">%s</a>.',
            $url,
            $url,
            $linkText
        ));
    }

    /**
     * @param string $message
     */
    protected function exitWithMessage($message)
    {
        // Clear output buffer so no other page contents show.
        ob_clean();

        die($message);
    }

    /**
     * @return Server
     *
     * @throws \RuntimeException
     */
    protected function getServer()
    {
        return $this->server->currentOrFail();
    }

    /**
     * @return array
     */
    public static function staticFunctions()
    {
        return [
            static::ADMIN_ACTIONS => 'admin',
        ];
    }
}
