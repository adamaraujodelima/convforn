# API - CONVFORN - Integração de empresas e fornecedores

## Escopo
Aplicação em Docker com API desenvolvida em Laravel Framework usando Laravel Passport para autenticação, cache via Redis, Mysql e MailDev para testar e-mails com objetivo de demonstrar o cadastro e gerenciamento  do cadastro de empresa e pagamentos mensais dos seus respectivos fornecedores de forma simples via API REST FULL. 

## Requisitos

Docker (https://www.docker.com/) + docker-compose (https://docs.docker.com/compose/) instalado e configurado para rodar o container.

## Como instalar?

- Clone a aplicação para seu diretório: git clone https://github.com/adamaraujodelima/convforn.git
- Acesse a pasta criada e execute  o arquivo run.sh via terminal: ./run.sh
- Aguarde a inicialização do container Docker e a instalação e configuração da aplicação em Laravel
- Após finalizado, acesse http://localhost para ver a aplicação rodando.
- Obs: Caso esteja rodando algum serviço nas portas 80, 1080, 1025 e 3306, você deverá parar estes serviços para que o container do Docker possa inicializar e rodar corretamente.

## Criando Usuário e o TOKEN de acesso para API

- Acesse a aplicação no seu navegador via http://localhost em crie sua conta de usuário.
![Alt text](https://imagizer.imageshack.com/v2/1135x584q90/921/CYw9dD.png?raw=true "Novo usuário")

- Após criar sua conta, será necessário ativar a conta no email de verificação. Acesse http://localhost:1080 para visualizar a caixa postal emulada de e-mails:
![Alt text](https://imagizer.imageshack.com/v2/1135x617q90/923/XJWyKJ.png?raw=true "Mail Dev")

- Uma vez a conta ativada, acesse aplicações para gerar uma nova aplicação que terá permissão para acessar a API via REST FULL:
![Alt text](https://imagizer.imageshack.com/v2/1135x584q90/921/rQbS0B.png?raw=true "Aplicações")

- Após criar a aplicação, copie o ID e Chave para um local que desejar e clique em Autorizar para gerar o TOKEN de acesso que será utilizado nas chamadas dos endpoints da API:
![Alt text](https://imagizer.imageshack.com/v2/1135x584q90/924/GcUBaV.png?raw=true "Autorizar Aplicação")

- Informe o ID e Chave copiadas e clique em Generate:
![Alt text](https://imagizer.imageshack.com/v2/1135x584q90/923/x5q8K5.png?raw=true "FORM TOKEN")

- Salve o ACESS TOKEN e o REFRESH TOKEN gerado em um local que desejar e o utilize nas chamadas da API:
![Alt text](https://imagizer.imageshack.com/v2/1135x584q90/923/UnnkN2.png?raw=true "ACESSS TOKEN")

- Uma vez gerado o ACESS TOKEN, sua aplicação deve estar no Dashboard como autorizada
![Alt text](https://imagizer.imageshack.com/v2/1135x584q90/923/2surxr.png?raw=true "ACESSS TOKEN")

# Endpoints

## Empresa

- GET http://localhost/api/company/info -> Retorna as informações da empresa
- GET http://localhost/api/company/totalMonthPayments -> Retorna o total de mensalidades cadastrados da empresa
- PUT http://localhost/api/company/edit -> Atualiza os dados da empresa
    - Campos disponíveis:
        - name (string)                
        - cnpj (string)                
        - postcode (string)                
        - address (string)                
        - number (string)                
        - neighborhood (string)                
        - city (string)                
        - state (string)                
- POST http://localhost/api/company/register -> Cria uma empresa associada ao usuário da API
    - Campos disponíveis:
        - name (string)                
        - cnpj (string)                
        - postcode (string)                
        - address (string)                
        - number (string)                
        - neighborhood (string)                
        - city (string)                
        - state (string)                

## Fornecedor

- GET http://localhost/api/manufacturer/list -> Lista os fornecedores associados a empresa do usuário da API
- GET http://localhost/api/manufacturer/info/{id} -> Retorna os dados do fornecedor
- POST http://localhost/api/manufacturer/register -> Cria um fornecedor associado a empresa do usuário da API
    - Campos disponíveis:
        - name (string)
        - email (string)
        - month_payment (double)
- PUT http://localhost/api/manufacturer/edit/{id} -> Atualiza os dados do fornecedor
    - Campos disponíveis:
        - name (string)
        - email (string)
        - month_payment (double)
- DELETE http://localhost/api/manufacturer/remove/{id} -> Remove um fornecedor

## Como Testar?

- Para poder usar todos os recursos, o usuário da API deve antes de mais nada, chamar o ENDPOINT de registro de empresa para que a empresa seja criada e associada ao usuário da API e então poder lançar os fornecedores.
    - Exemplo:
        - Endpoint: http://localhost/api/company/register
        - Method: POST
        - Data: [{"key":"name","value":"Empresa Teste Corporações Operativas","description":""},{"key":"cnpj","value":"34.398.979/0001-10","description":""},{"key":"postcode","value":"88220-000","description":""},{"key":"address","value":"Rua 402","description":""},{"key":"number","value":"997","description":""},{"key":"neighborhood","value":"Morretes","description":""},{"key":"city","value":"Itapema","description":""},{"key":"state","value":"SC","description":""}]
        - Content-type: application/json
        - Authorization: Bearer [ACESS TOKEN]
- Em sua aplicação faça as chamadas ao ENDPOINTs disponíveis passando o ACESS TOKEN gerado no HEADER.
    - Exemplo:
        - Endpoint: http://localhost/api/manufacturer/list
        - Content-type: application/json
        - Authorization: Bearer [ACESS TOKEN]

- Você pode utilizar o POSTMAN (https://www.getpostman.com/) para testar as chamadas e o comportamento da API:
![Alt text](https://imagizer.imageshack.com/v2/986x601q90/923/6b5uj3.png?raw=true "ACESSS TOKEN")

## Obtendo novo TOKEN

- Os tokens gerados são válidos apenas nas próximas 24 horas e o REFRESH TOKEN por 30 dias. Sendo assim, você deve solicitar um novo token válido para continuar acessando a API. Utilize o método abaixo para usar o REFRESH TOKEN e obter novo token:
    - Endpoint: http://localhost/oauth/token
    - Method: POST
    - Headers: Content-type: application/json
    - Data: {
        'grant_type': 'refresh_token',
        'refresh_token': 'the-refresh-token',
        'client_id': 'client-id',
        'client_secret': 'client-secret',
        'scope': ''
    }
        

