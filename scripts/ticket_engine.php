<?php
/**
 * AiCode Ticket Checker Engine
 * Realiza login em sistemas externos e verifica chamados
 */

class TicketChecker {
    private $cookieFile;

    public function __construct() {
        $this->cookieFile = __DIR__ . '/cookies.txt';
    }

    /**
     * Executa o login e retorna o HTML da página pós-login
     */
    public function loginAndCheck($loginUrl, $dataUrl, $credentials) {
        // 1. Realizar Login
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $loginUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($credentials));
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookieFile); // Salva cookies aqui
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookieFile);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
        
        $response = curl_exec($ch);
        
        // 2. Acessar página de chamados usando os cookies capturados
        curl_setopt($ch, CURLOPT_URL, $dataUrl);
        curl_setopt($ch, CURLOPT_POST, false); // Mudar para GET
        $pageHtml = curl_exec($ch);
        
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($pageHtml === false) {
            return "Erro cURL: " . $error;
        }
        
        return $pageHtml;
    }

    /**
     * Analisa o HTML em busca de padrões de chamados abertos
     */
    public function parseTickets($html) {
        // Busca expandida por termos comuns de chamados
        $terms = ['aberto', 'pendente', 'aguardando', 'novo chamado', 'em andamento', 'atendimento'];
        $openCount = 0;
        
        $cleanHtml = strtolower(strip_tags($html)); // Remove tags para focar no texto
        
        foreach ($terms as $term) {
            $openCount += substr_count($cleanHtml, $term);
        }
        
        return [
            'total_abertos' => $openCount,
            'status' => ($openCount > 0) ? "Existem chamados ativos no portal!" : "Não detectei chamados abertos com os termos padrão."
        ];
    }
}
