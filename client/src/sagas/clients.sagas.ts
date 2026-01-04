import { SubscribeApiClient } from 'src/lib/api';
import { createApi } from './api';
import { call, fork, put, takeEvery } from 'redux-saga/effects'
import { Action } from 'redux-saga';
import { get } from 'lodash'

import * as lifecycle from '../store/lifecycle.store';
import * as client from '../store/clients.store'
import { takeFirst } from './helper';
import { Client } from '../types/client.types';

function* clientSaga(): any {
    const apiClient: SubscribeApiClient = yield createApi()
    yield fork(initialize, apiClient);
    yield fork(fetchSelectedClients, apiClient);
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

function* fetchSelectedClients(api: SubscribeApiClient) {
    const { result } = yield api.get('clients')

    if (!result) {
        return;
    }

    yield put(client.set_selected_clients(result));
}

function* removeSelectedClient(api: SubscribeApiClient, action: Action) {
    const selectedClient: Client = get(action, ['payload'])
    if (selectedClient) {
        yield call([api, api.delete], `clients/${selectedClient.id}`)
    }
}

function* addSelectedClients(api: SubscribeApiClient, action: Action) {
    const selectedClients: Client[] = get(action, ['payload']) || []
    if (!selectedClients.length) {
        return
    }

    for (const selectedClient of selectedClients) {
        const { result: createResult, error: createError } = yield call(
            [api, api.post],
            'clients',
            {}
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
            call_schema: selectedClient.call_schema
        })
    }

    const { result } = yield api.get('clients')
    if (!result) {
        return
    }

    yield put(client.set_selected_clients(result));
}

function* updateSelectedClient(api: SubscribeApiClient, action: Action) {
    const payload: client.ClientUpdate = get(action, ['payload']);
    if (!payload) {
        return;
    }

    const id = get(payload, 'id', null);
    if (!id) {
        return;
    }

    const body: Record<string, any> = {};
    if (Object.prototype.hasOwnProperty.call(payload, 'platform')) {
        body.platform = payload.platform;
    }
    if (Object.prototype.hasOwnProperty.call(payload, 'title')) {
        body.title = payload.title;
    }
    if (Object.prototype.hasOwnProperty.call(payload, 'type')) {
        body.type = payload.type;
    }

    if (!Object.keys(body).length) {
        return;
    }

    yield call([api, api.put], `clients/${id}`, body);
}

export default function () {
    return function* () {
      yield takeFirst(lifecycle.INIT, clientSaga)
    }
}
