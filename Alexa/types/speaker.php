<?php

declare(strict_types=1);

class DeviceTypeSpeaker
{
    private static $implementedCapabilities = [
        'Speaker'
    ];

    private static $displayedCategories = [
        'SPEAKER'
    ];

    use HelperDeviceType;

    public static function getPosition()
    {
        return 6;
    }

    public static function getCaption()
    {
        return 'Speaker';
    }

    public static function getTranslations()
    {
        return [
            'de' => [
                'Speaker'  => 'Lautsprecher',
                'Variable' => 'Variable'
            ]
        ];
    }
}

DeviceTypeRegistry::register('Speaker');