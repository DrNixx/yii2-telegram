<?php
namespace onix\telegram\entities\inlineQuery;

use yii\helpers\ArrayHelper;

/**
 * Class InlineQueryResultContact
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultcontact
 *
 * <code>
 * $data = [
 *   'id' => '',
 *   'phone_number' => '',
 *   'first_name' => '',
 *   'last_name' => '',
 *   'reply_markup' => <InlineKeyboard>,
 *   'input_message_content' => <InputMessageContent>,
 *   'thumb_url' => '',
 *   'thumb_width' => 30,
 *   'thumb_height' => 30,
 * ];
 * </code>
 *
 * @property string $phoneNumber Contact's phone number
 * @property string $firstName Contact's first name
 * @property string $lastName Optional. Contact's last name
 * @property string $vcard() Optional. Additional data about the contact in the form of a vCard, 0-2048 bytes
 * @property string $thumbUrl Optional. Url of the thumbnail for the result
 * @property int $thumbWidth Optional. Thumbnail width
 * @property int $thumbHeight Optional. Thumbnail height
 */
class InlineQueryResultContact extends InlineEntity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ArrayHelper::merge(
            parent::attributes(),
            ['phoneNumber', 'firstName', 'lastName', 'vcard', 'thumbUrl', 'thumbWidth', 'thumbHeight']
        );
    }
    
    /**
     * InlineQueryResultContact constructor
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config['type'] = 'contact';
        parent::__construct($config);
    }
}
