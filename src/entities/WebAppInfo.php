<?php

namespace onix\telegram\entities;

/**
 * Class WebAppInfo
 *
 * @link https://core.telegram.org/bots/api#webappinfo
 *
 * @property string $url An HTTPS URL of a Web App to be opened with additional data as specified in Initializing Web Apps
 */
class WebAppInfo extends Entity
{
    public function attributes(): array
    {
        return ['url'];
    }
}