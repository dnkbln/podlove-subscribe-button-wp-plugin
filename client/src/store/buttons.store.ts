import { createAction, handleActions } from "redux-actions"
import { SubscribeButton } from "src/types/buttons.types"

export type State = {
    buttons: SubscribeButton[]
}

export const initialState: State = {
    buttons: []
}

export const INIT = 'podlove/subscribe/buttons/INIT'
export const SET = 'podlove/subscribe/buttons/SET'

export const init = createAction<void>(INIT);
export const set = createAction<SubscribeButton[]>(SET)

export const reducer = handleActions({
    [SET]: (state: State, action: { payload: SubscribeButton[] }): State => ({
        ...state,
        buttons: action.payload
    })
}, initialState);

export const selectors = {
    buttons: (state: State) => state.buttons
}