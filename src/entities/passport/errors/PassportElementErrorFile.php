<?php
namespace onix\telegram\entities\passport\errors;

use yii\helpers\ArrayHelper;

/**
 * Class PassportElementErrorFile
 *
 * Represents an issue with a document scan. The error is considered resolved when the file
 * with the document scan changes.
 *
 * @link https://core.telegram.org/bots/api#passportelementerrorfile
 *
 * @property-read string $fileHash Base64-encoded file hash
 */
class PassportElementErrorFile extends ErrorEntity
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return ArrayHelper::merge(
            parent::attributes(),
            ['fileHash']
        );
    }
    
    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        $this->setAttribute('source', 'file');
    }
}
