<?php
namespace onix\telegram\entities\passport\errors;

/**
 * Class PassportElementErrorSelfie
 *
 * Represents an issue with the selfie with a document. The error is considered resolved when
 * the file with the selfie changes.
 *
 * @link https://core.telegram.org/bots/api#passportelementerrorselfie
 */
class PassportElementErrorSelfie extends PassportElementErrorFile
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        $this->setAttribute('source', 'selfie');
    }
}
