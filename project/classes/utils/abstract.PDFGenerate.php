<?php
/**
 * Slouzi k snadnemu vygenerovani PDF pomoci PDML pluginu
 * k pouziti staci podedit tuto tridu
 *  - !je treba definovat $fileNameTemplateHTML!
 *  - !je treba mit namapovanou cestu k PDML sablonam property path_templates_pdml v properties.xml! 
 *  - v parametru konstruktoru je mozno predat record, ktery je potom v sablone dostupny jako SELF/dataRecord
 * @author Michal Palma <palmic@email.cz>
 * @package LBox ubytovny-v-praze.cz
 * @version 1.0
 * @license http://creativecommons.org/licenses/by-sa/3.0/
 * @since 2009-08-12
 */
abstract class PDFGenerate
{
	/**
	 * nazev sablony HTML
	 * @var string
	 */
	protected $fileNameTemplateHTML	= "";

	/**
	 * TAL instance
	 * @var PHPTAL
	 */
	protected $TAL;

	/**
	 * objekt ze ktereho bereme data
	 * @var AbstractRecord
	 */
	public $dataRecord;

	/**
	 * @param AbstractRecord $dataRecord
	 */
	public function __construct(AbstractRecord $dataRecord = NULL) {
		try {
			$this->dataRecord	= $dataRecord;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na PDF zdrojak
	 * @return string
	 */
	public function getSourceCode() {
		try {
			$outTemp	= 	ob_get_contents();
							ob_end_clean();
			$pdml = new PDML('P','pt','A4'); // P and A4 should be customizable. XXX
			$pdml->compress=0;
			$pdml->ParsePDML($this->getTAL()->execute());
			$s = $pdml->Output("","S");
			/*priklad hlavicek:
				Header('Content-Type: application/pdf');
				Header('Content-Length: '.strlen($s));
				Header('Content-disposition: inline; filename=test_faktury.pdf');
			  	Header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
		  	*/
			ob_end_clean();
			ob_start();
			echo $outTemp;
			return $s;
		}
		catch(Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na TAL
	 * @return PHPTAL
	 */
	protected function getTAL() {
		try {
			if (strlen($this->fileNameTemplateHTML) < 1) {
				throw new LBoxException(LBoxException::MSG_INSTANCE_VAR_STRING_NOTNULL, LBoxException::CODE_BAD_INSTANCE_VAR);
			}
			$pathTemplate	= $this->getTemplatesPath() ."/". $this->fileNameTemplateHTML;
			if (!file_exists($pathTemplate)) {
				throw new LBoxExceptionPage("Cannot find HTML mail template file in '$pathTemplate'!");
			}
			if (!$this->TAL instanceof PHPTAL) {
				$this->TAL = new PHPTAL($pathTemplate);
			}
			$this->TAL->SELF 		= $this;
			return $this->TAL;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci cestu k adresari sablon emailu
	 * @return string
	 */
	protected function getTemplatesPath() {
		try {
			$path	= LBoxConfigManagerProperties::getPropertyContentByName("path_templates_pdml");
			$path	= str_ireplace("<project>", LBOX_PATH_PROJECT, $path);
			return $path;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na host pro sablonu
	 * @return string
	 */
	public function getHost() {
		try {
			return LBOX_REQUEST_URL_HOST;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>