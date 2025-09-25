export function getCsrfToken() {
  const el = document.querySelector('meta[name="csrf-token"]');
  return el ? el.getAttribute('content') : '';
}

export async function request(url, options = {}) {
  const headers = options.headers ? { ...options.headers } : {};
  if (!('Content-Type' in headers) && options.method && options.method !== 'GET') {
    headers['Content-Type'] = 'application/json';
  }
  // XSRF保護（Sanctum）
  headers['X-Requested-With'] = 'XMLHttpRequest';
  headers['X-CSRF-TOKEN'] = getCsrfToken();

  const res = await fetch(url, {
    credentials: 'include',
    ...options,
    headers,
  });

  if (!res.ok) {
    let detail = '';
    try { detail = JSON.stringify(await res.json()); } catch(e) {}
    throw new Error(`HTTP ${res.status} ${res.statusText} ${detail}`);
  }
  // JSON or empty
  const ct = res.headers.get('content-type') || '';
  if (ct.includes('application/json')) return res.json();
  return res.text();
}