<?php

namespace Matmar10\Bundle\RestApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Matmar10\Bundle\RestApiBundle\Entity\UriEntityBase;
use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\ReadOnly;
use JMS\Serializer\Annotation\Groups;
use Lmh\UtilBundle\Uuid;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 * @AccessType("public_method")
 * @ExclusionPolicy("none")
 */
abstract class UuidUriEntityBase extends UriEntityBase
{

    /**
     * @ORM\Column(type="string", length=36)
     * @Type("string")
     * @ReadOnly
     * @Groups({"uri"})
     */
    protected $uuid;

    /**
     * @ORM\PrePersist
     */
    public function setUuidValue()
    {
        $this->uuid = new Uuid();
    }

    public function setUuid($uuid)
    {
        if($uuid instanceof Uuid) {
            $this->uuid = new Uuid($uuid);
            return;
        }
        $this->uuid = $uuid;
    }

    public function getUuid()
    {
        if($this->uuid instanceof Uuid) {
            return $this->uuid->__toString();
        }
        return $this->uuid;
    }

    // default implementation requires at least a uuid to generate a route
    public function getUriRouteParameters()
    {
        return array(
            'uuid' => $this->uuid,
        );
    }
}