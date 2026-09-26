<?php

namespace alf89\HttpClient\Config;

class Config
{

    public function __construct(
        public int     $timeout = 30 {
            get {
                return $this->timeout;
            }
            set {
                if($value <= 0){
                    throw new \InvalidArgumentException('The value must be a positive integer');
                }
                $this->timeout = $value;
            }
        },
        public int     $connectTimeout = 5 {
            get {
                return $this->connectTimeout;
            }
            set {
                if($value <= 0){
                    throw new \InvalidArgumentException('The value must be a positive integer');
                }
                $this->connectTimeout = $value;
            }
        },
        public private(set) string $userAgent = 'alf89/http-client/0.2.1'{
            get {
                return $this->userAgent;
            }
        },
        public bool $verifyPeer = true,
    ){}
}