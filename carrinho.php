<?php
session_start();
$PRODUTO = null; $AVALIACOES = null; $AVALIACOES_RESUMO = null;
require __DIR__ . '/data.php';
$cfg = require __DIR__ . '/config.php';

// ---- adiciona produto ao carrinho ----
function achar_cor($PRODUTO, $ref) {
    foreach ($PRODUTO['cores'] as $c) if ($c['ref'] === $ref) return $c;
    return $PRODUTO['cores'][0];
}
if (empty($_SESSION['carrinho'])) {
    $ref = $_GET['cor'] ?? $PRODUTO['cores'][0]['ref'];
    $cor = achar_cor($PRODUTO, $ref);
    $_SESSION['carrinho'] = [[
        'ref'   => $cor['ref'],
        'nome'  => $cor['nome'],
        'titulo'=> $PRODUTO['titulo'],
        'img'   => $cor['img'],
        'preco' => $PRODUTO['preco'],
        'qtd'   => 1,
    ]];
} elseif (isset($_GET['cor'])) {
    $cor = achar_cor($PRODUTO, $_GET['cor']);
    $_SESSION['carrinho'][0]['ref']  = $cor['ref'];
    $_SESSION['carrinho'][0]['nome'] = $cor['nome'];
    $_SESSION['carrinho'][0]['img']  = $cor['img'];
}

// ---- atualização de quantidade (AJAX POST) ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $body = json_decode(file_get_contents('php://input'), true) ?: $_POST;
    $q = max(1, min(99, (int)($body['qtd'] ?? 1)));
    $_SESSION['carrinho'][0]['qtd'] = $q;
    $it = $_SESSION['carrinho'][0];
    $sub = $it['preco'] * $it['qtd'];
    header('Content-Type: application/json');
    echo json_encode([
        'qtd'      => $q,
        'subtotal' => number_format($sub, 2, ',', '.'),
        'total'    => number_format($sub, 2, ',', '.'),
    ]);
    exit;
}

$item = $_SESSION['carrinho'][0];
$subtotal = $item['preco'] * $item['qtd'];
$total = $subtotal; // frete sempre grátis
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>Carrinho</title>
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/checkout.css">
</head>
<body>
<div class="phone" style="padding-bottom:80px;">

  <header class="checkout-head">
    <a href="index.php" class="back">&#8592;</a>
    <h2>Carrinho</h2>
  </header>

  <div class="steps">
    <span class="dot on"><b>1</b> Carrinho</span><span class="sep"></span>
    <span class="dot"><b>2</b> Cadastro</span><span class="sep"></span>
    <span class="dot"><b>3</b> Pagamento</span>
  </div>

  <section class="block">
    <div class="cart-item">
      <img src="<?= htmlspecialchars($item['img']) ?>" alt="Produto">
      <div class="info">
        <div class="nome"><?= htmlspecialchars($item['titulo']) ?></div>
        <div class="var">Cor: <?= htmlspecialchars($item['nome']) ?> (<?= htmlspecialchars($item['ref']) ?>)</div>
        <div class="preco"><?= brl($item['preco']) ?></div>
        <div class="stepper">
          <button id="minus">&minus;</button>
          <span id="qtd"><?= $item['qtd'] ?></span>
          <button id="plus">+</button>
        </div>
      </div>
    </div>

    <div class="free-ship">
      <span class="tag">FRETE GRÁTIS</span>
      Cupom <b>&nbsp;<?= htmlspecialchars($cfg['cupom_frete']) ?></b>&nbsp; aplicado — entrega sem custo
    </div>

    <div class="coupon-box">
      <input id="cupom" value="<?= htmlspecialchars($cfg['cupom_frete']) ?>" readonly>
      <span class="applied">&#10003; Aplicado</span>
    </div>
  </section>

  <section class="block">
    <h3>Resumo</h3>
    <div class="summary">
      <div class="line"><span>Subtotal</span><span id="sub">R$<?= number_format($subtotal, 2, ',', '.') ?></span></div>
      <div class="line free"><span>Frete</span><b>GRÁTIS</b></div>
      <div class="total">
        <span class="lbl">Total</span>
        <span class="val">R$<span id="tot"><?= number_format($total, 2, ',', '.') ?></span></span>
      </div>
    </div>
  </section>

  <div class="action-bar">
    <div class="ab-total">
      <div class="l">Total</div>
      <div class="v">R$<span id="totBar"><?= number_format($total, 2, ',', '.') ?></span></div>
    </div>
    <a href="cadastro.php" class="ab-btn">Continuar &#8594;</a>
  </div>
</div>

<script>
(function(){
  var qtd = <?= $item['qtd'] ?>;
  function refresh(){
    fetch('carrinho.php', {method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({qtd:qtd})})
      .then(r=>r.json()).then(function(d){
        document.getElementById('qtd').textContent = d.qtd;
        document.getElementById('sub').textContent = 'R$'+d.subtotal;
        document.getElementById('tot').textContent = d.total;
        document.getElementById('totBar').textContent = d.total;
      });
  }
  document.getElementById('plus').onclick  = function(){ qtd = Math.min(99, qtd+1); refresh(); };
  document.getElementById('minus').onclick = function(){ qtd = Math.max(1, qtd-1); refresh(); };
})();
</script>
</body>
</html>
