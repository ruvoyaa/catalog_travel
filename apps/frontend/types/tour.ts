export type TourCard = {
  title: string;
  slug: string;
  shortDescription: string | null;
  durationDays: number;
  durationLabel: string;
  status: string;
  coverImageUrl: string | null;
  coverImageAlt: string | null;
  minPrice: number | null;
  primaryCategory: string | null;
  categories: { name: string; slug: string }[];
};

export type TourDatePrice = {
  startDate: string;
  endDate: string | null;
  price: number;
  currency: string;
  label: string | null;
  isActive: boolean;
};

export type TourRoute = {
  title: string | null;
  centerLat: number;
  centerLng: number;
  zoom: number;
  staticMapUrl: string;
};

export type TourDetail = TourCard & {
  fullDescription: string | null;
  images: { url: string; alt: string | null }[];
  datePrices: TourDatePrice[];
  route: TourRoute | null;
};
