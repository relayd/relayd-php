<?php

class Relayd {

    private $serverConfig;

    private $configuration;
    private $messageProcesser;
    private $responseGenerator;
    private $validation;

    private $authDriver = null;
    private $storageDriver = null;

    public function __construct(array $serverConfig) {
        $this->serverConfig = $serverConfig;
        $this->configuration = new Configuration($this);
        $this->messageProcesser = new MessageProcesser($this);
        $this->responseGenerator = new ResponseGenerator($this);
        $this->validation = new Validation($this);
    }

    public function getServerConfig(): array {
        return $this->serverConfig;
    }

    public function getConfiguration(): Configuration {
        return $this->configuration;
    }

    public function getMessageProcesser(): MessageProcesser {
        return $this->messageProcesser;
    }

    public function getResponseGenerator(): ResponseGenerator {
        return $this->responseGenerator;
    }

    public function getValidation(): Validation {
        return $this->validation;
    }

    // Drivers

    public function getAuthDriver(): RelaydAuth {
        if ($this->authDriver == null) {
            if (!is_file(ROOT_PATH . "/core/custom/auth/" . $this->getServerConfig()['drivers']['auth'] . ".php")) {
                die("The auth driver was not found.");
            }
            require_once ROOT_PATH . "/core/custom/auth/" . $this->getServerConfig()['drivers']['auth'] . ".php";
            $authDriverName = $this->getServerConfig()['drivers']['auth'];
            $this->authDriver = new $authDriverName($this);
        }
        return $this->authDriver;
    }

    public function getStorageDriver(): RelaydStorage {
        if ($this->storageDriver == null) {
            if (!is_file(ROOT_PATH . "/core/custom/storage/" . $this->getServerConfig()['drivers']['storage'] . ".php")) {
                die("The storage driver was not found.");
            }
            require_once ROOT_PATH . "/core/custom/storage/" . $this->getServerConfig()['drivers']['storage'] . ".php";
            $storageDriverName = $this->getServerConfig()['drivers']['storage'];
            $this->storageDriver = new $storageDriverName($this);
        }
        return $this->storageDriver;
    }

    // Messages

    public function getSentMessage(string $uuid): SentMessage {
        return $this->getStorageDriver()->getSentMessage($uuid);
    }

    public function createSentMessage(string $from, string $to, string $text): SentMessage {
        return $this->getStorageDriver()->createSentMessage($from, $to, $text);
    }

    public function getReceivedMessage(string $uuid): ReceivedMessage {
        return $this->getStorageDriver()->getReceivedMessage($uuid);
    }

    public function createReceivedMessage(string $from, string $to, string $text): ReceivedMessage {
        $this->getStorageDriver()->createReceivedMessage($from, $to, $text);
    }

    // Random code

    private function generateRandomCode(int $length = 8): string { // Generate a random code that isn't just letters or just numbers
        $found = false;
        $generated_code = substr(bin2hex(random_bytes($length)), 0, $length);
        while (!$found) {
            if (preg_match('/^[0-9]+$/', $generated_code)) {
                $generated_code = substr(bin2hex(random_bytes($length)), 0, $length);
            } else if (preg_match('/^[a-zA-Z]+$/', $generated_code)) {
                $generated_code = substr(bin2hex(random_bytes($length)), 0, $length);
            } else {
                $found = true;
            }
        }
        return $generated_code;
    }

    public function findRandomCode(string $category, int $length = 8): string {
        $found = false;
        $found_code = $this->generateRandomCode($length);
        while (!$found) {
            if (!$this->getStorageDriver()->isAvailableCode($category, $found_code)) {
                $found_code = $this->generateRandomCode($length);
            } else {
                $found = true;
            }
        }
        return $found_code;
    }
}