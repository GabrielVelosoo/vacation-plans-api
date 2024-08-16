<!-- title -->
<h1 align="center"> 
	   VACATION-PLANS-API 
</h1>

<!-- Description: -->
## Sobre o projeto

<!-- EXPLICA O MOTIVO DO PROJETO -->
vacation-plans-api é um projeto criado para o processo seletivo da Buzzvel.

O desafio proposto para este projeto era a criação de uma API RESTful para gerenciar planos de férias para o ano de 2024. Neste projeto, teríamos que implementar operações CRUD (Criar, Ler, Atualizar, Excluir) para planos de férias, garantir a autenticação segura dos usuários, validar as entradas para garantir integridade dos dados e permitir a geração de documentos PDF com os detalhes dos planos.

A API foi desenvolvida utilizando Laravel, Docker e MySQL, e inclui endpoints para criar, recuperar, atualizar e excluir planos de férias, bem como para gerar PDFs detalhados dos planos. O objetivo é demonstrar habilidades no desenvolvimento de APIs, manuseio de dados e boas práticas de programação, além de fornecer uma solução robusta e bem documentada para o gerenciamento de planos de férias.

<!-- LINHA DE DIVISÃO: -->

---

<!-- ---------------------------------------------------------------------- -->

<!-- MODELO DE PRÉ REQUISITOS -->
## Pré-requisitos

