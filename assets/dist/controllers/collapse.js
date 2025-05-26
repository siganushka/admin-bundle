import { Controller } from '@hotwired/stimulus';
import { Collapse } from 'bootstrap';
import Cookies from 'js-cookie';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static values = {
    cookie: String
  }

  connect() {
    // const collapses = this.element.querySelectorAll('.collapse')
    // collapses.forEach(item => Collapse.getOrCreateInstance(item).toggle())
  }

  toggle () {
    const { element, cookieValue } = this
    element.classList.toggle('collapsed')
      ? Cookies.set(cookieValue, 1)
      : Cookies.remove(cookieValue)
  }
}
