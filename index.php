
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="style.css" />
  <title>CTNAPP</title>
</head>

<body>
<img src="img/fundoInicio.svg" alt="Fundo do site" class="background-svg">
  <header class="topo">
    <div class="conteudo-topo">
        <h1 class="marca">CTNAPP</h1>
      <nav class="menu">
        <a href="auth/logAdmin.php" class="botao cadastrar">
          <div class="texto">Admin</div>
        </a>
        <a href="auth/cadEscola.php" class="botao entrar">
          <div class="texto">Escola</div>
        </a>
        <a href="auth/logCantineiro.php" class="botao cadastrar">
          <div class="texto">Cantineiro</div>
        </a>
      </nav>
    </div>
  </header>

<!--<div id="modalApp">
    <div class="modal-conteudo">
        <span class="fechar" onclick="fecharModal()">&times;</span>
        <h2>Baixe o App CTNAPP</h2>
        <p>Peça seu lanche direto do celular!</p>
        <a href="#" class="btn-app">Android</a>
        <a href="#" class="btn-app">iOS</a>
    </div>
</div>
<script>
function abrirModal() {
    document.getElementById('modalApp').style.display = 'flex';
}
function fecharModal() {
    document.getElementById('modalApp').style.display = 'none';
}

window.onload = abrirModal;
</script>-->

  <main>
    <section class="apresentacao">
      <h2 class="apresentacao-titulo">Transforme a cantina da sua escola</h2>
      <p class="apresentacao-texto" style="color: black;">A gestão da cantina escolar nunca foi tão simples. Elimine filas, facilite o controle de pedidos e traga mais praticidade para alunos, pais e funcionários. Tudo na palma da mão, com eficiência e segurança. Transforme a cantina da sua escola hoje mesmo!
      </p>
      <a href="auth/escola.php" class="botao destaque">
        <div class="texto">Comece agora</div>
      </a>
    </section>

    <section class="diferenciais">
      <div class="cartao">
        <i class="fas fa-bolt"></i>
        <h3 class="cartao-titulo">Pedidos em poucos cliques</h3>
        <p class="cartao-texto">Alunos e responsáveis fazem os pedidos direto pelo app, evitando filas e agilizando o atendimento.</p>
      </div>
      <div class="cartao">
        <i class="fas fa-chart-line"></i>
        <h3 class="cartao-titulo">Transparência para os pais</h3>
        <p class="cartao-texto">Pais ou responsáveis acompanham em tempo real o que os filhos estão consumindo e controlam os gastos com alimentação.</p>
      </div>
      <div class="cartao">
        <i class="fas fa-box"></i>
        <h3 class="cartao-titulo">Organização para os cantineiros</h3>
        <p class="cartao-texto">Gestão fácil de pedidos, cardápio e entregas, direto no celular ou computador.</p>
      </div>
    </section>
  </main>

<?php include 'include/footer.php'; ?>

</body>

</html>