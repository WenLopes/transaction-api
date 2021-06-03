# Transaction API

API para controle de transações entre usuários

## Objetivo

Temos 2 tipos de usuários, os comuns e lojistas, ambos têm carteira com dinheiro e realizam transferências entre eles. Vamos nos atentar **somente** ao fluxo de transferência entre dois usuários.

Requisitos:

- Para ambos tipos de usuário, precisamos do Nome Completo, CPF, e-mail e Senha. CPF/CNPJ e e-mails devem ser únicos no sistema. Sendo assim, seu sistema deve permitir apenas um cadastro com o mesmo CPF ou endereço de e-mail.

- Usuários podem enviar dinheiro (efetuar transferência) para lojistas e entre usuários. 

- Lojistas **só recebem** transferências, não enviam dinheiro para ninguém.

- Validar se o usuário tem saldo antes da transferência.

- Antes de finalizar a transferência, deve-se consultar um serviço autorizador externo, use este mock para simular (https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6).

- A operação de transferência deve ser uma transação (ou seja, revertida em qualquer caso de inconsistência) e o dinheiro deve voltar para a carteira do usuário que envia. 

- No recebimento de pagamento, o usuário ou lojista precisa receber notificação (envio de email, sms) enviada por um serviço de terceiro e eventualmente este serviço pode estar indisponível/instável. Use este mock para simular o envio (http://o4d9z.mocklab.io/notify). 

- Este serviço deve ser RESTFul.

## Tecnologias inclusas no projeto

- [Docker](https://www.docker.com/)
- [PHP 7.3](https://www.php.net/)
- [Laravel 8.1](https://laravel.com/docs/8.x)
- [Composer 2.0](https://getcomposer.org/)
- [Nginx](https://www.nginx.com/)
- [Mysql 5.7](https://www.mysql.com/)
- [Phpmyadmin](https://www.phpmyadmin.net/)

## Instalação

### 1. Clonar o projeto

```bash
git clone https://github.com/WenLopes/transaction-api
```


### 2. Configure as variáveis de ambiente do DOCKER
*Na pasta Docker, crie o arquivo .env utilizando o .env.example como base. Modifique o valor das variáveis de acordo com a sua preferência.*

```bash
cp .env_example .env
```

*Obs: As portas dos containers são customizáveis, então fique a vontade para modifica-las, porém certifique-se de utilizar as portas definidas em locais como o .env do laravel (conexão com o Mysql), clients do banco de dados (Workbench, por exemplo) e clients de API (Insomnia ou Postman, por exemplo)*

### 3. No diretório Docker, execute o comando

```bash
docker-compose up --build
```


### 4. Instalando as dependências e configurando a API
*4.1 Instale as dependências do Laravel executando o comando*
```bash
docker exec php_transaction composer install
```


*4.2 Corriga as permissões dos diretórios, executando os comandos abaixo no diretório **api**:*

```bash
sudo chgrp -R www-data storage bootstrap/cache
```


```bash
sudo chmod -R ug+rwx storage bootstrap/cache
```


*4.3 Crie o arquivo .env do diretório **api**, utilizando o .env.example como base:*

```bash
cp .env_example .env
```


***Obs:** Certifique-se de informar as portas definidas no .env do docker, nas variáveis de conexão com o banco de dados (DB_HOST, DB_PORT, etc...) e na variável **APP_URL** (porta definida no container NGINX)*

*4.4 Gere a chave do projeto executando o comando na **raiz do projeto**:*

```bash
docker exec php_transaction php artisan key:generate
```

*4.5 Rode as migrations e Seeders executando o comando na **raiz do projeto**:*

```bash
docker exec php_transaction php artisan migrate:refresh --seed
```

## Banco dados

![modelagem do banco de dados](https://github.com/WenLopes/transaction-api/blob/master/docs/Modeling%20design.png?raw=true)

*(Imagem gerada a partir a funcionalidade "Designer" do Phpmyadmin, incluso no projeto)*

O banco de dados padrão da aplicação se chama **transaction** .

O Phpmyadmin está contido no projeto e configurado para acessar o servidor Mysql diretamente, sendo necessário informar apenas as credenciais para logar. Para acessá-lo, digite o IP configurado em seu container do Docker em seu navegador, sendo por padrão: **100.10.0.12** e logue com as credenciais definidas no .env do Docker. 

Se preferir utilizar outro Client, informe as credenciais passadas no .env do Docker e o IP padrão definido no container do Mysql nas configurações de conexão

* Host: **100.10.0.11** ( IPV4 definido nas configurações do container do mysql )

* Porta: **{MYSQL_PORT}** ( definido no .env do docker. Por padrão **8020** )

* User: **{MYSQL_USER} ou root** ( definidos respectivamente no .env do docker e nas configurações do container do Mysql. Por padrão **system** ou **root** )

* Password: **{MYSQL_USER} ou {MYSQL_ROOT_PASSWORD}** ( definidos no .env do docker. Por padrão **systempass** ou **rootpass** )

*Exemplo de preenchimento*
![exemplo de preenchimento](https://github.com/WenLopes/transaction-api/blob/master/docs/beekeeper%20studio%20example.png?raw=true)


## Utilização

### Fluxo
Antes de finalizar a transferência, um serviço autorizador deve ser consultado e posteriormente uma notificação deve ser enviada para o usuário, informando do sucesso/falha da execução.

Visando o atendimento dessas premissas, utilizei o [processamento de filas do laravel](https://laravel.com/docs/8.x/queues), para que essas tarefas sejam feitas em background.

As transações são criadas com status inicial *"WAITING"* e após a execução do Job responsável por seu processamento, seu status é modificado para *"SUCCESS"* ou *"ERROR"*. Em caso de sucesso, tanto o **payer** quanto o **payee** são notificados. Em caso de falha, o **payer** recebe a notificação contendo a informação do erro. 

As notificações também possuem status para representar seu estado atual, sendo eles *"WAITING"*, *"DISPATCHED"* (despachado para o serviço responsável pelo envio), *"ERROR"* (em caso de falha ao despachar).

### Processamento de fila

Para processamento da fila, é necessário executar o comando responsável por isso do Laravel. Para isso, execute o comando e o mantenha no console

```bash
docker exec php_transaction php artisan queue:work
```

### Payload

No diretório **docs**, contém arquivos para importação nos API clients [Insomnia](https://github.com/WenLopes/transaction-api/blob/master/docs/Insomnia%20-%20Transaction%20API) e [Postman](https://github.com/WenLopes/transaction-api/blob/master/docs/Postman%20-%20Transaction%20API.json). Faça o Download e importe em seu client de preferência

Ou se preferir, segue abaixo os Payload's para consumo das rotas criadas

**GET:** ```http://localhost:{NGINX_PORT}/api/transaction/{transactionId}```

**POST:** ```http://localhost:{NGINX_PORT}/api/transaction```

```json
{
    "value" : 100.00,
    "payer" : 4,
    "payee" : 15
}
```

## Executando testes

Na **raiz do projeto**, execute o comando:

```bash
docker exec php_transaction vendor/bin/phpunit
```

## Referências

* [Laravel 8](https://laravel.com/docs/8.x/releases)
* [Docker](https://www.docker.com/)
* [Clean code](https://github.com/jupeter/clean-code-php)
* [L5 Swagger](https://github.com/DarkaOnLine/L5-Swagger/wiki/Installation-&-Configuration)
* [Laravel permissions security]() 
* [Insomnia API CLient](https://insomnia.rest/download)
* [Postman API CLient](https://www.postman.com/)