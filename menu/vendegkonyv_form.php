<h2>Vendégkönyv</h2>

<h3>Kérlek, mondd el a véleményed!</h3>

<form action='vendegkonyv_ir.php' class="vendegkonyv" method='post' target="kisablak" enctype='multipart/form-data'>
    <div class="elem-group">
        <label for="nev" id="nev">Neved:</label><br>
        <input type='text' name='nev'><br><br>

        <label for="uzi">Üzeneted:</label><br>
        <textarea rows=8 cols=48 name='uzi'></textarea><br><br>

        <label for="kep">Kapcsolódó kép:</label><br>
        <input type='file' name='kep'><br><br>

        <label for="captcha">Kérlek töltsd ki a robotok elleni védelemhez:</label><br>
        <img src="captcha.php" alt="CAPTCHA" class="captcha-image">
        <i class="fas fa-redo refresh-captcha"></i><br>

        <input type="text" id="captcha" name="captcha_challenge" pattern="[A-Z0-9]{6}" required><br><br>
        <input type='submit' value='Küldés'>
    </div>

</form>
<script>
    var refreshButton = document.querySelector(".refresh-captcha");
    refreshButton.onclick = function() {
        document.querySelector(".captcha-image").src = 'captcha.php?' + Date.now();
    }
    </script>
<hr>

<?php
$fajlnev = "vendegkonyv.txt";
if (file_exists($fajlnev)) {
    // Fájl megnyitása olvasásra
    $fp = fopen($fajlnev, "r");
    $kommentek = [];

    // Minden sor beolvasása és hozzáadása a $kommentek tömbhöz
    while ($sor = fgets($fp)) {
        $adatok = explode(";", $sor);
        $kommentek[] = $adatok;
    }

    fclose($fp);

    // A hozzászólások sorrendjének megfordítása
    $kommentek = array_reverse($kommentek);

    // Emotikonok helyettesítése képekkel
    function replace_emoticons($text) {
        $emoticons = [
            ':)' => '<img src="img/Smile.png" style="width:24px;height:24px;">',
            ':D' => '<img src="img/Laugh.png" style="width:24px;height:24px;">',
            ':P' => '<img src="img/Silly.png" style="width:24px;height:24px;">',
            ';)' => '<img src="img/Wink.png" style="width:24px;height:24px;">',
            ':$' => '<img src="img/Blush.png" style="width:24px;height:24px;">',
            ':(' => '<img src="img/Sad.png" style="width:24px;height:24px;">',
            'B)' => '<img src="img/Cool.png" style="width:24px;height:24px;">',
            ';(' => '<img src="img/cry.png" style="width:24px;height:24px;">',
            ':-*' => '<img src="img/Kissy.png" style="width:24px;height:24px;">'
        ];

        return str_replace(array_keys($emoticons), array_values($emoticons), $text);
    }

    // Kiírás
    foreach ($kommentek as $adatok) {
        // Az emotikonok helyettesítése a bejegyzés szövegében
        $adatok[3] = replace_emoticons($adatok[3]);
        print "
        <div style='background-color: lightgrey; margin:12px; border-radius:7px; padding: 10px;'>
            <div style='float:right;'>$adatok[0]</div>
            <b>$adatok[1]. $adatok[2]</b><hr>
            <i>$adatok[3]</i>
        </div>
        ";
    }
}
?>








