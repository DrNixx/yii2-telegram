<?php
namespace onix\telegram\models;

use onix\telegram\entities\Animation;
use onix\telegram\entities\Audio;
use onix\telegram\entities\ChatPhoto;
use onix\telegram\entities\Contact;
use onix\telegram\entities\Dice;
use onix\telegram\entities\Document;
use onix\telegram\entities\games\Game;
use onix\telegram\entities\InlineKeyboard;
use onix\telegram\entities\Location;
use onix\telegram\entities\Message as MessageEntity;
use onix\telegram\entities\MessageAutoDeleteTimerChanged;
use onix\telegram\entities\MessageEntity as MessageEntityEntity;
use onix\telegram\entities\Poll as PollEntity;
use onix\telegram\entities\Sticker;
use onix\telegram\entities\Venue;
use onix\telegram\entities\Video;
use onix\telegram\entities\VideoNote;
use onix\telegram\entities\Voice;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "telegram.message".
 *
 * @property object $_id Unique message identifier
 * @property int $chatId Unique chat identifier
 * @property int $id Unique message identifier
 * @property int $userId Unique user identifier
 * @property int $senderChatId Sender of the message, sent on behalf of a chat
 * @property string|null $date Entry date creation
 * @property int|null $forwardFrom Unique user identifier, sender of the original message
 * @property int|null $forwardFromChat Unique chat identifier, chat the original message belongs to
 * @property int|null $forwardFromMessageId Unique chat identifier of the original message in the channel
 * @property string|null $forwardSignature For messages forwarded from channels, signature of the post author if present
 *
 * @property string|null $forwardSenderName Sender's name for messages forwarded from users who disallow adding a
 * link to their account in forwarded messages
 *
 * @property string|null $forwardDate Date the original message was sent in timestamp format
 * @property int|null $replyToChat Unique chat identifier
 * @property int|null $replyToMessage Message that this message is reply to
 * @property int|null $viaBot Optional. Bot through which the message was sent
 * @property string|null $editDate Date the message was last edited
 * @property string|null $mediaGroupId The unique identifier of a media message group this message belongs to
 * @property string|null $authorSignature Signature of the post author for messages in channels
 * @property string|null $text For text messages, the actual UTF-8 text of the message max message
 * length 4096 char utf8mb4
 *
 * @property MessageEntityEntity[]|null $entities For text messages, special entities like usernames, URLs, bot commands,
 * etc. that appear in the text
 *
 * @property MessageEntityEntity[]|null $captionEntities For messages with a caption, special entities like usernames, URLs,
 * bot commands, etc. that appear in the caption
 *
 * @property Audio|null $audio Audio object. Message is an audio file, information about the file
 * @property Document|null $document Document object. Message is a general file, information about the file
 * @property Animation|null $animation Message is an animation, information about the animation
 * @property Game|null $game Game object. Message is a game, information about the game
 * @property ChatPhoto|null $photo Array of PhotoSize objects. Message is a photo, available sizes of the photo
 * @property Sticker|null $sticker Sticker object. Message is a sticker, information about the sticker
 * @property Video|null $video Video object. Message is a video, information about the video
 * @property Voice|null $voice Voice Object. Message is a Voice, information about the Voice
 * @property VideoNote|null $videoNote VoiceNote Object. Message is a Video Note, information about the Video Note
 * @property string|null $caption For message with caption, the actual UTF-8 text of the caption
 * @property Contact|null $contact Contact object. Message is a shared contact, information about the contact
 * @property Location|null $location Location object. Message is a shared location, information about the location
 * @property Venue|null $venue Venue object. Message is a Venue, information about the Venue
 * @property PollEntity|null $poll Poll object. Message is a native poll, information about the poll
 * @property Dice|null $dice Message is a dice with random value from 1 to 6
 * @property int[]|null $newChatMembers List of unique user identifiers, new member(s) were added to the group,
 * information about them (one of these members may be the bot itself)
 *
 * @property int|null $leftChatMember Unique user identifier, a member was removed from the group,
 * information about them (this member may be the bot itself)
 *
 * @property string|null $newChatTitle A chat title was changed to this value
 * @property string|null $newChatPhoto Array of PhotoSize objects. A chat photo was change to this value
 * @property bool $deleteChatPhoto Informs that the chat photo was deleted
 * @property bool $groupChatCreated Informs that the group has been created
 * @property bool $supergroupChatCreated Informs that the supergroup has been created
 * @property bool $channelChatCreated Informs that the channel chat has been created
 * @property int|null $migrateToChatId Migrate to chat identifier. The group has been migrated to a supergroup
 * with the specified identifier
 *
 * @property int|null $migrateFromChatId Migrate from chat identifier. The supergroup has been migrated from
 * a group with the specified identifier
 *
 * @property string|null $pinnedMessage Message object. Specified message was pinned
 * @property string|null $invoice Message is an invoice for a payment, information about the invoice
 * @property string|null $successfulPayment Message is a service message about a successful payment,
 * information about the payment
 *
 * @property string|null $connectedWebsite The domain name of the website on which the user has logged in.
 * @property string|null $passportData Telegram Passport data
 *
 * @property string|null $proximityAlertTriggered Service message. A user in the chat triggered another user's
 * proximity alert while sharing Live Location.
 *
 * @property MessageAutoDeleteTimerChanged|null $messageAutoDeleteTimerChanged MessageAutoDeleteTimerChanged object. Message is a service
 * message: auto-delete timer settings changed in the chat
 *
 * @property string|null $voiceChatStarted VoiceChatStarted object. Message is a service message: voice chat started
 * @property string|null $voiceChatEnded VoiceChatEnded object. Message is a service message: voice chat ended
 * @property string|null $voiceChatParticipantsInvited VoiceChatParticipantsInvited object. Message is a service
 * message: new participants invited to a voice chat
 *
 * @property InlineKeyboard|null $replyMarkup Inline keyboard attached to the message
 */
