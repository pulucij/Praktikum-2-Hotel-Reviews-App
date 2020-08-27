<?php

class StartController extends BaseController
{
	public function index()
	{
		$ss = new SmjestajService();

		// Popuni template potrebnim podacima
		$this->registry->template->title = 'Nađi svoj smještaj!';

        $this->registry->template->show( 'start_index' );
	}

	public function ulogiraj_registiraj()
	{
		//provjeri zeli li se korisnik ulogirati ili registrirati
		if(isset($_POST['ulogiraj']))
		{
			$this->registry->template->title = 'Login';
			$this->registry->template->show( 'login' );
		}
		if(isset($_POST['registriraj']))
		{
			$this->registry->template->title = 'Registriraj se';
			$this->registry->template->show( 'register' );
		}

	}

};

?>
