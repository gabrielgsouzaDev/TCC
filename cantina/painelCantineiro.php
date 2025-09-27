<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Painel Cantineiro</title>
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
* { margin:0; padding:0; box-sizing:border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
body { background-color: var(--cor-fundo); color: var(--cor-texto); display: flex; min-height:100vh; }
.menu-lateral { width:220px; background-color: var(--cor-branco); box-shadow:2px 0 10px rgba(0,0,0,0.1); padding:20px 0; display:flex; flex-direction:column; }
.menu-lateral h2 { color: var(--cor-titulo); text-align:center; margin-bottom:30px; font-size:1.4rem; }
.menu-lateral button { background:none; border:none; width:100%; padding:14px 20px; text-align:left; font-size:1rem; color:var(--cor-texto); cursor:pointer; transition: background 0.2s; }
.menu-lateral button:hover { background-color: var(--cor-fundo); }
.menu-lateral button.ativo { background-color: var(--cor-principal); color: var(--cor-branco); }
.conteudo-principal { flex:1; padding:30px; }
.pagina h1 { font-size:2rem; margin-bottom:20px; color: var(--cor-titulo); }
@media (max-width:768px) {
  .menu-lateral { position:fixed; left:-220px; top:0; height:100vh; z-index:100; transition:left 0.3s; }
  .menu-lateral.aberto { left:0; }
  .menu-toggle { display:block; position:fixed; top:15px; left:15px; background:var(--cor-principal); color:white; border:none; width:40px; height:40px; border-radius:5px; font-size:1.2rem; z-index:101; }
}
.menu-toggle { display:none; }
.kanban { display:flex; gap:15px; overflow-x:auto; }
.kanban-coluna { background:#fff; border-radius:8px; padding:10px; min-width:200px; flex:0 0 200px; display:flex; flex-direction:column; max-height:70vh; overflow-y:auto; }
.kanban-coluna h3 { text-align:center; margin-bottom:10px; color: var(--cor-principal); pointer-events:none; }
.kanban-card { background:#f5f5f5; margin-bottom:10px; padding:10px; border-radius:5px; cursor:grab; box-shadow:0 2px 4px rgba(0,0,0,0.1); }
.kanban-card.urgente { border-left:4px solid var(--cor-principal); }
.estoque-item { display:flex; justify-content:space-between; padding:8px; background:#fff; margin-bottom:5px; border-radius:5px; border:1px solid #ddd; }
.estoque-item.alerta { background: var(--cor-secundaria); color:#fff; }
</style>
</head>
<body>
<button class="menu-toggle" id="menuToggle">☰</button>
<nav class="menu-lateral" id="menuLateral">
  <h2>Painel Cantineiro</h2>
  <button class="ativo" data-pagina="pedidos">Pedidos</button>
  <button data-pagina="estoque">Estoque</button>
  <button data-pagina="config">Configurações</button>
</nav>
<div class="conteudo-principal conteudo"></div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
const menuToggle = document.getElementById('menuToggle');
const menuLateral = document.getElementById('menuLateral');
const conteudoPrincipal = document.querySelector('.conteudo-principal');

menuToggle.addEventListener('click', ()=> menuLateral.classList.toggle('aberto'));

const botoesMenu = menuLateral.querySelectorAll('button');
botoesMenu.forEach(botao => {
  botao.addEventListener('click', ()=>{
    botoesMenu.forEach(b=>b.classList.remove('ativo'));
    botao.classList.add('ativo');
    carregarPagina(botao.dataset.pagina);
  });
});

function carregarPagina(pagina){
  if(pagina==='pedidos') renderPedidos();
  else if(pagina==='estoque') renderEstoque();
  else if(pagina==='config') renderConfig();
}

function renderPedidos(){
  const pedidos = [
    {id:1, nome:"Pedro Aluno", status:"pendente", produto:"Suco Natural x2"},
    {id:2, nome:"Maria Responsavel", status:"confirmado", produto:"Sanduíche"},
    {id:3, nome:"João Aluno", status:"entregue", produto:"Água"},
    {id:4, nome:"Ana Aluno", status:"cancelado", produto:"Suco Natural"}
    
  ];
  const statuses=["pendente","confirmado","entregue","cancelado"];
  let html = `<div class="kanban">`;
  statuses.forEach(status=>{
    html += `<div class="kanban-coluna" data-status="${status}">
               <h3>${status.charAt(0).toUpperCase()+status.slice(1)}</h3>`;
    pedidos.filter(p=>p.status===status).forEach(p=>{
      html += `<div class="kanban-card" data-id="${p.id}">
                 <strong>${p.nome}</strong><br>${p.produto}
               </div>`;
    });
    html += `</div>`;
  });
  html += `</div>`;
  conteudoPrincipal.innerHTML = html;

  document.querySelectorAll('.kanban-coluna').forEach(col=>{
    new Sortable(col,{
      group:'kanban',
      animation:150,
      filter:'h3', 
      onAdd: function(evt){
        const cardId = evt.item.dataset.id;
        const novoStatus = evt.to.dataset.status;
        const pedido = pedidos.find(p=>p.id==cardId);
        if(pedido) pedido.status = novoStatus;
        console.log(`Pedido ${cardId} agora está ${novoStatus}`);
      }
    });
  });
}

function renderEstoque(){
  const estoque = [
    {nome:"Suco Natural",quantidade:5,minimo:5},
    {nome:"Sanduíche",quantidade:2,minimo:3},
    {nome:"Água",quantidade:10,minimo:2}
  ];
  let html = `<h1>Estoque da Cantina</h1>`;
  estoque.forEach(item=>{
    const classeAlerta = item.quantidade <= item.minimo ? 'estoque-item alerta' : 'estoque-item';
    html += `<div class="${classeAlerta}">
               <span>${item.nome}</span><span>${item.quantidade}</span>
             </div>`;
  });
  conteudoPrincipal.innerHTML = html;
}

function renderConfig(){
  conteudoPrincipal.innerHTML = `<h1>Configurações</h1>
  <p>Alterar dados pessoais, senha ou preferências de notificações.</p>`;
}

carregarPagina('pedidos');
</script>
</body>
</html>
