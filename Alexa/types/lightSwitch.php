<?php

declare(strict_types=1);

class DeviceTypeLightSwitch
{
    private static $implementedCapabilities = [
        'PowerController'
    ];

    private static $displayedCategories = [
        'LIGHT'
    ];

    private static $displayStatusPrefix = false;

    use HelperDeviceType;

    public static function getPosition()
    {
        return 0;
    }

    public static function getCaption()
    {
        return 'Light (Switch)';
    }

    public static function getTranslations()
    {
        return [
            'de' => [
                'Light (Switch)'  => 'Licht (Schalter)',
                'Switch Variable' => 'Schaltervariable'
            ]
        ];
    }
}

DeviceTypeRegistry::register('LightSwitch');
