import { createAction, handleActions, Action } from "redux-actions"
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

export const reducer = handleActions<State, any>({
    [SET]: (state, { payload }: Action<SubscribeButton[]>) => ({
        ...state,
        buttons: payload
    })
}, initialState);

export const selectors = {
    buttons: (state: State) => state.buttons
}