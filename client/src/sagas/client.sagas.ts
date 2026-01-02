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
    yield takeEvery(client.REMOVE_SELECTED_CLIENT, removeSelectedClient, apiClient)
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

export default function () {
    return function* () {
      yield takeFirst(lifecycle.INIT, clientSaga)
    }
}
