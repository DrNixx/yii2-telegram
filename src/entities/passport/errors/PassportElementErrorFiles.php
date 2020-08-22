<?php
namespace onix\telegram\entities\passport\errors;

use yii\helpers\ArrayHelper;

/**
 * Class PassportElementErrorFiles
 *
 * Represents an issue with a list of scans. The error is considered resolved when the list
 * of files containing the scans changes.
 *
 * @link https://core.telegram.org/bots/api#passportelementerrorfiles
 *
 * @property-read string[] $fileHashes List of base64-encoded file hashes
 */
class PassportElementErrorFiles extends ErrorEntity
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return ArrayHelper::merge(
            parent::attributes(),
            ['fileHashes']
        );
    }
    
    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        $this->setAttribute('source', 'files');
    }
}
