<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caesar Cipher</title> 
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
        <h1>Caesar Cipher</h1>
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
            
            // Kata kunci Vigenere Cipher
            $keyword = "wonogiri";
            
            // Memanggil fungsi vigenereCipher() untuk melakukan enkripsi atau dekripsi teks
            // dengan menggunakan teks, kata kunci, dan tindakan yang dipilih oleh pengguna
            $resultText = vigenereCipher($text, $keyword, $action);
            
            // Menampilkan hasil enkripsi atau dekripsi di bawah form
            echo '<p class="result"><strong>Hasil:</strong> ' . $resultText . '</p>';
        }
        
        function vigenereCipher($text, $keyword, $action) {
            $result = ""; // Variabel untuk menyimpan teks hasil enkripsi atau dekripsi.
            $textLength = strlen($text); // Menghitung panjang teks input.
            $keywordLength = strlen($keyword); // Menghitung panjang kata kunci.
            
            for ($i = 0, $j = 0; $i < $textLength; $i++) {
                $char = $text[$i]; // Mengambil karakter pada posisi $i dalam teks.
                
                // Mengecek apakah karakter adalah huruf kecil (a-z).
                if ($char >= 'a' && $char <= 'z') {
                    // Mengambil karakter dari kata kunci yang sesuai dengan posisi saat ini.
                    $keywordChar = $keyword[$j % $keywordLength];
                    
                    if ($action === "encrypt") { // Jika tindakan adalah enkripsi.
                        // Mengenkripsi karakter dengan menggunakan rumus Vigenere Cipher.
                        $newChar = chr(((ord($char) - ord('a') + ord($keywordChar) - ord('a')) % 26) + ord('a'));
                    } elseif ($action === "decrypt") { // Jika tindakan adalah dekripsi.
                        // Mendekripsi karakter dengan menggunakan rumus Vigenere Cipher.
                        $newChar = chr(((ord($char) - ord('a') - (ord($keywordChar) - ord('a')) + 26) % 26) + ord('a'));
                    } else {
                        // Jika tindakan bukan enkripsi atau dekripsi, karakter tetap tidak berubah.
                        $newChar = $char;
                    }
                    
                    $j++; // Pindah ke karakter berikutnya dalam kata kunci.
                } else {
                    // Jika karakter bukan huruf kecil, karakter tetap tidak berubah.
                    $newChar = $char;
                }
                
                // Menambahkan karakter yang telah dienkripsi atau didekripsi ke hasil akhir.
                $result .= $newChar;
            }
            
            // Mengembalikan teks hasil enkripsi atau dekripsi.
            return $result;
        }
        ?>        
        
    </div>
</body>
</html>
