<?php

declare(strict_types=1);

class CapabilityThermostatController
{
    const capabilityPrefix = 'ThermostatController';
    const DATE_TIME_FORMAT = 'o-m-d\TH:i:s\Z';

    use HelperCapabilityDiscovery;
    use HelperFloatDevice;

    private static function computeProperties($configuration)
    {
        if (IPS_VariableExists($configuration[self::capabilityPrefix . 'ID'])) {
            return [
                [
                    'namespace'                 => 'Alexa.ThermostatController',
                    'name'                      => 'targetSetpoint',
                    'value'                     => [
                        'value' => floatval(self::getFloatValue($configuration[self::capabilityPrefix . 'ID'])),
                        'scale' => 'CELSIUS'
                    ],
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
                'label' => 'VariableID',
                'name'  => self::capabilityPrefix . 'ID',
                'width' => '150px',
                'add'   => 0,
                'edit'  => [
                    'type' => 'SelectVariable'
                ]
            ]
        ];
    }

    public static function getStatus($configuration)
    {
        return self::getGetFloatCompatibility($configuration[self::capabilityPrefix . 'ID']);
    }

    public static function doDirective($configuration, $directive, $data)
    {
        $colorDevice = function ($configuration, $value) {
            if (self::setFloatValue($configuration[self::capabilityPrefix . 'ID'], $value)) {
                $i = 0;
                while (($value != self::getFloatValue($configuration[self::capabilityPrefix . 'ID'])) && $i < 10) {
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

            case 'SetTargetTemperature':
                return $colorDevice($configuration, $data['targetSetpoint']['value']);

            case 'AdjustTargetTemperature':
                return $colorDevice($configuration, self::getFloatValue($configuration[self::capabilityPrefix . 'ID']) + $data['targetSetpointDelta']['value']);

            default:
                throw new Exception('Command is not supported by this trait!');
        }
    }

    public static function supportedDirectives()
    {
        return [
            'ReportState',
            'SetTargetTemperature',
            'AdjustTargetTemperature'
        ];
    }

    public static function supportedCapabilities()
    {
        return [
            'Alexa.ThermostatController'
        ];
    }

    public static function supportedProperties($realCapability)
    {
        return [
            'targetSetpoint'
        ];
    }
}