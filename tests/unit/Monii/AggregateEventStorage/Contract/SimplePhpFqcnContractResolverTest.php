<?php

namespace Monii\AggregateEventStorage\Contract;

use PHPUnit_Framework_TestCase as TestCase;

class SimplePhpFqcnContractResolverTest extends TestCase
{
    protected function getContractResolver()
    {
        return new SimplePhpFqcnContractResolver();
    }

    /**
     * @param string $contractName
     * @param Contract $expectedContract
     * @dataProvider provideResolveFromContractNameData
     */
    public function testResolveFromContractName($contractName, Contract $expectedContract)
    {
        $this->assertEquals(
            $expectedContract,
            $this->getContractResolver()->resolveFromContractName($contractName)
        );
    }

    public function provideResolveFromContractNameData()
    {
        return [
            [
                'Com.Example.Whatever',
                new Contract('Com.Example.Whatever', 'Com\\Example\\Whatever'),
            ]
        ];
    }

    /**
     * @param string $className
     * @param Contract $expectedContract
     * @dataProvider provideResolveFromClassNameData
     */
    public function testResolveFromClassName($className, Contract $expectedContract)
    {
        $this->assertEquals(
            $expectedContract,
            $this->getContractResolver()->resolveFromClassName($className)
        );
    }

    public function provideResolveFromClassNameData()
    {
        return [
            [
                'Com\\Example\\Whatever',
                new Contract('Com.Example.Whatever', 'Com\\Example\\Whatever'),
            ]
        ];
    }
}
