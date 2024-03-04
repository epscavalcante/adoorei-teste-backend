

# Setup application

Entre no diretório .docker e siga as a seguir.


#### Levantar os containes (laravel, nginx e mysql):
```docker compose up -d```

#### Define o arquivo .env
```docker compose exec app cp .env.example .env```

#### Instale as dependências
```docker compose exec app composer install```

#### [Opcional] Rode os testes:
```docker compose exec app php artisan test```

Adicione o ```--coverage``` no final do comando acima para ver a % de cobertura do teste.

### Api documentation
Acesse a url http://127.0.0.1:8000/api/docs para acessar a documentação da api com swagger ou [faça o download do arquivo](https://github.com/epscavalcante/adoorei-teste-backend/blob/main/adoorei-api-doc.json) e importe-o no Postman ou Insomnia.
