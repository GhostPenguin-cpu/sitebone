/* pagamento.js — seleção de método, chamada à API, PIX copia-e-cola + QR */
(function () {
  'use strict';

  var metodoAtual = 'pix';
  var methods = document.querySelectorAll('.pay-method');
  var totBar = document.getElementById('totBar');
  var tot = document.getElementById('tot');
  var lineDesc = document.getElementById('line-desc');
  var descVal = document.getElementById('desc-val');
  var btnPagar = document.getElementById('btn-pagar');

  // ---- selecionar método ----
  methods.forEach(function (m) {
    m.addEventListener('click', function (e) {
      if (e.target.closest('input, select, textarea, button')) return;
      methods.forEach(function (x) { x.classList.remove('sel'); });
      m.classList.add('sel');
      metodoAtual = m.getAttribute('data-metodo');
      var fmt = m.getAttribute('data-total-fmt');
      totBar.textContent = fmt;
      tot.textContent = fmt;
      // mostra linha de desconto só no PIX
      if (metodoAtual === 'pix') {
        lineDesc.style.display = 'flex';
        descVal.textContent = '- ' + (m.getAttribute('data-desc-fmt') || calcDesc());
      } else {
        lineDesc.style.display = 'none';
      }
    });
  });

  function calcDesc() {
    // diferença entre cartão e pix
    var pix = parseFloat(document.querySelector('[data-metodo=pix]').getAttribute('data-total'));
    var cartao = parseFloat(document.querySelector('[data-metodo=cartao]').getAttribute('data-total'));
    return 'R$' + (cartao - pix).toFixed(2).replace('.', ',');
  }
  // inicializa linha de desconto (PIX default)
  lineDesc.style.display = 'flex';
  descVal.textContent = '- ' + calcDesc();

  // ---- chamar API de pagamento ----
  function chamarAPI(payload) {
    return fetch('api/pagamento.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    }).then(function (r) { return r.json(); });
  }

  // ---- PIX ----
  function gerarPix(btn) {
    var card = document.querySelector('[data-metodo=pix]');
    var valor = parseFloat(card.getAttribute('data-total'));
    if (btn) { btn.disabled = true; btn.textContent = 'Gerando...'; }
    chamarAPI({ metodo: 'pix', valor: valor }).then(function (res) {
      if (btn) { btn.disabled = false; btn.textContent = 'Gerar código PIX'; }
      if (!res.ok) { alert(res.erro || 'Erro ao gerar PIX'); return; }
      mostrarPix(res);
    }).catch(function () {
      if (btn) { btn.disabled = false; btn.textContent = 'Gerar código PIX'; }
      alert('Falha de conexão com a API de pagamento.');
    });
  }

  function mostrarPix(res) {
    var box = document.getElementById('pix-result');
    var codeEl = document.getElementById('pix-code');
    var info = document.getElementById('pix-info');
    var qr = document.getElementById('pix-qr');
    codeEl.value = res.pix_copia_cola || '';
    info.innerHTML = (res.instrucoes || '') +
      (res.demo ? '<br><b>Modo demo:</b> integre um PSP para confirmação automática (veja o README).' : '');
    box.classList.add('show');
    // QR: usa imagem do gateway se vier, senão gera no cliente
    qr.innerHTML = '';
    if (res.qr_base64) {
      var img = new Image(); img.src = 'data:image/png;base64,' + res.qr_base64; qr.appendChild(img);
    } else if (window.qrcode && res.pix_copia_cola) {
      try {
        var q = qrcode(0, 'M');           // 0 = versão automática
        q.addData(res.pix_copia_cola);
        q.make();
        qr.innerHTML = q.createSvgTag({ cellSize: 5, margin: 8 });
      } catch (err) {
        qr.innerHTML = '<div style="font-size:12px;color:#888;text-align:center;">Use o código copia e cola abaixo.</div>';
      }
    } else {
      qr.innerHTML = '<div style="font-size:12px;color:#888;text-align:center;">QR indisponível — use o código abaixo.</div>';
    }
    box.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }

  var btnPix = document.getElementById('btn-pix');
  if (btnPix) btnPix.onclick = function () { gerarPix(btnPix); };

  // ---- copiar código PIX ----
  var copyBtn = document.getElementById('copy-pix');
  if (copyBtn) copyBtn.onclick = function () {
    var ta = document.getElementById('pix-code');
    ta.select(); ta.setSelectionRange(0, 99999);
    var done = function () { copyBtn.textContent = 'Copiado ✓'; setTimeout(function () { copyBtn.textContent = 'Copiar'; }, 1800); };
    if (navigator.clipboard) navigator.clipboard.writeText(ta.value).then(done, function () { document.execCommand('copy'); done(); });
    else { document.execCommand('copy'); done(); }
  };

  // ---- CARTÃO (demo) ----
  function pagarCartao() {
    var card = document.querySelector('[data-metodo=cartao]');
    var valor = parseFloat(card.getAttribute('data-total'));
    var parc = parseInt(document.getElementById('card-parc').value || '1', 10);
    // NUNCA enviar o número do cartão ao servidor: aqui iria o token do SDK do gateway.
    var card_token = 'TOKEN_DEMO';
    btnPagar.disabled = true; btnPagar.textContent = 'Processando...';
    chamarAPI({ metodo: 'cartao', valor: valor, parcelas: parc, card_token: card_token }).then(function (res) {
      btnPagar.disabled = false; btnPagar.textContent = 'Pagar';
      if (!res.ok) { alert(res.erro || 'Pagamento recusado'); return; }
      document.getElementById('action-bar').style.display = 'none';
      var s = document.getElementById('success');
      document.getElementById('success-msg').textContent =
        res.mensagem + ' Pedido em ' + parc + 'x.';
      s.style.display = 'block';
      s.scrollIntoView({ behavior: 'smooth' });
    });
  }

  // ---- botão Pagar do rodapé ----
  btnPagar.onclick = function () {
    if (metodoAtual === 'pix') gerarPix(null);
    else pagarCartao();
  };
})();
