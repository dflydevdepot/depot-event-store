<?php

namespace Monii\AggregateEventStorage\Fixtures\Banking\Account;

use Monii\AggregateEventStorage\Fixtures\Banking\Common\EventSourcedAggregate;

class Account extends EventSourcedAggregate
{
    /**
     * @var string
     */
    private $accountId;

    /**
     * @var int
     */
    private $balance = 0;

    public static function open($accountId, $startingBalance = 0)
    {
        $account = new static();
        $account->recordEvent(new AccountWasOpened($accountId, $startingBalance));

        return $account;
    }

    public function increaseBalance($amount)
    {
        $this->recordEvent(new AccountBalanceIncreased($this->accountId, $amount));
    }

    public function decreaseBalance($amount)
    {
        $this->recordEvent(new AccountBalanceDecreased($this->accountId, $amount));
    }

    protected function applyAccountWasOpened(AccountWasOpened $event)
    {
        $this->accountId = $event->accountId;
        $this->balance = $event->startingBalance;
    }

    protected function applyAccountBalanceIncreased(AccountBalanceIncreased $event)
    {
        $this->balance += $event->amount;
    }

    protected function applyAccountBalanceDecreased(AccountBalanceDecreased $event)
    {
        $this->balance -= $event->amount;
    }

    /**
     * @return string
     */
    public function getAggregateIdentity()
    {
        return $this->accountId;
    }

}
