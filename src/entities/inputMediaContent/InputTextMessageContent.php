<?php
namespace onix\telegram\entities\inputMediaContent;

use onix\telegram\entities\Entity;
use onix\telegram\entities\LinkPreviewOptions;

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
 * @property-read string $messageText Text of the message to be sent, 1-4096 characters.
 * @property-read string $parseMode Optional. Send Markdown or HTML, if you want Telegram apps to show bold,
 * italic, fixed-width text or inline URLs in your bot's message.
 *
 * @property-read bool $disableWebPagePreview Depricated. Optional. Disables link previews for links in the sent message
 * @property-read LinkPreviewOptions $linkPreviewOptions Optional. Link preview generation options for the message
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

    /**
     * @inheritDoc
     */
    protected function subEntities(): array
    {
        return [
            'linkPreviewOptions' => LinkPreviewOptions::class
        ];
    }
}
