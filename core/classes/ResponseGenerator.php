<?php

class ResponseGenerator {

    private $relayd;

    public function __construct(Relayd &$relayd) {
        $this->relayd = $relayd;
    }

    public function sendResponse(int $code, $data = null): void {
        $reflection = new ReflectionClass('ResponseCodes');
        $constants = $reflection->getConstants();
        $message = "";
        foreach ($constants as $key => $value) {
            if ($value === $code) {
                $message = $key;
                break;
            }
        }
        if ($data !== null) {
            echo json_encode(array(
                "code" => $code,
                "message" => $message,
                "data" => $data
            ), JSON_PRETTY_PRINT);
        } else {
            echo json_encode(array(
                "code" => $code,
                "message" => $message
            ), JSON_PRETTY_PRINT);
        }
    }

    public function sendResponseDie(int $code, $data = null): void {
        $this->sendResponse($code, $data);
        die();
    }
}