<?php

interface RelaydAuth {

    function __construct(Relayd &$relayd);

    public function isValidAuthKey(string $authKey): bool;

    public function canUseAddress(string $authKey, string $address): bool;

    public function getAddressesForAuthKey(string $authKey): array;

    public function canGetReceivedMessage(string $authKey, string $uuid): bool;
}