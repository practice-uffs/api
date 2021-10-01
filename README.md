<p align="center">
    <img width="800" src=".github/logo.png" title="Logo do projeto"><br />
    <img src="https://img.shields.io/maintenance/yes/2021?style=for-the-badge" title="Status do projeto">
    <img src="https://img.shields.io/github/workflow/status/ccuffs/template/ci.uffs.cc?label=Build&logo=github&logoColor=white&style=for-the-badge" title="Status do build">
</p>

# API Practice

Este repositório contém a API central do programa [Practice](https://practice.uffs.cc), que integra todos os serviços digitais criados pelo programa. Esse serviço tem o papel de centralizar funcionalidades para outras aplicações do programa (api gateway), além de disponibilizar serviços próprios.

> Documentação completa da API: [api-practice.uffs.edu.br/documentation](https://api-practice.uffs.edu.br/documentation)

## 🚀 Começando

### 1. Dependências

Para executar o projeto, você precisa ter o seguinte instalado:

- [Git](https://git-scm.com)
- [PHP](https://www.php.net/downloads)
- [Composer](https://getcomposer.org/download/)
- [NodeJS](https://nodejs.org/en/)
- [NPM](https://www.npmjs.com/package/npm)

Você precisa de várias extensões PHP instaladas também:

```
sudo apt install php-cli php-mbstring php-zip php-xml php-curl
```

### 2. Configuração

Feito a instalação das dependências, é necessário obter uma cópia do projeto. A forma recomendada é clonar o repositório para a sua máquina.

Para isso, rode:

```
git clone --recurse-submodules https://github.com/practice-uffs/api && cd api
```

Isso criará e trocará para a pasta `api` com o código do projeto.

#### 2.1 PHP

Instale as dependências do PHP usando o comando abaixo:

```
composer install
```

#### 2.2 Banco de Dados

O banco de dados mais simples para uso é o SQLite. Para criar uma base usando esse SGBD, rode:

```
touch database/database.sqlite
```

#### 2.3 Node

Instale também as dependências do NodeJS executando:

```
npm install
```

#### 2.4 Laravel

Crie o arquivo `.env` a partir do arquivo `.env.example` gerado automaticamente pelo Laravel:

```
cp .env.example .env
```

Crie as tabelas do banco de dados:

```
php artisan migrate
```

Gere os recursos JavaScript e CSS:

```
npm run dev
```

>*DICA:* enquanto estiver desenvolvendo, rode `npm run watch` para manter os scripts javascript sendo gerados sob demanda quando alterados.

Se você estiver rodando o projeto localmente para desenvolvimento, você também precisa rodas os seeds:

```
php artisan db:seed
```

#### 2.5 Aura NLP (opcional)

Se você estiver desenvolvendo funcionalidades que utilizem a api de NLP da Aura, o micro-serviço da Aura precisa ser configurado. Essa funcionalidade é disponibilizada pelo projeto externo [aura-nlp](https://github.com/ccuffs/aura-nlp).

O sistema da `aura-nlp` pode ser instalado e configurado em qualquer pasta do seu computador. Instruções de instalação, configuração e execução estão no [README](https://github.com/ccuffs/aura-nlp/blob/master/README.md) do [aura-nlp](https://github.com/ccuffs/aura-nlp).

Se você seguir todas as instruções, a `aura-nlp` estará rodando em uma url como `http://localhost:3000/api/`. Feito isso, você precisa informar essa url nos arquivos de configuração da API practice.

Para isso, edite o arquivo `.env` e adicione a seguinte linha (edite essa linha se ela já existir):

```
AURA_NLP_API_URL="http://localhost:3000/api"
```

Para garantir que o Laravel não tem caches antigas, rode em seguida:

```
php artisan config:clear
```

### 3. Utilizacão

#### 3.1 Rodando o projeto

Depois de seguir todos os passos de instalação, inicie o servidor do Laravel:

```
php artisan serve
```

Após isso a aplicação estará rodando na porta 8000 e poderá ser acessada em [localhost:8000](http://localhost:8000).

> *Dica:* acesse [localhost:8000/documentation](http://localhost:8000/documentation) para ver a documentação de cada endpoint.

#### 3.2 Utilização da API

Todos endpoints disponibilizados pela API estarão acessivel em `/v0`, `/v1`, etc, por exemplo [localhost:8000/v0](http://localhost:8000/v0). A maioria dos endpoints exige autenticação, então você precisa obter um token de acesso primeiro através do endpoint `/v0/auth`:

```
curl -H 'Accept: application/json' -d "user=meuiduffsaqui&password=minhasenhaaqui&app_id=1" http://localhost:8000/v0/auth
```

> *DICA:* o `app_id` é o id da aplicação de onde você está fazendo a requisição. Ela deve estar cadastrada na lista de aplicações de api para funcionar. Por convenção, o id da aplicação `pratice-api` é 0.

A resposta deve ser algo parecido com o seguinte:

```json
{
    "passport": "eyJ0eXAiOiJKV1QiLCJhbGc...9qBQVVw",
    "user": {
        "name": "Fulano Silva",
        "email": "fulano.silva@uffs.edu.br",
        "username": "fulano.silva",
        "cpf": "99988877666",
        "uid": "fulano.silva",
        "pessoa_id": "fe82a549-2226-46c1-91b2-a9e9ba4c2d5b"
    }
}
```

O `passport` retornado é sua chave de autenticação. Com ele, você pode acessar os endpoints que precisam autenticação (inclusive de outros sistemas do Practice, como o [mural](https://github.com/practice-uffs/mural)). Para isso, utilize o seguinte cabeçalho HTTP nas requisições que precisam de autenticação:

```
Authorization: Bearer XXX
```

onde `XXX` é o valor do seu passporte/token de acesso. Abaixo está um exemplo de requisição para o endpoint `user` utilizando a chave de acesso acima:

```bash
curl -H 'Accept: application/json' -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc...9qBQVVw" http://localhost:8000/v0/user
```

#### 3.3 Token de acesso para desenvolvimento (opcional)

Se você estiver trabalhando com a API de outro serviço Practice, como o [mural](https://github.com/practice-uffs/mural) acessado através da API Practice (api gateway), você precisará de um token/passaporte de acesso.

O practice api, quando roda localmente, tem uma rota `/test` para gerar tokens válidos. Para isso, acesse [localhost:8000/test](http://localhost:8000/test). Há instruções na página sobre o token e como gerar outros. 

#### 3.4 Consumo de outras APIs practice (api gateway)

Utilizando a API central do Practice, você pode consumir apis de outros serviços, como o [mural](https://github.com/practice-uffs/mural), utilizando um passaporte único. As rotas disponíveis estão no arquivo `routes/api/v0.php`.

Em linhas gerais, elas são no formato `/v0/{app}/{endpoint}`, onde `{app}` é o apelido (slug) de um app practice (exemplo: `mural`) e `{endpoint}` é o endpoint da api naquele serviço. Usando o mural como exemplo, se houver a rota `/api/services/audio` no próprio mural, essa rota está disponível na api practice como `/v0/mural/services/audio`. Os verbos de acesso (`GET`, `POST`, etc) são os mesmos, assim como os modelos/parâmetros de entrada.

O passaporte de acesso obtido através do endpoint `/v0/auth` da api central funcionará para acesso as outras apis (esse processo é feito internamente, de forma transparente). Você também pode utilizar ele como `Authorization: Bearer XXX` nas requisições que precisam de autenticação dos outros serviços (o token da API  central funciona no mural, sem passar pela api central, por exemplo).

> *IMPORTANTE*: a api central do practice toma as precauções para que o token de acesso seja válido e que o usuário exista na aplicação destino. Por exemplo, se o usuário nunca logou no mural e houver uma requisição (via api gateway) para o mural (vindo do app móvel do programa, por exemplo), a api criará o usuário equivalente no mural (em linhas gerais, ela fará uma autenticação e criação de conta no mural em nome do usuário dono da requisição original na api central).

## 🤝 Contribua

Sua ajuda é muito bem-vinda, independente da forma! Confira o arquivo [CONTRIBUTING.md](CONTRIBUTING.md) para conhecer todas as formas de contribuir com o projeto. Por exemplo, [sugerir uma nova funcionalidade](https://github.com/practice-uffs/api/issues/new?assignees=&labels=&template=feature_request.md&title=), [reportar um problema/bug](https://github.com/practice-uffs/api/issues/new?assignees=&labels=bug&template=bug_report.md&title=), [enviar um pull request](https://github.com/ccuffs/hacktoberfest/blob/master/docs/tutorial-pull-request.md), ou simplemente utilizar o projeto e comentar sua experiência.

Veja o arquivo [ROADMAP.md](ROADMAP.md) para ter uma ideia de como o projeto deve evoluir.


## 🎫 Licença

Esse projeto é licenciado nos termos da licença open-source [MIT](https://choosealicense.com/licenses/mit) e está disponível de graça.

## 🧬 Changelog

Veja todas as alterações desse projeto no arquivo [CHANGELOG.md](CHANGELOG.md).

## 🧪 Projetos semelhates

Abaixo está uma lista de links interessantes e projetos similares:

* [Mural Practice](https://github.com/practice-uffs/mural)
* [App Practice](https://github.com/practice-uffs/app)