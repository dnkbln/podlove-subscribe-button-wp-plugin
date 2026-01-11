import { SubscribeApiClient } from '@/lib/api';
import { createApi } from './api';
import { call, fork, put, takeEvery } from 'redux-saga/effects'
import { Action } from 'redux-saga';
import { get } from 'lodash'

import * as lifecycle from '@store/lifecycle.store';
import * as client from '@store/clients.store'
import { takeFirst } from './helper';
import { Client } from '@/types/client.types';

function* clientSaga(): any {
    const apiClient: SubscribeApiClient = yield createApi()
    yield fork(initialize, apiClient);
    yield takeEvery(client.FETCH_SELECTED_CLIENTS, fetchSelectedClients, apiClient);
    yield takeEvery(client.ADD_SELECTED_CLIENTS, addSelectedClients, apiClient)
    yield takeEvery(client.REMOVE_SELECTED_CLIENT, removeSelectedClient, apiClient)
    yield takeEvery(client.UPDATE_SELECTED_CLIENT, updateSelectedClient, apiClient)
}

function* initialize(api: SubscribeApiClient) {
    const { result } = yield api.get('clients/list')

    if (!result) {
        return;
    }

    yield put(client.set_client_list(result));
}

function* fetchSelectedClients(api: SubscribeApiClient, action: Action) {
    const buttonId: number = get(action, ['payload', 'buttonId']);
    if (!buttonId) {
        return;
    }

    const { result } = yield call(
        [api, api.get],
        'clients?button_id=' + buttonId
    )

    if (!result) {
        return;
    }

    yield put(client.set_selected_clients({ buttonId, clients: result }));
}

function* removeSelectedClient(api: SubscribeApiClient, action: Action) {
    const payload = get(action, ['payload']) as { buttonId: number; client: Client } | undefined;
    if (payload?.client) {
        yield call([api, api.delete], `clients/${payload.client.id}`)
    }
}

function* addSelectedClients(api: SubscribeApiClient, action: Action) {
    const payload = get(action, ['payload']) as { buttonId: number; clients: Client[] } | undefined;
    const buttonId = payload?.buttonId;
    const selectedClients: Client[] = payload?.clients || [];

    if (!buttonId) {
        return;
    }
    if (!selectedClients.length) {
        return
    }

    for (const selectedClient of selectedClients) {
        const { result: createResult, error: createError } = yield call(
            [api, api.post],
            'clients',
            {
                button_id: buttonId
            }
        );

        if (createError || !createResult?.id) {
            console.error('Error creating client entry:', createError);
            continue;
        }

        yield call([api, api.put], `clients/${createResult.id}`, {
            id: selectedClient.id,
            title: selectedClient.title,
            platform: selectedClient.platform,
            type: selectedClient.type,
            call_schema: selectedClient.call_schema,
            button_id: buttonId
        })
    }

    const { result } = yield call(
        [api, api.get],
        'clients?button_id=' + buttonId
    )
    if (!result) {
        return
    }

    yield put(client.set_selected_clients({ buttonId, clients: result }));
}

function* updateSelectedClient(api: SubscribeApiClient, action: Action) {
    const payload: client.ClientUpdate = get(action, ['payload']);
    if (!payload) {
        return;
    }

    const id = get(payload, 'id', null);
    const prop = get(payload, 'prop', null);
    const value = get(payload, 'value', null);
    if (!id || !prop) {
        return;
    }

    yield call([api, api.put], `clients/${id}`, {
        [prop]: value,
        button_id: payload.buttonId
    });
}

export default function () {
    return function* () {
      yield takeFirst(lifecycle.INIT, clientSaga)
    }
}
