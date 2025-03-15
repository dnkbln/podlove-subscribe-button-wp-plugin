import { combineReducers } from 'redux'
import * as lifecycleStore from './lifecycle.store'
import * as runtimeStore from './runtime.store'
import * as buttonsStore from './buttons.store'

export default combineReducers({
  lifecycle: lifecycleStore.reducer,
  runtime: runtimeStore.reducer,
  buttons: buttonsStore.reducer,
})
