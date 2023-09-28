<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vigenere Chiper</title> 
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f0f0f0;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 50px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        h1 {
            color: #333;
        }
        label {
            display: block;
            margin-bottom: 10px;
             font-size: 14px;
             max-width: 100px
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
        }
        button {
            background-color: #007BFF;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        p.result {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Hill Chiper</h1>
        <form method="POST">
            <label for="text">Teks:</label>  <!-- buat judul dari halaman-->
            <!-- Input teks yang akan dienkripsi atau didekripsi -->
            <input type="text" id="text" name="text" required value="<?php echo isset($_POST['text']) ? htmlspecialchars($_POST['text']) : ''; ?>" size="90">
            <!-- Tombol untuk mengirim form dengan tindakan "encrypt" -->
            <button type="submit" name="action" value="encrypt">Enkripsi</button>
            <!-- Tombol untuk mengirim form dengan tindakan "decrypt" -->
            <button type="submit" name="action" value="decrypt">Deskripsi</button>
        </form>
        <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Mengambil teks yang diinputkan oleh pengguna dari form
            $text = $_POST["text"];
            
            // Mengambil tindakan yang dipilih oleh pengguna (encrypt atau decrypt) dari form
            $action = $_POST["action"];
            
            // Matriks kunci Hill Cipher yang diambil dari teks Clariva Meydieta Widagdo
            $keyMatrix = [
                [2, 11, 0],
                [17, 8, 21],
                [12, 4, 24]
            ];
            
            // Memanggil fungsi hillCipher() untuk melakukan enkripsi atau dekripsi teks
            // dengan menggunakan teks, matriks kunci, dan tindakan yang dipilih oleh pengguna
            $resultText = hillCipher($text, $keyMatrix, $action);
            
            // Menampilkan hasil enkripsi atau dekripsi di bawah form
            echo '<p class="result"><strong>Hasil:</strong> ' . $resultText . '</p>';
        }
        
        function hillCipher($text, $keyMatrix, $action) {
            $result = ""; // Variabel untuk menyimpan teks hasil enkripsi atau dekripsi.
            $text = preg_replace('/[^a-z]/', '', strtolower($text)); // Hanya mengambil karakter huruf kecil dan mengabaikan karakter lainnya.
            $textLength = strlen($text); // Menghitung panjang teks input.
            $matrixSize = count($keyMatrix); // Ukuran matriks kunci (biasanya 3x3).
            
            // Fungsi untuk mengalikan matriks kunci dengan vektor karakter
            function multiplyMatrixVector($matrix, $vector) {
                $result = array_fill(0, count($matrix), 0);
                for ($i = 0; $i < count($matrix); $i++) {
                    for ($j = 0; $j < count($vector); $j++) {
                        $result[$i] += $matrix[$i][$j] * $vector[$j];
                    }
                    $result[$i] %= 26; // Modulo 26 karena alfabet Inggris.
                }
                return $result;
            }
            
            // Fungsi untuk menghitung determinan matriks
            function determinant($matrix) {
                return ($matrix[0][0] * ($matrix[1][1] * $matrix[2][2] - $matrix[1][2] * $matrix[2][1])
                      - $matrix[0][1] * ($matrix[1][0] * $matrix[2][2] - $matrix[1][2] * $matrix[2][0])
                      + $matrix[0][2] * ($matrix[1][0] * $matrix[2][1] - $matrix[1][1] * $matrix[2][0])) % 26;
            }
            
            // Fungsi untuk menghitung invers dari suatu angka dalam modulo 26
            function modInverse($a) {
                for ($x = 1; $x < 26; $x++) {
                    if ((($a % 26) * ($x % 26)) % 26 == 1) {
                        return $x;
                    }
                }
                return -1; // Invers tidak ditemukan.
            }
            
            for ($i = 0; $i < $textLength; $i += $matrixSize) {
                $block = substr($text, $i, $matrixSize); // Ambil satu blok teks.
                $blockVector = array_map(function ($char) {
                    return ord($char) - ord('a');
                }, str_split($block)); // Ubah blok teks menjadi vektor angka.
                
                // Jika tindakan adalah enkripsi, maka perkalian matriks kunci dengan vektor blok.
                // Jika tindakan adalah dekripsi, maka perkalian matriks invers kunci dengan vektor blok.
                $resultVector = ($action === "encrypt") ? multiplyMatrixVector($keyMatrix, $blockVector) : multiplyMatrixVector($keyMatrix, $blockVector);
                
                // Ubah vektor hasil ke dalam karakter alfabet kecil.
                $resultBlock = implode("", array_map(function ($num) {
                    return chr($num + ord('a'));
                }, $resultVector));
                
                $result .= $resultBlock; // Tambahkan blok hasil ke teks hasil.
            }
            
            // Mengembalikan teks hasil enkripsi atau dekripsi.
            return $result;
        }
        ?>
        

        
    </div>
</body>
</html>
