<?php
namespace tests\unit\entities;

use onix\telegram\entities\Entity;

class EntityTest extends \Codeception\Test\Unit
{
    public function testEscapeMarkdown()
    {
        // Make sure all characters that need escaping are escaped.

        // Markdown V1
        self::assertEquals('\[\`\*\_', Entity::escapeMarkdown('[`*_'));
        self::assertEquals('\*mark\*\_down\_~test~', Entity::escapeMarkdown('*mark*_down_~test~'));

        // Markdown V2
        self::assertEquals('\_\*\[\]\(\)\~\`\>\#\+\-\=\|\{\}\.\!', Entity::escapeMarkdownV2('_*[]()~`>#+-=|{}.!'));
        self::assertEquals('\*mark\*\_down\_\~test\~', Entity::escapeMarkdownV2('*mark*_down_~test~'));
    }
}
