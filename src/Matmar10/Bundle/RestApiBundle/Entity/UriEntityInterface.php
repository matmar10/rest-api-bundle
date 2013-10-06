<?php

namespace Matmar10\Bundle\RestApiBundle\Entity;
 
interface UriEntityInterface
{

    public function setUri($uri);

    public function getUri();

    public function setUriRouteId($routeId);

    public function getUriRouteId();

    public function setUriRouteParameter($name, $value);

    public function setUriRouteParameters($parameters);

    public function getUriRouteParameters();

}
