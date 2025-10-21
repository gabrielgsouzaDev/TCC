
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="style.css" />
  <script src="https://cdn.tailwindcss.com"></script>

  <title>CTNAPP</title>
</head>

<body>
<img src="img/fundoInicio.svg" alt="Fundo do site" class="background-svg">
<header class="bg-[var(--cor-fundo)] shadow-md">
  <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
    <!-- Marca -->
    <h1 class="text-2xl font-bold text-[var(--cor-titulo)]">CTNAPP</h1>

    <!-- Menu -->
    <nav class="flex space-x-8 relative">

      <!-- Administrador -->
      <div class="relative group">
        <button class="px-4 py-2 bg-[var(--cor-principal)] text-[var(--cor-branco)] rounded-lg font-semibold hover:bg-[var(--cor-principal-hover)] transition">
          Administrador
        </button>
        <!-- Submenu -->
        <div class="absolute left-0 mt-2 w-44 bg-[var(--cor-branco)] rounded-lg shadow-lg hidden group-hover:block z-10">
          <a href="auth/logAdmin.php" class="block px-4 py-2 text-[var(--cor-texto)] hover:bg-[var(--cor-fundo)] border-b border-[var(--cor-borda)]">Admin</a>
          <a href="auth/cadEscola.php" class="block px-4 py-2 text-[var(--cor-texto)] hover:bg-[var(--cor-fundo)] border-b border-[var(--cor-borda)]">Escola</a>
          <a href="auth/logCantineiro.php" class="block px-4 py-2 text-[var(--cor-texto)] hover:bg-[var(--cor-fundo)]">Cantineiro</a>
        </div>
      </div>

      <!-- Usuário -->
      <div class="relative group">
        <button class="px-4 py-2 bg-[var(--cor-secundaria)] text-[var(--cor-branco)] rounded-lg font-semibold hover:bg-[var(--cor-principal-hover)] transition">
          Usuário
        </button>
        <!-- Submenu -->
        <div class="absolute left-0 mt-2 w-44 bg-[var(--cor-branco)] rounded-lg shadow-lg hidden group-hover:block z-10">
          <a href="auth/logAluno.php" class="block px-4 py-2 text-[var(--cor-texto)] hover:bg-[var(--cor-fundo)] border-b border-[var(--cor-borda)]">Aluno</a>
          <a href="auth/logResponsavel.php" class="block px-4 py-2 text-[var(--cor-texto)] hover:bg-[var(--cor-fundo)]">Responsável</a>
        </div>
      </div>

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
      <a href="auth/cadEscola.php" class="botao destaque">
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