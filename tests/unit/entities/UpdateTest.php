<?php /** @noinspection PhpIllegalPsrClassPathInspection */

namespace onix\telegram\tests\unit\entities;

use Codeception\Test\Unit;
use onix\telegram\entities\Update;
use yii\helpers\Json;

class UpdateTest extends Unit
{
    public function testUpdateCast()
    {
        $json = '{
            "update_id":137809336,
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

        $struct = Json::decode($json, true);
        $update = new Update($struct);

        $array_string_after = Json::decode($update->toJson(), true);
        self::assertEquals($struct, $array_string_after);
    }
}
