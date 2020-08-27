<?php

//klasa sa varijablama koje su više manje jednake atributima iz tablice projekt_sobe

class Soba
{
	protected $ime_hotela, $id, $id_hotela, $broj_osoba, $tip_kreveta, $vlastita_kupaonica, $cijena_po_osobi;

	function __construct( $ime_hotela, $id, $id_hotela, $broj_osoba, $tip_kreveta, $vlastita_kupaonica, $cijena_po_osobi )
	{
		$this->ime_hotela = $ime_hotela;
		$this->id = $id;
    $this->id_hotela = $id_hotela;
		$this->broj_osoba = $broj_osoba;
		$this->tip_kreveta = $tip_kreveta;
		$this->vlastita_kupaonica = $vlastita_kupaonica;
    $this->cijena_po_osobi = $cijena_po_osobi;
	}

	function __get( $prop ) { return $this->$prop; }
	function __set( $prop, $val ) { $this->$prop = $val; return $this; }
}

?>
