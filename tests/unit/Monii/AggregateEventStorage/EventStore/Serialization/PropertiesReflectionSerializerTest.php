<?php

use Monii\AggregateEventStorage\EventStore\Serialization\PropertiesReflectionSerializer;
use Monii\AggregateEventStorage\Fixtures\Banking\Account\AccountWasOpened;
use Monii\AggregateEventStorage\Contract\SimplePhpFqcnContractResolver;
use PHPUnit_Framework_TestCase as TestCase;

class PropertiesReflectionSerializerTest extends TestCase
{
    public function testReflectionSerializeDeserialize()
    {
        $myBankAccount = new AccountWasOpened('fixture-account-000', 25);

        $reflectionSerializer = new PropertiesReflectionSerializer;

        $contractResolver = new SimplePhpFqcnContractResolver();
        $contract = $contractResolver->resolveFromClassName(AccountWasOpened::class);

        if (!$reflectionSerializer->canSerialize($contract, $myBankAccount))
        {
            // Stop here and mark this test as failed.
            $this->fail('Unable to serialize');
        }

        $data = $reflectionSerializer->serialize($contract, $myBankAccount);

        if (!$reflectionSerializer->canDeserialize($contract, $data))
        {
            // Stop here and mark this test as failed.
            $this->fail('Unable to deserialize array ' . print_r($data, true));
        }

        $object = $reflectionSerializer->deserialize($contract, $data);

        $this->assertEquals($myBankAccount->accountId, $object->accountId);
        $this->assertEquals($myBankAccount->startingBalance, $object->startingBalance);

    }
}