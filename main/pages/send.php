<?php

if (!isset($_POST['toAddress']) || $_POST['toAddress'] == "") {
    $relayd->getResponseGenerator()->sendResponseDie(ResponseCodes::NO_ADDRESS);
}

$validateTo = $relayd->getValidation()->validateAddress($_POST['toAddress']);

if ($validateTo != ResponseCodes::SUCCESS) {
    $relayd->getResponseGenerator()->sendResponseDie($validateTo, array(
        "for" => "toAddress"
    ));
}

if (!isset($_POST['fromAddress']) || $_POST['fromAddress'] == "") {
    $relayd->getResponseGenerator()->sendResponseDie(ResponseCodes::NO_ADDRESS);
}

$validateFrom = $relayd->getValidation()->validateAddress($_POST['fromAddress']);

if ($validateFrom != ResponseCodes::SUCCESS) {
    $relayd->getResponseGenerator()->sendResponseDie($validateFrom, array(
        "for" => "fromAddress"
    ));
}

if (!isset($_POST['text']) || $_POST['text'] == "") {
    $relayd->getResponseGenerator()->sendResponseDie(ResponseCodes::NO_TEXT);
}

$validateText = $relayd->getValidation()->validateText($_POST['text']);

if ($validateText != ResponseCodes::SUCCESS) {
    $relayd->getResponseGenerator()->sendResponseDie($validateText, array(
        "for" => "text"
    ));
}

if (!isset($_POST['key']) || $_POST['key'] == "") {
    $relayd->getResponseGenerator()->sendResponseDie(ResponseCodes::NO_PERMISSION);
}

if (!$relayd->getAuthDriver()->isValidAuthKey($_POST['key'])) {
    $relayd->getResponseGenerator()->sendResponseDie(ResponseCodes::NO_PERMISSION);
}

if (!$relayd->getAuthDriver()->canUseAddress($_POST['key'], $_POST['fromAddress'])) {
    $relayd->getResponseGenerator()->sendResponseDie(ResponseCodes::NO_PERMISSION);
}

$sendMessage = $relayd->getMessageProcesser()->sendMessage($_POST['toAddress'], $_POST['fromAddress'], $_POST['text']);

$relayd->getResponseGenerator()->sendResponseDie($sendMessage);