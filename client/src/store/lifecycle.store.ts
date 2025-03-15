import { handleActions, createAction } from 'redux-actions'

export type State = {
  bootstrapped: boolean;
}

export const INIT = 'podlove/subscribe/INIT'
export const READY = 'podlove/subscribe/READY'

export const init = createAction<{
  api?: {
    base: string;
    nonce: string;
  }
}>(INIT);

export const ready = createAction<void>(READY)

export const initialState: State = {
  bootstrapped: false
};

export const reducer = handleActions({
  [INIT]: (state: State): State => ({
    ...state,
    bootstrapped: true
  })
}, initialState);

export const selectors = {
  bootstrapped: (state: State) => state.bootstrapped
}

