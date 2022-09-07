<?php
namespace onix\telegram\entities;

use onix\telegram\entities\games\CallbackGame;
use onix\telegram\exceptions\TelegramException;
use Yii;

/**
 * Class InlineKeyboardButton
 *
 * @link https://core.telegram.org/bots/api#inlinekeyboardbutton
 *
 * @property string $text Label text on the button
 * @property string $url Optional. HTTP url to be opened when button is pressed
 * @property LoginUrl $loginUrl Optional. An HTTP URL used to automatically authorize the user. Can be used as
 * a replacement for the Telegram Login Widget.
 *
 * @property string $callbackData Optional. Data to be sent in a callback query to the bot
 * when button is pressed, 1-64 bytes
 *
 * @property string $switchInlineQuery Optional. If set, pressing the button will prompt the user to select
 * one of their chats, open that chat and insert the bot's username and the specified inline query in the input field.
 * Can be empty, in which case just the bot’s username will be inserted.
 *
 * @property string $switchInlineQueryCurrentChat() Optional. If set, pressing the button will insert the bot‘s username
 * and the specified inline query in the current chat's input field. Can be empty, in which case only the bot’s username
 * will be inserted.
 *
 * @property CallbackGame $callbackGame() Optional. Description of the game that will be launched when
 * the user presses the button.
 *
 * @property bool $pay Optional. Specify True, to send a Pay button.
 */
class InlineKeyboardButton extends KeyboardButton
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return [
            'text',
            'url',
            'loginUrl',
            'callbackData',
            'switchInlineQuery',
            'switchInlineQueryCurrentChat',
            'callbackGame',
            'pay'
        ];
    }

    /**
     * @inheritDoc
     *
     * @throws TelegramException
     */
    public function init()
    {
        parent::init();

        if (!$this->validate()) {
            $errors = $this->getFirstErrors();
            throw new TelegramException(print_r(array_shift($errors), true));
        }
    }

    /**
     * Check if the passed data array could be an InlineKeyboardButton.
     *
     * @param array $data
     *
     * @return bool
     */
    public static function couldBe($data)
    {
        return is_array($data) &&
               array_key_exists('text', $data) && (
                   array_key_exists('url', $data) ||
                   array_key_exists('login_url', $data) ||
                   array_key_exists('callback_data', $data) ||
                   array_key_exists('switch_inline_query', $data) ||
                   array_key_exists('switch_inline_query_current_chat', $data) ||
                   array_key_exists('callback_game', $data) ||
                   array_key_exists('pay', $data)
               );
    }

    private static $uniqueParams = [
        'url' => false,
        'loginUrl' => false,
        'callbackData' => false,
        'callbackGame' => false,
        'pay' => false,
        'switchInlineQuery' => true,
        'switchInlineQueryCurrentChat' => true
    ];

    public function rules()
    {
        return [
            [['text'], 'required'],
            [array_keys(self::$uniqueParams), 'validateParams', 'skipOnEmpty' => false]
        ];
    }

    /**
     * @param string $attribute
     */
    public function validateParams($attribute)
    {
        $num_params = 0;

        foreach (self::$uniqueParams as $param => $allowEmpty) {
            $allow = $allowEmpty ? isset($this->$param) : !empty($this->$param);
            if ($allow) {
                $num_params++;
            }
        }

        if ($num_params !== 1) {
            $errText = Yii::t(
                'telegram',
                'You must use only one of these fields: url, login_url, callback_data, switch_inline_query,' .
                ' switch_inline_query_current_chat, callback_game, pay'
            );

            $this->addError($attribute, $errText);
        }
    }

    public function __set($name, $value)
    {
        $paramName = $this->toCamelCase($name);
        // Only 1 of these can be set, so clear the others when setting a new one.
        if (in_array($paramName, array_keys(self::$uniqueParams), true)) {
            foreach (self::$uniqueParams as $param => $allowEmpty) {
                unset($this->$param);
            }
        }

        parent::__set($name, $value);
    }
}
