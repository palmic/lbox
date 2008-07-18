<?
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0

* @since 2007-12-08
*/
class OutputFilterUserAdminXTRoles extends LBoxOutputFilter
{
	public function prepare($name = "", $value = NULL) {
		try {
			switch ($name) {
				case "rolesArray":
					foreach(LBoxXT::getXTRoles($superAdmin = true) as $role) {
						$out[$role->id]["value"]	= $role->name;
						$out[$role->id]["selected"]	= ($role->id == $this->instance->ref_xtRole) ? "selected" : "";
					}
					return $out;
					break;
				default:
					return $value;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>