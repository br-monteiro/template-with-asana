#!/bin/bash

echo "Iniciando setup"

# Diretório atual
root_dir=$(pwd)

# Executando instalação via composer
composer install

# Setando permissão de execução ao arquivo
sudo chmod +x $root_dir"/index.php"

# Criando link simbólico para o executável
sudo ln -s $root_dir"/index.php" /usr/bin/asana

echo "Setup finalizado com sucesso! =)"
echo "Execute o comando:"
echo "asana hello"
