<?php namespace ctmh\PropertyManager\Classes;

use stdClass;
use Input;
use ctmh\PropertyManager\Classes\Geocoder;
use ctmh\PropertyManager\Models\Property as PropertyResult;

/**
 * Param processor and defaults
 */
class ParamProcessor
{
	
	/* standard types */
	
	private $config = [
		'type' => [
			'rent' => true,
			'buy' => true
		],
		'radius' => [
			200 => true,
			150 => true,
			100 => true,
			75 => true,
			50 => true,
			25 => true,
			20 => true,
			15 => true,
			10 => true,
			5 => true,
			1 => true,
		],
		'prices' => [
			'buy' => [
				50000 => true,
				100000 => true,
				150000 => true,
				200000 => true,
				250000 => true,
				300000 => true,
				350000 => true,
				400000 => true,
				450000 => true,
				500000 => true,
				555000 => true,
				600000 => true,
				650000 => true,
				700000 => true,
				750000 => true,
				800000 => true,
				850000 => true,
				850000 => true,
				900000 => true,
				950000 => true,
				1000000 => true,
				1250000 => true,
				1500000 => true,
				1750000 => true,
				2000000 => true,
				5000000 => true
			],
			'rent' => [
				300 => true,
				350 => true,
				400 => true,
				450 => true,
				500 => true,
				600 => true,
				700 => true,
				800 => true,
				900 => true,
				1000 => true,
				1250 => true,
				1500 => true,
				1750 => true,
				2000 => true,
				5000 => true,
				10000 => true
			]
		]
	];

    public function propertyParams()
    {
	    
	    $response = new stdClass();
	    
	    $response->type = Input::get('type', 'buy');
	    $response->location = Input::get('location', false);
	    $response->radius = Input::get('radius', 50);
	    $response->minPrice = Input::get('minPrice', 0);
	    $response->maxPrice = Input::get('maxPrice', false);
	    $response->sort = Input::get('sort');
	    $response->page = Input::get('page', 1);
	    
	    return $response;
	    
    }
    
    public function queryBuilder($params, $apiKey)
    {
	    
	    /* base query is always all properties that are published */
	    
	    $query = PropertyResult::where('published', true);
	    
	    /* type */
	    
	    $type = ( isset( $this->config['type'][$params->type] ) ) ? $params->type : 'buy';
	    
	    $query = $query->where('type', $type);
	    
	    /* price bracket */
	    
	    $minPrice = ( isset( $this->config['prices'][$type][$params->minPrice] ) ) ? $params->minPrice : 0;
	    
	    $maxPrice = ( isset( $this->config['prices'][$type][$params->maxPrice] ) ) ? $params->maxPrice : 99999999;
	    
	    $query = $query->where('price', '>=', $minPrice)
	    				->where('price', '<=', $maxPrice);
	    
	    /* location if specified in the search */
	    	    
	    if ( $params->location )
	    {
		    
		    /* location for the search */
		    
			$geocoder = new Geocoder;
			
			$response = $geocoder->fetchLatLng($params->location, $apiKey);
			
			/* radius for the search in miles */	
				
			$radius = ( isset( $this->config['radius'][$params->radius] ) ) ? $params->radius : false;
			
			if ( $response && $radius )
			{
				$latitude = $response->results[0]->geometry->location->lat;
				$longitude = $response->results[0]->geometry->location->lng;
				
				/* fetch bounds */
				
				$bounds = $geocoder->searchBounding($latitude, $longitude, $radius);
				
				$query = $query->whereBetween('latitude', array($bounds->minLat, $bounds->maxLat))
								->whereBetween('longitude', array($bounds->minLon, $bounds->maxLon));
			}
	    
	    }
	    
	    /* always paginate */
	    
	    $query = $query->paginate(25);
	    
	    return $query;
	    
    }
}