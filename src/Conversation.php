<?php
namespace onix\telegram;

use onix\telegram\models\Conversation as ConversationRepo;
use yii\base\BaseObject;
use yii\base\Exception as BaseException;
use yii\helpers\Json;

/**
 * Class Conversation
 *
 * Only one conversation can be active at any one time.
 * A conversation is directly linked to a user, chat and the command that is managing the conversation.
 *
 * @property-read int $userId;
 * @property-read int $chatId;
 * @property-read string $commandText;
 */
class Conversation extends BaseObject
{
    /**
     * All information fetched from the database
     *
     * @var ConversationRepo|null
     */
    protected ?ConversationRepo $conversation = null;

    /**
     * Notes stored inside the conversation
     *
     * @var mixed|null
     */
    protected mixed $protected_notes = null;

    /**
     * Notes to be stored
     *
     * @var mixed|null
     */
    public mixed $notes = null;

    /**
     * Telegram user id
     *
     * @var int
     */
    public int $user_id;

    /**
     * Telegram chat id
     *
     * @var int
     */
    public int $chat_id;

    /**
     * Command to be executed if the conversation is active
     *
     * @var string|null
     */
    public ?string $command = null;

    /**
     * Conversation contructor to initialize a new conversation
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    /**
     * @throws BaseException
     */
    public function init(): void
    {
        parent::init();

        //Try to load an existing conversation if possible
        if (!$this->load() && $this->command !== null) {
            //A new conversation start
            $this->start();
        }
    }

    /**
     * Clear all conversation variables.
     *
     * @return bool Always return true, to allow this method in an if statement.
     */
    protected function clear(): bool
    {
        $this->conversation = null;
        $this->protected_notes = null;
        $this->notes = null;

        return true;
    }

    /**
     * Load the conversation from the database
     *
     * @return bool
     */
    protected function load(): bool
    {
        //Select an active conversation
        $conversation = Storage::conversationSelect($this->user_id, $this->chat_id);

        if ($conversation !== null) {
            //Pick only the first element
            $this->conversation = $conversation;

            //Load the command from the conversation if it hasn't been passed
            $this->command = $this->command ?: $this->conversation->command;

            if ($this->command !== $this->conversation->command) {
                $this->cancel();
                return false;
            }

            //Load the conversation notes
            $this->protected_notes = Json::decode($this->conversation['notes']);
            $this->notes = $this->protected_notes;
        }

        return $this->exists();
    }

    /**
     * Check if the conversation already exists
     *
     * @return bool
     */
    public function exists(): bool
    {
        return ($this->conversation !== null);
    }

    /**
     * Start a new conversation if the current command doesn't have one yet
     *
     * @return bool
     *
     * @throws BaseException
     */
    protected function start(): bool
    {
        if ($this->command && !$this->exists()) {
            if (Storage::conversationInsert($this->user_id, $this->chat_id, $this->command)) {
                return $this->load();
            }
        }

        return false;
    }

    /**
     * Delete the current conversation
     *
     * Currently, the Conversation is not deleted but just set to 'stopped'
     *
     * @return bool
     */
    public function stop(): bool
    {
        return ($this->updateStatus('stopped') && $this->clear());
    }

    /**
     * Cancel the current conversation
     *
     * @return bool
     */
    public function cancel(): bool
    {
        return ($this->updateStatus('cancelled') && $this->clear());
    }

    /**
     * Update the status of the current conversation
     *
     * @param string $status
     *
     * @return bool
     */
    protected function updateStatus(string $status): bool
    {
        if ($this->exists()) {
            $this->conversation->status = $status;

            if ($this->conversation->save()) {
                return true;
            } else {
                \Yii::warning(['Update conversation error', $this->conversation->errors], 'telegram');
            }
        }

        return false;
    }

    /**
     * Store the array/variable in the database with Json::encode() function
     *
     * @return bool
     */
    public function update(): bool
    {
        if ($this->exists()) {
            $this->conversation->notes = Json::encode($this->notes, JSON_UNESCAPED_UNICODE);
            if ($this->conversation->save()) {
                return true;
            } else {
                \Yii::warning(['Update conversation error', $this->conversation->errors], 'telegram');
            }
        }

        return false;
    }
    
    public function getUserId(): int
    {
        return $this->user_id;
    }
    
    public function getChatId(): int
    {
        return $this->chat_id;
    }

    public function getCommandText(): ?string
    {
        return $this->command;
    }
}
