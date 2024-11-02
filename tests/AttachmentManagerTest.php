<?php

use PHPUnit\Framework\TestCase;

class AttachmentManagerTest extends TestCase
{
    private $attachmentManager;
    private $uploadDir;

    protected function setUp(): void
    {
        $this->uploadDir = getenv('HOME') . '/tmp/uploads';
        
        Config::$UPLOAD_DIR = $this->uploadDir;
        Config::$MAX_UPLOAD_FILE_SIZE = 2 * 1024 * 1024; // 2MB
        Config::$ALLOWED_FILE_TYPES = ['image/jpeg', 'image/png'];

        $this->attachmentManager = new AttachmentManager();
    }

    protected function tearDown(): void
    {
        array_map('unlink', glob("$this->uploadDir/*.*"));
        //rmdir($this->uploadDir);
    }

    public function testConstructorInitializesProperties()
    {
        $this->assertEquals($this->uploadDir, $this->attachmentManager->uploadDir);
        $this->assertEquals(2 * 1024 * 1024, $this->attachmentManager->maxFileSize);
        $this->assertEquals(['image/jpeg', 'image/png'], $this->attachmentManager->allowedTypes);
    }

    public function testUploadValidFile()
    {
        $file = [
            'name' => 'test.jpg',
            'type' => 'image/jpeg',
            'tmp_name' => tempnam(getenv('HOME') . '/tmp', 'Tux'),
            'error' => UPLOAD_ERR_OK,
            'size' => 500000
        ];

        $filename = $this->attachmentManager->upload($file);
        $this->assertFileExists($this->uploadDir . '/' . $filename);
    }

    public function testUploadInvalidFileType()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Tipo de archivo no permitido.');

        $file = [
            'name' => 'test.txt',
            'type' => 'text/plain',
            'tmp_name' => tempnam(getenv('HOME') . '/tmp', 'Tux'),
            'error' => UPLOAD_ERR_OK,
            'size' => 500000
        ];

        $this->attachmentManager->upload($file);
    }

    public function testUploadFileTooLarge()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('El tamaño del archivo excede el límite.');

        $file = [
            'name' => 'test.jpg',
            'type' => 'image/jpeg',
            'tmp_name' => tempnam(getenv('HOME') . '/tmp', 'Tux'),
            'error' => UPLOAD_ERR_OK,
            'size' => 3 * 1024 * 1024 // 3MB
        ];

        $this->attachmentManager->upload($file);
    }

    public function testUploadError()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Error durante la subida del archivo.');

        $file = [
            'name' => 'test.jpg',
            'type' => 'image/jpeg',
            'tmp_name' => tempnam(getenv('HOME') . '/tmp', 'Tux'),
            'error' => UPLOAD_ERR_CANT_WRITE,
            'size' => 500000
        ];

        $this->attachmentManager->upload($file);
    }

    public function testGenerateFilename()
    {
        $reflection = new ReflectionClass($this->attachmentManager);
        $method = $reflection->getMethod('generateFilename');
        $method->setAccessible(true);

        $file = [
            'name' => 'test.jpg',
            'type' => 'image/jpeg',
            'tmp_name' => tempnam(getenv('HOME') . '/tmp', 'Tux'),
            'error' => UPLOAD_ERR_OK,
            'size' => 500000
        ];

        $filename = $this->attachmentManager->upload($file);
        $this->assertNotEmpty($filename);
        $this->assertMatchesRegularExpression('/^[a-zA-Z0-9_-]{13}\.[a-zA-Z0-9]{3}$/', $filename);
    }
}