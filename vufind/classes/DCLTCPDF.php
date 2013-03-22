<?php
require_once dirname(__FILE__).'/../vendors/tcpdf/config/lang/eng.php';
require_once dirname(__FILE__).'/../vendors/tcpdf/tcpdf.php';

class DCLTCPDF extends TCPDF
{
	public function __construct($orientation='P', $unit='mm', $format='A4', $unicode=true, $encoding='UTF-8', $diskcache=false, $pdfa=false) {
		parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);
		$this->setDocCreationTimestamp(0);
		$this->setDocModificationTimestamp(0);
		$this->file_id = 0;
	}
}
?>