import { createApp } from 'vue'
import modules from './modules'

import './style.css'


window.addEventListener("load", () => {
  document
    .querySelectorAll('[data-client="podlove-subscribe-button"]:not([data-loaded="true"])')
    .forEach((elem) => {
      elem.setAttribute("data-loaded", "true");

      const app = createApp({
        components: {
          ...modules,
        },
      });

      app.mount(elem);
    });
});

