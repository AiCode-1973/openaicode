<?php
/**
 * Memory Search Utility for AiCode
 * Procura termos nos arquivos de memória (.md)
 */

$searchDir = __DIR__ . '/../memory';
$query = $argv[1] ?? '';

if (empty($query)) {
    echo "Uso: php search_memory.php [termo_de_busca]\n";
    exit(1);
}

$files = glob($searchDir . '/*.md');
$results = [];

foreach ($files as $file) {
    $content = file_get_contents($file);
    if (stripos($content, $query) !== false) {
        $results[] = [
            'file' => basename($file),
            'snippet' => extractSnippet($content, $query)
        ];
    }
}

// Também busca no MEMORY.md na raiz
$rootMemory = __DIR__ . '/../MEMORY.md';
if (file_exists($rootMemory)) {
    $content = file_get_contents($rootMemory);
    if (stripos($content, $query) !== false) {
        $results[] = [
            'file' => 'MEMORY.md',
            'snippet' => extractSnippet($content, $query)
        ];
    }
}

echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

function extractSnippet($content, $query) {
    $pos = stripos($content, $query);
    $start = max(0, $pos - 50);
    $snippet = substr($content, $start, 150);
    return "..." . str_replace("\n", " ", $snippet) . "...";
}
