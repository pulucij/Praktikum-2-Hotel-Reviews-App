<?php require_once __SITE_PATH . '/view/_header.php';  ?>
<link rel="stylesheet" href="<?php echo __SITE_URL;?>/css/style2.css">

<h2><?php echo $hotel->ime_hotela; ?></h2>
<table><tr><th>Adresa hotela</th><th>Udaljenost od centra</th><th>Ocjena</th><th>Broj zvjezdica</th></tr>
<tr><td> <?php echo $hotel->adresa_hotela; ?> </td><td><?php echo $hotel->udaljenost_od_centra; ?> </td>
  <td> <?php echo $hotel->ocjena; ?> </td><td><?php echo $hotel->broj_zvjezdica; ?> </td></tr></table>

<h3>Ponuđene sobe: </h3>
<?php if(count($hotel->sobe)!==0) { ?>
<table><tr><th>Broj osoba</th><th>Tip kreveta</th><th>Vlastita kupaonica</th><th>Cijena po osobi za 1 noćenje</th>
<th>Izračunaj po sobi za 1 noćenje</th>
<th>Izračunaj za željeni broj noćenja i željeni broj osoba<br/>Unesi broj dana <input type="text" id="broj" size="3"/> 
i broj osoba <input type="text" id="brojos" size="3"/></th></tr>
<?php foreach($hotel->sobe as $soba)
{ ?>
<tr><td><?php echo $soba->broj_osoba; ?></td><td><?php echo $soba->tip_kreveta; ?></td>
  <td><?php echo $soba->vlastita_kupaonica; ?></td><td><?php echo $soba->cijena_po_osobi; ?></td>
  <td><button class='tablica_gumb' id=<?php echo "1noc".",".$soba->broj_osoba .",".$soba->cijena_po_osobi;?>>Izračunaj!</button></td>
  <td><button class='tablica_gumb' id=<?php echo "nnoc".",".$soba->broj_osoba .",".$soba->cijena_po_osobi;?>>Izračunaj!</button></td>
</tr>
<?php } ?>
</table> <?php } else echo 'Trenutno nema ponuđenih soba'; ?>

<script>
//obradi izračunavanja, iznos ispisi u alert box
$(document).ready(function(){
	$("button").on("click", function(){
		var id=this.id;
		if(id.split(",")[0]==="1noc")
			alert("Cijena po sobi po noćenju iznosi:  "+id.split(",")[1]*id.split(",")[2]+" kn.");
		else if(id.split(",")[0]==="nnoc"){
			brnoc=$("#broj").val();
			bros=$("#brojos").val();
			if(bros>parseInt(id.split(",")[1]))
				alert("U odabranu sobu ne može stati toliko ljudi.");
			else{
				if (brnoc>0){
					if(bros>0)
						alert("Cijena za "+brnoc+" nocenja za "+bros+" osobe iznosi:  "+bros*id.split(",")[2]*brnoc+" kn.");
					else 
						alert("Trebate upisati željeni broj osoba (broj veći od 0).");
				}
				else
					alert("Trebate upisati željeni broj noćenja (broj veći od 0).");
			}
		}
	});
});
</script>


<h3>Komentari i ocjene: </h3>
<?php if(count($komentari)!==0) { ?>
<table><tr><th>Ime</th><th>Prezime</th><th>Komentar</th><th>Ocjena</th></tr>
<?php foreach($komentari as $komentar){ ?>
  <tr><td> <?php echo $komentar->ime_korisnika; ?> </td><td><?php echo $komentar->prezime_korisnika; ?> </td>
    <td> <?php echo $komentar->komentar; ?> </td><td><?php echo $komentar->ocjena_korisnika; ?> </td></tr>
<?php } ?>
</table> <?php } else echo 'Trenutno nema komentara za ovaj hotel'; ?>

<h3>Pogledaj na karti: </h3>
<div id="mapa"></div>
<br/>
<h3>Pregledaj slike:  </h3>
Klikni na lijevi dio slike da bi vidio prethodnu te na desni dio slike da bi vidio sljedeću. <br/><br/>
<canvas height="500" width="800" id="canvas"></canvas>

<script>
var sir = [52.326110, 52.383840, 52.360590, 52.332880, 52.369030, 48.847500, 48.874670, 48.827110, 48.878540, 38.727700,
  38.726400, 38.715990, 38.721650, 52.521751, 52.512980, 52.530160, 55.780980, 55.777490, 55.744430, 55.758940, 37.985950,
  37.978230, 37.980150, 50.101140, 50.086980, 50.080800];
var duz = [4.953490, 4.902230, 4.915950, 4.920040, 4.897320, 2.371860, 2.305720, 2.348530, 2.370630, -9.158480, -9.142370,
  -9.143810, -9.146190, 13.411500, 13.405100, 13.401940, 37.620820, 37.580170, 37.635880, 37.605380, 23.720780, 23.724300,
  23.727740, 14.397590, 14.433370, 14.417900];
$( document ).ready( function()
{
  var id = "<?php echo $hotel->id ?>";
  var openLayerMap = new ol.Map(
        {
            target: 'mapa', // id elementa gdje će se nacrtati mapa
            layers: // koje slojeve ćemo prikazati na mapi
            [
                // OpenStreetMap
                new ol.layer.Tile( { source: new ol.source.OSM() } )
            ],
            view: new ol.View(
            {
                center: ol.proj.fromLonLat( [duz[id-1], sir[id-1]] ), // zemljopisne koord. centra mape
                zoom: 17
            })
        });
    var marker = new ol.Feature({
        geometry: new ol.geom.Point(ol.proj.transform([duz[id-1], sir[id-1]], 'EPSG:4326', 'EPSG:3857')),
    });
    var markers = new ol.source.Vector({
        features: [marker]
    });

    var markerVectorLayer = new ol.layer.Vector({
        source: markers,
    });
    openLayerMap.addLayer(markerVectorLayer);

    //dohvacanje slika, za svaki hotel imamo 5 slika, te one imaju oznake jednake (id-1)*5+1, ... (id-1)*5+5
    var imgArray = new Array();
    var oznaka = (id-1)*5+1;
    for(var i=0; i<5; i++){
      imgArray[i] = new Image();
      imgArray[i].src = 'images/img'+oznaka+'.jpg';
      oznaka++;
    }

    //iscrtavanje slika
    var canvas = $( "#canvas" ).get(0);
    var ctx = canvas.getContext( "2d" );
    var k=0;

    $( imgArray[k] ).on( "load", function() {
      ctx.drawImage(this, 0, 0, 800, 500);
    });

    function changeImage(event){

      var rect = canvas.getBoundingClientRect();
      var x = event.clientX - rect.left;
      if(x<=400) k--;
      else if(x>400) k++;
      if(k<0) k=4;
      if(k>=5) k=0;
      ctx.drawImage(imgArray[k], 0, 0, 800, 500);
    }

    $("#canvas").on("click", changeImage);

});
</script>

<br /><br />

<h3>Dodaj komentar: </h3>
<form method="post" action="<?php echo __SITE_URL; ?>/index.php?rt=users/check_comments">
  Ocjena: <input type="text" name="ocjena" /><br />
  Opis: <br />
  <textarea name="komentar" rows="10" cols="50">Ovdje napišite komentar.</textarea><br />
<button class='tablica_gumb' type="submit" name="komentar_gumb">Dodaj komentar</button><br /><br />

<br /><br />

<button class='ostali' type="submit" name="natrag">Natrag</button><br /><br />
<button class='ostali' type="submit" name="odlogiraj">Odlogiraj se</button>
</form>

<?php require_once __SITE_PATH . '/view/_footer.php'; ?>
