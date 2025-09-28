<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard Admin</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>

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

/* Tabela geral */
#tabelaEscolas {
  width: 100%;
  border-collapse: collapse;
  background: var(--cor-branco, #ffffff);
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  border-radius: 8px;
  overflow: hidden;
}

/* Cabeçalho */
#tabelaEscolas thead {
  background-color: var(--cor-titulo, #7a1523);
  color: #fff;
}

#tabelaEscolas th {
  text-align: left;
  padding: 12px 15px;
  font-weight: 600;
  font-size: 0.95rem;
}

/* Linhas do corpo */
#tabelaEscolas tbody tr {
  border-bottom: 1px solid #ddd;
  transition: background 0.2s;
}

#tabelaEscolas tbody tr:hover {
  background-color: #f0f0f0;
}

/* Células do corpo */
#tabelaEscolas td {
  padding: 12px 15px;
  font-size: 0.9rem;
}

/* Status visual */
#tabelaEscolas td.status-ativa {
  color: #2e7d32; /* verde */
  font-weight: bold;
}

#tabelaEscolas td.status-inativa {
  color: #c62828; /* vermelho */
  font-weight: bold;
}


.percent {
  margin-left: 0.5rem;
  color: #02972f;
  font-weight: 600;
  display: flex;
}


/* Responsividade para telas pequenas */
@media (max-width: 800px) {
  #tabelaEscolas thead {
    display: none;
  }

  #tabelaEscolas, #tabelaEscolas tbody, #tabelaEscolas tr, #tabelaEscolas td {
    display: block;
    width: 100%;
  }

  #tabelaEscolas tr {
    margin-bottom: 15px;
    border-bottom: 2px solid var(--cor-borda, #d8b0b0);
  }

  #tabelaEscolas td {
    text-align: right;
    padding-left: 50%;
    position: relative;
  }

  #tabelaEscolas td::before {
    content: attr(data-label);
    position: absolute;
    left: 15px;
    width: 45%;
    text-align: left;
    font-weight: 600;
  }
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
</head>
<body>

<button class="menu-toggle" id="menuToggle">☰</button>

<nav class="menu-lateral" id="menuLateral">
  <h2>Painel Admin</h2>
  <button class="ativo" data-pagina="dashboard">Dashboard</button>
  <button data-pagina="escolas">Escolas</button>
  <!--<button data-pagina="financeiro">Histórico Financeiro</button>
  <button data-pagina="usuarios">Usuários</button>-->
  <button data-pagina="configuracoes">Configurações</button>
</nav>

<main class="conteudo">
  <!-- Dashboard -->
  <section id="dashboard" class="pagina">
    <h1 class="text-2xl font-bold mb-6">Dashboard</h1>


    <div id="resumos" style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 30px;">
      <div style="flex: 1; min-width: 200px; background: var(--cor-branco); border: 1px solid var(--cor-borda); border-radius: 8px; padding: 20px;">
        <h3 style="color: var(--cor-titulo); margin-bottom: 10px;">Escolas Ativas</h3>
        <p id="resumoEscolas" style="font-size: 1.5rem; font-weight: bold;">Carregando...</p>
        <span class="text-xs font-medium text-emerald-500">+12.3%</span>
      </div>

      <div style="flex: 1; min-width: 200px; background: var(--cor-branco); border: 1px solid var(--cor-borda); border-radius: 8px; padding: 20px;">
        <h3 style="color: var(--cor-titulo); margin-bottom: 10px;">Alunos Ativos</h3>
        <p id="resumoAlunos" style="font-size: 1.5rem; font-weight: bold;">Carregando...</p>
                <span class="text-xs font-medium text-emerald-500">+12.3%</span>
      </div>

      <div style="flex: 1; min-width: 200px; background: var(--cor-branco); border: 1px solid var(--cor-borda); border-radius: 8px; padding: 20px;">
        <h3 style="color: var(--cor-titulo); margin-bottom: 10px;">Responsáveis Ativos</h3>
        <p id="resumoResponsaveis" style="font-size: 1.5rem; font-weight: bold;">Carregando...</p>
        <span class="text-xs font-medium text-emerald-500">+12.3%</span>
      </div>

      <div style="flex: 1; min-width: 200px; background: var(--cor-branco); border: 1px solid var(--cor-borda); border-radius: 8px; padding: 20px;">
        <h3 style="color: var(--cor-titulo); margin-bottom: 10px;">Cantineiros Ativos</h3>
        <p id="resumoCantineiros" style="font-size: 1.5rem; font-weight: bold;">Carregando...</p>
        <span class="text-xs font-medium text-emerald-500">+12.3%</span>
      </div>

      <div style="flex: 1; min-width: 200px; background: var(--cor-branco); border: 1px solid var(--cor-borda); border-radius: 8px; padding: 20px;">
        <h3 style="color: var(--cor-titulo); margin-bottom: 10px;">Pedidos no Mês</h3>
        <p id="resumoPedidosMes" style="font-size: 1.5rem; font-weight: bold;">Carregando...</p>
        <span class="text-xs font-medium text-emerald-500">+12.3%</span>
      </div>
    </div>

    <div style="background: var(--cor-branco); padding: 20px; border-radius: 8px; border: 1px solid var(--cor-borda); max-width: 100%;">
      <h3 style="color: var(--cor-titulo); margin-bottom: 20px;">Faturamento por Mês (R$)</h3>
      <canvas id="graficoFaturamento" height="100"></canvas>
    </div>
  </section>

  <!-- Escolas -->
