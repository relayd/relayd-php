<?php

class Configuration {

    private $relayd;

    public function __construct(Relayd &$relayd) {
        $this->relayd = $relayd;
    }

    public function getServerVersion(): string {
        return "dev";
    }

    public function getServerHost(): string {
        return $this->relayd->getServerConfig()['server']['host'];
    }

    public function getDisplayName(): string {
        return $this->relayd->getServerConfig()['server']['displayName'];
    }

    public function getBackendName(): string {
        return $this->relayd->getServerConfig()['server']['backendName'];
    }
}