<?php
namespace onix\telegram\entities;

/**
 * Class WebhookInfo
 *
 * @link https://core.telegram.org/bots/api#webhookinfo
 *
 * @property-read string url Webhook URL, may be empty if webhook is not set up
 * @property-read bool $hasCustomCertificate True, if a custom certificate was provided for webhook certificate checks
 * @property-read int $pendingUpdateCount Number of updates awaiting delivery
 * @property-read string $ipAddress
 * @property-read int $lastErrorDate Optional. Unix time for the most recent error that happened when trying to
 * deliver an update via webhook
 *
 * @property-read string $lastErrorMessage Optional. Error message in human-readable format for the most recent error
 * that happened when trying to deliver an update via webhook
 *
 * @property-read int $maxConnections Optional. Maximum allowed number of simultaneous HTTPS connections to
 * the webhook for update delivery
 *
 * @property-read string[] $allowedUpdates Optional. A list of update types the bot is subscribed to.
 * Defaults to all update types
 */
class WebhookInfo extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return [
            'url',
            'hasCustomCertificate',
            'pendingUpdateCount',
            'ipAddress',
            'lastErrorDate',
            'lastErrorMessage',
            'maxConnections',
            'allowedUpdates'
        ];
    }
}
