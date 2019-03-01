<?php
namespace Bee\Di;

/**
 * Container
 *
 * @package Bee\Di
 */
class Container implements ContainerInterface
{
    /**
     * @var Service[]
     */
    protected $services;

    /**
     * @var Service[]
     */
    protected $sharedInstances;

    /**
     * @var bool
     */
    protected $freshInstance = false;

    /**
     * @var Container
     */
    protected static $default;

    /**
     * Container constructor.
     */
    public function __construct()
    {
        if (!self::$default) {
            self::$default = $this;
        }
    }

    /**
     * Registers a service in the services container
     *
     * @param string $name
     * @param $definition
     * @param bool $shared
     * @return ServiceInterface
     */
    public function set(string $name, $definition, bool $shared = false): ServiceInterface
    {
        $service = new Service($name, $definition, $shared);

        $this->services[$name] = $service;

        return $service;
    }

    /**
     * Registers an "always shared" service in the services container
     *
     * @param string $name
     * @param $definition
     * @return ServiceInterface
     */
    public function setShared(string $name, $definition): ServiceInterface
    {
        return $this->set($name, $definition, true);
    }

    /**Removes a service in the services container
     *
     * @param string $name
     */
    public function remove(string $name)
    {
        unset($this->services[$name]);
        unset($this->sharedInstances[$name]);
    }

    /**
     * Attempts to register a service in the services container
     * Only is successful if a service hasn't been registered previously
     * with the same name
     *
     * @param string $name
     * @param $definition
     * @param bool $shared
     * @return ServiceInterface|false
     */
    public function attempt(string $name, $definition, bool $shared = false): ServiceInterface
    {
        if (isset($this->services[$name])) {
            return false;
        }

        $service               = new Service($name, $definition, $shared);
        $this->services[$name] = $service;

        return $service;
    }

    /**
     * Resolves the service based on its configuration
     *
     * @param string $name
     * @param null $parameters
     * @return mixed
     * @throws Exception
     */
    public function get(string $name, $parameters = null)
    {
        if (isset($this->services[$name])) {
            $instance = $this->services[$name]->resolve($parameters, $this);
        } else {
            if (!class_exists($name)) {
                throw new Exception("Service '" . $name . "' wasn't found in the dependency injection container");
            }

            if (is_array($parameters) && count($parameters)) {
                $instance = new $name($parameters);
            } else {
                $instance = new $name;
            }
        }

        if ($instance instanceof InjectionAwareInterface) {
            $instance->setDi($this);
        }

        return $instance;
    }

    /**
     * Returns a shared service based on their configuration
     *
     * @param string $name
     * @param null $parameters
     * @return mixed
     * @throws Exception
     */
    public function getShared(string $name, $parameters = null)
    {
        $instance = null;

        if (isset($this->sharedInstances[$name])) {
            $instance            = $this->sharedInstances[$name];
            $this->freshInstance = false;
        } else {
            $instance                     = $this->get($name, $parameters);
            $this->sharedInstances[$name] = $instance;
            $this->freshInstance          = true;
        }

        return $instance;
    }

    /**
     * Sets a service using a raw Bee\Container\Service definition
     *
     * @param string $name
     * @param ServiceInterface $rawDefinition
     * @return ServiceInterface
     */
    public function setRaw(string $name, ServiceInterface $rawDefinition): ServiceInterface
    {
        $this->services[$name] = $rawDefinition;

        return $rawDefinition;
    }

    /**
     * Returns a service definition without resolving
     *
     * @param string $name
     * @return mixed
     * @throws Exception
     */
    public function getRaw(string $name)
    {
        if (!isset($this->services[$name])) {
            return $this->services[$name]->getDefinition();
        }

        throw new Exception("Service '" . $name . "' wasn't found in the dependency injection container");
    }

    /**
     * Returns the corresponding Bee\Container\Service instance for a service
     *
     * @param string $name
     * @return ServiceInterface
     * @throws Exception
     */
    public function getService(string $name): ServiceInterface
    {
        if (isset($this->services[$name])) {
            return $this->services[$name];
        }

        throw new Exception("Service '" . $name . "' wasn't found in the dependency injection container");
    }

    /**
     * Check whether the DI contains a service by a name
     *
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return isset($this->services[$name]);
    }

    /**
     * Check whether the last service obtained via getShared produced a fresh instance or an existing one
     *
     * @return bool
     */
    public function wasFreshInstance(): bool
    {
        return $this->freshInstance;
    }

    /**
     * Return the services registered in the DI
     *
     * @return ServiceInterface[]
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * Whether a offset exists
     * @link https://php.net/manual/en/arrayaccess.offsetexists.php
     * @param string $name <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($name) : bool
    {
        return $this->has($name);
    }

    /**
     * Offset to retrieve
     * @link https://php.net/manual/en/arrayaccess.offsetget.php
     * @param $name
     * @return mixed Can return all value types.
     * @throws Exception
     * @since 5.0.0
     */
    public function offsetGet($name)
    {
        return $this->getShared($name);
    }

    /**
     * Offset to set
     * @link https://php.net/manual/en/arrayaccess.offsetset.php
     * @param $name
     * @param $definition
     * @return bool
     * @since 5.0.0
     */
    public function offsetSet($name, $definition) : bool
    {
        $this->setShared($name, $definition);

        return true;
    }

    /**
     * Offset to unset
     * @link https://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $name <p>
     * The offset to unset.
     * </p>
     * @since 5.0.0
     * @return bool
     */
    public function offsetUnset($name) : bool
    {
        return false;
    }

    /**
     * Use ServiceProvider register service
     *
     * @param ServiceProviderInterface $provider
     */
    public function register(ServiceProviderInterface $provider)
    {
        $provider->register($this);
    }

    /**
     * Set a default dependency injection container to be obtained into static methods
     *
     * @param ContainerInterface $dependencyInjector
     */
    public static function setDefault(ContainerInterface $dependencyInjector)
    {
        self::$default = $dependencyInjector;
    }

    /**
     * Return the last DI created
     *
     * @return ContainerInterface
     */
    public static function getDefault(): ContainerInterface
    {
        return self::$default;
    }

    /**
     * Resets the internal default DI
     */
    public static function reset()
    {
        self::$default = null;
    }
}
