<?php

namespace App\Helpers;

class ApiCodes
{
    const SUCCESS = 1;
    const USER_AUTHENTICATION_FAILED = 10;
    const VALIDATION_FAILED = 2;
    const RESOURCE_NOT_FOUND = 3;
    const USER_NOT_FOUND = 3;
    const RESOURCE_EXISTS = 4;
    const GENERAL_ERROR = 5;
    const RESOURCE_CAN_NOT_BE_MODIFIED = 5;
    const RESOURCE_INACTIVE = 6;
    const PERMISSION_DENIED = 7;
    const TOO_MANY_LOGIN_ATTEMPTS = 8;
    const CREATION_FAILED = 9;

    public static function getSuccessMessage(): string
    {
        return __('api.success_message');
    }

    public static function getResourceNotFoundMessage(): string
    {
        return __('api.resource_not_found');
    }

    public static function getUserAuthenticationFailedMessage(): string
    {
        return __('api.user_authentication_failed_message');
    }

    public static function getUserNotFoundMessage(): string
    {
        return __('api.user_not_found_message');
    }

    public static function getValidationFailedMessage(): string
    {
        return __('api.validation_failed_message');
    }

    public static function getResourceExistsMessage(): string
    {
        return __('api.resource_exists');
    }

    public static function getGeneralErrorMessage(): string
    {
        return __('api.general_error_message');
    }

    public static function getResourceInactiveMessage(): string
    {
        return __('api.resource_inactive_message');
    }
}
