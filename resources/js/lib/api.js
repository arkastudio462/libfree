/**
 * Small fetch wrapper for the same-origin Laravel API.
 *
 * The API runs with the `web` middleware group, so session cookies are sent
 * with every request and non-GET calls must include the CSRF token from the
 * app shell meta tag.
 */

export class ApiError extends Error {
  constructor(message, status, payload) {
    super(message)
    this.name = 'ApiError'
    this.status = status
    this.payload = payload
  }
}

export function csrfToken() {
  return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? ''
}

export function setCsrfToken(token) {
  if (token) {
    document.querySelector('meta[name="csrf-token"]')?.setAttribute('content', token)
  }
}

async function refreshCsrfToken() {
  try {
    const response = await fetch('/api/csrf-token', {
      credentials: 'same-origin',
      headers: { Accept: 'application/json' },
    })

    if (!response.ok) return ''

    const payload = await response.json()
    const token = payload?.token ?? ''

    if (token) {
      document.querySelector('meta[name="csrf-token"]')?.setAttribute('content', token)
    }

    return token
  } catch {
    return ''
  }
}

function messageFromPayload(payload) {
  const errors = payload?.errors

  if (errors && typeof errors === 'object') {
    const first = Object.values(errors)[0]

    if (Array.isArray(first) && first.length) return first[0]
    if (typeof first === 'string') return first
  }

  return payload?.message || 'Terjadi kesalahan. Silakan coba lagi.'
}

export async function apiFetch(url, options = {}) {
  const method = (options.method ?? 'GET').toUpperCase()
  const headers = {
    Accept: 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
    ...(options.headers ?? {}),
  }

  if (options.body && !(options.body instanceof FormData)) {
    headers['Content-Type'] = 'application/json'
  }

  if (!['GET', 'HEAD', 'OPTIONS'].includes(method)) {
    headers['X-CSRF-TOKEN'] = csrfToken()
  }

  const request = () => fetch(url, {
    credentials: 'same-origin',
    ...options,
    method,
    headers,
  })

  let response = await request()

  // The session token rotates after logout, so long-lived tabs can hold a
  // stale CSRF token. Refresh it once, then replay the request.
  if (response.status === 419 && !['GET', 'HEAD', 'OPTIONS'].includes(method)) {
    const token = await refreshCsrfToken()

    if (token) {
      headers['X-CSRF-TOKEN'] = token
      response = await request()
    }
  }

  const payload = response.status === 204 ? null : await response.json().catch(() => null)

  if (!response.ok) {
    throw new ApiError(messageFromPayload(payload), response.status, payload)
  }

  return payload
}

export function errorMessage(error) {
  if (error instanceof ApiError) return error.message

  return error?.message || 'Tidak dapat menghubungi server. Periksa koneksi lalu coba lagi.'
}
