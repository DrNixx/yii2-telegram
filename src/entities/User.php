<?php
namespace onix\telegram\entities;

/**
 * Class User
 *
 * @link https://core.telegram.org/bots/api#user
 *
 * @property-read int $id Unique identifier for this user or bot
 * @property-read bool $isBot True, if this user is a bot
 * @property-read string $firstName User's or bot’s first name
 * @property-read string $lastName Optional. User's or bot’s last name
 * @property-read string $username Optional. User's or bot’s username
 * @property-read string $languageCode Optional. IETF language tag of the user's language
 * @property-read bool $canJoinGroups Optional. True, if the bot can be invited to groups. Returned only in getMe
 * @property-read bool $canReadAllGroupMessages Optional. True, if privacy mode is disabled for the bot
 * @property-read bool $isPremium Optional. True, if this user is a Telegram Premium user
 * @property-read bool $addedToAttachmentMenu Optional. True, if this user added the bot to the attachment menu
 * @property-read bool $supportsInlineQueries Optional. True, if the bot supports inline queries. Returned only in getMe
 */
class User extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return [
            'id',
            'isBot',
            'firstName',
            'lastName',
            'username',
            'languageCode',
            'canJoinGroups',
            'canReadAllGroupMessages',
            'isPremium',
            'addedToAttachmentMenu',
            'supportsInlineQueries',

        ];
    }
}
