<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 02/03/2020 14:39
 */

namespace Gta\DataExportBundle\Builder;


use Gta\CoreBundle\Contracts\Traits\ArrayAccessTrait;
use Gta\DataExportBundle\Exception\TsUnusedConfigKey;
use Gta\DataExportBundle\Utils\TsKey;

/**
 * Class YmlConfigArrayAccess
 *
 * @package Gta\DataExportBundle\Builder
 * @author  Seif <ben.s@mipih.fr> (02/03/2020/ 14:42)
 * @version 19
 */
class YmlConfigArrayAccess implements \ArrayAccess
{

    use ArrayAccessTrait;

    private static $instances = [];
    /**
     * @var array
     */
    private $configuratedKeys;
    /**
     * @var array
     */
    private $usedKeys = [];
    /**
     * @var
     */
    private $id;

    /**
     * YmlConfigArrayAccess constructor.
     *
     * @param       $id
     * @param array $store
     */
    final private function __construct($id, array $store)
    {
        $this->id = $id;
        $this->store = $store;
        $this->configuratedKeys = array_keys($store);
    }

    /**
     * dumps all config objects
     *
     * @return void
     * @author Seif <ben.s@mipih.fr>
     */
    final public static function dump()
    {
        dump(self::$instances);
    }

    /**
     * @param $id
     *
     * @return \Gta\DataExportBundle\Builder\YmlConfigArrayAccess
     * @author Seif <ben.s@mipih.fr>
     */
    final public static function getConfig($id)
    {
        if (!self::hasConfig($id)) {
            throw new \InvalidArgumentException('No config object with id '.$id);
        }

        return self::getInstance($id);
    }

    /**
     * Performs a test if config object with ID $id already exists, ebtter use this function prior calling getConfig
     * and create config
     *
     * @param $id
     *
     * @return bool
     * @author Seif <ben.s@mipih.fr>
     */
    final public static function hasConfig($id)
    {
        return isset(self::$instances[$id]);
    }

    /**
     * Creates a new config object and returns it, this function throws an exception if config object already exists
     *
     * @param       $id
     * @param array $store
     *
     * @return \Gta\DataExportBundle\Builder\YmlConfigArrayAccess
     * @author Seif <ben.s@mipih.fr>
     */
    final public static function createConfig($id, array $store)
    {
        if (self::hasConfig($id)) {
            throw new \InvalidArgumentException('Config object with id '.$id.' already exists');
        }

        return self::getInstance($id, $store);
    }

    /**
     * @param array $configs
     *
     * @author Seif <ben.s@mipih.fr>
     */
    final public static function createConfigs(array $configs)
    {
        foreach ($configs as $config) {
            self::createConfig($config[0], $config[1]);
        }
    }

    /**
     * Performs verification for all config objects
     *
     * @param bool $throwException
     *
     * @throws \Exception
     * @author Seif <ben.s@mipih.fr>
     */
    final static public function checkAllConfig($throwException = false)
    {
        $unusedKeys = array();
        /** @var self $configObject */
        foreach (self::$instances as $configObject) {
            # we put elemnts in array only if unused keys are found, in order to perform the final test for exception throwing
            if (false !== $configObject->getUnusedKeys()) {
                $unusedKeys[$configObject->getId()] = $configObject->getUnusedKeys();
            }

        }
        if (!empty($unusedKeys) && true === $throwException) {
            var_dump($unusedKeys);
            throw new TsUnusedConfigKey('Some config keys were not used');
        }
    }

    /**
     * @param $userParams
     *
     * @author Seif <ben.s@mipih.fr>
     */
    final public static function overrideConfigParams($userParams)
    {
        /**
         * @param string $configKey        it's the key wich is user in the yml config file which has to be identical to request param name
         * @param string $configSectionKey [header, left, body, print_method_calls, print_options]
         */
        $setParam = function ($configKey, $configSectionKey) use ($userParams) {
            if (isset($userParams[$configKey])) {
                self::getConfig($configSectionKey)->store[$configKey] = $userParams[$configKey];
            }
        };
        # print orientation
        $setParam(TsKey::K_PRINT_ORIENTATION, TsKey::K_PRINT_METHOD_CALLS);
        # paper size
        $setParam(TsKey::K_PRINT_PAPER_SIZE_INDEX, TsKey::K_PRINT_METHOD_CALLS);
        # shwo activites
        $setParam(TsKey::K_CELL_SHOW_ACTIVITE, TsKey::K_BODY);
        # show remuneration
        $setParam(TsKey::K_CELL_SHOW_REMUNERATION, TsKey::K_BODY);
        # show indicateurs
        $setParam(TsKey::K_CELL_SHOW_INDICATEURS, TsKey::K_BODY);
        # show couverture
        $setParam(TsKey::K_CELL_SHOW_COUVERTURE, TsKey::K_BODY);
    }

    /**
     * @param       $id
     * @param array $store
     *
     * @return $this
     * @author Seif <ben.s@mipih.fr>
     */
    final private static function getInstance($id, $store = array())
    {
        if (isset(self::$instances[$id])) {
            return self::$instances[$id];
        }

        return self::$instances[$id] = new YmlConfigArrayAccess($id, $store);

    }

    /**
     * @return array
     * @author Seif <ben.s@mipih.fr>
     */
    public function toArray()
    {
        return $this->store;
    }

    /**
     * modifying config object in runtime is not supported yet
     * you can remove this function so you can set a config
     *
     * @param mixed $offset
     * @param mixed $value
     *
     * @author Seif <ben.s@mipih.fr>
     */
    public function offsetSet($offset, $value)
    {
        trigger_error('You cannot modify config in runtime', E_USER_ERROR);
    }

    /**
     * @param mixed $offset
     *
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    final public function offsetGet($offset)
    {
        if (!array_key_exists($offset, $this->store)) {
            throw new \InvalidArgumentException('No element with key '.$offset);
        }
        if (!in_array($offset, $this->usedKeys)) {
            $this->usedKeys [] = $offset;
        }


        return $this->store[$offset];
    }

    /**
     * Performs a config keys verification for the calling object
     *
     * @param bool $returnKeys
     *
     * @return array|void
     * @author Seif <ben.s@mipih.fr>
     */
    final public function checkUnusedConfigKeys($returnKeys = false)
    {
        $unusedKeys = $this->getUnusedKeys(true);
        if (true === $returnKeys) {
            return $unusedKeys;
        }
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param bool $throwException
     *
     * @return array|int
     * @author Seif <ben.s@mipih.fr>
     */
    final private function getUnusedKeys($throwException = false)
    {
        # get unused keys
        if ($unusedKeys = array_diff($this->configuratedKeys, $this->usedKeys)) {
            if (true === $throwException) {
                throw new \LogicException($this->id.' ['.implode(' ,', $unusedKeys).']');
            }

            return $unusedKeys;
        }

        # note that the other way problem (access a config that was not set up in config file) is managed in offsetGet
        return false;
    }
}