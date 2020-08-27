<?php require_once __SITE_PATH . '/view/_header.php';  ?>
<link rel="stylesheet" href="<?php echo __SITE_URL;?>/css/style2.css">


<p>Možete odabrati više kriterija sortiranja i više kriterija za filter. Ako ne odaberete ništa od navedenog ispisat će Vam se svi hoteli u tom gradu.</p>
<form method="post" action="<?php echo __SITE_URL; ?>/index.php?rt=users/check_sort_filt">
<h3>Odaberite kriterij/e po kojima želite sortirati:</h3>
<t1>
<input type="checkbox" name="sort[]" value = "cijena_po_osobi"> Cijena <br />
<input type="checkbox" name="sort[]" value = "udaljenost_od_centra"> Udaljenost od centra <br />
<input type="checkbox" name="sort[]" value = "broj_osoba"> Broj osoba u sobi <br />
<input type="checkbox" name="sort[]" value = "ocjena"> Ocjena <br />
<input type="checkbox" name="sort[]" value = "broj_zvjezdica"> Broj zvjezdica <br />
</t1>
<br />
<h3>Odaberite kriterij/e po kojima želite filtrirati:</h3>
<t1>
<input type="checkbox" name="filter[]" value = "cijena_po_osobi"> Maksimalna cijena <input type="text" name="cijena" size="6"> kn <br />
<input type="checkbox" name="filter[]" value = "udaljenost_od_centra"> Udaljenost od centra najviše <input type="text" name="udaljenost" size="3"> km <br />
<input type="checkbox" name="filter[]" value = "broj_osoba"> Broj osoba u sobi <input type="text" name="osobe" size="3"> <br />
<input type="checkbox" name="filter[]" value = "tip_kreveta"> Tip kreveta u sobi
<dd> broj <input type="text" name="bracni" size="3"> bracnih </dd>
<dd> broj <input type="text" name="odvojeni" size="3"> odvojenih </dd>
<dd> broj <input type="text" name="na_kat" size="3"> na kat </dd>
<br />
<input type="checkbox" name="filter[]" value = "ocjena"> Minimalna ocjena <input type="text" name="ocjena" size="3"> (1-10) <br />
<input type="checkbox" name="filter[]" value = "broj_zvjezdica"> Minimalni broj zvjezdica <input type="text" name="zvjezdice" size="3"> (1-5) <br />
<input type="checkbox" name="filter[]" value = "vlastita_kupaonica"> Obvezna kupaonica<br />
</t1>
<br />
<button class='ostali' type="submit" name="button">Dalje</button><br /><br />
<button class='ostali' type="submit" name="natrag">Natrag</button><br /><br />
<button class='ostali' type="submit" name="odlogiraj">Odlogiraj se</button>
</form>

<?php require_once __SITE_PATH . '/view/_footer.php'; ?>
