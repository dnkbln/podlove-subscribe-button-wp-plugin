import { createSelector } from 'reselect'
import { State } from '@store/index'
import * as lifecycleStore from '@store/lifecycle.store'
import * as runtimeStore from '@store/runtime.store'
import * as buttonsStore from '@store/buttons.store'
import * as settingsStore from '@store/settings.store'
import * as clientStore from '@store/clients.store'

const root = {
  lifecycle: (state: State) => state.lifecycle,
  runtime: (state: State) => state.runtime,
  buttons: (state: State) => state.buttons,
  settings: (state: State) => state.settings,
  client: (state: State) => state.client
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

const client = {
  clientList: createSelector(root.client, clientStore.selectors.clientList),
  selectedClients: createSelector(root.client, clientStore.selectors.selectedClients)
}

export default {
  lifecycle,
  runtime,
  buttons,
  settings,
  client
}
