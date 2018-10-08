<?php

declare(strict_types=1);

class CapabilityPercentageController
{
    const capabilityPrefix = 'PercentageController';
    const DATE_TIME_FORMAT = 'o-m-d\TH:i:s\Z';

    use HelperCapabilityDiscovery;
    use HelperDimDevice;

    private static function computeProperties($configuration)
    {
        if (IPS_VariableExists($configuration[self::capabilityPrefix . 'ID'])) {
            return [
                [
                    'namespace'                 => 'Alexa.PowerController',
                    'name'                      => 'powerState',
                    'value'                     => (self::getDimValue($configuration[self::capabilityPrefix . 'ID']) > 0 ? 'ON' : 'OFF'),
                    'timeOfSample'              => gmdate(self::DATE_TIME_FORMAT),
                    'uncertaintyInMilliseconds' => 0
                ],
                [
                    'namespace'                 => 'Alexa.PercentageController',
                    'name'                      => 'percentage',
                    'value'                     => self::getDimValue($configuration[self::capabilityPrefix . 'ID']),
                    'timeOfSample'              => gmdate(self::DATE_TIME_FORMAT),
                    'uncertaintyInMilliseconds' => 0
                ]
            ];
        } else {
            return [];
        }
    }

    public static function getColumns()
    {
        return [
            [
                'label' => 'Variable',
                'name'  => self::capabilityPrefix . 'ID',
                'width' => '250px',
                'add'   => 0,
                'edit'  => [
                    'type' => 'SelectVariable'
                ]
            ]
        ];
    }

    public static function getStatus($configuration)
    {
        return self::getDimCompatibility($configuration[self::capabilityPrefix . 'ID']);
    }

    public static function doDirective($configuration, $directive, $payload)
    {
        $setDimValue = function ($configuration, $value) {
            if (self::dimDevice($configuration[self::capabilityPrefix . 'ID'], $value)) {
                $i = 0;
                while (($value != self::getDimValue($configuration[self::capabilityPrefix . 'ID'])) && $i < 10) {
                    $i++;
                    usleep(100000);
                }
                return [
                    'properties'     => self::computeProperties($configuration),
                    'payload'        => new stdClass(),
                    'eventName'      => 'Response',
                    'eventNamespace' => 'Alexa'
                ];
            } else {
                return [
                    'payload'        => [
                        'type' => 'NO_SUCH_ENDPOINT'
                    ],
                    'eventName'      => 'ErrorResponse',
                    'eventNamespace' => 'Alexa'
                ];
            }
        };

        switch ($directive) {
            case 'ReportState':
                return [
                    'properties'     => self::computeProperties($configuration),
                    'payload'        => new stdClass(),
                    'eventName'      => 'StateReport',
                    'eventNamespace' => 'Alexa'
                ];
                break;

            case 'AdjustPercentage':
                return $setDimValue($configuration, self::getDimValue($configuration[self::capabilityPrefix . 'ID']) + $payload['percentageDelta']);

            case 'SetPercentage':
                return $setDimValue($configuration, $payload['percentage']);

            case 'TurnOn':
            case 'TurnOff':
                $value = ($directive == 'TurnOn' ? 100 : 0);
                return $setDimValue($configuration, $value);

            default:
                throw new Exception('Command is not supported by this trait!');
        }
    }

    public static function supportedDirectives()
    {
        return [
            'ReportState',
            'AdjustPercentage',
            'SetPercentage',
            'TurnOn',
            'TurnOff'
        ];
    }

    public static function supportedCapabilities()
    {
        return [
            'Alexa.PercentageController',
            'Alexa.PowerController'
        ];
    }

    public static function supportedProperties($realCapability)
    {
        switch ($realCapability) {
            case 'Alexa.PercentageController':
                return [
                    'percentage'
                ];

            case 'Alexa.PowerController':
                return [
                    'powerState'
                ];
        }
    }
}