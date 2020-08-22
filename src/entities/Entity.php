<?php

namespace onix\telegram\entities;

use onix\telegram\Telegram;
use Exception;
use JsonSerializable;
use Yii;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\base\Model;
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
abstract class Entity extends Model implements JsonSerializable
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
    public function getTelegram()
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return Yii::$app->get('telegram');
    }

    /**
     * Returns the list of attribute names.
     * @return array list of attribute names.
     */
    public function attributes()
    {
        return [];
    }

    /**
     * Returns a value indicating whether the model has an attribute with the specified name.
     * @param string $name the name of the attribute
     * @return bool whether the model has an attribute with the specified name.
     */
    public function hasAttribute($name)
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
    protected function fromCamelCase($str)
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
    protected function toCamelCase($str, $capitalise_first_char = false)
    {
        if ($capitalise_first_char) {
            $str[0] = strtoupper($str[0]);
        }

        return preg_replace_callback('/_([a-z])/', function ($c) {
            return strtoupper($c[1]);
        }, $str);
    }

    /**
     * PHP getter magic method.
     * This method is overridden so that attributes and related objects can be accessed like properties.
     *
     * @param string $name property name
     *
     * @return mixed property value
     *
     * @see getAttribute()
     *
     * @noinspection PhpDocMissingThrowsInspection
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

            /** @noinspection PhpUnhandledExceptionInspection */
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
                            $objects[] = new $class($param);
                        }
                    }

                    return $objects;
                }

                return new $class($value);
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
     * @noinspection PhpDocMissingThrowsInspection
     */
    public function __set($name, $value)
    {
        $propName = $this->toCamelCase($name);
        if ($this->hasAttribute($propName)) {
            $this->attributes[$propName] = $value;
        } else {
            /** @noinspection PhpUnhandledExceptionInspection */
            parent::__set($name, $value);
        }
    }

    /**
     * Checks if a property value is null.
     * This method overrides the parent implementation by checking if the named attribute is `null` or not.
     * @param string $name the property name or the event name
     *
     * @return bool whether the property value is null
     *
     * @noinspection PhpWrongCatchClausesOrderInspection
     * @noinspection PhpUndefinedClassInspection
     * @noinspection PhpRedundantCatchClauseInspection
     * @noinspection PhpFullyQualifiedNameUsageInspection
     */
    public function __isset($name)
    {
        try {
            return $this->__get($name) !== null;
        } catch (\Throwable $t) {
            return false;
        } catch (Exception $e) {
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
    public function getAttribute($name)
    {
        return isset($this->attributes[$name]) ? $this->attributes[$name] : null;
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
    public function setAttribute($name, $value)
    {
        if ($this->hasAttribute($name)) {
            $this->attributes[$name] = $value;
        } else {
            throw new InvalidArgumentException(get_class($this) . ' has no attribute named "' . $name . '".');
        }
    }

    public function jsonSerialize()
    {
        $data = [];
        foreach ($this->attributes as $key => $value) {
            $data[$this->fromCamelCase($key)] = $value;
        }

        return $data;
    }

    /**
     * Perform to json
     *
     * @return string
     */
    public function toJson()
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
    protected function assignMemberVariables(array $data)
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
    protected function subEntities()
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
    public static function escapeMarkdown($string)
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
    public static function escapeMarkdownV2($string)
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

    /**
     * Try to mention the user
     *
     * Mention the user with the username otherwise print first and last name
     * if the $escape_markdown argument is true special characters are escaped from the output
     *
     * @todo What about MarkdownV2?
     *
     * @param bool $escape_markdown
     *
     * @return string|null
     */
    public function tryMention($escape_markdown = false)
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
}
