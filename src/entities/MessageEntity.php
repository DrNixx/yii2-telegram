<?php
namespace onix\telegram\entities;

/**
 * Class MessageEntity
 *
 * @link https://core.telegram.org/bots/api#messageentity
 *
 * @property-read string $type Type of the entity. Currently, can be “mention” (@username), “hashtag” (#hashtag),
 * “cashtag” ($USD), “bot_command” (/start@jobs_bot), “url” (https://telegram.org),
 * “email” (do-not-reply@telegram.org), “phone_number” (+1-212-555-0123), “bold” (bold text),
 * “italic” (italic text), “underline” (underlined text), “strikethrough” (strikethrough text),
 * “spoiler” (spoiler message), “code” (monowidth string), “pre” (monowidth block),
 * “text_link” (for clickable text URLs), “text_mention” (for users without usernames),
 * “custom_emoji” (for inline custom emoji stickers)
 *
 * @property-read int $offset Offset in UTF-16 code units to the start of the entity
 * @property-read int $length Length of the entity in UTF-16 code units
 * @property-read string $url Optional. For "text_link" only, url that will be opened after user taps on the text
 * @property-read User $user Optional. For "text_mention" only, the mentioned user
 * @property-read string $language Optional. For "pre" only, the programming language of the entity text
 */
class MessageEntity extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return ['type', 'offset', 'length', 'url', 'user', 'language'];
    }
    
    /**
     * @inheritDoc
     */
    protected function subEntities()
    {
        return [
            'user' => User::class,
        ];
    }
}
