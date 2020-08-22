<?php
namespace onix\telegram\entities\passport\errors;

/**
 * Class PassportElementErrorTranslationFile
 *
 * Represents an issue with one of the files that constitute the translation of a document.
 * The error is considered resolved when the file changes.
 *
 * @link https://core.telegram.org/bots/api#passportelementerrortranslationfile
 */
class PassportElementErrorTranslationFile extends PassportElementErrorFile
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        $this->setAttribute('source', 'translation_file');
    }
}
