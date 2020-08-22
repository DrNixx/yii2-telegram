<?php

use yii\db\Migration;

/**
 * Class m200717_202527_create_telegram_tables
 */
class m200717_202527_create_telegram_tables extends Migration
{
    /**
     * {@inheritdoc}
     *
     * @throws \yii\db\Exception
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
            $this->getDb()->createCommand("CREATE SCHEMA telegram")->execute();
            $tablePrefix = "telegram.";
        }

        $this->createTable("{$tablePrefix}user", [
            'id' => $this->bigInteger()->notNull()->comment('Unique identifier for this user or bot'),
            'is_bot' => $this->boolean()->notNull()->defaultValue(false)->comment('True, if this user is a bot'),
            'user_id' => $this->integer()->comment('Identifier for chess user'),
            'first_name' => $this->string(255)->notNull()->comment('User\'s or bot\'s first name'),
            'last_name' => $this->string(255)->comment('User\'s or bot\'s last name'),
            'username' => $this->string(191)->comment('User\'s or bot\'s last username'),
            'language_code' => $this->string(10)->comment('IETF language tag of the user\'s language'),
            'created_at' => $this->dateTime()
                ->notNull()
                ->defaultExpression($defaultDateNow)
                ->comment('Entry date creation'),
            'updated_at' => $this->dateTime()
                ->notNull()
                ->defaultExpression($defaultDateNow)
                ->comment('Entry date update'),
        ], $tableOptions);

        $this->addPrimaryKey('pk_telegram_user', "{$tablePrefix}user", ['id']);
        $this->createIndex('ix_telegram_user_username', "{$tablePrefix}user", 'username');

        $this->createTable("{$tablePrefix}chat", [
            'id' => $this->bigInteger()->notNull()->comment('Unique identifier for this chat'),
            'type' => $this->string(15)
                ->notNull()
                ->comment('Type of chat, can be either private, group, supergroup or channel'),
            'title' => $this->string(255)
                ->comment('Title, for supergroups, channels and group chats'),
            'first_name' => $this->string(255)->comment('First name of the other party in a private chat'),
            'last_name' => $this->string(255)->comment('Last name of the other party in a private chat'),
            'username' => $this->string(255)
                ->comment('Username, for private chats, supergroups and channels if available'),
            'all_members_are_administrators' => $this->boolean()
                ->notNull()
                ->defaultValue(false)
                ->comment("True if a all members of this group are admins"),
            'created_at' => $this->dateTime()
                ->notNull()
                ->defaultExpression($defaultDateNow)
                ->comment('Entry date creation'),
            'updated_at' => $this->dateTime()
                ->notNull()
                ->defaultExpression($defaultDateNow)
                ->comment('Entry date update'),
            'old_id' => $this->bigInteger()
                ->comment('Unique chat identifier, this is filled when a group is converted to a supergroup'),
        ], $tableOptions);

        $this->addPrimaryKey('pk_telegram_chat', "{$tablePrefix}chat", ['id']);
        $this->createIndex('ix_telegram_chat_old_id', "{$tablePrefix}chat", 'old_id');

        $this->createTable("{$tablePrefix}user_chat", [
            'user_id' => $this->bigInteger()->notNull()->comment('Unique user identifier'),
            'chat_id' => $this->bigInteger()->notNull()->comment('Unique user or chat identifier'),
        ], $tableOptions);

        $this->addPrimaryKey('pk_telegram_user_chat', "{$tablePrefix}user_chat", ['user_id', 'chat_id']);
        $this->createIndex('fki_telegram_user_chat_chat_id', "{$tablePrefix}user_chat", 'chat_id');

        $this->addForeignKey(
            'fk_user_chat_user',
            "{$tablePrefix}user_chat",
            'user_id',
            "{$tablePrefix}user",
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_user_chat_chat',
            "{$tablePrefix}user_chat",
            'chat_id',
            "{$tablePrefix}chat",
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createTable("{$tablePrefix}inline_query", [
            'id' => $this->bigInteger()->notNull()->comment('Unique identifier for this query'),
            'user_id' => $this->bigInteger()->notNull()->comment('Unique user identifier'),
            'location' => $this->string(255)->comment('Location of the user'),
            'query' => $this->text()->notNull()->comment('Text of the query'),
            'offset' => $this->string(255)->comment('Offset of the result'),
            'created_at' => $this->dateTime()
                ->notNull()
                ->defaultExpression($defaultDateNow)
                ->comment('Entry date creation'),
        ], $tableOptions);

        $this->addPrimaryKey('pk_telegram_inline_query', "{$tablePrefix}inline_query", ['id']);
        $this->createIndex('fki_telegram_inline_query_user_id', "{$tablePrefix}inline_query", 'user_id');

        $this->addForeignKey(
            'fk_telegram_inline_query_user_id',
            "{$tablePrefix}inline_query",
            'user_id',
            "{$tablePrefix}user",
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createTable("{$tablePrefix}chosen_inline_result", [
            'id' => $this->primaryKey()->notNull()->comment('Unique identifier for this entry'),
            'result_id' => $this->string(255)
                ->notNull()
                ->comment('The unique identifier for the result that was chosen'),
            'user_id' => $this->bigInteger()->comment('The user that chose the result'),
            'location' => $this->string(255)
                ->comment('Sender location, only for bots that require user location'),
            'inline_message_id' => $this->string(255)->comment('Identifier of the sent inline message'),
            'query' => $this->text()->notNull()->comment('The query that was used to obtain the result'),
            'created_at' => $this->dateTime()
                ->notNull()
                ->defaultExpression($defaultDateNow)
                ->comment('Entry date creation'),
        ], $tableOptions);

        $this->createIndex(
            'fki_telegram_chosen_inline_result_user_id',
            "{$tablePrefix}chosen_inline_result",
            'user_id'
        );

        $this->addForeignKey(
            'fk_telegram_chosen_inline_result_user_id',
            "{$tablePrefix}chosen_inline_result",
            'user_id',
            "{$tablePrefix}user",
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createTable("{$tablePrefix}message", [
            'chat_id' => $this->bigInteger()->notNull()->comment('Unique chat identifier'),
            'id' => $this->bigInteger()->notNull()->comment('Unique message identifier'),
            'user_id' => $this->bigInteger()->notNull()->comment('Unique user identifier'),
            'date' => $this->dateTime()->comment('Entry date creation'),
            'forward_from' => $this->bigInteger()->comment('Unique user identifier, sender of the original message'),
            'forward_from_chat' => $this->bigInteger()
                ->comment('Unique chat identifier, chat the original message belongs to'),
            'forward_from_message_id' => $this->bigInteger()
                ->comment('Unique chat identifier of the original message in the channel'),
            'forward_signature' => $this->text()
                ->comment('For messages forwarded from channels, signature of the post author if present'),
            'forward_sender_name' => $this->text()
                ->comment('Sender\'s name for messages forwarded from users who disallow adding a link to their account in forwarded messages'),
            'forward_date' => $this->dateTime()->comment('Date the original message was sent in timestamp format'),
            'reply_to_chat' => $this->bigInteger()->comment('Unique chat identifier'),
            'reply_to_message' => $this->bigInteger()->comment('Message that this message is reply to'),
            'via_bot' => $this->bigInteger()->comment('Optional. Bot through which the message was sent'),
            'edit_date' => $this->dateTime()->comment('Date the message was last edited'),
            'media_group_id' => $this->text()
                ->comment('The unique identifier of a media message group this message belongs to'),
            'author_signature' => $this->text()->comment('Signature of the post author for messages in channels'),
            'text' => $this->text()
                ->comment('For text messages, the actual UTF-8 text of the message max message length 4096 char utf8mb4'),
            'entities' => $this->text()
                ->comment('For text messages, special entities like usernames, URLs, bot commands, etc. that appear in the text'),
            'caption_entities' => $this->text()
                ->comment('For messages with a caption, special entities like usernames, URLs, bot commands, etc. that appear in the caption'),
            'audio' => $this->text()->comment('Audio object. Message is an audio file, information about the file'),
            'document' => $this->text()
                ->comment('Document object. Message is a general file, information about the file'),
            'animation' => $this->text()->comment('Message is an animation, information about the animation'),
            'game' => $this->text()->comment('Game object. Message is a game, information about the game'),
            'photo' => $this->text()
                ->comment('Array of PhotoSize objects. Message is a photo, available sizes of the photo'),
            'sticker' => $this->text()->comment('Sticker object. Message is a sticker, information about the sticker'),
            'video' => $this->text()->comment('Video object. Message is a video, information about the video'),
            'voice' => $this->text()->comment('Voice Object. Message is a Voice, information about the Voice'),
            'video_note' => $this->text()
                ->comment('VoiceNote Object. Message is a Video Note, information about the Video Note'),
            'caption' => $this->text()->comment('For message with caption, the actual UTF-8 text of the caption'),
            'contact' => $this->text()
                ->comment('Contact object. Message is a shared contact, information about the contact'),
            'location' => $this->text()
                ->comment('Location object. Message is a shared location, information about the location'),
            'venue' => $this->text()->comment('Venue object. Message is a Venue, information about the Venue'),
            'poll' => $this->text()->comment('Poll object. Message is a native poll, information about the poll'),
            'dice' => $this->text()->comment('Message is a dice with random value from 1 to 6'),
            'new_chat_members' => $this->text()
                ->comment('List of unique user identifiers, new member(s) were added to the group, information about them (one of these members may be the bot itself)'),
            'left_chat_member' => $this->bigInteger()
                ->comment('Unique user identifier, a member was removed from the group, information about them (this member may be the bot itself)'),
            'new_chat_title' => $this->string(255)->comment('A chat title was changed to this value'),
            'new_chat_photo' => $this->text()
                ->comment('Array of PhotoSize objects. A chat photo was change to this value'),
            'delete_chat_photo' => $this->boolean()
                ->notNull()
                ->defaultValue(false)
                ->comment('Informs that the chat photo was deleted'),
            'group_chat_created' => $this->boolean()
                ->notNull()
                ->defaultValue(false)
                ->comment('Informs that the group has been created'),
            'supergroup_chat_created' => $this->boolean()
                ->notNull()
                ->defaultValue(false)
                ->comment('Informs that the supergroup has been created'),
            'channel_chat_created' => $this->boolean()
                ->notNull()
                ->defaultValue(false)
                ->comment('Informs that the channel chat has been created'),
            'migrate_to_chat_id' => $this->bigInteger()
                ->comment('Migrate to chat identifier. The group has been migrated to a supergroup with the specified identifier'),
            'migrate_from_chat_id' => $this->bigInteger()
                ->comment('Migrate from chat identifier. The supergroup has been migrated from a group with the specified identifier'),
            'pinned_message' => $this->text()->comment('Message object. Specified message was pinned'),
            'invoice' => $this->text()->comment('Message is an invoice for a payment, information about the invoice'),
            'successful_payment' => $this->text()
                ->comment('Message is a service message about a successful payment, information about the payment'),
            'connected_website' => $this->text()
                ->comment('The domain name of the website on which the user has logged in.'),
            'passport_data' => $this->text()->comment('Telegram Passport data'),
            'reply_markup' => $this->text()->comment('Inline keyboard attached to the message'),
        ], $tableOptions);

        $this->addPrimaryKey('pk_telegram_message', "{$tablePrefix}message", ['chat_id', 'id']);
        $this->createIndex('fki_telegram_message_user_id', "{$tablePrefix}message", 'user_id');
        $this->createIndex('fki_telegram_message_chat_id', "{$tablePrefix}message", 'chat_id');
        $this->createIndex('fki_telegram_message_forward_from', "{$tablePrefix}message", 'forward_from');
        $this->createIndex(
            'fki_telegram_message_forward_from_chat',
            "{$tablePrefix}message",
            'forward_from_chat'
        );
        $this->createIndex(
            'fki_telegram_message_reply_to',
            "{$tablePrefix}message",
            ['reply_to_chat', 'reply_to_message']
        );
        $this->createIndex('fki_telegram_message_via_bot', "{$tablePrefix}message", 'via_bot');
        $this->createIndex('fki_telegram_message_left_chat_member', "{$tablePrefix}message", 'left_chat_member');
        $this->createIndex(
            'fki_telegram_message_migrate_from_chat_id',
            "{$tablePrefix}message",
            'migrate_from_chat_id'
        );
        $this->createIndex('fki_telegram_message_migrate_to_chat_id', "{$tablePrefix}message", 'migrate_to_chat_id');

        $this->addForeignKey(
            'fk_telegram_message_user_id',
            "{$tablePrefix}message",
            'user_id',
            "{$tablePrefix}user",
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_telegram_message_chat_id',
            "{$tablePrefix}message",
            'chat_id',
            "{$tablePrefix}chat",
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_telegram_message_forward_from',
            "{$tablePrefix}message",
            'forward_from',
            "{$tablePrefix}user",
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_telegram_message_forward_from_chat',
            "{$tablePrefix}message",
            'forward_from_chat',
            "{$tablePrefix}chat",
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_telegram_message_reply_to',
            "{$tablePrefix}message",
            ['reply_to_chat', 'reply_to_message'],
            "{$tablePrefix}message",
            ['chat_id', 'id'],
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_telegram_message_via_bot',
            "{$tablePrefix}message",
            'via_bot',
            "{$tablePrefix}user",
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_telegram_message_left_chat_member',
            "{$tablePrefix}message",
            'left_chat_member',
            "{$tablePrefix}user",
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createTable("{$tablePrefix}edited_message", [
            'id' => $this->primaryKey()->notNull()->comment('Unique identifier for this entry'),
            'chat_id' => $this->bigInteger()->comment('Unique chat identifier'),
            'message_id' => $this->bigInteger()->comment('Unique message identifier'),
            'user_id' => $this->bigInteger()->comment('Unique user identifier'),
            'edit_date' => $this->dateTime()->comment('Date the message was last edited'),
            'text' => $this->text()->comment('For text messages, the actual UTF-8 text of the message max message length 4096 char utf8'),
            'entities' => $this->text()->comment('For text messages, special entities like usernames, URLs, bot commands, etc. that appear in the text'),
            'caption' => $this->text()->comment('For message with caption, the actual UTF-8 text of the caption'),
        ], $tableOptions);

        $this->createIndex('fki_telegram_edited_message_user_id', "{$tablePrefix}edited_message", 'user_id');
        $this->createIndex(
            'fki_telegram_edited_message_message',
            "{$tablePrefix}edited_message",
            ['chat_id', 'message_id']
        );
        $this->createIndex('fki_telegram_edited_message_chat_id', "{$tablePrefix}edited_message", 'chat_id');

        $this->addForeignKey(
            'fk_telegram_edited_message_user_id',
            "{$tablePrefix}edited_message",
            'user_id',
            "{$tablePrefix}user",
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_telegram_edited_message_message',
            "{$tablePrefix}edited_message",
            ['chat_id', 'message_id'],
            "{$tablePrefix}message",
            ['chat_id', 'id'],
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_telegram_edited_message_chat_id',
            "{$tablePrefix}edited_message",
            'chat_id',
            "{$tablePrefix}chat",
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createTable("{$tablePrefix}callback_query", [
            'id' => $this->bigInteger()->notNull()->comment('Unique identifier for this query'),
            'user_id' => $this->bigInteger()->comment('Unique user identifier'),
            'chat_id' => $this->bigInteger()->comment('Unique chat identifier'),
            'message_id' => $this->bigInteger()->comment('Unique message identifier'),
            'inline_message_id' => $this->string(255)->comment('Identifier of the message sent via the bot in inline mode, that originated the query'),
            'chat_instance' => $this->string(255)->comment('Global identifier, uniquely corresponding to the chat to which the message with the callback button was sent'),
            'data' => $this->string(255)->comment('Data associated with the callback button'),
            'game_short_name' => $this->string(255)->comment('Short name of a Game to be returned, serves as the unique identifier for the game'),
            'created_at' => $this->dateTime()
                ->notNull()
                ->defaultExpression($defaultDateNow)
                ->comment('Entry date creation'),
        ], $tableOptions);

        $this->addPrimaryKey('pk_telegram_callback_query', "{$tablePrefix}callback_query", 'id');
        $this->createIndex('fki_telegram_callback_query_user_id', "{$tablePrefix}callback_query", 'user_id');
        $this->createIndex('fki_telegram_callback_query_chat_id', "{$tablePrefix}callback_query", 'chat_id');
        $this->createIndex('fki_telegram_callback_query_message', "{$tablePrefix}callback_query", ['chat_id', 'message_id']);


        $this->addForeignKey(
            'fk_telegram_callback_query_user_id',
            "{$tablePrefix}callback_query",
            'user_id',
            "{$tablePrefix}user",
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_telegram_callback_query_chat_id',
            "{$tablePrefix}callback_query",
            'chat_id',
            "{$tablePrefix}chat",
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_telegram_callback_query_message',
            "{$tablePrefix}callback_query",
            ['chat_id', 'message_id'],
            "{$tablePrefix}message",
            ['chat_id', 'id'],
            'CASCADE',
            'CASCADE'
        );

        $this->createTable("{$tablePrefix}shipping_query", [
            'id' => $this->bigInteger()->notNull()->comment('Unique query identifier'),
            'user_id' => $this->bigInteger()->comment('User who sent the query'),
            'invoice_payload' => $this->string(255)->notNull()->comment('Bot specified invoice payload'),
            'shipping_address' => $this->string(255)->notNull()->comment('User specified shipping address'),
            'created_at' => $this->dateTime()
                ->notNull()
                ->defaultExpression($defaultDateNow)
                ->comment('Entry date creation'),
        ], $tableOptions);

        $this->addPrimaryKey('pk_telegram_shipping_query', "{$tablePrefix}shipping_query", 'id');
        $this->createIndex('fki_telegram_shipping_query_user_id', "{$tablePrefix}shipping_query", 'user_id');


        $this->addForeignKey(
            'fk_telegram_shipping_query_user_id',
            "{$tablePrefix}shipping_query",
            'user_id',
            "{$tablePrefix}user",
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createTable("{$tablePrefix}pre_checkout_query", [
            'id' => $this->bigInteger()->notNull()->comment('Unique query identifier'),
            'user_id' => $this->bigInteger()->comment('User who sent the query'),
            'currency' => $this->string(3)->comment('Three-letter ISO 4217 currency code'),
            'total_amount' => $this->bigInteger()->comment('Total price in the smallest units of the currency'),
            'invoice_payload' => $this->string(255)->notNull()->comment('Bot specified invoice payload'),
            'shipping_option_id' => $this->string(255)->comment('Identifier of the shipping option chosen by the user'),
            'order_info' => $this->text()->comment('Order info provided by the user'),
            'created_at' => $this->dateTime()
                ->notNull()
                ->defaultExpression($defaultDateNow)
                ->comment('Entry date creation'),
        ], $tableOptions);

        $this->addPrimaryKey('pk_telegram_pre_checkout_query', "{$tablePrefix}pre_checkout_query", 'id');
        $this->createIndex('fki_telegram_pre_checkout_query_user_id', "{$tablePrefix}pre_checkout_query", 'user_id');


        $this->addForeignKey(
            'fk_telegram_pre_checkout_query_user_id',
            "{$tablePrefix}pre_checkout_query",
            'user_id',
            "{$tablePrefix}user",
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createTable("{$tablePrefix}poll", [
            'id' => $this->bigInteger()->notNull()->comment('Unique poll identifier'),
            'question' => $this->string(255)->notNull()->comment('Poll question'),
            'options' => $this->text()->notNull()->comment('List of poll options'),
            'total_voter_count' => $this->integer()->comment('Total number of users that voted in the poll'),
            'is_closed' => $this->boolean()->notNull()->defaultValue(false)->comment('True, if the poll is closed'),
            'is_anonymous' => $this->boolean()->notNull()->defaultValue(true)->comment('True, if the poll is anonymous'),
            'type' => $this->string(255)->comment('Poll type, currently can be "regular" or "quiz"'),
            'allows_multiple_answers' => $this->boolean()->notNull()->defaultValue(false)->comment('True, if the poll allows multiple answers'),
            'correct_option_id' => $this->integer()->comment('0-based identifier of the correct answer option. Available only for polls in the quiz mode, which are closed, or was sent (not forwarded) by the bot or to the private chat with the bot.'),
            'explanation' => $this->string(255)->comment('Text that is shown when a user chooses an incorrect answer or taps on the lamp icon in a quiz-style poll, 0-200 characters'),
            'explanation_entities' => $this->text()->notNull()->comment('Special entities like usernames, URLs, bot commands, etc. that appear in the explanation'),
            'open_period' => $this->integer()->comment('Amount of time in seconds the poll will be active after creation'),
            'close_date' => $this->dateTime()->comment('Point in time when the poll will be automatically closed'),
            'created_at' => $this->dateTime()
                ->notNull()
                ->defaultExpression($defaultDateNow)
                ->comment('Entry date creation'),
        ], $tableOptions);

        $this->addPrimaryKey('pk_telegram_poll', "{$tablePrefix}poll", 'id');

        $this->createTable("{$tablePrefix}poll_answer", [
            'poll_id' => $this->bigInteger()->notNull()->comment('Unique poll identifier'),
            'user_id' => $this->bigInteger()->notNull()->comment('Unique user identifier'),
            'option_ids' => $this->text()->comment('0-based identifiers of answer options, chosen by the user. May be empty if the user retracted their vote.'),
            'created_at' => $this->dateTime()
                ->notNull()
                ->defaultExpression($defaultDateNow)
                ->comment('Entry date creation'),
        ], $tableOptions);

        $this->addPrimaryKey('pk_telegram_poll_answer', "{$tablePrefix}poll_answer", ['poll_id', 'user_id']);
        $this->createIndex('fki_telegram_poll_answer_user_id', "{$tablePrefix}poll_answer", 'user_id');

        $this->addForeignKey(
            'fk_poll_answer_poll',
            "{$tablePrefix}poll_answer",
            'poll_id',
            "{$tablePrefix}poll",
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_poll_answer_user',
            "{$tablePrefix}poll_answer",
            'user_id',
            "{$tablePrefix}user",
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createTable("{$tablePrefix}telegram_update", [
            'id' => $this->bigInteger()->notNull()->comment('Update\'s unique identifier'),
            'chat_id' => $this->bigInteger()->comment('Unique chat identifier'),
            'message_id' => $this->bigInteger()->comment('New incoming message of any kind - text, photo, sticker, etc.'),
            'edited_message_id' => $this->bigInteger()->comment('New version of a message that is known to the bot and was edited'),
            'channel_post_id' => $this->bigInteger()->comment('New incoming channel post of any kind - text, photo, sticker, etc.'),
            'edited_channel_post_id' => $this->bigInteger()->comment('New version of a channel post that is known to the bot and was edited'),
            'inline_query_id' => $this->bigInteger()->comment('New incoming inline query'),
            'chosen_inline_result_id' => $this->bigInteger()->comment('The result of an inline query that was chosen by a user and sent to their chat partner'),
            'callback_query_id' => $this->bigInteger()->comment('New incoming callback query'),
            'shipping_query_id' => $this->bigInteger()->comment('New incoming shipping query. Only for invoices with flexible price'),
            'pre_checkout_query_id' => $this->bigInteger()->comment('New incoming pre-checkout query. Contains full information about checkout'),
            'poll_id' => $this->bigInteger()->comment('New poll state. Bots receive only updates about polls, which are sent or stopped by the bot'),
            'poll_answer_poll_id' => $this->bigInteger()->comment('A user changed their answer in a non-anonymous poll. Bots receive new votes only in polls that were sent by the bot itself.'),
        ], $tableOptions);

        $this->addPrimaryKey('pk_telegram_update', "{$tablePrefix}telegram_update", 'id');
        $this->createIndex('fki_telegram_update_message', "{$tablePrefix}telegram_update", ['chat_id', 'message_id']);
        $this->createIndex('fki_telegram_update_edited_message', "{$tablePrefix}telegram_update", 'edited_message_id');
        $this->createIndex('fki_telegram_update_channel_post', "{$tablePrefix}telegram_update", 'channel_post_id');
        $this->createIndex('fki_telegram_update_edited_channel_post', "{$tablePrefix}telegram_update", 'edited_channel_post_id');
        $this->createIndex('fki_telegram_update_inline_query', "{$tablePrefix}telegram_update", 'inline_query_id');
        $this->createIndex('fki_telegram_update_chosen_inline_result', "{$tablePrefix}telegram_update", 'chosen_inline_result_id');
        $this->createIndex('fki_telegram_update_callback_query', "{$tablePrefix}telegram_update", 'callback_query_id');
        $this->createIndex('fki_telegram_update_shipping_query', "{$tablePrefix}telegram_update", 'shipping_query_id');
        $this->createIndex('fki_telegram_update_pre_checkout_query', "{$tablePrefix}telegram_update", 'pre_checkout_query_id');
        $this->createIndex('fki_telegram_update_poll', "{$tablePrefix}telegram_update", 'poll_id');
        $this->createIndex('fki_telegram_update_poll_answer_poll', "{$tablePrefix}telegram_update", 'poll_answer_poll_id');

        $this->addForeignKey(
            'fk_telegram_telegram_update_message',
            "{$tablePrefix}telegram_update",
            ['chat_id', 'message_id'],
            "{$tablePrefix}message",
            ['chat_id', 'id'],
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_telegram_telegram_update_post',
            "{$tablePrefix}telegram_update",
            ['chat_id', 'channel_post_id'],
            "{$tablePrefix}message",
            ['chat_id', 'id'],
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_telegram_telegram_update_edited_message',
            "{$tablePrefix}telegram_update",
            'edited_message_id',
            "{$tablePrefix}edited_message",
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_telegram_telegram_update_edited_post',
            "{$tablePrefix}telegram_update",
            'edited_channel_post_id',
            "{$tablePrefix}edited_message",
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_telegram_telegram_update_inline_query',
            "{$tablePrefix}telegram_update",
            'inline_query_id',
            "{$tablePrefix}inline_query",
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_telegram_telegram_update_chosen_inline_result',
            "{$tablePrefix}telegram_update",
            'chosen_inline_result_id',
            "{$tablePrefix}chosen_inline_result",
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_telegram_telegram_update_callback_query',
            "{$tablePrefix}telegram_update",
            'callback_query_id',
            "{$tablePrefix}callback_query",
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_telegram_telegram_update_shipping_query',
            "{$tablePrefix}telegram_update",
            'shipping_query_id',
            "{$tablePrefix}shipping_query",
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_telegram_telegram_update_pre_checkout_query',
            "{$tablePrefix}telegram_update",
            'pre_checkout_query_id',
            "{$tablePrefix}pre_checkout_query",
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_telegram_telegram_update_poll',
            "{$tablePrefix}telegram_update",
            'poll_id',
            "{$tablePrefix}poll",
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createTable("{$tablePrefix}conversation", [
            'id' => $this->primaryKey()->notNull()->comment('Unique identifier for this entry'),
            'user_id' => $this->bigInteger()->comment('Unique user identifier'),
            'chat_id' => $this->bigInteger()->comment('Unique chat identifier'),
            'status' => $this->string(15)
                ->notNull()
                ->defaultValue('active')
                ->comment('Identifier of the message sent via the bot in inline mode, that originated the query'),
            'command' => $this->string(160)
                ->notNull()
                ->defaultValue('active')
                ->comment('Default command to execute'),
            'notes' => $this->text()->comment('Data stored from command'),
            'created_at' => $this->dateTime()
                ->notNull()
                ->defaultExpression($defaultDateNow)
                ->comment('Entry date creation'),
            'updated_at' => $this->dateTime()
                ->notNull()
                ->defaultExpression($defaultDateNow)
                ->comment('Entry date update'),
        ], $tableOptions);

        $this->createIndex('fki_telegram_conversation_user_id', "{$tablePrefix}conversation", 'user_id');
        $this->createIndex('fki_telegram_conversation_chat_id', "{$tablePrefix}conversation", 'chat_id');
        $this->createIndex('ix_telegram_conversation_status', "{$tablePrefix}conversation", 'status');


        $this->addForeignKey(
            'fk_telegram_conversation_user_id',
            "{$tablePrefix}conversation",
            'user_id',
            "{$tablePrefix}user",
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_telegram_conversation_chat_id',
            "{$tablePrefix}conversation",
            'chat_id',
            "{$tablePrefix}chat",
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createTable("{$tablePrefix}request_limiter", [
            'id' => $this->primaryKey()->notNull()->comment('Unique identifier for this entry'),
            'chat_id' => $this->bigInteger()->comment('Unique chat identifier'),
            'inline_message_id' => $this->string(255)->comment('Identifier of the sent inline message'),
            'method' => $this->string(255)->comment('Request method'),
            'created_at' => $this->dateTime()
                ->notNull()
                ->defaultExpression($defaultDateNow)
                ->comment('Entry date creation'),
        ], $tableOptions);

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
    }

    /**
     * {@inheritdoc}
     * @throws \yii\db\Exception
     */
    public function safeDown()
    {
        $tablePrefix = 'telegram_';
        if ($this->db->driverName === 'pgsql') {
            $tablePrefix = "telegram.";
        }

        $this->dropTable("{$tablePrefix}request_limiter");
        $this->dropTable("{$tablePrefix}conversation");
        $this->dropTable("{$tablePrefix}telegram_update");
        $this->dropTable("{$tablePrefix}poll_answer");
        $this->dropTable("{$tablePrefix}poll");
        $this->dropTable("{$tablePrefix}pre_checkout_query");
        $this->dropTable("{$tablePrefix}shipping_query");
        $this->dropTable("{$tablePrefix}callback_query");
        $this->dropTable("{$tablePrefix}edited_message");
        $this->dropTable("{$tablePrefix}message");
        $this->dropTable("{$tablePrefix}chosen_inline_result");
        $this->dropTable("{$tablePrefix}inline_query");
        $this->dropTable("{$tablePrefix}user_chat");
        $this->dropTable("{$tablePrefix}chat");
        $this->dropTable("{$tablePrefix}user");

        if ($this->db->driverName === 'pgsql') {
            $this->getDb()->createCommand("DROP SCHEMA telegram;")->execute();
        }

        return true;
    }
}
