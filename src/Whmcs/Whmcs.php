<?php

namespace Scp\WhmcsBasic\Whmcs;
use Scp\Support\Arr;

class Whmcs
{
    const META = 'MetaData';

    /**
     * @var array
     */
    protected $params;

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    public function getParam($param, $default = null)
    {
        return Arr::get($this->getParams(), $param, $default);
    }

    /**
     * Define module related meta data.
     *
     * Values returned here are used to determine module related abilities and
     * settings.
     *
     * @see http://docs.whmcs.com/Provisioning_Module_Meta_Data_Parameters
     *
     * @return array
     */
    public static function meta()
    {
        return [
            'DisplayName' => 'Synergy Control Panel - Basic',

            // Use WHMCS API Version 1.1
            'APIVersion' => '1.1',

            // Set true if module requires a server to work
            'RequiresServer' => true,
        ];
    }

    public static function staticFunctions()
    {
        return [
            static::META => 'meta',
        ];
    }
}
