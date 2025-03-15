import { createSelector } from 'reselect'
import { State } from './index'
import * as lifecycleStore from './lifecycle.store'
import * as runtimeStore from './runtime.store'
import * as buttonsStore from './buttons.store'

const root = {
  lifecycle: (state: State) => state.lifecycle,
  runtime: (state: State) => state.runtime,
  buttons: (state: State) => state.buttons
}

const buttons = {
  buttons: createSelector(root.buttons, buttonsStore.selectors.buttons)
}

const lifecycle = {
  bootstrapped: createSelector(root.lifecycle, lifecycleStore.selectors.bootstrapped),
}

const runtime = {
  baseUrl: createSelector(root.runtime, runtimeStore.selectors.baseUrl),
  nonce: createSelector(root.runtime, runtimeStore.selectors.nonce),
  base: createSelector(root.runtime, runtimeStore.selectors.base),
  auth: createSelector(root.runtime, runtimeStore.selectors.auth),
  bearer: createSelector(root.runtime, runtimeStore.selectors.bearer),
}

export default {
  buttons,
  lifecycle,
  runtime
}
