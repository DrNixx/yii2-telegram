<?php
namespace onix\telegram\tests\unit\entities;

use onix\telegram\entities\WebhookInfo;

class WebhookInfoTest extends \Codeception\Test\Unit
{
    /**
     * @var array Webhook data
     */
    public $data;

    public function setUp(): void
    {
        $this->data = [
            'url'                    => 'http://phpunit',
            'has_custom_certificate' => (bool) mt_rand(0, 1),
            'pending_update_count'   => (int) mt_rand(1, 9),
            'last_error_date'        => time(),
            'last_error_message'     => 'Some_error_message',
            'max_connections'        => (int) mt_rand(1, 100),
            'allowed_updates'        => ['message', 'edited_channel_post', 'callback_query'],
        ];
    }

    public function testBaseStageWebhookInfo()
    {
        $webhook = new WebhookInfo($this->data);
        $this->assertInstanceOf(WebhookInfo::class, $webhook);
    }

    public function testGetUrl()
    {
        $webhook = new WebhookInfo($this->data);
        $url     = $webhook->url;
        $this->assertEquals($this->data['url'], $url);
    }

    public function testGetHasCustomCertificate()
    {
        $webhook            = new WebhookInfo($this->data);
        $custom_certificate = $webhook->hasCustomCertificate;
        $this->assertIsBool($custom_certificate);
        $this->assertEquals($this->data['has_custom_certificate'], $custom_certificate);
    }

    public function testGetPendingUpdateCount()
    {
        $webhook      = new WebhookInfo($this->data);
        $update_count = $webhook->pendingUpdateCount;
        $this->assertIsInt($update_count);
        $this->assertEquals($this->data['pending_update_count'], $update_count);
    }

    public function testGetLastErrorDate()
    {
        $webhook    = new WebhookInfo($this->data);
        $error_date = $webhook->lastErrorDate;
        $this->assertIsInt($error_date);
        $this->assertEquals($this->data['last_error_date'], $error_date);
    }

    public function testGetLastErrorMessage()
    {
        $webhook   = new WebhookInfo($this->data);
        $error_msg = $webhook->lastErrorMessage;
        $this->assertIsString($error_msg);
        $this->assertEquals($this->data['last_error_message'], $error_msg);
    }

    public function testGetMaxConnections()
    {
        $webhook         = new WebhookInfo($this->data);
        $max_connections = $webhook->maxConnections;
        $this->assertIsInt($max_connections);
        $this->assertEquals($this->data['max_connections'], $max_connections);
    }

    public function testGetAllowedUpdates()
    {
        $webhook         = new WebhookInfo($this->data);
        $allowed_updates = $webhook->allowedUpdates;
        $this->assertIsArray($allowed_updates);
        $this->assertEquals($this->data['allowed_updates'], $allowed_updates);
    }

    public function testGetDataWithoutParams()
    {
        // Make a copy to not risk failed tests if not run in proper order.
        $data = $this->data;

        unset($data['url']);
        $this->assertNull((new WebhookInfo($data))->url);

        unset($data['has_custom_certificate']);
        $this->assertNull((new WebhookInfo($data))->hasCustomCertificate);

        unset($data['pending_update_count']);
        $this->assertNull((new WebhookInfo($data))->pendingUpdateCount);

        unset($data['last_error_date']);
        $this->assertNull((new WebhookInfo($data))->lastErrorDate);

        unset($data['last_error_message']);
        $this->assertNull((new WebhookInfo($data))->lastErrorMessage);

        unset($data['max_connections']);
        $this->assertNull((new WebhookInfo($data))->maxConnections);

        unset($data['allowed_updates']);
        $this->assertNull((new WebhookInfo($data))->allowedUpdates);
    }
}
