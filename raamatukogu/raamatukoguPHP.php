<?php
require_once('connect.php');
global $yhendus;
//andmete lisamine tabelise
if (isset($_REQUEST['lisamisvorm'])&& !empty($_REQUEST['raamatuNimi'])){
    $paring=$yhendus->prepare(
        "INSERT INTO raamatu(raamatuNimi,autor,kirjutamiseAasta,trukkikodaID,pilt) VALUES (?,?,?,?,?)"
    );
    $paring->bind_param("sssis", $_REQUEST["raamatuNimi"], $_REQUEST["autor"],$_REQUEST["kirjutamiseAasta"],$_REQUEST["trukkikodaID"],$_REQUEST["pilt"]);
    $paring->execute();
}
if (isset($_REQUEST["kustuta"])){
    $paring=$yhendus->prepare("DELETE FROM raamatu WHERE raamatuID=?");
    $paring->bind_param("i",$_REQUEST["kustuta"]);
    $paring->execute();

}
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>Raamatukogu</title>
    <link rel="stylesheet" href="raamatukoguStyle.css">
</head>
<body>
<h1>Raamatukogu andmebaas</h1>
<div id="menu">
    <ul class="fontst">
        <?php
        //tabeli sisu näitamine
        $paring=$yhendus->prepare("SELECT raamatuID, raamatuNimi,autor,kirjutamiseAasta,trukkikoda.trukkikoda,pilt FROM raamatu,trukkikoda where raamatu.trukkikodaID = trukkikoda.trukkikodaID");
        $paring->bind_result($raamatuID, $raamatuNimi,$autor,$kirjutamiseAasta,$trukkikodaID,$pilt);
        $paring->execute();
        while($paring->fetch()){
            echo "<li><a href='?id=$raamatuID'>$raamatuNimi</a></li>";
        }
        echo "</ul>";
        echo "<a href='?lisaraamat=zxc' style='font-size: 20pt'>Lisa raamatu</a>";
        ?>
</div>
<div id="sisu">
    <?php
    if (isset($_REQUEST["id"])){
        $paring=$yhendus->prepare("SELECT raamatuID, raamatuNimi,autor,kirjutamiseAasta,trukkikoda.trukkikoda,pilt FROM raamatu,trukkikoda WHERE raamatuID=? and raamatu.trukkikodaID = trukkikoda.trukkikodaID");
        $paring->bind_param("i", $_REQUEST["id"]);
        $paring->bind_result($raamatuID, $raamatuNimi,$autor,$kirjutamiseAasta,$trukkikodaID,$pilt);
        $paring->execute();

        if ($paring->fetch()){
            echo "<div class='fontst'><h2>" . htmlspecialchars($raamatuNimi)."</h2>";
            echo "<img src='$pilt' alt='pilt'><br>";
            echo "Autor: ".htmlspecialchars($autor)."<br>";
            echo "Kirjutamise aasta: ".htmlspecialchars($kirjutamiseAasta)."<br>";
            echo "Trukkikoda: ".htmlspecialchars($trukkikodaID)."<br>";
            echo "<br><a href='?kustuta=".$_REQUEST["id"]."'>Kustuta</a>";
            echo "</div>";
        }
    }
    else if (isset($_REQUEST["lisaraamat"])){
        echo '<h2>Uue raamatu lisamine</h2>';
        echo '<form name="uusraamat" method="post" action="?">';
        echo '<input type="hidden" name="lisamisvorm" value="zxc">';
        echo '<input type="text" name="raamatuNimi" class="lisaform" placeholder="Raamatu nimi"><br>';
        echo '<input type="text" name="autor" class="lisaform" placeholder="Raamatu autor"><br>';
        echo '<input type="number" name="kirjutamiseAasta" class="lisaform" placeholder="kirjutamise Aasta"><br>';
        echo '<input type="text" name="pilt" class="lisaform" placeholder="Pilt"><br>';
        echo '<input type="hidden" name="trukkikodaID" class="lisaform" min="1" max="1" value="1"><br>';
        echo '<label for="trukkikodaID"><br>';
        echo '<input type="submit" value="OK">';
        echo '</form>';
    }
    else {
        echo "<h2>Vali raamatu palun!</h2>";
    }
    ?>
</div>
</body>
</html>
