import { createApp } from 'vue'
import { store } from './store'
import { init } from './store/lifecycle.store'
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

(globalThis as any).initSubscribeUI = (data: any) => {
  store.dispatch(init(data))
}
