<?php

class Validation {

    private $relayd;

    private const HOST_PATTERN = '/^(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z0-9][a-z0-9-]{0,61}[a-z0-9]$/';
    private const USER_PATTERN = '/^(?:[a-z0-9](?:[a-z0-9-]{0,62}[a-z0-9]))$/';
    private const UUID_PATTERN = '/^([a-zA-Z0-9]{64,128})$/';

    public function __construct(Relayd &$relayd) {
        $this->relayd = $relayd;
    }

    public function validateHost(?string $host): int {
        // Make sure the host is not null
        if ($host == null) {
            return ResponseCodes::NO_HOST;
        }
        // Check to make sure host matches valid format
        if (!preg_match(Validation::HOST_PATTERN, $host)) {
            return ResponseCodes::INVALID_HOST;
        }
        return ResponseCodes::SUCCESS;
    }

    public function validateUuid(?string $uuid): int {
        // Make sure the uuid is not null
        if ($uuid == null) {
            return ResponseCodes::NO_UUID;
        }
        // Check to make sure uuid matches valid format
        if (!preg_match(Validation::UUID_PATTERN, $uuid)) {
            return ResponseCodes::INVALID_UUID;
        }
        return ResponseCodes::SUCCESS;
    }

    public function validateAddress(?string $address): int {
        // Make sure the address is not null
        if ($address == null) {
            return ResponseCodes::NO_ADDRESS;
        }
        // Make sure it has # symbol
        if (strpos($address, "#") === false) {
            return ResponseCodes::NO_ADDRESS_SEPARATOR;
        }
        // Split it at the # symbol and make sure there are exactly 2 parts
        $splitAddress = explode("#", $address);
        if (sizeof($splitAddress) != 2) {
            return ResponseCodes::MISSING_ADDRESS_PARTS;
        }
        // Make sure the user length is min 1 character and max 64 characters
        if (strlen($splitAddress[0]) < 1 || 64 < strlen($splitAddress[0])) {
            return ResponseCodes::INVALID_USER_LENGTH;
        }
        // Check to make sure user matches valid format
        if (!preg_match(Validation::USER_PATTERN, $splitAddress[0])) {
            return ResponseCodes::INVALID_USER;
        }
        // Make sure the host length is min 1 character and max 64 characters
        if (strlen($splitAddress[1]) < 1 || 64 < strlen($splitAddress[1])) {
            return ResponseCodes::INVALID_HOST_LENGTH;
        }
        // Check to make sure host matches valid format
        if (!preg_match(Validation::HOST_PATTERN, $splitAddress[1])) {
            return ResponseCodes::INVALID_HOST;
        }
        return ResponseCodes::SUCCESS;
    }

    public function validateText(?string $text): int {
        // Make sure the text is not null
        if ($text == null) {
            return ResponseCodes::NO_TEXT;
        }
        // Make sure the text length is min 1 character and max 1000 characters
        if (strlen($text) < 1 || 1000 < strlen($text)) {
            return ResponseCodes::INVALID_TEXT_LENGTH;
        }
        return ResponseCodes::SUCCESS;
    }
}