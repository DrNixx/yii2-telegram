<?php
namespace onix\telegram\entities;

use onix\telegram\entities\games\Game;
use onix\telegram\entities\passport\PassportData;
use onix\telegram\entities\payments\Invoice;
use onix\telegram\entities\payments\SuccessfulPayment;

/**
 * Class Message
 *
 * @link https://core.telegram.org/bots/api#message
 *
 * @property-read int $messageId Unique message identifier
 * @property-read int $messageThreadId Optional. Unique identifier of a message thread to which the message belongs;
 * for supergroups only
 * @property-read User $from Optional. Sender, can be empty for messages sent to channels
 * @property-read Chat $senderChat Optional. Sender of the message, sent on behalf of a chat. The channel itself for
 * channel messages. The supergroup itself for messages from anonymous group administrators. The linked channel
 * for messages automatically forwarded to the discussion group
 * @property-read int $date Date the message was sent in Unix time
 * @property-read Chat $chat Conversation the message belongs to
 * @property-read User $forwardFrom Optional. For forwarded messages, sender of the original message
 * @property-read Chat $forwardFromChat Optional. For messages forwarded from a channel, information about
 * the original channel
 *
 * @property-read int $forwardFromMessageId Optional. For forwarded channel posts, identifier of the original
 * message in the channel
 *
 * @property-read string $forwardSignature Optional. For messages forwarded from channels, signature of the post
 * author if present
 *
 * @property-read string $forwardSenderName Optional. Sender's name for messages forwarded from users who disallow
 * adding a link to their account in forwarded messages
 *
 * @property-read bool $isTopicMessage Optional. True, if the message is sent to a forum topic
 *
 * @property-read bool $isAutomaticForward Optional. True, if the message is a channel post that was automatically
 * forwarded to the connected discussion group
 *
 * @property-read int $forwardDate Optional. For forwarded messages, date the original message was sent in Unix time
 * @property-read ReplyToMessage $replyToMessage Optional. For replies, the original message. Note that the Message
 * object in this field will not contain further reply_to_message fields even if it itself is a reply.
 *
 * @property-read User $viaBot Optional. Bot through which the message was sent
 * @property-read int $editDate Optional. Date the message was last edited in Unix time
 * @property-read string $mediaGroupId Optional. The unique identifier of a media message group this message belongs to
 * @property-read string $authorSignature Optional. Signature of the post author for messages in channels
 * @property-read string $text Optional. For text messages, the actual UTF-8 text of the message, 0-4096 characters
 * @property-read MessageEntity[] $entities Optional. For text messages, special entities like usernames, URLs,
 * bot commands, etc. that appear in the text
 *
 * @property-read MessageEntity[] $captionEntities Optional. For messages with a caption, special entities
 * like usernames,
 * URLs, bot commands, etc. that appear in the caption
 *
 * @property-read Audio $audio Optional. Message is an audio file, information about the file
 * @property-read Document $document Optional. Message is a general file, information about the file
 * @property-read Animation $animation Optional. Message is an animation, information about the animation.
 * For backward compatibility, when this field is set, the document field will also be set
 *
 * @property-read Game $game Optional. Message is a game, information about the game.
 * @property-read PhotoSize[] $photo Optional. Message is a photo, available sizes of the photo
 * @property-read Sticker $sticker Optional. Message is a sticker, information about the sticker
 * @property-read Video $video Optional. Message is a video, information about the video
 * @property-read Voice $voice Optional. Message is a voice message, information about the file
 * @property-read VideoNote $videoNote Optional. Message is a video note message, information about the video
 * @property-read string $caption Optional. Caption for the document, photo or video, 0-200 characters
 * @property-read Contact $contact Optional. Message is a shared contact, information about the contact
 * @property-read Location $location Optional. Message is a shared location, information about the location
 * @property-read Venue $venue Optional. Message is a venue, information about the venue
 * @property-read Poll $poll Optional. Message is a native poll, information about the poll
 * @property-read Dice $dice Optional. Message is a dice with random value from 1 to 6
 * @property-read User[] $newChatMembers Optional. A new member(s) was added to the group, information about them
 * (one of this members may be the bot itself)
 *
 * @property-read User $leftChatMember Optional. A member was removed from the group, information about them
 * (this member may be the bot itself)
 *
 * @property-read string $newChatTitle Optional. A chat title was changed to this value
 * @property-read PhotoSize[] $newChatPhoto Optional. A chat photo was changed to this value
 * @property-read bool $deleteChatPhoto Optional. Service message: the chat photo was deleted
 * @property-read bool $groupChatCreated Optional. Service message: the group has been created
 * @property-read bool $supergroupChatCreated Optional. Service message: the supergroup has been created.
 * This field can't be received in a message coming through updates, because bot can’t be a member of a supergroup
 * when it is created. It can only be found in reply_to_message if someone replies to a very first message in
 * a directly created supergroup.
 *
 * @property-read bool $channelChatCreated Optional. Service message: the channel has been created. This field can't
 * be received in a message coming through updates, because bot can’t be a member of a channel when it is created.
 * It can only be found in reply_to_message if someone replies to a very first message in a channel.
 *
 * @property-read MessageAutoDeleteTimerChanged $messageAutoDeleteTimerChanged Optional. Service message:
 * auto-delete timer settings changed in the chat
 *
 * @property-read int $migrateToChatId Optional. The group has been migrated to a supergroup with the specified
 * identifier. This number may be greater than 32 bits and some programming languages may have difficulty/silent
 * defects in interpreting it. But it smaller than 52 bits, so a signed 64-bit integer or double-precision float type
 * are safe for storing this identifier.
 *
 * @property-read int $migrateFromChatId Optional. The supergroup has been migrated from a group with the specified
 * identifier. This number may be greater than 32 bits and some programming languages may have difficulty/silent
 * defects in interpreting it. But it smaller than 52 bits, so a signed 64-bit integer or double-precision float type
 * are safe for storing this identifier.
 *
 * @property-read Message $pinnedMessage Optional. Specified message was pinned. Note that the Message object in this
 * field will not contain further reply_to_message fields even if it is itself a reply.
 *
 * @property-read Invoice $invoice Optional. Message is an invoice for a payment, information about the invoice.
 * @property-read SuccessfulPayment $successfulPayment Optional. Message is a service message about a successful
 * payment, information about the payment.
 *
 * @property-read string $connectedWebsite Optional. The domain name of the website on which the user has logged in.
 * @property-read PassportData $passportData Optional. Telegram Passport data
 *
 * @property-read ProximityAlertTriggered $proximityAlertTriggered	Optional. Service message. A user in the chat triggered another user's proximity alert while sharing Live Location.
 *
 * @property-read VoiceChatStarted $voiceChatStarted Optional. Service message: voice chat started
 * @property-read VoiceChatEnded $voiceChatEnded Optional. Service message: voice chat ended
 * @property-read VoiceChatParticipantsInvited $voiceChatParticipantsInvited Optional. Service message: new participants invited to a voice chat
 *
 * @property-read InlineKeyboard $replyMarkup Optional. Inline keyboard attached to the message. login_url buttons are
 * represented as ordinary url buttons.
 *
 * @property string $editedMessageId
 *
 * @property-read string $command
 * @property-read string $fullCommand
 * @property-read string $messageText
 */
