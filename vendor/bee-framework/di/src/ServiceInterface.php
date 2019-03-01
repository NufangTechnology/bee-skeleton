<?php
namespace Bee\Di;

interface ServiceInterface
{
    public function getName() : string;

    public function setShared(bool $shared);

    public function isShare() : bool;

    public function setDefinition($definition);

    public function getDefinition();

    public function resolve($parameters = null, ContainerInterface $dependencyInjector = null);

    public static function __set_state(array $attributes) : ServiceInterface;
}
