<?php namespace ctmh\PropertyManager\Classes;

use Cache;
use stdClass;

/**
 * Google geocode wrapper
 */
class Geocoder
{

    /* standard geocoder, utilising google, with caching */

    public function fetchLatLng($location, $apiKey)
    {
	    
	    /* configure cache and location variables */
	    
	    $name = 'ctmh_propertymanager_google_';
			
		$expiry = 60 * 60 * 24 * 14;
		
		$location = urlencode($location);
			
		$cacheName = $name.preg_replace("/[^a-zA-Z0-9]+/", "", $location);
		
		/* configure endpoint */
		
		$endpoint = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$location.'&key='.$apiKey;
		
		$geocode = true;
		
		if (Cache::has($cacheName))
		{
		    $result = Cache::get($cacheName);
		    
		 } else
		{
			/* fetch */
			
			$ch = curl_init($endpoint);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			
			$result = curl_exec($ch);
			
			curl_close($ch);
			
			$checkResult = json_decode($result);
			
			if ( $checkResult->status == 'OK' )
			{
				/* commit to cache with cache key */
				
				$checkResult->cache = true;
				
				$checkResult = json_encode($checkResult);
				
				Cache::put($cacheName, $checkResult, $expiry);
				
			} else 
			{
				$geocode = false;
			}
			
		}
		
		$result = ( $geocode ) ? json_decode($result) : false;
		
		return $result;
	    
    }
    
    public function searchBounding($latitude, $longitude, $searchRadius)
    {
	    /* radius of the earth in miles */
	    
	    $R = 3959;
	    
	    $response = new stdClass();
	     
	    /* first-cut bounding box (in degrees) */
	    $response->maxLat = $latitude + rad2deg($searchRadius/$R);
	    $response->minLat = $latitude - rad2deg($searchRadius/$R);
	    
	    /* compensate for degrees longitude getting smaller with increasing latitude */
	    $response->maxLon = $longitude + rad2deg($searchRadius/$R/cos(deg2rad($latitude)));
	    $response->minLon = $longitude - rad2deg($searchRadius/$R/cos(deg2rad($latitude)));
	    
	    return $response;
    }
}