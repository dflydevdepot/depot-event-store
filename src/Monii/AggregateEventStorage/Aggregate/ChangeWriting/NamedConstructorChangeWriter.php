<?php

namespace Monii\AggregateEventStorage\Aggregate\ChangeWriting;

use Monii\AggregateEventStorage\Aggregate\Error\AggregateChangeNotSupported;

class NamedConstructorChangeWriter implements ChangeWriter
{
    /**
     * @var string
     */
    private $staticConstructorMethodName;

    /**
     * @var string
     */
    private $changeClassName;

    public function __construct(
        $staticConstructorMethod = 'instantiateAggregateChangeFromEventAndMetadata',
        $changeClassName = null
    ) {
        $this->staticConstructorMethodName = $staticConstructorMethod;
        $this->changeClassName = $changeClassName;
    }

    /**
     * {@inheritdoc}
     */
    public function writeChange($event, $metadata = null)
    {
        $this->assertStaticMethodCallExists();

        $staticMethodCall = $this->getStaticMethodCall();

        $object = call_user_func($staticMethodCall, $event, $metadata);

        return $object;
    }

    private function assertStaticMethodCallExists()
    {
        if (method_exists($this->changeClassName, $this->staticConstructorMethodName)) {
            return;
        }

        throw AggregateChangeNotSupported::becauseClassDoesNotHaveAnExpectedStaticMethod(
            $this->changeClassName,
            $this->staticConstructorMethodName
        );
    }

    private function getStaticMethodCall()
    {
        return sprintf('%s::%s', $this->changeClassName, $this->staticConstructorMethodName);
    }
}
