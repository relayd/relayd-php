<?php

class MessageProcesser {

    private $relayd;

    public function __construct(Relayd &$relayd) {
        $this->relayd = $relayd;
    }

    public function receiveMessage(?string $relaydHost, ?string $uuid): int {
        if ($relaydHost == null || $relaydHost == "") {
            return ResponseCodes::INVALID_HOST;
        }
        if ($uuid == null || $uuid == "") {
            return ResponseCodes::INVALID_UUID;
        }
        // Send the request to get the message
        $ch = curl_init("https://" . $relaydHost . "/get/" . $uuid);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->relayd->getServerConfig()['server']['requestTimeout']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "X-Relayd: 1"
        ));
        $remoteMessageRaw = curl_exec($ch);
        $remoteMessageJson = json_decode($remoteMessageRaw, true);
        if ($remoteMessageJson === false) {
            return ResponseCodes::INVALID_MESSAGE;
        }
        // Validate received message
        $validateToAddress = $this->relayd->getValidation()->validateText($remoteMessageJson['data']['toAddress']);
        if ($validateToAddress != ResponseCodes::SUCCESS) {
            return $validateToAddress;
        }
        $validateFromAddress = $this->relayd->getValidation()->validateText($remoteMessageJson['data']['fromAddress']);
        if ($validateFromAddress != ResponseCodes::SUCCESS) {
            return $validateFromAddress;
        }
        $validateText = $this->relayd->getValidation()->validateText($remoteMessageJson['data']['text']);
        if ($validateText != ResponseCodes::SUCCESS) {
            return $validateText;
        }
        // Validate sender
        $splitAddress = explode("#", $remoteMessageJson['data']['fromAddress']);
        // Send the request to get the valid senders
        $ch = curl_init("https://" . $splitAddress[1] . "/relayd.json");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->relayd->getServerConfig()['server']['requestTimeout']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "X-Relayd: 1"
        ));
        $remoteServerRaw = curl_exec($ch);
        $remoteServerJson = json_decode($remoteServerRaw, true);
        if ($remoteServerJson === false) {
            return ResponseCodes::INVALID_RESPONSE;
        }
        if (!isset($remoteServerJson['relayd']['allowedSenders'])) {
            return ResponseCodes::INVALID_RESPONSE;
        }
        if (!is_array($remoteServerJson['relayd']['allowedSenders'])) {
            return ResponseCodes::INVALID_RESPONSE;
        }
        if (!in_array($relaydHost, $remoteServerJson['relayd']['allowedSenders'])) {
            return ResponseCodes::SENDER_NOT_ALLOWED;
        }
        $this->relayd->createReceivedMessage($remoteMessageJson['data']['toAddress'], $remoteMessageJson['data']['fromAddress'], $remoteMessageJson['data']['text']);
        return ResponseCodes::SUCCESS;
    }

    public function sendMessage(?string $toAddress, ?string $fromAddress, ?string $text): int {
        $validateTo = $this->relayd->getValidation()->validateAddress($toAddress);
        if ($validateTo != ResponseCodes::SUCCESS) {
            return $validateTo;
        }
        $validateFrom = $this->relayd->getValidation()->validateAddress($fromAddress);
        if ($validateFrom != ResponseCodes::SUCCESS) {
            return $validateFrom;
        }
        $validateText = $this->relayd->getValidation()->validateText($text);
        if ($validateText != ResponseCodes::SUCCESS) {
            return $validateText;
        }
        // Validate sender
        $splitAddress = explode("#", $toAddress);
        // Send the request to get the valid senders
        $ch = curl_init("https://" . $splitAddress[1] . "/relayd.json");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->relayd->getServerConfig()['server']['requestTimeout']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "X-Relayd: 1"
        ));
        $remoteServerRaw = curl_exec($ch);
        $remoteServerJson = json_decode($remoteServerRaw, true);
        if ($remoteServerJson === false) {
            return ResponseCodes::INVALID_RESPONSE;
        }
        if (!isset($remoteServerJson['relayd']['host'])) {
            return ResponseCodes::INVALID_RESPONSE;
        }
        if (!is_string($remoteServerJson['relayd']['host'])) {
            return ResponseCodes::INVALID_RESPONSE;
        }
        $sentMessage = $this->relayd->createSentMessage($toAddress, $fromAddress, $text);
        $postFields = [
            "host" => $this->relayd->getConfiguration()->getServerHost(),
            "uuid" => $sentMessage->getUuid()
        ];
        $ch = curl_init("https://" . $remoteServerJson['relayd']['host'] . "/receive");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST,true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->relayd->getServerConfig()['server']['requestTimeout']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "X-Relayd: 1"
        ));
        $remoteReceiveRaw = curl_exec($ch);
        return ResponseCodes::SUCCESS;
    }
}