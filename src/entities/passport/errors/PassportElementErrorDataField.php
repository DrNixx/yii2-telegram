<?php
namespace onix\telegram\entities\passport\errors;

use yii\helpers\ArrayHelper;

/**
 * Class PassportElementErrorDataField
 *
 * Represents an issue in one of the data fields that was provided by the user.
 * The error is considered resolved when the field's value changes.
 *
 * @link https://core.telegram.org/bots/api#passportelementerrordatafield
 *
 * @property-read string $fieldName Name of the data field which has the error
 * @property-read string $dataHash Base64-encoded data hash
 */
class PassportElementErrorDataField extends ErrorEntity
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return ArrayHelper::merge(
            parent::attributes(),
            ['fieldName', 'dataHash']
        );
    }
    
    /**
     * PassportElementErrorDataField constructor
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config['source'] = 'data';
        parent::__construct($config);
    }
}
