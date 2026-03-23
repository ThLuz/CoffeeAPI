# Coffee API

API REST desenvolvida em **PHP Nativo** e **MySQL** para controle e monitoramento de consumo de café. O projeto permite o cadastro de usuários, autenticação via Token e geração de rankings baseados na quantidade de consumo por período.

---

## Tecnologias Utilizadas

* PHP (Sem frameworks)
* MySQL
* Programação Orientada a Objetos (POO)
* Arquitetura MVC (simplificada)
* Autenticação via Custom Token
* JSON para Input/Output

---

## Funcionalidades

* Cadastro de usuários com validação de duplicidade
* Autenticação de usuários (Login)
* Gestão de perfil (Listagem, Detalhes, Edição e Remoção)
* Incremento de contador de consumo de café
* Relatórios de ranking por período (últimos X dias)
* Proteção de rotas (Apenas o próprio usuário pode editar/remover seus dados)

---

## Estrutura do Projeto

```text
coffee-api/
├── config/         # Conexão com banco e scripts SQL
├── controllers/    # Processamento de requisições e regras de negócio
├── middleware/     # Validação de tokens e segurança
├── models/         # Camada de abstração de dados (Users, Drinks)
├── routes/         # Definição das rotas da API
├── services/       # Serviços complementares (Autenticação)
├── utils/          # Padronização de respostas JSON
├── index.php       # Ponto de entrada da aplicação
└── test.html       # Interface simples para testes de integração
```
---

# Instalação e Configuração

## 1. Banco de Dados

Importe o arquivo SQL para criar as tabelas `users` e `drinks`.

No terminal MySQL ou através do phpMyAdmin:

SOURCE config/mysql.sql;

## 2. Configurar Credenciais

Abra o arquivo:

config/database.php

E insira os dados do seu banco de dados local:

- Host
- Nome do banco (DB Name)
- Usuário
- Senha

## 3. Execução do Servidor

Na raiz do projeto, inicie o servidor embutido do PHP:

php -S localhost:8000

## 4. Testes

Você pode utilizar:

- O arquivo test.html no navegador
- Ferramentas como Postman ou Insomnia

---

## Endpoints da API

### Usuários e Autenticação

#### Cadastro de Usuário
`POST /users/`
- **Entrada (JSON):** `email`, `name`, `password`
- **Saída:** Objeto do usuário criado.

#### Login
`POST /login`
- **Entrada (JSON):** `email`, `password`
- **Saída:** `token`, `iduser`, `email`, `name`, `drinkCounter`

#### Listar Usuários
`GET /users/`
- **Header:** `Authorization: Bearer <token>`
- **Saída:** Array com todos os usuários.

#### Detalhes do Usuário
`GET /users/:iduser`
- **Header:** `Authorization: Bearer <token>`
- **Saída:** `iduser`, `name`, `email`, `drinkCounter`

#### Atualizar Usuário
`PUT /users/:iduser`
- **Header:** `Authorization: Bearer <token>`
- **Entrada (JSON):** `email`, `name`, `password`

#### Remover Usuário
`DELETE /users/:iduser`
- **Header:** `Authorization: Bearer <token>`

---

### Consumo de Café

#### Registrar Consumo
`POST /users/:iduser/drink`
- **Descrição:** Incrementa a quantidade de vezes que o usuário bebeu café.
- **Header:** `Authorization: Bearer <token>`
- **Entrada (JSON):** `drink` (int)
- **Saída:** `iduser`, `email`, `name`, `drinkCounter`

---

### Ranking e Relatórios

#### Ranking por Período
`GET /ranking/days/:days`
- **Exemplo:** `/ranking/days/7` (Ranking dos últimos 7 dias).
- **Saída:** Lista de usuários ordenada pelo consumo no período.

## Autenticação

Após realizar o login, utilize o token retornado no header de todas as requisições protegidas:

token: {seu_token}

---

## Rotas

| Operação           | Método  | Endpoint            | Entrada (JSON)           | Protegido |
|------------------|--------|---------------------|--------------------------|----------|
| Criar Usuário     | POST   | /users              | name, email, password    | Não      |
| Login             | POST   | /login              | email, password          | Não      |
| Listar Usuários   | GET    | /users              | -                        | Sim      |
| Ver Usuário       | GET    | /users/:id          | -                        | Sim      |
| Editar Usuário    | PUT    | /users/:id          | name, email, password    | Sim      |
| Deletar Usuário   | DELETE | /users/:id          | -                        | Sim      |
| Registrar Café    | POST   | /users/:id/drink    | drink (int)              | Sim      |

---

# Relatórios e Rankings (Implementações Adicionais)

## Ranking Geral

GET /ranking/days/:days

Exemplo:

/ranking/days/7

Retorna os usuários que mais consumiram café nos últimos 7 dias.

## Histórico Diário

Serviço responsável por listar o histórico de registros por dia de um usuário específico.

---

# Regras de Negócio e Validações

### Unicidade de E-mail
O sistema valida se o usuário já existe no momento do cadastro.

### Validação de Login
- Verifica credenciais inválidas
- Detecta senha incorreta ou usuário inexistente

### Controle de Propriedade
- Operações de edição (PUT) e remoção (DELETE) são restritas ao usuário autenticado
- Um usuário não pode alterar dados de terceiros

### Persistência de Consumo
O endpoint de registro de café:
- Incrementa o contador
- Retorna o status atualizado (drinkCounter)

---

# Autor

Thiago da Luz Barbosa

Projeto desenvolvido como demonstração de competência técnica em desenvolvimento de APIs RESTful utilizando PHP.
