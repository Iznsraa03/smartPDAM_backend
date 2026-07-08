# Ringkasan Perhitungan Penagihan Air PDAM

Perhitungan tagihan air tidak menggunakan tarif tunggal (*flat rate*), melainkan menerapkan sistem **Tarif Blok Progresif (Berjenjang)** untuk menyubsidi kebutuhan dasar rumah tangga dan memberikan penalti pada pemborosan air.

## 1. Kalkulasi Konsumsi Bersih
Sistem tidak menagihkan total angka mutlak pada meteran, melainkan hanya selisih volume pemakaian dari periode sebelumnya.
* **Rumus:** `Konsumsi (m³) = Angka Meteran Bulan Ini - Angka Meteran Bulan Lalu`
* **Contoh Kasus:** 123 m³ (Hasil OCR) - 110 m³ (Bulan Lalu) = **13 m³** (Konsumsi Bersih).

## 2. Skema Tarif Blok Progresif
Volume konsumsi bersih didistribusikan ke dalam blok-blok pemakaian yang tarif per meternya meningkat secara bertahap. *(Ilustrasi menggunakan tarif Rumah Tangga)*:

| Blok | Batas Volume | Tarif per m³ | Simulasi Komputasi untuk 13 m³ |
| :--- | :--- | :--- | :--- |
| **Blok 1** | 0 - 10 m³ | Rp 2.600 | 10 m³ × Rp 2.600 = **Rp 26.000** |
| **Blok 2** | 11 - 20 m³ | Rp 4.600 | 3 m³ × Rp 4.600 = **Rp 13.800** |
| **Blok 3** | 21 - 30 m³ | Rp 7.400 | - |
| **Blok 4** | > 30 m³ | Rp 10.700 | - |
| **Beban Air**| | | **Rp 39.800** |

## 3. Komponen Biaya Tetap (*Fix Cost*)
*Invoice* akhir (*Grand Total*) yang diteruskan ke *Payment Gateway* (Midtrans) adalah gabungan dari total beban pemakaian air progresif dengan biaya operasional statis.
* Beban Air (Progresif): Rp 39.800
* Biaya Administrasi (Tetap): Rp 10.000
* Biaya Pemeliharaan (Tetap): Rp 5.000
* **Total Tagihan Akhir:** **Rp 54.800**

## 4. Arsitektur Komputasi Backend (Laravel)
Logika pengurangan volume berulang ini tidak diletakkan di dalam *Controller*, melainkan diabstraksi ke dalam *Service Class* tersendiri untuk menjaga kebersihan struktur kode dan mempermudah pengujian otomatis (*Unit Testing*).

```php
public function calculateWaterUsage(int $usageVolume): int
{
    $totalCost = 0;
    $remainingVolume = $usageVolume;

    // Komputasi Blok 1 (Batas 10 m³)
    if ($remainingVolume > 0) {
        $billable = min($remainingVolume, 10);
        $totalCost += $billable * 2600;
        $remainingVolume -= $billable;
    }

    // Komputasi Blok 2 (Batas 10 m³ berikutnya)
    if ($remainingVolume > 0) {
        $billable = min($remainingVolume, 10);
        $totalCost += $billable * 4600;
        $remainingVolume -= $billable;
    }

    // Pola ini dilanjutkan untuk Blok 3 (Batas 10) dan Blok 4 (Uncapped)
    
    return $totalCost; // Mengembalikan Rp 39.800
}