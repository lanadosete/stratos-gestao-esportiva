# Stratos — Gestão Esportiva

Sistema web para gestão de arenas esportivas e agendamento de quadras. Permite que donos de arena administrem suas quadras, horários de funcionamento, preços e equipe, enquanto clientes reservam horários para jogar e a recepção acompanha o movimento do dia.

Desenvolvido com [Laravel](https://laravel.com) 13, Blade e Tailwind CSS.

## Autores

- Lana Lourrani
- Luís Felipe
- Leonardo Davi

## Sumário

- [Visão geral](#visão-geral)
- [Funcionalidades](#funcionalidades)
- [Perfis de usuário](#perfis-de-usuário)
- [Requisitos funcionais](#requisitos-funcionais)
- [Requisitos não funcionais](#requisitos-não-funcionais)
- [Tecnologias](#tecnologias)
- [Como rodar o projeto](#como-rodar-o-projeto)
  - [Opção 1: ambiente local (PHP + MySQL/WAMP)](#opção-1-ambiente-local-php--mysqlwamp)
  - [Opção 2: Docker](#opção-2-docker)
- [Testes](#testes)
- [Estrutura do projeto](#estrutura-do-projeto)

## Visão geral

O Stratos conecta donos de arenas esportivas (quadras de vôlei, beach tennis, futevôlei, etc.) a jogadores que querem reservar um horário. Cada arena pode ter múltiplas quadras, cada quadra pode oferecer múltiplos esportes com preços por turno, e o sistema calcula automaticamente disponibilidade de horários com base no funcionamento cadastrado e nas reservas já feitas.

## Funcionalidades

- **Agendamento público**: qualquer visitante pode buscar arenas, ver quadras, esportes e preços disponíveis sem precisar de login.
- **Reserva de horário**: cliente logado escolhe quadra, data, horário e esporte, e finaliza o agendamento com forma de pagamento.
- **Painel do cliente**: histórico de agendamentos, com opção de cancelar e contatar a arena via WhatsApp.
- **Painel administrativo (dono da arena)**:
  - Dashboard com status de funcionamento (aberta/fechada) e total de reservas.
  - Cadastro e edição da arena (nome, endereço, telefone, horários de funcionamento).
  - Gestão de quadras (esportes oferecidos, preços por turno).
  - Gestão de equipe (cadastro de funcionários vinculados à arena).
  - Relatório financeiro.
  - Agenda com visão dos dias que têm reservas e detalhe das reservas do dia.
- **Painel da recepção (funcionário)**: visão dos jogos do dia na arena vinculada, confirmação de pagamento recebido, contato com o cliente via WhatsApp.
- **Status de reserva calculado automaticamente por horário**: a iniciar, em jogo, finalizado ou cancelado.

## Perfis de usuário

O sistema tem três tipos de conta (`tipo_conta`):

| Perfil | Acesso |
|---|---|
| `cliente` | Área principal do site: busca arenas, reserva quadras, acompanha seus agendamentos. |
| `admin` | Dono da arena. Painel administrativo completo (`/admin/*`), agenda e recepção da própria arena. |
| `funcionario` | Cadastrado por um admin e vinculado a uma arena. Acesso à recepção e agenda da arena vinculada. |

Login de cliente e login administrativo (`admin`/`funcionario`) são feitos em telas separadas (`/login` e `/login/administrativo`), cada uma bloqueando o perfil que não pertence àquela área.

## Requisitos funcionais

- RF01 — O sistema deve permitir cadastro e autenticação de clientes, administradores e funcionários.
- RF02 — O sistema deve permitir que um administrador cadastre e edite os dados de sua arena (nome, endereço, telefone).
- RF03 — O sistema deve permitir que um administrador configure os horários de funcionamento da arena por dia da semana.
- RF04 — O sistema deve permitir que um administrador cadastre, edite e exclua quadras, associando esportes e preços por turno a cada quadra.
- RF05 — O sistema deve permitir que um administrador cadastre, edite e remova funcionários vinculados à sua arena.
- RF06 — O sistema deve permitir que qualquer visitante (sem login) busque arenas e visualize quadras, esportes e preços.
- RF07 — O sistema deve exigir login de cliente para finalizar uma reserva.
- RF08 — O sistema deve calcular os horários disponíveis de uma quadra em uma data, considerando o funcionamento da arena e as reservas já existentes.
- RF09 — O sistema não deve permitir reservas em horários já ocupados ou já passados no dia atual.
- RF10 — O sistema deve permitir que o cliente cancele uma reserva.
- RF11 — O sistema deve permitir que administradores e funcionários registrem reservas em nome de um cliente (ou sem cliente cadastrado) diretamente pela recepção.
- RF12 — O sistema deve permitir à recepção confirmar o pagamento de uma reserva.
- RF13 — O sistema deve calcular automaticamente o status de uma reserva (a iniciar, em jogo, finalizado, cancelado) com base no horário atual.
- RF14 — O sistema deve gerar um link do WhatsApp para contato entre arena e cliente a partir do telefone cadastrado.
- RF15 — O sistema deve exibir ao administrador um relatório financeiro e um dashboard com o status de funcionamento da arena e total de reservas.
- RF16 — O sistema deve exibir uma agenda com os dias que possuem reservas e o detalhe das reservas de um dia selecionado.

## Requisitos não funcionais

- RNF01 — **Localização**: interface e mensagens em português (pt-BR).
- RNF02 — **Segurança**: senhas armazenadas com hash (bcrypt) e nunca expostas na aplicação; cada perfil só acessa as rotas e dados permitidos ao seu tipo de conta.
- RNF03 — **Isolamento por arena**: dados de quadras, equipe, reservas e agenda de uma arena não podem ser acessados por usuários de outra arena.
- RNF04 — **Persistência**: dados armazenados em banco relacional MySQL via Eloquent ORM, com integridade referencial garantida por chaves estrangeiras.
- RNF05 — **Portabilidade**: o ambiente de desenvolvimento deve poder ser executado tanto localmente (PHP + WAMP/MySQL) quanto via Docker, sem alterações no código.
- RNF06 — **Manutenibilidade**: cobertura de testes automatizados (PHPUnit) para os principais fluxos de autenticação, agendamento, escopo por arena e cálculo de status.
- RNF07 — **Usabilidade**: interface responsiva construída com Tailwind CSS.
- RNF08 — **Performance de build**: assets front-end compilados via Vite, com hot-reload em ambiente de desenvolvimento.

## Tecnologias

- **Backend**: PHP 8.3+, Laravel 13
- **Frontend**: Blade, Tailwind CSS 4, Vite
- **Banco de dados**: MySQL 8
- **Testes**: PHPUnit
- **Containerização**: Docker / Docker Compose (opcional)

## Como rodar o projeto

### Opção 1: ambiente local (PHP + MySQL/WAMP)

**Pré-requisitos**: PHP 8.3+, Composer, Node.js 22+, MySQL (ex.: via WAMP).

```bash
# 1. Clonar o repositório
git clone https://github.com/lanadosete/stratos-gestao-esportiva.git
cd stratos-gestao-esportiva

# 2. Instalar dependências PHP
composer install

# 3. Copiar o arquivo de ambiente e gerar a chave da aplicação
cp .env.example .env
php artisan key:generate

# 4. Configurar o banco no .env (ajuste conforme seu MySQL local)
#    DB_HOST=127.0.0.1 (ou localhost)
#    DB_PORT=3306
#    DB_DATABASE=stratos
#    DB_USERNAME=root
#    DB_PASSWORD=

# 5. Criar o banco "stratos" no MySQL e rodar as migrations
php artisan migrate

# 6. Instalar dependências JS e compilar os assets
npm install
npm run build   # ou `npm run dev` durante o desenvolvimento

# 7. Subir o servidor
php artisan serve
```

A aplicação estará disponível em `http://localhost:8000`.

> Também é possível usar `composer run dev`, que sobe o servidor PHP, o listener da fila, os logs (`pail`) e o Vite ao mesmo tempo.

### Opção 2: Docker

**Pré-requisitos**: Docker e Docker Compose.

```bash
# 1. Clonar o repositório
git clone https://github.com/lanadosete/stratos-gestao-esportiva.git
cd stratos-gestao-esportiva

# 2. Copiar o arquivo de ambiente
cp .env.example .env

# 3. Subir os containers (app, webserver Nginx, MySQL e Node/Vite)
docker compose up -d

# 4. Instalar dependências, gerar chave e rodar migrations dentro do container
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
```

A aplicação estará disponível em `http://localhost:8000` (porta configurável via `APP_PORT` no `.env`) e o Vite em `http://localhost:5173` (`VITE_PORT`).

## Testes

```bash
php artisan test
```

Os testes cobrem, entre outros: separação de login por perfil, escopo de dados por arena (recepção e equipe), fluxo de agendamento por perfil (visitante, cliente, admin, funcionário), status calculado da reserva, bloqueio de horários passados e exibição de contato via WhatsApp.

## Estrutura do projeto

```
app/
  Http/Controllers/   # AuthController, AgendaController, ArenaController, QuadraController, EquipeController, FinanceiroController, ReservaController
  Models/             # User, Arena, Quadra, QuadraEsporte, QuadraPrecoTurno, ArenaFuncionamento, Reserva
database/migrations/  # Schema do banco (arenas, quadras, reservas, funcionamento, preços por turno)
resources/views/      # Blade templates (admin, agendamento, cliente, recepção, auth)
routes/web.php        # Rotas públicas, de agendamento e protegidas por perfil
tests/Feature/        # Testes de fluxo end-to-end (PHPUnit)
docker/               # Dockerfile (PHP) e configuração do Nginx
```
