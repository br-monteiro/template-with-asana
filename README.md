### Gerador de Descrição de MR e PR
Este projeto foi pensado para auxiliar o time de ATD da Chaordic Systems na criação e padronização de Descrição de __Merge Request__ ou __Pull Request__.

### Dependências - uso
- PHP 7.0+
- Composer

### Dependências - desenvolvimento
- PHP 7.0+
- Composer
- asana/asana 0.5.1+
- splitbrain/php-cli 1.1+

### Instalação
Para instalar o projeto na sua máquina, basta realizar o clone do repositório. Abaixo é possível visualizar o comando de clone do repositório:

```bash
cd ~/ && git clone git@github.com:br-monteiro/template-with-asana.git
```

Após clonar o projeto, entre no diretório `tamplate-with-asana` e execute o arquivo de setup do projeto. Abaixo é possível visualizar o comando de execução do arquivo de setup:

```bash
cd ~/template-with-asana && bash ./setup.sh
```

>__IMPORTANTE__: Será solicitado a senha de root do Sistema Operacional, mas não se preocupe, esta solicitação servirá apenas para setar permissão de execução (`+x`) e criação do _link simbólico_ (`ln -s`) para o arquivo `index.php`.

### Gerando um Token de Acesso Pessoal
Para que o sistema funcione corretamente, é necessário ter um Token de Acesso Pessoal. Esse token será usado para consultar os dados das tasks criadas no Asana. Para gerar o token, siga a documentação oficial da API do Asana disponível em https://asana.com/pt/guide/help/api/api

>__IMPORTANTE__: O Token gerado deve ser do tipo __PERSONAL ACCESS TOKEN__.

### Registrando o Token no Sistema
O registro do token pode ser feito usando o comando abaixo

```bash
asana token <token-gerado>
```

Se tudo ocorrer como o esperado, a mensagem __Token successfully set__ será exibida. Exemplo de execução do comando __token__:

```bash
asana token 0/46a1d3f0c4da375c36a9a45479209307
```

### Gerando as Descrições para PR ou MR
O fluxo de geração de descrição é bem simples, e pode ser feito com o mando __make__. A sintaxe do comando pode ser observada abaixo:

```bash
asana make [-m|--mr] [template-name] <link-da-task>
```

Explicando a sintaxe:

1. __\[-m|--mr]__ Indica que a descrição que queremos gerar será para um Merge Request (Gitlab). Por padrão o sistema gera descrições para Pull Request (Github). __Este parâmetro é obrigatório apenas para MRs__.
2. __\[template-name]__ Indica qual o template queremos usar na geração das descrições. Por padrão o comando __make__ usa o template principal (`template.md`). É possível criar seus próprios templates e usar normalmente com o comando __make__, para isso basta salvar os seus templates personalizados no diretório `~/tamplate-with-asana/templates/` com extensão `*.md`. __Este parâmetro não é obrigatório__.
3. __\<link-da-task>__ Indica qual task será usada para extração de informações. __Este parâmetro é obrigatório__.

Exemplo de execução do comando __make__:

##### Pull Request

```bash
asana make https://app.asana.com/0/24457451196652/567808915565777
```

##### Merge Request

```bash
asana make --mr https://app.asana.com/0/24457451196652/567808915565777
```

>__IMPORTANTE__: No decorrer da execução deste comando, você será indagado com algumas questões importantes para construção da descrição. Para sair do modo de inserção de texto basta digitar `\ok!` e pressionar __enter__.

1. Primeiro será perguntado qual a solução usada para resolver a task;
2. Depois será perguntado se há alguma observação para a task; e
3. Por ultimo será perguntado algumas palavras importantes que caracterizam a task. __Caso haja palavras importantes, você deve inserir UMA por linha__.

Ao final da execução será impresso no terminal a descrição gerada para o MR ou PR. O resultado se parece com o seguinte:

```markdown
#### Asana task
https://app.asana.com/0/24457451196652/567808915565777
## Solution
Alterado os endereços que estavam setados como HTTP para Free Protocol
#### Observation
Os endereços que já estavam setados como HTTPS foram alterados para Free Protocol

[https](https://github.com/search?utf8=✓&q=org%3Achaordic+https&type=issues),
[http](https://github.com/search?utf8=✓&q=org%3Achaordic+http&type=issues),
[OnSite](https://github.com/search?utf8=✓&q=org%3Achaordic+OnSite&type=issues)
```

### Créditos

Este projeto foi desenvolvido por Edson B S Monteiro - <bruno.monteirodg@gmail.com> em uma distribuição Linux. __\o/__

## LAUS DEO ∴