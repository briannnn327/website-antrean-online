<?php
// Konfigurasi Response: Mengatur header agar browser tahu ini adalah JSON response.
header('Content-Type: application/json');
// CORS Header: Mengizinkan akses dari domain manapun (penting untuk AJAX dari domain berbeda).
header('Access-Control-Allow-Origin: *');

// URL API BPS: URL endpoint API dari Badan Pusat Statistik untuk mengambil data fasilitas kesehatan.
$url = "https://webapi.bps.go.id/v1/api/interoperabilitas/datasource/simdasi/id/25/tahun/2017/id_tabel/TEptbDV0QlRORVl6cjl0THhMbk02Zz09/wilayah/0000000/key/e34d50c3e2e4773ebe4c8162f7a76057";

// Inisialisasi cURL: Membuat resource cURL untuk melakukan HTTP request ke API BPS.
$ch = curl_init();
// Konfigurasi cURL: Mengatur URL, return response sebagai string (bukan output langsung), dan timeout 30 detik.
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

// Eksekusi Request: Menjalankan HTTP request ke API dan menyimpan response dalam variabel.
$response = curl_exec($ch);

// Pengecekan Error cURL: Jika ada error saat request, tampilkan pesan error dalam format JSON dan berhenti.
if(curl_errno($ch)){
    echo json_encode([
        "error" => "cURL Error: " . curl_error($ch)
    ]);
    curl_close($ch);
    exit;
}

// Tutup cURL: Menutup resource cURL setelah request selesai untuk membebaskan memory.
curl_close($ch);

// Validasi Response Kosong: Jika response dari API kosong, tampilkan error.
if (!$response) {
    echo json_encode(["error" => "Gagal mengambil data dari BPS"]);
    exit;
}

// Decode JSON: Mengubah response JSON dari API menjadi array PHP untuk validasi.
$data = json_decode($response, true);

// Validasi JSON: Jika response bukan JSON valid, tampilkan error.
if (!$data) {
    echo json_encode(["error" => "Response bukan JSON valid"]);
    exit;
}

// Return Data: Mengirim data yang sudah divalidasi kembali ke frontend dalam format JSON.
echo json_encode($data);