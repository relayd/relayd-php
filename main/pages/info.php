<?php

$relayd->getResponseGenerator()->sendResponseDie(ResponseCodes::SUCCESS, array(
    "version" => $relayd->getConfiguration()->getServerVersion(),
    "host" => $relayd->getConfiguration()->getServerHost(),
    "backendName" => $relayd->getConfiguration()->getBackendName(),
    "displayName" => $relayd->getConfiguration()->getDisplayName()
));