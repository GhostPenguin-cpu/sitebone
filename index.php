<?php require __DIR__ . '/data.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title><?= htmlspecialchars($PRODUTO['titulo']) ?></title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="phone">

  <!-- STATUS BAR -->

  <!-- TOP NAV -->

  <!-- GALERIA -->
  <section class="gallery">
    <div class="track">
      <?php foreach ($PRODUTO['galeria'] as $img): ?>
        <div class="slide"><img src="<?= htmlspecialchars($img) ?>" alt="Foto do produto" loading="lazy"></div>
      <?php endforeach; ?>
    </div>
    <span class="counter">1/<?= count($PRODUTO['galeria']) ?></span>
  </section>

  <!-- OFERTA RELÂMPAGO -->
  <div class="flash">
    <div class="left"><span class="bolt">&#9889;</span> OFERTAS RELÂMPAGO</div>
    <div class="right">
      TERMINA EM
      <span class="timer"><span data-h>00</span><b>:</b><span data-m>58</span><b>:</b><span data-s>52</span></span>
    </div>
  </div>

  <!-- PREÇO -->
  <section class="price-card">
    <div class="price-row">
      <div>
        <div class="price-main">
          <span class="cur">R$</span>
          <span class="val"><?= number_format($PRODUTO['preco'], 2, ',', '.') ?></span>
          <span class="old"><?= brl($PRODUTO['preco_antigo']) ?></span>
          <span class="off">-<?= $PRODUTO['desconto'] ?>%</span>
        </div>
        <div class="installments">
          Em até <?= $PRODUTO['parcelas'] ?>x <?= brl($PRODUTO['parcela_valor']) ?> <span class="chev">&#8250;</span>
        </div>
      </div>
      <div class="sold">
        <?= htmlspecialchars($PRODUTO['vendidos']) ?> Vendido(s)
        <svg class="heart" viewBox="0 0 24 24" fill="none" stroke="#bdbdbd"><path d="M12 21s-7-4.5-9.5-9A5 5 0 0 1 12 5a5 5 0 0 1 9.5 7c-2.5 4.5-9.5 9-9.5 9z"/></svg>
      </div>
    </div>
    <div class="coupon-pill"><?= htmlspecialchars($PRODUTO['cupom_extra']) ?> <span class="chev">&#8250;</span></div>
  </section>

  <!-- TÍTULO -->
  <section class="title-card">
    <?php if ($PRODUTO['indicado']): ?><span class="indicado">Indicado</span><?php endif; ?>
    <h1><?= htmlspecialchars($PRODUTO['titulo']) ?></h1>
  </section>

  <!-- SELETOR DE COR -->
  <section class="section">
    <div class="label">Cor</div>
    <div class="colors">
      <?php foreach ($PRODUTO['cores'] as $i => $cor): ?>
        <div class="color<?= $i === 0 ? ' active' : '' ?>"
             data-img="<?= htmlspecialchars($cor['img']) ?>"
             data-nome="<?= htmlspecialchars($cor['nome']) ?>"
             data-ref="<?= htmlspecialchars($cor['ref']) ?>">
          <img src="<?= htmlspecialchars($cor['img']) ?>" alt="<?= htmlspecialchars($cor['nome']) ?>">
          <?php if (!empty($cor['hot'])): ?><span class="fire">&#128293;</span><?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="selected-name">Selecionado: <b><?= htmlspecialchars($PRODUTO['cores'][0]['nome']) ?></b> (<?= htmlspecialchars($PRODUTO['cores'][0]['ref']) ?>)</div>
  </section>

  <!-- FRETE -->
  <div class="row-line">
    <span class="ic"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M3 4h11v9H3zM14 8h4l3 3v2h-7zM7 18a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm10 0a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/></svg></span>
    <div class="body">
      <span class="strong">Frete</span>
      <span class="old"><?= brl($PRODUTO['frete_antigo']) ?></span>
      <span class="now"><?= brl($PRODUTO['frete']) ?></span>
      <div class="sub"><?= htmlspecialchars($PRODUTO['frete_gratis']) ?></div>
    </div>
    <span class="chev">&#8250;</span>
  </div>

  <!-- PARCELADO -->
  <div class="row-line">
    <span class="ic"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M3 6h18v4H3zM3 12h18v6H3zm3 3h5v1H6z"/></svg></span>
    <div class="body"><span class="strong">Parcelado:</span> Parcele em até <?= $PRODUTO['parcelado_max'] ?>x</div>
    <span class="chev">&#8250;</span>
  </div>

  <!-- RANK -->
  <div class="rank">
    <span class="trophy">&#127942;</span>
    <span class="txt"><?= htmlspecialchars($PRODUTO['rank']) ?></span>
    <span class="chev">&#8250;</span>
  </div>

  <!-- ACORDEÃO: GUIA DE TAMANHOS -->
  <div class="accordion">
    <div class="acc-head">Guia de Tamanhos <span class="chev">&#9662;</span></div>
    <div class="acc-body"><div class="inner"><?= htmlspecialchars($PRODUTO['guia_tamanhos']) ?></div></div>

    <!-- ACORDEÃO: ESPECIFICAÇÕES -->
    <div class="acc-head">Especificações e Descrição <span class="chev">&#9662;</span></div>
    <div class="acc-body"><div class="inner">
      <table class="spec-table">
        <?php foreach ($PRODUTO['especificacoes'] as $k => $v): ?>
          <tr><td><?= htmlspecialchars($k) ?></td><td><?= htmlspecialchars($v) ?></td></tr>
        <?php endforeach; ?>
      </table>
      <p style="margin-top:12px;"><?= htmlspecialchars($PRODUTO['descricao']) ?></p>
    </div></div>
  </div>

  <!-- AVALIAÇÕES -->
  <section class="reviews">
    <div class="rev-summary">
      <span class="nota"><?= number_format($AVALIACOES_RESUMO['nota'], 1, '.', '') ?></span>
      <span class="star">&#9733;</span>
      <span class="ttl">Avaliações do produto (<?= htmlspecialchars($AVALIACOES_RESUMO['total']) ?>)</span>
      <span class="ver">Ver mais &#8250;</span>
    </div>
    <div class="quality">
      <span class="bar">&#128227;</span> | <?= $AVALIACOES_RESUMO['boa_qualidade'] ?>% dizem boa qualidade
    </div>

    <?php foreach ($AVALIACOES as $av): ?>
      <article class="review">
        <div class="rev-top">
          <span class="rev-avatar"><svg viewBox="0 0 24 24"><path d="M12 12a5 5 0 1 0 0-10 5 5 0 0 0 0 10zm0 2c-5 0-9 2.5-9 6v2h18v-2c0-3.5-4-6-9-6z"/></svg></span>
          <span class="rev-user"><?= htmlspecialchars($av['usuario']) ?></span>
          <?php if (!empty($av['data'])): ?>
            <span class="rev-date"><?= htmlspecialchars($av['data']) ?></span>
          <?php endif; ?>
          <span class="rev-useful">Útil (<?= $av['util'] ?>) <svg viewBox="0 0 24 24"><path d="M2 10h4v11H2zM22 11a2 2 0 0 0-2-2h-5l1-4a2 2 0 0 0-2-2l-5 8v10h11a2 2 0 0 0 2-1.7l1-7z"/></svg></span>
        </div>
        <div class="rev-stars"><?= str_repeat('&#9733;', $av['estrelas']) ?></div>
        <div class="rev-var">Variação: <?= htmlspecialchars($av['variacao']) ?></div>
        <?php foreach ($av['campos'] as $titulo => $texto): ?>
          <div class="rev-field"><b><?= htmlspecialchars($titulo) ?>:</b> <?= htmlspecialchars($texto) ?></div>
        <?php endforeach; ?>

        <?php if (!empty($av['midias'])): ?>
          <div class="rev-media">
            <?php foreach ($av['midias'] as $m): ?>
              <?php $isVideo = $m['tipo'] === 'video'; ?>
              <div class="media <?= $isVideo ? 'media-video' : '' ?>"
                   data-src="<?= htmlspecialchars($m['src']) ?>"
                   data-tipo="<?= htmlspecialchars($m['tipo']) ?>"
                   data-dur="<?= htmlspecialchars($m['duracao'] ?? '') ?>">

                <?php if ($isVideo): ?>
                  <!-- Thumbnail gerada pelo próprio vídeo -->
                  <video
                    src="<?= htmlspecialchars($m['src']) ?>"
                    preload="metadata"
                    muted
                    playsinline
                    class="video-thumb"
                    style="pointer-events:none;">
                  </video>
                  <span class="play">
                    <svg viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                  </span>
                  <?php if (!empty($m['duracao'])): ?>
                    <span class="dur"><?= htmlspecialchars($m['duracao']) ?></span>
                  <?php endif; ?>
                <?php else: ?>
                  <img src="<?= htmlspecialchars($m['src']) ?>" alt="Foto da avaliação" loading="lazy">
                  <?php if (!empty($m['extra'])): ?>
                    <span class="more">
                      <svg viewBox="0 0 24 24"><path d="M21 19V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2zM8.5 13.5l2.5 3 3.5-4.5L19 18H5z"/></svg>
                      +<?= $m['extra'] ?>
                    </span>
                  <?php endif; ?>
                <?php endif; ?>

              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </article>
    <?php endforeach; ?>
  </section>

  <!-- RODAPÉ FIXO -->
  <nav class="footer">
    <div class="left">
      <div class="fbtn" data-msg="💬 Abrindo chat com a loja...">
        <svg viewBox="0 0 24 24"><path d="M21 11.5a8.4 8.4 0 0 1-8.5 8.5 8.6 8.6 0 0 1-3.8-.9L3 21l1.9-5.7A8.4 8.4 0 0 1 4 11.5 8.4 8.4 0 0 1 12.5 3 8.4 8.4 0 0 1 21 11.5z"/></svg>
        Chat
      </div>
      <div class="divider"></div>
      <div class="fbtn" data-msg="🛒 Adicionado ao carrinho">
        <svg viewBox="0 0 24 24"><path d="M3 4h2l2.6 12.4A2 2 0 0 0 9.5 18H18a2 2 0 0 0 2-1.6L21.5 8H6"/><circle cx="9.5" cy="20.5" r="1.2"/><circle cx="17" cy="20.5" r="1.2"/><path d="M14 7v4M12 9h4"/></svg>
        Carrinho
      </div>
    </div>
    <button class="buy">Compre com cupom</button>
  </nav>

  <!-- LIGHTBOX -->
  <div class="lightbox" id="lightbox">
    <span class="close">&times;</span>
    <span class="lb-nav lb-prev">&#8249;</span>
    <img class="lb-img" src="" alt="" style="display:none;">
    <video class="lb-video" controls playsinline style="display:none; max-width:100%; max-height:80vh;"></video>
    <span class="lb-nav lb-next">&#8250;</span>
    <span class="lb-count"></span>
  </div>

  <div class="toast" id="toast"></div>
</div>

<script src="assets/js/app.js"></script>
</body>
</html>