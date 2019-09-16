<?php

namespace Scp\WhmcsBasic\Whmcs;

use Scp\Support\Collection;

class WhmcsConfig
{
    /**
     * Functions
     */
    const FORM = 'ConfigOptions';

    /**
     * Config Options (make sure to update count below when adding).
     */
    const CLIENT_MANAGE_BUTTON = 1;
    const CLIENT_EMBEDDED_SERVER_MANAGE = 2;

    /**
     * The 1-based index of the last Config Option.
     *
     * @var int
     */
    protected $countOptions = self::CLIENT_EMBEDDED_SERVER_MANAGE;

    const CLIENT_MANAGE_BUTTON_DESC = 'Adds a Manage on SynergyCP button to client server pages.';
    const CLIENT_EMBEDDED_SERVER_MANAGE_DESC = 'Adds an embedded Manage on SynergyCP iFrame to client server pages. This requires the SynergyCP API to have HTTPS enabled and for WHMCS to be configured to use it.';

    /**
     * @var Whmcs
     */
    protected $whmcs;

    public function __construct(
        Whmcs $whmcs
    ) {
        $this->whmcs = $whmcs;
    }

    public function get($key)
    {
        $params = $this->whmcs->getParams();

        return $params[$key];
    }

    public function option($key)
    {
        return $this->get('configoption'.$key);
    }

    public function options()
    {
        return $this->get('configoptions');
    }

    /**
     * @return mixed
     */
    public function getOption($option)
    {
        return $this->options()[$option];
    }

    public function form()
    {
        $config = [];

        for ($i = 1; $i <= $this->countOptions; ++$i) {
            $this->addFormOption($config, $i);
        }

        return $config;
    }

    protected function addFormOption(array &$config, $key)
    {
        switch ($key) {
        case static::CLIENT_MANAGE_BUTTON:
            return $config['Client Manage Button'] = [
                'Type' => 'yesno',
                'Default' => 'yes',
                'Description' => self::CLIENT_MANAGE_BUTTON_DESC,
            ];
        case static::CLIENT_EMBEDDED_SERVER_MANAGE:
            return $config['Embedded Client Manage Page '] = [
                'Type' => 'yesno',
                'Default' => 'yes',
                'Description' => self::CLIENT_EMBEDDED_SERVER_MANAGE_DESC,
            ];
        }
    }

    // protected function getDepartmentNames()
    // {
    //     $admin = $this->option(static::API_USER);
    //     $results = localAPI('getsupportdepartments', [], $admin);
    //     $departments = $this->getDepartmentsFromResults($results);
    //     $getName = function ($department) {
    //         return $department['name'];
    //     };
    //
    //     return with(new Collection($departments))
    //         ->keyBy('id')
    //         ->map($getName);
    // }

    // /**
    //  * @param  array  $results
    //  *
    //  * @return array
    //  */
    // protected function getDepartmentsFromResults(array $results)
    // {
    //     if ($results['result'] != 'success') {
    //         return [[
    //             'name' => 'Error: ' . json_encode($results),
    //         ]];
    //     }
    //
    //     return $results['departments']['department'];
    // }

    // /**
    //  * @param  string $value
    //  *
    //  * @return int
    //  */
    // protected function getDepartmentIdByName($value)
    // {
    //     $escaped = htmlspecialchars($value);
    //
    //     return $this->getDepartmentNames()->search($escaped);
    // }

    // public static function functions()
    // {
    //     return [
    //         static::FORM => 'form',
    //     ];
    // }
}
