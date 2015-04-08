<?php namespace ctmh\PropertyManager\Components;

use DB;
use Redirect;
use Pagination;
use Input;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use ctmh\PropertyManager\Models\Property as PropertyResult;
use ctmh\PropertyManager\Models\Settings;
use ctmh\PropertyManager\Classes\Geocoder;
use ctmh\PropertyManager\Classes\ParamProcessor;

class ListProperties extends ComponentBase
{
    /**
     * A collection of posts to display
     * @var Collection
     */
    public $properties;

    /**
     * Parameter to use for the page number
     * @var string
     */
    public $pageParam;

    /**
     * Parameter to geocode by location
     * @var string
     */
    public $location;
    
    /**
     * Parameter to set the type
     * @var string
     */
    public $type;
    
    /**
     * Parameter to set the radius
     * @var string
     */
    public $radius;
    
    /**
     * Parameter to set the min-price
     * @var string
     */
    public $minPrice;
    
    /**
     * Parameter to set the max-price
     * @var string
     */
    public $maxPrice;

    /**
     * Message to display when there are no messages.
     * @var string
     */
    public $noPropertiesMessage;

    /**
     * Reference to the page name for linking to properties.
     * @var string
     */
    public $propertyPage;

    /**
     * If the post list should be ordered by another attribute.
     * @var string
     */
    public $sortOrder;

    public function componentDetails()
    {
        return [
            'name'        => 'Property List',
            'description' => 'Property list page including search variables'
        ];
    }

    public function defineProperties()
    {
        return [
            'postsPerPage' => [
                'title'             => 'Number of posts to show per page',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'Please enter a valid page number!',
                'default'           => '25',
            ],
            'noPropertiesMessage' => [
                'title'        => 'No properties',
                'description'  => 'No properties available message',
                'type'         => 'string',
                'default'      => 'No properties found',
                'showExternalParam' => false
            ],
            'propertyPage' => [
                'title'       => 'Property',
                'description' => 'Property link',
                'type'        => 'dropdown',
                'default'     => 'propertymanager/property',
                'group'       => 'Links',
            ],
        ];
    }

    public function onRun()
    {
		
		$this->page['properties'] = $this->listFiltered();

        /*
         * If the page number is not valid, redirect
         */
        /*if ($pageNumberParam = $this->paramName('pageNumber')) {
            $currentPage = $this->property('pageNumber');

            if ($currentPage > ($lastPage = $this->properties->lastPage()) && $currentPage > 1)
                return Redirect::to($this->currentPageUrl([$pageNumberParam => $lastPage]));
        }*/
    }

    protected function prepareVars()
    {
        $this->pageParam = $this->page['pageParam'] = $this->paramName('pageNumber');
        $this->noPropertiesMessage = $this->page['noPropertiesMessage'] = $this->property('noPropertiesMessage');

        /*
         * Page links
         */
        $this->propertyPage = $this->page['propertyPage'] = $this->property('propertyPage');
    }

    protected function listFiltered()
    {
	    
	    /* no properties message */
	    
	    $this->page['noPropertiesMessage'] = $this->property('noPropertiesMessage');
	    
	    /* locale will be set by a dropdown on the settings page */
	    
	    setlocale(LC_MONETARY, 'en_GB');
	    
	    /* api key from settings */
	    
	    $settings = Settings::instance();
        if (!$settings->geocode_api_key)
            throw new ApplicationException('Google geocoder API key is not configured.');
	    
	    $apiKey = Settings::instance()->geocode_api_key;
	    
	    /* params and defaults */
	    
	    $paramProcessor = new ParamProcessor;
	    $params = $paramProcessor->propertyParams();
	       
        /*
         * List all the properties that match the search criteria
         */
        
        $properties = $paramProcessor->queryBuilder($params, $apiKey);
        
        return $properties;
    }

}