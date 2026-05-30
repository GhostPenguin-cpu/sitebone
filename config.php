<?php
/**
 * config.php — Ajuste estes valores antes de publicar.
 */
return [
    // ===== PIX (modo DEMO usa estes dados para gerar o copia-e-cola) =====
    'pix_chave'   => 'sua-chave-pix@email.com',   // e-mail, CPF/CNPJ, telefone ou chave aleatória
    'pix_nome'    => 'NO LIMITE DO LACO',          // nome do recebedor (máx 25, sem acento)
    'pix_cidade'  => 'SAO PAULO',                  // cidade do recebedor (máx 15, sem acento)

    // ===== Regras comerciais =====
    'desconto_pix' => 10,             // % de desconto no PIX
    'cupom_frete'  => 'FRETEGRATIS',  // cupom de frete grátis (sempre aplicado)
    'parcelas_max' => 12,             // parcelamento no cartão

    // ===== Gateway de pagamento =====
    // 'demo'        -> gera PIX estático válido (sem confirmação automática)
    // 'mercadopago' -> usa a API do Mercado Pago (preencha o token)
    // 'asaas'       -> usa a API do Asaas
    // 'efi'         -> usa a API da Efí (Gerencianet)
    'gateway'       => 'demo',
    'gateway_token' => '',            // access_token / api_key do gateway escolhido
    'gateway_sandbox' => true,        // true = ambiente de teste do gateway
];
