<?php
namespace onix\telegram\entities\passport\errors;

/**
 * Class PassportElementErrorTranslationFiles
 *
 * Represents an issue with a list of scans. The error is considered resolved when the list
 * of files containing the scans changes.
 *
 * @link https://core.telegram.org/bots/api#passportelementerrortranslationfiles
 */
class PassportElementErrorTranslationFiles extends PassportElementErrorFiles
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        $this->setAttribute('source', 'translation_files');
    }
}
