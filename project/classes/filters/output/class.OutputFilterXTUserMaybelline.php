<?
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2008-08-26
*/
class OutputFilterXTUserMaybelline extends LBoxOutputFilter
{
	/* cache variables */
	protected $productsRegistration;
	
	public function prepare($name = "", $value = NULL) {
		try {
			switch ($name) {
				case "getProductsRegistration":
					return $this->getProductsRegistration();
					break;
				default:
					return $value;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * vraci, ktere produkty si pri registraci uzivatel zvolil jako ze je pouziva
	 * @return ProductsRegistrationRecords
	 * @throws LBoxException
	 */
	protected function getProductsRegistration () {
		try {
			if ($this->productsRegistration instanceof ProductsRegistrationRecords) {
				return $this->productsRegistration;
			}
			return $this->productsRegistration	= new ProductsRegistrationXTUsersRecords(array("id" => $this->instance->id));
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>