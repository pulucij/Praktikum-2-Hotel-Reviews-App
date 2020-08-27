<?php

class IndexController extends BaseController
{
	public function index()
	{
		// Samo preusmjeri na start podstranicu.
		header( 'Location: ' . __SITE_URL . '/index.php?rt=start' );
	}
};

?>
