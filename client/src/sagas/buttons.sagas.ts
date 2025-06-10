import { SubscribeApiClient } from "src/lib/api";
import { all, call, fork, put, select, takeEvery } from 'redux-saga/effects'
import { get } from 'lodash'
import { createApi } from "./api";
import { takeFirst } from "./helper";
import { selectors } from "../store";

import * as buttons from '../store/buttons.store'
import * as lifecycle from '../store/lifecycle.store';
import { SubscribeButton } from "../types/buttons.types";
import { Action } from "redux-saga";

function* buttonsSaga(): any {
    const apiClient: SubscribeApiClient = yield createApi()
    yield fork(initialize, apiClient)

    yield takeEvery([buttons.UPDATE, buttons.UPDATE_ITEM], save, apiClient)
    yield takeEvery(buttons.ADD, create, apiClient)
}

function* save(api: SubscribeApiClient) {
    const buttons: SubscribeButton[] = yield select(selectors.buttons.buttons)

    yield all(
        buttons.map((button: SubscribeButton) =>
            call([api, api.put], `buttons/${button.id}`, button)
        )
    )
}

function* create(api: SubscribeApiClient, action: Action) {
    const newButton: SubscribeButton = get(action, ['payload'])

    const { result: createResult, error: createError } = yield call(
        [api, api.post],
        'buttons',
        {}
    );

    if (createError || !createResult?.id) {
      console.error("Fehler beim Erstellen des Buttons:", createError);
      return;
    }

    yield call(
        [api, api.put],
        `buttons/${createResult.id}`,
        newButton
    );
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
