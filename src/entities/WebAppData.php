<?php

namespace onix\telegram\entities;

/**
 * Class WebAppData
 * Describes data sent from a Web App to the bot.
 *
 * @link https://core.telegram.org/bots/api#webappdata
 *
 * @property-read string $data The data. Be aware that a bad client can send arbitrary data in this field.
 * @property-read string $buttonText Text of the web_app keyboard button from which the Web App was opened.
 * Be aware that a bad client can send arbitrary data in this field.
 *
 */
class WebAppData extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return [
            'data',
            'buttonText'
        ];
    }
}