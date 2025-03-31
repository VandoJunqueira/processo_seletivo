# Teste - Desenvolvedor PHP - Senior

## Guia de Instalação

Este guia descreve os passos necessários para instalar e configurar a aplicação, que utiliza o [Laravel Sail](https://laravel.com/docs/12.x/sail). Certifique-se de seguir cada etapa cuidadosamente para garantir uma instalação bem-sucedida.

Caso esteja no Windows acesse a [documentação](https://laravel.com/docs/12.x/installation#getting-started-on-windows) do Laravel para mais informações.

## Pré-requisitos

Antes de começar, certifique-se de ter o seguinte instalado no seu sistema:

- [Docker](https://www.docker.com/get-started)
- [Git](https://git-scm.com/)
- [Composer](https://getcomposer.org/)

# Passos de Instalação

### 1. Clone Repositório

Clone o repositório da aplicação a partir do repositório Git:

```sh
git clone https://github.com/VandoJunqueira/processo_seletivo.git
```

### 2. Instalar as Dependências do Backend

Navegue até o diretório da aplicação clonada e instale as dependências do backend usando o Composer:

```sh
cd processo_seletivo
composer install
```

### 3. Configurar o Arquivo .env

Duplique o arquivo .env.example e renomeie-o para .env.

```sh
cp .env.example .env
```

### 4. Iniciar o Laravel Sail

Se tiver no windows acessa o terminar do Linux com o comando `wsl`

```sh
./vendor/bin/sail up -d
```

No entanto, em vez de digitar repetidamente `vendor/bin/sail` para executar comandos do Sail, você pode configurar um alias de shell que permita executar os comandos do Sail com mais facilidade:

```sh
alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
```

Depois que o alias do shell tiver sido configurado, você poderá executar comandos Sail simplesmente digitando `sail`. O restante dos exemplos desta documentação assumirá que você configurou este alias:

```sh
sail up -d
```

### 5. Executar migração

```sh
sail artisan migrate
```

### 6. Executar o seeder para popular a tabela

```sh
sail artisan db:seed
```

Após a execução do seeder é criado um usuário de teste:

```sh
email: user@test.com
senha: 123456
```

### 7. Acessar a Aplicação

Abra o seu navegador e acesse [http://localhost](http://localhost) para visualizar a aplicação em funcionamento.


## Autenticação e Uso do Token  

Para acessar os endpoints protegidos da API, é necessário autenticar-se e obter um **token de acesso**. Isso é feito enviando uma requisição **POST** para o endpoint de autenticação.  

### **Endpoint de Autenticação** 

Endpoint: `POST /api/authenticate`

## BODY PARAMS

| Parâmetro | Tipo   | Obrigatório | Descrição                      |
| --------- | ------ | ----------- | ------------------------------ |
| email     | string | Sim         | Endereço de e-mail do usuário  |
| password  | string | Sim         | Senha do usuário               |

### **Exemplo de Requisição**  

```json
{
    "email": "usuario@example.com",
    "password": "senha123"
}
```
**Exemplo de resposta de sucesso (201 OK):**

```json
{
    "token_type": "Bearer",
    "expires_in": 300,
    "issued_at": "2025-03-31T22:15:45.143493Z",
    "access_token": "vj_NSKXxSNc5WvrkiEAU8h4oNXCfik3FbEeUgOJiGE6e0T975Gra2LYiN5rqtunFbL80MZb0jrEHalPbCtvlfDmGxErqlcFADZO4Qzave8ABhRZsScsmHut3NrUJY3MH5DX",
    "expires_at": "2025-03-31T22:20:45.000000Z"
}
```

### Expiração do Token
O token tem um tempo de expiração definido no campo "expires_in", que indica o tempo em segundos (neste caso, 300 segundos = 5 minutos).

Após esse tempo, será necessário obter um novo token realizando a autenticação novamente

## Rotas da API

### Endpoints Unidade

#### Store

Endpoint: `POST /api/unidade`

Descrição: Cadastra uma nova unidade

## BODY PARAMS

| Parâmetro          | Tipo    | Obrigatório | Descrição                             |
| ------------------ | ------- | ----------- | ------------------------------------- |
| nome               | string  | Sim         | Nome da Unidade                       |
| sigla              | string  | Sim         | Sigla da Unidade                      |
| endereco           | object  | Sim         | Endereço da Unidade                   |
| ├─ tipo_logradouro | string  | Sim         | Tipo de logradouro (ex: Avenida, Rua) |
| ├─ logradouro      | string  | Sim         | Nome do logradouro                    |
| ├─ numero          | integer | Sim         | Número do endereço                    |
| ├─ bairro          | string  | Sim         | Bairro da Unidade                     |
| ├─ cidade          | object  | Sim         | Dados da cidade                       |
| │  ├─ nome         | string  | Sim         | Nome da cidade                        |
| │  ├─ uf           | string  | Sim         | Estado (UF) da cidade                 |

**Exemplo de resposta de sucesso (201 OK):**

```json
{
    "status": "success",
    "data": {
        "unid_nome": "Teste - edit",
        "unid_sigla": "MT",
        "unid_id": 3,
        "endereco": [
            {
                "end_id": 6,
                "end_tipo_logradouro": "Avenida",
                "end_logradouro": "Brasil",
                "end_numero": 1234,
                "end_bairro": "Centro",
                "cid_id": 1,
                "pivot": {
                    "unid_id": 3,
                    "end_id": 6
                }
            }
        ]
    },
    "message": "Unidade criada com sucesso"
}
```

#### Exibir

Endpoint: `GET /api/unidade/{id}`

```json
{
    "status": "success",
    "data": {
        "unid_id": 1,
        "unid_nome": "Teste - edit",
        "unid_sigla": "MT",
        "endereco": [
            {
                "end_id": 1,
                "end_tipo_logradouro": "Avenida",
                "end_logradouro": "Brasil",
                "end_numero": 1234,
                "end_bairro": "Centro",
                "cid_id": 1,
                "pivot": {
                    "unid_id": 1,
                    "end_id": 1
                }
            }
        ]
    }
}
```

#### Listar

Endpoint: `GET /api/unidade?per_page=10&page=1`

```json
{
    "status": "success",
    "data": {
        "current_page": 1,
        "data": [
            {
                "unid_id": 1,
                "unid_nome": "Teste - edit",
                "unid_sigla": "MT",
                "endereco": [
                    {
                        "end_id": 1,
                        "end_tipo_logradouro": "Avenida",
                        "end_logradouro": "Brasil",
                        "end_numero": 1234,
                        "end_bairro": "Centro",
                        "cid_id": 1,
                        "pivot": {
                            "unid_id": 1,
                            "end_id": 1
                        }
                    }
                ]
            },
            {
                "unid_id": 2,
                "unid_nome": "Teste - edit",
                "unid_sigla": "MT",
                "endereco": [
                    {
                        "end_id": 3,
                        "end_tipo_logradouro": "Avenida",
                        "end_logradouro": "Brasil",
                        "end_numero": 1234,
                        "end_bairro": "Centro",
                        "cid_id": 1,
                        "pivot": {
                            "unid_id": 2,
                            "end_id": 3
                        }
                    }
                ]
            },
            {
                "unid_id": 3,
                "unid_nome": "Teste - edit",
                "unid_sigla": "MT",
                "endereco": [
                    {
                        "end_id": 6,
                        "end_tipo_logradouro": "Avenida",
                        "end_logradouro": "Brasil",
                        "end_numero": 1234,
                        "end_bairro": "Centro",
                        "cid_id": 1,
                        "pivot": {
                            "unid_id": 3,
                            "end_id": 6
                        }
                    }
                ]
            }
        ],
        "first_page_url": "http://localhost/api/unidade?page=1",
        "from": 1,
        "next_page_url": null,
        "path": "http://localhost/api/unidade",
        "per_page": 10,
        "prev_page_url": null,
        "to": 3
    }
}
```

#### Update

Endpoint: `PUT /api/unidade/{id}`

Descrição: Cadastra uma nova unidade

## BODY PARAMS

| Parâmetro          | Tipo    | Obrigatório | Descrição                             |
| ------------------ | ------- | ----------- | ------------------------------------- |
| nome               | string  | Sim         | Nome da Unidade                       |
| sigla              | string  | Sim         | Sigla da Unidade                      |
| endereco           | object  | Sim         | Endereço da Unidade                   |
| ├─ tipo_logradouro | string  | Sim         | Tipo de logradouro (ex: Avenida, Rua) |
| ├─ logradouro      | string  | Sim         | Nome do logradouro                    |
| ├─ numero          | integer | Sim         | Número do endereço                    |
| ├─ bairro          | string  | Sim         | Bairro da Unidade                     |
| ├─ cidade          | object  | Sim         | Dados da cidade                       |
| │  ├─ nome         | string  | Sim         | Nome da cidade                        |
| │  ├─ uf           | string  | Sim         | Estado (UF) da cidade                 |

**Exemplo de resposta de sucesso (201 OK):**

```json
{
    "status": "success",
    "data": {
        "unid_nome": "Teste - edit",
        "unid_sigla": "MT",
        "unid_id": 3,
        "endereco": [
            {
                "end_id": 6,
                "end_tipo_logradouro": "Avenida",
                "end_logradouro": "Brasil",
                "end_numero": 1234,
                "end_bairro": "Centro",
                "cid_id": 1,
                "pivot": {
                    "unid_id": 3,
                    "end_id": 6
                }
            }
        ]
    },
    "message": "Unidade editada com sucesso"
}
```

#### Apagar

Endpoint: `GET /api/unidade/{id}`

```json
{
    "status": "success",
    "data": {
        "current_page": 1,
        "data": [
            {
                "unid_id": 1,
                "unid_nome": "Teste - edit",
                "unid_sigla": "MT",
                "endereco": [
                    {
                        "end_id": 1,
                        "end_tipo_logradouro": "Avenida",
                        "end_logradouro": "Brasil",
                        "end_numero": 1234,
                        "end_bairro": "Centro",
                        "cid_id": 1,
                        "pivot": {
                            "unid_id": 1,
                            "end_id": 1
                        }
                    }
                ]
            },
            {
                "unid_id": 2,
                "unid_nome": "Teste - edit",
                "unid_sigla": "MT",
                "endereco": [
                    {
                        "end_id": 3,
                        "end_tipo_logradouro": "Avenida",
                        "end_logradouro": "Brasil",
                        "end_numero": 1234,
                        "end_bairro": "Centro",
                        "cid_id": 1,
                        "pivot": {
                            "unid_id": 2,
                            "end_id": 3
                        }
                    }
                ]
            },
            {
                "unid_id": 3,
                "unid_nome": "Teste - edit",
                "unid_sigla": "MT",
                "endereco": [
                    {
                        "end_id": 6,
                        "end_tipo_logradouro": "Avenida",
                        "end_logradouro": "Brasil",
                        "end_numero": 1234,
                        "end_bairro": "Centro",
                        "cid_id": 1,
                        "pivot": {
                            "unid_id": 3,
                            "end_id": 6
                        }
                    }
                ]
            }
        ],
        "first_page_url": "http://localhost/api/unidade?page=1",
        "from": 1,
        "next_page_url": null,
        "path": "http://localhost/api/unidade",
        "per_page": 10,
        "prev_page_url": null,
        "to": 3
    }
}
```

## Créditos

Este projeto foi desenvolvido por [Vando Junqueira](https://github.com/VandoJunqueira/) como parte de um teste prático.
