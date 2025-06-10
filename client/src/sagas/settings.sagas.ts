import { SubscribeApiClient } from "src/lib/api";
import { fork, put, select, takeEvery } from 'redux-saga/effects'
import { createApi } from "./api";
import { takeFirst } from "./helper";
import { selectors } from "../store";

import * as settings from '../store/settings.store'
import * as lifecycle from '../store/lifecycle.store';
import { SubscribeButtonSettings } from "src/types/settings.types";

function* buttonsSaga(): any {
    const apiClient: SubscribeApiClient = yield createApi()
    yield fork(initialize, apiClient)

    yield takeEvery(settings.UPDATE_ITEM, save, apiClient)
}

function* save(api: SubscribeApiClient) {
    const settings: SubscribeButtonSettings = yield select(selectors.settings.settings)

    yield api.put('settings', settings)
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
