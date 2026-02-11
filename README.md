Estrutura baseado em: https://docs.docker.com/guides/frameworks/laravel/production-setup/ e https://laravel.com/docs/12.x

## Como Rodar

### Pré-requisitos

- Docker e Docker Compose instalados
- Git instalado

### Organização das pastas

Os dois repositórios precisam estar na **mesma pasta**. Crie um diretório (por exemplo `farmaciaOnline`) e clone os projetos nesta ordem:

1. **Clone o backend:**

   ```bash
   git clone https://github.com/LucasPetruci/farmaciaOnlineBackend
   ```

2. **Clone o frontend na mesma pasta:**

   ```bash
   git clone https://github.com/LucasPetruci/farmaciaOnlineFrontend
   ```

A estrutura deve ficar assim:

```
farmaciaOnline/
├── farmaciaOnlineBackend/
└── farmaciaOnlineFrontend/
```

Em seguida, entre na pasta do backend (`cd farmaciaOnlineBackend`) e siga o passo a passo abaixo.

### Passo a Passo

1. **Copie o arquivo de exemplo de variáveis de ambiente:**

   ```bash
   cp .env.example .env
   ```
2. **Configure as variáveis de ambiente no arquivo `.env`:**

   Edite o arquivo `.env` e configure apenas as seguintes variáveis (as demais já têm valores padrão):

   - **APP_KEY**: Deixe vazio por enquanto, será gerado automaticamente no passo 6
   - **DB_PASSWORD**: Configure uma senha para o banco de dados
3. **Inicie os containers:**

   ```bash
   docker compose up --build
   ```
4. **Acesse o container de workspace:**

   ```bash
   docker exec -it farmaciaonline-workspace bash
   ```
5. **Instale as dependências do Composer:**

   ```bash
   composer install
   ```
6. **Gere a chave da aplicação:**

   ```bash
   php artisan key:generate
   ```
7. **Execute as migrações:**

   ```bash
   php artisan migrate
   ```

### Acessando a Aplicação

- **Backend API**: http://localhost:8000
- **Frontend Angular**: http://localhost:4200

### Documentação das rotas (Insomnia)

Você pode consultar e testar todas as rotas da API importando a coleção do [Insomnia](https://insomnia.rest/) que está no repositório. Basta importar o arquivo `Insomnia_farmacia_online.yaml` no Insomnia ( **Application** → **Import/Export** → **Import Data** → **From File** ) e usar as requisições prontas para produtos, autenticação etc.

## Funcionalidades do Backend

1. [X] **Cadastrar produtos** - `POST /api/products`

    - Validação: nome, preço e tipo obrigatórios
    - Mensagens de erro personalizadas em inglês
2. [X] **Listar produtos com paginação** - `GET /api/products`

    - Paginação de 15 itens por página
    - Retorna metadados de paginação (total, per_page, current_page, etc.)
3. [X] **Atualizar produtos** - `PUT/PATCH /api/products/{id}`

    - Validação com campos opcionais (usando `sometimes`)
    - Route Model Binding para buscar produto automaticamente
4. [X] **Buscar produto por nome** - `GET /api/products?search=nome`

    - Busca parcial (LIKE) no campo `name`
    - Funciona com paginação
    - Usa `when()` para aplicar filtro condicionalmente
5. [X] **Autenticação de usuário**

    - Criar usuário
    - Login e Logout
    - Proteger rotas com middleware de autenticação
6. [X] **Filtros e ordenação na listagem**

    - Filtrar por tipo: `GET /api/products?type=medication`
    - Ordenar por nome ou preço: `GET /api/products?sort_by=name&sort_order=asc`
    - Parâmetros de ordenação: `sort_by` (name, price) e `sort_order` (asc, desc)
    - Combina com busca e paginação
    - Validação de colunas permitidas para segurança

### Validações Implementadas

- **Nome**: obrigatório, string, máximo 255 caracteres
- **Preço**: obrigatório, numérico, mínimo 0
- **Tipo**: obrigatório, deve ser um dos valores: `medication`, `vitamin`, `supplement`, `hygiene`, `beauty`, `others`
