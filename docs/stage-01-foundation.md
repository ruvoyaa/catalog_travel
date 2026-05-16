# Stage 01 — Foundation And Technical Launch

## Цель этапа

Подготовить техническую основу проекта и документационный baseline, достаточный для безопасного старта разработки.

## Что входит в этап

- инициализация monorepo-структуры;
- Docker baseline для backend, frontend и PostgreSQL;
- стартовый Laravel backend foundation;
- стартовый frontend foundation на Vue + Vike + Vite + Tailwind 4;
- фиксация master-spec и project rules;
- стартовая схема директорий и integration boundaries между backend и frontend.
- фиксация решения, что admin UI живёт внутри Laravel backend.

## Что не входит в этап

- реализация каталога туров;
- реализация админки туров;
- семантический поиск;
- LLM-генерация;
- production-grade CI/CD beyond minimal local launchability.

## Ожидаемый результат

- проект запускается локально через Docker;
- monorepo-структура не вызывает двусмысленности;
- backend и frontend skeleton готовы к следующему этапу;
- документация первого уровня согласована;
- зафиксировано архитектурное решение по placement admin UI.

## Основные риски

- размытая граница между Laravel backend и SSR frontend;
- избыточная сложность монорепо на старте;
- преждевременное проектирование AI/search runtime.
- риск смешения public SSR frontend и Laravel admin responsibilities.

## Проверки

- Docker environment поднимается;
- backend стартует;
- frontend SSR/dev baseline стартует;
- структура репозитория соответствует документации.
- решение по admin UI зафиксировано в docs.

## Фактический результат текущего прохода

- git-репозиторий и `origin` инициализированы;
- Laravel skeleton установлен в `apps/backend`;
- Vike + Vue + Tailwind skeleton установлен в `apps/frontend`;
- backend `.env` переведён на PostgreSQL baseline;
- backend `APP_KEY` сгенерирован;
- локальные build-проверки должны опираться на `docs/dev-setup.md`.
