<?php

abstract class RelaydMiddleware {

    private $relayd;

    public function __construct(Relayd &$relayd) {
        $this->relayd = $relayd;
    }

    abstract public function handle(int $eventType, MiddlewareResponse $previousResponse): MiddlewareResponse;
}