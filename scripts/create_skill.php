<?php
/**
 * Skill Creator Script for AiCode
 * Cria a estrutura de uma nova skill automaticamente
 */

$name = $argv[1] ?? '';
$description = $argv[2] ?? 'Sem descrição';

if (empty($name)) {
    echo "Erro: Nome da skill não fornecido.\n";
    echo "Uso: php create_skill.php [nome-da-skill] \"[descrição]\"\n";
    exit(1);
}

$basePath = __DIR__ . '/../skills/' . $name;

if (is_dir($basePath)) {
    echo "Erro: A skill '$name' já existe.\n";
    exit(1);
}

// Cria pastas
mkdir($basePath, 0777, true);
mkdir($basePath . '/scripts', 0777, true);

// Cria SKILL.md
$skillMd = "# Skill: " . ucwords(str_replace('-', ' ', $name)) . "\n\n";
$skillMd .= "## Descrição\n$description\n\n";
$skillMd .= "## Como Usar\nInstruções de uso aqui.\n\n";
$skillMd .= "## Scripts\nLista de scripts desta skill.\n";

file_put_to_file($basePath . '/SKILL.md', $skillMd);

echo "Skill '$name' criada com sucesso em $basePath\n";

function file_put_to_file($path, $content) {
    file_put_contents($path, $content);
}
