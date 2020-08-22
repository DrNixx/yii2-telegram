<?php
namespace onix\telegram\entities\passport\errors;

/**
 * Class PassportElementErrorFrontSide
 *
 * Represents an issue with the front side of a document.
 * The error is considered resolved when the file with the front side of the document changes.
 *
 * @link https://core.telegram.org/bots/api#passportelementerrorfrontside
 */
class PassportElementErrorFrontSide extends PassportElementErrorFile
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        $this->setAttribute('source', 'front_side');
    }
}
