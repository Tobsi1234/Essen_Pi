<?php
	//alle js funktionen befinden sich in includeBody
	
	if(!isset($_SESSION['userid'])) {
		echo('<script language="javascript">hideUnterseiten();</script>');
	}
	else echo('<script language="javascript">showUnterseiten();</script>');
?>