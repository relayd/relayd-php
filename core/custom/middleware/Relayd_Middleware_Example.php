<?php

class Relayd_Middleware_Example extends RelaydMiddleware {

    public function handle(int $eventType, MiddlewareResponse $previousResponse): MiddlewareResponse {
        if ($eventType == MiddlewareCodes::PRE_SEND) {
            // Disallow sending to joe#example.com
            if (isset($previousResponse->getData()['toAddress']) && strtolower($previousResponse->getData()['toAddress']) == "joe#example.com") {
                return new MiddlewareResponse(MiddlewareResponseCodes::REJECT, $previousResponse->getData());
            }
        }
        // Required for middleware to work: Pass default allow response
        return new MiddlewareResponse(MiddlewareResponseCodes::ALLOW, $previousResponse->getData());
    }
}