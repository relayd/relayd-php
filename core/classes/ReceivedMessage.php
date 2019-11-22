<?php

class ReceivedMessage {

    private $relayd;
    private $data;
    private $exists;

    function __construct(Relayd &$relayd, array $data, bool $exists) {
        $this->relayd = $relayd;
        $this->data = $data;
        $this->exists = $exists;
    }

    public function getUuid(): string {
        return $this->data['uuid'];
    }

    public function getTime(): int {
        return $this->data['time'];
    }

    public function getTo(): string {
        return $this->data['toAddress'];
    }

    public function getFrom(): string {
        return $this->data['fromAddress'];
    }

    public function getText(): string {
        return $this->data['text'];
    }

    public function remove() {
        $this->relayd->getStorageDriver()->removeReceivedMessage($this);
    }

    public function doesExist(): bool {
        return $this->exists;
    }
}