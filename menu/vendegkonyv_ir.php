<?php
session_start();

$fajlnev = "vendegkonyv.txt";
$sorszamFajl = "sorszam.txt";

// Ellenőrizzük, hogy létezik-e a sorszam.txt fájl. Ha nem, akkor létrehozzuk és beállítjuk a kezdő értéket.
if (!file_exists($sorszamFajl)) {
    file_put_contents($sorszamFajl, 0); // Kezdő sorszámot 0-ra állítjuk.
}

// Számláló lekérése
$sorszam = (int)file_get_contents($sorszamFajl);

// Ha a nevet nem adták meg, akkor hibát dobunk
if ($_POST['nev'] == "") die("<script> alert('Nem adtad meg a neved!') </script>");

// Ha az üzenet hossza kisebb mint 10 karakter, akkor hibát dobunk
if (mb_strlen($_POST['uzi']) < 10) die("<script> alert('Írj legalább 10 karaktert!') </script>");

// Ha a CAPTCHA nem egyezik, akkor hibát dobunk
if (isset($_POST['captcha_challenge']) && $_POST['captcha_challenge'] != $_SESSION['captcha_text']) {
    die("<script> alert('Sikertelen validálás!') </script>");
}

// Távolítsuk el a potenciálisan veszélyes karaktereket
$nev = str_replace(";", "", htmlspecialchars($_POST['nev'], ENT_QUOTES, 'UTF-8'));
$uzenet = str_replace(";", "", htmlspecialchars($_POST['uzi'], ENT_QUOTES, 'UTF-8'));

// Növeljük a számlálót és mentjük vissza a fájlba
$sorszam++;
file_put_contents($sorszamFajl, $sorszam);

// Emotikonok helyettesítése képekkel
function replace_emoticons($text) {
    $emoticons = [
        ':)' => ':)',
        ':D' => ':D',
        ':P' => ':P',
        ';)' => ';)',
        ':$' => ':$',
        ':(' => ':(',
        'B)' => 'B)',
        ';(' => ';(',
        ':-*' => ':-*'
    ];

    return str_replace(array_keys($emoticons), array_values($emoticons), $text);
}

// Az üzenetben található emotikonok helyettesítése
$uzenet = replace_emoticons($uzenet);

// A fájlba írás
$kep = $_FILES['fajl'];
$sz = date("Y-m-d H:i:s") . ";" . $sorszam . ";" . $nev . ";" . str_replace("\r\n", "<br>", $uzenet) . ";" . $kep['name'] . "\r\n";

$fp = fopen($fajlnev, "a");
fwrite($fp, $sz);
fclose($fp);

// A sikeres mentés után újratöltjük az oldalt
print "
    <script>  parent.location.href = parent.location.href </script>
";
?>
