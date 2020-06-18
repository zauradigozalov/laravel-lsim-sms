<?php

namespace samirmhdev\LaravelLsimSms\Traits;

use samirmhdev\LaravelLsimSms\Exceptions\InsufficientBalanceException;
use samirmhdev\LaravelLsimSms\Exceptions\InvalidNotifiableCredentialsException;
use samirmhdev\LaravelLsimSms\Exceptions\InvalidPhoneNumberException;
use samirmhdev\LaravelLsimSms\Exceptions\UnknownLsimCredentials;

trait Quickable
{
    private $login;
    private $password;

    private $endpoints = [
        'CHECK_BALANCE_ENDPOINT' => 'http://apps.lsim.az/quicksms/v1/balance?',
        'SEND_ENDPOINT' => 'http://apps.lsim.az/quicksms/v1/send?',
        'CHECK_OPERATOR_ENDPOINT' => 'http://apps.lsim.az/lsimrest/mnp/check?',
        'CHECK_DETAILS_ENDPOINT' => 'http://apps.lsim.az/quicksms/v1/report?',
    ];

    private $msisdn;
    private $message;
    private $sender;

    /**
     * Quickable constructor.
     * @throws UnknownLsimCredentials
     */
    public function __construct()
    {
        $this->login = config('laravel-lsim-sms.login');
        $this->password = config('laravel-lsim-sms.password');
        $this->sender = urlencode(config('laravel-lsim-sms.sender'));

        if ($this->login === 'lsim' || $this->password === 'password' || $this->sender === 'lsim')
            throw new UnknownLsimCredentials;
    }

    /**
     * @return mixed
     */
    public function checkBalance()
    {
        return $this->request($this->endpoints['CHECK_BALANCE_ENDPOINT'], [
            'login' => $this->login,
            'key' => $this->checkBalanceKey()
        ]);
    }

    /**
     * @param $to
     * @param $message
     * @return int
     * @throws InsufficientBalanceException
     * @throws InvalidNotifiableCredentialsException
     * @throws InvalidPhoneNumberException
     */
    public function sendSms($to, $message)
    {
        if (!$this->hasBalance())
            throw new InsufficientBalanceException();

        if (!preg_match('/^[+][(\d){12}]*$/ ', $to))
            throw new InvalidPhoneNumberException();

        $this->msisdn = str_replace('+', '', $to);
        $this->message = $message;

        return $this->request($this->endpoints['SEND_ENDPOINT'], [
            'login' => $this->login,
            'msisdn' => $this->msisdn,
            'text' => $this->message,
            'sender' => $this->sender,
            'key' => $this->sendSmsKey()
        ]);
    }

    public function checkOperator($number)
    {
        return $this->request($this->endpoints['CHECK_OPERATOR_ENDPOINT'], [
            'username' => $this->login,
            'password' => $this->password,
            'prefix' => '0' . substr($number, 4, 2),
            'msisdn' => substr($number, 6, strlen($number))
        ]);
    }

    /**
     * @throws InsufficientBalanceException
     * @throws InvalidNotifiableCredentialsException
     * @throws InvalidPhoneNumberException
     */
    public function hasBalance()
    {
        if ($this->checkBalance()->getData()->response->obj === 100)
            if (config('laravel-lsim-sms.notifiable.enabled')) {
                if (config('laravel-lsim-sms.notifiable.phone') === null || config('laravel-lsim-sms.notifiable.message') === null)
                    throw new InvalidNotifiableCredentialsException();
                $this->sendSms(str_replace(' ', '', config('laravel-lsim-sms.notifiable.phone')), config('laravel-lsim-sms.notifiable.message'));
            }
        return $this->checkBalance()->getData()->response->obj;
    }

    public function getDetails($transactionId, $locale = 'az')
    {
        app()->setLocale($locale);
        if(!\Lang::has('LaravelLsimSms::report'))
            app()->setLocale('en');

        return response()->json(
            ['message' => trans('LaravelLsimSms::report.'
                . $this->request(
                    $this->endpoints['CHECK_DETAILS_ENDPOINT'], [
                        'login' => $this->login,
                        'trans_id' => $transactionId
                    ]
                )->getData()->response)
            ]
        );
    }

    private function checkBalanceKey()
    {
        return md5(md5($this->password) . $this->login);
    }

    private function sendSmsKey()
    {
        return md5(md5($this->password) . $this->login . $this->message . $this->msisdn . $this->sender);
    }
}