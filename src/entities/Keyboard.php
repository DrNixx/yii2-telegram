<?php
namespace onix\telegram\entities;

use onix\telegram\exceptions\TelegramException;
use Yii;

/**
 * Class Keyboard
 *
 * @link https://core.telegram.org/bots/api#replykeyboardmarkup
 *
 * @property bool $resizeKeyboard Optional. Requests clients to resize the keyboard vertically for optimal fit
 * (e.g., make the keyboard smaller if there are just two rows of buttons). Defaults to false, in which case
 * the custom keyboard is always of the same height as the app's standard keyboard.
 *
 * @property bool $oneTimeKeyboard Optional. Requests clients to remove the keyboard as soon as it's been used.
 * The keyboard will still be available, but clients will automatically display the usual letter-keyboard
 * in the chat – the user can press a special button in the input field to see the custom keyboard again.
 * Defaults to false.
 *
 * @property bool $selective Optional. Use this parameter if you want to show the keyboard to specific users only.
 * Targets:
 * 1) users that are @mentioned in the text of the Message object;
 * 2) if the bot's message is a reply (has reply_to_message_id), sender of the original message.
 *
 * @property string $keyboardType The type of keyboard, either "inline_keyboard" or "keyboard".
 * @property array $keyboard Array of button rows, each represented by an Array of KeyboardButton objects
 * of InlineKeyboardButton objects
 *
 * @property-read bool $removeKeyboard Requests clients to remove the custom keyboard (user will not be able to
 * summon this keyboard; if you want to hide the keyboard from sight but keep it accessible,
 * use one_time_keyboard in ReplyKeyboardMarkup
 *
 * @property-read bool $forceReply Shows reply interface to the user, as if they manually selected the bot's
 * message and tapped 'Reply'
 */
class Keyboard extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return ['resizeKeyboard', 'oneTimeKeyboard', 'selective', 'removeKeyboard', 'forceReply', 'keyboard'];
    }
    
    /**
     * @inheritDoc
     */
    public function __construct($config = [])
    {
        $config = call_user_func_array([$this, 'createFromParams'], func_get_args());
        parent::__construct($config);
    }

    /**
     * @inheritDoc
     *
     * @throws TelegramException
     */
    public function init()
    {
        parent::init();

        // Remove any empty buttons.
        $type = $this->getAttribute($this->keyboardType);
        if (is_array($type)) {
            $this->setAttribute($this->keyboardType, array_filter($type));
        }

        if (!$this->validate()) {
            $errors = $this->getFirstErrors();
            throw new TelegramException(print_r(array_shift($errors), true));
        }
    }

    public function rules()
    {
        return [
            [['resizeKeyboard', 'oneTimeKeyboard', 'selective'], 'boolean'],
            [['keyboard'], 'validateKeyboard', 'skipOnEmpty' => false]
        ];
    }

    /**
     * @param string $attribute
     */
    public function validateKeyboard($attribute)
    {
        $keyboard_type = $this->keyboardType;
        $keyboard = $this->getAttribute($keyboard_type);

        if ($keyboard !== null) {
            if (!is_array($keyboard)) {
                $this->addError(
                    $attribute,
                    Yii::t(
                        'telegram',
                        '{type} field is not an array',
                        ['type' => $keyboard_type]
                    )
                );

                return;
            }

            foreach ($keyboard as $item) {
                if (!is_array($item)) {
                    $this->addError(
                        $attribute,
                        Yii::t(
                            'telegram',
                            '{type} subfield is not an array',
                            ['type' => $keyboard_type]
                        )
                    );

                    return;
                }
            }
        }
    }

    /**
     * If this keyboard is an inline keyboard.
     *
     * @return bool
     */
    public function isInlineKeyboard()
    {
        return $this instanceof InlineKeyboard;
    }

    /**
     * Get the proper keyboard button class for this keyboard.
     *
     * @return string
     */
    public function getKeyboardButtonClass()
    {
        return $this->isInlineKeyboard() ? InlineKeyboardButton::class : KeyboardButton::class;
    }

    /**
     * Get the type of keyboard, either "inline_keyboard" or "keyboard".
     *
     * @return string
     */
    public function getKeyboardType()
    {
        return $this->isInlineKeyboard() ? 'inlineKeyboard' : 'keyboard';
    }

    /**
     * If no explicit keyboard is passed, try to create one from the parameters.
     *
     * @return array
     */
    protected function createFromParams()
    {
        $keyboard_type = $this->keyboardType;

        $args = func_get_args();

        // Force button parameters into individual rows.
        foreach ($args as &$arg) {
            !is_array($arg) && $arg = [$arg];
        }
        unset($arg);

        $data = reset($args);

        if ($from_data = array_key_exists($keyboard_type, (array) $data)) {
            $args = $data[$keyboard_type];

            // Make sure we're working with a proper row.
            if (!is_array($args)) {
                $args = [];
            }
        }

        $new_keyboard = [];
        foreach ($args as $row) {
            $new_keyboard[] = $this->parseRow($row);
        }

        if (!empty($new_keyboard)) {
            if (!$from_data) {
                $data = [];
            }
            $data[$keyboard_type] = $new_keyboard;
        }

        return $data;
    }

    /**
     * Create a new row in keyboard and add buttons.
     *
     * @return $this
     */
    public function addRow()
    {
        if (($new_row = $this->parseRow(func_get_args())) !== null) {
            $rows = $this->getAttribute($this->keyboardType);
            if ($rows !== null) {
                if (!is_array($rows)) {
                    $rows = [$rows];
                }
            } else {
                $rows = [];
            }

            $rows[] = $new_row;
            $this->setAttribute($this->keyboardType, $rows);
        }

        return $this;
    }

    /**
     * Parse a given row to the correct array format.
     *
     * @param array $row
     *
     * @return array
     */
    protected function parseRow($row)
    {
        if (!is_array($row)) {
            return null;
        }

        $new_row = [];
        foreach ($row as $button) {
            if (($new_button = $this->parseButton($button)) !== null) {
                $new_row[] = $new_button;
            }
        }

        return $new_row;
    }

    /**
     * Parse a given button to the correct KeyboardButton object type.
     *
     * @param array|string|KeyboardButton $button
     *
     * @return KeyboardButton|null
     */
    protected function parseButton($button)
    {
        $button_class = $this->getKeyboardButtonClass();

        if ($button instanceof $button_class) {
            return $button;
        }

        if (!$this->isInlineKeyboard() || call_user_func([$button_class, 'couldBe'], $button)) {
            return new $button_class($button);
        }

        return null;
    }

    /**
     * Remove the current custom keyboard and display the default letter-keyboard.
     *
     * @link https://core.telegram.org/bots/api/#replykeyboardremove
     *
     * @param array $data
     *
     * @return Keyboard
     */
    public static function remove(array $data = [])
    {
        return new static(array_merge(['keyboard' => [], 'remove_keyboard' => true, 'selective' => false], $data));
    }

    /**
     * Display a reply interface to the user (act as if the user has selected the bot's message and tapped 'Reply').
     *
     * @link https://core.telegram.org/bots/api#forcereply
     *
     * @param array $data
     *
     * @return Keyboard
     */
    public static function forceReply(array $data = [])
    {
        return new static(array_merge(['keyboard' => [], 'force_reply' => true, 'selective' => false], $data));
    }
}
