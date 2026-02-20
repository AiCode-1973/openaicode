<?php
/**
 * AiCode WhatsApp Webhook
 * Recebe mensagens do WhatsApp e processa via AiCode Connector
 */

require_once 'connector.php';
$config = include 'config.php';

// Token de Verificação (Defina o mesmo no Painel do Meta/Facebook Developers)
$verifyToken = 'aicode_secret_token_2026';

// 1. Verificação do Webhook (Necessário para a primeira conexão com o Meta)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['hub_mode']) && $_GET['hub_mode'] === 'subscribe') {
    if ($_GET['hub_verify_token'] === $verifyToken) {
        echo $_GET['hub_challenge'];
        exit;
    }
}

// 2. Processamento de Mensagem Recebida
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (isset($data['entry'][0]['changes'][0]['value']['messages'][0])) {
    $message = $data['entry'][0]['changes'][0]['value']['messages'][0];
    $from = $message['from']; // Número do cliente
    $text = $message['text']['body'] ?? "";

    if (!empty($text)) {
        // Inicializa o Cérebro do AiCode
        $ai = new AiCodeConnector($config['api_key']);
        
        // Carrega Contexto (Soul)
        $soul = file_exists('../SOUL.md') ? file_get_contents('../SOUL.md') : "";
        $response = $ai->ASK($text, "Contexto:\n$soul\nResponda de forma curta e profissional.");

        // Envia resposta de volta para o WhatsApp
        sendWhatsAppMessage($from, $response, $config);
    }
}

/**
 * Função para enviar mensagem via Cloud API
 */
function sendWhatsAppMessage($to, $message, $config) {
    $url = "https://graph.facebook.com/v17.0/" . ($config['whatsapp_phone_id'] ?? 'SEU_PHONE_ID') . "/messages";
    
    $data = [
        "messaging_product" => "whatsapp",
        "to" => $to,
        "type" => "text",
        "text" => ["body" => $message]
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . ($config['whatsapp_token'] ?? 'SEU_TOKEN')
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);
}
