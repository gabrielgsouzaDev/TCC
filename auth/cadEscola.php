<?php
include("../banco/conexao.php");

// LOGIN ESCOLA
if(isset($_POST['email']) && isset($_POST['senha'])){
    $sql = "SELECT senha_hash FROM tb_escola WHERE email_contato = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':email' => $_POST['email']]);
    $hash = $stmt->fetchColumn();

    if(!$hash){
        echo "<script>alert('E-mail Não Encontrado, Tente Novamente!');</script>";
    }elseif(!password_verify($_POST['senha'], $hash)){
        echo "<script>alert('Senha Incorreta, Tente Novamente!');</script>";
    }else{
        // Login bem-sucedido
        session_start();
        $_SESSION['email_escola'] = $_POST['email'];
        echo "<script>window.location.href='../escola/vitrineProdutos.html';</script>";
    }
}

// CADASTRO ESCOLA
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    // valida campos obrigatórios
    $camposObrigatorios = ['EscolaNome','EscolaCNPJ','ResponsavelNome','CantEmail','CantSenha','cep','logradouro','numero','bairro','cidade','estado'];
    foreach($camposObrigatorios as $campo){
        if(empty($_POST[$campo])){
            die("O campo $campo é obrigatório.");
        }
    }

    // Hash da senha
    $senhaHash = password_hash($_POST['CantSenha'], PASSWORD_DEFAULT);

    // Complemento pode ser nulo
    $complemento = $_POST['complemento'] ?? null;

    try {
        $pdo->beginTransaction();

        // Inserir endereço
        $sql = "INSERT INTO tb_endereco (cep, logradouro, numero, complemento, bairro, cidade, estado)
                VALUES (:cep, :logradouro, :numero, :complemento, :bairro, :cidade, :estado)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':cep' => $_POST['cep'],
            ':logradouro' => $_POST['logradouro'],
            ':numero' => $_POST['numero'],
            ':complemento' => $complemento,
            ':bairro' => $_POST['bairro'],
            ':cidade' => $_POST['cidade'],
            ':estado' => $_POST['estado']
        ]);
        $idEndereco = $pdo->lastInsertId();

        // Inserir escola
        $sql = "INSERT INTO tb_escola (nome, cnpj, email_contato, senha_hash, nm_gerente, telefone_contato, id_endereco)
                VALUES (:nome, :cnpj, :email, :senha, :gerente, :telefone, :endereco)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nome' => $_POST['EscolaNome'],
            ':cnpj' => $_POST['EscolaCNPJ'],
            ':email' => $_POST['CantEmail'],
            ':senha' => $senhaHash,
            ':gerente' => $_POST['ResponsavelNome'],
            ':telefone' => $_POST['CantEmail'], // se não tiver telefone, pode usar email ou null
            ':endereco' => $idEndereco
        ]);

        $pdo->commit();

        echo "<script>alert('Cadastro realizado com sucesso!');</script>";
        echo "<script>window.location.href='../index.php';</script>";

    } catch(PDOException $e){
        $pdo->rollBack();
        die("Erro ao cadastrar escola: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="auth.css" />
  
<style>
  /* Ajustes para multi-step form */
  fieldset {
    border: 1px solid var(--cor-borda);
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    background-color: var(--cor-branco);
  }

  legend {
    font-weight: bold;
    color: var(--cor-titulo);
    margin-bottom: 15px;
  }

  .form-group {
    margin-bottom: 15px;
  }

  .form-group label {
    display: block;
    margin-bottom: 5px;
    color: var(--cor-texto);
  }

  .form-group input {
    width: 100%;
    padding: 8px 10px;
    border: 1px solid var(--cor-borda);
    border-radius: 5px;
    font-size: 1rem;
  }

  .form-navigation {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 10px;
  }

  .form-navigation .botao {
    padding: 10px 20px;
    font-size: 1rem;
    cursor: pointer;
  }
</style>

  <title>Escola - Autenticação</title>
</head>

<body>
  <img src="../img/fundoAuth.svg" alt="Fundo do site" class="background-svg">

  <div class="container">
    <div class="link-back">
      <a href="../index.php">← Voltar para Início</a>
    </div>

    <div class="usuarios">
      <div class="usuario active" id="escola">LOGIN</div>
      <div class="usuario" id="cantineiro">CADASTRO</div>
    </div>

    <h2 id="tituloLogin">Acesse o painel da Escola</h2>
    <p class="sub-text" id="sub-text">Este acesso é exclusivo para escolas previamente cadastradas na plataforma.
    </p>

    <!-- LOGIN -->
    <form id="formLogin" action="" method="POST">
      <div class="form-group">
        <input type="email" name="email" placeholder="Digite seu e-mail" required>
      </div>
      <div class="form-group senha">
        <input type="password" name="senha" placeholder="Digite sua senha" id="senhaLogin" required>
        <!--Olhinho de ver senha-->
        <span class="ver-senha" onclick="toggleSenha('senhaLogin')">👁</span>
      </div>
      <button class="botao" type="submit">
        <span class="texto">ENTRAR</span>
      </button>
      
    <div class="link">
      <a href="lembrarSenha.php">Esqueci minha senha</a>
    </div>
    </form>

    <!-- CADASTRO -->
<form id="formCadastro" action="" method="POST" style="display:none;">

  <!-- Step 1: Dados da Escola -->
  <fieldset class="step step-1">
    <legend>Dados da Escola</legend>

    <div class="form-group">
      <label for="escolaNome">Nome da Escola</label>
      <input type="text" id="escolaNome" name="EscolaNome" placeholder="Digite o nome da escola" maxlength="100" required>
    </div>

    <div class="form-group">
      <label for="escolaCNPJ">CNPJ</label>
      <input type="text" id="escolaCNPJ" name="EscolaCNPJ" placeholder="Digite o CNPJ da escola" maxlength="18" required pattern="\d{2}\.\d{3}\.\d{3}/\d{4}-\d{2}">
    </div>

    <div class="form-group">
      <label for="responsavelNome">Nome do Responsável</label>
      <input type="text" id="responsavelNome" name="ResponsavelNome" placeholder="Digite o nome do responsável" maxlength="70" required>
    </div>

    <div class="form-group">
      <label for="responsavelEmail">Email Institucional</label>
      <input type="email" id="responsavelEmail" name="CantEmail" placeholder="Digite o Email Institucional" maxlength="50" required>
    </div>

    <div class="form-group senha">
      <label for="senhaCantineiro">Senha</label>
      <input type="password" id="senhaCantineiro" name="CantSenha" placeholder="Digite sua senha" required>
      <span class="ver-senha" onclick="toggleSenha('senhaCantineiro')">👁</span>
    </div>

    <div class="form-navigation">
      <button type="button" class="botao proximo">Próximo →</button>
    </div>
  </fieldset>

  <!-- Step 2: Dados do Endereço -->
  <fieldset class="step step-2" style="display:none;">
    <legend>Endereço</legend>
    
    <div class="form-group">
      <label for="cep">CEP</label>
      <input type="text" id="cep" name="cep" maxlength="9" placeholder="Digite o CEP" required>
    </div>
    <div class="form-group">
      <label for="numero">Número</label>
      <input type="text" id="numero" name="numero" placeholder="Número da escola" required>
    </div>
    <div class="form-group">
      <label for="complemento">Complemento</label>
      <input type="text" id="complemento" name="complemento" placeholder="Complemento">
    </div>
    <div class="form-group">
      <label for="logradouro">Logradouro</label>
      <input type="text" id="logradouro" name="logradouro" placeholder="Rua/Avenida" required>
    </div>
    <div class="form-group">
      <label for="bairro">Bairro</label>
      <input type="text" id="bairro" name="bairro" placeholder="Bairro" required>
    </div>
    <div class="form-group">
      <label for="cidade">Cidade</label>
      <input type="text" id="cidade" name="cidade" placeholder="Cidade" required>
    </div>
    <div class="form-group">
      <label for="estado">Estado</label>
      <input type="text" id="estado" name="estado" placeholder="Estado" required>
    </div>

    <div class="form-navigation">
      <button type="button" class="botao voltar">← Voltar</button>
      <button type="button" class="botao proximo">Próximo →</button>
    </div>
  </fieldset>

  <!-- Step 3: Revisão e Envio -->
  <fieldset class="step step-3" style="display:none;">
    <legend>Revisão e Envio</legend>

    <p>Confira os dados informados antes de enviar o cadastro.</p>
    <ul id="resumoCadastro" style="list-style: none; padding-left: 0; background: var(--cor-branco); border: 1px solid var(--cor-borda); border-radius: 5px; padding: 15px; margin-bottom: 15px;"></ul>

    <div class="form-navigation">
      <button type="button" class="botao voltar">← Voltar</button>
      <button class="botao" type="submit">
        <span class="texto">CADASTRAR</span>
      </button>
    </div>
  </fieldset>
</form>
<script>
 function toggleSenha(id) {
  const input = document.getElementById(id);
  input.type = input.type === "password" ? "text" : "password";
  }

  const escola = document.getElementById("escola");
  const cantineiro = document.getElementById("cantineiro");
  const titulo = document.getElementById("tituloLogin");
  const aviso = document.getElementById("sub-text");
  const formLogin = document.getElementById("formLogin");
  const formCadastro = document.getElementById("formCadastro"); // já existe

// event listeners das abas
escola.addEventListener("click", () => {
  escola.classList.add("active");
  cantineiro.classList.remove("active");
  titulo.textContent = "Acesse sua Escola";
  aviso.textContent = "Este acesso é exclusivo para escolas previamente cadastradas na plataforma.";

  formLogin.style.display = "block";
  formCadastro.style.display = "none";
});

cantineiro.addEventListener("click", () => {
  cantineiro.classList.add("active");
  escola.classList.remove("active");
  titulo.textContent = "Cadastre sua Escola";
  aviso.textContent = "Preencha o formulário multi-etapas para ativar sua conta com um pagamento único.\nO plano será ajustado automaticamente conforme o número de alunos ativados.";

  formLogin.style.display = "none";
  formCadastro.style.display = "block";
});

// === Controle multi-step ===
const form = formCadastro; // garante que a referência exista
const steps = form.querySelectorAll('.step');
let currentStep = 0;

function showStep(index) {
  steps.forEach((step, i) => {
    step.style.display = i === index ? 'block' : 'none';
  });
}

function updateResumo() {
  const resumo = document.getElementById('resumoCadastro');
  resumo.innerHTML = '';

  // Corrigido: pegar endereço direto dos campos existentes
  const endereco = `
    ${form.logradouro.value}, ${form.numero.value}${form.complemento.value ? ' - ' + form.complemento.value : ''}, 
    ${form.bairro.value}, ${form.cidade.value} - ${form.estado.value}, CEP: ${form.cep.value}
  `;

  const fields = [
    {label: 'Nome da Escola', value: form.EscolaNome.value},
    {label: 'CNPJ', value: form.EscolaCNPJ.value},
    {label: 'Endereço', value: endereco},
    {label: 'Nome do Responsável', value: form.ResponsavelNome.value},
    {label: 'Email Institucional', value: form.CantEmail.value},
  ];

  fields.forEach(field => {
    const li = document.createElement('li');
    li.textContent = `${field.label}: ${field.value}`;
    resumo.appendChild(li);
  });
}

// Botões Próximo
form.querySelectorAll('.proximo').forEach(btn => {
  btn.addEventListener('click', () => {
    const camposAtuais = steps[currentStep].querySelectorAll('input, select, textarea');
    let valido = true;
    camposAtuais.forEach(input => {
      if (!input.checkValidity()) valido = false;
    });

    if (valido) {
      currentStep++;
      if (currentStep >= steps.length) currentStep = steps.length - 1;
      if(currentStep === steps.length -1) updateResumo();
      showStep(currentStep);
    } else {
      camposAtuais.forEach(input => input.reportValidity());
    }
  });
});

// Botões Voltar
form.querySelectorAll('.voltar').forEach(btn => {
  btn.addEventListener('click', () => {
    currentStep--;
    if (currentStep < 0) currentStep = 0;
    showStep(currentStep);
  });
});

// Inicializa mostrando o primeiro passo
showStep(currentStep);

document.getElementById('cep').addEventListener('blur', function() {
  const cep = this.value.replace(/\D/g, '');
  if(cep.length === 8){
    fetch(`https://viacep.com.br/ws/${cep}/json/`)
      .then(res => res.json())
      .then(data => {
        if(!data.erro){
          document.getElementById('logradouro').value = data.logradouro;
          document.getElementById('bairro').value = data.bairro;
          document.getElementById('cidade').value = data.localidade;
          document.getElementById('estado').value = data.uf;
        } else {
          alert("CEP não encontrado!");
        }
      });
  }
});
  </script>

</body>

</html>