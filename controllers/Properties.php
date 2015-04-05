<?php namespace ctmh\PropertyManager\Controllers;

use Flash;
use Redirect;
use BackendMenu;
use Backend\Classes\Controller;
use ApplicationException;
use ctmh\PropertyManager\Models\Property;
use ctmh\PropertyManager\Models\Settings;

class Properties extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public $bodyClass = 'compact-container';

    public $requiredPermissions = ['ctmh.propertymanager.access_properties'];
	
	public function __construct()
    {
	    
	    $settings = Settings::instance();
		if (!$settings->maps_api_key)
	            throw new ApplicationException('Google Maps v3 API key is not configured. Please add this in the settings menu before creating any properties.');
	    
        parent::__construct();

        BackendMenu::setContext('ctmh.PropertyManager', 'propertymanager', 'properties');
        $this->addCss('/plugins/ctmh/propertymanager/assets/css/rainlab.blog-preview.css');
        $this->addCss('/plugins/ctmh/propertymanager/assets/css/rainlab.blog-preview-theme-default.css');
        $this->addCss('/plugins/ctmh/propertymanager/assets/css/properties.css');

        $this->addCss('/plugins/ctmh/propertymanager/assets/vendor/prettify/prettify.css');
        $this->addCss('/plugins/ctmh/propertymanager/assets/vendor/prettify/theme-desert.css');

        $this->addJs('/plugins/ctmh/propertymanager/assets/js/post-form.js');
        $this->addJs('/plugins/ctmh/propertymanager/assets/vendor/prettify/prettify.js');
        
        /* add maps JS */
        $this->addJs('https://maps.googleapis.com/maps/api/js?key='.$settings->maps_api_key);
    }

    public function index()
    {
        $this->vars['propertiesTotal'] = Property::count();
        $this->vars['propertiesPublished'] = Property::isPublished()->count();
        $this->vars['propertiesDrafts'] = $this->vars['propertiesTotal'] - $this->vars['propertiesPublished'];

        $this->asExtension('ListController')->index();
    }

    public function index_onDelete()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {

            foreach ($checkedIds as $propertyId) {
                if ((!$property = Property::find($propertyId)) || !$property->canEdit($this->user))
                    continue;

                $property->delete();
            }

            Flash::success('Successfully deleted those properties.');
        }

        return $this->listRefresh();
    }

    /**
     * {@inheritDoc}
     */
    public function listInjectRowClass($record, $definition = null)
    {
        if (!$record->published)
            return 'safe disabled';
    }

    public function formBeforeCreate($model)
    {
        $model->user_id = $this->user->id;
    }

    public function onRefreshPreview()
    {
        $data = post('Property');

        $previewHtml = Property::formatHtml($data['content'], true);

        return [
            'preview' => $previewHtml
        ];
    }

}