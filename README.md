# Smart Challenge - API PHP Laravel 6
API para busca e filtragem de Customers em um arquivo JSON local

## Tecnologias utilizadas
 - Linguagem em PHP 7, pela familiaridade e performance
 - Laravel 6, pela familiaridade com o framework
 - Nginx como servidor web, por sua performance e fácil configuração
 - PHP Unit para testes, por ser a referência em testes no PHP
 - Docker, por sua simplicidade para outras pessoas desenvolverem e por deploy ágil
 
## Observação
A princípio este pacote está configurado para utilizar a porta 8383. Tenha certeza de que ela esteja liberada no firewall.

## Como rodar localmente
Pré-requisitos: possuir o Git, Docker e Docker Composer instalados

 - 1º passo: clonar este repositório
 - 2º passo: build da imagem Docker. Entrar no diretório contendo o Dockerfile e executar o comando:
 ```
 docker-compose build app
```

 - 3º passo: Subir o contâiner em background
 ```
docker-compose up -d
```

 - 4º passo (opcional): em caso de ambiente Linux ou MacOS, alterar as permissões da pasta src/ e seu conteúdo, para possuir leitura e escrita
 - 5º passo: Instalar as dependências do Laravel
```
docker-compose exec app composer install
```

 - 6º passo: acessar o endereço http://localhost:8383/api/Customers
 
## Como desenvolver localmente
Não é necessário nenhum software adicional para desenvolvimento local, os arquivos são sincronizados com o Docker e nele executados, necessitando apenas da IDE.

## Como rodar os testes
Para executar os testes do PHP Unit, é necessário acessar o ambiente docker e executar o seguinte comando:
```
./vendor/bin/phpunit
```

## Como fazer o deploy
O deploy é feito da mesma maneira para rodar localmente, por meio do Docker.

## Dificuldades
A maior dificuldade foi a configuração correta do Docker para desenvolvimento local e deploy sem alteração de configurações.

## Melhorias para o futuro
 - Implementar banco de dados (MySQL) como alternativa ao JSON
 - Autenticação por usuário, incluindo JWT
 - Implementação de cache