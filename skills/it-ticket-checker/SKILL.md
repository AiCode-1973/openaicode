# Skill: It Ticket Checker

## Descrição
Habilidade para automatizar a verificação de sistemas de chamados (GLPI, Jira, Movidesk, etc) realizando login automatizado.

## Componentes
- **Engine**: `scripts/ticket_engine.php`
- **Script de Execução**: `scripts/it_checker_service.php`

## Como Configurar
1. Definir a URL de Login do sistema de TI.
2. Mapear os nomes dos campos de formulário (ex: `user`, `pass`).
3. Definir a URL da lista de chamados.

## Fluxo
O AiCode acessa o site, armazena os cookies de sessão, navega até a lista de chamados e reporta o status via Dashboard ou WhatsApp.
