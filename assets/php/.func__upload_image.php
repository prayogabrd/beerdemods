<?php
// Fungsi untuk upload gambar
function upload_image($data, $API_KEY) {
  // Inisialisasi curl
  $ch = curl_init();
  
  // Kirim permintaan
  curl_setopt($ch, CURLOPT_URL, 'https://api.imgbb.com/1/upload?key='.$API_KEY);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	
	// Hasil permintaan
	$curl_result = curl_exec($ch);
	$result = json_decode($curl_result, true);
	
	// Cek apakah ada error
  if (curl_errno($ch)) {
    $result = false;
  }
  
  // Tutup curl
  curl_close($ch);
  
  // Kembalikan nilai
  return $result;
}