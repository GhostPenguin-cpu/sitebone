/* app.js — interatividade da página de produto */
(function () {
  "use strict";

  /* ---------- Contagem regressiva da oferta ---------- */
  function startCountdown(el, totalSeconds) {
    const hEl = el.querySelector("[data-h]");
    const mEl = el.querySelector("[data-m]");
    const sEl = el.querySelector("[data-s]");
    function pad(n) {
      return String(n).padStart(2, "0");
    }
    function tick() {
      if (totalSeconds < 0) totalSeconds = 0;
      const h = Math.floor(totalSeconds / 3600);
      const m = Math.floor((totalSeconds % 3600) / 60);
      const s = totalSeconds % 60;
      hEl.textContent = pad(h);
      mEl.textContent = pad(m);
      sEl.textContent = pad(s);
      if (totalSeconds > 0) totalSeconds--;
    }
    tick();
    setInterval(tick, 1000);
  }
  const timer = document.querySelector(".flash .timer");
  if (timer) startCountdown(timer, 58 * 60 + 52); // 00:58:52

  /* ---------- Galeria principal: contador de slides ---------- */
  const track = document.querySelector(".gallery .track");
  const counter = document.querySelector(".gallery .counter");
  if (track && counter) {
    const slides = track.querySelectorAll(".slide");
    track.addEventListener(
      "scroll",
      function () {
        const idx = Math.round(track.scrollLeft / track.clientWidth);
        counter.textContent = idx + 1 + "/" + slides.length;
      },
      { passive: true },
    );
  }

  /* ---------- Seletor de cor ---------- */
  const colors = document.querySelectorAll(".color");
  const mainImg = document.querySelector(".gallery .slide:first-child img");
  const selName = document.querySelector(".selected-name");
  colors.forEach(function (c) {
    c.addEventListener("click", function () {
      colors.forEach((x) => x.classList.remove("active"));
      c.classList.add("active");
      const img = c.getAttribute("data-img");
      const nome = c.getAttribute("data-nome");
      const ref = c.getAttribute("data-ref");
      if (mainImg && img) {
        mainImg.src = img;
        if (track) track.scrollTo({ left: 0, behavior: "smooth" });
      }
      if (selName)
        selName.innerHTML = "Selecionado: <b>" + nome + "</b> (" + ref + ")";
    });
  });

  /* ---------- Acordeão ---------- */
  document.querySelectorAll(".acc-head").forEach(function (head) {
    head.addEventListener("click", function () {
      const body = head.nextElementSibling;
      const open = head.classList.toggle("open");
      body.style.maxHeight = open ? body.scrollHeight + "px" : "0px";
    });
  });

  /* ---------- Lightbox (fotos/vídeos das avaliações) ---------- */
  const lb = document.getElementById("lightbox");
  const lbImg = lb.querySelector(".lb-img");
  const lbVideo = lb.querySelector(".video-ph");
  const lbCount = lb.querySelector(".lb-count");
  let lbList = [];
  let lbIndex = 0;

  function renderLB() {
    const item = lbList[lbIndex];
    if (!item) return;

    if (item.tipo === "video") {
      lbImg.style.display = "none";
      lbVideo.style.display = "flex";
      lbVideo.innerHTML = `
        <video controls autoplay loop style="max-width: 92%; max-height: 78%; border-radius: 8px; background: #000;">
          <source src="${item.src}" type="video/mp4">
          Seu navegador não suporta o formato de vídeo.
        </video>
      `;
    } else {
      lbVideo.style.display = "none";
      lbImg.style.display = "block";
      lbImg.src = item.src;
    }
    lbCount.textContent = lbIndex + 1 + " / " + lbList.length;
  }

  document.querySelectorAll(".review").forEach(function (review) {
    const medias = Array.from(review.querySelectorAll(".media")).map(
      function (m) {
        return {
          src: m.getAttribute("data-src"),
          tipo: m.getAttribute("data-tipo"),
          dur: m.getAttribute("data-dur"),
        };
      },
    );
    review.querySelectorAll(".media").forEach(function (m, i) {
      m.addEventListener("click", function () {
        lbList = medias;
        lbIndex = i;
        renderLB();
        lb.classList.add("show");
      });
    });
  });

  lb.querySelector(".close").addEventListener("click", () =>
    lb.classList.remove("show"),
  );
  lb.querySelector(".lb-prev").addEventListener("click", function () {
    lbIndex = (lbIndex - 1 + lbList.length) % lbList.length;
    renderLB();
  });
  lb.querySelector(".lb-next").addEventListener("click", function () {
    lbIndex = (lbIndex + 1) % lbList.length;
    renderLB();
  });
  lb.addEventListener("click", function (e) {
    if (e.target === lb) lb.classList.remove("show");
  });

  /* ---------- Toast helper + ações do rodapé ---------- */
  const toast = document.getElementById("toast");
  let toastTimer;
  function showToast(msg) {
    toast.textContent = msg;
    toast.classList.add("show");
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => toast.classList.remove("show"), 1800);
  }
  function corSelecionada() {
    const a = document.querySelector(".color.active");
    return a ? encodeURIComponent(a.getAttribute("data-ref")) : "";
  }
  const buy = document.querySelector(".footer .buy");
  if (buy)
    buy.addEventListener("click", function () {
      showToast("🛒 Indo para o carrinho...");
      setTimeout(function () {
        window.location.href = "carrinho.php?cor=" + corSelecionada();
      }, 350);
    });
  document.querySelectorAll(".footer .fbtn").forEach(function (b) {
    b.addEventListener("click", function () {
      if (/[Cc]arrinho/.test(b.textContent)) {
        window.location.href = "carrinho.php?cor=" + corSelecionada();
      } else {
        showToast(b.getAttribute("data-msg") || "Ação");
      }
    });
  });
  const heart = document.querySelector(".heart");
  if (heart)
    heart.addEventListener("click", function () {
      const filled = heart.getAttribute("fill") === "none";
      heart.setAttribute("fill", filled ? "#ee4d2d" : "none");
      heart.setAttribute("stroke", filled ? "#ee4d2d" : "#bdbdbd");
      showToast(
        filled ? "❤️ Adicionado aos favoritos" : "Removido dos favoritos",
      );
    });
})();
