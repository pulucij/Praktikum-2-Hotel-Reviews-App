<?php

class UsersController extends BaseController
{
	public function index()
	{

	}

	//ciscenje sessiona, vracanje na pocetak, cekanje novog korisnika
	public function odlogiraj()
	{
		unset($_POST['ime']); unset($_POST['prezime']); unset($_POST['pass']);
		session_unset(); session_destroy();
		//$this->registry->template->show( 'login' );
		header( 'Location: ' . __SITE_URL . '/index.php?rt=start/index');
	}

	//prikazi stranicu odabir na kojoj su ponudjeni gradovi
	public function odabir()
	{
		$this->registry->template->title = 'Odaberi!';
		$this->registry->template->show( 'odabir' );
	}

	public function check_login()
	{
		$ss = new SmjestajService();
		if(isset($_POST['ime'], $_POST['prezime']) && !preg_match('/^[a-zA-Z\s-]{1, 20}/',$_POST['ime']) 
					&& !preg_match('/^[a-zA-Z\s-]{1, 20}/',$_POST['prezime']))
			$lozinke = $ss->getPasswordByNameAndSurname( $_POST['ime'], $_POST['prezime'] ); //više ih je za slučaj da ima više korisnika s istim
																							//imenom i prezimenom, sifra je  onda npr.'perinasifra2'
		if(isset( $_POST['pass'] ))
		{
			foreach($lozinke as $lozinka)
			{
				if(password_verify($_POST['pass'], $lozinka))
				{
					session_start();
					$secret_word = 'racunarski praktikum 2!!!';
					//spremi u session korisnika koji se sad ulogirao
					$_SESSION['login'] = $_POST['ime'] . ','. $_POST['prezime'] . ',' . md5( $_POST['ime'] . $secret_word );;
					header( 'Location: ' . __SITE_URL . '/index.php?rt=users/odabir');
					exit();
				}
			}
			//proslo je kroz petlju po lozinkama i nije se dogodio exit tj. nema te osobe s tim imenom, prezimenom
			//i šifrom, onda ne valja log in i ide na odlogiraj
			$this->odlogiraj();
		}
		else $this->odlogiraj();
	}

  public function check_register()
	{
		if(isset($_POST['ime']) && isset($_POST['prezime']) && isset($_POST['pass']) && 
				isset($_POST['pass2']) && !preg_match('/^[a-zA-Z0-9]{1, 20}/', $_POST['pass']) && !preg_match('/^[a-zA-Z0-9]{1, 20}/', $_POST['pass2']) 
				&& !preg_match('/^[a-zA-Z\s-]{1, 20}/',$_POST['ime']) && !preg_match('/^[a-zA-Z\s-]{1, 20}/',$_POST['prezime']) && $_POST['ime']!==''
				&& $_POST['prezime']!=='' && $_POST['pass']!=='' && $_POST['pass2']!=='' && $_POST['pass'] === $_POST['pass2'] )
		{
			$ss2 = new SmjestajService();
			$lozinke = $ss2->getPasswordByNameAndSurname( $_POST['ime'], $_POST['prezime'] );
			//neće uć u petlju ako nemamo osobu s istim imenom i prezimenom
			foreach($lozinke as $lozinka)
			{
				//Ako imamo već osobu s istim imenom, prezimenom pa čak i lozinkom, odi na odlogiraj
				if(password_verify($_POST['pass'], $lozinka))
				{
					$this->odlogiraj();
					exit();
				}
			}
			$ss2->dodajUsera($_POST['ime'], $_POST['prezime'], $_POST['pass']);
			session_start();
			$secret_word = 'racunarski praktikum 2!!!';
			//spremi u session korisnika koji se sad registrirao
			$_SESSION['login'] = $_POST['ime'] . ',' . $_POST['prezime'] . ',' . md5( $_POST['ime'] . $secret_word );;
			header( 'Location: ' . __SITE_URL . '/index.php?rt=users/odabir');
			exit();
		}
		else $this->odlogiraj();
	}

