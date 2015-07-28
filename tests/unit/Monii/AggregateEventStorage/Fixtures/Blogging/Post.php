<?php

namespace Monii\AggregateEventStorage\Fixtures\Blogging;

use Monii\AggregateEventStorage\Fixtures\Blogging\PostId;

class Post
{
    /**
     * @var PostId
     */
    private $postId;

    public function __construct(PostId $postId)
    {
        $this->postId = $postId;
    }
}
