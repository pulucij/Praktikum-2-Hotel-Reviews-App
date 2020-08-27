<?php

// Manualno inicijaliziramo bazu ako već nije.
require_once '../../model/db.class.php';

$db = DB::getConnection();

try
{
	$st = $db->prepare(
		'CREATE TABLE IF NOT EXISTS projekt_hoteli (' .
		'id int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
		'ime_grada varchar(50) NOT NULL,' .
		'ime_hotela varchar(100) NOT NULL,' .
		'adresa_hotela varchar(200) NOT NULL,' .
		'udaljenost_od_centra DECIMAL(4,2) NOT NULL,' .
		'ocjena DECIMAL(3,1),' .
		'broj_zvjezdica INT NOT NULL)'
	);
//ocjena je od 0 do 10,udaljenost u km -> decimalni brojevi
//DECIMAL(3, 1) pokriva brojeve sa ukupno 3 znamenke i 1 decimalom, tj. od -99.9 do 99.9,
// nama treba od 0 do 10 uglavnom s deimalom

	$st->execute();
}
catch( PDOException $e ) { exit( "PDO error #1: " . $e->getMessage() ); }

echo "Napravio tablicu projekt_hoteli.<br />";

try
{
	$st = $db->prepare(
		'CREATE TABLE IF NOT EXISTS projekt_sobe (' .
		'id int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
		'id_hotela INT NOT NULL,' .
		'broj_osoba INT NOT NULL,' .
		'tip_kreveta varchar(200) NOT NULL,' .
		'vlastita_kupaonica BOOLEAN,'.
		'cijena_po_osobi DECIMAL(6,2) NOT NULL)'
	);
	//zero is considered as false, and non-zero value is considered as true.
	//To use Boolean literals, you use the constants TRUE and FALSE that evaluate to 1 and 0 respectively.
	//cijena u kunama!

	$st->execute();
}
catch( PDOException $e ) { exit( "PDO error #2: " . $e->getMessage() ); }

echo "Napravio tablicu projekt_sobe.<br />";

try
{
	$st = $db->prepare(
		'CREATE TABLE IF NOT EXISTS projekt_korisnici (' .
		'id int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
		'name varchar(50) NOT NULL,' .
		'surname varchar(50) NOT NULL,' .
		'password varchar(255) NOT NULL)'
	);

	$st->execute();
}
catch( PDOException $e ) { exit( "PDO error #3: " . $e->getMessage() ); }

echo "Napravio tablicu projekt_korisnici.<br />";


try
{
	$st = $db->prepare(
		'CREATE TABLE IF NOT EXISTS projekt_ocjene (' .
		'id int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
		'id_user INT NOT NULL,' .
		'id_hotela INT NOT NULL,' .
		'ocjena_korisnika DECIMAL(3,1),' .
		'komentar varchar(50))'
	);

	$st->execute();
}
catch( PDOException $e ) { exit( "PDO error #4: " . $e->getMessage() ); }

echo "Napravio tablicu projekt_ocjene.<br />";


// Ubaci neke korisnike unutra
try
{
	$st = $db->prepare( 'INSERT INTO projekt_korisnici(name, surname, password) VALUES (:name, :surname, :password)' );

	$st->execute( array( 'name' => 'Pero', 'surname' => 'Perić', 'password' => password_hash( 'perinasifra', PASSWORD_DEFAULT ) ) );
	$st->execute( array( 'name' => 'Mirko', 'surname' => 'Mirić', 'password' => password_hash( 'mirkovasifra', PASSWORD_DEFAULT ) ) );
	$st->execute( array( 'name' => 'Slavko', 'surname' => 'Slavić', 'password' => password_hash( 'slavkovasifra', PASSWORD_DEFAULT ) ) );
	$st->execute( array( 'name' => 'Ana', 'surname' => 'Anić', 'password' => password_hash( 'aninasifra', PASSWORD_DEFAULT ) ) );
	$st->execute( array( 'name' => 'Maja', 'surname' => 'Majić', 'password' => password_hash( 'majinasifra', PASSWORD_DEFAULT ) ) );
}
catch( PDOException $e ) { exit( "PDO error #5: " . $e->getMessage() ); }

