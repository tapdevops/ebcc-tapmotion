<?php

$cons = oci_connect('qa_user', 'qa_user', 'dw.tap-agri.com/tapdw');
if (!$cons) {
	$e = oci_error();
	trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

?>