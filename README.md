# Página de Produto — Boné Trucker (estilo Shopee)

Página de vendas mobile interativa em **PHP + HTML + JavaScript**, recriada
fielmente a partir das telas enviadas: galeria, seletor de cor/modelo, oferta
relâmpago com contagem regressiva, frete, seções expansíveis, avaliações com
fotos/vídeos e rodapé fixo.

## Como rodar

Precisa de PHP instalado. Na pasta do projeto:

```bash
php -S localhost:8000
```

Depois abra no navegador: `http://localhost:8000`

(Também funciona em qualquer hospedagem com PHP — basta subir os arquivos.)

## Estrutura

```
bone-shopee/
├── index.php            # página (HTML gerado pelos dados)
├── data.php             # << EDITE AQUI: produto, cores, avaliações, preços
├── gen_assets.py        # gerador dos placeholders SVG (opcional)
└── assets/
    ├── css/style.css    # estilos (cores, layout)
    ├── js/app.js        # interatividade (timer, troca de cor, galeria)
    └── img/             # imagens dos bonés e das avaliações
```

## Como personalizar

Quase tudo é editado em **`data.php`**:

- **Trocar fotos do produto:** coloque seus arquivos (`.jpg`, `.png`, `.webp`)
  em `assets/img/` e atualize os caminhos em `$PRODUTO['galeria']` e em
  `$PRODUTO['cores'][...]['img']`.
- **Preço / desconto / parcelas:** campos `preco`, `preco_antigo`, `desconto`,
  `parcelas`, `parcela_valor`.
- **Cores/modelos:** array `$PRODUTO['cores']` (cada item tem `ref`, `nome`,
  `img`; use `'hot' => true` para o selo de fogo 🔥).
- **Avaliações:** array `$AVALIACOES` — usuário, estrelas, texto, e `midias`
  (fotos ou vídeos com duração e selo `+N`).

### Vídeo real nas avaliações

Os vídeos são placeholders. Para usar um vídeo de verdade, troque o bloco
`.video-ph` no `app.js` (função `renderLB`) por uma tag `<video controls>`
apontando para o arquivo do cliente.

## Tempo da oferta relâmpago

A contagem começa em `00:58:52`. Para mudar, edite a última linha do timer em
`assets/js/app.js`: `startCountdown(timer, 58*60 + 52)`.

## Observação

As imagens de boné incluídas são ilustrações SVG geradas como placeholder —
substitua pelas fotos reais do seu produto antes de publicar.

---

# Fluxo de venda (Carrinho → Cadastro → Pagamento)

Páginas novas:

- **`carrinho.php`** — produto, quantidade, **frete sempre GRÁTIS** com o cupom
  `FRETEGRATIS` já aplicado, resumo e botão "Continuar".
- **`cadastro.php`** — cadastro do cliente: nome, e-mail, telefone (com máscara),
  senha e endereço. O **CEP busca o endereço automaticamente** (API ViaCEP) e
  preenche rua/bairro/cidade/UF. A senha é salva **com hash** (`password_hash`),
  nunca em texto puro.
- **`pagamento.php`** — resumo do pedido + escolha do método:
  - **PIX** (selecionado por padrão) com **10% de desconto** automático.
  - **Cartão de crédito** parcelado (sem desconto).
- **`api/pagamento.php`** — backend que o botão "Pagar" chama. Já gera o
  **PIX copia-e-cola válido** e tem os stubs prontos para o gateway real.

O estado do pedido passa entre as páginas por **sessão PHP**.

## Botão de pagamento e o "copia e cola"

Ao clicar em **Gerar código PIX / Pagar**, o JS chama `api/pagamento.php`, que
devolve o código **copia e cola** e o QR (gerado no navegador, lib local
`assets/js/qrcode.lib.js` — sem CDN). No **modo demo** o código é um BR Code
estático válido: dá pra colar no app do banco. Só **não há confirmação
automática** — isso exige um PSP (próximo passo).

## Integrar o gateway real (confirmação automática)

1. Abra **`config.php`** e preencha:
   - `pix_chave`, `pix_nome`, `pix_cidade` (recebedor do PIX);
   - `gateway` = `mercadopago` | `asaas` | `efi`;
   - `gateway_token` (token/chave do gateway) e `gateway_sandbox`.
2. Em **`api/pagamento.php`**, descomente/ajuste o bloco do seu gateway
   (já há um exemplo pronto do Mercado Pago: cria a cobrança PIX e devolve
   `qr_code` + `qr_code_base64`). O front exibe automaticamente.
3. Configure o **webhook** do gateway para receber a confirmação e marcar o
   pedido como pago.

### Cartão de crédito — segurança (PCI)

**Nunca envie o número do cartão para o seu servidor.** Use o **SDK JS** do
gateway no navegador para gerar um **token** e envie só o token para
`api/pagamento.php` (o campo `card_token` já está preparado). O formulário de
cartão incluso é a interface; troque a captura pelo SDK do gateway.

## Personalizar regras

- Desconto do PIX, cupom de frete e nº de parcelas: em **`config.php`**.
- O frete é fixo em **GRÁTIS** (regra do projeto) — definido em `carrinho.php`
  e `pagamento.php`.

## Cadastros salvos

No modo demo, cada cadastro é anexado em `clientes.json` (com a senha em hash).
Em produção, troque por um banco de dados (MySQL/PostgreSQL).