<!-- Escolas -->
<section id="escolas" class="pagina" style="display:none;">
  <h1 class="text-2xl font-bold mb-6">Escolas</h1>


  <!-- Filtros -->
<div class="flex gap-4 mb-3">

  <!-- Filtro Plano -->
  <div class="relative group rounded-lg w-40 bg-[var(--cor-branco)] overflow-hidden">
    <svg
      xmlns="http://www.w3.org/2000/svg"
      viewBox="0 0 100 100"
      class="w-6 h-6 absolute right-2 -rotate-45 stroke-[var(--cor-principal)] top-2 group-hover:rotate-0 duration-300"
    >
      <path
        stroke-width="4"
        stroke-linejoin="round"
        stroke-linecap="round"
        fill="none"
        d="M60.7,53.6,50,64.3m0,0L39.3,53.6M50,64.3V35.7m0,46.4A32.1,32.1,0,1,1,82.1,50,32.1,32.1,0,0,1,50,82.1Z"
      ></path>
    </svg>
    <select id="filtroPlano"
      class="appearance-none w-full bg-[var(--cor-branco)] border border-[var(--cor-principal)] text-[var(--cor-principal)] rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[var(--cor-principal-hover)]"
    >
      <option value="" disabled selected>Filtro: Planos</option>
        <option value="">Todos</option>
      <option value="Básico">Básico</option>
      <option value="Médio">Médio</option>
      <option value="Alto">Alto</option>
    </select>
  </div>

  <!-- Filtro Status -->
  <div class="relative group rounded-lg w-40 bg-[var(--cor-branco)] overflow-hidden">
    <svg
      xmlns="http://www.w3.org/2000/svg"
      viewBox="0 0 100 100"
      class="w-6 h-6 absolute right-2 -rotate-45 stroke-[var(--cor-principal)] top-2 group-hover:rotate-0 duration-300"
    >
      <path
        stroke-width="4"
        stroke-linejoin="round"
        stroke-linecap="round"
        fill="none"
        d="M60.7,53.6,50,64.3m0,0L39.3,53.6M50,64.3V35.7m0,46.4A32.1,32.1,0,1,1,82.1,50,32.1,32.1,0,0,1,50,82.1Z"
      ></path>
    </svg>
    <select id="filtroStatus"
      class="appearance-none relative bg-transparent ring-0 outline-none border border-[var(--cor-principal)] text-[var(--cor-principal)] text-sm font-semibold rounded-lg focus:ring-[var(--cor-principal-hover)] focus:border-[var(--cor-principal-hover)] block w-full p-2 pr-8"
    >
  <option value="" disabled selected>Filtro: Status</option>
  <option value="">Todos</option>
  <option value="ativa">Ativa</option>
  <option value="inativa">Inativa</option>
    </select>
  </div>

</div>


  <table id="tabelaEscolas" style="width:100%; border-collapse: collapse;">
    <thead>
      <tr>
        <th>Nome</th>
        <th>Email</th>
        <th>Endereço</th>
        <th>Plano</th>
        <th>Status</th>
        <th>Último Pagamento</th>
        <th>Alunos Ativos</th>
      </tr>
    </thead>
    <tbody>
      <tr><td colspan="7" style="text-align:center;">Carregando...</td></tr>
    </tbody>
  </table>
