<?php
session_start();

// Engedélyezett karakterek
$permitted_chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ123456789';

// Véletlenszerű karakterlánc generálása
function generate_string($input, $strength = 6) {
    $input_length = strlen($input);
    $random_string = '';
    for ($i = 0; $i < $strength; $i++) {
        $random_string .= $input[mt_rand(0, $input_length - 1)];
    }
    return $random_string;
}

// CAPTCHA szöveg generálása
$string_length = 6;
$captcha_string = generate_string($permitted_chars, $string_length);

// Tároljuk a szöveget a munkamenetben
$_SESSION['captcha_text'] = $captcha_string;

// Kép létrehozása
$image = imagecreatetruecolor(200, 50);

// Színek előállítása
$background_color = imagecolorallocate($image, rand(200, 255), rand(200, 255), rand(200, 255));
$text_color = imagecolorallocate($image, rand(0, 100), rand(0, 100), rand(0, 100));

// Háttér kitöltése
imagefill($image, 0, 0, $background_color);

// Zaj hozzáadása
for ($i = 0; $i < 10; $i++) {
    $line_color = imagecolorallocate($image, rand(150, 200), rand(150, 200), rand(150, 200));
    imageline($image, rand(0, 200), rand(0, 50), rand(0, 200), rand(0, 50), $line_color);
}

// Szöveg megjelenítése képen
$font_size = 5; // Beépített betűméret (1-5)
$x_spacing = 200 / ($string_length + 1); // Karakterek közötti távolság

for ($i = 0; $i < $string_length; $i++) {
    $x = ($i + 1) * $x_spacing - 10; // X koordináta
    $y = rand(10, 25);               // Y koordináta
    imagestring($image, $font_size, $x, $y, $captcha_string[$i], $text_color);
}

// Kép megjelenítése
header('Content-type: image/png');
imagepng($image);

// Takarítás
imagedestroy($image);
?>
