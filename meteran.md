# Ringkasan Skema Pembacaan Meteran Air PDAM (Model 6 Digit)

Konfigurasi 6 digit (4 digit hitam + 2 digit merah) adalah standar umum pada instrumen meteran air perumahan. Berikut adalah intisari dari skema pembacaan dan implementasinya pada ekosistem aplikasi:

## 1. Anatomi Meteran (Format 4+2)
* **4 Digit Pertama (Latar Hitam):** Merepresentasikan volume air dalam satuan **Meter Kubik (m³)**. Angka ini adalah basis tunggal untuk perhitungan tarif dan penagihan. *(Contoh: `0123` = 123 m³)*.
* **2 Digit Terakhir (Latar Merah):** Merepresentasikan volume air dalam satuan **Liter**. Angka ini hanya untuk kalibrasi atau indikator aliran presisi harian, dan mutlak **diabaikan** dalam sistem penagihan resmi. *(Contoh: `45` = 450 liter)*.

## 2. Strategi Ekstraksi OCR di Frontend (Flutter)
Mengingat model ML Kit membaca seluruh teks yang terlihat tanpa membedakan warna latar belakang, diperlukan penanganan *post-processing* untuk menghindari anomali tagihan yang membengkak:
* **Pembatasan Visual (UI Bounding Box):** Menyediakan kotak pemindai (*overlay*) pada antarmuka kamera yang memandu pelanggan untuk memfokuskan lensa hanya pada 4 digit hitam.
* **Pembersihan Karakter (Regex):** Membersihkan *string* mentah dari karakter non-numerik yang sering muncul akibat kaca kotor, berembun, atau berdebu (misal: angka '5' terbaca sebagai huruf 'S').
* **Pemotongan *String* (Substring):** Jika sistem tetap menangkap 6 digit secara penuh, terapkan logika untuk memotong 2 digit terakhir agar hanya menyisakan nilai meter kubik murni.

```dart
// Contoh implementasi logika di Dart
String rawOcrResult = "012345";

// 1. Bersihkan dari karakter huruf/simbol
String sanitizedResult = rawOcrResult.replaceAll(RegExp(r'[^0-9]'), '');

// 2. Ambil hanya 4 digit pertama (m³)
if (sanitizedResult.length == 6) {
  String cleanCubicMeter = sanitizedResult.substring(0, 4); 
  
  // 3. Konversi ke integer untuk dikirim ke API
  int finalVolume = int.parse(cleanCubicMeter); // Hasil: 123
}