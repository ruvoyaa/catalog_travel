<template>
  <article class="space-y-5 sm:space-y-6">
    <a href="/" class="inline-flex text-sm text-stone-500 underline">← Назад к подборке</a>

    <section class="grid gap-5 lg:grid-cols-[1.1fr_0.9fr]">
      <div class="reveal-card overflow-hidden rounded-[2rem] border border-stone-200 bg-white shadow-sm">
        <div class="aspect-[16/10] bg-stone-200">
          <img v-if="heroImage" :src="heroImage.url" :alt="heroImage.alt || tour.title" class="h-full w-full object-cover" />
          <div v-else class="flex h-full items-center justify-center bg-[linear-gradient(135deg,_#f59e0b,_#f97316,_#ea580c)] text-2xl font-semibold text-white">
            {{ tour.title }}
          </div>
        </div>
      </div>

      <div class="reveal-card rounded-[2rem] border border-stone-200 bg-white p-5 shadow-sm sm:p-7">
        <div class="flex flex-wrap gap-2">
          <span
            v-for="category in tour.categories"
            :key="category.slug"
            class="rounded-full bg-stone-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-stone-700"
          >
            {{ category.name }}
          </span>
        </div>
        <h1 class="mt-5 text-3xl font-semibold tracking-tight text-stone-900 sm:text-4xl">{{ tour.title }}</h1>
        <p class="mt-4 text-base leading-7 text-stone-600">{{ tour.shortDescription || "Краткое описание маршрута будет добавлено позже." }}</p>

        <dl class="mt-6 grid gap-4 rounded-[1.5rem] bg-stone-50 p-5 sm:grid-cols-2">
          <div>
            <dt class="text-xs uppercase tracking-[0.25em] text-stone-500">Длительность</dt>
            <dd class="mt-2 text-sm font-medium text-stone-900">{{ tour.durationLabel }}</dd>
          </div>
          <div>
            <dt class="text-xs uppercase tracking-[0.25em] text-stone-500">Цена</dt>
            <dd class="mt-2 text-sm font-medium text-stone-900">{{ formatPrice(tour.minPrice) }}</dd>
          </div>
          <div>
            <dt class="text-xs uppercase tracking-[0.25em] text-stone-500">Основная категория</dt>
            <dd class="mt-2 text-sm font-medium text-stone-900">{{ tour.primaryCategory || "Не указана" }}</dd>
          </div>
          <div>
            <dt class="text-xs uppercase tracking-[0.25em] text-stone-500">Маршрут</dt>
            <dd class="mt-2 text-sm font-medium text-stone-900">{{ tour.route?.title || "Точки программы уточняются" }}</dd>
          </div>
        </dl>
      </div>
    </section>

    <section class="grid gap-5 xl:grid-cols-[1.08fr_0.92fr]">
      <div class="space-y-5">
        <section class="reveal-card rounded-[2rem] border border-stone-200 bg-white p-5 shadow-sm sm:p-7">
          <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-700">Описание</p>
          <div class="prose prose-stone mt-5 max-w-none whitespace-pre-line text-base leading-7 text-stone-700">
            {{ tour.fullDescription || tour.shortDescription || "Подробное описание будет добавлено." }}
          </div>
        </section>

        <section class="reveal-card rounded-[2rem] border border-stone-200 bg-white p-5 shadow-sm sm:p-7">
          <div class="flex items-center justify-between gap-4">
            <div>
              <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-700">Фотоальбом</p>
              <h2 class="mt-3 text-2xl font-semibold tracking-tight text-stone-900">Кадры поездки</h2>
            </div>
          </div>
          <div class="mt-6 grid gap-4 sm:grid-cols-2">
            <button
              v-for="(image, index) in tour.images"
              :key="image.url"
              type="button"
              class="overflow-hidden rounded-[1.5rem] border border-stone-200 bg-stone-50 text-left transition duration-300 hover:-translate-y-1 hover:shadow-md"
              @click="openLightbox(index)"
            >
              <div class="aspect-[4/3] p-3">
                <img :src="image.url" :alt="image.alt || tour.title" class="h-full w-full rounded-[1rem] object-contain" />
              </div>
              <div class="px-4 pb-4 text-sm font-medium text-stone-700">Открыть крупнее</div>
            </button>
          </div>
        </section>
      </div>

      <div class="space-y-5">
        <section class="reveal-card rounded-[2rem] border border-stone-200 bg-white p-5 shadow-sm sm:p-7">
          <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-700">Даты и цены</p>
          <div class="mt-6 space-y-4">
            <div v-for="item in activeDates" :key="`${item.startDate}-${item.price}`" class="rounded-[1.5rem] border border-stone-200 bg-stone-50 p-5">
              <div class="flex items-start justify-between gap-4">
                <div>
                  <p class="text-sm font-semibold text-stone-900">{{ item.label || formatDates(item.startDate, item.endDate) }}</p>
                  <p class="mt-2 text-sm text-stone-600">{{ formatDates(item.startDate, item.endDate) }}</p>
                </div>
                <p class="text-lg font-semibold text-stone-900">{{ formatPrice(item.price) }}</p>
              </div>
            </div>
            <div v-if="!activeDates.length" class="rounded-[1.5rem] border border-dashed border-stone-300 bg-stone-50 p-5 text-sm text-stone-500">
              Активные даты ещё не добавлены.
            </div>
          </div>
        </section>

        <section class="reveal-card rounded-[2rem] border border-stone-200 bg-white p-5 shadow-sm sm:p-7">
          <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-700">Яндекс Карта</p>
          <div v-if="tour.route" class="mt-6">
            <img :src="tour.route.staticMapUrl" :alt="tour.route.title || tour.title" class="w-full rounded-[1.5rem] border border-stone-200" />
            <p class="mt-4 text-sm text-stone-600">
              Центр маршрута: {{ tour.route.centerLat }}, {{ tour.route.centerLng }} · zoom {{ tour.route.zoom }}
            </p>
            <a
              class="mt-4 inline-flex rounded-full border border-stone-300 px-4 py-2 text-sm font-medium text-stone-700"
              :href="`https://yandex.ru/maps/?ll=${tour.route.centerLng},${tour.route.centerLat}&z=${tour.route.zoom}`"
              target="_blank"
              rel="noreferrer"
            >
              Открыть в Яндекс Картах
            </a>
          </div>
          <div v-else class="mt-6 rounded-[1.5rem] border border-dashed border-stone-300 bg-stone-50 p-5 text-sm text-stone-500">
            Маршрут для этого тура ещё уточняется.
          </div>
        </section>
      </div>
    </section>

    <div
      v-if="activeImage"
      class="fixed inset-0 z-50 flex items-center justify-center bg-stone-950/80 px-4 py-8"
      @click="closeLightbox"
    >
      <div class="relative w-full max-w-5xl" @click.stop>
        <button type="button" class="absolute right-0 top-0 z-10 rounded-full bg-white/95 px-4 py-2 text-sm font-medium text-stone-900 shadow" @click="closeLightbox">
          Закрыть
        </button>
        <div class="overflow-hidden rounded-[2rem] bg-white p-4 shadow-2xl sm:p-6">
          <div class="flex items-center justify-between gap-3 pb-4">
            <button type="button" class="slider-button" aria-label="Предыдущее фото" @click="moveLightbox(-1)">‹</button>
            <p class="text-sm text-stone-600">{{ activeImage.alt || tour.title }}</p>
            <button type="button" class="slider-button" aria-label="Следующее фото" @click="moveLightbox(1)">›</button>
          </div>
          <div class="flex max-h-[78vh] items-center justify-center">
            <img :src="activeImage.url" :alt="activeImage.alt || tour.title" class="max-h-[72vh] w-full rounded-[1.5rem] object-contain" />
          </div>
        </div>
      </div>
    </div>
  </article>
