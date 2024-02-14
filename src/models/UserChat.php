<?php
namespace onix\telegram\models;

use yii\db\ActiveQuery;
use yii\mongodb\ActiveRecord;

/**
 * This is the model class for table "telegram.user_chat".
 *
 * @property int $userId Unique user identifier
 * @property int $chatId Unique user or chat identifier
 */
class UserChat extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function collectionName(): array|string
    {
        return 'telegram_user_chat';
    }

    public function attributes(): array
    {
        return ['_id', 'userId', 'chatId'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['userId', 'chatId'], 'required'],
            [['userId', 'chatId'], 'integer'],
            [['userId', 'chatId'], 'unique', 'targetAttribute' => ['userId', 'chatId']],
            [
                ['chatId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Chat::class,
                'targetAttribute' => ['chatId' => '_id']
            ],
            [
                ['userId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['userId' => '_id']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     * @return UserChatQuery the active query used by this AR class.
     */
    public static function find(): UserChatQuery
    {
        return new UserChatQuery(get_called_class());
    }

    /**
     * @throws \Exception
     */
    public static function checkIndices(): void
    {
        $coll = self::getCollection();
        $idxs = $coll->listIndexes();

        $inames = [];
        foreach ($idxs as $idx) {
            $inames[$idx['name']] = 1;
        }

        if (empty($inames['_userId_chatId'])) {
            $coll->createIndex(['userId', 'chatId'], ['unique' => true, 'name' => '_userId_chatId']);
        }
    }
}
