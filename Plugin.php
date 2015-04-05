<?php namespace ctmh\PropertyManager;

/**
 * The plugin.php file (called the plugin initialization script) defines the plugin information class.
 */

use Backend;
use Controller;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{

    public function pluginDetails()
    {
        return [
            'name'        => 'Property Manager',
            'description' => 'Providers property management functionality.',
            'author'      => 'Darryl Walker',
            'icon'        => 'icon-home'
        ];
    }

    /*public function registerComponents()
    {
        return [
            '\ctmh\PropertyManager\Components\Signup' => 'mailSignup'
        ];
    }*/
    
    /* register permissions */

    public function registerPermissions()
    {
        return [
            'ctmh.propertymanager.access_properties'       => ['tab' => 'Property Manager', 'label' => 'Manage the properties']
        ];
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'Property Manager',
                'icon'        => 'icon-home',
                'description' => 'Configure Property Manager settings.',
                'class'       => 'ctmh\PropertyManager\Models\Settings',
                'order'       => 600
            ]
        ];
    }
    
    public function registerNavigation()
    {
        return [
            'propertymanager' => [
                'label'       => 'Property Manager',
                'url'         => Backend::url('ctmh/propertymanager/properties'),
                'icon'        => 'icon-home',
                'permissions' => ['ctmh.propertymanager.*'],
                'order'       => 500,
                
                'sideMenu' => [
                    'properties' => [
                        'label'       => 'All Properties',
                        'url'         => Backend::url('ctmh/propertymanager/properties'),
                        'icon'        => 'icon-pencil',
                        'permissions' => ['ctmh.propertymanager.access_properties'],
                    ]
                ]

            ]
        ];
    }

}