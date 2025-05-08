import { createAction, handleActions, Action } from "redux-actions"
import { SubscribeButtonSettings } from "../types/settings.types"

export type State = {
    settings: SubscribeButtonSettings[]
}

export const initialState: State = {
    settings: []
}

export const INIT = 'podlove/subscribe/settings/INIT'
export const SET = 'podlove/subscribe/settings/SET'

export const init = createAction<void>(INIT);
export const set = createAction<SubscribeButtonSettings[]>(SET)

export const reducer = handleActions<State, any>({
    [SET]: (state, { payload }: Action<SubscribeButtonSettings[]>) => ({
        ...state,
        settings: payload
    })
}, initialState);

export const selectors = {
    settings: (state: State) => state.settings
}