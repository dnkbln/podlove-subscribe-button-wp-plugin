import { SubscribeApiClient } from "src/lib/api";
import { fork, put } from 'redux-saga/effects'
import { createApi } from "./api";
import { takeFirst } from "./helper";

import * as settings from '../store/settings.store'
import * as lifecycle from '../store/lifecycle.store';

function* buttonsSaga(): any {
    const apiClient: SubscribeApiClient = yield createApi()
    yield fork(initialize, apiClient)
}

function* initialize(api: SubscribeApiClient) {
    const { result } = yield api.get('settings')

    if (!result) {
        return
    }

    yield put(settings.set(result))
}

export default function () {
    return function* () {
      yield takeFirst(lifecycle.INIT, buttonsSaga)
    }
}
