import { createAction, handleActions, Action } from "redux-actions"
import { SubscribeButton } from "src/types/buttons.types"
import { get } from 'lodash'

export type State = {
    buttons: SubscribeButton[]
}

export const initialState: State = {
    buttons: []
}

export const INIT = 'podlove/subscribe/buttons/INIT'
export const SET = 'podlove/subscribe/buttons/SET'
export const UPDATE = 'podlove/subscribe/buttons/UPDATE'
export const UPDATE_ITEM = 'podlove/subscribe/buttons/UPDATE_ITEM'
export const ADD = 'podlove/subscribe/buttons/ADD'

export const init = createAction<void>(INIT);
export const set = createAction<SubscribeButton[]>(SET)
export const update = createAction<SubscribeButton>(UPDATE)
export const updateItem = createAction<{id: string, prop: string, value: any}>(UPDATE_ITEM)
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
    [UPDATE_ITEM]: (state, {payload}: Action<{id: string, prop: string, value: any}>) => {
        const id = get(payload, 'id', null)
        const rawProp = get(payload, 'prop', null)
        const value = get (payload, 'value', null)

        const validKeys: (keyof SubscribeButton)[] = ['id', 'name', 'title', 'subtitle', 'description', 'cover', 'feeds']

        if (!id || !rawProp || !value)
            return {
                ...state
        }

        if (validKeys.includes(rawProp as keyof SubscribeButton)) {
            const prop = rawProp as keyof SubscribeButton

            const updatedButtons = state.buttons.map(button => {
                return button.id === id
                  ? { ...button, [prop]: value }
                  : button
            });

            return {
                ...state,
                buttons: updatedButtons
            };
        }

        return {
            ...state
        }
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