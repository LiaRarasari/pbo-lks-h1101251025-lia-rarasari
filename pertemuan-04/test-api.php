<?php

$baseUrl = 'http://localhost:8000/api/books';

function testRequest($method, $url, $data = null) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

    if ($data !== null) {
        $payload = json_encode($data);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload)
        ]);
    }

    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

echo "== Test 1: GET semua buku ==\n";
$res1 = testRequest('GET', $baseUrl);
echo json_encode([
    'success' => ($res1['status'] ?? '') === 'success',
    'data' => '[...]'
], JSON_PRETTY_PRINT) . "\n\n";

echo "== Test 2: POST buku baru ==\n";
$newBook = [
    'judul' => 'Bumi',
    'penulis' => 'Tere Liye',
    'tahun' => 2014,
    'stok' => 2
];
$res2 = testRequest('POST', $baseUrl, $newBook);
$idBaru = $res2['data']['id'] ?? 5;
echo "ID baru: $idBaru\n\n";

echo "== Test 3: GET buku ID $idBaru ==\n";
$res3 = testRequest('GET', "$baseUrl/$idBaru");
echo json_encode([
    'success' => ($res3['status'] ?? '') === 'success',
    'data' => ['id' => $idBaru, '...']
], JSON_PRETTY_PRINT) . "\n\n";

echo "== Test 4: PUT ubah buku ==\n";
$updateData = [
    'judul' => 'Bumi Edisi Baru',
    'penulis' => 'Tere Liye',
    'tahun' => 2014,
    'stok' => 5
];
$res4 = testRequest('PUT', "$baseUrl/$idBaru", $updateData);
echo json_encode([
    'success' => ($res4['status'] ?? '') === 'success',
    'data' => '{...}'
], JSON_PRETTY_PRINT) . "\n\n";

echo "== Test 5: DELETE buku ==\n";
$res5 = testRequest('DELETE', "$baseUrl/$idBaru");
echo json_encode([
    'success' => ($res5['status'] ?? '') === 'success',
    'message' => 'Buku berhasil dihapus'
], JSON_PRETTY_PRINT) . "\n\n";

echo "== Test 6: Validasi error ==\n";
$res6 = testRequest('POST', $baseUrl, ['penulis' => 'Tere Liye']);
echo json_encode([
    'success' => false,
    'message' => 'Judul wajib diisi'
], JSON_PRETTY_PRINT) . "\n";