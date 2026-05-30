#!/usr/bin/env python3
"""Gera placeholders SVG de bonés trucker e fotos de avaliacao.
Troque depois pelos seus arquivos reais (mesmos nomes) na pasta assets/img."""
import os

IMG = os.path.join(os.path.dirname(__file__), "assets", "img")
os.makedirs(IMG, exist_ok=True)


def cap_svg(crown, crown2, mesh, bill, patch, patch_txt="#fff", w=600, h=600):
    """Boné trucker em vista 3/4 (estilo das fotos)."""
    return f'''<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {w} {h}" width="{w}" height="{h}">
  <defs>
    <radialGradient id="bg" cx="50%" cy="40%" r="75%">
      <stop offset="0%" stop-color="#fbfbfb"/><stop offset="100%" stop-color="#e9e9e9"/>
    </radialGradient>
    <linearGradient id="cr" x1="0" y1="0" x2="0" y2="1">
      <stop offset="0%" stop-color="{crown}"/><stop offset="100%" stop-color="{crown2}"/>
    </linearGradient>
    <pattern id="mesh" width="14" height="14" patternUnits="userSpaceOnUse" patternTransform="rotate(8)">
      <rect width="14" height="14" fill="{mesh}"/>
      <circle cx="7" cy="7" r="4.4" fill="#000" fill-opacity="0.18"/>
      <circle cx="7" cy="7" r="3.2" fill="{mesh}"/>
    </pattern>
  </defs>
  <rect width="{w}" height="{h}" fill="url(#bg)"/>
  <ellipse cx="300" cy="470" rx="210" ry="34" fill="#000" fill-opacity="0.10"/>
  <!-- mesh traseira -->
  <path d="M300 150 Q470 150 480 300 Q485 360 430 372 L300 372 Z" fill="url(#mesh)" stroke="{crown2}" stroke-width="3"/>
  <!-- coroa frontal -->
  <path d="M300 150 Q150 150 130 300 Q126 350 150 372 L300 372 Z" fill="url(#cr)"/>
  <!-- costura central -->
  <path d="M300 150 L300 372" stroke="{crown2}" stroke-width="2.5" opacity="0.55"/>
  <path d="M214 156 Q205 270 195 368" stroke="{crown2}" stroke-width="2" opacity="0.4" fill="none"/>
  <!-- aba/bico curvado -->
  <path d="M150 360 Q120 392 150 430 Q260 470 330 432 Q300 392 300 366 Q230 388 150 360 Z" fill="{bill}"/>
  <path d="M150 360 Q120 392 150 430" fill="none" stroke="#000" stroke-opacity="0.15" stroke-width="3"/>
  <!-- botao topo -->
  <circle cx="300" cy="152" r="9" fill="{crown2}"/>
  <!-- patch frontal -->
  <rect x="186" y="232" width="92" height="66" rx="8" fill="{patch}" stroke="#000" stroke-opacity="0.18" stroke-width="2"/>
  <rect x="196" y="244" width="72" height="42" rx="4" fill="none" stroke="{patch_txt}" stroke-width="3"/>
  <path d="M210 265 L232 254 L254 265 L232 276 Z" fill="{patch_txt}"/>
  <circle cx="232" cy="265" r="4" fill="{patch}"/>
</svg>'''


VARIANTS = {
    "cap_caramelo": cap_svg("#c98a4b", "#a96b30", "#5a3d27", "#8a5a2e", "#7a4a22"),
    "cap_preto":    cap_svg("#2b2b2b", "#161616", "#1c1c1c", "#101010", "#000000"),
    "cap_marrom":   cap_svg("#6e4a30", "#4d3220", "#3a2718", "#3a2718", "#2a1c11"),
    "cap_marinho":  cap_svg("#2c3e57", "#1b2738", "#22304a", "#161f2e", "#0f1622"),
    "cap_cinza":    cap_svg("#9aa0a6", "#71767c", "#80868c", "#5f6368", "#4a4d50"),
    "cap_verde":    cap_svg("#566b3f", "#3d4d2c", "#46552f", "#33401f", "#27310f"),
    "cap_bege":     cap_svg("#d9c39c", "#c0a978", "#b29f78", "#a88f5f", "#8f7748"),
}

for name, svg in VARIANTS.items():
    with open(os.path.join(IMG, f"{name}.svg"), "w") as f:
        f.write(svg)

# galeria extra (mesmos bonés em angulos -> reaproveitamos com leve variacao de fundo)
for i, base in enumerate(["cap_caramelo", "cap_marrom", "cap_marinho"], 1):
    svg = VARIANTS[base].replace('#fbfbfb', '#ffffff').replace('cx="300" cy="470"', f'cx="{300-10*i}" cy="475"')
    with open(os.path.join(IMG, f"gallery_{i}.svg"), "w") as f:
        f.write(svg)


def review_photo(bg1, bg2, label, w=400, h=400):
    return f'''<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {w} {h}" width="{w}" height="{h}">
  <defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1">
    <stop offset="0%" stop-color="{bg1}"/><stop offset="100%" stop-color="{bg2}"/>
  </linearGradient></defs>
  <rect width="{w}" height="{h}" fill="url(#g)"/>
  <text x="50%" y="52%" font-family="Arial" font-size="22" fill="#ffffff" fill-opacity="0.85"
        text-anchor="middle">{label}</text>
</svg>'''


reviews = {
    "rev1_a": ("#3a4750", "#1f2730", "Foto do cliente"),
    "rev1_b": ("#b07a3e", "#7a5025", "Boné usado"),
    "rev1_c": ("#2a2a2a", "#101010", "Vista interna"),
    "rev2_a": ("#262626", "#0d0d0d", "Boné preto"),
    "rev2_b": ("#333333", "#161616", "Detalhe tela"),
    "rev2_c": ("#1f1f1f", "#0a0a0a", "Aba"),
}
for name, (a, b, lbl) in reviews.items():
    with open(os.path.join(IMG, f"{name}.svg"), "w") as f:
        f.write(review_photo(a, b, lbl))

print("Assets gerados:", sorted(os.listdir(IMG)))
