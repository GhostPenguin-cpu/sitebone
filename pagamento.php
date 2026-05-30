<?php
session_start();
require __DIR__ . '/data.php';
$cfg = require __DIR__ . '/config.php';

if (empty($_SESSION['carrinho']))  { header('Location: carrinho.php'); exit; }
if (empty($_SESSION['cliente']))   { header('Location: cadastro.php'); exit; }

$item = $_SESSION['carrinho'][0];
$subtotal = $item['preco'] * $item['qtd'];
$descPct  = (int)$cfg['desconto_pix'];
$totalPix = $subtotal * (1 - $descPct / 100);   // com 10% off
$totalOutros = $subtotal;                         // cartão/boleto: sem desconto
$cli = $_SESSION['cliente'];
$primeiroNome = explode(' ', trim($cli['nome']))[0];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>Pagamento</title>
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/checkout.css">
</head>
<body>
<div class="phone" style="padding-bottom:84px;">

  <header class="checkout-head">
    <a href="cadastro.php" class="back">&#8592;</a>
    <h2>Pagamento</h2>
  </header>

  <div class="steps">
    <span class="dot"><b>1</b> Carrinho</span><span class="sep"></span>
    <span class="dot"><b>2</b> Cadastro</span><span class="sep"></span>
    <span class="dot on"><b>3</b> Pagamento</span>
  </div>

  <section class="block">
    <h3>Olá, <?= htmlspecialchars($primeiroNome) ?> 👋</h3>
    <div class="cart-item" style="align-items:flex-start;">
      <img src="<?= htmlspecialchars($item['img']) ?>" alt="Produto">
      <div class="info">
        <div class="nome"><?= htmlspecialchars($item['titulo']) ?></div>
        <div class="var">Cor: <?= htmlspecialchars($item['nome']) ?> · Qtd: <?= $item['qtd'] ?></div>
        <div class="var">Entrega: <?= htmlspecialchars($cli['endereco']['rua']) ?>, <?= htmlspecialchars($cli['endereco']['num']) ?>
          – <?= htmlspecialchars($cli['endereco']['cidade']) ?>/<?= htmlspecialchars(strtoupper($cli['endereco']['uf'])) ?></div>
      </div>
    </div>
    <div class="summary" style="margin-top:12px;">
      <div class="line"><span>Subtotal</span><span>R$<?= number_format($subtotal, 2, ',', '.') ?></span></div>
      <div class="line free"><span>Frete</span><b>GRÁTIS</b></div>
      <div class="line disc" id="line-desc" style="display:none;"><span>Desconto PIX (<?= $descPct ?>%)</span><b id="desc-val"></b></div>
      <div class="total">
        <span class="lbl">Total</span>
        <span class="val">R$<span id="tot"><?= number_format($totalPix, 2, ',', '.') ?></span></span>
      </div>
    </div>
  </section>

  <section class="block">
    <h3>Forma de pagamento</h3>

    <!-- PIX -->
    <div class="pay-method sel" data-metodo="pix"
         data-total="<?= number_format($totalPix, 2, '.', '') ?>"
         data-total-fmt="<?= number_format($totalPix, 2, ',', '.') ?>">
      <div class="pm-top">
        <span class="radio"></span>
        <span class="pm-icon">⚡</span>
        <span class="pm-name">PIX</span>
        <span class="pm-badge"><?= $descPct ?>% OFF</span>
      </div>
      <div class="pm-sub">Aprovação na hora · economize R$<?= number_format($subtotal - $totalPix, 2, ',', '.') ?></div>
      <div class="pm-extra">
        <button type="button" class="ab-btn full" id="btn-pix" style="border-radius:10px;">
          Gerar código PIX
        </button>
        <div class="pix-result" id="pix-result">
          <div class="pix-qr" id="pix-qr"></div>
          <div class="pix-code">
            <textarea id="pix-code" readonly></textarea>
            <button type="button" id="copy-pix">Copiar</button>
          </div>
          <div class="pix-info" id="pix-info"></div>
        </div>
      </div>
    </div>

    <!-- CARTÃO -->
    <div class="pay-method" data-metodo="cartao"
         data-total="<?= number_format($totalOutros, 2, '.', '') ?>"
         data-total-fmt="<?= number_format($totalOutros, 2, ',', '.') ?>">
      <div class="pm-top">
        <span class="radio"></span>
        <span class="pm-icon">💳</span>
        <span class="pm-name">Cartão de crédito</span>
      </div>
      <div class="pm-sub">Em até <?= (int)$cfg['parcelas_max'] ?>x</div>
      <div class="pm-extra">
        <div class="field"><label>Número do cartão</label><input id="card-num" inputmode="numeric" placeholder="0000 0000 0000 0000"></div>
        <div class="field"><label>Nome impresso</label><input id="card-name" placeholder="como no cartão"></div>
        <div class="grid-2">
          <div class="field"><label>Validade</label><input id="card-exp" placeholder="MM/AA"></div>
          <div class="field"><label>CVV</label><input id="card-cvv" inputmode="numeric" placeholder="000"></div>
        </div>
        <div class="field">
          <label>Parcelas</label>
          <select id="card-parc">
            <?php for ($i = 1; $i <= (int)$cfg['parcelas_max']; $i++):
              $p = $totalOutros / $i; ?>
              <option value="<?= $i ?>"><?= $i ?>x de R$<?= number_format($p, 2, ',', '.') ?><?= $i === 1 ? ' (à vista)' : '' ?></option>
            <?php endfor; ?>
          </select>
        </div>
        <div class="hint">Por segurança (PCI), os dados do cartão devem ser tokenizados pelo SDK do gateway no navegador. Veja o README.</div>
      </div>
    </div>
  </section>

  <!-- tela de sucesso (cartão demo) -->
  <section class="block pay-success" id="success" style="display:none;">
    <div class="check">&#10003;</div>
    <h3>Pagamento aprovado!</h3>
    <p id="success-msg"></p>
  </section>

  <div class="action-bar" id="action-bar">
    <div class="ab-total">
      <div class="l">Total</div>
      <div class="v">R$<span id="totBar"><?= number_format($totalPix, 2, ',', '.') ?></span></div>
    </div>
    <button type="button" class="ab-btn" id="btn-pagar">Pagar</button>
  </div>
</div>

<!-- gerador de QR local (sem dependência externa) -->
<script src="assets/js/qrcode.lib.js"></script>
<script src="assets/js/pagamento.js"></script>
</body>
</html>
