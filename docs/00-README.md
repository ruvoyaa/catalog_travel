# Catalog Travel Development System

Этот документ фиксирует стартовую модель работы по проекту `catalog_travel`.

## Что это за проект

`catalog_travel` — это веб-приложение с:

- публичным каталогом туров;
- карточками туров;
- базовыми фильтрами;
- семантическим поиском;
- административной частью;
- ручным запуском LLM-генерации контента тура.

Функционал бронирования в scope проекта не входит.

## Стартовый принцип

Первый этап проекта — не код приложения, а документационный foundation:

- зафиксировать границы;
- зафиксировать master-spec;
- разбить проект на 3-4 крупных этапа;
- подготовить ТЗ на каждый этап.

После этого уже переходить к реализации.

## Модель процесса

Для проекта используется stage-based подход, но в компактной форме:

1. analysis
2. implementation
3. review
4. fixes if needed

Проект не должен расползаться на большое число мелких этапов без необходимости.

## Текущая декомпозиция

На старте зафиксированы 4 крупных этапа:

1. foundation и технический запуск проекта;
2. core catalog и admin foundation;
3. semantic search и LLM-assisted content generation;
4. stabilization, SSR polish и release readiness.

## Обязательные артефакты

На текущем уровне проекта обязательны:

- `docs/project-master-spec.md`
- `docs/stage-01-foundation.md`
- `docs/stage-02-catalog-admin-core.md`
- `docs/stage-03-semantic-search-and-llm.md`
- `docs/stage-04-stabilization-and-release.md`
- `docs/admin/00-README.md`

## Ограничения

- не вводить бронирование;
- не дробить этапы сверх практической пользы;
- не придумывать backend и frontend архитектуру вне зафиксированного stage scope;
- не подменять stage docs неформальными договорённостями в чате.
