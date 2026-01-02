import { createAction, handleActions, Action } from "redux-actions";
import { Client } from "../types/client.types";

export type State = {
    clientList: Client[],
    selectedClients: Client[]
}

export const initialState: State = {
    clientList: [],
    selectedClients: []
}

export const INIT = 'podlove/subscribe/client/INIT'
export const SET_CLIENT_LIST = 'podlove/subscribe/client/SET_CLIENT_LIST'
export const SET_SELECTED_CLIENTS = 'podlove/subscribe/client/SET_SELECTED_CLIENTS'
export const REMOVE_SELECTED_CLIENT = 'podlove/subscribe/client/REMOVE_SELECTED_CLIENT'

export const init = createAction<void>(INIT);
export const set_client_list = createAction<Client[]>(SET_CLIENT_LIST);
export const set_selected_clients = createAction<Client[]>(SET_SELECTED_CLIENTS);
export const remove_selected_client = createAction<Client>(REMOVE_SELECTED_CLIENT);

export const reducer = handleActions<State, any>({
    [SET_CLIENT_LIST]: (state : State, { payload }: Action<Client[]>) => ({
        ...state,
        clientList: payload
    }),
    [SET_SELECTED_CLIENTS]: (state: State, { payload }: Action<Client[]>) => ({
        ...state,
        selectedClients: payload
    }),
    [REMOVE_SELECTED_CLIENT]: (state: State, { payload}: Action<Client>) => {
        const selectedClients = state.selectedClients.filter((client) => client.id !== payload.id)
        return {
            ...state,
            selectedClients: selectedClients
        };
    }
}, initialState);

export const seclectors = {
    clientList: (state: State) => state.clientList,
    selectedClients: (state: State) => state.selectedClients
}
