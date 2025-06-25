import { createAction, handleActions, Action } from "redux-actions"
import { SubscribeButton } from "../types/buttons.types"
import { get } from 'lodash'

export type State = {
    buttons: SubscribeButton[],
    lastCreatedId: string | null
}

export const initialState: State = {
    buttons: [],
    lastCreatedId: null
}

export const INIT = 'podlove/subscribe/buttons/INIT'
export const SET = 'podlove/subscribe/buttons/SET'
export const SET_LAST_CREATED_ID = 'podlove/subscribe/buttobs/SET_LAST_CREATED_ID'
export const ADD = 'podlove/subscribe/buttons/ADD'
export const UPDATE_ITEM = 'podlove/subscribe/buttons/UPDATE_ITEM'
export const ADD_OR_UPDATE_REQUEST = 'podlove/subscribe/butons/ADD_OR_UPDATE_REQUEST'
export const ADD_OR_UPDATE_SUCCESS = 'podlove/subscribe/butons/ADD_OR_UPDATE_SUCCESS'
export const DELETE = 'podlove/subscribe/buttons/DELETE'

export const init = createAction<void>(INIT);
export const set = createAction<SubscribeButton[]>(SET);
export const add = createAction<void>(ADD);
export const set_last_created_id = createAction<string>(SET_LAST_CREATED_ID)
export const updateItem = createAction<{id: string, prop: string, value: any}>(UPDATE_ITEM)
export const add_or_update_request = createAction<SubscribeButton>(ADD_OR_UPDATE_REQUEST)
export const add_or_update_success = createAction<SubscribeButton>(ADD_OR_UPDATE_SUCCESS)
export const deleteButton = createAction<SubscribeButton>(DELETE)

export const reducer = handleActions<State, any>({
    [SET]: (state, { payload }: Action<SubscribeButton[]>) => ({
        ...state,
        buttons: payload
    }),
    [SET_LAST_CREATED_ID]: (state, { payload }: Action<string>) => ({
        ...state,
        lastCreatedId: payload
    }),
    [ADD_OR_UPDATE_SUCCESS]: (state, { payload }: Action<SubscribeButton>) => {
        const index = state.buttons.findIndex( i => i.id === payload.id);
        if (index >= 0) {
            const updateButtons = state.buttons.map(button =>
                button.id === payload.id
                    ? { ...button, ...payload }
                    : button
            );

            return {
                ...state,
                buttons: updateButtons
            };
        }
        else {
            return {
                ...state,
                buttons: [...state.buttons, payload]
            }
        }
    },
    [UPDATE_ITEM]: (state, {payload}: Action<{id: string, prop: string, value: any}>) => {
        const id = get(payload, 'id', null)
        const rawProp = get(payload, 'prop', null)
        const value = get (payload, 'value', null)

        const validKeys: (keyof SubscribeButton)[] = [
          "id",
          "name",
          "title",
          "subtitle",
          "description",
          "cover",
          "feeds",
          "size",
          "autowidth",
          "color",
          "style",
          "format",
        ];

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
    [DELETE]: (state, { payload }: Action<SubscribeButton>) => {
        const updateButtons = state.buttons.filter((button) => button.id !== payload.id)
        return {
            ...state,
            buttons: updateButtons
        };
    },
}, initialState);

export const selectors = {
    buttons: (state: State) => state.buttons,
    lastCreatedId: (state: State) => state.lastCreatedId
}