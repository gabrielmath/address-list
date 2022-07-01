# Teste de Backend: Address List

Este foi um teste realizado a partir de um processo seletivo. O teste consiste na construção de um pequeno backend
(servindo como API) para cadastro de endereços.

## Enunciado

O mesmo possui duas partes distintas:

1. Importação das Cidades do seu estado
   [IBGE](https://servicodados.ibge.gov.br/api/docs/localidades#api-Municipios-estadosUFMunicipiosGet):
    - _Criar comunicação com a API do IBGE;_
    - _Criar um comando artisan para importar as cidades do seu estado;_
    - _Salvar as cidades na base de dados._
2. Criar uma API para o cadastro de endereços:
    - _Necessário endpoint's para as 4 operações: criar, atualiza, listar e excluir os endereços;_
    - _Os dados a serem salvos serão: logradouro, número, bairro e o ID da cidade;_
    - _É também necessário um endpoint para listar as cidades;_
    - _Realizar validação via FormRequest nos endpoint's de 'criar' e também no de 'atualizar'._

### Tecnologia Exigida

- Banco de Dados: livre escolha;
- Framework: Laravel;
- TDD será um diferencial.

## Ambiente Local Utilizado

No banco de dados resolvi utilizar o **MySQL**. Usei também o **NGINX** como servidor HTTP e utilizei
o **PHP em sua versão 8.1**. Usei o **Docker a partir do LaravelSail**,
que já trás um ambiente robusto e completamente configurado.
Rodei isso no **WSL2 com Ubuntu** e o docker instalado nele, nativamente.

## Instalação

Após clonar o projeto em seu aparelho, basta rodar o comando do composer para instalar as
dependências:

```bash
composer install
```

OBS: caso não tenha o composer em sua máquina, recomendo utilizar seu [container
docke](https://hub.docker.com/_/composer) para instalar as dependências do projeto.

Em seguida, faça uma cópia do arquivo `.env.example` para `.env` e configure seu ambiente de desenvolvimento como
banco de dados e etc.

E lembre-se também de gerar uma chave para aplicação:

```bash
sail artisan key:generate
```

Finalmente, para rodar a aplicação:

```bash
sail up -d
```

## Resumo da construção da aplicação/API

### Banco de Dados

No banco de dados, criei apenas 2 tabelas: _cities_ e _address_. Ambas mantém gravado o que foi solicitado no enunciado.
Por precaução, na tabela de cidade, além de armazenar o seu `ibge_id` e seu `nome`, tive o mesmo cuidado para salvar
ID, sigla e nome completo do estado onde a cidade está localizada. Por ser um teste rápido, optei por indexar tais
campos ao invés de criar uma outra tabela somente pra salvar os estados.

Após criar sua base de dados local e configura-la em seu `.env`, execute o comando:

```
sail artisan migrate
```

### Consumo da API externa

Conforme o enunciado solicita, criei uma camada em `\App\Integrations` para lidar com as requisições de APIs externas.
Apesar de ser apenas um teste, optei por tentar desacoplar o que fosse possível e não deixar as classes dependentes
umas das outras.

Para isso, ao criar a classe `\App\Integrations\IBGE\IBGEInstitute.php`, criei também uma Interface chamada
`InstituteInterface.php` para padronizar as chamadas de API caso futuramente apareça a necessidade de integração com
algum instituto semelhante. A partir daí, todas (ou quase todas) as chamadas foram realizadas a partir da Interface.

### Comando Artisan

Criei o comando para realizar a consulta na API e salvar as cidades no banco de dados. Logo alí pensei em
uma forma de facilitar o uso para a pessoa que estiver usando. Por que não permitir que quem esteja testando
escolha quais cidades (a partir do IBGE_ID do Estado) cadastrar? E por que não deixá-la cadastrar cidades de mais de um
Estado (se necessário)? E se essa pessoa quiser limpar o banco e seus endereços na hora de rodar o comando artisan?

Pensando nisso, o comando criado permite passar (ou não, caso não queira) um ou mais `IBGE_ID` dos Estados. Caso decida
deixá-lo em branco, ele adotará por padrão o ID do Estado do Paraná e preencherá o banco de dados com suas cidades.

Comando simplificado (o IBGE_ID padrão é o 41, do Paraná):

```bash
sail artisan cities:add-from-api
```

Comando com um array de IDs

```bash
sail artisan cities:add-from-api 41 35    # 41 = PR, 35 = SP 
```

Caso a pessoa digite um IBGE_ID errado estando dentro do array, o comando salva as cidades do ID que estiver correto e
somente ao final avisa quais IDs estão incorretos, trazendo consigo uma listagem no console + um link de acesso a mesma
listagem -> um endpoint retornando um json dos Estados.

Comando com um ou mais ID incorreto:

```bash
sail artisan cities:add-from-api 100 35 11 150    # IDs 100 e 150 não existem
```

Caso tenha algum ID passado anteriormente, o sistema evita um novo cadastro na base. Caso decida rodar o comando para
limpar toda a base e cadastrar as cidades novamente, funciona.

Para acessá-lo, basta rodar:

```bash
sail artisan cities:add-from-api 35 --fresh    # Com a opção --fresh, o DB será apagado e as cidades da UF 35 salvas novamente
```

### Endpoints - CRUD de Endereços

Abaixo, colocarei as rotas nomeadas. Porém, lembre-se: antes de iniciar o cadastro de endereços,
certifique-se de que a tabelas de cidades esteja populada e que o ID passado via parâmetro exista no banco.

```php
GET|HEAD        api/v1/addresses ........................... api.v1.addresses.index › Api\V1\AddressController@index
POST            api/v1/addresses ........................... api.v1.addresses.store › Api\V1\AddressController@store
GET|HEAD        api/v1/addresses/{address} ................... api.v1.addresses.show › Api\V1\AddressController@show
PUT|PATCH       api/v1/addresses/{address} ............... api.v1.addresses.update › Api\V1\AddressController@update
DELETE          api/v1/addresses/{address} ............. api.v1.addresses.destroy › Api\V1\AddressController@destroy
GET|HEAD        api/v1/uf .............................................. api.v1.uf.index › Api\V1\UfController@index
GET|HEAD        api/v1/uf/{ufIbgeId}/cities ...................... api.v1.cities.index › Api\V1\CityController@index
```

Os parâmetros enviados nas rotas de POST e PUT são:
`cidade_id, logradouro, numero, bairro`.

A não ser o envio do formulário e o JSON de resposta, todos o restante do sistema está em inglês. O que não está em
inglês serve somente pra enviar/exibir algum dado na tela para usuários leigos.

O formRequest está validando não somente se os campos estão preenchidos, como também a quantidade mínima de caracteres,
quantidade máxima e no caso do ID da cidade, há uma verificação via banco de dados.

Também foi padronizado o json de resposta a partir das classes de Resources.

Os controllers ficaram extremamente enxutos e assertivos.

### TDD

Por fim, chegamos aos Testes Automatizados. Apesar não ter tanta experiência, consegui rodá-los. Os primeiros
métodos (testes) que escrevi já estavam com seu código funcionando. Porém, ao chegar no CRUD,
priorizei a metodologia do TDD e fui, pouco a pouco, escrevendo o teste, imaginando como eu deveria executar o código e,
somente depois, escrevi o código a ser testado de fato.

Provavelmente não é o melhor TDD já visto, mas consegui entregá-lo. O importante aqui foi eu ter me desafiado e mesmo
"aos trancos e barrancos" ter conseguido.

Caso queira rodar os arquivos individualmente:

```bash
sail test tests/Feature/Integrations/IBGE/IBGEInstituteTest.php
sail test tests/Feature/Console/Commands/IBGE/AddCitiesFromApiTest.php
sail test tests/Feature/Http/Controllers/Api/V1/AddressControllerTest.php
```

Os separei de acordo com seu namespace porque facilita a leitura e também é mais fácil de identificar qual classe
está sendo testada.

Enfim, espero ter conseguido passar um pouco da ideia que tive pra solucionar o problema e como me organizei, como
estruturei o código para deixá-lo melhor manutenível, com fácil compreensão e rápida leitura.

### Obrigado!

