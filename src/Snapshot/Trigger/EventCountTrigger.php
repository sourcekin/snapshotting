<?php

/*
 * This file is part of the broadway/snapshotting package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Broadway\Snapshotting\Snapshot\Trigger;

use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Broadway\Snapshotting\Snapshot\Trigger;

class EventCountTrigger implements Trigger
{
    /**
     * @var int
     */
    private $eventCount;

    /**
     * @param int $eventCount
     */
    public function __construct($eventCount = 20)
    {
        $this->eventCount = $eventCount;
    }

    /**
     * {@inheritdoc}
     */
    public function shouldSnapshot(EventSourcedAggregateRoot $aggregateRoot)
    {
        $clonedAggregateRoot = clone $aggregateRoot;

        foreach ($clonedAggregateRoot->getUncommittedEvents() as $domainMessage) {
            if (($domainMessage->getPlayhead() + 1) % $this->eventCount === 0) {
                return true;
            }
        }

        return false;
    }
}