class Message extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return [
            'messageId',
            'messageThreadId',
            'from',
            'senderChat',
            'date',
            'chat',
            'forwardFrom',
            'forwardFromChat',
            'forwardFromMessageId',
            'forwardSignature',
            'forwardSenderName',
            'isTopicMessage',
            'isAutomaticForward',
            'forwardDate',
            'replyToMessage',
            'viaBot',
            'editDate',
            'mediaGroupId',
            'authorSignature',
            'text',
            'entities',
            'animation',
            'audio',
            'document',
            'photo',
            'sticker',
            'video',
            'videoNote',
            'voice',
            'caption',
            'captionEntities',
            'contact',
            'dice',
            'game',
            'poll',
            'venue',
            'location',
            'newChatParticipant', // depricated
            'newChatMember', // depricated
            'newChatMembers',
            'leftChatMember',
            'newChatTitle',
            'newChatPhoto',
            'deleteChatPhoto',
            'groupChatCreated',
            'supergroupChatCreated',
            'channelChatCreated',
            'messageAutoDeleteTimerChanged',
            'migrateToChatId',
            'migrateFromChatId',
            'pinnedMessage',
            'invoice',
            'successfulPayment',
            'connectedWebsite',
            'passportData',
            'proximityAlertTriggered',
            'voiceChatStarted',
            'voiceChatEnded',
            'voiceChatParticipantsInvited',
            'replyMarkup',
            'editedMessageId'
        ];
    }

    /**
     * @inheritDoc
     */
    protected function subEntities()
    {
        return [
            'from' => User::class,
            'senderChat' => Chat::class,
            'chat' => Chat::class,
            'forwardFrom' => User::class,
            'forwardFromChat' => Chat::class,
            'replyToMessage' => ReplyToMessage::class,
            'viaBot' => User::class,
            'entities' => [MessageEntity::class],
            'captionEntities' => [MessageEntity::class],
            'audio' => Audio::class,
            'document' => Document::class,
            'animation' => Animation::class,
            'game' => Game::class,
            'photo' => [PhotoSize::class],
            'sticker' => Sticker::class,
            'video' => Video::class,
            'voice' => Voice::class,
            'videoNote' => VideoNote::class,
            'contact' => Contact::class,
            'location' => Location::class,
            'venue' => Venue::class,
            'poll' => Poll::class,
            'dice' => Dice::class,
            'newChatMembers' => [User::class],
            'leftChatMember' => User::class,
            'newChatPhoto' => [PhotoSize::class],
            'pinnedMessage' => Message::class,
            'invoice' => Invoice::class,
            'successfulPayment' => SuccessfulPayment::class,
            'passportData' => PassportData::class,
            'proximityAlertTriggered' => ProximityAlertTriggered::class,
            'voiceChatStarted' => VoiceChatStarted::class,
            'voiceChatEnded' => VoiceChatEnded::class,
            'voiceChatParticipantsInvited' => VoiceChatParticipantsInvited::class,
            'messageAutoDeleteTimerChanged' => MessageAutoDeleteTimerChanged::class,
            'replyMarkup' => InlineKeyboard::class,
        ];
    }

    /**
     * return the entire command like /echo or /echo@bot1 if specified
     *
     * @return string|null
     */
    public function getFullCommand()
    {
        $text = $this->text;
        if (strpos($text, '/') !== 0) {
            return null;
        }

        $no_EOL   = strtok($text, PHP_EOL);
        $no_space = strtok($text, ' ');

        //try to understand which separator \n or space divide /command from text
        return strlen($no_space) < strlen($no_EOL) ? $no_space : $no_EOL;
    }

    /**
     * Get command
     *
     * @return string|null
     */
    public function getCommand()
    {
        $full_command = $this->fullCommand;
        if (strpos($full_command, '/') !== 0) {
            return null;
        }
        $full_command = substr($full_command, 1);

        //check if command is followed by bot username
        $split_cmd = explode('@', $full_command);
        if (!isset($split_cmd[1])) {
            //command is not followed by name
            return $full_command;
        }

        if (strtolower($split_cmd[1]) === strtolower($this->telegram->botUsername)) {
            //command is addressed to me
            return $split_cmd[0];
        }

        return null;
    }

    /**
     * For text messages, the actual UTF-8 text of the message, 0-4096 characters.
     *
     * @param bool $without_cmd
     *
     * @return string
     */
    public function getMessageText($without_cmd = false)
    {
        $text = $this->getAttribute('text');

        if ($without_cmd && $command = $this->fullCommand) {
            if (strlen($command) + 1 < strlen($text)) {
                return substr($text, strlen($command) + 1);
            }

            return '';
        }

        return $text;
    }

    /**
     * Bot added in chat
     *
     * @return bool
     */
    public function botAddedInChat()
    {
        foreach ($this->newChatMembers as $member) {
            if ($member instanceof User && $member->username === $this->telegram->botUsername) {
                return true;
            }
        }

        return false;
    }

    /**
     * Detect type based on properties.
     *
     * @return string
     */
    public function getType()
    {
        $types = [
            'text',
            'audio',
            'animation',
            'document',
            'game',
            'photo',
            'sticker',
            'video',
            'voice',
            'video_note',
            'contact',
            'location',
            'venue',
            'poll',
            'new_chat_members',
            'left_chat_member',
            'new_chat_title',
            'new_chat_photo',
            'delete_chat_photo',
            'group_chat_created',
            'supergroup_chat_created',
            'channel_chat_created',
            'migrate_to_chat_id',
            'migrate_from_chat_id',
            'pinned_message',
            'invoice',
            'successful_payment',
            'passport_data',
            'reply_markup',
        ];

        $is_command = strlen($this->command) > 0;
        foreach ($types as $type) {
            if ($this->getAttribute($type) !== null) {
                if ($is_command && $type === 'text') {
                    return 'command';
                }

                return $type;
            }
        }

        return 'message';
    }
}
