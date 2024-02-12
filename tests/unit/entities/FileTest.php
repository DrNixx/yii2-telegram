<?php
namespace onix\telegram\tests\unit\entities;

use onix\telegram\entities\File;

class FileTest extends \Codeception\Test\Unit
{
    /**
     * @var array
     */
    private $data;

    public function setUp(): void
    {
        $this->data = [
            'file_id'   => (int) mt_rand(1, 99),
            'file_size' => (int) mt_rand(100, 99999),
            'file_path' => 'home' . DIRECTORY_SEPARATOR . 'phpunit',
        ];
    }

    public function testInstance()
    {
        $file = new File($this->data);
        $this->assertInstanceOf(File::class, $file);
    }

    public function testGetFileId()
    {
        $file = new File($this->data);
        $id   = $file->fileId;
        $this->assertIsInt($id);
        $this->assertEquals($this->data['file_id'], $id);
    }

    public function testGetFileSize()
    {
        $file = new File($this->data);
        $size = $file->fileSize;
        $this->assertIsInt($size);
        $this->assertEquals($this->data['file_size'], $size);
    }

    public function testGetFilePath()
    {
        $file = new File($this->data);
        $path = $file->filePath;
        $this->assertEquals($this->data['file_path'], $path);
    }

    public function testGetFileSizeWithoutData()
    {
        unset($this->data['file_size']);
        $file = new File($this->data);
        $id   = $file->fileSize;
        $this->assertNull($id);
    }

    public function testGetFilePathWithoutData()
    {
        unset($this->data['file_path']);
        $file = new File($this->data);
        $path = $file->filePath;
        $this->assertNull($path);
    }
}