</template>

<script lang="ts" setup>
import { computed, ref } from "vue";
import { useData } from "vike-vue/useData";
import type { Data } from "./+data";

const { tour } = useData<Data>();

const heroImage = computed(() => tour.images[0] || null);
const activeDates = computed(() => tour.datePrices.filter((item) => item.isActive));
const activeImageIndex = ref<number | null>(null);
const activeImage = computed(() => (activeImageIndex.value === null ? null : tour.images[activeImageIndex.value] || null));

function openLightbox(index: number) {
  activeImageIndex.value = index;
}

function closeLightbox() {
  activeImageIndex.value = null;
}

function moveLightbox(direction: number) {
  if (activeImageIndex.value === null || !tour.images.length) return;
  activeImageIndex.value = (activeImageIndex.value + direction + tour.images.length) % tour.images.length;
}

function formatPrice(price: number | null) {
  if (!price) return "по запросу";

  return new Intl.NumberFormat("ru-RU", {
    style: "currency",
    currency: "RUB",
    maximumFractionDigits: 0,
  }).format(price);
}

function formatDates(startDate: string, endDate: string | null) {
  const start = new Date(startDate).toLocaleDateString("ru-RU", { day: "numeric", month: "long" });
  if (!endDate) return start;

  const end = new Date(endDate).toLocaleDateString("ru-RU", { day: "numeric", month: "long" });
  return `${start} — ${end}`;
}
</script>

<style scoped>
.slider-button {
  display: inline-flex;
  height: 2.75rem;
  width: 2.75rem;
  align-items: center;
  justify-content: center;
  border-radius: 9999px;
  border: 1px solid rgb(214 211 209);
  background: white;
  color: rgb(41 37 36);
  transition:
    transform 0.25s ease,
    box-shadow 0.25s ease,
    background-color 0.25s ease;
}

.slider-button:hover {
  transform: translateY(-1px) scale(1.03);
  background: rgb(255 247 237);
  box-shadow: 0 10px 25px -12px rgba(28, 25, 23, 0.35);
}
</style>
