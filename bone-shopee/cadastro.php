<?php
session_start();
require __DIR__ . '/data.php';

// segurança: precisa ter carrinho
if (empty($_SESSION['carrinho'])) { header('Location: carrinho.php'); exit; }

$erros = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $tel   = trim($_POST['telefone'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $cep   = trim($_POST['cep'] ?? '');
    $rua   = trim($_POST['rua'] ?? '');
    $num   = trim($_POST['numero'] ?? '');
    $bairro= trim($_POST['bairro'] ?? '');
    $cidade= trim($_POST['cidade'] ?? '');
    $uf    = trim($_POST['uf'] ?? '');

    if ($nome === '')                              $erros[] = 'Informe seu nome.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))$erros[] = 'E-mail inválido.';
    if (strlen(preg_replace('/\D/', '', $tel)) < 10)$erros[] = 'Telefone inválido.';
    if (strlen($senha) < 6)                        $erros[] = 'A senha deve ter ao menos 6 caracteres.';
    if (strlen(preg_replace('/\D/', '', $cep)) !== 8)$erros[] = 'CEP inválido.';
    if ($rua === '' || $num === '' || $cidade === '' || $uf === '') $erros[] = 'Complete o endereço.';

    if (!$erros) {
        // Senha NUNCA em texto puro — sempre hash.
        $_SESSION['cliente'] = [
            'nome'     => $nome,
            'email'    => $email,
            'telefone' => $tel,
            'senha_hash' => password_hash($senha, PASSWORD_DEFAULT),
            'endereco' => compact('cep', 'rua', 'num', 'bairro', 'cidade', 'uf'),
        ];

        // (Opcional) persistir o "cadastro" num arquivo local de clientes:
        $arq = __DIR__ . '/clientes.json';
        $base = file_exists($arq) ? json_decode(file_get_contents($arq), true) : [];
        $base[] = ['nome'=>$nome,'email'=>$email,'telefone'=>$tel,
                   'senha_hash'=>$_SESSION['cliente']['senha_hash'],
                   'endereco'=>$_SESSION['cliente']['endereco'],'data'=>date('c')];
        @file_put_contents($arq, json_encode($base, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        header('Location: pagamento.php');
        exit;
    }
}
$v = $_POST ?? [];
function old($k){ global $v; return htmlspecialchars($v[$k] ?? ''); }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>Cadastro</title>
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/checkout.css">
</head>
<body>
<div class="phone" style="padding-bottom:80px;">

  <header class="checkout-head">
    <a href="carrinho.php" class="back">&#8592;</a>
    <h2>Seus dados</h2>
  </header>

  <div class="steps">
    <span class="dot"><b>1</b> Carrinho</span><span class="sep"></span>
    <span class="dot on"><b>2</b> Cadastro</span><span class="sep"></span>
    <span class="dot"><b>3</b> Pagamento</span>
  </div>

  <?php if ($erros): ?>
    <div class="block" style="background:#fff3f1;color:#c0392b;font-size:13px;">
      <?php foreach ($erros as $e) echo '• ' . htmlspecialchars($e) . '<br>'; ?>
    </div>
  <?php endif; ?>

  <form method="post" id="form-cad" novalidate>
    <section class="block">
      <h3>Conta</h3>
      <div class="field">
        <label>Nome completo <span class="req">*</span></label>
        <input name="nome" value="<?= old('nome') ?>" autocomplete="name" required>
      </div>
      <div class="field">
        <label>E-mail <span class="req">*</span></label>
        <input name="email" type="email" value="<?= old('email') ?>" autocomplete="email" required>
      </div>
      <div class="field">
        <label>Telefone / WhatsApp <span class="req">*</span></label>
        <input name="telefone" id="tel" value="<?= old('telefone') ?>" inputmode="numeric" placeholder="(00) 00000-0000" required>
      </div>
      <div class="field">
        <label>Senha <span class="req">*</span></label>
        <div class="pw-wrap">
          <input name="senha" id="senha" type="password" autocomplete="new-password" placeholder="mínimo 6 caracteres" required>
          <button type="button" class="toggle" id="toggle-pw">mostrar</button>
        </div>
        <div class="hint">Sua senha é armazenada de forma criptografada.</div>
      </div>
    </section>

    <section class="block">
      <h3>Endereço de entrega</h3>
      <div class="grid-cep">
        <div class="field">
          <label>CEP <span class="req">*</span></label>
          <input name="cep" id="cep" value="<?= old('cep') ?>" inputmode="numeric" placeholder="00000-000" required>
          <div class="hint" id="cep-hint">Preenche o endereço automaticamente.</div>
        </div>
        <div class="field">
          <label>Número <span class="req">*</span></label>
          <input name="numero" id="numero" value="<?= old('numero') ?>" inputmode="numeric" required>
        </div>
      </div>
      <div class="field">
        <label>Rua / Logradouro <span class="req">*</span></label>
        <input name="rua" id="rua" value="<?= old('rua') ?>" autocomplete="address-line1" required>
      </div>
      <div class="field">
        <label>Complemento</label>
        <input name="complemento" id="complemento" value="<?= old('complemento') ?>" placeholder="apto, bloco (opcional)">
      </div>
      <div class="field">
        <label>Bairro</label>
        <input name="bairro" id="bairro" value="<?= old('bairro') ?>">
      </div>
      <div class="grid-2">
        <div class="field">
          <label>Cidade <span class="req">*</span></label>
          <input name="cidade" id="cidade" value="<?= old('cidade') ?>" required>
        </div>
        <div class="field">
          <label>UF <span class="req">*</span></label>
          <input name="uf" id="uf" value="<?= old('uf') ?>" maxlength="2" style="text-transform:uppercase" required>
        </div>
      </div>
    </section>

    <div class="action-bar">
      <button type="submit" class="ab-btn full">Ir para o pagamento &#8594;</button>
    </div>
  </form>
</div>

<script src="assets/js/checkout.js"></script>
</body>
</html>
