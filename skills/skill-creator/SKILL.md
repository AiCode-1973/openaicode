# Skill: Skill Creator

## Descrição
Esta habilidade permite que o AiCode crie novas "skills" (habilidades) de forma automatizada, seguindo o padrão de estrutura do projeto.

## Como Usar
Para criar uma nova skill, utilize o comando:
`php scripts/create_skill.php [nome-da-skill] "[descrição]"`

## Estrutura de uma Skill
Cada skill deve ter seu próprio diretório em `/skills/` contendo:
- `SKILL.md`: Instruções detalhadas.
- `scripts/`: Scripts de execução (opcional).
