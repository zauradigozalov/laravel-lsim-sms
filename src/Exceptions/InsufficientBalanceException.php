<?php

namespace mrsamirmh\LaravelLsimSms\Exceptions;

use Exception;

class InsufficientBalanceException extends Exception
{
    /**
     * @var string
     */
    protected $message = 'Kifayət qədər balans yoxdur.';

    /**
     * @var int
     */
    protected $code = 403;
}
