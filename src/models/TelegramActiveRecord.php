<?php

namespace onix\telegram\models;

use onix\telegram\entities\Entity;
use yii\behaviors\AttributeTypecastBehavior;
use yii\helpers\ArrayHelper;
use yii\mongodb\ActiveRecord;

/**
 * @property int $_id Unique identifier for this entity
 */
abstract class TelegramActiveRecord extends ActiveRecord
{
    protected ?string $entityClass = null;

    protected array $ownAttributes = [];

    protected array $attributeMap = ['id' => '_id'];

    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
            'typecast' => [
                'class' => AttributeTypecastBehavior::class,
            ],
        ];
    }

    public function getDirtyAttributes($names = null): array
    {
        $attributes = parent::getDirtyAttributes($names);
        $result = [];
        foreach ($attributes as $key => $value) {
            if (empty($value) && empty($this->getOldAttribute($key))) {
                continue;
            }

            $result[$key] = $value;
        }

        return $result;
    }

    function fields(): array
    {
        $return = ['_id'];
        foreach ($this->attributes as $key => $value) {
            if (($key !== '_id') && !empty($value)) {
                $return[] = $key;
            }
        }

        return $return;
    }

    public function attributes(): array
    {
        $className = $this->entityClass;
        /** @noinspection PhpUndefinedMethodInspection */
        $attributes = $className::getScalarAttributes(ArrayHelper::merge($this->ownAttributes, ['_id']));
        $result = [];
        foreach ($attributes as $attribute) {
            if (isset($this->attributeMap[$attribute])) {
                $map = $this->attributeMap[$attribute];
                if (is_array($map)) {
                    foreach ($map as $new_name) {
                        $result[] = $new_name;
                    }
                } else {
                    $result[] = $map;
                }
            } else {
                $result[] = $attribute;
            }
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['_id'], 'integer'],
            [['_id'], 'unique'],
        ];
    }

    public function assign(Entity $entity): void
    {
        foreach ($entity->attributes() as $attribute) {
            $value = $entity->$attribute;
            if (isset($this->attributeMap[$attribute])) {
                if (is_scalar($value) && is_string($this->attributeMap[$attribute])) {
                    $this->setAttribute($this->attributeMap[$attribute], $value);
                }
            } elseif ($this->hasAttribute($attribute)) {
                if (!empty($value)) {
                    $value = Entity::serializeValue($value);
                }

                $this->setAttribute($attribute, $value);
            }
        }
    }
}
