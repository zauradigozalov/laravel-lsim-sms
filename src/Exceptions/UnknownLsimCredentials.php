<?php

namespace mrsamirmh\LaravelLsimSms\Exceptions;

use Exception;

class UnknownLsimCredentials extends Exception
{
    /**
     * @var string
     */
    protected $message = 'Login, şifrə və ya göndərən əlavə edilməyib';

    /**
     * @var int
     */
    protected $code = 404;
}
