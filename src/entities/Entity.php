<?php

namespace onix\telegram\entities;

use onix\telegram\Telegram;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\UnknownPropertyException;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * Class Entity
 *
 * This is the base class for all entities.
 *
 * @link https://core.telegram.org/bots/api#available-types
 *
 * @property-read Telegram $telegram
 */
abstract class Entity extends Model implements \JsonSerializable
{
    /**
     * @var array attribute values indexed by attribute names
     */
    private $attributes = [];

    /**
     * Entity constructor.
     *
     * @param array  $config
     */
    public function __construct($config)
    {
        parent::__construct($config);
    }

    /**
     * @return Telegram
     *
     * @throws InvalidConfigException
     */
    public function getTelegram(): Telegram
    {
        return \Yii::$app->get('telegram');
    }

    /**
     * Returns the list of attribute names.
     * @return string[] list of attribute names.
     */
    public function attributes(): array
    {
        return [];
    }

    /**
     * Returns a value indicating whether the model has an attribute with the specified name.
     * @param string $name the name of the attribute
     * @return bool whether the model has an attribute with the specified name.
     */
    public function hasAttribute(string $name): bool
    {
        return isset($this->attributes[$name]) || in_array($name, $this->attributes(), true);
    }

    /**
     * Translates a camel case string into a string with
     * underscores (e.g. firstName -> first_name)
     *
     * @param string $str String in camel case format
     * @return string $str Translated into underscore format
     */
    protected function fromCamelCase(string $str): string
    {
        $str[0] = strtolower($str[0]);
        return preg_replace_callback('/([A-Z])/', function ($c) {
            return "_" . strtolower($c[1]);
        }, $str);
    }

    /**
     * Translates a string with underscores
     * into camel case (e.g. first_name -> firstName)
     *
     * @param string $str String in underscore format
     * @param bool $capitalise_first_char If true, capitalise the first char in $str
     * @return string $str translated into camel caps
     */
    protected function toCamelCase(string $str, bool $capitalise_first_char = false): string
    {
        if ($capitalise_first_char) {
            $str[0] = strtoupper($str[0]);
        }

        return preg_replace_callback('/_([a-z])/', function ($c) {
            return strtoupper($c[1]);
        }, $str);
    }

    /**
     * Return true if attribute is subEntity
     * @param string $name
     * @return bool
     */
    public function isSubEntity(string $name): bool
    {
        $sub_entities = $this->subEntities();
        return isset($sub_entities[$name]);
    }

    /**
     * PHP getter magic method.
     * This method is overridden so that attributes and related objects can be accessed like properties.
     *
     * @param string $name property name
     *
     * @return mixed property value
     *
     * @throws UnknownPropertyException
     *
     * @see getAttribute()
     */
    public function __get($name)
    {
        $propName = $this->toCamelCase($name);
        if (isset($this->attributes[$propName]) || array_key_exists($propName, $this->attributes)) {
            $name = $propName;
            $value = $this->attributes[$name];
        } else {
            if ($this->hasAttribute($propName)) {
                return null;
            }

            $value = parent::__get($name);
        }

        if ($value !== null) {
            //Get all sub-Entities of the current Entity
            $sub_entities = $this->subEntities();

            if (isset($sub_entities[$name])) {
                $class = $sub_entities[$name];

                if (is_array($class)) {
                    $class = reset($class);
                    $objects = [];
                    if (is_array($value)) {
                        foreach ($value as $param) {
                            $objects[] = Factory::resolveEntityClass($class, $param);
                        }
                    }

                    return $objects;
                }

                return Factory::resolveEntityClass($class, $value);
            }
        }

        return $value;
    }

    /**
     * PHP setter magic method.
     *
     * @param string $name property name
     * @param mixed $value property value
     *
     * @throws UnknownPropertyException
     */
    public function __set($name, $value)
    {
        $propName = $this->toCamelCase($name);
        if ($this->hasAttribute($propName)) {
            $this->attributes[$propName] = $value;
        } else {
            try {
                parent::__set($name, $value);
            } catch (UnknownPropertyException $e) {
                if (YII_DEBUG) {
                    throw $e;
                } else {
                    \Yii::warning($e->getMessage());
                }

            }
        }
    }

    /**
     * Checks if a property value is null.
     * This method overrides the parent implementation by checking if the named attribute is `null` or not.
     * @param string $name the property name or the event name
     *
     * @return bool whether the property value is null
     */
    public function __isset($name)
    {
        try {
            return $this->__get($name) !== null;
        } catch (\Throwable|\Exception) {
            return false;
        }
    }

    /**
     * Sets a component property to be null.
     * This method overrides the parent implementation by clearing
     * the specified attribute value.
     * @param string $name the property name or the event name
     */
    public function __unset($name)
    {
        if ($this->hasAttribute($name)) {
            unset($this->attributes[$name]);
        } else {
            parent::__unset($name);
        }
    }

