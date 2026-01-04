import { createAction, handleActions, Action } from "redux-actions";
import { Client } from "../types/client.types";

export type State = {
    clientList: Client[],
    selectedClients: Client[]
}

export type ClientUpdate = Partial<Client> & { id?: string; title?: string | null };

export const initialState: State = {
    clientList: [],
    selectedClients: []
}

export const INIT = 'podlove/subscribe/client/INIT'
export const SET_CLIENT_LIST = 'podlove/subscribe/client/SET_CLIENT_LIST'
export const SET_SELECTED_CLIENTS = 'podlove/subscribe/client/SET_SELECTED_CLIENTS'
export const REMOVE_SELECTED_CLIENT = 'podlove/subscribe/client/REMOVE_SELECTED_CLIENT'
export const ADD_SELECTED_CLIENTS = 'podlove/subscribe/client/ADD_SELECTED_CLIENTS'
export const UPDATE_SELECTED_CLIENT = 'podlove/subscribe/client/UPDATE_SELECTED_CLIENT'

export const init = createAction<void>(INIT);
export const set_client_list = createAction<Client[]>(SET_CLIENT_LIST);
export const set_selected_clients = createAction<Client[]>(SET_SELECTED_CLIENTS);
export const remove_selected_client = createAction<Client>(REMOVE_SELECTED_CLIENT);
export const add_selected_clients = createAction<Client[]>(ADD_SELECTED_CLIENTS);
export const update_selected_client = createAction<ClientUpdate>(UPDATE_SELECTED_CLIENT);

function clientKey(client: Client | ClientUpdate): string {
    return client.id ?? client.title ?? '';
}

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
    },
    [UPDATE_SELECTED_CLIENT]: (state: State, { payload }: Action<ClientUpdate>) => {
        if (!payload) {
            return {
                ...state
            };
        }

        const key = clientKey(payload);
        if (!key) {
            return {
                ...state
            };
        }

        const selectedClients = state.selectedClients.map((client) => {
            if (clientKey(client) !== key) {
                return client;
            }
            return {
                ...client,
                ...payload
            };
        });

        return {
            ...state,
            selectedClients
        };
    },
}, initialState);

export const seclectors = {
    clientList: (state: State) => state.clientList,
    selectedClients: (state: State) => state.selectedClients
}
