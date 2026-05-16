# Monorepo Layout

## Цель

Зафиксировать базовую структуру монорепозитория проекта `catalog_travel`.

## Структура

- `apps/backend` — Laravel backend
- `apps/frontend` — Vue + Vike + Vite SSR frontend
- `infra/docker` — Dockerfiles
- `docs` — проектная документация
- `.codex` — локальный контекст и служебные материалы Codex
- `inbox` — входящие артефакты проекта

## Границы ответственности

### Backend

- API и серверная бизнес-логика
- работа с PostgreSQL
- admin workflows
- Laravel-based admin UI
- integration points для semantic search и LLM flows

### Frontend

- SSR публичной части
- UI каталога
- UI карточки тура
- фильтры
- поиск
- без admin UI

## Зафиксированное решение

- админка реализуется внутри Laravel backend;
- `apps/frontend` используется только для публичной SSR-части;
- backend/frontend граница проходит между public SSR UI и server-side admin workflows.
