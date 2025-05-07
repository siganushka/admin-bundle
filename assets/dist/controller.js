import { Controller } from '@hotwired/stimulus';
import { Collapse } from 'bootstrap';
import Cookies from 'js-cookie';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static values = {
    collapsedCookie: String
  }

  connect() {
    console.log('ssssss', this)
    // const collapses = this.element.querySelectorAll('.collapse')
    // collapses.forEach(item => Collapse.getOrCreateInstance(item).toggle())
  }

  collapsed () {
    const { element, collapsedCookieValue } = this
    element.classList.toggle('collapsed')
      ? Cookies.set(collapsedCookieValue, 1)
      : Cookies.remove(collapsedCookieValue)
  }
}
