<?php

namespace onix\telegram\entities;

/**
 * Class LinkPreviewOptions
 *
 * @link https://core.telegram.org/bots/api#linkpreviewoptions
 *
 * @property-read bool $isDisabled Optional. True, if the link preview is disabled
 * @property-read string url Optional. URL to use for the link preview. If empty, then the first URL found in
 * the message text will be used
 *
 * @property-read bool preferSmallMedia Optional. True, if the media in the link preview is supposed to be shrunk;
 * ignored if the URL isn't explicitly specified or media size change isn't supported for the preview
 *
 * @property-read bool preferLargeMedia Optional. True, if the media in the link preview is supposed to be enlarged;
 * ignored if the URL isn't explicitly specified or media size change isn't supported for the preview
 *
 * @property-read bool showAboveText Optional. True, if the link preview must be shown above the message text;
 * otherwise, the link preview will be shown below the message text
 */
class LinkPreviewOptions extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ['isDisabled', 'url', 'preferSmallMedia', 'preferLargeMedia', 'showAboveText'];
    }
}
