<?php
namespace Bee\Di;

/**
 * Bee\Di\ServiceProviderInterface
 *
 * Should be implemented by service providers, or such components,
 * which register a service in the service container.
 *
 * <code>
 * namespace Acme;
 *
 * use Bee\DiInterface;
 * use Bee\Di\ServiceProviderInterface;
 *
 * class SomeServiceProvider implements ServiceProviderInterface
 * {
 *     public function register(DiInterface $di)
 *     {
 *         $di->setShared('service', function () {
 *             // ...
 *         });
 *     }
 * }
 * </code>
 */
interface ServiceProviderInterface
{
    /**
     * Registers a service provider.
     *
     * @param ContainerInterface $di
     * @return mixed
     */
    public function register(ContainerInterface $di);
}
