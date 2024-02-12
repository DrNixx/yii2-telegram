<?php

namespace onix\telegram\models;

use MongoDB\BSON\UTCDateTime;
use onix\telegram\entities\User as UserEntity;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "telegram.user".
 *
 * @property int $_id Unique identifier for this user or bot
 * @property bool $isBot True, if this user is a bot
 * @property int|null $userId Identifier for chess user
 * @property string $firstName User's or bot's first name
 * @property string|null $lastName User's or bot's last name
 * @property string|null $username User's or bot's last username
 * @property string|null $languageCode IETF language tag of the user's language
 */
class User extends TelegramActiveRecord
{
    protected ?string $entityClass = UserEntity::class;

    protected array $ownAttributes = ['userId'];

    /**
     * {@inheritdoc}
     */
    public static function collectionName(): array|string
    {
        return 'telegram_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return ArrayHelper::merge(parent::rules(), [
            [['userId'], 'integer'],
            [['isBot'], 'boolean'],
            [['firstName', 'lastName'], 'string', 'max' => 255],
            [['username'], 'string', 'max' => 191],
            [['languageCode'], 'string', 'max' => 10],
        ]);
    }

    /**
     * {@inheritdoc}
     * @return UserQuery the active query used by this AR class.
     */
    public static function find($alias = null): UserQuery
    {
        return new UserQuery(get_called_class());
    }
}
