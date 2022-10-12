# Laravel API
## Projeto Base usando JWT e Spatie Permissions
` `

#### Clonando o repositório `*`
```sh
git clone git@bitbucket.org:_matheus-souza_/laravel_base.git
```

#### Dependências `*`
- [Composer](https://getcomposer.org/download/)
- [PHP](https://www.php.net/downloads.php)

#### Configuração de Ambiente `*`
`.ENV`: Crie o arquivo **.env** baseado no arquivo **.env.example** e faça as seguintes mudanças de acordo com o seu ambiente de desenvolvimento
```sh
DB_CONNECTION=driver de conexão com o banco de dados. Ex: pgsql, mysql
DB_HOST=host do banco de dados. Ex: 127.0.0.0, localhost
DB_PORT=porta do banco de dados. Ex: 5432, 3306
DB_DATABASE=nome do banco de dados
DB_USERNAME=usuário do banco de dados
DB_PASSWORD=senha do banco de dados
```

#### Instalando Pacotes/Dependências e Gerando Keys `*`
`Instalando Pacotes e Dependências`
```sh
composer install --ignore-platform-reqs
```
`Laravel App Key`
```sh
php artisan key:generate
```
`JWT Secret Key`
```sh
php artisan jwt:secret
```
> **Nota:** Todos os comandos devem ser executados dentro da pasta do projeto.

#### Rodando Migrations e Populando o Banco de Dados `*`
```sh
php artisan migrate --seed
```
ou
```sh
php artisan migrate:fresh --seed
```

#### Usuários Iniciais
`Admin`
```sh
name: Admin, email: admin@gmail.com, password: admin
```
`User`
```sh
name: User, email: user@gmail.com, password: user
```

#### Rotas Iniciais
`URL Base`
```sh
http://host:8000/api/v1 | http://localhost:8000/api/v1
```
`Login na API`
```sh
/login
```
`Logout da API`
```sh
/logout
```
`Usuário Logado`
```sh
/me
```
`Atualizar Token`
```sh
/refresh
```
`Novo Usuário`
```sh
/register
```

#### Rodando Projeto
```sh
php artisan serve
```

> **Nota:** As rotas de Login e Novo Usuário não necessitam que seja passado um token, já as demais rotas, necessitam.

**Matheus de Souza Rufino - FullStack Developer**
