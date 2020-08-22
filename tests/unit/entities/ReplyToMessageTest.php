<?php
namespace tests\unit\entities;

use onix\telegram\entities\ReplyToMessage;
use onix\telegram\entities\Update;
use yii\helpers\Json;

class ReplyToMessageTest extends \Codeception\Test\Unit
{
    public function testChatType()
    {
        $json = '{
            "update_id":137809335,
            "message":{
                "message_id":4479,
                "from":{"id":123,"first_name":"John","username":"MJohn"},
                "chat":{"id":-123,"title":"MyChat","type":"group"},
                "date":1449092987,
                "reply_to_message":{
                    "message_id":11,
                    "from":{"id":121,"first_name":"Myname","username":"mybot"},
                    "chat":{"id":-123,"title":"MyChat","type":"group"},
                    "date":1449092984,
                    "text":"type some text"
                },
                "text":"some text"
            }
        }';

        $update           = new Update(Json::decode($json, true));
        $reply_to_message = $update->message->replyToMessage;

        self::assertNull($reply_to_message->replyToMessage);
    }
}