	public function check_odabir()
	{
		if(isset($_POST['odlogiraj']))
		{
			$this->odlogiraj();
			exit();
		}
		if(isset($_POST['odabir']))
		{
			//spremi u session grad kojeg je trenutni korisnik odabrao
			$_SESSION['ime_grada'] = $_POST['odabir'];
			$this->registry->template->title = 'Sortiraj i filtriraj!';
			$this->registry->template->show( 'sortiraj_filtriraj' );
			//exit();
			//sad treba omogućiti da pretrazuje hotele po filterima(udaljenost, vlastita soba...) ili sortira(po cijeni...)
		}
		else 
		{
			$this->registry->template->title = 'Odaberi!';
			$this->registry->template->show( 'odabir' );
		}
	}

	public function check_sort_filt()
	{
		if(isset($_POST['odlogiraj']))
		{
			$this->odlogiraj();
			exit();
		}
		//makni sessione nastale na trenutnoj stranici i vrati se na prethodnu
		if(isset($_POST['natrag']))
		{
			$this->registry->template->title = 'Odaberite grad koji Vas zanima';
			$this->registry->template->show( 'odabir' );
			unset(	$_SESSION['sort']); unset($_SESSION['filter']);
			unset($_SESSION['cijena']); unset($_SESSION['udaljenost']);
			unset($_SESSION['osobe']); unset($_SESSION['bracni']);
			unset($_SESSION['odvojeni']); unset($_SESSION['na_kat']);
			unset($_SESSION['ocjena']); unset($_SESSION['zvjezdice']);
			exit();
		}
		//postavljanje sessiona, za slučaj da se vratimo sa stranice detalji natrag na
		//sortirano, filtrirano da možemo opet dobiti isti ispis kao i nakon stranice
		// sortiraj i filtriraj... U tom slučaju nemamo postove...
		if(isset($_POST['sort']))
			$_SESSION['sort'] = $_POST['sort'];
		if(isset($_POST['filter']))
			$_SESSION['filter'] = $_POST['filter'];
		if(isset($_POST['cijena']) && preg_match('/^-?(?:\d+|\d*\.\d+)$/', $_POST['cijena']))
			$_SESSION['cijena'] = $_POST['cijena'];
		if(isset($_POST['udaljenost']) && preg_match('/^-?(?:\d+|\d*\.\d+)$/', $_POST['udaljenost']))
			$_SESSION['udaljenost'] = $_POST['udaljenost'];
		if(isset($_POST['osobe']) && !preg_match('/^[0-9]{1, 20}/', $_POST['osobe']) && $_POST['osobe']!=='')
			$_SESSION['osobe'] = $_POST['osobe'];
		if(isset($_POST['bracni']) && !preg_match('/^[0-9]{1, 10}/', $_POST['bracni']))
			$_SESSION['bracni'] = $_POST['bracni'];
		if(isset($_POST['odvojeni']) && !preg_match('/^[0-9]{1, 10}/', $_POST['odvojeni']))
			$_SESSION['odvojeni'] = $_POST['odvojeni'];
		if(isset($_POST['na_kat']) && !preg_match('/^[0-9]{1, 10}/', $_POST['na_kat']))
			$_SESSION['na_kat'] = $_POST['na_kat'];
		if(isset($_POST['ocjena']) && preg_match('/^-?(?:\d+|\d*\.\d+)$/', $_POST['ocjena']))
			$_SESSION['ocjena'] = $_POST['ocjena'];
		if(isset($_POST['zvjezdice']) && preg_match('/^[1-5]/', $_POST['zvjezdice']))
			$_SESSION['zvjezdice'] = $_POST['zvjezdice'];

		$polje_polja_hotela= array();
		$hotel_kriteriji= array();
		$filtri = array();
		//korisnik je odabrao neke kriterije sortiranja
		//u $polje_polja_hotela stavljamo onoliko polja hotela koliko je
		//korisnik odabrao kriterija sortiranja
		//u polju hotel_kriteriji su pobrojani kriteriji po kojima korisnik zeli sortirati
		if(isset($_SESSION['sort']))
		{
			$ss3 = new SmjestajService();
				$N = count($_SESSION['sort']);
				for($i=0; $i < $N; $i++)
	    	{
						$polje_hotela = $ss3->getHotelsByNameOrderBy($_SESSION['ime_grada'], $_SESSION['sort'][$i]);
						array_push($polje_polja_hotela, $polje_hotela);
						array_push($hotel_kriteriji, $_SESSION['sort'][$i]);
				}
		}
		//korisnik nije odabrao kriterije sortiranja pa imamo samo
		//jedno polje hotela koje sadrzi sve hotele iz tog grada
		else {
			$ss3 = new SmjestajService();
			$polje_hotela = $ss3->getHotelsByName($_SESSION['ime_grada']);
			array_push($polje_polja_hotela, $polje_hotela);
		}
		//sada iz svakog polja hotela mičemo hotele koji ne zadovoljavaju kriterije filtra
		//koje je korisnik odabrao (ako ih je odabrao), to obavljaju funkcije iz klase SmjestajService
			if(isset($_SESSION['filter']))
			{
				$M = count($_SESSION['filter']);
				//polje filtri sadrzi pobrojane filtre s vrijednostima
				for($i=0; $i < $M; $i++)
				{
					if($_SESSION['filter'][$i] === 'cijena_po_osobi' && isset($_SESSION['cijena']))
					{
						$ss3->applyFilterCijena($polje_polja_hotela, $_SESSION['cijena']);
						array_push($filtri, 'cijena po osobi po noćenju najviše: '.$_SESSION['cijena']);
					}
					if($_SESSION['filter'][$i] === 'udaljenost_od_centra' && isset($_SESSION['udaljenost']))
					{
						$ss3->applyFilterUdaljenost($polje_polja_hotela, $_SESSION['udaljenost']);
						array_push($filtri, 'udaljenost od centra najviše: '.$_SESSION['udaljenost']);
					}
					if($_SESSION['filter'][$i] === 'broj_osoba' && isset($_SESSION['osobe']))
					{
						$ss3->applyFilterOsobe($polje_polja_hotela, $_SESSION['osobe']);
						array_push($filtri, 'broj osoba: '.$_SESSION['osobe']);
					}
					if($_SESSION['filter'][$i] === 'tip_kreveta' && (isset($_SESSION['bracni'])
					|| isset($_SESSION['odvojeni']) || isset($_SESSION['na_kat'])))
					{
						//punimo niz kreveti s informacijama o broju i vrsti kreveta koje korisnik hoće
						//taj niz stavljamo o niz filtri (informacije o filtrima koje je korisnik odabrao)
						//taj niz je bitan također bitan kao argument fje applyFilterKreveti
						$nizKreveti = array();
						if($_SESSION['bracni'] !== '' && $_SESSION['bracni'] !== '0')
							array_push($nizKreveti, $_SESSION['bracni'].' x bracni');
						if($_SESSION['odvojeni'] !== '' && $_SESSION['odvojeni'] !== '0')
							array_push($nizKreveti, $_SESSION['odvojeni'].' x odvojeni');
						if($_SESSION['na_kat'] !== '' && $_SESSION['na_kat'] !== '0')
							array_push($nizKreveti, $_SESSION['na_kat'].' x na kat');
						$string = ' ';
						foreach($nizKreveti as $var) $string.= $var.' ';
						$ss3->applyFilterKreveti($polje_polja_hotela, $nizKreveti);
						array_push($filtri, 'tip kreveta: '.$string);
					}
					if($_SESSION['filter'][$i] === 'ocjena' && isset($_SESSION['ocjena']))
					{
						$ss3->applyFilterOcjena($polje_polja_hotela, $_SESSION['ocjena']);
						array_push($filtri, 'minimalna ocjena: '.$_SESSION['ocjena']);
					}
					if($_SESSION['filter'][$i] === 'broj_zvjezdica' && isset($_SESSION['zvjezdice']))
					{
						$ss3->applyFilterZvjezdice($polje_polja_hotela, $_SESSION['zvjezdice']);
						array_push($filtri, 'minimalni broj zvjezdica: '.$_SESSION['zvjezdice']);
					}
					if($_SESSION['filter'][$i] === 'vlastita_kupaonica')
					{
						$ss3->applyFilterKupaonica($polje_polja_hotela);
						array_push($filtri, 'obvezna kupaonica' );
					}
				}
			}

			//ako su hoteli sortirani po nekom atributu koji se ne nalazi u tablici projekt_hoteli
			//vec u tablici projekt_sobe onda zelimo osim vrijednosti atributa iz tablice projekt_hoteli
			//ispisati i pripadnu vrijednost atributa iz tablice porjekt_sobe po kojemu se sortiralo
			//ovdje se pripremaju nizovi sort_cijene i sort_sobe za ispis ako korisnik zeli sort po tim kriterijima
			$sort_cijene = array();
			$sort_osobe = array();
			$i=0;
			$niz_id = array();
			foreach($polje_polja_hotela as $polje_hotela)
			{
				if(isset($hotel_kriteriji[$i]) && $hotel_kriteriji[$i] === 'cijena_po_osobi')
				{
					foreach($polje_hotela as $hotel)
						array_push($niz_id, $hotel->id);
					//treba nam array_unique jer u polju hotela se neki hotel moze pojavit i vise puta jer nudi vise soba
					array_unique($niz_id);
					foreach($polje_hotela as $hotel)
					{
						//sad za svaki hotel u tom polju hotela trazimo njegove sobe i u niz
						//sort_cijene pushamo cijenu te sobe

						//da nismo koristili niz_id onda bi se cijena pojedine sobe pojedinog hotela
						// u niz sort_cijene zapisala onoliko puta koliko taj hotel ima soba odnosno
						//koliko puta se pojavljue u $polje_hotela
						if(in_array($hotel->id, $niz_id))
							foreach($hotel->sobe as $soba)
							{
								array_push($sort_cijene, $soba->cijena_po_osobi);
								unset($niz_id[array_search($hotel->id, $niz_id)]);
							}
					}
				}
				//potpuno isti kod samo za kriterij broj_osoba
				if(isset($hotel_kriteriji[$i]) && $hotel_kriteriji[$i] === 'broj_osoba')
				{
					foreach($polje_hotela as $hotel)
						array_push($niz_id, $hotel->id);
					array_unique($niz_id);
					foreach($polje_hotela as $hotel)
					{
						if(in_array($hotel->id, $niz_id))
							foreach($hotel->sobe as $soba)
							{
								array_push($sort_osobe, $soba->broj_osoba);
								unset($niz_id[array_search($hotel->id, $niz_id)]);
							}
					}
				}
				$i++;
			}
			
			//uredi polje $hotel_kriteriji tj. napravi novo tako da je ljepše za ispis (bez _)
			$kriteriji = array();
			for($i=0; $i<count($hotel_kriteriji); $i++)
			{
				if($hotel_kriteriji[$i] === 'cijena_po_osobi') 
					$kriteriji[$i] = 'cijeni po osobi po noćenju';
				else if($hotel_kriteriji[$i] === 'udaljenost_od_centra')
					$kriteriji[$i] = 'udaljenosti od centra';
				else if($hotel_kriteriji[$i] === 'broj_osoba')
					$kriteriji[$i] = 'broju osoba u sobi';
				else if($hotel_kriteriji[$i] === 'broj_zvjezdica')
					$kriteriji[$i] =  'broju zvjezdica';
				else $kriteriji[$i] =  'ocjeni';
			}
			//na kraju je samo potrebno sortirati ta polja i spremna su za ispisivanje uz
			//informacije o hotelima
			sort($sort_cijene); sort($sort_osobe);
			$this->registry->template->hoteli = $polje_polja_hotela;
			$this->registry->template->hotel_kriteriji = $kriteriji;
			$this->registry->template->filtri = $filtri;
			$this->registry->template->sort_cijene = $sort_cijene;
			$this->registry->template->sort_osobe = $sort_osobe;
			$this->registry->template->title = 'Sortiraj i filtriraj!';
			$this->registry->template->show( 'sortirano_filtrirano' );

	}

