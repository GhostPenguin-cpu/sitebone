<?php
/**
 * pix.php — Gera um código PIX "Copia e Cola" (BR Code / EMV) VÁLIDO.
 * Usado no modo DEMO. Para confirmação automática de pagamento,
 * integre um PSP (Mercado Pago / Asaas / Efí) — ver api/pagamento.php.
 */

  /* ---------- Lightbox (fotos/vídeos das avaliações) ---------- */
  const lb = document.getElementById('lightbox');
  const lbImg = lb.querySelector('.lb-img');
  const lbVideo = lb.querySelector('.video-ph');   // ← importante
  const lbCount = lb.querySelector('.lb-count');
  let lbList = [];
  let lbIndex = 0;

  function renderLB() {
    const item = lbList[lbIndex];
    if (!item) return;

    if (item.tipo === 'video') {
      lbImg.style.display = 'none';
      lbVideo.style.display = 'flex';
      lbVideo.innerHTML = `
        <video controls autoplay style="max-width:92%; max-height:78%; border-radius:8px; background:#000;">
          <source src="${item.src}" type="video/mp4">
          Seu navegador não suporta vídeo.
        </video>
      `;
    } else {
      lbVideo.style.display = 'none';
      lbImg.style.display = 'block';
      lbImg.src = item.src;
    }
    lbCount.textContent = (lbIndex + 1) + ' / ' + lbList.length;
  }

/**
 * Monta o payload PIX estático.
 * @param string $chave   Chave PIX (e-mail, CPF/CNPJ, telefone ou aleatória)
 * @param string $nome    Nome do recebedor (máx 25)
 * @param string $cidade  Cidade do recebedor (máx 15)
 * @param float  $valor   Valor (0 = aberto)
 * @param string $txid    Identificador (use '***' para estático)
 * @param string $descricao Descrição opcional
 */
function pix_montar($chave, $nome, $cidade, $valor = 0, $txid = '***', $descricao = '') {
    $nome   = pix_sanitize(strtoupper($nome), 25);
    $cidade = pix_sanitize(strtoupper($cidade), 15);

    $gui = pix_emv('00', 'br.gov.bcb.pix') . pix_emv('01', $chave);
    if ($descricao !== '') {
        $gui .= pix_emv('02', pix_sanitize($descricao, 40));
    }

    $p  = pix_emv('00', '01');                 // Payload Format Indicator
    $p .= pix_emv('26', $gui);                 // Merchant Account Info (PIX)
    $p .= pix_emv('52', '0000');               // Merchant Category Code
    $p .= pix_emv('53', '986');                // Moeda (BRL)
    if ($valor !== null && $valor > 0) {
        $p .= pix_emv('54', number_format($valor, 2, '.', ''));
    }
    $p .= pix_emv('58', 'BR');                 // País
    $p .= pix_emv('59', $nome !== '' ? $nome : 'RECEBEDOR');
    $p .= pix_emv('60', $cidade !== '' ? $cidade : 'CIDADE');
    $p .= pix_emv('62', pix_emv('05', $txid ?: '***')); // Additional data (txid)
    $p .= '6304';                              // CRC placeholder
    $p .= pix_crc16($p);
    return $p;
}
