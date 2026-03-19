# Cadastro de Clientes — Laravel + Docker

Sistema web de cadastro de clientes com autenticação, CRUD completo e consulta automática de endereço por CEP.

---

## O que você vai precisar antes de começar

Antes de tudo, instale as ferramentas abaixo no seu computador. Elas são gratuitas:

- **Rancher Desktop** — [https://rancherdesktop.io](https://rancherdesktop.io)
  - Após instalar, abra o Rancher Desktop e aguarde ele iniciar (ícone na barra de tarefas fica verde)
- **Git** — [https://git-scm.com/downloads](https://git-scm.com/downloads)
  - Necessário para clonar o projeto

> Não é necessário instalar PHP, MySQL ou qualquer outra coisa. O Docker cuida de tudo isso.

---

## Passo a passo para rodar o projeto

### 1. Baixe o projeto

Abra o terminal (Prompt de Comando, PowerShell ou Terminal do Mac/Linux) e execute:

```bash
git clone https://github.com/Tritecnologias/sebrae-mt.git
```

Entre na pasta do projeto:

```bash
cd sebrae-mt
```

### 2. Suba a aplicação

Execute o comando abaixo. Na primeira vez pode demorar alguns minutos pois irá baixar as dependências:

```bash
docker-compose up -d --build
```

O sistema irá automaticamente:
- Instalar todas as dependências do projeto
- Criar e configurar o banco de dados
- Criar o usuário de acesso inicial

### 3. Aguarde a inicialização

Após o comando acima terminar, aguarde cerca de **30 segundos** para o banco de dados inicializar completamente.

Você pode acompanhar o progresso com:

```bash
docker logs laravel_app -f
```

Quando aparecer a mensagem `NOTICE: fpm is running`, a aplicação está pronta.

Pressione `Ctrl + C` para sair dos logs.

### 4. Acesse no navegador

Abra o seu navegador e acesse:

**http://localhost:8088**

---

## Usuário e senha de acesso

Use as credenciais abaixo para entrar no sistema:

| Campo  | Valor             |
|--------|-------------------|
| E-mail | admin@admin.com   |
| Senha  | password          |

---

## Consulta de CEP

Ao cadastrar ou editar um cliente, basta digitar o CEP no campo indicado e clicar na lupa (ou sair do campo). O sistema preencherá automaticamente os campos de endereço.

O sistema utiliza duas fontes de consulta:

1. **WebService SOAP oficial dos Correios** — consultado primeiro
2. **ViaCEP** (viacep.com.br) — utilizado automaticamente como alternativa caso o serviço dos Correios esteja indisponível

Essa redundância garante que a busca de CEP funcione mesmo quando o serviço dos Correios apresentar instabilidades.

---

## Funcionalidades

- Login e logout com controle de sessão
- Cadastro completo de clientes: nome, e-mail, telefone e endereço
- Listagem com paginação
- Edição e exclusão de clientes
- Consulta automática de endereço pelo CEP
- Validações com mensagens em português

---

## Parar a aplicação

Para parar os containers:

```bash
docker-compose down
```

Para parar e apagar todos os dados do banco (útil para recomeçar do zero):

```bash
docker-compose down -v
```

Para subir novamente após parar (sem precisar rebuildar):

```bash
docker-compose up -d
```

---

## Estrutura do projeto

```
app/
  Http/
    Controllers/
      AuthController.php       # Login e logout
      ClienteController.php    # CRUD + endpoint de CEP
    Requests/
      ClienteRequest.php       # Validações do formulário
  Models/
    Cliente.php
  Services/
    CepService.php             # Consulta CEP (Correios SOAP + ViaCEP fallback)
database/
  migrations/                  # Criação das tabelas
  seeders/                     # Usuário admin inicial
resources/views/
  layouts/app.blade.php        # Layout base
  auth/login.blade.php         # Tela de login
  clientes/                    # Listagem, criação e edição
routes/web.php                 # Rotas da aplicação
docker-compose.yml             # Configuração dos containers
Dockerfile                     # Configuração da imagem PHP
```

---

Desenvolvido por: Wanderson Martins da Silva
