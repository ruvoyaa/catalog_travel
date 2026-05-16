import { useConfig } from "vike-vue/useConfig";
import type { PageContextServer } from "vike/types";
import { fetchJson } from "../../lib/api";
import type { TourCard } from "../../types/tour";

type CatalogResponse = {
  filters: {
    q: string;
    category: string;
    duration: string | null;
  };
  categories: {
    name: string;
    slug: string;
    tourCount: number;
  }[];
  tours: TourCard[];
};

export type Data = Awaited<ReturnType<typeof data>>;

export async function data(pageContext: PageContextServer) {
  const config = useConfig();
  const currentUrl = new URL(pageContext.urlOriginal, "http://catalog-travel.local");
  const queryString = currentUrl.searchParams.toString();
  const payload = await fetchJson<CatalogResponse>(`/api/tours${queryString ? `?${queryString}` : ""}`);

  config({
    title: payload.filters.q ? `Поиск: ${payload.filters.q}` : "Каталог туров",
  });

  return payload;
}
