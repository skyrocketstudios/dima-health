<?php
use QodeListing\Lib\Front;
$listing_types = qode_listing_get_listing_types_by_listing_id(get_the_ID());

if(count($listing_types)){
	foreach($listing_types as $type){

		$object = new Front\ListingTypeFieldCreator($type['id'], get_the_ID());

		$object->getSingleListingAmenities();

	}
}