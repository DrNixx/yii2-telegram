<?php
namespace onix\telegram\entities\inputMedia;

/**
 * Class InputMediaPhoto
 *
 * @link https://core.telegram.org/bots/api#inputmediaphoto
 *
 * <code>
 * $data = [
 *   'media' => '123abc',
 *   'caption' => '*Photo* caption',
 *   'parse_mode' => 'markdown',
 * ];
 * </code>
 */
class InputMediaPhoto extends InputEntity
{
    /**
     * InputMediaPhoto constructor
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config['type'] = 'photo';
        parent::__construct($config);
    }
}
