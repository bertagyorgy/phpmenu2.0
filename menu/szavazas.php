<?php

// A fájl elérési útja
$fajl = "szavazatok.txt";


// Ellenőrizd, hogy létezik-e a fájl, ha nem, hozz létre egy alapértelmezett fájlt
if (!file_exists($fajl)) {
    file_put_contents($fajl, "0||0||0||0");
}

// Beolvassuk a fájl tartalmát
$content = file($fajl);
$array = explode("||", trim($content[0])); // Az adatokat || alapján bontjuk

// A változók inicializálása
$bab = (int)$array[0];
$gulyas = (int)$array[1];
$para = (int)$array[2];
$tojas = (int)$array[3];

// Ha a felhasználó szavazott

if (isset($_POST['vote'])) {
    $vote = (int)$_POST['vote'];

    // A szavazat hozzáadása a megfelelő változóhoz
    if (!$_SESSION['ittjártam']){
        if ($vote == 0) {
            $bab++;
        } elseif ($vote == 1) {
            $gulyas++;
        } elseif ($vote == 2) {
            $para++;
        } elseif ($vote == 3) {
            $tojas++;
        }
    }


    // Az új szavazatokat összefűzzük és elmentjük a fájlba
    $insertvote = $bab . "||" . $gulyas . "||" . $para . "||" . $tojas;
    file_put_contents($fajl, $insertvote);

    // Session beállítása, hogy ne lehessen többször szavazni ugyanazzal a böngészővel
    $_SESSION['ittjártam'] = true;
}

// Ha a felhasználó már szavazott, jelenítse meg az eredményeket
$ossz = $bab+$gulyas+$para+$tojas;
if (isset($_SESSION['ittjártam'])) {
    echo "<h3>Szavazatok eredményei:</h3>";
    echo "Bableves:        " . $bab .    " db " . round($bab/$ossz*100,1   ). "% </span><br>";
    echo "Gulyásleves:     " . $gulyas . " db " . round($gulyas/$ossz*100,1). "% </span><br>";
    echo "Paradicsomleves: " . $para .   " db " . round($para/$ossz*100,1  ). "% </span><br>";
    echo "Tojásleves:      " . $tojas .  " db " . round($tojas/$ossz*100,1 ). "% </span><br>";
} else {
    // Ha a felhasználó még nem szavazott, akkor jelenítse meg a szavazólapot
    echo '
    <form action="" method="post" class="szavazodoboz">
        <h3>Mit választasz?</h3>

        <input type="radio" name="vote" value="0">Bableves <br>
        <input type="radio" name="vote" value="1">Gulyásleves <br>
        <input type="radio" name="vote" value="2">Paradicsomleves <br>
        <input type="radio" name="vote" value="3">Tojásleves <br>

        <input type="submit" value="szavazok" style="margin: 24px 0 0 24px;">
    </form>';
}
?>