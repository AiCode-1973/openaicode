<?php
require_once 'connector.php';
$config = include 'config.php';
$apiKey = $config['api_key'];

$url = "https://generativelanguage.googleapis.com/v1beta/models?key=" . $apiKey;

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$result = curl_exec($ch);
curl_close($ch);

echo "Modelos Disponíveis:\n";
$data = json_decode($result, true);
if (isset($data['models'])) {
    foreach ($data['models'] as $model) {
        echo "- " . $model['name'] . " (Suporta: " . implode(', ', $model['supportedGenerationMethods']) . ")\n";
    }
} else {
    echo "Erro ao listar modelos: " . $result;
}
