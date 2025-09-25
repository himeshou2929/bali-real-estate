import { request } from './http';

// お気に入り一覧 → Set<propertyId> で返却（ページング先の考慮は省略）
export async function fetchFavoritesSet() {
  const json = await request('/api/favorites', { method: 'GET' });
  const list = (json?.data?.data ?? json?.data ?? []);
  return new Set(list.map(p => p.id));
}

export async function addFavorite(propertyId) {
  await request('/api/favorites', {
    method: 'POST',
    body: JSON.stringify({ property_id: propertyId }),
  });
}

export async function removeFavorite(propertyId) {
  await request(`/api/favorites/${propertyId}`, {
    method: 'DELETE',
  });
}