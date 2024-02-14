<?php
namespace onix\telegram\entities\passport\errors;

use yii\helpers\ArrayHelper;

/**
 * Class PassportElementErrorUnspecified
 *
 * Represents an issue in an unspecified place. The error is considered resolved when new data is added.
 *
 * @link https://core.telegram.org/bots/api#passportelementerrorunspecified
 *
 * @property-read string $elementHash Base64-encoded element hash
 */
class PassportElementErrorUnspecified extends ErrorEntity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ArrayHelper::merge(
            parent::attributes(),
            ['elementHash']
        );
    }
    
    /**
     * PassportElementErrorUnspecified constructor
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config['source'] = 'unspecified';
        parent::__construct($config);
    }
}
