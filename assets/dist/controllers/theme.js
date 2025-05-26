import { Controller } from '@hotwired/stimulus';
import Cookies from 'js-cookie';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static values = {
    cookie: String
  }

  connect() {
    const media = window.matchMedia('(prefers-color-scheme: dark)')
    media.addEventListener('change', (event) => {
      document.documentElement.setAttribute('data-bs-theme', event.matches ? 'dark' : 'light')
      // Using browser theme
      Cookies.remove(this.cookieValue)
    })

    const theme = Cookies.get(this.cookieValue) ?? (media.matches ? 'dark' : 'light')
    document.documentElement.setAttribute('data-bs-theme', theme)
  }

  toggle() {
    const current = document.documentElement.getAttribute('data-bs-theme')
    const theme = current === 'dark' ? 'light' : 'dark'

    document.documentElement.setAttribute('data-bs-theme', theme)
    Cookies.set(this.cookieValue, theme)
  }
}
