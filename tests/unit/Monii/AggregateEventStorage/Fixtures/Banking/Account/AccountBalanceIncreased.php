<?php

namespace Monii\AggregateEventStorage\Fixtures\Banking\Account;

class AccountBalanceIncreased
{
    public $accountId;
    public $amount;
    public function __construct($accountId, $amount)
    {
        $this->accountId = $accountId;
        $this->amount = $amount;
    }
}
