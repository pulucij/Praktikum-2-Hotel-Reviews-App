<?php require_once __SITE_PATH . '/view/_header.php';  ?>
<link rel="stylesheet" href="<?php echo __SITE_URL;?>/css/style2.css">

<form method="post" action="<?php echo __SITE_URL; ?>/index.php?rt=users/check_odabir">
<br />
<big>
<input type="radio" name="odabir" value = "Amsterdam" class="big"> Amsterdam <br />
<input type="radio" name="odabir" value = "Atena" class="big"> Atena <br />
<input type="radio" name="odabir" value = "Berlin" class="big"> Berlin <br />
<input type="radio" name="odabir" value = "Lisabon" class="big"> Lisabon <br />
<input type="radio" name="odabir" value = "Moskva" class="big"> Moskva <br />
<input type="radio" name="odabir" value = "Pariz" class="big"> Pariz <br />
<input type="radio" name="odabir" value = "Prag" class="big"> Prag <br />
</big>
<br />
<button class='ostali' type="submit" name="button">Dalje</button><br /><br />
<button class='ostali' type="submit" name="odlogiraj">Odlogiraj se</button>
</form>

<?php require_once __SITE_PATH . '/view/_footer.php'; ?>
