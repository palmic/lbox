<?
/**
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0

* @since 2008-03-23
*/
class OutputFilterContactForm extends OutputFilterComponent
{
	public function prepare($name = "", $value = NULL) {
		switch ($name) {
			case "contact_form_addresses":
				$addresses	= LBoxConfigManagerProperties::getInstance()->getPropertyByName("contact_form_addresses")->getContent();
				$addresses	= str_ireplace("\$domain_email", LBOX_REQUEST_URL_HOST, $addresses);
				$addresses	= str_ireplace("www.", "", $addresses);
				return $addresses;
				break;
			default:
					return parent::prepare($name, $value);
		}
	}
}
?>