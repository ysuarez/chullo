<?php

namespace Islandora\Chullo\Uuid;

use Ramsey\Uuid\Uuid;

/**
 * Generator for v4 UUIDs.
 */
class UuidGenerator implements IUuidGenerator
{
    protected $namespace;
    protected $namespace_uuid;

    public function __construct($namespace = NULL) {
        // Give sensible default namespace if none is provided.
        if (empty($namespace)) {
            $namespace = "islandora.ca";
        }
        $this->namespace = $namespace;
        $this->namespace_uuid = Uuid::uuid(Uuid::NAMESPACE_DNS, $namespace);
    }

    /**
     * Generates a v4 UUID.
     *
     * @return String   Valid v4 UUID.
     */
    public function generateV4() {
        return Uuid::uuid4()->toString();
    }

    /**
     * Generates a v5 UUID.
     *
     * @return String   Valid v5 UUID.
     */
    public function generateV5($str, $namespace = NULL) {
        // Use default namespace if none is provided.
        if (!empty($namespace)) {
          return Uuid::uuid5(Uuid::uuid5(Uuid::NAMESPACE_DNS, $namespace), $str)->toString();
        }
        else {
          return Uuid::uuid5($namespace_uuid, $str)->toString();
        }
    }

}
