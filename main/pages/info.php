<?php

$relayd->getResponseGenerator()->sendResponseDie(ResponseCodes::SUCCESS, array(
    "backendName" => $relayd->getConfiguration()->getBackendName(),
    "displayName" => $relayd->getConfiguration()->getDisplayName(),
    "host" => $relayd->getConfiguration()->getServerHost(),
    "version" => array(
        "server" => $relayd->getConfiguration()->getServerVersion(),
        "implementation" => $relayd->getConfiguration()->getImplementationVersion()
    )
));