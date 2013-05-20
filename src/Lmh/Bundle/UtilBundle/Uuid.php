<?php

namespace Lmh\Bundle\UtilBundle;

class Uuid
{

    const FORMAT = '%04x%04x-%04x-%04x-%04x-%04x%04x%04x';

    protected $uuid;

    public function __construct()
    {
        $this->uuid = self::generateUuid();
    }

    public static function generateUuid()
    {
        return sprintf( self::FORMAT,
            // 32 bits for "time_low"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

            // 16 bits for "time_mid"
            mt_rand( 0, 0xffff ),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand( 0, 0x0fff ) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand( 0, 0x3fff ) | 0x8000,

            // 48 bits for "node"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }

    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function __toString()
    {
        return $this->uuid;
    }
}
