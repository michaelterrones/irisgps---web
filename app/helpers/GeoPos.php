<?php

class GeoPos {

	public static function getAddressData($orig_lat, $orig_lon)
	{		
		$url = "http://maps.google.com/maps/api/geocode/json?latlng=$orig_lat,$orig_lon&sensor=false";
	    //$data = json_decode(file_get_contents($url), true);
	    $data = json_decode(file_get_contents($url));

	    $address = $data->results[0]->formatted_address;

	    $ai = 2;

	    $address_components = $data->results[$ai]->address_components;

	    $hasDistrict = false;
	    $hasProvince = false;
	    $hasRegion = false;

	    for ($i = 0; $i < count($address_components); $i++) {
	    	$ac = $address_components[$i];

	    	if(in_array('locality', $ac->types))
	    	{
	    		if(!$hasDistrict){
	    			$district = $ac->long_name;
	    			$hasDistrict = true;
	    		}
	    	}

	    	if(in_array('administrative_area_level_2', $ac->types))
	    	{
	    		if(!$hasProvince){
	    			$province = $ac->long_name;
	    			$hasProvince = true;
	    		}
	    	}

	    	if(in_array('administrative_area_level_1', $ac->types))
	    	{
	    		if(!$hasRegion){
	    			$region = $ac->long_name;
	    			$hasRegion = true;
	    		}
	    	}

	    	if ($i == (count($address_components)-1)) {
				if($hasDistrict && $hasProvince && $hasRegion)
		    	{
		    		break;
		    	}else{
		    		if($ai >= 1)
		    		{
		    			$ai--;
		    			$address_components = $data->results[$ai]->address_components;
		    			$i = -1;
		    		}else{
		    			break;
		    		}
		    	}
		    }
	    }

	    $return_array['address']= (isset($address)) ? $address : '';
	    $return_array['district']= (isset($district)) ? $district : '';
	    $return_array['province']= (isset($province)) ? $province : '';
	    $return_array['region']= (isset($region)) ? $region : '';
	    
	    return $return_array;
	}

	public static function is_in_polygon($points_polygon, $poligon_lng, $poligon_lat, $longitude_x, $latitude_y)
	{
	  $i = $j = $c = 0;
	  for ($i = 0, $j = $points_polygon ; $i < $points_polygon; $j = $i++) {
	    if ( (($poligon_lat[$i]  >  $latitude_y != ($poligon_lat[$j] > $latitude_y)) &&
	     ($longitude_x < ($poligon_lng[$j] - $poligon_lng[$i]) * ($latitude_y - $poligon_lat[$i]) / ($poligon_lat[$j] - $poligon_lat[$i]) + $poligon_lng[$i]) ) )
	       $c = !$c;
	  }
	  return $c;
	}
}