Antes de começar, você vai precisar ter instalado em sua máquina as seguintes ferramentas:<br>
• [Git](https://git-scm.com/downloads)<br>
• [Docker](https://www.docker.com/products/docker-desktop) e [Docker Compose](https://docs.docker.com/compose/install/)<br>

Além disso, é recomendável ter um editor para trabalhar com o código, como [VSCode](https://code.visualstudio.com/).

Certifique-se de que o Docker e o Docker Compose estejam funcionando corretamente para configurar e executar o ambiente do projeto. Não é necessário instalar o PHP ou o MySQL separadamente, pois eles são configurados automaticamente dentro dos contêineres Docker.

---

<!-- ---------------------------------------------------------------------- -->

<!-- MODELO DE COMO EXECUTAR O PROJETO -->
## Configuração do ambiente

1. **Baixar o Projeto**

   Clone o repositório do projeto para sua máquina local:
   ```
   git clone https://github.com/GabrielVelosoo/vacation-plans-api.git
   ```
   
   Depois acesse o diretório do projeto
   ```
   cd vacation-plans-api
   ```
   
3. **Configurar e Executar o Docker**

   Certifique-se de que o Docker e o Docker Compose estejam instalados e funcionando corretamente.
   
   • Subir os Contêineres

   Execute o comando abaixo para construir as imagens e iniciar os contêineres:
   
   ```
   docker-compose up -d
   ```

   • Instalar as Dependências do PHP

   Utilize o comando `docker ps` para listar os containers, você vai ter um resultado parecido com este:
   ```
   CONTAINER ID   IMAGE                  COMMAND                  CREATED          STATUS          PORTS                   NAMES
   b620bb4e4c28   vacation-plans-api-app "docker-php-entrypoi…"   30 minutes ago   Up 30 minutes   0.0.0.0:8000->80/tcp    vacation-plans-api-app-1
   3b7e781f9d14   mysql:8.0.39           "docker-entrypoint.s…"   30 minutes ago   Up 30 minutes   0.0.0.0:3306->3306/tcp  vacation-plans-api-mysql-1
   ```

   Entre no contêiner da Aplicação(vacation-plans-api-app) e instale as dependências do Composer:
   
   ```
   docker exec -it <nome-ou-id-contêiner> bash
   composer install
   ```

   • Configuração do Ambiente

   Ainda no contêiner da aplicação, copie o arquivo .env.example para .env.
   ```
   cp .env.example .env
   ```

   Dentro do arquivo .env, configure as váriaveis do banco de dados:
   ```
   DB_CONNECTION=mysql
   DB_HOST=mysql
   DB_PORT=3306
   DB_DATABASE=vacation_plans
   DB_USERNAME=user
   DB_PASSWORD=password
   ```
   
   • Gerar a Chave da Aplicação

   No mesmo contêiner, gere a chave da aplicação do Laravel:
   
   ```
   php artisan key:generate
   ```
   
   • Executar as Migrações

   Ainda dentro do contêiner, execute as migrações para configurar o banco de dados:

   ```
   php artisan migrate
   ```

   • Executar o Seeder

   E por fim, execute o seeder para gerar um usuário na tabela users, para futuramente gerar o token de acesso:

   ```
   php artisan db:seed
   ```
   
5. **Acessar o Projeto**

   Após a configuração, a API estará disponível em http://localhost:8080 (ou a porta que você configurou no docker-compose.yml).

   Você pode usar ferramentas como [Postman](https://www.postman.com/downloads/) ou [Insomnia](https://insomnia.rest/download) para testar os endpoints da API.

---

<!-- ---------------------------------------------------------------------- -->

<!-- Autenticação: -->
## Autenticação

OBS: Para evitar qualquer tipo de problema, inclua "application/json" no cabeçalho `Accept` de suas requisições.
```
Accept: application/json
```

Para acessar os endpoints da API, você precisará de um token de autenticação. Abaixo estão os detalhes sobre como gerar o token. Inclua o token no cabeçalho `Authorization` de suas requisições:
```
Authorization: Bearer {seu-token}
```

#### Rotas para Autenticação

1. **Gerar Token de Acesso**<br>
   • **Método:** POST<br>
   • **URL:** `/api/login`<br>
   • **Corpo da Requisição:**<br>
     ```
     {
       "email": "user@test.com",
       "password": "12345",
       "device_name": "Postman"
     }
     ```
   • **Resposta de Sucesso:**
     ```
     {
       "token": "{seu-token}"
     }
     ```
   • **Resposta Dados Inválidos:**
     ```
     {
        "message": "The provided credentials are incorrect",
        "errors": {
            "message": [
                "The provided credentials are incorrect"
            ]
        }
     }
     ```
     OBS: Certifique-se de utilizar o email:user@test.com e a senha: 12345 criadas ao utilizar o comando `php artisan db:seed`, e não se esqueça de enviar o device_name, se preferir          você pode alterar o email e a senha, para isso, basta você acessar na raiz do projeto database/seeders/DatabaseSeeder.php, troque os dados pelos de sua preferência e utilize o 
     comando para rodar os seeders novamente, você pode também adicionar mais usuários, para isso basta trocar os dados em DatabaseSeeder.php e rodar novamente o comando `php artisan 
     db:seed`, ou se preferir, você pode acessar o contêiner do MySQL.

     Acessando contêiner MySQL:

     Utilize o `docker ps` para listar seus conteiners, após isso identifique o nome ou id do contêiner MySQL e entre nele com o comando:
     ```
     docker exec -it <nome-ou-id-contêiner> bash
     ```

     Após entrar no contêiner, utilize o comando:
     ```
     mysql -u root -p
     ```

     Será necessário uma senha para entrar, a senha do usuário root está definida no arquivo `docker-compose.yml`, por padrão a senha é "root", você terá algo parecido com isto:
     ```
     Enter password: root
     ```

     Pronto, agora você tem acesso ao MySQL e lá você pode utilizar comandos SQL como bem entender.
     
 
3. **Recuperar usuário logado**<br>
   • **Método:** GET<br>
   • **URL:** `/api/user`<br>
   • **Resposta de Sucesso:**<br>
     ```
     {
        "user": {
            "id": 1,
            "name": "User Test",
            "email": "user@test.com",
            "email_verified_at": "2024-08-16T00:00:00.000000Z",
            "created_at": "2024-08-16T00:00:00.000000Z",
            "updated_at": "2024-08-16T00:00:00.000000Z"
        }
     }
     ```
4. **Realizar Logout**<br>
   • **Método:** POST<br>
   • **URL:** `/api/logout`<br>
   • **Resposta de Sucesso:**<br>
     ```
     {
        "message": "success"
     }
     ```

---

<!-- ---------------------------------------------------------------------- -->

<!-- Uso: -->
## Uso

OBS: Para evitar qualquer tipo de problema, inclua "application/json" no cabeçalho `Accept` de suas requisições, inclua também o token no cabeçalho `Authorization`.
```
Accept: application/json
Authorization: Bearer {seu-token} #Inclua o Bearer e em seguida dê um espaço e inclua seu token
```

A API oferece vários endpoints para gerenciar planos de férias. Abaixo estão os detalhes sobre como interagir com cada um deles.

#### Endpoints da API

1. **Criar um Novo Plano de Férias**<br>
   • **Método:** POST<br>
   • **URL:** `/api/vacation-plans`<br>
   • **Corpo da Requisição:**<br>
     ```
     {
        "title": "Férias de Verão",
        "description": "Plano para as férias de verão",
        "date": "2024-07-01",
        "location": "Praia",
        "participants": "João, Maria"
     }
     ```
   • **Resposta de Sucesso:**<br>
     **Status: 201**
     ```
     {
        "title": "Férias de Verão",
        "description": "Plano para as férias de verão",
        "date": "2024-07-01",
        "location": "Praia",
        "participants": "João, Maria",
        "updated_at": "2024-08-16T00:00:00.000000Z",
        "created_at": "2024-08-16T00:00:00.000000Z",
        "id": 1
     }
     ```
2. **Recuperar Todos os Planos de Férias**<br>
   • **Método:** GET<br>
   • **URL:** `/api/vacation-plans`<br>
   • **Resposta de Sucesso:**<br>
     **Status: 200**
     ```
     [
        {
            "id": 1,
            "title": "Férias de Verão",
            "description": "Plano para as férias de verão",
            "date": "2024-07-01",
            "location": "Praia",
            "participants": "João, Maria",
            "created_at": "2024-08-16T16:39:55.000000Z",
            "updated_at": "2024-08-16T16:39:55.000000Z"
        }
     ]
     ```
3. **Recuperar um Plano de Férias Específico por ID**<br>
   • **Método:** GET<br>
   • **URL:** `/api/vacation-plans/{id}`<br>
   • **Parâmetros:**<br>
     • `id`: ID do plano de férias<br>
   • **Resposta de Sucesso:**<br>
     **Status: 200**
     ```
     {
        "id": 1,
        "title": "Férias de Verão",
        "description": "Plano para as férias de verão",
        "date": "2024-07-01",
        "location": "Praia",
        "participants": "João, Maria",
        "created_at": "2024-08-16T16:39:55.000000Z",
        "updated_at": "2024-08-16T16:39:55.000000Z"
     }
     ```
   • **Resposta Plano de Férias não encontrado:**<br>
     **Status: 404**
     ```
     {
        "message": "Holiday plan not found"
     }
     ```
     
4. **Atualizar um Plano de Férias Existente**<br>
   • **Método:** PUT<br>
   • **URL:** `/api/vacation-plans/{id}`<br>
   • **Parâmetros:**<br>
     • `id`: ID do plano de férias<br>
   • **Corpo da Requisição:**
     ```
     {
       "title": "Férias de Inverno",
       "description": "Plano atualizado para as férias de inverno",
       "date": "2024-12-01",
       "location": "Montanhas",
       "participants": "Ana, Pedro"
     }
     ```
   • **Resposta de Sucesso:**<br>
     **Status: 200**
     ```
     {
        "id": 1,
        "title": "Férias de Inverno",
        "description": "Plano atualizado para as férias de inverno",
        "date": "2024-12-01",
        "location": "Montanhas",
        "participants": "Ana, Pedro",
        "created_at": "2024-08-16T16:39:55.000000Z",
        "updated_at": "2024-08-16T16:47:30.000000Z"
     }
     ```
   • **Resposta Plano de Férias não encontrado:**<br>
     **Status: 404**
     ```
     {
        "message": "Unable to update. Holiday plan not found"
     }
     ```

5. **Atualizar um Plano de Férias Parcialmente**<br>
   • **Método:** PATCH<br>
   • **URL:** `/api/vacation-plans/{id}`<br>
   • **Parâmetros:**<br>
     • `id`: ID do plano de férias<br>
   • **Corpo da Requisição:**
     ```
     {
         "description": "Plano atualizado para as férias de inverno"
     }
     ```
   • **Resposta de Sucesso:**<br>
     **Status: 200**
     ```
     {
        "id": 1,
        "title": "Férias de Verão",
        "description": "Plano atualizado para as férias de inverno",
        "date": "2024-07-01",
        "location": "Praia",
        "participants": "João, Maria",
        "created_at": "2024-08-16T16:39:55.000000Z",
        "updated_at": "2024-08-16T17:08:56.000000Z"
     }
     ```
   • **Resposta Plano de Férias não encontrado:**<br>
     **Status: 404**
     ```
     {
        "message": "Unable to update. Holiday plan not found"
     }
     ```
     
6. **Excluir um Plano de Férias**<br>
   • **Método:** DELETE<br>
   • **URL:** `/api/vacation-plans/{id}`<br>
   • **Parâmetros:**<br>
     • `id`: ID do plano de férias<br>
   • **Resposta de Sucesso:**<br>
     **Status: 200**
     ```
     {
       "message": "Plan deleted"
     }
     ```
   • **Resposta Plano de Férias não encontrado:**<br>
     **Status: 404**
     ```
     {
        "message": "Unable to delete. Holiday plan not found"
     }
     ```
     
7. **Gerar PDF para um Plano de Férias Específico**<br>
   • **Método:** GET<br>
   • **URL:** `/api/vacation-plans/{id}/pdf`<br>
   • **Parâmetros:**<br>
     • `id`: ID do plano de férias<br>
   • **Resposta de Sucesso:**<br>
     **Status: 200**<br>
     O PDF será gerado e retornado como um download. Certifique-se de ter um visualizador de PDF para visualizar o documento.
     
   • **Resposta Plano de Férias não encontrado:**<br>
     **Status: 404**
     ```
     {
        "message": "Unable to generate pdf. Holiday plan not found"
     }
     ```

---

<!-- ---------------------------------------------------------------------- -->

<!-- Testes: -->
## Testes

Para executar os testes unitários, utilize o PHPUnit:
```
docker-compose exec <nome-ou-id-contêiner> bash
php artisan test
```

---

<!-- ---------------------------------------------------------------------- -->

<!-- Contribuição: -->
## Contribuição

1. Faça um **fork** do projeto.
2. Crie uma nova branch com as suas alterações: `git checkout -b my-feature`
3. Salve as alterações e crie uma mensagem de commit contando o que você fez: `git commit -m "feature: My new feature"`
4. Envie as suas alterações: `git push origin my-feature`

---

<!-- ---------------------------------------------------------------------- -->

<!-- MODELO DE TECNOLOGIAS -->
## Tecnologias

As seguintes ferramentas foram usadas na construção do projeto:

#### **Back-End**  ([Laravel](https://laravel.com/))

• **[Laravel Sanctum](https://laravel.com/docs/11.x/sanctum)** - Para autenticação via tokens OAuth.<br>
• **[Composer](https://getcomposer.org/)** - Gerenciador de dependências PHP.

#### **Banco de Dados**

• **[MySQL](https://www.mysql.com/)** - Sistema de gerenciamento de banco de dados.

#### **Containerização** ([Docker](https://www.docker.com/))

• **[Docker](https://www.docker.com/)** - Para containerização do ambiente de desenvolvimento.<br>
• **[Docker Compose](https://docs.docker.com/compose/)** - Para orquestração de múltiplos contêineres.

#### **Gerenciamento de Dependências e Build**

• **[PHP](https://www.php.net/)** - Linguagem de programação usada no back-end.<br>
• **[PHPUnit](https://phpunit.de/)** - Framework de testes para PHP.

#### **Documentação e Testes**

• **[Postman](https://www.postman.com/)** - Para testar os endpoints da API.

---

<!-- ---------------------------------------------------------------------- -->

<!-- AUTOR -->
## Autor

<a href="https://www.linkedin.com/in/gabriel-veloso-2183b82b6/">
Gabriel Veloso Pinheiro</a>
 <br />
 
[![Gmail Badge](https://img.shields.io/badge/-gaabrielvelooso@gmail.com-c14438?style=flat-square&logo=Gmail&logoColor=white&link=mailto:gaabrielvelooso@gmail.com)](mailto:gaabrielvelooso@gmail.com)

---

<!-- ---------------------------------------------------------------------- -->

<!-- LICENÇA -->
## Licença

Este projeto esta sobe a licença [MIT](./LICENSE).

Feito por Gabriel Veloso Pinheiro👋🏽 [Entre em contato!](https://www.linkedin.com/in/gabriel-veloso-2183b82b6/)

