<?php require_once __SITE_PATH . '/view/_header.php'; ?>
<link rel="stylesheet" href="<?php echo __SITE_URL;?>/css/style.css"> 
	<form method="post" action="<?php echo __SITE_URL; ?>/index.php?rt=users/check_login">
	Ime: <input type="text" name="ime" /><br />
  Prezime: <input type="text" name="prezime" /><br />
	Password: <input type="password" name="pass"><br />
	<br /><br />
	<button class='ostali' type="submit">Ulogiraj se!</button>
	</form>

<?php require_once __SITE_PATH . '/view/_footer.php'; ?>
