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
export const UPDATE = 'podlove/subscribe/buttons/UPDATE'
export const ADD = 'podlove/subscribe/buttons/ADD'

export const init = createAction<void>(INIT);
export const set = createAction<SubscribeButton[]>(SET)
export const update = createAction<SubscribeButton>(UPDATE)
export const add = createAction<SubscribeButton>(ADD)

export const reducer = handleActions<State, any>({
    [SET]: (state, { payload }: Action<SubscribeButton[]>) => ({
        ...state,
        buttons: payload
    }),
    [UPDATE]: (state, { payload }: Action<SubscribeButton>) => {
        const updateButtons = state.buttons.map(button =>
            button.id === payload.id
                ? { ...button, ...payload }
                : button
        );

        return {
            ...state,
            buttons: updateButtons
        };
    },
    [ADD]: (state, { payload }: Action<SubscribeButton>) => {
        return {
            ...state,
            buttons: [...state.buttons, payload]
        }
    }
}, initialState);

export const selectors = {
    buttons: (state: State) => state.buttons
}