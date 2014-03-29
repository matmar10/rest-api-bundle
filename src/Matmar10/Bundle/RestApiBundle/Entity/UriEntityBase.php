<?php

namespace Matmar10\Bundle\RestApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\ReadOnly;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Exclude;
use Matmar10\Bundle\UtilBundle\Uuid;
use Matmar10\Bundle\RestApiBundle\Entity\UriEntityInterface;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 * @AccessType("public_method")
 * @ExclusionPolicy("none")
 */
abstract class UriEntityBase implements UriEntityInterface
{

    /**
     * @Exclude
     */
    protected $uriRouteId;

    /**
     * @Exclude
     */
    protected $uriRouteParameters;

    /**
     * @Type("string")
     * @Groups({"uri"})
     */
    protected $uri;

    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function setUriRouteId($routeId)
    {
        $this->uriRouteId = $routeId;
    }

    public function setUriRouteParameter($parameterKey, $parameterValue)
    {
        $this->uriRouteParameters[$parameterKey] = $parameterValue;
    }

    public function setUriRouteParameters($parameters)
    {
        $this->uriRouteParameters = $parameters;
    }

    public function getUriRouteParameters()
    {
        return $this->uriRouteParameters;
    }

}