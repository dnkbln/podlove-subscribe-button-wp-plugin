import { createAction, handleActions, Action } from "redux-actions";
import { Client } from "@/types/client.types";
import { get } from 'lodash'

export type State = {
    clientList: Client[],
    selectedClientsByButton: Record<number, Client[]>
}

export type ClientUpdate = {
    buttonId: number;
    id?: number;
    prop?: keyof Client;
    value?: Client[keyof Client];
};

export const initialState: State = {
    clientList: [],
    selectedClientsByButton: {}
}

export const INIT = 'podlove/subscribe/client/INIT'
export const SET_CLIENT_LIST = 'podlove/subscribe/client/SET_CLIENT_LIST'
export const SET_SELECTED_CLIENTS = 'podlove/subscribe/client/SET_SELECTED_CLIENTS'
export const REMOVE_SELECTED_CLIENT = 'podlove/subscribe/client/REMOVE_SELECTED_CLIENT'
export const ADD_SELECTED_CLIENTS = 'podlove/subscribe/client/ADD_SELECTED_CLIENTS'
export const UPDATE_SELECTED_CLIENT = 'podlove/subscribe/client/UPDATE_SELECTED_CLIENT'
export const CLEAR_SELECTED_CLIENTS = 'podlove/subscribe/client/CLEAR_SELECTED_CLIENTS'
export const FETCH_SELECTED_CLIENTS = 'podlove/subscribe/client/FETCH_SELECTED_CLIENTS'

export const init = createAction<void>(INIT);
export const set_client_list = createAction<Client[]>(SET_CLIENT_LIST);
export const set_selected_clients = createAction<{ buttonId: number; clients: Client[] }>(SET_SELECTED_CLIENTS);
export const remove_selected_client = createAction<{ buttonId: number; client: Client }>(REMOVE_SELECTED_CLIENT);
export const add_selected_clients = createAction<{ buttonId: number; clients: Client[] }>(ADD_SELECTED_CLIENTS);
export const update_selected_client = createAction<ClientUpdate>(UPDATE_SELECTED_CLIENT);
export const clear_selected_clients = createAction<{ buttonId: number }>(CLEAR_SELECTED_CLIENTS);
export const fetch_selected_clients = createAction<{ buttonId: number }>(FETCH_SELECTED_CLIENTS);

export const reducer = handleActions<State, any>({
    [SET_CLIENT_LIST]: (state : State, { payload }: Action<Client[]>) => ({
        ...state,
        clientList: payload
    }),
    [SET_SELECTED_CLIENTS]: (state: State, { payload }: Action<{ buttonId: number; clients: Client[] }>) => ({
        ...state,
        selectedClientsByButton: {
            ...state.selectedClientsByButton,
            [payload.buttonId]: payload.clients
        }
    }),
    [UPDATE_SELECTED_CLIENT]: (state: State, { payload }: Action<ClientUpdate>) => {
        const buttonId = get(payload, 'buttonId', null);
        const id = get(payload, 'id', null);
        const prop = get(payload, 'prop', null) as ClientUpdate['prop'];
        const value = get(payload, 'value', null);

        if (!buttonId || !id || !prop) {
            return {
                ...state
            }
        }

        const validKeys: (keyof Client)[] = [
            'title',
            'platform',
            'type',
            'logo',
            'call_schema'
        ];

        if (!validKeys.includes(prop) || value === undefined) {
            return {
                ...state
            }
        }

        if (prop === 'platform' || prop === 'type') {
            const list = Array.isArray(value) ? value : (value ? [value] : []);
            if (!list.length) {
                return {
                    ...state
                }
            }
        }

        const selectedClients = (state.selectedClientsByButton[buttonId] ?? []).map((client) => {
            return client.id === id
              ? { ...client, [prop]: value }
              : client;
        });

        return {
            ...state,
            selectedClientsByButton: {
                ...state.selectedClientsByButton,
                [buttonId]: selectedClients
            }
        }
    },
    [REMOVE_SELECTED_CLIENT]: (state: State, { payload}: Action<{ buttonId: number; client: Client }>) => {
        const buttonId = get(payload, 'buttonId', null);
        const selectedClient = get(payload, 'client', null) as Client | null;
        if (!buttonId || !selectedClient) {
            return {
                ...state
            };
        }

        const selectedClients = (state.selectedClientsByButton[buttonId] ?? []).filter((client) => client.id !== selectedClient.id)
        return {
            ...state,
            selectedClientsByButton: {
                ...state.selectedClientsByButton,
                [buttonId]: selectedClients
            }
        };
    },
    [CLEAR_SELECTED_CLIENTS]: (state: State, { payload }: Action<{ buttonId: number }>) => {
        const buttonId = get(payload, 'buttonId', null);
        if (!buttonId) {
            return {
                ...state
            };
        }
        return {
            ...state,
            selectedClientsByButton: {
                ...state.selectedClientsByButton,
                [buttonId]: []
            }
        }
    }
}, initialState);

export const selectors = {
    clientList: (state: State) => state.clientList,
    selectedClientsByButton: (state: State, buttonId: number) =>
        state.selectedClientsByButton[buttonId] ?? [],
}
