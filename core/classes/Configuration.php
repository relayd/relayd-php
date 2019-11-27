<?php

class Configuration {

    private $relayd;

    public function __construct(Relayd &$relayd) {
        $this->relayd = $relayd;
    }

    /**
     * Get the version of the Relayd instance
     * @return string
     */
    public function getServerVersion(): string {
        return "dev";
    }

    /**
     * Gets the implementation version of the protocol
     * @return string
     */
    public function getImplementationVersion(): string {
        return "dev";
    }

    /**
     * Gets the timeout (in seconds) for outgoing requests
     * @return int
     */
    public function getServerTimeout(): int {
        return $this->relayd->getServerConfig()['server']['requestTimeout'];
    }

    /**
     * Get the host for the Relayd instance
     * @return string
     */
    public function getServerHost(): string {
        return $this->relayd->getServerConfig()['server']['host'];
    }

    /**
     * Get the display name for the Relayd instance
     * @return string
     */
    public function getDisplayName(): string {
        return $this->relayd->getServerConfig()['server']['displayName'];
    }

    /**
     * Get the backend name for the Relayd instance
     * @return string
     */
    public function getBackendName(): string {
        return $this->relayd->getServerConfig()['server']['backendName'];
    }
}