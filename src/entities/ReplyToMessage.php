<?php
namespace onix\telegram\entities;

/**
 * Class ReplyToMessage
 *
 * @todo Is this even required?!
 */
class ReplyToMessage extends Message
{
    /**
     * ReplyToMessage constructor.
     *
     * @param array  $config
     */
    public function __construct(array $config)
    {
        //As explained in the documentation
        //Reply to message can't contain other reply to message entities
        unset($config['reply_to_message']);

        parent::__construct($config);
    }
}
