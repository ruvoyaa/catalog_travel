# Development Setup

Этот документ фиксирует фактический dev baseline после технической части Stage 01.

## Структура приложений

- `apps/backend` — Laravel backend и admin UI
- `apps/frontend` — публичный SSR frontend на Vike/Vue

## Уже подготовлено

- Laravel skeleton установлен
- Vike + Vue + Tailwind skeleton установлен
- `APP_KEY` для backend сгенерирован
- backend `npm run build` проходит

## Локальный запуск без Docker

### Backend

```bash
cd apps/backend
composer install
npm install
php artisan serve
```

Если PostgreSQL уже поднят локально:

```bash
cd apps/backend
php artisan migrate
```

### Frontend

```bash
cd apps/frontend
npm install
npm run dev
```

## Docker baseline

В репозитории подготовлены:

- `docker-compose.yml`
- `infra/docker/backend/Dockerfile`
- `infra/docker/frontend/Dockerfile`

Но в текущем окружении `docker` бинарь не найден, поэтому Docker runtime не был проверен фактическим запуском на этом проходе.

## Минимальные проверки Stage 01

- `php artisan route:list`
- `npm run build` в `apps/backend`
- `npm run build` в `apps/frontend`

## Открытые следующие шаги

- проверить Docker runtime в окружении, где доступен `docker`
- решить точный transport-контракт между Laravel backend и SSR frontend
- перейти к реализации доменной модели Stage 02
