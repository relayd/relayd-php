<?php

if (!isset($_POST['host'])) {
    $relayd->getResponseGenerator()->sendResponseDie(ResponseCodes::NO_HOST);
}

$verifyHost = $relayd->getValidation()->validateHost($_POST['host']);

if ($verifyHost != ResponseCodes::SUCCESS) {
    $relayd->getResponseGenerator()->sendResponseDie($verifyHost);
}

if (!isset($_POST['uuid'])) {
    $relayd->getResponseGenerator()->sendResponseDie(ResponseCodes::NO_UUID);
}

$verifyUuid = $relayd->getValidation()->validateUuid($_POST['uuid']);

if ($verifyUuid != ResponseCodes::SUCCESS) {
    $relayd->getResponseGenerator()->sendResponseDie($verifyUuid);
}

$relayd->getMessageProcesser()->receiveMessage($_POST['host'], $_POST['uuid']);

$relayd->getResponseGenerator()->sendResponseDie(ResponseCodes::SUCCESS);