<?php /** @noinspection PhpIllegalPsrClassPathInspection */

use yii\db\Migration;

/**
 * Class m210421_212934_update_api_5_1
 */
class m210421_212934_update_api_5_1 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tablePrefix = 'telegram_';
        if ($this->db->driverName === 'pgsql') {
            $tablePrefix = "telegram.";
        }

        $this->dropForeignKey('fk_telegram_request_limiter_chat_id', "{$tablePrefix}request_limiter");
        $this->dropIndex('fki_telegram_request_limiter_chat_id', "{$tablePrefix}request_limiter");
        $this->createIndex('ix_telegram_request_limiter_chat_id', "{$tablePrefix}request_limiter", 'chat_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $tablePrefix = 'telegram_';
        if ($this->db->driverName === 'pgsql') {
            $tablePrefix = "telegram.";
        }

        $this->dropIndex('ix_telegram_request_limiter_chat_id', "{$tablePrefix}request_limiter");
        $this->createIndex('fki_telegram_request_limiter_chat_id', "{$tablePrefix}request_limiter", 'chat_id');
        $this->addForeignKey(
            'fk_telegram_request_limiter_chat_id',
            "{$tablePrefix}request_limiter",
            'chat_id',
            "{$tablePrefix}chat",
            'id',
            'CASCADE',
            'CASCADE'
        );

        return true;
    }
}
