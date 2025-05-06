import { SubscribeApiClient } from "src/lib/api";
import { fork, put } from 'redux-saga/effects'
import { createApi } from "./api";
import { takeFirst } from "./helper";

import * as buttons from '../store/buttons.store'
import * as lifecycle from '../store/lifecycle.store';

function* buttonsSaga(): any {
    const apiClient: SubscribeApiClient = yield createApi()
    yield fork(initialize, apiClient)
}

function* initialize(api: SubscribeApiClient) {
    const { result } = yield api.get('buttons')

    if (!result) {
        return
    }

    yield put(buttons.set(result))
}

export default function () {
    return function* () {
      yield takeFirst(lifecycle.INIT, buttonsSaga)
    }
}