	public function check_details()
	{
		if(isset($_POST['odlogiraj']))
		{
			$this->odlogiraj();
			exit();
		}
		//makni sessione nastale na trenutnoj stranici i vrati se na prethodnu
		//micu se i sessioni koji sadrze kriterije sorta i filtra jer ako ne odemo na
		//stranicu s detaljima onda se vraćamo na stranicu di odabiremo kriterije
		//kako bi imali samo nove kriterije koje smo dobili u POST-u treba maknuti stare sessione
		if(isset($_POST['natrag']))
		{
			$this->registry->template->title = 'Odaberite kako želite da Vam hoteli budu sortirani i filtrirani';
			$this->registry->template->show( 'sortiraj_filtriraj' );
			unset($_SESSION['detalji']);
			unset($_SESSION['id_hotela']);
			unset($_SESSION['sort']); unset($_SESSION['filter']);
			unset($_SESSION['cijena']); unset($_SESSION['udaljenost']);
			unset($_SESSION['osobe']); unset($_SESSION['bracni']);
			unset($_SESSION['odvojeni']); unset($_SESSION['na_kat']);
			unset($_SESSION['ocjena']); unset($_SESSION['zvjezdice']);
			exit();
		}
			$ss = new SmjestajService();
			$polje_hotela = $ss->getHotelsByName($_SESSION['ime_grada']);

			//pripremi sve informacije o hotelu za ispis na stranici detalji
			foreach($polje_hotela as $hotel)
			{
				//session je tu ako se vracamo sa neke stranice natrag na ovu
				//tj. kad se vracamo na istu nakon dodavanja komentara
				if((isset($_POST['detalji']) && $_POST['detalji'] === $hotel->ime_hotela) ||
				(isset($_SESSION['detalji']) && $_SESSION['detalji'] === $hotel->ime_hotela))
				{
					$_SESSION['detalji'] = $hotel->ime_hotela;
					$_SESSION['id_hotela'] = $hotel->id;
					$polje_komentara = $ss->getCommentsByHotelId($hotel->id);
					$this->registry->template->title = 'Detalji hotela';
					$this->registry->template->hotel = $hotel;
					$this->registry->template->komentari = $polje_komentara;
					$this->registry->template->show( 'detalji' );
					exit();
				}
			}

	}

	public function check_comments()
	{
		if(isset($_POST['odlogiraj']))
		{
			$this->odlogiraj();
			exit();
		}
		//natrag na liste sortiranih i fitlriranih hotela
		if(isset($_POST['natrag']))
		{
			unset($_POST['natrag']);
			unset($_SESSION['detalji']);
			unset($_SESSION['id_hotela']);
			$this->check_sort_filt();
			exit();
		}

		if(isset($_POST['komentar_gumb']))
		{
			if(isset($_POST['ocjena']) && preg_match('/^-?(?:\d+|\d*\.\d+)$/', $_POST['ocjena']) && $_POST['ocjena']!=='' && 
					isset($_POST['komentar']) && $_POST['komentar'] !== 'Ovdje napišite komentar.')
			{
				$ocjena = ' '; $komentar = ' ';
				$ocjena = $_POST['ocjena'];
				$komentar = $_POST['komentar'];

				$ss = new SmjestajService();

				$ss->dodajKomentar($ocjena, $komentar);
			}

			$this->check_details();
		}
	}

};

?>
