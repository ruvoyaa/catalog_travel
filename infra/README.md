# Infrastructure

Инфраструктурные артефакты проекта.

## Состав

- `docker/` — Dockerfiles для backend и frontend;
- `docker-compose.yml` в корне — локальный orchestration baseline.

## Принцип

На текущем этапе инфраструктура минимальна и нужна только для:

- локального запуска;
- фиксации monorepo-границ;
- согласованного dev baseline для backend, frontend и PostgreSQL.