</section>


  <!-- Histórico Financeiro -->
  <section id="financeiro" class="pagina" style="display:none;">
    <h1>Histórico Financeiro</h1>
    <div id="tabelaFinanceiro">Tabela de pagamentos e transações...</div>
  </section>

  <!-- Usuários -->
  <section id="usuarios" class="pagina" style="display:none;">
    <h1>Usuários</h1>
    <div id="tabelaUsuarios">Tabela de usuários por escola...</div>
  </section>

<!-- Configurações -->
<section id="configuracoes" class="pagina" style="display:none;">
  <h1 class="text-2xl font-bold mb-6">Configurações</h1>

  <div class="grid md:grid-cols-2 gap-6">
    <div class="bg-white shadow-md rounded-lg p-6 border border-gray-200">
      <h2 class="text-lg font-semibold mb-4 text-gray-800">Perfil</h2>
      
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-600">E-mail</label>
        <input type="email" value="usuario@email.com" 
               class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed" disabled>
        <p class="text-xs text-gray-500 mt-1">O e-mail não pode ser alterado.</p>
      </div>
      
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-600">Nome</label>
        <input type="text" value="Gabriel Souza" 
       class="w-full mt-1 px-3 py-2 border border-[var(--cor-principal)] rounded-lg focus:ring-2 focus:ring-[var(--cor-principal-hover)] focus:outline-none">
      </div>

      <button class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-6 rounded-lg shadow-sm transition">
        Salvar Alterações
      </button>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6 border border-gray-200">
      <h2 class="text-lg font-semibold mb-4 text-gray-800">Segurança</h2>
      
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-600">Senha Atual</label>
        <input type="password"  class="w-full mt-1 px-3 py-2 border border-[var(--cor-principal)] rounded-lg focus:ring-2 focus:ring-[var(--cor-principal-hover)] focus:outline-none">

      </div>
      
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-600">Nova Senha</label>
        <input type="password"  class="w-full mt-1 px-3 py-2 border border-[var(--cor-principal)] rounded-lg focus:ring-2 focus:ring-[var(--cor-principal-hover)] focus:outline-none">
      </div>
      
      <div class="mb-6">
        <label class="block text-sm font-medium text-gray-600">Confirmar Nova Senha</label>
        <input type="password"  class="w-full mt-1 px-3 py-2 border border-[var(--cor-principal)] rounded-lg focus:ring-2 focus:ring-[var(--cor-principal-hover)] focus:outline-none">
      </div>

      <button class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-6 rounded-lg shadow-sm transition">
        Alterar Senha
      </button>
    </div>
  </div>

  <div class="bg-white shadow-md rounded-lg p-6 border border-gray-200 mt-6">
    <h2 class="text-lg font-semibold mb-4 text-gray-800">Conta</h2>
    <p class="text-sm text-gray-600 mb-4">Gerencie sua sessão e acesso ao sistema.</p>
    <button id="btnSair" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-6 rounded-lg shadow-sm transition">
      Sair da Conta
    </button>
  </div>
</section>

</main>

<script>

