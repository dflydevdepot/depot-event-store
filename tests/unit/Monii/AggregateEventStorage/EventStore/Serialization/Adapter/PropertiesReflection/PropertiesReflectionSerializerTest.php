<?php

namespace Monii\AggregateEventStorage\EventStore\Serialization\Adapter\PropertiesReflection;

use Monii\AggregateEventStorage\Contract\SimplePhpFqcnContractResolver;
use Monii\AggregateEventStorage\Fixtures\Banking\Account\AccountWasOpened;
use Monii\AggregateEventStorage\Fixtures\Blogging\Post;
use Monii\AggregateEventStorage\Fixtures\Blogging\PostId;
use PHPUnit_Framework_TestCase as TestCase;

class PropertiesReflectionSerializerTest extends TestCase
{
    /**
     * @dataProvider provideRoundTripData
     */
    public function testRoundTrip($input)
    {
        $contractResolver = new SimplePhpFqcnContractResolver();

        $reflectionSerializer = new PropertiesReflectionSerializer($contractResolver);

        $contract = $contractResolver->resolveFromObject($input);

        if (!$reflectionSerializer->canSerialize($contract, $input))
        {
            // Stop here and mark this test as failed.
            $this->fail('Unable to serialize');
        }

        $data = $reflectionSerializer->serialize($contract, $input);

        // Let's simulate converting this to a JSON string and
        // back again.
        $data = json_decode(json_encode($data), true);

        if (!$reflectionSerializer->canDeserialize($contract, $data))
        {
            // Stop here and mark this test as failed.
            $this->fail('Unable to deserialize array ' . print_r($data, true));
        }

        $object = $reflectionSerializer->deserialize($contract, $data);

        $this->assertEquals($input, $object);
    }

    public function provideRoundTripData()
    {
        $complicated = new PropertiesReflectionSerializerFixture();
        $complicated->setPrivateOuterValue('a');
        $complicated->setPrivateExtendedValue('b');
        $complicated->setPrivateTraitValue('c');

        return [
            [new AccountWasOpened('fixture-account-000', 25)],
            [new Post(PostId::fromString('first-post'))],
            [$complicated],
        ];
    }
}

trait PropertiesReflectionSerializerTrait
{
    private $privateTraitValue;
    public function setPrivateTraitValue($privateTraitValue)
    {
        $this->privateTraitValue = $privateTraitValue;
    }
}

class PropertiesReflectionSerializerExtended
{
    use PropertiesReflectionSerializerTrait;

    private $privateExtendedValue;

    public function setPrivateExtendedValue($privateExtendedValue)
    {
        $this->privateExtendedValue = $privateExtendedValue;
    }
}

class PropertiesReflectionSerializerFixture extends PropertiesReflectionSerializerExtended
{
    private $privateOuterValue;

    public function setPrivateOuterValue($privateOuterValue)
    {
        $this->privateOuterValue = $privateOuterValue;
    }
}
