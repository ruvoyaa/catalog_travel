<!-- https://vike.dev/Layout -->

<template>
  <div class="min-h-screen bg-[radial-gradient(circle_at_top,_#fef3c7,_#f8fafc_40%,_#e7e5e4)] text-stone-900">
    <header class="border-b border-stone-200/70 bg-white/85 backdrop-blur">
      <div class="border-b border-stone-200/70 bg-stone-900 text-stone-100">
        <div class="mx-auto flex max-w-6xl flex-col gap-2 px-4 py-3 text-xs sm:flex-row sm:items-center sm:justify-between sm:px-6 sm:text-sm">
          <div class="flex flex-wrap items-center gap-2 sm:gap-3">
            <a href="tel:+7XXXXXXXXXX" class="rounded-full border border-white/15 bg-white/10 px-3 py-1.5 transition hover:bg-white/15">
              Телефон: +7 (XXX) XXX-XX-XX
            </a>
            <span class="rounded-full border border-white/15 bg-white/10 px-3 py-1.5">Адрес: Москва, ул. Примерная, ХХХ</span>
          </div>
          <span class="text-stone-300">Ежедневно с 10:00 до 20:00</span>
        </div>
      </div>
      <div class="mx-auto flex max-w-6xl flex-col gap-4 px-4 py-5 sm:px-6 lg:flex-row lg:items-end lg:justify-between">
        <div>
          <p class="text-[11px] font-semibold uppercase tracking-[0.35em] text-amber-700">Каталог путешествий</p>
          <a href="/" class="mt-2 block text-2xl font-semibold tracking-tight text-stone-900 sm:text-3xl">Подборка туров по России</a>
          <p class="mt-2 max-w-2xl text-sm leading-6 text-stone-600">
            Готовые маршруты с понятной программой, ценами, датами и картой поездки.
          </p>
        </div>
        <div class="grid grid-cols-2 gap-3 sm:max-w-sm">
          <div class="rounded-[1.25rem] bg-stone-50 px-4 py-3">
            <p class="text-[11px] uppercase tracking-[0.25em] text-stone-500">Поддержка</p>
            <p class="mt-2 text-sm font-semibold text-stone-900">Быстрый ответ</p>
          </div>
          <div class="rounded-[1.25rem] bg-stone-50 px-4 py-3">
            <p class="text-[11px] uppercase tracking-[0.25em] text-stone-500">Формат</p>
            <p class="mt-2 text-sm font-semibold text-stone-900">Готовые маршруты</p>
          </div>
        </div>
      </div>
    </header>

    <main class="mx-auto max-w-6xl px-4 py-6 sm:px-6 sm:py-8">
      <slot />
    </main>
  </div>
</template>

<script lang="ts" setup>
</script>

<style>
body {
  margin: 0;
  font-family:
    "IBM Plex Sans",
    "Segoe UI",
    sans-serif;
}
* {
  box-sizing: border-box;
}
a {
  text-decoration: none;
}

button {
  font: inherit;
}

.reveal-card {
  animation: reveal-up 0.55s ease both;
}

.reveal-card:nth-child(2) {
  animation-delay: 0.06s;
}

.reveal-card:nth-child(3) {
  animation-delay: 0.12s;
}

.reveal-card:nth-child(4) {
  animation-delay: 0.18s;
}

@keyframes reveal-up {
  from {
    opacity: 0;
    transform: translateY(18px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
