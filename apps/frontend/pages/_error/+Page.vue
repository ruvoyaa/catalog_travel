<template>
  <section class="rounded-[2rem] border border-stone-200 bg-white px-8 py-12 shadow-sm">
    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-700">Ошибка маршрута</p>
    <h1 class="mt-4 text-4xl font-semibold tracking-tight text-stone-900">{{ heading }}</h1>
    <p class="mt-4 max-w-2xl text-base text-stone-600">{{ abortReason }}</p>
    <a href="/" class="mt-8 inline-flex rounded-full bg-stone-900 px-5 py-3 text-sm font-medium text-white">Вернуться в каталог</a>
  </section>
</template>

<script lang="ts" setup>
import { usePageContext } from "vike-vue/usePageContext";

const pageContext = usePageContext();
let { is404, abortReason } = pageContext;
if (!abortReason) {
  abortReason = is404 ? "This page could not be found." : "Something went wrong.";
}
const heading = is404 ? "Page Not Found" : "Internal Error";
</script>
