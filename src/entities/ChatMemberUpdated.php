<?php
namespace onix\telegram\entities;

use onix\telegram\entities\chatMember\ChatMember;
use onix\telegram\entities\chatMember\Factory as ChatMemberFactory;

/**
 * Class ChatMemberUpdated
 *
 * @link https://core.telegram.org/bots/api#chatmemberupdated
 *
 * @property-read  Chat $chat Chat the user belongs to
 * @property-read User $from Performer of the action, which resulted in the change
 * @property-read int $date	Date the change was done in Unix time
 * @property-read ChatMember $oldChatMember Previous information about the chat member
 * @property-read ChatMember $newChatMember New information about the chat member
 * @property-read ChatInviteLink $inviteLink Optional. Chat invite link, which was used by the user to join the chat; for joining by invite link events only.
 */
class ChatMemberUpdated extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return [
            'chat',
            'from',
            'date',
            'oldChatMember',
            'newChatMember',
            'inviteLink',
        ];
    }

    /**
     * @inheritDoc
     */
    protected function subEntities()
    {
        return [
            'chat' => Chat::class,
            'from' => User::class,
            'oldChatMember' => ChatMemberFactory::class,
            'newChatMember' => ChatMemberFactory::class,
            'inviteLink' => ChatInviteLink::class,
        ];
    }
}