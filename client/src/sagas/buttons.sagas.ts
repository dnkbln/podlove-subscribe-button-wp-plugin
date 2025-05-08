import { SubscribeApiClient } from "src/lib/api";
import { all, call, fork, put, select, takeEvery } from 'redux-saga/effects'
import { createApi } from "./api";
import { takeFirst } from "./helper";
import { selectors } from "../store";

import * as buttons from '../store/buttons.store'
import * as lifecycle from '../store/lifecycle.store';
import { SubscribeButton } from "../types/buttons.types";

function* buttonsSaga(): any {
    const apiClient: SubscribeApiClient = yield createApi()
    yield fork(initialize, apiClient)

    yield takeEvery([buttons.UPDATE, buttons.ADD], save, apiClient)
}

function* save(api: SubscribeApiClient) {
    const buttons: SubscribeButton[] = yield select(selectors.buttons.buttons)

    yield all(
        buttons.map((button: SubscribeButton) =>
            call([api, api.put], 'buttons', button)
        )
    )
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
