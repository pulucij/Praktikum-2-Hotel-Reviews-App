<?php require_once __SITE_PATH . '/view/_header.php';  ?>
<link rel="stylesheet" href="<?php echo __SITE_URL;?>/css/style2.css"> 

<h4>Filtri: 
<?php if(count($filtri)===0) 
		echo 'niste odabrali niti jedan kriterij';  echo '</h4>';
	  foreach($filtri as $var)
        echo $var.'<br />';
      
$i=0; ?> 

<form method="post" action="<?php echo __SITE_URL; ?>/index.php?rt=users/check_details">
<?php 
	foreach($hoteli as $popis)
   { 
		if(count($popis)!==0) {?>
        
		<h4>Sortirano po: <?php if(count($hotel_kriteriji)===0) echo 'niste odabrali niti jedan kriterij';
		
		if(isset($hotel_kriteriji[$i])) echo $hotel_kriteriji[$i].' <br />'; ?> </h4>

          <?php 
		  if(!isset($hotel_kriteriji[$i]) ||
              ($hotel_kriteriji[$i] === 'udaljenosti od centra' || $hotel_kriteriji[$i] === 'ocjeni' || $hotel_kriteriji[$i] === 'broju zvjezdica'))
          {?>
            <table><tr><th>Ime hotela</th><th>Adresa hotela</th><th>Udaljenost od centra</th><th>Ocjena</th><th>Broj zvjezdica</th>
              <th> </th></tr>
		  
			<?php
			foreach($popis as $var)
			{?>
            <tr><td> <?php echo $var->ime_hotela; ?> </td><td> <?php echo $var->adresa_hotela; ?> </td><td>
              <?php echo $var->udaljenost_od_centra; ?> </td><td> <?php echo $var->ocjena; ?> </td><td>
                <?php echo $var->broj_zvjezdica; ?> </td>
                <td><button class='tablica_gumb' type="submit" name="detalji" value="<?php echo $var->ime_hotela; ?>">Detalji</button><br /></td></tr>
        <?php }
        }
		
		
		
        if(isset($hotel_kriteriji[$i]) && $hotel_kriteriji[$i] === 'cijeni po osobi po noćenju')
		{
		  $j=0;?>
          <table><tr><th>Ime hotela</th><th>Ocjena hotela</th><th>Broj zvjezdica</th><th>Cijena jedne od ponuđenih soba</th></tr>
		  <th> </th></tr> <?php 
          foreach($popis as $var)
          {?>
          <tr><td> <?php echo $var->ime_hotela; ?> </td><td> <?php echo $var->ocjena; ?> </td><td>
            <?php echo $var->broj_zvjezdica; ?> </td><td> <?php echo $sort_cijene[$j]; ?> </td>
            <td><button class='tablica_gumb' type="submit" name="detalji" value="<?php echo $var->ime_hotela; ?>">Detalji</button><br /></td></tr>
      <?php $j++; }
        }
        
		
		
		if (isset($hotel_kriteriji[$i]) && $hotel_kriteriji[$i] === 'broju osoba u sobi')
		{
          $j=0;?>
          <table><tr><th>Ime hotela</th><th>Ocjena hotela</th><th>Broj zvjezdica</th><th>Broj osoba u jednoj od ponuđenih soba</th>
		  <th> </th></tr> <?php
          foreach($popis as $var)
          {?>
          <tr><td> <?php echo $var->ime_hotela; ?> </td><td> <?php echo $var->ocjena; ?> </td><td>
            <?php echo $var->broj_zvjezdica; ?> </td><td> <?php echo $sort_osobe[$j]; ?> </td>
            <td><button class='tablica_gumb' type="submit" name="detalji" value="<?php echo $var->ime_hotela; ?>">Detalji</button><br /></td></tr>
      <?php $j++; }
		}
      $i++; ?>
    </table> 
	<?php } 
		else 
		{ 
			echo 'Nema hotela koji zadovoljavaju Vaše kriterije'.'<br /><br />'; break; 
		}  
	} ?>

      <button class='ostali' name="natrag">Natrag</button><br /><br />
      <button class='ostali' name="odlogiraj">Odlogiraj se</button>
    </form>

<?php require_once __SITE_PATH . '/view/_footer.php'; ?>
