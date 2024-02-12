<?php
namespace onix\telegram\entities\inputMediaContent;

use onix\telegram\entities\Entity;

/**
 * Class InputTextMessageContent
 *
 * @link https://core.telegram.org/bots/api#inputtextmessagecontent
 *
 * <code>
 * $data = [
 *   'message_text' => '',
 *   'parse_mode' => '',
 *   'disable_web_page_preview' => true,
 * ];
 * </code>
 *
 * @property string $messageText Text of the message to be sent, 1-4096 characters.
 * @property string $parseMode Optional. Send Markdown or HTML, if you want Telegram apps to show bold,
 * italic, fixed-width text or inline URLs in your bot's message.
 *
 * @property bool $disableWebPagePreview Optional. Disables link previews for links in the sent message
 */
class InputTextMessageContent extends Entity implements InputMessageContent
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ['messageText', 'parseMode', 'disableWebPagePreview'];
    }
}