    /**
     * Returns the named attribute value.
     * If this record is the result of a query and the attribute is not loaded, `null` will be returned.
     *
     * @param string $name the attribute name
     * @return mixed the attribute value. `null` if the attribute is not set or does not exist.
     *
     * @see hasAttribute()
     */
    public function getAttribute(string $name): mixed
    {
        return $this->attributes[$name] ?? null;
    }

    /**
     * Sets the named attribute value.
     *
     * @param string $name the attribute name
     * @param mixed $value the attribute value.
     *
     * @throws InvalidArgumentException if the named attribute does not exist.
     *
     * @see hasAttribute()
     */
    public function setAttribute(string $name, mixed $value): void
    {
        if ($this->hasAttribute($name)) {
            $this->attributes[$name] = $value;
        } else {
            throw new InvalidArgumentException(get_class($this) . ' has no attribute named "' . $name . '".');
        }
    }

    public static function serializeValue($value)
    {
        if ($value instanceof Entity) {
            return $value->jsonSerialize();
        } elseif (is_array($value)) {
            $newValues = [];
            foreach ($value as $k => $item) {
                $newValues[$k] = self::serializeValue($item);
            }

            return $newValues;
        }

        return $value;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        $data = [];
        foreach ($this->attributes as $key => $value) {
            $data[$this->fromCamelCase($key)] = self::serializeValue($value);
        }

        return $data;
    }

    /**
     * Perform to json
     *
     * @return string
     */
    public function toJson(): string
    {
        return Json::encode($this);
    }

    /**
     * Perform to string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * Helper to set member variables
     *
     * @param array $data
     */
    protected function assignMemberVariables(array $data): void
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Get the list of the properties that are themselves Entities
     *
     * @return array
     */
    protected function subEntities(): array
    {
        return [];
    }

    /**
     * Escape markdown (v1) special characters
     *
     * @see https://core.telegram.org/bots/api#markdown-style
     *
     * @param string $string
     *
     * @return string
     */
    public static function escapeMarkdown(string $string): string
    {
        return str_replace(
            ['[', '`', '*', '_',],
            ['\[', '\`', '\*', '\_',],
            $string
        );
    }

    /**
     * Escape markdown (v2) special characters
     *
     * @see https://core.telegram.org/bots/api#markdownv2-style
     *
     * @param string $string
     *
     * @return string
     */
    public static function escapeMarkdownV2(string $string): string
    {
        return str_replace(
            [
                '_',
                '*',
                '[',
                ']',
                '(',
                ')',
                '~',
                '`',
                '>',
                '#',
                '+',
                '-',
                '=',
                '|',
                '{',
                '}',
                '.',
                '!'
            ],
            [
                '\_',
                '\*',
                '\[',
                '\]',
                '\(',
                '\)',
                '\~',
                '\`',
                '\>',
                '\#',
                '\+',
                '\-',
                '\=',
                '\|',
                '\{',
                '\}',
                '\.',
                '\!'
            ],
            $string
        );
    }

    public static function emoji($utf8emoji) {
        /** @noinspection RegExpRedundantEscape */
        preg_replace_callback(
            '@\\\x([0-9a-fA-F]{2})@x',
            function ($captures) {
                return chr(hexdec($captures[1]));
            },
            $utf8emoji
        );

        return $utf8emoji;
    }

    /**
     * Try to mention the user
     *
     * Mention the user with the username otherwise print first and last name
     * if the $escape_markdown argument is true special characters are escaped from the output
     *
     * @param bool $escape_markdown
     *
     * @return string|null
     *@todo What about MarkdownV2?
     *
     */
    public function tryMention(bool $escape_markdown = false): ?string
    {
        //TryMention only makes sense for the User and Chat entity.
        if (!($this instanceof User || $this instanceof Chat)) {
            return null;
        }

        //Try with the username first...
        $name = $this->hasAttribute('username') ? $this->username : null;
        $is_username = $name !== null;

        if ($name === null) {
            //...otherwise try with the names.
            $name      = $this->hasAttribute('firstName') ? $this->firstName : null;
            $last_name = $this->hasAttribute('lastName') ? $this->lastName : null;
            if ($last_name !== null) {
                $name .= ' ' . $last_name;
            }
        }

        if ($escape_markdown) {
            $name = self::escapeMarkdown($name);
        }

        return ($is_username ? '@' : '') . $name;
    }

    public static function getScalarAttributes(array $base, array $excludes = []): array
    {
        $obj = new static([]);
        $all = $obj->attributes();
        // $sub = array_keys($obj->subEntities());
        // $own = array_filter(array_diff($all, $sub), function ($v) { return $v !== 'id'; });
        $own = array_diff($all, $excludes);
        return ArrayHelper::merge($base, $own);
    }
}
