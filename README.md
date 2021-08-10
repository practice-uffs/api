<p align="center">
    <img width="800" src=".github/logo.png" title="Logo do projeto"><br />
    <img src="https://img.shields.io/maintenance/yes/2021?style=for-the-badge" title="Status do projeto">
    <img src="https://img.shields.io/github/workflow/status/ccuffs/template/ci.uffs.cc?label=Build&logo=github&logoColor=white&style=for-the-badge" title="Status do build">
</p>

# API Practice

Este repositório contém a API central do programa [Practice](https://practice.uffs.cc), que integra todos os serviços digitais criados pelo programa. Para ver a documentação completa da documentação, visite [api-practice.uffs.edu.br/docs](https://api-practice.uffs.edu.br/docs).

## 🚀 Começando

### 1. Dependências

Para executar o projeto, você precisa ter o seguinte instalado:

- [Git](https://git-scm.com);
- [PHP](https://www.php.net/downloads);
- [Composer](https://getcomposer.org/download/);
- [NodeJS](https://nodejs.org/en/);
- [NPM](https://www.npmjs.com/package/npm);

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

Por fim execute o comando abaixo para a geração da chave de autenticação da aplicação:

```
php artisan key:generate
```

Gere os recursos JavaScript e CSS:

```
npm run dev
```

>*DICA:* enquanto estiver desenvolvendo, rode `npm run watch` para manter os scripts javascript sendo gerados sob demanda quando alterados.

### 3. Utilizacão

#### 3.1 Rodando o projeto

Depois de seguir todos os passos de instalação, inicie o servidor do Laravel:

```
php artisan serve
```

Após isso a aplicação estará rodando na porta 8000 e poderá ser acessada em [localhost:8000](http://localhost:8000).


#### 3.2 Utilização da API

Todos endpoints disponibilizados pela API estarão acessivel em `/v0`, `/v1`, etc, por exemplo [localhost:8000/v0](http://localhost:8000/v0). A maioria dos endpoints exige autenticação, então você precisa obter um token de acesso primeiro através do endpoint `/v0/auth`:

```
curl -H 'Accept: application/json' -d "user=meuiduffsaqui&password=minhasenhaaqui" http://localhost:8000/v0/auth
```

A resposta deve ser algo parecido com o seguinte:

```json
{
    "token": "f7c3e8dc63a1385d1db8f07cc6758fa84d860364d3c4df9b8ef38f943e658e03",
    "passport": null,
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

O `token` retornado é sua chave de autenticação. Com ele, você pode acessar os endpoints que precisam autenticação. Para isso, utilize o seguinte cabeçalho HTTP nas requisições que precisam de autenticação:

```
Authorization: Bearer XXX
```

onde `XXX` é o valor do seu token/chave de acesso. Abaixo está um exemplo de requisição para o endpoint `user` utilizando a chave de acesso acima:

```bash
curl -H 'Accept: application/json' -H "Authorization: Bearer f7c3e8dc63a1385d1db8f07cc6758fa84d860364d3c4df9b8ef38f943e658e03" http://localhost:8080/v0/user
```

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