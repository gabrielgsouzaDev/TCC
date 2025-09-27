<?php
// setup.php - cria banco, tabelas e dados iniciais

$host = 'db'; // nome do serviço MySQL no Docker
$username = 'root';
$password = 'root';

try {
    // Conectar sem banco (para criar o banco primeiro)
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Criar banco se não existir
    $pdo->exec("CREATE DATABASE IF NOT EXISTS CTNAPP CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
    $pdo->exec("USE CTNAPP");

    // Criar tabelas
    $tables = [

        // tb_admin
        "CREATE TABLE IF NOT EXISTS tb_admin (
            id_admin INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            senha_hash VARCHAR(255) NOT NULL,
            dt_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",

        // tb_endereco
        "CREATE TABLE IF NOT EXISTS tb_endereco (
            id_endereco INT AUTO_INCREMENT PRIMARY KEY,
            logradouro VARCHAR(255) NOT NULL,
            numero VARCHAR(20),
            bairro VARCHAR(100),
            cidade VARCHAR(100) NOT NULL,
            estado VARCHAR(50) NOT NULL,
            cep VARCHAR(10)
        )",

        // tb_plano
        "CREATE TABLE IF NOT EXISTS tb_plano (
            id_plano INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(50) NOT NULL,
            preco_mensal DECIMAL(10,2) NOT NULL
        )",

        // tb_escola
        "CREATE TABLE IF NOT EXISTS tb_escola (
            id_escola INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(150) NOT NULL,
            cnpj VARCHAR(20) UNIQUE,
            id_endereco INT,
            id_plano INT,
            status ENUM('ativa','inativa') DEFAULT 'ativa',
            qtd_alunos INT DEFAULT 0,
            dt_ultimo_pagamento DATE,
            email_contato VARCHAR(100),
            telefone_contato VARCHAR(20),
            dt_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (id_endereco) REFERENCES tb_endereco(id_endereco),
            FOREIGN KEY (id_plano) REFERENCES tb_plano(id_plano)
        )",

        // tb_cantina
        "CREATE TABLE IF NOT EXISTS tb_cantina (
            id_cantina INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(50) NOT NULL,
            id_escola INT,
            FOREIGN KEY (id_escola) REFERENCES tb_escola(id_escola)
        )",

        // tb_cantineiro
        "CREATE TABLE IF NOT EXISTS tb_cantineiro (
            id_cantineiro INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            senha_hash VARCHAR(255) NOT NULL,
            id_cantina INT NOT NULL,
            FOREIGN KEY (id_cantina) REFERENCES tb_cantina(id_cantina)
        )",

        // tb_aluno
        "CREATE TABLE IF NOT EXISTS tb_aluno (
            id_aluno INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(100) NOT NULL,
            email VARCHAR(100) UNIQUE,
            senha_hash VARCHAR(255) NOT NULL,
            id_escola INT NOT NULL,
            dt_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (id_escola) REFERENCES tb_escola(id_escola)
        )",

        // tb_responsavel
        "CREATE TABLE IF NOT EXISTS tb_responsavel (
            id_responsavel INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(100) NOT NULL,
            email VARCHAR(100) UNIQUE,
            senha_hash VARCHAR(255) NOT NULL,
            dt_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",

        // tb_aluno_responsavel
        "CREATE TABLE IF NOT EXISTS tb_aluno_responsavel (
            id_aluno INT NOT NULL,
            id_responsavel INT NOT NULL,
            PRIMARY KEY (id_aluno, id_responsavel),
            FOREIGN KEY (id_aluno) REFERENCES tb_aluno(id_aluno),
            FOREIGN KEY (id_responsavel) REFERENCES tb_responsavel(id_responsavel)
        )",

        // tb_produto
        "CREATE TABLE IF NOT EXISTS tb_produto (
            id_produto INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(50) NOT NULL,
            preco DECIMAL(10,2) NOT NULL,
            id_cantina INT NOT NULL,
            FOREIGN KEY (id_cantina) REFERENCES tb_cantina(id_cantina)
        )",

        // tb_pedido
        "CREATE TABLE IF NOT EXISTS tb_pedido (
            id_pedido INT AUTO_INCREMENT PRIMARY KEY,
            id_aluno INT NOT NULL,
            id_responsavel INT DEFAULT NULL,
            id_cantina INT NOT NULL,
            dt_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            status ENUM('pendente','confirmado','entregue','cancelado') DEFAULT 'pendente',
            FOREIGN KEY (id_aluno) REFERENCES tb_aluno(id_aluno),
            FOREIGN KEY (id_responsavel) REFERENCES tb_responsavel(id_responsavel),
            FOREIGN KEY (id_cantina) REFERENCES tb_cantina(id_cantina)
        )",

        // tb_item_pedido
        "CREATE TABLE IF NOT EXISTS tb_item_pedido (
            id_item INT AUTO_INCREMENT PRIMARY KEY,
            id_pedido INT NOT NULL,
            id_produto INT NOT NULL,
            quantidade INT NOT NULL DEFAULT 1,
            preco_unitario DECIMAL(10,2) NOT NULL,
            FOREIGN KEY (id_pedido) REFERENCES tb_pedido(id_pedido),
            FOREIGN KEY (id_produto) REFERENCES tb_produto(id_produto)
        )"
    ];

    foreach ($tables as $sql) {
        $pdo->exec($sql);
    }

    echo "Tabelas criadas com sucesso!\n";

    // Inserções iniciais
    $pdo->exec("INSERT INTO tb_plano (nome, preco_mensal) VALUES ('Básico', 150.00)");
    $pdo->exec("INSERT INTO tb_endereco (logradouro, numero, bairro, cidade, estado, cep)
                VALUES ('Rua A', '123', 'Centro', 'São Vicente', 'SP', '11330-000')");
    $pdo->exec("INSERT INTO tb_escola (nome, cnpj, id_endereco, email_contato, telefone_contato, qtd_alunos, id_plano)
                VALUES ('Escola Teste', '12.345.678/0001-99', LAST_INSERT_ID(), 'teste@escola.com', '13123456789', 120, 1)");
    $pdo->exec("INSERT INTO tb_cantina (nome, id_escola) VALUES ('Cantina Central', 1)");
    $pdo->exec("INSERT INTO tb_cantineiro (nome, email, senha_hash, id_cantina)
                VALUES ('João Cantineiro', 'joao@cantina.com', 'senha123', 1)");
    $pdo->exec("INSERT INTO tb_aluno (nome, email, senha_hash, id_escola)
                VALUES ('Pedro Aluno', 'pedro@aluno.com', 'senha123', 1)");
    $pdo->exec("INSERT INTO tb_responsavel (nome, email, senha_hash)
                VALUES ('Maria Responsavel', 'maria@responsavel.com', 'senha123')");
    $pdo->exec("INSERT INTO tb_aluno_responsavel (id_aluno, id_responsavel) VALUES (1, 1)");
    $pdo->exec("INSERT INTO tb_produto (nome, preco, id_cantina) VALUES ('Suco Natural', 5.50, 1)");
    $pdo->exec("INSERT INTO tb_pedido (id_aluno, id_responsavel, id_cantina, status)
                VALUES (1, 1, 1, 'pendente')");
    $pdo->exec("INSERT INTO tb_item_pedido (id_pedido, id_produto, quantidade, preco_unitario)
                VALUES (1, 1, 2, 5.50)");
    $pdo->exec("INSERT INTO tb_admin (nome, email, senha_hash)
                VALUES ('Admin', 'admin@gmail.com', 'senha123')");

    echo "Dados iniciais inseridos com sucesso!\n";

} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}
