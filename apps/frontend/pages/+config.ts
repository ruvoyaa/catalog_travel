import type { Config } from "vike/types";
import vikeVue from "vike-vue/config";

// Default config (can be overridden by pages)
// https://vike.dev/config

const config: Config = {
  title: "Catalog Travel",
  description: "Каталог авторских туров с фильтрами и SSR-витриной.",
  extends: [vikeVue],
};

export default config;
