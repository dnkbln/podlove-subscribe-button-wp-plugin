import { createAction, handleActions, Action } from "redux-actions"
import { SubscribeButtonSettings } from "../types/settings.types"
import { get } from 'lodash'

export type State = {
    settings: SubscribeButtonSettings[]
}

export const initialState: State = {
    settings: []
}

export const INIT = 'podlove/subscribe/settings/INIT'
export const SET = 'podlove/subscribe/settings/SET'
export const UPDATE_ITEM = 'podlove/suscribe/settings/UPDATE_ITEM'

export const init = createAction<void>(INIT);
export const set = createAction<SubscribeButtonSettings[]>(SET)
export const updateItem = createAction<{prop: string, value: any}>(UPDATE_ITEM)

export const reducer = handleActions<State, any>({
    [SET]: (state, { payload }: Action<SubscribeButtonSettings[]>) => ({
        ...state,
        settings: payload
    }),
    [UPDATE_ITEM]: (state, { payload }: Action<{prop: string, value: string}>) => {
        const rawProp = get(payload, 'prop', null)
        const value = get(payload, 'value', null)

        const validKeys: (keyof SubscribeButtonSettings)[] = [
          'size',
          'color',
          'autowidth',
          'style',
          'format',
          'hide'
        ]

        if (!rawProp || value === null || value === undefined) {
          return { ...state }
        }

        if (validKeys.includes(rawProp as keyof SubscribeButtonSettings)) {
          const prop = rawProp as keyof SubscribeButtonSettings

          return {
            ...state,
            button: {
              ...state.settings,
              [prop]: value
            }
          }
        }

        return { ...state }
    }
}, initialState);

export const selectors = {
    settings: (state: State) => state.settings
}