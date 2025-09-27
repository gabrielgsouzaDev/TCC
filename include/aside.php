<button class="menu-toggle" id="menuToggle">☰</button>

<nav class="menu-lateral" id="menuLateral">
  <h2>Painel Admin</h2>
  <button class="ativo" data-pagina="dashboard">Dashboard</button>
  <button data-pagina="escolas">Escolas</button>
  <!--<button data-pagina="financeiro">Histórico Financeiro</button>
  <button data-pagina="usuarios">Usuários</button>-->
  <button data-pagina="configuracoes">Configurações</button>
</nav>
<style>
        :root {
      --cor-fundo: #f8f2f3;
      --cor-branco: #ffffff;
      --cor-titulo: #7a1523;
      --cor-texto: #4d4d4d;
      --cor-borda: #d8b0b5;
      --cor-principal: #a71d31;
      --cor-principal-hover: #7a1523;
      --cor-secundaria: #e57373;
    }

        * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      background-color: var(--cor-fundo);
      color: var(--cor-texto);
      display: flex;
      min-height: 100vh;
    }

    /* MENU */
    .menu-lateral {
      width: 220px;
      background-color: var(--cor-branco);
      box-shadow: 2px 0 10px rgba(0,0,0,0.1);
      padding: 20px 0;
      display: flex;
      flex-direction: column;
    }

    .menu-lateral h2 {
      color: var(--cor-titulo);
      text-align: center;
      margin-bottom: 30px;
      font-size: 1.4rem;
    }

    .menu-lateral button {
      background: none;
      border: none;
      width: 100%;
      padding: 14px 20px;
      text-align: left;
      font-size: 1rem;
      color: var(--cor-texto);
      cursor: pointer;
      transition: background 0.2s;
    }

    .menu-lateral button:hover {
      background-color: var(--cor-fundo);
    }

    .menu-lateral button.ativo {
      background-color: var(--cor-principal);
      color: var(--cor-branco);
    }

    .conteudo {
      flex: 1;
      padding: 30px;
    }

    .conteudo h1 {
      color: var(--cor-titulo);
      margin-bottom: 20px;
    }
    /* Container da página */
    .pagina {
        padding: 20px;
        font-family: 'Arial', sans-serif;
        background: var(--cor-fundo, #f8f2f3);
        color: var(--cor-texto, #4d4d4d);
    }

    /* Título da página */
    .pagina h1 {
        font-size: 2rem;
        margin-bottom: 20px;
        color: var(--cor-titulo, #7a1523);
    }


    @media (max-width: 768px) {
      .menu-lateral {
        position: fixed;
        left: -220px;
        top: 0;
        height: 100vh;
        z-index: 100;
        transition: left 0.3s;
      }

      .menu-lateral.aberto {
        left: 0;
      }

      .menu-toggle {
        display: block;
        position: fixed;
        top: 15px;
        left: 15px;
        background: var(--cor-principal);
        color: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 5px;
        font-size: 1.2rem;
        z-index: 101;
      }
    }

    .menu-toggle {
      display: none;
    }
    </style>
<script>
  const menuToggle = document.getElementById('menuToggle');
  const menuLateral = document.getElementById('menuLateral');

  menuToggle.addEventListener('click', () => {
    menuLateral.classList.toggle('aberto');
  });

  // Alternar entre páginas
  const botoesMenu = menuLateral.querySelectorAll('button');
  const conteudoPrincipal = document.querySelector('.conteudo-principal');

  botoesMenu.forEach(botao => {
    botao.addEventListener('click', () => {
      // Remover classe 'ativo' de todos os botões
      botoesMenu.forEach(b => b.classList.remove('ativo'));
      // Adicionar classe 'ativo' ao botão clicado
      botao.classList.add('ativo');

      // Carregar a página correspondente
      const pagina = botao.getAttribute('data-pagina');
      carregarPagina(pagina);
    });
  });

  function carregarPagina(pagina) {
    fetch(`${pagina}.php`)
      .then(response => response.text())
      .then(html => {
        conteudoPrincipal.innerHTML = html;
      })
      .catch(err => {
        console.error('Erro ao carregar a página:', err);
        conteudoPrincipal.innerHTML = '<p>Erro ao carregar a página.</p>';
      });
  }

  // Carregar a página inicial (dashboard) ao carregar o painel
  carregarPagina('dashboard');
</script>