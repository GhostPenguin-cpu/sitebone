<?php
/**
 * api/pagamento.php — Endpoint que o botão "Pagar" chama (AJAX).
 *
 * Recebe JSON:  { "metodo": "pix"|"cartao", "valor": 47.61, "cliente": {...}, "pedido": "..." }
 * Devolve JSON: { ok, metodo, valor, pix_copia_cola, txid, instrucoes }  (PIX)
 *               { ok, metodo, status, mensagem }                          (cartão)
 *
 * >>> AQUI é onde você integra o gateway de pagamento real. <<<
 * Procure por "INTEGRAÇÃO" abaixo.
 */

header('Content-Type: application/json; charset=utf-8');

require __DIR__ . '/../config.php';   // não usado direto, mas mostra o caminho
$cfg = require __DIR__ . '/../config.php';
require __DIR__ . '/../pix.php';

// ---- Lê o corpo (JSON ou form) ----
$raw = file_get_contents('php://input');
$in  = json_decode($raw, true);
if (!is_array($in)) { $in = $_POST; }

$metodo = $in['metodo'] ?? 'pix';
$valor  = isset($in['valor']) ? (float)$in['valor'] : 0.0;
$cliente = $in['cliente'] ?? [];
$txid   = 'PED' . date('YmdHis') . rand(100, 999);

if ($valor <= 0) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'erro' => 'Valor inválido.']);
    exit;
}

// =====================================================================
//  PIX
// =====================================================================
if ($metodo === 'pix') {

    if ($cfg['gateway'] === 'demo') {
        // ---- MODO DEMO: gera PIX estático válido (copia e cola funciona) ----
        $copiaCola = pix_montar(
            $cfg['pix_chave'],
            $cfg['pix_nome'],
            $cfg['pix_cidade'],
            $valor,
            substr($txid, 0, 25),
            'Pedido boné trucker'
        );

        echo json_encode([
            'ok'              => true,
            'metodo'          => 'pix',
            'valor'           => number_format($valor, 2, '.', ''),
            'txid'            => $txid,
            'pix_copia_cola'  => $copiaCola,
            'expira_em'       => 30, // minutos (informativo no modo demo)
            'instrucoes'      => 'Abra o app do seu banco, escolha PIX > Copia e Cola, '
                               . 'cole o código e confirme o pagamento.',
            'demo'            => true,
        ]);
        exit;
    }

    // ---- INTEGRAÇÃO: PIX dinâmico com confirmação automática ----
    // Descomente/implemente conforme o seu gateway. Exemplo Mercado Pago:
    /*
    if ($cfg['gateway'] === 'mercadopago') {
        $payload = [
            'transaction_amount' => round($valor, 2),
            'description'        => 'Pedido boné trucker',
            'payment_method_id'  => 'pix',
            'payer' => ['email' => $cliente['email'] ?? 'cliente@email.com'],
        ];
        $ch = curl_init('https://api.mercadopago.com/v1/payments');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $cfg['gateway_token'],
                'X-Idempotency-Key: ' . $txid,
            ],
            CURLOPT_POSTFIELDS => json_encode($payload),
        ]);
        $resp = json_decode(curl_exec($ch), true);
        $tx = $resp['point_of_interaction']['transaction_data'] ?? [];
        echo json_encode([
            'ok' => true, 'metodo' => 'pix',
            'valor' => number_format($valor, 2, '.', ''),
            'txid' => $resp['id'] ?? $txid,
            'pix_copia_cola' => $tx['qr_code'] ?? '',
            'qr_base64' => $tx['qr_code_base64'] ?? '',  // imagem pronta do gateway
            'instrucoes' => 'Pague pelo PIX. A confirmação chega via webhook.',
        ]);
        exit;
    }
    // Asaas:  POST https://api.asaas.com/v3/payments  (billingType: "PIX")
    // Efí:    POST /v2/cob  + /v2/loc/{id}/qrcode
    */

    http_response_code(501);
    echo json_encode(['ok' => false, 'erro' => 'Gateway PIX não configurado. Veja api/pagamento.php']);
    exit;
}

// =====================================================================
//  CARTÃO DE CRÉDITO
// =====================================================================
if ($metodo === 'cartao') {

    // IMPORTANTE (segurança/PCI): NUNCA envie o número do cartão para o seu
    // servidor. Use o SDK JS do gateway no navegador para gerar um TOKEN e
    // envie apenas esse token para cá. O campo abaixo já espera um token.
    $token    = $in['card_token'] ?? null;
    $parcelas = (int)($in['parcelas'] ?? 1);

    if ($cfg['gateway'] === 'demo') {
        echo json_encode([
            'ok'       => true,
            'metodo'   => 'cartao',
            'status'   => 'aprovado',     // simulação
            'parcelas' => $parcelas,
            'valor'    => number_format($valor, 2, '.', ''),
            'mensagem' => 'Pagamento simulado aprovado (modo demo).',
            'demo'     => true,
        ]);
        exit;
    }

    // ---- INTEGRAÇÃO: cobrança no cartão usando o $token do gateway ----
    /*
    if ($cfg['gateway'] === 'mercadopago') {
        // POST https://api.mercadopago.com/v1/payments
        // body: token, installments, transaction_amount, payment_method_id, payer
    }
    */

    http_response_code(501);
    echo json_encode(['ok' => false, 'erro' => 'Gateway de cartão não configurado.']);
    exit;
}

http_response_code(400);
echo json_encode(['ok' => false, 'erro' => 'Método de pagamento desconhecido.']);
