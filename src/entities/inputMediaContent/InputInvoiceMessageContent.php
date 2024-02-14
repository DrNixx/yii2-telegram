<?php
namespace onix\telegram\entities\inputMediaContent;

use onix\telegram\entities\Entity;
use onix\telegram\entities\payments\LabeledPrice;

/**
 * Class InputInvoiceMessageContent
 *
 * @link https://core.telegram.org/bots/api#inputinvoicemessagecontent
 *
 * @property string $title Product name, 1-32 characters
 * @property string $description Product description, 1-255 characters *
 * @property string $payload Bot-defined invoice payload, 1-128 bytes. This will not be displayed to the user,
 * use for your internal processes.
 *
 * @property string $providerToken Payment provider token, obtained via @BotFather
 * @property string $currency Three-letter ISO 4217 currency code, see more on currencies
 * @property LabeledPrice[] $prices Price breakdown, a JSON-serialized list of components (e.g. product price,
 * tax, discount, delivery cost, delivery tax, bonus, etc.)
 *
 * @property int $maxTipAmount Optional. The maximum accepted amount for tips in the smallest units of the currency
 * (integer, not float/double). For example, for a maximum tip of US$ 1.45 pass max_tip_amount = 145.
 * See the exp parameter in currencies.json, it shows the number of digits past the decimal point for each
 * currency (2 for the majority of currencies). Defaults to 0
 *
 * @property int[] $suggestedTipAmounts Optional. A JSON-serialized array of suggested amounts of tip in the smallest
 * units of the currency (integer, not float/double). At most 4 suggested tip amounts can be specified.
 * The suggested tip amounts must be positive, passed in a strictly increased order and must not exceed max_tip_amount.
 *
 * @property string $providerData Optional. A JSON-serialized object for data about the invoice, which will be shared with the payment provider. A detailed description of the required fields should be provided by the payment provider.
 * @property string $photoUrl Optional. URL of the product photo for the invoice. Can be a photo of the goods or a marketing image for a service.
 * @property int $photoSize Optional. Photo size in bytes
 * @property int $photoWidth Optional. Photo width
 * @property int $photoHeight Optional. Photo height
 * @property bool $needName Optional. Pass True if you require the user's full name to complete the order
 * @property bool $needPhoneNumber Optional. Pass True if you require the user's phone number to complete the order
 * @property bool $needEmail Optional. Pass True if you require the user's email address to complete the order
 * @property bool $needShippingAddress Optional. Pass True if you require the user's shipping address to complete the order
 * @property bool $sendPhoneNumberToProvider Optional. Pass True if the user's phone number should be sent to provider
 * @property bool $sendEmailToProvider Optional. Pass True if the user's email address should be sent to provider
 * @property bool $isFlexible Optional. Pass True if the final price depends on the shipping method
 */
class InputInvoiceMessageContent extends Entity implements InputMessageContent
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return [
            'title',
            'description',
            'payload',
            'providerToken',
            'currency',
            'prices',
            'maxTipAmount',
            'suggestedTipAmounts',
            'providerData',
            'photoUrl',
            'photoSize',
            'photoWidth',
            'photoHeight',
            'needName',
            'needPhoneNumber',
            'needEmail',
            'needShippingAddress',
            'sendPhoneNumberToProvider',
            'sendEmailToProvider',
            'isFlexible',
        ];
    }

    protected function subEntities(): array
    {
        return [
            'prices' => LabeledPrice::class
        ];
    }
}
