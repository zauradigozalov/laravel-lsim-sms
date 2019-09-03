<?php

namespace samirmhdev\LaravelLsimSms\Controllers;

use App\Http\Controllers\Controller;
use samirmhdev\LaravelLsimSms\Traits\Quickable;
use samirmhdev\LaravelLsimSms\Traits\Requestable;

class LsimController extends Controller
{
    use Requestable, Quickable {
        Quickable::__construct as private quickableConstruct;
        Requestable::__construct as private requestableConstruct;
    }

    private $debug;

    public function __construct()
    {
        $this->debug = config('laravel-lsim-sms.debug');
        $this->requestableConstruct();
        $this->quickableConstruct();
    }
}