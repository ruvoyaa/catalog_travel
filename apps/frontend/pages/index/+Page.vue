<template>
  <section class="space-y-5">
    <div class="reveal-card rounded-[2rem] border border-stone-200 bg-white p-5 shadow-sm sm:p-6">
      <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
        <div class="max-w-2xl">
          <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-700">Коллекция маршрутов</p>
          <h1 class="mt-3 text-3xl font-semibold tracking-tight text-stone-900 sm:text-4xl">Туры для коротких поездок и больших путешествий</h1>
          <p class="mt-3 text-sm leading-6 text-stone-600 sm:text-base">
            Выбирайте поездку по направлению, длительности и интересам. В каждой карточке собраны программа, даты, стоимость и карта маршрута.
          </p>
        </div>
        <div class="grid grid-cols-3 gap-3 sm:gap-4">
          <div class="rounded-[1.5rem] bg-stone-50 px-4 py-4 text-center">
            <p class="text-[11px] uppercase tracking-[0.25em] text-stone-500">Туров</p>
            <p class="mt-2 text-2xl font-semibold text-stone-900">{{ tours.length }}</p>
          </div>
          <div class="rounded-[1.5rem] bg-stone-50 px-4 py-4 text-center">
            <p class="text-[11px] uppercase tracking-[0.25em] text-stone-500">Категорий</p>
            <p class="mt-2 text-2xl font-semibold text-stone-900">{{ categories.length }}</p>
          </div>
          <div class="rounded-[1.5rem] bg-stone-50 px-4 py-4 text-center">
            <p class="text-[11px] uppercase tracking-[0.25em] text-stone-500">Подбор</p>
            <p class="mt-2 text-sm font-semibold text-stone-900">{{ filters.mode === "semantic" ? "По смыслу" : "По каталогу" }}</p>
          </div>
        </div>
      </div>
    </div>

    <section class="reveal-card rounded-[2rem] border border-stone-200 bg-white p-5 shadow-sm sm:p-6">
      <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
          <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-700">Подборки</p>
          <h2 class="mt-2 text-2xl font-semibold tracking-tight text-stone-900">Популярные направления</h2>
        </div>
        <div class="flex flex-col gap-3 sm:items-end">
          <p class="max-w-xl text-sm leading-6 text-stone-600">Несколько идей для отпуска, короткой поездки и путешествия с природными маршрутами.</p>
          <div class="flex items-center gap-2">
            <span class="text-xs uppercase tracking-[0.22em] text-stone-400">Автопоказ</span>
            <button class="slider-button" type="button" aria-label="Назад" @click="moveFeatured(-1)">‹</button>
            <button class="slider-button" type="button" aria-label="Вперёд" @click="moveFeatured(1)">›</button>
          </div>
        </div>
      </div>
      <div class="mt-5 overflow-hidden" @mouseenter="setFeaturedPaused(true)" @mouseleave="setFeaturedPaused(false)">
        <div class="flex gap-4 transition-transform duration-500 ease-out" :style="{ transform: `translateX(-${featuredOffset}px)` }">
        <a
          v-for="tour in featuredTours"
          :key="`featured-${tour.slug}`"
          :href="`/tours/${tour.slug}`"
          class="group min-w-[280px] max-w-[280px] overflow-hidden rounded-[1.75rem] border border-stone-200 bg-stone-50 transition duration-300 hover:-translate-y-1.5 hover:border-amber-200 hover:bg-white hover:shadow-xl sm:min-w-[320px] sm:max-w-[320px]"
        >
          <div class="aspect-[16/10] bg-stone-200">
            <img v-if="tour.coverImageUrl" :src="tour.coverImageUrl" :alt="tour.coverImageAlt || tour.title" class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.04]" />
            <div v-else class="flex h-full items-center justify-center bg-[linear-gradient(135deg,_#fde68a,_#fdba74,_#fb7185)] text-lg font-semibold text-white">
              {{ tour.title }}
            </div>
          </div>
          <div class="p-4">
            <p class="text-xs uppercase tracking-[0.22em] text-stone-500">{{ tour.durationLabel }}</p>
            <p class="mt-2 text-lg font-semibold text-stone-900">{{ tour.title }}</p>
            <p class="mt-2 text-sm leading-6 text-stone-600">{{ tour.shortDescription }}</p>
          </div>
        </a>
        </div>
      </div>
    </section>

    <section class="reveal-card rounded-[2rem] border border-stone-200 bg-white p-5 shadow-sm sm:p-6">
      <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
          <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-700">Короткие поездки</p>
          <h2 class="mt-2 text-2xl font-semibold tracking-tight text-stone-900">Идеи на выходные и мини-отпуск</h2>
        </div>
        <div class="flex flex-col gap-3 sm:items-end">
          <p class="max-w-xl text-sm leading-6 text-stone-600">Маршруты, которые удобно выбирать для быстрой смены обстановки без длинной подготовки.</p>
          <div class="flex items-center gap-2">
            <span class="text-xs uppercase tracking-[0.22em] text-stone-400">Автопоказ</span>
            <button class="slider-button" type="button" aria-label="Назад" @click="moveShort(-1)">‹</button>
            <button class="slider-button" type="button" aria-label="Вперёд" @click="moveShort(1)">›</button>
          </div>
        </div>
      </div>
      <div class="mt-5 overflow-hidden" @mouseenter="setShortPaused(true)" @mouseleave="setShortPaused(false)">
        <div class="flex gap-4 transition-transform duration-500 ease-out" :style="{ transform: `translateX(-${shortOffset}px)` }">
        <a
          v-for="tour in shortTripTours"
          :key="`short-${tour.slug}`"
          :href="`/tours/${tour.slug}`"
          class="group min-w-[250px] max-w-[250px] rounded-[1.75rem] border border-stone-200 bg-[linear-gradient(180deg,_#fff7ed,_#ffffff)] p-5 transition duration-300 hover:-translate-y-1.5 hover:border-amber-200 hover:shadow-xl sm:min-w-[290px] sm:max-w-[290px]"
        >
          <p class="text-xs uppercase tracking-[0.22em] text-amber-700">{{ tour.primaryCategory || "Маршрут" }}</p>
          <h3 class="mt-3 text-xl font-semibold tracking-tight text-stone-900 transition duration-300 group-hover:text-amber-700">{{ tour.title }}</h3>
          <p class="mt-3 text-sm leading-6 text-stone-600">{{ tour.shortDescription }}</p>
          <div class="mt-4 flex items-center justify-between gap-3 text-sm">
            <span class="font-medium text-stone-900">{{ tour.durationLabel }}</span>
            <span class="font-semibold text-stone-900">{{ formatPrice(tour.minPrice) }}</span>
          </div>
        </a>
        </div>
      </div>
    </section>

    <div class="grid gap-5 lg:grid-cols-[290px_minmax(0,1fr)] lg:items-start">
      <aside class="reveal-card rounded-[2rem] border border-stone-200 bg-white p-5 shadow-sm lg:sticky lg:top-6">
        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-700">Фильтры</p>
        <h2 class="mt-3 text-2xl font-semibold tracking-tight text-stone-900">Подобрать поездку</h2>
        <p class="mt-3 text-sm leading-6 text-stone-600">
          Поиск работает и по точным совпадениям, и по смыслу запроса.
        </p>

        <form class="mt-6 space-y-4" method="GET" action="/">
          <label class="block">
            <span class="mb-2 block text-sm font-medium text-stone-700">Поиск по маршруту</span>
            <input
              class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm"
              type="text"
              name="q"
              :value="filters.q"
              placeholder="Алтай, лёд Байкала, семейный отдых..."
            />
          </label>

          <label class="block">
            <span class="mb-2 block text-sm font-medium text-stone-700">Категория</span>
            <select class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm" name="category">
              <option value="">Все категории</option>
              <option v-for="category in categories" :key="category.slug" :selected="filters.category === category.slug" :value="category.slug">
                {{ category.name }} ({{ category.tourCount }})
              </option>
            </select>
          </label>

          <label class="block">
            <span class="mb-2 block text-sm font-medium text-stone-700">Длительность</span>
            <select class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm" name="duration">
              <option value="">Любая</option>
              <option v-for="duration in durationOptions" :key="duration" :selected="String(filters.duration || '') === String(duration)" :value="duration">
                {{ duration }} дн.
              </option>
            </select>
          </label>

          <div class="flex flex-col gap-3 sm:flex-row">
            <button class="rounded-full bg-stone-900 px-5 py-3 text-sm font-medium text-white" type="submit">Показать варианты</button>
            <a class="rounded-full border border-stone-300 px-5 py-3 text-center text-sm font-medium text-stone-700" href="/">Сбросить</a>
          </div>
        </form>
      </aside>

      <div class="space-y-5">
        <div class="reveal-card rounded-[2rem] border border-stone-200 bg-white p-5 shadow-sm sm:p-6">
          <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
              <p class="text-xs font-semibold uppercase tracking-[0.35em] text-stone-500">Подборка</p>
              <p class="mt-2 text-3xl font-semibold tracking-tight text-stone-900 sm:text-4xl">{{ tours.length }}</p>
              <p class="mt-2 text-sm text-stone-600">маршрутов найдено по текущим параметрам</p>
            </div>
            <div class="max-w-md text-sm leading-6 text-stone-600 sm:text-right">
              Список обновляется сразу по выбранным фильтрам и поисковому запросу.
            </div>
          </div>
        </div>

        <div class="grid gap-5 xl:grid-cols-2">
          <article v-for="tour in tours" :key="tour.slug" class="group reveal-card overflow-hidden rounded-[2rem] border border-stone-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1.5 hover:border-amber-200 hover:shadow-xl">
            <div class="aspect-[16/10] bg-stone-200">
              <img v-if="tour.coverImageUrl" :src="tour.coverImageUrl" :alt="tour.coverImageAlt || tour.title" class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.04]" />
              <div v-else class="flex h-full items-center justify-center bg-[linear-gradient(135deg,_#fde68a,_#fdba74,_#fb7185)] text-lg font-semibold text-white">
                {{ tour.title }}
              </div>
            </div>
            <div class="p-5 sm:p-6">
              <div class="flex flex-wrap gap-2">
                <span
                  v-for="category in tour.categories"
                  :key="category.slug"
                  class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-amber-800"
                >
                  {{ category.name }}
                </span>
              </div>
              <h2 class="mt-4 text-2xl font-semibold tracking-tight text-stone-900 transition duration-300 group-hover:text-amber-700">{{ tour.title }}</h2>
              <p class="mt-3 text-sm leading-6 text-stone-600">{{ tour.shortDescription || "Описание будет добавлено позже." }}</p>
              <div class="mt-5 grid gap-3 rounded-[1.5rem] bg-stone-50 p-4 sm:grid-cols-2">
                <div>
                  <p class="text-xs uppercase tracking-[0.25em] text-stone-500">Длительность</p>
                  <p class="mt-2 text-sm font-medium text-stone-900">{{ tour.durationLabel }}</p>
                </div>
                <div class="sm:text-right">
                  <p class="text-xs uppercase tracking-[0.25em] text-stone-500">Стоимость</p>
                  <p class="mt-2 text-lg font-semibold text-stone-900">{{ formatPrice(tour.minPrice) }}</p>
                </div>
              </div>
              <a :href="`/tours/${tour.slug}`" class="mt-5 inline-flex rounded-full bg-stone-900 px-5 py-3 text-sm font-medium text-white transition hover:bg-stone-800">
                Смотреть программу
              </a>
            </div>
          </article>
        </div>

        <div v-if="!tours.length" class="rounded-[2rem] border border-dashed border-stone-300 bg-white px-6 py-10 text-center text-stone-500 sm:px-8 sm:py-12">
          По текущим параметрам подходящих туров не найдено.
        </div>
      </div>
    </div>
  </section>
