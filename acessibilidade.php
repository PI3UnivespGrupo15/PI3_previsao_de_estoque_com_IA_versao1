<!-- acessibilidade.php -->
<button class="btn-acessibilidade" onclick="alternarFonte()" title="Aumentar/Reduzir Fonte">
  ♿
</button>

<style>
  .btn-acessibilidade {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 80px;
    height: 80px;
    background: #007BFF;
    color: #fff;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    font-size: 72px;
    font-weight: bold;
    box-shadow: 0 4px 6px rgba(0,0,0,0.3);
    transition: all 0.3s ease;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
  }

  .btn-acessibilidade:hover {
    background: #0056b3;
    transform: scale(1.2);
  }
</style>

<script>
  // acessibilidade: aumentar/reduzir fonte
  let fonteAumentada = localStorage.getItem("fonteAumentada") === "true";

  function aplicarFonte() {
      const tamanho = fonteAumentada ? "32px" : "18px"; // fontes proporcionais
      document.body.style.fontSize = tamanho;

      // aplica em todos os elementos importantes, incluindo links
      const elementos = document.querySelectorAll(
          'input, select, textarea, label, p, h1, h2, h3, h4, h5, h6, button, a'
      );
      elementos.forEach(el => {
          el.style.fontSize = tamanho;
      });
  }

  function alternarFonte() {
      fonteAumentada = !fonteAumentada;
      localStorage.setItem("fonteAumentada", fonteAumentada);
      aplicarFonte();
  }

  window.onload = aplicarFonte;
</script>
