<?php
namespace onix\telegram;

use onix\telegram\models\Conversation as ConversationRepo;
use Yii;
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
    protected $conversation;

    /**
     * Notes stored inside the conversation
     *
     * @var mixed
     */
    protected $protected_notes;

    /**
     * Notes to be stored
     *
     * @var mixed
     */
    public $notes;

    /**
     * Telegram user id
     *
     * @var int
     */
    public $user_id;

    /**
     * Telegram chat id
     *
     * @var int
     */
    public $chat_id;

    /**
     * Command to be executed if the conversation is active
     *
     * @var string
     */
    public $command;

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
    public function init()
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
    protected function clear()
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
     *
     * @throws BaseException
     */
    protected function load()
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
            $this->protected_notes = Json::decode($this->conversation['notes'], true);
            $this->notes = $this->protected_notes;
        }

        return $this->exists();
    }

    /**
     * Check if the conversation already exists
     *
     * @return bool
     */
    public function exists()
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
    protected function start()
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
     * Currently the Conversation is not deleted but just set to 'stopped'
     *
     * @return bool
     *
     * @throws BaseException
     */
    public function stop()
    {
        return ($this->updateStatus('stopped') && $this->clear());
    }

    /**
     * Cancel the current conversation
     *
     * @return bool
     *
     * @throws BaseException
     */
    public function cancel()
    {
        return ($this->updateStatus('cancelled') && $this->clear());
    }

    /**
     * Update the status of the current conversation
     *
     * @param string $status
     *
     * @return bool
     *
     * @throws BaseException
     */
    protected function updateStatus($status)
    {
        if ($this->exists()) {
            $this->conversation->status = $status;

            if ($this->conversation->save()) {
                return true;
            } else {
                Yii::warning(['Update conversation error', $this->conversation->errors], 'telegram');
            }
        }

        return false;
    }

    /**
     * Store the array/variable in the database with Json::encode() function
     *
     * @return bool
     *
     * @throws BaseException
     */
    public function update()
    {
        if ($this->exists()) {
            $this->conversation->notes = Json::encode($this->notes, JSON_UNESCAPED_UNICODE);
            if ($this->conversation->save()) {
                return true;
            } else {
                Yii::warning(['Update conversation error', $this->conversation->errors], 'telegram');
            }
        }

        return false;
    }
    
    public function getUserId()
    {
        return $this->user_id;
    }
    
    public function getChatId()
    {
        return $this->chat_id;
    }

    public function getCommandText()
    {
        return $this->command;
    }
}
