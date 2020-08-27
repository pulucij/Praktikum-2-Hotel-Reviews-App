<?php

//klasa sa varijablama koje su više manje jednake atributima iz tablice projekt_hoteli s dodanim poljem soba
//koje nudi taj hotel

class Hotel
{
	protected $id, $ime_grada, $ime_hotela, $adresa_hotela, $udaljenost_od_centra, $ocjena, $broj_zvjezdica, $sobe;

	function __construct( $id, $ime_grada, $ime_hotela, $adresa_hotela, $udaljenost_od_centra, $ocjena, $broj_zvjezdica, $sobe)
	{
		$this->id = $id;
    $this->ime_grada = $ime_grada;
		$this->ime_hotela = $ime_hotela;
		$this->adresa_hotela = $adresa_hotela;
		$this->udaljenost_od_centra = $udaljenost_od_centra;
    $this->ocjena = $ocjena;
    $this->broj_zvjezdica= $broj_zvjezdica;
		$this->sobe= $sobe;
	}

	function __get( $prop ) { return $this->$prop; }
	function __set( $prop, $val ) { $this->$prop = $val; return $this; }
}

?>
