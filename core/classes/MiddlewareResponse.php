<?php

class MiddlewareResponse {

    private $middlewareResponseCode;
    private $data;

    public function __construct(int $middlewareResponseCode, array $data) {
        $this->middlewareResponseCode = $middlewareResponseCode;
        $this->data = $data;
    }

    public function getMiddlewareResponseCode(): int {
        return $this->middlewareResponseCode;
    }

    public function getData(): array {
        return $this->data;
    }
}