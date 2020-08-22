<?php
namespace onix\telegram\entities;

use onix\telegram\entities\games\GameHighScore;

/**
 * Class ServerResponse
 *
 * @link https://core.telegram.org/bots/api#making-requests
 *
 * @property-read bool $ok If the request was successful
 * @property-read mixed $result The result of the query
 * @property-read int $errorCode Error code of the unsuccessful request
 * @property-read string $description Human-readable description of the result / unsuccessful request
 */
class ServerResponse extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return ['ok', 'result', 'errorCode', 'description'];
    }
    
    /**
     * ServerResponse constructor.
     *
     * @param array  $config
     */
    public function __construct(array $config)
    {
        $is_ok  = isset($config['ok']) ? (bool) $config['ok'] : false;
        $result = isset($config['result']) ? $config['result'] : null;

        if ($is_ok && is_array($result)) {
            if ($this->isAssoc($result)) {
                $config['result'] = $this->createResultObject($result);
            } else {
                $config['result'] = $this->createResultObjects($result);
            }
        }

        parent::__construct($config);
    }

    /**
     * Check if array is associative
     *
     * @link https://stackoverflow.com/a/4254008
     *
     * @param array $array
     *
     * @return bool
     */
    protected function isAssoc(array $array)
    {
        return count(array_filter(array_keys($array), 'is_string')) > 0;
    }

    /**
     * If response is ok
     *
     * @return bool
     */
    public function isOk()
    {
        return (bool) $this->ok;
    }

    /**
     * Print error
     *
     * @see https://secure.php.net/manual/en/function.print-r.php
     *
     * @param bool $return
     *
     * @return bool|string
     */
    public function printError($return = false)
    {
        $error = sprintf('Error N: %s, Description: %s', $this->errorCode, $this->description);

        if ($return) {
            return $error;
        }

        echo $error;

        return true;
    }

    /**
     * Create and return the object of the received result
     *
     * @param array  $result
     *
     * @return Chat|ChatMember|File|Message|User|UserProfilePhotos|WebhookInfo
     */
    private function createResultObject(array $result)
    {
        $action = $this->telegram->request->getCurrentAction();

        $result_object_types = [
            'getChat' => Chat::class,
            'getChatMember' => ChatMember::class,
            'getFile' => File::class,
            'getMe' => User::class,
            'getStickerSet' => StickerSet::class,
            'getUserProfilePhotos' => UserProfilePhotos::class,
            'getWebhookInfo' => WebhookInfo::class,
        ];

        $object_class = array_key_exists($action, $result_object_types) ?
            $result_object_types[$action] :
            Message::class;

        return new $object_class($result);
    }

    /**
     * Create and return the objects array of the received result
     *
     * @param array  $result
     *
     * @return BotCommand[]|ChatMember[]|GameHighScore[]|Message[]|Update[]
     */
    private function createResultObjects(array $result)
    {
        $results = [];
        $action  = $this->telegram->request->getCurrentAction();

        $result_object_types = [
            'getMyCommands' => BotCommand::class,
            'getChatAdministrators' => ChatMember::class,
            'getGameHighScores' => GameHighScore::class,
            'sendMediaGroup' => Message::class,
        ];

        $object_class = array_key_exists($action, $result_object_types) ? $result_object_types[$action] : Update::class;

        foreach ($result as $data) {
            // We don't need to save the raw_data of the response object!
            $results[] = new $object_class($data);
        }

        return $results;
    }
}
