<?php
namespace onix\telegram\entities;

/**
 * Class ProximityAlertTriggered
 *
 * Represents the content of a service message, sent whenever a user in the chat triggers a proximity alert set by another user.
 *
 * @link https://core.telegram.org/bots/api#proximityalerttriggered
 *
 * @property-read User $traveler User that triggered the alert
 * @property-read User $watcher User that set the alert
 * @property-read int  $distance The distance between the users
 */
class ProximityAlertTriggered extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return [
            'traveler',
            'watcher',
            'distance'
        ];
    }

    /**
     * @inheritDoc
     */
    protected function subEntities()
    {
        return [
            'traveler' => User::class,
            'watcher' => User::class,
        ];
    }
}