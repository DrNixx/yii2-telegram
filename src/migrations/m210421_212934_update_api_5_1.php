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
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci ENGINE=InnoDB';
            $tablePrefix = "telegram_";
        }

        $defaultDateNow = 'now()';
        if ($this->db->driverName === 'pgsql') {
            $defaultDateNow = "timezone('GMT'::text, now())";
            $tablePrefix = "telegram.";
        }

        $this->addColumn("{$tablePrefix}message", 'sender_chat_id', $this->bigInteger()
            ->comment('Sender of the message, sent on behalf of a chat'));

        $this->addForeignKey(
            'fk_telegram_message_sender_chat',
            "{$tablePrefix}message",
            'sender_chat_id',
            "{$tablePrefix}chat",
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addColumn("{$tablePrefix}message", 'proximity_alert_triggered', $this->text()
            ->comment('Service message. A user in the chat triggered another user\'s proximity alert while sharing Live Location.'));

        $this->addColumn("{$tablePrefix}message", 'message_auto_delete_timer_changed', $this->text()
            ->comment('MessageAutoDeleteTimerChanged object. Message is a service message: auto-delete timer settings changed in the chat'));

        $this->addColumn("{$tablePrefix}message", 'voice_chat_started', $this->text()
            ->comment('VoiceChatStarted object. Message is a service message: voice chat started'));

        $this->addColumn("{$tablePrefix}message", 'voice_chat_ended', $this->text()
            ->comment('VoiceChatEnded object. Message is a service message: voice chat ended'));

        $this->addColumn("{$tablePrefix}message", 'voice_chat_participants_invited', $this->text()
            ->comment('VoiceChatParticipantsInvited object. Message is a service message: new participants invited to a voice chat'));

        $this->createTable("{$tablePrefix}chat_member_updated", [
            'id' => $this->primaryKey()->notNull()->comment('Unique identifier for this entry'),
            'chat_id' => $this->bigInteger()->notNull()->comment('Chat the user belongs to'),
            'user_id' => $this->bigInteger()->notNull()->comment('Performer of the action, which resulted in the change'),
            'date' => $this->dateTime()->notNull()->defaultExpression($defaultDateNow)->comment('Date the change was done'),
            'old_chat_member' => $this->text()->notNull()->comment('Previous information about the chat member'),
            'new_chat_member' => $this->text()->notNull()->comment('New information about the chat member'),
            'invite_link' => $this->text()->comment('Chat invite link, which was used by the user to join the chat; for joining by invite link events only'),
            'created_at' => $this->dateTime()
                ->notNull()
                ->defaultExpression($defaultDateNow)
                ->comment('Entry date creation'),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_telegram_chat_member_updated_chat_id',
            "{$tablePrefix}chat_member_updated",
            'chat_id',
            "{$tablePrefix}chat",
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_telegram_chat_member_updated_user_id',
            "{$tablePrefix}chat_member_updated",
            'user_id',
            "{$tablePrefix}user",
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addColumn("{$tablePrefix}telegram_update", 'my_chat_member_updated_id', $this->integer()
            ->comment('The bot\'s chat member status was updated in a chat. For private chats, this update is received only when the bot is blocked or unblocked by the user.'));

        $this->addForeignKey(
            'fk_telegram_update_my_chat_member_updated_id',
            "{$tablePrefix}telegram_update",
            'my_chat_member_updated_id',
            "{$tablePrefix}chat_member_updated",
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addColumn("{$tablePrefix}telegram_update", 'chat_member_updated_id', $this->integer()
            ->comment('A chat member\'s status was updated in a chat. The bot must be an administrator in the chat and must explicitly specify “chat_member” in the list of allowed_updates to receive these updates.'));

        $this->addForeignKey(
            'fk_telegram_update_chat_member_updated_id',
            "{$tablePrefix}telegram_update",
            'chat_member_updated_id',
            "{$tablePrefix}chat_member_updated",
            'id',
            'CASCADE',
            'CASCADE'
        );
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

        $this->dropColumn("{$tablePrefix}telegram_update", 'my_chat_member_updated_id');
        $this->dropColumn("{$tablePrefix}telegram_update", 'chat_member_updated_id');

        $this->dropTable("{$tablePrefix}chat_member_updated");

        $this->dropColumn("{$tablePrefix}message", 'voice_chat_participants_invited');
        $this->dropColumn("{$tablePrefix}message", 'voice_chat_ended');
        $this->dropColumn("{$tablePrefix}message", 'voice_chat_started');
        $this->dropColumn("{$tablePrefix}message", 'message_auto_delete_timer_changed');
        $this->dropColumn("{$tablePrefix}message", 'proximity_alert_triggered');
        $this->dropColumn("{$tablePrefix}message", 'sender_chat_id');

        return true;
    }
}
