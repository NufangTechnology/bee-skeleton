<?php
namespace Bee\Di;

/**
 * This interface must be implemented in those classes that uses internally the Phalcon\Di that creates them
 *
 * @package Bee\Di
 */
interface InjectionAwareInterface
{
    /**
     * Sets the dependency injector
     *
     * @param ContainerInterface $dependencyInjector
     */
    public function setDi(ContainerInterface $dependencyInjector);

    /**
     * Returns the internal dependency injector
     */
    public function getDi() : ContainerInterface;
}
