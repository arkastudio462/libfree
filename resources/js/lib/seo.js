/**
 * Client-side metadata updates for SPA navigation.
 *
 * The server renders the same tags in resources/views/app.blade.php so
 * crawlers and social previews see complete metadata before JavaScript runs.
 */

export const SITE_NAME = 'libfree'
export const DEFAULT_OG_IMAGE = '/og-libfree.png'

function absolute(path) {
  if (!path) return ''
  if (/^https?:\/\//i.test(path)) return path

  return window.location.origin + (path.startsWith('/') ? path : '/' + path)
}

function upsertMeta(selector, attributes) {
  let element = document.head.querySelector(selector)

  if (!element) {
    element = document.createElement('meta')
    document.head.appendChild(element)
  }

  Object.entries(attributes).forEach(([key, value]) => {
    if (value === undefined || value === null || value === '') {
      element.removeAttribute(key)
    } else {
      element.setAttribute(key, value)
    }
  })
}

function upsertCanonical(canonical) {
  let link = document.head.querySelector('link[rel="canonical"]')

  if (!canonical) {
    link?.remove()
    return
  }

  if (!link) {
    link = document.createElement('link')
    link.setAttribute('rel', 'canonical')
    document.head.appendChild(link)
  }

  link.setAttribute('href', canonical)
}

function replaceJsonLd(schemas) {
  document.head
    .querySelectorAll('script[data-seo-jsonld]')
    .forEach((node) => node.remove())

  schemas.filter(Boolean).forEach((schema) => {
    const script = document.createElement('script')
    script.type = 'application/ld+json'
    script.dataset.seoJsonld = 'true'
    script.textContent = JSON.stringify(schema)
    document.head.appendChild(script)
  })
}

export function setSeo({
  title,
  description = '',
  canonical,
  robots = 'index,follow',
  image = DEFAULT_OG_IMAGE,
  type = 'website',
  jsonLd = [],
} = {}) {
  if (title) document.title = title

  const resolvedCanonical = absolute(canonical)
  const resolvedImage = absolute(image)

  upsertMeta('meta[name="description"]', { name: 'description', content: description })
  upsertMeta('meta[name="robots"]', { name: 'robots', content: robots })
  upsertCanonical(resolvedCanonical)

  upsertMeta('meta[property="og:type"]', { property: 'og:type', content: type })
  upsertMeta('meta[property="og:site_name"]', { property: 'og:site_name', content: SITE_NAME })
  upsertMeta('meta[property="og:locale"]', { property: 'og:locale', content: 'id_ID' })
  upsertMeta('meta[property="og:title"]', { property: 'og:title', content: title })
  upsertMeta('meta[property="og:description"]', { property: 'og:description', content: description })
  upsertMeta('meta[property="og:url"]', { property: 'og:url', content: resolvedCanonical })
  upsertMeta('meta[property="og:image"]', { property: 'og:image', content: resolvedImage })

  upsertMeta('meta[name="twitter:card"]', { name: 'twitter:card', content: 'summary_large_image' })
  upsertMeta('meta[name="twitter:title"]', { name: 'twitter:title', content: title })
  upsertMeta('meta[name="twitter:description"]', { name: 'twitter:description', content: description })
  upsertMeta('meta[name="twitter:image"]', { name: 'twitter:image', content: resolvedImage })

  replaceJsonLd(jsonLd)
}
