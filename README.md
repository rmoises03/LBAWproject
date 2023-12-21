# lbaw2382

## NEWS4U

## Description

A plataforma NEWS4U está a ser desenvolvido por alunos de engenharia informática da FEUP, no âmbito da unidade curricular de Laboratório de Bases de Dados e Aplicações Web, como um produto cujo o público alvo será qualquer utilizador que partilhe interesse em publicar ou apenas visualizar notícias.
O objetivo principal do projeto é desenvolver uma plataforma que permita que os usuários possam participar ativamente no website. Será possível publicar notícias, visualizá-las, interagir e comentar com outras publicações e estabelecer amizade entre utilizadores de forma a poder filtrar o conteúdo que visualiza. O elemento diferenciador será a possibilidade do utilizador comum poder publicar também notícias ao invés de ser apenas um mero espectador.
A plataforma terá dois tipos de utilizadores autenticados, o administrador e o utilizador comum. Para além disso será possível utilizar a plataforma, de forma limitada, sem se autenticar.
O utilizador não autenticado poderá registar-se na plataforma, ver o feed das notícias mais vistas, ver o feed das notícias mais recentes, ver comentários e pesquisar também por notícias ou comentários específicos.
O utilizador autenticado poderá, para além das funcionalidades básicas, fazer o login/logout, recuperar a palavra-passe, apagar a sua conta, ver o seu feed de notícias, ver detalhes sobre cada notícia e comentário, criar notícias, votar e comentar em notícias e comentários, ver perfis de outros utilizadores assim como a sua reputação e poderão também seguir utilizadores ou tags.
O administrador terá a capacidade de bloquear ou desbloquear contas de usuários e também terá a capacidade de eliminar contas de utilizadores específicos.

## Installation

> https://git.fe.up.pt/lbaw/lbaw2324/lbaw2382/-/releases/PA

> docker run -it -p 8000:80 --name=lbaw2382 -e DB_DATABASE="lbaw2382" -e DB_SCHEMA="lbaw2382" -e DB_USERNAME="lbaw2382" -e DB_PASSWORD="rcpSWcHJ" git.fe.up.pt:5050/lbaw/lbaw2324/lbaw2382

## Usage

> http://lbaw2382.lbaw.fe.up.pt/

#### Administration Credentials

| Email | Password |
|-------|----------|
| admin@example.com | 1234 |
| jane_smith@example.com | 0000 |

#### User Credentials

| Email | Password |
|-------|----------|
| john@admin.com | 0000 |


## Authors

- Nelson Campos, up202005083@up.pt
- Ntsay Zacarias, up202008863@up.pt
- Rodrigo Ribeiro, up202108679@up.pt
- Rui Teixeira, up202103345@up.pt

