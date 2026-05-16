export const backendUrl = (import.meta.env.VITE_BACKEND_URL || "http://127.0.0.1:8000").replace(/\/$/, "");

export async function fetchJson<T>(path: string): Promise<T> {
  const response = await fetch(`${backendUrl}${path}`);

  if (!response.ok) {
    throw new Error(`API request failed: ${response.status}`);
  }

  return (await response.json()) as T;
}
