<?php
namespace Bee\Di;

/**
 * Interface ContainerInterface
 *
 * @package Bee\Container
 */
interface ContainerInterface extends \ArrayAccess
{
    /**
     * Registers a service in the services container
     * 
     * @param string $name
     * @param $definition
     * @param bool $shared
     * @return ServiceInterface
     */
    public function set(string $name, $definition, bool $shared = false) : ServiceInterface;

    /**
     * Registers an "always shared" service in the services container
     * 
     * @param string $name
     * @param $definition
     * @return ServiceInterface
     */
    public function setShared(string $name, $definition) : ServiceInterface;

    /**Removes a service in the services container
     * 
     * @param string $name
     * @return mixed
     */
    public function remove(string $name);

    /**
     * Attempts to register a service in the services container
     * Only is successful if a service hasn't been registered previously
     * with the same name
     * 
     * @param string $name
     * @param $definition
     * @param bool $shared
     * @return ServiceInterface
     */
    public function attempt(string $name, $definition, bool $shared = false) : ServiceInterface;

    /**
     * Resolves the service based on its configuration
     * 
     * @param string $name
     * @param null $parameters
     * @return mixed
     */
    public function get(string $name, $parameters = null);

    /**
     * Returns a shared service based on their configuration
     * 
     * @param string $name
     * @param null $parameters
     * @return mixed
     */
    public function getShared(string $name, $parameters = null);

    /**
     * Sets a service using a raw Bee\Container\Service definition
     * 
     * @param string $name
     * @param ServiceInterface $rawDefinition
     * @return ServiceInterface
     */
    public function setRaw(string $name, ServiceInterface $rawDefinition) : ServiceInterface;

    /**
     * Returns a service definition without resolving
     * 
     * @param string $name
     * @return mixed
     */
    public function getRaw(string $name);

    /**
     * Returns the corresponding Bee\Container\Service instance for a service
     * 
     * @param string $name
     * @return ServiceInterface
     */
    public function getService(string $name) : ServiceInterface;

    /**
     * Check whether the DI contains a service by a name
     * 
     * @param string $name
     * @return bool
     */
    public function has(string $name) : bool;

    /**
     * Check whether the last service obtained via getShared produced a fresh instance or an existing one
     * 
     * @return bool
     */
    public function wasFreshInstance() : bool;

    /**
     * Return the services registered in the DI
     * 
     * @return ServiceInterface[]
     */
    public function getServices();

    /**
     * Set a default dependency injection container to be obtained into static methods
     * 
     * @param ContainerInterface $dependencyInjector
     */
    public static function setDefault(ContainerInterface $dependencyInjector);

    /**
     * Return the last DI created
     * 
     * @return ContainerInterface
     */
    public static function getDefault() : ContainerInterface;

    /**
     * Resets the internal default DI
     * 
     * @return mixed
     */
    public static function reset();
}
