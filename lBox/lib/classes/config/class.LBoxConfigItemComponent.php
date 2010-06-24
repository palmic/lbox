<?php
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0

* @date 2007-12-08
*/
class LBoxConfigItemComponent extends LBoxConfigItem
{
	/**
	 * page attributes names
	 * @var string
	 */
	protected $attNames = array(
								"class" 		=> "class",
								"template"		=> "template",
	);

	protected $nodeName 			= "component";
	protected $classNameIterator	= "LBoxIteratorComponents";
	protected $idAttributeName		= "id";
	
	public function __construct() {
		try {
			// defaultne nastavime OutputFilterComponent
			$this->setOutputFilter(new OutputFilterComponent($this));
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Vraci nazev sablony z parametru, nebo system config default
	 * @return string
	 * @throws LBoxExceptionConfigComponent
	 */
	public function getTemplateFileName() {
		try {
			if (strlen($value = $this->__get($this->attNames["template"])) < 1) {
				throw new LBoxExceptionConfigComponent(LBoxExceptionConfigComponent::MSG_TEMPLATE_NOTFOUND, LBoxExceptionConfigComponent::CODE_TEMPLATE_NOTFOUND);
			}
			return $value;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Vraci nazev tridy z parametru, nebo system config default
	 * @return string
	 * @throws LBoxException
	 */
	public function getClassName() {
		try {
			if (strlen($value = $this->__get($this->attNames["class"])) > 0) {
				return $value;
			}
			else {
				$default = LBoxConfigSystem::getInstance()->getParamByPath("components/classes/default");				
				if (strlen($default) < 1) {
					throw new LBoxExceptionConfigComponent(LBoxExceptionConfigComponent::MSG_CLASS_DEFAULT_NOTFOUND, LBoxExceptionConfigComponent::CODE_CLASS_DEFAULT_NOTFOUND);
				}
				return $default;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>