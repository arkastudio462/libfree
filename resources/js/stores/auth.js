import { computed, reactive } from 'vue'
import { apiFetch, setCsrfToken } from '../lib/api'

/**
 * Shared authentication state for the SPA. The session itself lives in a
 * Laravel cookie; this store only mirrors the current user for the UI.
 */
export const auth = reactive({
  user: null,
  ready: false,
  pending: false,
})

let inFlight = null

export const currentUser = computed(() => auth.user)

export async function fetchUser(force = false) {
  if (auth.ready && !force) return auth.user
  if (inFlight) return inFlight

  inFlight = apiFetch('/api/saya')
    .then((payload) => {
      auth.user = payload?.data ?? null
    })
    .catch(() => {
      auth.user = null
    })
    .finally(() => {
      auth.ready = true
      inFlight = null
    })

  return inFlight
}

export async function login(credentials) {
  auth.pending = true

  try {
    const payload = await apiFetch('/api/masuk', {
      method: 'POST',
      body: JSON.stringify(credentials),
    })

    auth.user = payload.data
    auth.ready = true
    setCsrfToken(payload.csrf_token)

    return auth.user
  } finally {
    auth.pending = false
  }
}

export async function register(credentials) {
  auth.pending = true

  try {
    const payload = await apiFetch('/api/daftar', {
      method: 'POST',
      body: JSON.stringify(credentials),
    })

    auth.user = payload.data
    auth.ready = true
    setCsrfToken(payload.csrf_token)

    return auth.user
  } finally {
    auth.pending = false
  }
}

export async function logout() {
  const payload = await apiFetch('/api/keluar', { method: 'POST' })

  setCsrfToken(payload?.csrf_token)
  auth.user = null
  auth.ready = true
}

export function initialsOf(name = '') {
  const parts = name.trim().split(/\s+/).filter(Boolean)

  return parts.slice(0, 2).map((part) => part[0]?.toUpperCase() ?? '').join('') || 'LF'
}
