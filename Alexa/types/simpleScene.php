<?php

declare(strict_types=1);

class DeviceTypeSimpleScene
{
    private static $implementedCapabilities = [
        'SceneController'
    ];

    private static $displayedCategories = [
        'SCENE_TRIGGER'
    ];

    private static $displayStatusPrefix = false;
    private static $skipMissingStatus = false;
    private static $expertDevice = true;

    use HelperDeviceType;

    public static function getPosition()
    {
        return 100;
    }

    public static function getCaption()
    {
        return 'Scenes';
    }

    public static function getTranslations()
    {
        return [
            'de' => [
                'Scenes' => 'Szenen',
                'Action' => 'Aktion'
            ]
        ];
    }
}

DeviceTypeRegistry::register('SimpleScene');
