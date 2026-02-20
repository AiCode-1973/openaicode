<?php
/**
 * Script de Teste: Verificador de Chamados AiCode
 * Utilize este arquivo para configurar o acesso ao seu portal de TI.
 */

require_once 'ticket_engine.php';

// --- CONFIGURAÇÃO DOS DADOS DO PORTAL ---
$configPortal = [
    'url_login'   => 'https://seu-portal-ti.com/login',    // URL onde envia o formulário de login
    'url_tickets' => 'https://seu-portal-ti.com/chamados',  // URL da página que lista os chamados
    'credentials' => [
        'usuario' => 'SEU_USUARIO',                      // Nome do campo de usuário no HTML
        'senha'   => 'SUA_SENHA',                        // Nome do campo de senha no HTML
        // Adicione outros campos se o formulário exigir (ex: 'csrf_token' => '...')
    ]
];

echo "--- Iniciando Verificação AiCode ---\n";

$checker = new TicketChecker();

// 1. Tentar Login e Capturar HTML
echo "Fazendo login em: " . $configPortal['url_login'] . "...\n";
$html = $checker->loginAndCheck(
    $configPortal['url_login'], 
    $configPortal['url_tickets'], 
    $configPortal['credentials']
);

if (empty($html)) {
    echo "[ERRO] Não foi possível obter resposta do servidor.\n";
    exit;
}

// 2. Analisar Resultados
echo "Analisando página de chamados...\n";
$resultado = $checker->parseTickets($html);

echo "\n--- RESULTADO ---\n";
echo "Status: " . $resultado['status'] . "\n";
echo "Chamados encontrados: " . $resultado['total_abertos'] . "\n";
echo "-----------------\n";

// Debug opcional: salvar o HTML para ver se o login realmente funcionou
// file_put_contents('debug_page.html', $html);
// echo "HTML da página salvo em debug_page.html para verificação.\n";
