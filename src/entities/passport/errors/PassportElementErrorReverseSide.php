<?php
namespace onix\telegram\entities\passport\errors;

/**
 * Class PassportElementErrorReverseSide
 *
 * Represents an issue with the reverse side of a document. The error is considered resolved when the file
 * with reverse side of the document changes.
 *
 * @link https://core.telegram.org/bots/api#passportelementerrorreverseside
 */
class PassportElementErrorReverseSide extends PassportElementErrorFile
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        $this->setAttribute('source', 'reverse_side');
    }
}
