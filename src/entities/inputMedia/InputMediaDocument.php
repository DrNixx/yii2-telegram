<?php
namespace onix\telegram\entities\inputMedia;

/**
 * Class InputMediaDocument
 *
 * @link https://core.telegram.org/bots/api#inputmediadocument
 *
 * <code>
 * $data = [
 *   'media' => '123abc',
 *   'thumb' => '456def',
 *   'caption' => '*Document* caption',
 *   'parse_mode' => 'markdown',
 * ];
 * </code>
 */
class InputMediaDocument extends InputEntity
{
    /**
     * InputMediaDocument constructor
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config['type'] = 'document';
        parent::__construct($config);
    }
}