</template>

<script lang="ts" setup>
import { computed, onBeforeUnmount, onMounted, ref } from "vue";
import { useData } from "vike-vue/useData";
import type { Data } from "./+data";

const { filters, categories, tours } = useData<Data>();

const durationOptions = computed(() =>
  [...new Set(tours.map((tour) => tour.durationDays))]
    .filter((duration) => Number.isFinite(duration))
    .sort((left, right) => left - right)
);

const featuredTours = computed(() => tours.slice(0, 6));
const shortTripTours = computed(() => tours.filter((tour) => tour.durationDays <= 4).slice(0, 6));
const featuredIndex = ref(0);
const shortIndex = ref(0);
const featuredPaused = ref(false);
const shortPaused = ref(false);
const featuredStep = 336;
const shortStep = 306;
const featuredOffset = computed(() => featuredIndex.value * featuredStep);
const shortOffset = computed(() => shortIndex.value * shortStep);
let featuredTimer: number | null = null;
let shortTimer: number | null = null;

function moveFeatured(direction: number) {
  const maxIndex = Math.max(featuredTours.value.length - 2, 0);
  featuredIndex.value = (featuredIndex.value + direction + maxIndex + 1) % (maxIndex + 1);
}

function moveShort(direction: number) {
  const maxIndex = Math.max(shortTripTours.value.length - 2, 0);
  shortIndex.value = (shortIndex.value + direction + maxIndex + 1) % (maxIndex + 1);
}

function setFeaturedPaused(value: boolean) {
  featuredPaused.value = value;
}

function setShortPaused(value: boolean) {
  shortPaused.value = value;
}

onMounted(() => {
  featuredTimer = window.setInterval(() => {
    if (!featuredPaused.value && featuredTours.value.length > 2) {
      moveFeatured(1);
    }
  }, 4200);

  shortTimer = window.setInterval(() => {
    if (!shortPaused.value && shortTripTours.value.length > 2) {
      moveShort(1);
    }
  }, 5200);
});

onBeforeUnmount(() => {
  if (featuredTimer !== null) window.clearInterval(featuredTimer);
  if (shortTimer !== null) window.clearInterval(shortTimer);
});

function formatPrice(price: number | null) {
  if (!price) return "по запросу";
  return new Intl.NumberFormat("ru-RU", {
    style: "currency",
    currency: "RUB",
    maximumFractionDigits: 0,
  }).format(price);
}
</script>

<style scoped>
.slider-button {
  display: inline-flex;
  height: 2.5rem;
  width: 2.5rem;
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
