<?php

if (!isset($_GET['uuid']) || $_GET['uuid'] == "") {
    $relayd->getResponseGenerator()->sendResponseDie(ResponseCodes::NOT_FOUND);
}

$message = $relayd->getStorageDriver()->getSentMessage($_GET['uuid']);

if (!$message->doesExist()) {
    $relayd->getResponseGenerator()->sendResponseDie(ResponseCodes::NOT_FOUND);
}

$message->remove();

$relayd->getResponseGenerator()->sendResponseDie(ResponseCodes::SUCCESS, array(
    "toAddress" => $message->getTo(),
    "fromAddress" => $message->getFrom(),
    "text" => $message->getText()
));