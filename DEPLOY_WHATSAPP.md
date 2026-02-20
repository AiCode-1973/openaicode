# Guia de Configuração: AiCode no WhatsApp

Agora que o sistema está hospedado em `https://open.aicode.dev.br`, siga estes passos para ativar o link neural com o WhatsApp:

## 1. Configuração no Meta Developers
1. Acesse o [Facebook Developers](https://developers.facebook.com/).
2. Crie ou selecione seu App do tipo **Negócios/Business**.
3. Adicione o produto **WhatsApp**.
4. No menu lateral, vá em **WhatsApp > Configuração de Webhook**.
5. Clique em **Editar** e preencha:
   - **URL de retorno**: `https://open.aicode.dev.br/scripts/whatsapp_webhook.php`
   - **Token de verificação**: `aicode_secret_token_2026`
6. Clique em **Verificar e Salvar**.
7. Em **Campos do Webhook**, clique em **Gerenciar** e assine o campo `messages`.

## 2. Configuração de Credenciais
No menu **WhatsApp > Configuração de API**, você encontrará dois dados importantes:
1. **ID do número de telefone** (Phone Number ID).
2. **Token de acesso temporário** (ou crie um permanente em Configurações do Negócio).

## 3. Atualizar o Sistema
Abra o arquivo `scripts/config.php` no seu servidor e preencha os campos vazios:

```php
'whatsapp_phone_id' => 'SEU_ID_AQUI',
'whatsapp_token' => 'SEU_TOKEN_AQUI'
```

## 4. Teste de Fogo
1. Envie uma mensagem de texto para o número que você configurou no Meta.
2. O **AiCode** deverá processar a mensagem no servidor e te responder em segundos!

---
**Nota de Segurança**: Certifique-se de que o arquivo `.htaccess` foi enviado corretamente para a pasta raiz no servidor para manter sua `api_key` escondida.
