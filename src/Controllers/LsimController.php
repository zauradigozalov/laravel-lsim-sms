<?php

namespace mrsamirmh\LaravelLsimSms\Controllers;

use App\Http\Controllers\Controller;
use mrsamirmh\LaravelLsimSms\Traits\Quickable;
use mrsamirmh\LaravelLsimSms\Traits\Requestable;

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