<?php

if (!isset($_POST['address']) || $_POST['address'] == "") {
    $relayd->getResponseGenerator()->sendResponseDie(ResponseCodes::NO_ADDRESS);
}

if (!isset($_POST['key']) || $_POST['key'] == "") {
    $relayd->getResponseGenerator()->sendResponseDie(ResponseCodes::NO_PERMISSION);
}

if (!$relayd->getAuthDriver()->isValidAuthKey($_POST['key'])) {
    $relayd->getResponseGenerator()->sendResponseDie(ResponseCodes::NO_PERMISSION);
}

if (!$relayd->getAuthDriver()->canUseAddress($_POST['key'], $_POST['address'])) {
    $relayd->getResponseGenerator()->sendResponseDie(ResponseCodes::NO_PERMISSION);
}

$data = array();
$messages = $relayd->getStorageDriver()->getOldestMessagesToFetch($_POST['address']);
foreach ($messages as $message) {
    array_push($data, array(
        "time" => $message->getTime(),
        "toAddress" => $message->getTo(),
        "fromAddress" => $message->getFrom(),
        "text" => $message->getText()
    ));
    $message->remove();
}

$relayd->getResponseGenerator()->sendResponseDie(ResponseCodes::SUCCESS, $data);