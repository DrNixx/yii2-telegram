<?php
namespace onix\telegram\entities;

use Yii;

/**
 * Class KeyboardButton
 *
 * This object represents one button of the reply keyboard. For simple text buttons String can be used
 * instead of this object to specify text of the button. Optional fields request_contact, request_location,
 * and request_poll are mutually exclusive.
 *
 * @link https://core.telegram.org/bots/api#keyboardbutton
 *
 * @property string $text Text of the button. If none of the optional fields are used, it will be sent to the bot as
 * a message when the button is pressed
 *
 * @property bool $requestContact Optional. If True, the user's phone number will be sent as a contact when
 * the button is pressed. Available in private chats only
 *
 * @property bool $requestLocation Optional. If True, the user's current location will be sent when
 * the button is pressed. Available in private chats only
 *
 * @property KeyboardButtonPollType $requestPoll Optional. If specified, the user will be asked to create
 * a poll and send it to the bot when the button is pressed. Available in private chats only
 */
class KeyboardButton extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ['text', 'requestContact', 'requestLocation', 'requestPoll'];
    }

    /**
     * KeyboardButton constructor.
     * @param array|string $config
     */
    public function __construct($config)
    {
        if (is_string($config)) {
            $config = ['text' => $config];
        }

        parent::__construct($config);
    }

    /**
     * Check if the passed data array could be a KeyboardButton.
     *
     * @param array $data
     *
     * @return bool
     */
    public static function couldBe($data)
    {
        return is_array($data) && array_key_exists('text', $data);
    }

    private static $uniqueParams = [
        'requestContact',
        'requestLocation',
        'requestPoll',
    ];

    public function rules()
    {
        return [
            [['text'], 'required'],
            [self::$uniqueParams, 'validateRequest']
        ];
    }

    /**
     * @param string $attribute
     */
    public function validateRequest($attribute)
    {
        // Make sure only 1 of the optional request fields is set.
        $field_count = array_filter([
            $this->requestContact,
            $this->requestLocation,
            $this->requestPoll,
        ]);

        if (count($field_count) > 1) {
            $errText = Yii::t(
                'telegram',
                'You must use only one of these fields: request_contact, request_location, request_poll'
            );

            $this->addError($attribute, $errText);
        }
    }

    public function __set($name, $value)
    {
        $paramName = $this->toCamelCase($name);
        // Only 1 of these can be set, so clear the others when setting a new one.
        if (in_array($paramName, self::$uniqueParams, true)) {
            unset($this->requestContact, $this->requestLocation, $this->requestPoll);
        }

        parent::__set($name, $value);
    }
}
