<?php

namespace App\Entity;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

interface AdvertisementsInterface{
	/**
	 * Method to check if an advertisement is valid by media type
	*/
	public function validateByMediaType():?bool ;
}
?>