class Message extends TelegramActiveRecord
{
    protected ?string $entityClass = MessageEntity::class;

    protected array $ownAttributes = ['_id'];

    protected array $attributeMap = [
        'chat' => 'chatId',
        'messageId' => 'id',
        'from' => 'userId',
        'senderChat' => 'senderChatId',
        'replyToMessage' => ['replyToChat', 'replyToMessage'],
    ];

    /**
     * {@inheritdoc}
     */
    public static function collectionName(): array|string
    {
        return 'telegram_message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'chatId', 'userId'], 'required'],
            [
                [
                    'chatId',
                    'id',
                    'userId',
                    'senderChatId',
                    'forwardFrom',
                    'forwardFromChat',
                    'forwardFromMessageId',
                    'replyToChat',
                    'replyToMessage',
                    'viaBot',
                    'leftChatMember',
                    'migrateToChatId',
                    'migrateFromChatId',
                ],
                'default',
                'value' => null
            ],
            [
                [
                    'chatId',
                    'id',
                    'userId',
                    'forwardFrom',
                    'forwardFromChat',
                    'forwardFromMessageId',
                    'replyToChat',
                    'replyToMessage',
                    'viaBot',
                    'leftChatMember',
                    'migrateToChatId',
                    'migrateFromChatId',
                    'senderChatId'
                ],
                'integer'
            ],
            [['date', 'forwardDate', 'editDate'], 'safe'],
            [
                [
                    'entities',
                    'captionEntities',
                    'audio',
                    'document',
                    'animation',
                    'game',
                    'photo',
                    'sticker',
                    'voice',
                    'video',
                    'videoNote',
                    'contact',
                    'location',
                    'venue',
                    'poll',
                    'dice',
                    'replyMarkup',
                    'newChatPhoto',
                    'newChatMembers',
                    'messageAutoDeleteTimerChanged',
                    'pinnedMessage',
                    'invoice',
                    'successfulPayment',
                    'passportData',
                    'proximityAlertTriggered',
                    'voiceChatStarted',
                    'voiceChatEnded',
                    'voiceChatParticipantsInvited',
                ], 'safe'
            ],
            [
                [
                    'forwardSignature',
                    'forwardSenderName',
                    'mediaGroupId',
                    'authorSignature',
                    'text',
                    'caption',
                    'connectedWebsite',
                ],
                'string'
            ],
            [
                ['deleteChatPhoto', 'groupChatCreated', 'supergroupChatCreated', 'channelChatCreated'],
                'boolean'
            ],
            [
                [
                    'deleteChatPhoto',
                    'groupChatCreated',
                    'supergroupChatCreated',
                    'channelChatCreated'
                ],
                'default',
                'value' => false
            ],
            [['newChatTitle'], 'string', 'max' => 255],
            [['chatId', 'id'], 'unique', 'targetAttribute' => ['chatId', 'id']],
            [
                ['replyToChat', 'replyToMessage'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Message::class,
                'targetAttribute' => ['replyToChat' => 'chatId', 'replyToMessage' => 'id']
            ],
            [
                ['userId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['userId' => '_id']
            ],
            [
                ['forwardFrom'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['forwardFrom' => '_id']
            ],
            [
                ['viaBot'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['viaBot' => '_id']
            ],
            [
                ['leftChatMember'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['leftChatMember' => '_id']
            ],
        ];
    }


    /**
     * {@inheritdoc}
     * @return MessageQuery the active query used by this AR class.
     */
    public static function find(): MessageQuery
    {
        return new MessageQuery(get_called_class());
    }

    /**
     * @throws \Exception
     */
    public static function checkIndices(): void
    {
        $coll = self::getCollection();
        $idxs = $coll->listIndexes();

        $inames = [];
        foreach ($idxs as $idx) {
            $inames[$idx['name']] = 1;
        }

        if (empty($inames['_chatId_id'])) {
            $coll->createIndex(['chatId', 'id'], ['unique' => false, 'name' => '_chatId_id']);
        }
    }
}
