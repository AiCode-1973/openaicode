# Skill: Whatsapp Integration

## Descrição
Integração do AiCode com o WhatsApp via Webhook para atendimento automatizado com suporte a áudio.

## Componentes
- **Webhook**: `scripts/whatsapp_webhook.php`
- **Configuração**: Necessário preencher `whatsapp_phone_id` e `whatsapp_token` em `config.php`.

## Fluxo de Trabalho
1. O cliente envia mensagem.
2. O Webhook recebe e consulta o Gemini 2.5.
3. A resposta é enviada via Cloud API.

## Funcionalidade de Áudio (Em Breve)
Conforme definido no `SOUL.md`, a resposta deve ser convertida em áudio. Para isso, integraremos a API da ElevenLabs ou Google TTS nesta skill.
