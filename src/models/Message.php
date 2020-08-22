<?php
namespace onix\telegram\models;

use onix\data\ActiveRecordEx;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "telegram.message".
 *
 * @property int $chat_id Unique chat identifier
 * @property int $id Unique message identifier
 * @property int $user_id Unique user identifier
 * @property string|null $date Entry date creation
 * @property int|null $forward_from Unique user identifier, sender of the original message
 * @property int|null $forward_from_chat Unique chat identifier, chat the original message belongs to
 * @property int|null $forward_from_message_id Unique chat identifier of the original message in the channel
 * @property string|null $forward_signature For messages forwarded from channels,
 * signature of the post author if present
 *
 * @property string|null $forward_sender_name Sender's name for messages forwarded from users who disallow adding a
 * link to their account in forwarded messages
 *
 * @property string|null $forward_date Date the original message was sent in timestamp format
 * @property int|null $reply_to_chat Unique chat identifier
 * @property int|null $reply_to_message Message that this message is reply to
 * @property int|null $via_bot Optional. Bot through which the message was sent
 * @property string|null $edit_date Date the message was last edited
 * @property string|null $media_group_id The unique identifier of a media message group this message belongs to
 * @property string|null $author_signature Signature of the post author for messages in channels
 * @property string|null $text For text messages, the actual UTF-8 text of the message max message
 * length 4096 char utf8mb4
 *
 * @property string|null $entities For text messages, special entities like usernames, URLs, bot commands,
 * etc. that appear in the text
 *
 * @property string|null $caption_entities For messages with a caption, special entities like usernames, URLs,
 * bot commands, etc. that appear in the caption
 *
 * @property string|null $audio Audio object. Message is an audio file, information about the file
 * @property string|null $document Document object. Message is a general file, information about the file
 * @property string|null $animation Message is an animation, information about the animation
 * @property string|null $game Game object. Message is a game, information about the game
 * @property string|null $photo Array of PhotoSize objects. Message is a photo, available sizes of the photo
 * @property string|null $sticker Sticker object. Message is a sticker, information about the sticker
 * @property string|null $video Video object. Message is a video, information about the video
 * @property string|null $voice Voice Object. Message is a Voice, information about the Voice
 * @property string|null $video_note VoiceNote Object. Message is a Video Note, information about the Video Note
 * @property string|null $caption For message with caption, the actual UTF-8 text of the caption
 * @property string|null $contact Contact object. Message is a shared contact, information about the contact
 * @property string|null $location Location object. Message is a shared location, information about the location
 * @property string|null $venue Venue object. Message is a Venue, information about the Venue
 * @property string|null $poll Poll object. Message is a native poll, information about the poll
 * @property string|null $dice Message is a dice with random value from 1 to 6
 * @property string|null $new_chat_members List of unique user identifiers, new member(s) were added to the group,
 * information about them (one of these members may be the bot itself)
 *
 * @property int|null $left_chat_member Unique user identifier, a member was removed from the group,
 * information about them (this member may be the bot itself)
 *
 * @property string|null $new_chat_title A chat title was changed to this value
 * @property string|null $new_chat_photo Array of PhotoSize objects. A chat photo was change to this value
 * @property bool $delete_chat_photo Informs that the chat photo was deleted
 * @property bool $group_chat_created Informs that the group has been created
 * @property bool $supergroup_chat_created Informs that the supergroup has been created
 * @property bool $channel_chat_created Informs that the channel chat has been created
 * @property int|null $migrate_to_chat_id Migrate to chat identifier. The group has been migrated to a supergroup
 * with the specified identifier
 *
 * @property int|null $migrate_from_chat_id Migrate from chat identifier. The supergroup has been migrated from
 * a group with the specified identifier
 *
 * @property string|null $pinned_message Message object. Specified message was pinned
 * @property string|null $invoice Message is an invoice for a payment, information about the invoice
 * @property string|null $successful_payment Message is a service message about a successful payment,
 * information about the payment
 *
 * @property string|null $connected_website The domain name of the website on which the user has logged in.
 * @property string|null $passport_data Telegram Passport data
 * @property string|null $reply_markup Inline keyboard attached to the message
 *
 * @property CallbackQuery[] $callbackQueries
 * @property Chat $chat
 * @property EditedMessage[] $editedMessages
 * @property User $forwardFrom
 * @property Chat $forwardFromChat
 * @property User $leftChatMember
 * @property Message[] $messages
 * @property Message $replyToChat
 * @property TelegramUpdate[] $telegramUpdates
 * @property TelegramUpdate[] $telegramUpdates0
 * @property User $user
 * @property User $viaBot
 */
