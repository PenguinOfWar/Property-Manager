<?php namespace ctmh\PropertyManager\Models;

use October\Rain\Database\Model;

/**
 * Twitter settings model
 *
 * @package system
 * @author Alexey Bobkov, Samuel Georges
 *
 */
class Settings extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $implement = ['System.Behaviors.SettingsModel'];

    public $settingsCode = 'ctmh_propertymanager_settings';

    public $settingsFields = 'fields.yaml';

    /**
     * Validation rules
     */
    public $rules = [
        'maps_api_key' => 'required',
        'geocode_api_key' => 'required',
        'currency' => 'required'
    ];
}