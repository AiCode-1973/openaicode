<?php
/**
 * AiCode API Connector
 * Centraliza a comunicação com modelos de IA (Gemini/Antigravity)
 */

class AiCodeConnector {
    private $apiKey;
    private $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent";

    public function __construct($apiKey = null) {
        if ($apiKey) {
            $this->apiKey = $apiKey;
        } else {
            $config = @include __DIR__ . '/config.php';
            $this->apiKey = $config['api_key'] ?? getenv('AICODE_API_KEY');
        }
    }

    public function ASK($prompt, $context = "") {
        if (!$this->apiKey) {
            return "Erro: Chave de API não configurada.";
        }

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$this->apiKey}";
        
        $data = [
            "contents" => [
                [
                    "parts" => [
                        ["text" => $context . "\n\nPergunta: " . $prompt]
                    ]
                ]
            ]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Evita problemas de SSL localmente no XAMPP

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($result === false) {
            return "Erro cURL: " . $error;
        }

        if ($httpCode !== 200) {
            $errorData = json_decode($result, true);
            $msg = $errorData['error']['message'] ?? "Erro desconhecido (HTTP $httpCode)";
            return "Erro na API: " . $msg;
        }

        $response = json_decode($result, true);
        return $response['candidates'][0]['content']['parts'][0]['text'] ?? "Resposta vazia.";
    }
}