class Message extends ActiveRecordEx
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'telegram.message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chat_id', 'id', 'user_id'], 'required'],
            [
                [
                    'chat_id',
                    'id',
                    'user_id',
                    'forward_from',
                    'forward_from_chat',
                    'forward_from_message_id',
                    'reply_to_chat',
                    'reply_to_message',
                    'via_bot',
                    'left_chat_member',
                    'migrate_to_chat_id',
                    'migrate_from_chat_id'
                ],
                'default',
                'value' => null
            ],
            [
                [
                    'chat_id',
                    'id',
                    'user_id',
                    'forward_from',
                    'forward_from_chat',
                    'forward_from_message_id',
                    'reply_to_chat',
                    'reply_to_message',
                    'via_bot',
                    'left_chat_member',
                    'migrate_to_chat_id',
                    'migrate_from_chat_id'
                ],
                'integer'
            ],
            [['date', 'forward_date', 'edit_date'], 'safe'],
            [
                [
                    'forward_signature',
                    'forward_sender_name',
                    'media_group_id',
                    'author_signature',
                    'text',
                    'entities',
                    'caption_entities',
                    'audio',
                    'document',
                    'animation',
                    'game',
                    'photo',
                    'sticker',
                    'video',
                    'voice',
                    'video_note',
                    'caption',
                    'contact',
                    'location',
                    'venue',
                    'poll',
                    'dice',
                    'new_chat_members',
                    'new_chat_photo',
                    'pinned_message',
                    'invoice',
                    'successful_payment',
                    'connected_website',
                    'passport_data',
                    'reply_markup'
                ],
                'string'
            ],
            [['delete_chat_photo', 'group_chat_created', 'supergroup_chat_created', 'channel_chat_created'], 'boolean'],
            [['new_chat_title'], 'string', 'max' => 255],
            [['chat_id', 'id'], 'unique', 'targetAttribute' => ['chat_id', 'id']],
            [
                ['chat_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Chat::class,
                'targetAttribute' => ['chat_id' => 'id']
            ],
            [
                ['forward_from_chat'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Chat::class,
                'targetAttribute' => ['forward_from_chat' => 'id']
            ],
            [
                ['reply_to_chat', 'reply_to_message'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Message::class,
                'targetAttribute' => ['reply_to_chat' => 'chat_id', 'reply_to_message' => 'id']
            ],
            [
                ['user_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['user_id' => 'id']
            ],
            [
                ['forward_from'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['forward_from' => 'id']
            ],
            [
                ['via_bot'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['via_bot' => 'id']
            ],
            [
                ['left_chat_member'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['left_chat_member' => 'id']
            ],
        ];
    }

    /**
     * Gets query for [[CallbackQueries]].
     *
     * @return ActiveQuery|CallbackQueryQuery
     */
    public function getCallbackQueries()
    {
        return $this->hasMany(CallbackQuery::class, ['chat_id' => 'chat_id', 'message_id' => 'id']);
    }

    /**
     * Gets query for [[Chat]].
     *
     * @return ActiveQuery|ChatQuery
     */
    public function getChat()
    {
        return $this->hasOne(Chat::class, ['id' => 'chat_id']);
    }

    /**
     * Gets query for [[EditedMessages]].
     *
     * @return ActiveQuery|EditedMessageQuery
     */
    public function getEditedMessages()
    {
        return $this->hasMany(EditedMessage::class, ['chat_id' => 'chat_id', 'message_id' => 'id']);
    }

    /**
     * Gets query for [[ForwardFrom]].
     *
     * @return ActiveQuery|UserQuery
     */
    public function getForwardFrom()
    {
        return $this->hasOne(User::class, ['id' => 'forward_from']);
    }

    /**
     * Gets query for [[ForwardFromChat]].
     *
     * @return ActiveQuery|ChatQuery
     */
    public function getForwardFromChat()
    {
        return $this->hasOne(Chat::class, ['id' => 'forward_from_chat']);
    }

    /**
     * Gets query for [[LeftChatMember]].
     *
     * @return ActiveQuery|UserQuery
     */
    public function getLeftChatMember()
    {
        return $this->hasOne(User::class, ['id' => 'left_chat_member']);
    }

    /**
     * Gets query for [[Messages]].
     *
     * @return ActiveQuery|MessageQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::class, ['reply_to_chat' => 'chat_id', 'reply_to_message' => 'id']);
    }

    /**
     * Gets query for [[ReplyToChat]].
     *
     * @return ActiveQuery|MessageQuery
     */
    public function getReplyToChat()
    {
        return $this->hasOne(Message::class, ['chat_id' => 'reply_to_chat', 'id' => 'reply_to_message']);
    }

    /**
     * Gets query for [[TelegramUpdates]].
     *
     * @return ActiveQuery|TelegramUpdateQuery
     */
    public function getTelegramUpdates()
    {
        return $this->hasMany(TelegramUpdate::class, ['chat_id' => 'chat_id', 'message_id' => 'id']);
    }

    /**
     * Gets query for [[TelegramUpdates0]].
     *
     * @return ActiveQuery|TelegramUpdateQuery
     */
    public function getTelegramUpdates0()
    {
        return $this->hasMany(TelegramUpdate::class, ['chat_id' => 'chat_id', 'channel_post_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery|UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Gets query for [[ViaBot]].
     *
     * @return ActiveQuery|UserQuery
     */
    public function getViaBot()
    {
        return $this->hasOne(User::class, ['id' => 'via_bot']);
    }

    /**
     * {@inheritdoc}
     * @return MessageQuery the active query used by this AR class.
     */
    public static function find($alias = null)
    {
        return new MessageQuery(get_called_class(), ['as' => $alias]);
    }
}
