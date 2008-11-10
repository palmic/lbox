<?php
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2008-08-26
*/
class ProductsRegistrationXXTUsersRecord extends AbstractRecordLBox
{
	public static $itemsType 		= "ProductsRegistrationXXTUsersRecords";
	public static $tableName    	= "products_registration_x_xt_users";
	public static $idColName    	= "id";

	public static $boundedM1 		= array("ProductsRegistrationRecords" 	=> "ref_product_registration",
											"XTUsersRecords" 				=> "ref_xt_user",
	);

	public static $dependingRecords	= array("");
	
	/**
	 * cache variables
	 */
	protected $productRegistration;
	protected $xtUser;
	
	/**
	 * getter na ProductsRegistrationRecord
	 * @return ProductsRegistrationRecord
	 * @throws Exception
	 */
	public function getProductRegistration() {
		try {
			if ($this->productRegistration instanceof ProductsRegistrationRecord) {
				return $this->productRegistration;
			}
			return $this->productRegistration	= $this->getBoundedM1Instance("ProductsRegistrationRecords", $filter, $order, $limit, $whereAdd)->current();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * getter na XTUsera
	 * @return XTUsersRecord
	 * @throws Exception
	 */
	public function getXTUser() {
		try {
			if ($this->xtUser instanceof XTUsersRecord) {
				return $this->xtUser;
			}
			return $this->xtUser	= $this->getBoundedM1Instance("XTUsersRecords", $filter, $order, $limit, $whereAdd)->current();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>