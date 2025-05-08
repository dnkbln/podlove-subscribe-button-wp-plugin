declare global {
    interface Window {
      __REDUX_DEVTOOLS_EXTENSION_COMPOSE__: Function
    }
  }

  import { createStore, applyMiddleware, compose, Store } from 'redux'
  import createSagaMiddleware from 'redux-saga'

  import selectors from './selectors'
  import reducers from './reducers'

  import { State as buttonsState } from './buttons.store'
  import { State as lifecycleState } from './lifecycle.store'
  import { State as runtimeState } from './runtime.store'
  import { State as settingsState } from './settings.store'

  import buttonsSaga from '../sagas/buttons.sagas'
  import lifecycleSaga from '../sagas/lifecycle.sagas'
  import notificationSaga from '../sagas/notification.sagas'
  import settingsSaga from '../sagas/settings.sagas'

  export interface State {
    lifecycle: lifecycleState
    runtime: runtimeState
    buttons: buttonsState
    settings: settingsState
  }

  const sagas = createSagaMiddleware()

  const composeEnhancers = window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__ || compose
  export const store: Store<State> = createStore(reducers, composeEnhancers(applyMiddleware(sagas)))

  sagas.run(lifecycleSaga())
  sagas.run(notificationSaga())
  sagas.run(buttonsSaga())
  sagas.run(settingsSaga())

  export { selectors, sagas }
