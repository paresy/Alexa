<?php

declare(strict_types=1);

class DeviceTypeTelevision
{
    use HelperDeviceType;
    private static $implementedCapabilities = [
        'PowerController',
        'ChannelController',
        'SpeakerMuteable',
        'InputController'
    ];

    private static $displayedCategories = [
        'TV'
    ];

    private static $displayStatusPrefix = true;
    private static $skipMissingStatus = true;
    private static $columnWidth = '150px';
    private static $expertDevice = true;

    public static function getPosition()
    {
        return 38;
    }

    public static function getCaption()
    {
        return 'Television';
    }

    public static function getTranslations()
    {
        return [
            'de' => [
                'Television'        => 'Fernsehgerät',
                'Switch Variable'   => 'Schaltervariable',
                'Volume Variable'   => 'Lautstärkevariable',
                'Mute Variable'     => 'Stummvariable',
                'Channel Variable' 	=> 'Kanalvariable',
                'Input Variable'    => 'Eingangsvariable',
                'Supported Inputs'  => 'Unterstützte Eingänge'
            ]
        ];
    }
}

DeviceTypeRegistry::register('Television');
