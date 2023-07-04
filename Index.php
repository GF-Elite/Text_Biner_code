<?php
function teksKeBiner($teks) {
    $biner = '';
    $panjang = strlen($teks);

    for ($i = 0; $i < $panjang; $i++) {
        $kodeBiner = decbin(ord($teks[$i]));
        $biner .= str_pad($kodeBiner, 8, '0', STR_PAD_LEFT) . ' ';
    }

    return rtrim($biner);
}

function binerKeTeks($biner) {
    $teks = '';
    $blokBiner = explode(' ', $biner);

    foreach ($blokBiner as $blok) {
        $kodeBiner = trim($blok);
        $kodeDesimal = bindec($kodeBiner);
        $teks .= chr($kodeDesimal);
    }

    return $teks;
}

// Proses input dan konversi
$input = isset($_POST['input']) ? $_POST['input'] : '';
$konversi = isset($_POST['konversi']) ? $_POST['konversi'] : '';
$hasil = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $input !== '' && $konversi !== '') {
    if ($konversi === 'teks-ke-biner') {
        $hasil = teksKeBiner($input);
    } elseif ($konversi === 'biner-ke-teks') {
        $hasil = binerKeTeks($input);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $error = 'Harap isi kedua bidang input dengan benar.';
}

// Fungsi untuk menambahkan script SweetAlert
function tambahkanSweetAlertScript() {
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.all.min.js"></script>';
}

// Fungsi untuk menampilkan SweetAlert
function tampilkanSweetAlert($icon, $title, $text, $position = 'top-end', $timer = 2000) {
    echo "
        <script>
            Swal.fire({
                icon: '{$icon}',
                title: '{$title}',
                text: '{$text}',
                timer: {$timer},
                timerProgressBar: true,
                toast: true,
                position: '{$position}',
                showConfirmButton: false
            });
        </script>
    ";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Konversi Teks - Biner</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.min.css">
</head>
<body>
    <h1>Konversi Teks - Biner</h1>

    <form method="POST" action="">
        <label for="input">Masukkan Teks atau Biner:</label><br>
        <textarea name="input" id="input" rows="5" cols="30"><?php echo $input; ?></textarea><br><br>

        <input type="radio" name="konversi" value="teks-ke-biner" <?php echo $konversi === 'teks-ke-biner' ? 'checked' : ''; ?>>
        <label for="konversi">Teks ke Biner</label><br>

        <input type="radio" name="konversi" value="biner-ke-teks" <?php echo $konversi === 'biner-ke-teks' ? 'checked' : ''; ?>>
        <label for="konversi">Biner ke Teks</label><br><br>

        <input type="submit" value="Konversi">
    </form>

    <?php if ($hasil !== ''): ?>
        <h2>Hasil Konversi:</h2>
        <textarea readonly rows="5" cols="30" id="hasil"><?php echo $hasil; ?></textarea><br><br>
        <button onclick="copyToClipboard()">Salin Hasil</button>
    <?php elseif ($error !== ''): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php
    if ($hasil !== '') {
        tambahkanSweetAlertScript();
        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                tampilkanSweetAlert('success', 'Berhasil!', 'Hasil konversi berhasil disalin ke clipboard!');
            });
        </script>";
    }
    ?>

    <script>
        function copyToClipboard() {
            var hasil = document.getElementById("hasil");
            hasil.select();
            document.execCommand("copy");
        }
    </script>
</body>
</html>
