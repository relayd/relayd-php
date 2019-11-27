<?php

class MiddlewareResponseCodes {

    public const REJECT = 0; // Stops from continuing to the next middleware
    public const ALLOW = 1; // Continues the process to the other middleware
}