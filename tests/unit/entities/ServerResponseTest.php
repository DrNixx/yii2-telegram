<?php
namespace tests\unit\entities;

use Codeception\Test\Unit;
use onix\telegram\entities\File;
use onix\telegram\entities\Message;
use onix\telegram\entities\PhotoSize;
use onix\telegram\entities\ServerResponse;
use onix\telegram\entities\Sticker;
use onix\telegram\entities\StickerSet;
use onix\telegram\entities\Update;
use onix\telegram\entities\UserProfilePhotos;
use onix\telegram\Request;
use onix\telegram\Telegram;
use Yii;
use yii\helpers\Json;

class ServerResponseTest extends Unit
{
    private function setRequestAction($action)
    {
        /** @var Telegram $telegram */
        /** @noinspection PhpUnhandledExceptionInspection */
        $telegram = Yii::$app->get('telegram');
        $request = $telegram->request;
        $changePropertyClosure = function () use ($action) {
            /** @noinspection PhpUndefinedFieldInspection */
            $this->current_action = $action;
        };

        $doChangePropertyClosure = $changePropertyClosure->bindTo($request, get_class($request));
        $doChangePropertyClosure();
    }

    public function sendMessageOk()
    {
        return '{
            "ok":true,
            "result":{
                "message_id":1234,
                "from":{"id":123456789,"first_name":"botname","username":"namebot"},
                "chat":{"id":123456789,"first_name":"john","username":"Mjohn"},
                "date":1441378360,
                "text":"hello"
            }
        }';
    }

    public function testSendMessageOk()
    {
        $result = $this->sendMessageOk();
        $server = new ServerResponse(Json::decode($result, true));
        $server_result = $server->result;

        self::assertTrue($server->isOk());
        self::assertNull($server->errorCode);
        self::assertNull($server->description);
        self::assertInstanceOf(Message::class, $server_result);

        //Message
        /** @var Message $server_result */
        self::assertEquals('1234', $server_result->messageId);
        self::assertEquals('123456789', $server_result->from->id);
        self::assertEquals('botname', $server_result->from->firstName);
        self::assertEquals('namebot', $server_result->from->username);
        self::assertEquals('123456789', $server_result->chat->id);
        self::assertEquals('john', $server_result->chat->firstName);
        self::assertEquals('Mjohn', $server_result->chat->username);
        self::assertEquals('1441378360', $server_result->date);
        self::assertEquals('hello', $server_result->text);

        //... they are not finished...
    }

    public function sendMessageFail()
    {
        return '{
            "ok":false,
            "error_code":400,
            "description":"Error: Bad Request: wrong chat id"
        }';
    }

    public function testSendMessageFail()
    {
        $result = $this->sendMessageFail();
        $server = new ServerResponse(Json::decode($result, true));

        self::assertFalse($server->isOk());
        self::assertNull($server->result);
        self::assertEquals('400', $server->errorCode);
        self::assertEquals('Error: Bad Request: wrong chat id', $server->description);
    }

    public function setWebhookOk()
    {
        return '{"ok":true,"result":true,"description":"Webhook was set"}';
    }

    public function testSetWebhookOk()
    {
        $result = $this->setWebhookOk();
        $server = new ServerResponse(Json::decode($result, true));

        self::assertTrue($server->isOk());
        self::assertTrue($server->result);
        self::assertNull($server->errorCode);
        self::assertEquals('Webhook was set', $server->description);
    }

    public function setWebhookFail()
    {
        return '{
            "ok":false,
            "error_code":400,
            "description":"Error: Bad request: htttps:\/\/domain.host.org\/dir\/hook.php"
        }';
    }

    public function testSetWebhookFail()
    {
        $result = $this->setWebhookFail();
        $server = new ServerResponse(Json::decode($result, true));

        self::assertFalse($server->isOk());
        self::assertNull($server->result);
        self::assertEquals(400, $server->errorCode);
        self::assertEquals('Error: Bad request: htttps://domain.host.org/dir/hook.php', $server->description);
    }

    public function getUpdatesArray()
    {
        return '{
            "ok":true,
            "result":[
                {
                    "update_id":123,
                    "message":{
                        "message_id":90,
                        "from":{"id":123456789,"first_name":"John","username":"Mjohn"},
                        "chat":{"id":123456789,"first_name":"John","username":"Mjohn"},
                        "date":1441569067,
                        "text":"\/start"
                    }
                },
                {
                    "update_id":124,
                    "message":{
                        "message_id":91,
                        "from":{"id":123456788,"first_name":"Patrizia","username":"Patry"},
                        "chat":{"id":123456788,"first_name":"Patrizia","username":"Patry"},
                        "date":1441569073,
                        "text":"Hello!"
                    }
                },
                {
                    "update_id":125,
                    "message":{
                        "message_id":92,
                        "from":{"id":123456789,"first_name":"John","username":"MJohn"},
                        "chat":{"id":123456789,"first_name":"John","username":"MJohn"},
                        "date":1441569094,
                        "text":"\/echo hello!"
                    }
                },
                {
                    "update_id":126,
                    "message":{
                        "message_id":93,
                        "from":{"id":123456788,"first_name":"Patrizia","username":"Patry"},
                        "chat":{"id":123456788,"first_name":"Patrizia","username":"Patry"},
                        "date":1441569112,
                        "text":"\/echo the best"
                    }
                }
            ]
        }';
    }

    public function testGetUpdatesArray()
    {
        $result = $this->getUpdatesArray();
        $server = new ServerResponse(Json::decode($result, true));

        self::assertCount(4, $server->result);
        self::assertInstanceOf(Update::class, $server->result[0]);
    }

    public function getUpdatesEmpty()
    {
        return '{"ok":true,"result":[]}';
    }

    public function testGetUpdatesEmpty()
    {
        $result = $this->getUpdatesEmpty();
        $server = new ServerResponse(Json::decode($result, true));

        self::assertEmpty($server->result);
    }

    public function getUserProfilePhotos()
    {
        $this->setRequestAction('getUserProfilePhotos');

        return <<<'JSON'
{
    "ok":true,
    "result":{
        "total_count":3,
        "photos":[
            [
                {"file_id":"AgADBG6_vmQaVf3qOGVurBRzHqgg5uEju-8IBAAEC","file_size":7402,"width":160,"height":160},
                {"file_id":"AgADBG6_vmQaVf3qOGVurBRzHWMuphij6_MIBAAEC","file_size":15882,"width":320,"height":320},
                {"file_id":"AgADBG6_vmQaVf3qOGVurBRzHNWdpQ9jz_cIBAAEC","file_size":46680,"width":640,"height":640}
            ],
            [
                {"file_id":"AgADBAADr6cxG6_vmH-bksDdiYzAABO8UCGz_JLAAgI","file_size":7324,"width":160,"height":160},
                {"file_id":"AgADBAADr6cxG6_vmH-bksDdiYzAABAlhB5Q_K0AAgI","file_size":15816,"width":320,"height":320},
                {"file_id":"AgADBAADr6cxG6_vmH-bksDdiYzAABIIxOSHyayAAgI","file_size":46620,"width":640,"height":640}
            ],
            [
                {"file_id":"AgABxG6_vmQaL2X0CUTAABMhd1n2RLaRSj6cAAgI","file_size":2710,"width":160,"height":160},
                {"file_id":"AgADcxG6_vmQaL2X0EUTAABPXm1og0O7qwjKcAAgI","file_size":11660,"width":320,"height":320},
                {"file_id":"AgADxG6_vmQaL2X0CUTAABMOtcfUmoPrcjacAAgI","file_size":37150,"width":640,"height":640}
            ]
        ]
    }
}
JSON;
    }

    public function testGetUserProfilePhotos()
    {
        $result = $this->getUserProfilePhotos();
        $server = new ServerResponse(Json::decode($result, true));
        $server_result = $server->result;
        $photos = $server_result->photoSets;

        //Photo count
        self::assertEquals(3, $server_result->totalCount);
        self::assertCount(3, $photos);
        //Photo size count
        self::assertCount(3, $photos[0]);

        self::assertInstanceOf(UserProfilePhotos::class, $server_result);
        self::assertInstanceOf(PhotoSize::class, $photos[0][0]);
    }

    public function getFile()
    {
        $this->setRequestAction('getFile');

        return '{
            "ok":true,
            "result":{
                "file_id":"AgADBxG6_vmQaVf3qRzHYTAABD1hNWdpQ9qz_cIBAAEC",
                "file_size":46680,
                "file_path":"photo\/file_1.jpg"
            }
        }';
    }

    public function testGetFile()
    {
        $result = $this->getFile();
        $server = new ServerResponse(Json::decode($result, true));

        self::assertInstanceOf(File::class, $server->result);
    }

    public function testSetGeneralTestFakeResponse()
    {
        //setWebhook ok
        $fake_response = Request::generateGeneralFakeServerResponse();

        $server = new ServerResponse($fake_response);

        self::assertTrue($server->isOk());
        self::assertTrue($server->result);
        self::assertNull($server->errorCode);
        self::assertEquals('', $server->description);

        //sendMessage ok
        $fake_response = Request::generateGeneralFakeServerResponse(['chat_id' => 123456789, 'text' => 'hello']);

        $server = new ServerResponse($fake_response);

        /** @var Message $server_result */
        $server_result = $server->result;

        self::assertTrue($server->isOk());
        self::assertNull($server->errorCode);
        self::assertNull($server->description);
        self::assertInstanceOf(Message::class, $server_result);

        //Message
        self::assertEquals('1234', $server_result->messageId);
        self::assertEquals('1441378360', $server_result->date);
        self::assertEquals('hello', $server_result->text);

        //Message //User
        self::assertEquals('123456789', $server_result->from->id);
        self::assertEquals('botname', $server_result->from->firstName);
        self::assertEquals('namebot', $server_result->from->username);

        //Message //Chat
        self::assertEquals('123456789', $server_result->chat->id);
        self::assertEquals('', $server_result->chat->firstName);
        self::assertEquals('', $server_result->chat->username);

        //... they are not finished...
    }

    public function getStickerSet()
    {
        $this->setRequestAction('getStickerSet');

        return <<<'JSON'
{
    "ok":true,
    "result":{
        "name":"stickerset_name",
        "title":"Some name",
        "contains_masks":false,
        "stickers":[
            {
                "width":512,
                "height":324,
                "emoji":"\ud83d\ude33",
                "set_name":"stickerset_name",
                "thumb":{"file_id":"AAQEABOKTFsZAASfA4t3pp1_VlH1AAIC","file_size":3120,"width":128,"height":81},
                "file_id":"CAADBAADzAIAAph_7gOATSb9ehxv5QI",
                "file_size":14246
            },
            {
                "width":419,
                "height":512,
                "emoji":"\u2764",
                "set_name":"stickerset_name",
                "thumb":{"file_id":"AAQEABMj8qoZAASePUHuDSJ2uGIKAAIC","file_size":3500,"width":105,"height":128},
                "file_id":"CAADBAADzQIAAph_7gNPFguft4qtjAI",
                "file_size":17814
            },
            {
                "width":512,
                "height":276,
                "emoji":"\ud83d\ude36",
                "set_name":"stickerset_name",
                "thumb":{"file_id":"AAQEABMiaWcZAATNUEPkYkd0Fh2JBAABAg","file_size":2642,"file_path":"thumbnails\/file_8.jpg","width":128,"height":69},
                "file_id":"CAADBAADzwIAAph_7gOClxA3gK5wqAI",
                "file_size":12258
            },
            {
                "width":512,
                "height":327,
                "emoji":"\ud83d\udcbb",
                "set_name":"stickerset_name",
                "thumb":{"file_id":"AAQEABPC3d8ZAAQUJJnFB1VfII2RAAIC","file_size":3824,"file_path":"thumbnails\/file_10.jpg","width":128,"height":82},
                "file_id":"CAADBAAD0QIAAph_7gO-vBJGkTeWqwI",
                "file_size":18282
            }
        ]
    }
}
JSON;
    }

    public function testGetStickerSet()
    {
        $result = $this->getStickerSet();
        $server = new ServerResponse(Json::decode($result, true));

        $server_result = $server->result;

        /** @var StickerSet $server_result */
        self::assertInstanceOf(StickerSet::class, $server_result);
        self::assertEquals('stickerset_name', $server_result->name);
        self::assertEquals('Some name', $server_result->title);
        self::assertFalse($server_result->containsMasks);

        $stickers = $server_result->stickers;
        self::assertCount(4, $stickers);
        self::assertInstanceOf(Sticker::class, $stickers[0]);
    }
}
