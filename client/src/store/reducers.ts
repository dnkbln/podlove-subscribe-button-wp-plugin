import { combineReducers } from 'redux'
import * as lifecycleStore from './lifecycle.store'
import * as runtimeStore from './runtime.store'
import * as buttonsStore from './buttons.store'
import * as settingsStore from './settings.store'
import * as clientStore from './clients.store'

export default combineReducers({
  lifecycle: lifecycleStore.reducer,
  runtime: runtimeStore.reducer,
  buttons: buttonsStore.reducer,
  settings: settingsStore.reducer,
  client: clientStore.reducer
})