document.addEventListener('DOMContentLoaded', () => {
  const botoes = document.querySelectorAll('.menu-lateral button');
  const titulo = document.getElementById('titulo');
  const menuLateral = document.getElementById('menuLateral');
  const menuToggle = document.getElementById('menuToggle');
  const paginas = document.querySelectorAll('.pagina');

  const nomes = {
    dashboard: 'Dashboard',
    escolas: 'Escolas',
    financeiro: 'Histórico Financeiro',
    usuarios: 'Usuários',
    configuracoes: 'Configurações'
  };

  botoes.forEach(botao => {
    botao.addEventListener('click', () => {
      botoes.forEach(b => b.classList.remove('ativo'));
      botao.classList.add('ativo');

      const pagina = botao.getAttribute('data-pagina');

      paginas.forEach(p => p.style.display = 'none');
      document.getElementById(pagina).style.display = 'block';

      if (titulo) titulo.textContent = nomes[pagina] || '';

      if (pagina === 'dashboard') carregarDashboard();
      if (pagina === 'escolas') carregarEscolas();

      if (menuLateral) menuLateral.classList.remove('aberto');
    });
  });

  if (menuToggle) {
    menuToggle.addEventListener('click', () => {
      menuLateral.classList.toggle('aberto');
    });
  }
  // CARREGAR DASHBOARD
  async function carregarDashboard() {
    try {

      const res = await fetch('dashboard.php');
      if (!res.ok) throw new Error('Erro na requisição: ' + res.status);
      const dados = await res.json();

      document.getElementById('resumoEscolas').textContent       = dados.escolasAtivas ?? 0;
      document.getElementById('resumoAlunos').textContent        = dados.usuariosResumo.aluno ?? 0;
      document.getElementById('resumoResponsaveis').textContent  = dados.usuariosResumo.responsavel ?? 0;
      document.getElementById('resumoCantineiros').textContent   = dados.usuariosResumo.cantineiro ?? 0;
      document.getElementById('resumoPedidosMes').textContent    = dados.pedidosMes ?? 0;

    if (window.graficoFaturamento instanceof Chart) {
  window.graficoFaturamento.destroy();
}

      const ctx = document.getElementById('graficoFaturamento').getContext('2d');
      window.graficoFaturamento = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
          datasets: [{
            label: 'Faturamento (R$)',
            data: dados.graficoFaturamento ?? Array(12).fill(0),
            backgroundColor: 'rgba(167, 29, 49, 0.7)',
            borderColor: 'rgba(167, 29, 49, 1)',
            borderWidth: 1,
            borderRadius: 5
          }]
        },
        options: {
          responsive: true,
          plugins: { legend: { display: false } },
          scales: { 
            y: { 
              beginAtZero: true,
              ticks: { callback: value => 'R$ ' + value.toLocaleString('pt-BR') }
            } 
          }
        }
      });
    } catch (e) {
      console.error('Erro ao carregar dashboard:', e);
      ['resumoEscolas','resumoAlunos','resumoResponsaveis','resumoCantineiros','resumoPedidosMes']
        .forEach(id => document.getElementById(id).textContent = 'Error');
    }
  }
function aplicarFiltros() {
  const plano = document.getElementById('filtroPlano').value.toLowerCase();
  const status = document.getElementById('filtroStatus').value.toLowerCase();

  const linhas = document.querySelectorAll('#tabelaEscolas tbody tr');
  linhas.forEach(tr => {
    const planoCell = tr.querySelector('td[data-label="Plano"]')?.textContent.toLowerCase() || '';
    const statusCell = tr.querySelector('td[data-label="Status"]')?.textContent.toLowerCase() || '';

    const mostraPlano = !plano || planoCell.includes(plano); // value="" = mostra tudo
    const mostraStatus = !status || statusCell.includes(status); // value="" = mostra tudo

    tr.style.display = (mostraPlano && mostraStatus) ? '' : 'none';
  });
}

// Ativar filtros quando mudar os selects
document.getElementById('filtroPlano').addEventListener('change', aplicarFiltros);
document.getElementById('filtroStatus').addEventListener('change', aplicarFiltros);

  // CARREGAR ESCOLAS
  async function carregarEscolas() {
    try {
      const res = await fetch('tbEscola.php');
      if (!res.ok) throw new Error('Erro na requisição: ' + res.status);

      const escolas = await res.json();
      const tbody = document.querySelector('#tabelaEscolas tbody');
      tbody.innerHTML = '';

      escolas.forEach(escola => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td data-label="Nome">${escola.nome}</td>
          <td data-label="Email">${escola.email_contato ?? '-'}</td>
          <td data-label="Endereço">${escola.endereco ?? '-'}</td>
          <td data-label="Plano">${escola.plano_pagamento ?? '-'}</td>
          <td data-label="Status" class="status-${escola.status}">${escola.status}</td>
          <td data-label="Último Pagamento">${escola.dt_ultimo_pagamento ?? '-'}</td>
          <td data-label="Alunos Ativos">${escola.qtd_alunos ?? 0}</td>
        `;
        tbody.appendChild(tr);
      });
    } catch (e) {
      console.error('Erro ao carregar escolas:', e);
      const tbody = document.querySelector('#tabelaEscolas tbody');
      tbody.innerHTML = `<tr><td colspan="7" style="text-align:center;">Erro ao carregar escolas</td></tr>`;
    }
  }

  document.getElementById('dashboard').style.display = 'block';
  
  carregarDashboard();

});

  document.getElementById('btnSair').addEventListener('click', () => {
  window.location.href = 'logout.php';
});
</script>

</body>
</html>
