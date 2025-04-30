import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  connect() {
    // const collapses = this.element.querySelectorAll('.collapse')
    // collapses.forEach(item => bootstrap.Collapse.getOrCreateInstance(item).toggle())
  }

  pinned () {
    this.element.classList.toggle('pinned')
  }
}
