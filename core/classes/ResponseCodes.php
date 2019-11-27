<?php

class ResponseCodes {

    public const UNKNOWN_ERROR = 0;
    public const SUCCESS = 1;
    public const NO_ADDRESS = 2;
    public const INVALID_MESSAGE = 3;
    public const NO_TEXT = 4;
    public const INVALID_TEXT_LENGTH = 5;
    public const INVALID_USER = 6;
    public const NO_ADDRESS_SEPARATOR = 7;
    public const MISSING_ADDRESS_PARTS = 8;
    public const INVALID_HOST = 9;
    public const INVALID_USER_LENGTH = 10;
    public const INVALID_HOST_LENGTH = 11;
    public const INVALID_RESPONSE = 12;
    public const SENDER_NOT_ALLOWED = 13;
    public const INVALID_UUID = 14;
    public const NO_HOST = 15;
    public const NO_UUID = 16;
    public const NO_PERMISSION = 17;
    public const MIDDLEWARE_REJECT = 18;

    public const NOT_FOUND = 404;
}