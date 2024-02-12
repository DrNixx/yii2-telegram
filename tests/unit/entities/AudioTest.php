<?php
namespace onix\telegram\tests\unit\entities;

use onix\telegram\entities\Audio;

class AudioTest extends \Codeception\Test\Unit
{
    public static function getFakeRecordedAudio()
    {
        $mime_type = ['audio/ogg', 'audio/mpeg', 'audio/vnd.wave', 'audio/x-ms-wma', 'audio/basic'];
        return [
            'file_id'   => mt_rand(1, 999),
            'duration'  => (string) mt_rand(1, 99) . ':' . mt_rand(1, 60),
            'performer' => 'phpunit',
            'title'     => 'track from phpunit',
            'mime_type' => $mime_type[array_rand($mime_type, 1)],
            'file_size' => mt_rand(1, 99999),
        ];
    }

    public function testInstance()
    {
        $data = self::getFakeRecordedAudio();
        $audio = new Audio($data);
        self::assertInstanceOf(Audio::class, $audio);
    }

    public function testGetProperties()
    {
        $data = self::getFakeRecordedAudio();
        $audio = new Audio($data);
        self::assertEquals($data['file_id'], $audio->fileId);
        self::assertEquals($data['duration'], $audio->duration);
        self::assertEquals($data['performer'], $audio->performer);
        self::assertEquals($data['title'], $audio->title);
        self::assertEquals($data['mime_type'], $audio->mimeType);
        self::assertEquals($data['file_size'], $audio->fileSize);
    }
}
