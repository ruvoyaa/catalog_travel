# Stage 01 — Implementation Plan

## Что уже зафиксировано

- monorepo обязателен;
- Docker обязателен;
- backend: Laravel;
- frontend: Vue + Vike + Vite + Tailwind 4;
- этап 01 посвящён foundation, а не прикладной логике тура.

## План реализации этапа

1. Зафиксировать структуру монорепозитория и Docker baseline.
2. Зафиксировать архитектурные границы backend/frontend.
3. Зафиксировать admin UI внутри Laravel backend.
4. Инициализировать backend skeleton.
5. Инициализировать frontend skeleton.
6. Подготовить launch instructions для локального старта.

## Что сознательно не делаем на этом этапе

- не реализуем каталог туров;
- не реализуем карточку тура;
- не внедряем embeddings и LLM;
- не строим production-grade deployment flow.
