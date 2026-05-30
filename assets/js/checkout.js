/* checkout.js — máscaras, busca de CEP e validações do cadastro */
(function () {
  'use strict';

  // ---- mostrar/ocultar senha ----
  var tgl = document.getElementById('toggle-pw');
  if (tgl) tgl.onclick = function () {
    var s = document.getElementById('senha');
    var hide = s.type === 'password';
    s.type = hide ? 'text' : 'password';
    tgl.textContent = hide ? 'ocultar' : 'mostrar';
  };

  // ---- máscara telefone ----
  var tel = document.getElementById('tel');
  if (tel) tel.addEventListener('input', function () {
    var d = tel.value.replace(/\D/g, '').slice(0, 11);
    var out = '';
    if (d.length > 0) out = '(' + d.slice(0, 2);
    if (d.length >= 2) out += ') ' + d.slice(2, d.length > 10 ? 7 : 6);
    if (d.length > 6) out += '-' + d.slice(d.length > 10 ? 7 : 6, 11);
    tel.value = out;
  });

  // ---- máscara + busca de CEP (ViaCEP) ----
  var cep = document.getElementById('cep');
  if (cep) {
    cep.addEventListener('input', function () {
      var d = cep.value.replace(/\D/g, '').slice(0, 8);
      cep.value = d.length > 5 ? d.slice(0, 5) + '-' + d.slice(5) : d;
      if (d.length === 8) buscarCep(d);
    });
  }

  function buscarCep(d) {
    var hint = document.getElementById('cep-hint');
    hint.textContent = 'Buscando endereço...';
    hint.classList.remove('error');
    fetch('https://viacep.com.br/ws/' + d + '/json/')
      .then(function (r) { return r.json(); })
      .then(function (j) {
        if (j.erro) { hint.textContent = 'CEP não encontrado — preencha manualmente.'; hint.classList.add('error'); return; }
        set('rua', j.logradouro); set('bairro', j.bairro);
        set('cidade', j.localidade); set('uf', j.uf);
        hint.textContent = 'Endereço encontrado ✓';
        var num = document.getElementById('numero'); if (num) num.focus();
      })
      .catch(function () { hint.textContent = 'Não foi possível buscar — preencha manualmente.'; hint.classList.add('error'); });
  }
  function set(id, val) { var el = document.getElementById(id); if (el && val) el.value = val; }

  // ---- destaque simples de campos obrigatórios ----
  var form = document.getElementById('form-cad');
  if (form) form.addEventListener('submit', function () {
    form.querySelectorAll('[required]').forEach(function (f) {
      f.classList.toggle('err', !f.value.trim());
    });
  });
})();
