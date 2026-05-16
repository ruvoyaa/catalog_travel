# Stage 03 — Semantic Search And LLM Generation

## Цель этапа

Добавить semantic search и ручной LLM-assisted workflow генерации тура в админке.

## Что входит в этап

- выбор и подключение легковесной Hugging Face модели для embeddings;
- pipeline расчёта embeddings для туров;
- хранение embedding data в согласованной форме;
- semantic retrieval or ranking flow для публичного поиска;
- ручной запуск LLM-генерации из админки;
- сохранение generated draft и возможность ручной доработки.

## Что не входит в этап

- полноценная observability-панель для индексации;
- сложная очередевая AI-оркестрация;
- fully automated publishing;
- расширенная аналитика поисковых запросов.

## Ожидаемый результат

- пользователь получает работающий semantic search в каталоге;
- администратор может вручную запустить генерацию контента тура;
- generated result можно использовать как черновик, не теряя ручной контроль.

## Основные риски

- слишком тяжёлая embedding-модель для dev/runtime baseline;
- неудачная схема хранения embeddings в PostgreSQL;
- смешение LLM draft workflow и основной публикационной модели.

## Проверки

- расчёт embeddings воспроизводим;
- semantic search возвращает релевантный результат на базовых сценариях;
- ручной AI workflow не ломает admin CRUD и public output.

## Стартовая точка этапа

Stage 03 стартует поверх уже собранного Stage 02 baseline:

- tour domain model и admin CRUD уже существуют;
- public catalog API и SSR frontend уже существуют;
- текущая задача этапа не в перестройке каталога, а в добавлении AI/search capabilities поверх существующего контракта.

Практический приоритет старта:

1. зафиксировать схему хранения embeddings;
2. выбрать минимальный reproducible embedding pipeline;
3. встроить semantic retrieval в публичный поиск без поломки текущих фильтров;
4. отдельно встроить ручной LLM draft workflow в admin UI.

## MVP baseline

В текущем тестовом проекте Stage 03 реализован в pragmatic MVP-форме:

- embeddings хранятся в `tour_embeddings`;
- используется локальный deterministic embedding provider вместо внешней HF-модели;
- semantic search подключён к существующему `/api/tours` как semantic ranking поверх текущего каталога;
- ручной draft workflow реализован через `llm_generation_artifacts`;
- draft generation для MVP выполняется локальным template-based provider.

Это сознательное упрощение:

- контракт и точки интеграции для search/AI уже существуют;
- later swap на реальные HF embeddings и внешний LLM provider возможен без перестройки Stage 02 core;
- для MVP не добавляются очереди, фоновые воркеры и сложная orchestration-логика.