echo "Ubacio korisnike u tablicu users.<br />";


// Ubaci neke hotele unutra
try
{
	$st = $db->prepare( 'INSERT INTO projekt_hoteli(ime_grada,ime_hotela, adresa_hotela, udaljenost_od_centra, ocjena, broj_zvjezdica)
				VALUES (:ime_grada, :ime_hotela, :adresa_hotela, :udaljenost_od_centra, :ocjena, :broj_zvjezdica)' );

	$st->execute( array( 'ime_grada' => 'Amsterdam', 'ime_hotela' => 'Via Amsterdam', 'adresa_hotela'=>'20 Diemerhof Via Amsterdam, 1112 XN Amsterdam', 'udaljenost_od_centra'=> 6, 'ocjena'=> 8.0, 'broj_zvjezdica'=> 3 ) );
	$st->execute( array( 'ime_grada' => 'Amsterdam', 'ime_hotela' => 'Sir Adam Hotel', 'adresa_hotela'=>'Overhoeksplein 7, Amsterdam Noord, 1031 KS Amsterdam', 'udaljenost_od_centra'=> 1.4, 'ocjena'=> 9.1, 'broj_zvjezdica'=> 4) );
	$st->execute( array( 'ime_grada' => 'Amsterdam', 'ime_hotela' => 'Hotel Arena','adresa_hotela'=>'s-Gravesandestraat 55, Oost, 1092 AA Amsterdam' , 'udaljenost_od_centra'=> 2, 'ocjena'=> 8.0, 'broj_zvjezdica'=> 4) );
	$st->execute( array( 'ime_grada' => 'Amsterdam', 'ime_hotela' => 'Postillion Hotel Amsterdam: BW Signature Collection','adresa_hotela'=>'Paul van Vlissingenstraat, Oost, 1096 BK Amsterdam' , 'udaljenost_od_centra'=> 4.8, 'ocjena'=> 8.7, 'broj_zvjezdica'=> 4) );
	$st->execute( array( 'ime_grada' => 'Amsterdam', 'ime_hotela' => 'Stayokay Amsterdam Stadsdoelen','adresa_hotela'=>'Kloveniersburgwal 97, Amsterdam-Centar, 1011 KB Amsterdam' , 'udaljenost_od_centra'=> 0.5, 'ocjena'=> 7.9, 'broj_zvjezdica'=> 2) );
	$st->execute( array( 'ime_grada' => 'Pariz', 'ime_hotela' => 'Hôtel de France Gare de Lyon Bastille','adresa_hotela'=>'12 rue de Lyon, 12. arondisman, 75012 Pariz' , 'udaljenost_od_centra'=> 1.3, 'ocjena'=> 7.9, 'broj_zvjezdica'=> 3) );
	$st->execute( array( 'ime_grada' => 'Pariz', 'ime_hotela' => 'Hotel Plaza Elysées' ,'adresa_hotela'=>'177 Boulevard Haussmann, 8. arondisman, 75008 Pariz' , 'udaljenost_od_centra'=> 4, 'ocjena'=> 8.3, 'broj_zvjezdica'=> 4) );
	$st->execute( array( 'ime_grada' => 'Pariz', 'ime_hotela' => 'Hôtel Saint-Charles','adresa_hotela'=>'6, Rue de LEsperance, 13. arondisman, 75013 Pariz' , 'udaljenost_od_centra'=> 3.3, 'ocjena'=> 8.4, 'broj_zvjezdica'=> 3) );
	$st->execute( array( 'ime_grada' => 'Pariz', 'ime_hotela' => 'Generator Pariz','adresa_hotela'=>'9 - 11 Place du Colonel Fabien, 10. arondisman, 75010 Pariz' , 'udaljenost_od_centra'=>2.8 , 'ocjena'=>8.0 , 'broj_zvjezdica'=> 3) );
	$st->execute( array( 'ime_grada' => 'Lisabon', 'ime_hotela' => 'Lux Lisboa Park','adresa_hotela'=>'Rua Padre António Vieira 32 a 34, Avenidas Novas, 1070-197 Lisabon' , 'udaljenost_od_centra'=> 2.3, 'ocjena'=> 8.8, 'broj_zvjezdica'=> 4) );
	$st->execute( array( 'ime_grada' => 'Lisabon', 'ime_hotela' => 'Hostel Crespo','adresa_hotela'=>'Rua Gonçalves Crespo No.3, 1-2Esq., Arroios, 1150-182 Lisabon' , 'udaljenost_od_centra'=> 1.4, 'ocjena'=> 9.3, 'broj_zvjezdica'=> 3) );
	$st->execute( array( 'ime_grada' => 'Lisabon', 'ime_hotela' => 'Gloria Suites by LxWay','adresa_hotela'=>'Travessa Fala-só 1, Santo Antonio, 1250-109 Lisabon' , 'udaljenost_od_centra'=> 0.45, 'ocjena'=> 8.8, 'broj_zvjezdica'=> 3) );
	$st->execute( array( 'ime_grada' => 'Lisabon', 'ime_hotela' => 'NLC Hostel','adresa_hotela'=>'Avenida da Liberdade 204, 3º andar esq, Santo Antonio, 1250-147 Lisabon' , 'udaljenost_od_centra'=> 1.1, 'ocjena'=> 8.9, 'broj_zvjezdica'=> 2) );
	$st->execute( array( 'ime_grada' => 'Berlin', 'ime_hotela' => 'Park Inn by Radisson Berlin Alexanderplatz','adresa_hotela'=>'Alexanderplatz 7, Mitte, 10178 Berlin' , 'udaljenost_od_centra'=> 2.6, 'ocjena'=> 8.0, 'broj_zvjezdica'=> 4) );
	$st->execute( array( 'ime_grada' => 'Berlin', 'ime_hotela' => 'Novotel Berlin Mitte','adresa_hotela'=>'Fischerinsel 12, Mitte, 10179 Berlin' , 'udaljenost_od_centra'=> 1.9, 'ocjena'=> 8.6, 'broj_zvjezdica'=> 4) );
	$st->execute( array( 'ime_grada' => 'Berlin', 'ime_hotela' => 'The Circus Hostel','adresa_hotela'=>'Weinbergsweg 1a, Mitte, 10119 Berlin' , 'udaljenost_od_centra'=> 2.3, 'ocjena'=> 9.0, 'broj_zvjezdica'=> 2) );
	$st->execute( array( 'ime_grada' => 'Moskva', 'ime_hotela' => 'Sunflower Avenue Hotel Moscow','adresa_hotela'=>'Schepkina Str, 32, bld. 1, Meshchansky, 129090 Moskva' , 'udaljenost_od_centra'=> 3.2, 'ocjena'=> 8.9, 'broj_zvjezdica'=> 3) );
	$st->execute( array( 'ime_grada' => 'Moskva', 'ime_hotela' => 'Mini-hotel Lindsay','adresa_hotela'=>'Leningradskiy prospekt 26, Bldg.1, Begovoy, 125040 Moskva' , 'udaljenost_od_centra'=> 4.4, 'ocjena'=> 8.8, 'broj_zvjezdica'=> 3) );
	$st->execute( array( 'ime_grada' => 'Moskva', 'ime_hotela' => 'Makarov Hostel','adresa_hotela'=>'Sadovnicheskaya ul., 22с2, Zamoskvorechye, 115035 Moskva' , 'udaljenost_od_centra'=> 1.2, 'ocjena'=>7.9 , 'broj_zvjezdica'=>1 ) );
	$st->execute( array( 'ime_grada' => 'Moskva', 'ime_hotela' => 'Hotel Neapol','adresa_hotela'=>'Voznesenskiy Pereulok 12-3, Presnensky, 125009 Moskva' , 'udaljenost_od_centra'=>1 , 'ocjena'=>8.0 , 'broj_zvjezdica'=> 4) );
	$st->execute( array( 'ime_grada' => 'Atena', 'ime_hotela' => 'The Stanley','adresa_hotela'=>'Odysseos 1- Karaiskaki Sq, Atena, 10437' , 'udaljenost_od_centra'=>1.7 , 'ocjena'=> 8.0, 'broj_zvjezdica'=> 4) );
	$st->execute( array( 'ime_grada' => 'Atena', 'ime_hotela' => 'BED in Athens','adresa_hotela'=>'Mikonos 8, Atena, 10554' , 'udaljenost_od_centra'=> 1, 'ocjena'=> 9.7, 'broj_zvjezdica'=> 2) );
	$st->execute( array( 'ime_grada' => 'Atena', 'ime_hotela' => 'Bedbox Hostel','adresa_hotela'=>'11 Poliklitou, Atena, 10551' , 'udaljenost_od_centra'=>0.75 , 'ocjena'=> 9.2, 'broj_zvjezdica'=> 1) );
	$st->execute( array( 'ime_grada' => 'Prag', 'ime_hotela' => 'Hotel Meda of Museum Kampa','adresa_hotela'=>'Národní obrany 33, Prag, 16000' , 'udaljenost_od_centra'=>2.2 , 'ocjena'=>7.0 , 'broj_zvjezdica'=>4 ) );
	$st->execute( array( 'ime_grada' => 'Prag', 'ime_hotela' => 'Apartments Hybernska','adresa_hotela'=>'22 Hybernská, Prag, 110 00' , 'udaljenost_od_centra'=> 0.9, 'ocjena'=> 9.4, 'broj_zvjezdica'=> 4) );
	$st->execute( array( 'ime_grada' => 'Prag', 'ime_hotela' => 'SafeStay Prague','adresa_hotela'=>'Ostrovni 131/15, Prag, 110 00' , 'udaljenost_od_centra'=>0.85 , 'ocjena'=> 8.4, 'broj_zvjezdica'=>2 ) );
}
catch( PDOException $e ) { exit( "PDO error #6: " . $e->getMessage() ); }

echo "Ubacio hotele u tablicu projekt_hoteli.<br />";


// Ubaci neke korisnike unutra
try
{
	$st = $db->prepare( 'INSERT INTO projekt_sobe(id_hotela, broj_osoba, tip_kreveta, vlastita_kupaonica, cijena_po_osobi) VALUES (:id_hotela, :broj_osoba, :tip_kreveta, :vlastita_kupaonica, :cijena_po_osobi)' );

	$st->execute( array( 'id_hotela' => 1, 'broj_osoba' => 8, 'tip_kreveta'=>'4 x na kat', 'vlastita_kupaonica' => false, 'cijena_po_osobi'=> 134) );
	$st->execute( array( 'id_hotela' => 1, 'broj_osoba' => 4, 'tip_kreveta'=>'2 x na kat', 'vlastita_kupaonica' => false, 'cijena_po_osobi'=> 193) );
	$st->execute( array( 'id_hotela' => 1, 'broj_osoba' => 2, 'tip_kreveta'=>'1 x bracni', 'vlastita_kupaonica' => true, 'cijena_po_osobi'=> 324) );
	$st->execute( array( 'id_hotela' => 1, 'broj_osoba' => 2, 'tip_kreveta'=>'2 x odvojen', 'vlastita_kupaonica' => true, 'cijena_po_osobi'=> 324) );
	$st->execute( array( 'id_hotela' => 2, 'broj_osoba' => 2, 'tip_kreveta'=>'1 x bracni', 'vlastita_kupaonica' => true, 'cijena_po_osobi'=> 553) );
	$st->execute( array( 'id_hotela' => 3, 'broj_osoba' => 2, 'tip_kreveta'=>'1 x bracni', 'vlastita_kupaonica' => true, 'cijena_po_osobi'=> 256) );
	$st->execute( array( 'id_hotela' => 3, 'broj_osoba' => 2, 'tip_kreveta'=>'2 x odvojen', 'vlastita_kupaonica' => true, 'cijena_po_osobi'=> 407) );
	$st->execute( array( 'id_hotela' => 4, 'broj_osoba' => 2,'tip_kreveta'=>'1 x bracni', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 358) );
	$st->execute( array( 'id_hotela' => 4, 'broj_osoba' => 2,'tip_kreveta'=>'2 x odvojen', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 400) );
	$st->execute( array( 'id_hotela' => 5, 'broj_osoba' => 10,'tip_kreveta'=>'5 x na kat', 'vlastita_kupaonica' => false,'cijena_po_osobi'=> 162) );
	$st->execute( array( 'id_hotela' => 6, 'broj_osoba' => 1,'tip_kreveta'=>'1 x odvojen', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 519) );
	$st->execute( array( 'id_hotela' => 6, 'broj_osoba' => 2,'tip_kreveta'=>'1 x bracni', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 460) );
	$st->execute( array( 'id_hotela' => 6, 'broj_osoba' => 3,'tip_kreveta'=>'3 x odvojen', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 300) );
	$st->execute( array( 'id_hotela' => 7, 'broj_osoba' => 2,'tip_kreveta'=>'1 x bracni', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 910) );
	$st->execute( array( 'id_hotela' => 7, 'broj_osoba' => 2,'tip_kreveta'=>'2 x odvojen', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 910) );
	$st->execute( array( 'id_hotela' => 7, 'broj_osoba' => 3,'tip_kreveta'=>'3 x odvojen', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 700) );
	$st->execute( array( 'id_hotela' => 8, 'broj_osoba' => 1,'tip_kreveta'=>'1 x odvojen', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 798) );
	$st->execute( array( 'id_hotela' => 8, 'broj_osoba' => 2,'tip_kreveta'=>'2 x odvojen', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 440) );
	$st->execute( array( 'id_hotela' => 8, 'broj_osoba' => 3,'tip_kreveta'=>'1 x odvojen, 1 x bracni', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 370) );
	$st->execute( array( 'id_hotela' => 9, 'broj_osoba' => 10,'tip_kreveta'=>'5 x na kat', 'vlastita_kupaonica' => false,'cijena_po_osobi'=> 220) );
	$st->execute( array( 'id_hotela' => 9, 'broj_osoba' => 6,'tip_kreveta'=>'3 x na kat', 'vlastita_kupaonica' => false,'cijena_po_osobi'=> 264) );
	$st->execute( array( 'id_hotela' => 9, 'broj_osoba' => 2,'tip_kreveta'=>'2 x odvojena', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 337) );
	$st->execute( array( 'id_hotela' => 9, 'broj_osoba' => 4,'tip_kreveta'=>'2 x na kat', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 250) );
	$st->execute( array( 'id_hotela' => 9, 'broj_osoba' => 6,'tip_kreveta'=>'3 x na kat', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 330) );
	$st->execute( array( 'id_hotela' => 10, 'broj_osoba' => 2,'tip_kreveta'=>'1 x bracni', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 417) );
	$st->execute( array( 'id_hotela' => 10, 'broj_osoba' => 2,'tip_kreveta'=>'2 x odvojen', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 430) );
	$st->execute( array( 'id_hotela' => 10, 'broj_osoba' => 4,'tip_kreveta'=>'2 x odvojen, 1 x bracni', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 415) );
	$st->execute( array( 'id_hotela' => 11, 'broj_osoba' => 2,'tip_kreveta'=>'2 x odvojena', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 175) );
	$st->execute( array( 'id_hotela' => 11, 'broj_osoba' => 6,'tip_kreveta'=>'2 x na kat, 1 x bracni', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 100) );
	$st->execute( array( 'id_hotela' => 12, 'broj_osoba' => 2,'tip_kreveta'=>'1 x bracni', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 415) );
	$st->execute( array( 'id_hotela' => 12, 'broj_osoba' => 4,'tip_kreveta'=>'2 x odvojen, 2 x bracni', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 370) );
	$st->execute( array( 'id_hotela' => 12, 'broj_osoba' => 3,'tip_kreveta'=>'1 x odvojen, 2 x bracni', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 350) );
	$st->execute( array( 'id_hotela' => 13, 'broj_osoba' => 10,'tip_kreveta'=>'5 x na kat', 'vlastita_kupaonica' => false,'cijena_po_osobi'=> 111) );
	$st->execute( array( 'id_hotela' => 13, 'broj_osoba' => 6,'tip_kreveta'=>'3 x na kat', 'vlastita_kupaonica' => false ,'cijena_po_osobi'=> 119) );
	$st->execute( array( 'id_hotela' => 14, 'broj_osoba' => 2,'tip_kreveta'=>'1 x bracni', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 510) );
	$st->execute( array( 'id_hotela' => 14, 'broj_osoba' => 1,'tip_kreveta'=>'1 x odvojen', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 793) );
	$st->execute( array( 'id_hotela' => 14, 'broj_osoba' => 4,'tip_kreveta'=>'2 x odvojen, 1 x bracni', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 550) );
	$st->execute( array( 'id_hotela' => 15, 'broj_osoba' => 3,'tip_kreveta'=>'1 x odvojen, 1 x bracni', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 320) );
	$st->execute( array( 'id_hotela' => 15, 'broj_osoba' => 2,'tip_kreveta'=>'1 x bracni', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 350) );
	$st->execute( array( 'id_hotela' => 15, 'broj_osoba' => 1,'tip_kreveta'=>'1 x odvojen', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 713) );
	$st->execute( array( 'id_hotela' => 16, 'broj_osoba' => 8,'tip_kreveta'=>'4 x na kat', 'vlastita_kupaonica' => false,'cijena_po_osobi'=> 148) );
	$st->execute( array( 'id_hotela' => 16, 'broj_osoba' => 6,'tip_kreveta'=>'3 x na kat', 'vlastita_kupaonica' => false,'cijena_po_osobi'=> 223) );
	$st->execute( array( 'id_hotela' => 16, 'broj_osoba' => 2,'tip_kreveta'=>'1 x bracni', 'vlastita_kupaonica' => false,'cijena_po_osobi'=> 270) );
	$st->execute( array( 'id_hotela' => 16, 'broj_osoba' => 2,'tip_kreveta'=>'1 x bracni', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 430) );
	$st->execute( array( 'id_hotela' => 17, 'broj_osoba' => 1,'tip_kreveta'=>'1 x odvojen', 'vlastita_kupaonica' => false,'cijena_po_osobi'=> 297) );
	$st->execute( array( 'id_hotela' => 17, 'broj_osoba' => 1,'tip_kreveta'=>'1 x odvojen', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 332) );
	$st->execute( array( 'id_hotela' => 17, 'broj_osoba' => 2,'tip_kreveta'=>'1 x bracni', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 257) );
	$st->execute( array( 'id_hotela' => 18, 'broj_osoba' => 4,'tip_kreveta'=>'2 x bracni', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 108) );
	$st->execute( array( 'id_hotela' => 18, 'broj_osoba' => 2,'tip_kreveta'=>'1 x bracni', 'vlastita_kupaonica' => false,'cijena_po_osobi'=> 124) );
	$st->execute( array( 'id_hotela' => 18, 'broj_osoba' => 2,'tip_kreveta'=>'2 x odvojen', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 130) );
	$st->execute( array( 'id_hotela' => 18, 'broj_osoba' => 2,'tip_kreveta'=>'1 x bracni', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 180) );
	$st->execute( array( 'id_hotela' => 19, 'broj_osoba' => 8,'tip_kreveta'=>'4 x na kat', 'vlastita_kupaonica' => false,'cijena_po_osobi'=> 60) );
	$st->execute( array( 'id_hotela' => 19, 'broj_osoba' => 2,'tip_kreveta'=>'1 x bracni', 'vlastita_kupaonica' => false,'cijena_po_osobi'=> 90) );
	$st->execute( array( 'id_hotela' => 19, 'broj_osoba' => 3,'tip_kreveta'=>'3 x odvojen', 'vlastita_kupaonica' => false,'cijena_po_osobi'=> 90) );
	$st->execute( array( 'id_hotela' => 20, 'broj_osoba' => 2,'tip_kreveta'=>'1 x bracni', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 250) );
	$st->execute( array( 'id_hotela' => 20, 'broj_osoba' => 4,'tip_kreveta'=>'2 x bracni', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 220) );
	$st->execute( array( 'id_hotela' => 21, 'broj_osoba' => 2,'tip_kreveta'=>'1 x bracni', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 235) );
	$st->execute( array( 'id_hotela' => 21, 'broj_osoba' => 4,'tip_kreveta'=>'2 x odvojen, 1 x bracni', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 265) );
	$st->execute( array( 'id_hotela' => 21, 'broj_osoba' => 3,'tip_kreveta'=>'1 x odvojen, 1 x bracni', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 350) );
	$st->execute( array( 'id_hotela' => 22, 'broj_osoba' => 2,'tip_kreveta'=>'1 x bracni', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 232) );
	$st->execute( array( 'id_hotela' => 22, 'broj_osoba' => 6,'tip_kreveta'=>'3 x bracni', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 120) );
	$st->execute( array( 'id_hotela' => 23, 'broj_osoba' => 6,'tip_kreveta'=>'3 x na kat', 'vlastita_kupaonica' => false,'cijena_po_osobi'=>126 ) );
	$st->execute( array( 'id_hotela' => 23, 'broj_osoba' => 4,'tip_kreveta'=>'2 x na kat', 'vlastita_kupaonica' => false,'cijena_po_osobi'=> 149) );
	$st->execute( array( 'id_hotela' => 23, 'broj_osoba' => 2,'tip_kreveta'=>'1 x bracni', 'vlastita_kupaonica' => false,'cijena_po_osobi'=> 204) );
	$st->execute( array( 'id_hotela' => 24, 'broj_osoba' => 2,'tip_kreveta'=>'1 x bracni', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 132) );
	$st->execute( array( 'id_hotela' => 24, 'broj_osoba' => 1,'tip_kreveta'=>'1 x odvojen', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 312) );
	$st->execute( array( 'id_hotela' => 24, 'broj_osoba' => 3,'tip_kreveta'=>'1 x odvojen, 1 x bracni', 'vlastita_kupaonica' => true ,'cijena_po_osobi'=> 190) );
	$st->execute( array( 'id_hotela' => 25, 'broj_osoba' => 6,'tip_kreveta'=>'3 x bracni', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 156) );
	$st->execute( array( 'id_hotela' => 26, 'broj_osoba' => 8,'tip_kreveta'=>'4 x na kat', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 74) );
	$st->execute( array( 'id_hotela' => 26, 'broj_osoba' => 4,'tip_kreveta'=>'2 x na kat', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 88) );
	$st->execute( array( 'id_hotela' => 26, 'broj_osoba' => 2,'tip_kreveta'=>'1 x bracni', 'vlastita_kupaonica' => true,'cijena_po_osobi'=> 317) );
}
catch( PDOException $e ) { exit( "PDO error #7: " . $e->getMessage() ); }

echo "Ubacio korisnike u tablicu users.<br />";

// Ubaci neke ocjene unutra (ovo nije baš pametno ovako raditi, preko hardcodiranih id-eva usera i knjiga)
try
{
	$st = $db->prepare( 'INSERT INTO projekt_ocjene(id_user, id_hotela, ocjena_korisnika, komentar) VALUES (:id_user, :id_hotela, :ocjena_korisnika, :komentar)' );

	$st->execute( array( 'id_user' => 1, 'id_hotela' => 2, 'ocjena_korisnika' => 8.2, 'komentar'=>'Jako lijep smještaj, međutim malo preskup.' ));
	$st->execute( array( 'id_user' => 3, 'id_hotela' => 1, 'ocjena_korisnika' => 8.7, 'komentar'=>'Odličan za mlade koji žele upoznati nove ljude.' ));
	$st->execute( array( 'id_user' => 5, 'id_hotela' => 15, 'ocjena_korisnika' => 9.0, 'komentar'=>'Preporuke!') );
	$st->execute( array( 'id_user' => 3, 'id_hotela' => 25, 'ocjena_korisnika' => 9.5, 'komentar'=>'Odlična lokacija!') );
}
catch( PDOException $e ) { exit( "PDO error #8: " . $e->getMessage() ); }

echo "Ubacio ocjene u tablicu projekt_ocjene.<br />";

?>
