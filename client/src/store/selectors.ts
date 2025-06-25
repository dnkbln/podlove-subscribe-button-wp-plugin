import { createSelector } from 'reselect'
import { State } from './index'
import * as lifecycleStore from './lifecycle.store'
import * as runtimeStore from './runtime.store'
import * as buttonsStore from './buttons.store'
import * as settingsStore from './settings.store'

const root = {
  lifecycle: (state: State) => state.lifecycle,
  runtime: (state: State) => state.runtime,
  buttons: (state: State) => state.buttons,
  settings: (state: State) => state.settings
}

const buttons = {
  buttons: createSelector(root.buttons, buttonsStore.selectors.buttons),
  lastCreatedId: createSelector(root.buttons, buttonsStore.selectors.lastCreatedId)
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

const settings = {
  settings: createSelector(root.settings, settingsStore.selectors.settings)
}

export default {
  lifecycle,
  runtime,
  buttons,
  settings
}
