<?php

//klasa sa varijablama koje su više manje jednake atributima iz tablice projekt_ocjene

class Ocjena
{
	protected $id, $id_user, $ime_korisnika, $prezime_korisnika, $id_hotela, $ocjena_korisnika, $komentar;

	function __construct( $id, $id_user, $ime_korisnika, $prezime_korisnika, $id_hotela, $ocjena_korisnika, $komentar )
	{
		$this->id = $id;
    $this->id_user = $id_user;
    $this->ime_korisnika = $ime_korisnika;
    $this->prezime_korisnika = $prezime_korisnika;
    $this->id_hotela = $id_hotela;
		$this->ocjena_korisnika = $ocjena_korisnika;
		$this->komentar = $komentar;
	}

	function __get( $prop ) { return $this->$prop; }
	function __set( $prop, $val ) { $this->$prop = $val; return $this; }
}

?>
