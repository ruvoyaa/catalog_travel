import { useConfig } from "vike-vue/useConfig";
import type { PageContextServer } from "vike/types";
import { fetchJson } from "../../../lib/api";
import type { TourDetail } from "../../../types/tour";

type TourResponse = {
  tour: TourDetail;
};

export type Data = Awaited<ReturnType<typeof data>>;

export async function data(pageContext: PageContextServer) {
  const config = useConfig();
  const payload = await fetchJson<TourResponse>(`/api/tours/${pageContext.routeParams.slug}`);

  config({
    title: payload.tour.title,
  });

  return payload;
}
