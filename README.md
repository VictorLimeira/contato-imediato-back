# Sobre
Api para salvar contatos (Contact) e seus meios de comunicação (Medium)

# Prerequisitos:
- Entendimento de php, [laravel](https://laravel.com/docs/10.x) e comandos simples do docker;
- Instalados:
    1. Docker;
    2. Docker compose.
- Obs: Consulte a documentação Laravel para aprofundamente caso necessário, comandos básicos abaixo.

## 1. Levante a aplicação com o sail
>``$ ./vendor/bin/sail up ``

## 2. Rode os testes com cobertura
>``$ ./vendor/bin/sail phpunit --coverage-text ``

## Liste as rotas
>``$ ./vendor/bin/sail php artisan route:list ``

Contate o mantenedor para receber exemplos de utilização exportados em [.har](https://en.wikipedia.org/wiki/HAR_(file_format)).
