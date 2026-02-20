<?php
/**
 * Teste de Integração AI
 */
require_once 'connector.php';

// Para teste, você pode definir a chave aqui temporariamente ou usar variável de ambiente
$apiKey = "SUA_API_KEY_AQUI"; 

$ai = new AiCodeConnector($apiKey);

$soul = file_get_contents('../SOUL.md');
echo "AiCode está processando...\n";

$pergunta = "Como você descreveria sua missão hoje?";
$resposta = $ai->ASK($pergunta, "Contexto do Sistema:\n" . $soul);

echo "Resposta da IA:\n" . $resposta . "\n";
