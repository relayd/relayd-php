<?php

interface RelaydStorage {

    function __construct(Relayd &$relayd);

    public function getSentMessage(string $uuid): SentMessage;

    public function createSentMessage(string $from, string $to, string $text): SentMessage;

    public function removeSentMessage(SentMessage $sentMessage): void;

    public function getReceivedMessage(string $uuid): ReceivedMessage;

    public function createReceivedMessage(string $from, string $to, string $text): ReceivedMessage;

    public function removeReceivedMessage(ReceivedMessage $receivedMessage): void;

    public function isAvailableCode(string $category, string $code): bool;

    public function getOldestMessagesToFetch(string $address): array;
}