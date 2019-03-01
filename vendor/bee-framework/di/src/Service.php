<?php
namespace Bee\Di;

/**
 * Bee\Di\Service
 *
 * Represents individually a service in the services container
 *
 *<code>
 * $service = new \Bee\Di\Service(
 *     "request",
 *     "Bee\\Http\\Request"
 * );
 *
 * $request = service->resolve();
 *</code>
 */
class Service implements ServiceInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var mixed
     */
    protected $definition;

    /**
     * @var bool
     */
    protected $shared = false;

    /**
     * @var bool
     */
    protected $resolved = false;

    /**
     * @var object
     */
    protected $shareInstance;

    /**
     * Service constructor.
     *
     * @param string $name
     * @param $definition
     * @param bool $shared
     */
    public final function __construct(string $name, $definition, bool $shared = false)
    {
        $this->name       = $name;
        $this->definition = $definition;
        $this->shared     = $shared;
    }

    /**
     * Returns the service's name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets if the service is shared or not
     *
     * @param bool $shared
     */
    public function setShared(bool $shared)
    {
        $this->shared = $shared;
    }

    /**
     * Check whether the service is shared or not
     *
     * @return bool
     */
    public function isShare(): bool
    {
        return $this->shared;
    }

    /**
     * Sets/Resets the shared instance related to the service
     *
     * @param $sharedInstance
     */
    public function setSharedInstance($sharedInstance)
    {
        $this->shareInstance = $sharedInstance;
    }

    /**
     * Set the service definition
     *
     * @param $definition
     */
    public function setDefinition($definition)
    {
        $this->definition = $definition;
    }

    /**
     * Returns the service definition
     *
     * @return mixed
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * Resolves the service
     *
     * @param null $parameters
     * @param ContainerInterface|null $dependencyInjector
     * @return mixed|object|null
     * @throws Exception
     */
    public function resolve($parameters = null, ContainerInterface $dependencyInjector = null)
    {
        if ($this->shared) {
            if ($this->shareInstance !== null) {
                return $this->shareInstance;
            }
        }

        $found    = true;
        $instance = null;

        if (is_string($this->definition)) {

            if (class_exists($this->definition)) {
                if (is_array($parameters)) {
                    if (count($parameters)) {
                        $instance = new $this->definition($parameters);
                    } else {
                        $instance = new $this->definition;
                    }
                } else {
                    $instance = new $this->definition;
                }
            } else {
                $found = false;
            }

        } else {

            // Object definitions can be a Closure or an already resolved instance
            if (is_object($this->definition)) {
                if ($this->definition instanceof \Closure) {

                    // Bounds the closure to the current DI
                    if (is_object($dependencyInjector)) {
                        $this->definition = \Closure::bind($this->definition, $dependencyInjector);
                    }

                    if (is_array($parameters)) {
                        $instance = call_user_func_array($this->definition, $parameters);
                    } else {
                        $instance = call_user_func($this->definition);
                    }
                } else {
                    $instance = $this->definition;
                }
            }
        }

        // If the service can't be built, we must throw an exception
        if ($found === false) {
            throw new Exception("Service '" . $this->name . "' cannot be resolved");
        }

        // Update the shared instance if the service is shared
        if ($this->shared) {
            $this->shareInstance = $instance;
        }

        $this->resolved = true;

        return $instance;
    }

    /**
     * Returns true if the service was resolved
     *
     * @return bool
     */
    public function isResolved() : bool
    {
        return $this->resolved;
    }

    /**
     * Restore the internal state of a service
     *
     * @param array $attributes
     * @return ServiceInterface
     * @throws Exception
     */
    public static function __set_state(array $attributes): ServiceInterface
    {
        if (!isset($attributes['_name'])) {
            throw new Exception("The attribute '_name' is required");
        }

        if (!isset($attributes['_definition'])) {
            throw new Exception("The attribute '_definition' is required");
        }

        if (!isset($attributes['_shared'])) {
            throw new Exception("The attribute '_shared' is required");
        }

        return new self($attributes['_name'], $attributes['_definition'], $attributes['_shared']);
    }
}
