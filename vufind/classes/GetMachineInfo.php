<?php

class GetMachineInfo {
	
	
	public function getMachineName()
	{
		return gethostname();
	}
	
}

?>