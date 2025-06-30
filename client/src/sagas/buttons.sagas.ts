import { SubscribeApiClient } from "src/lib/api";
import { call, fork, put, takeEvery } from 'redux-saga/effects'
import { get } from 'lodash'
import { createApi } from "./api";
import { takeFirst } from "./helper";

import * as buttons from '../store/buttons.store'
import * as lifecycle from '../store/lifecycle.store';
import { SubscribeButton } from "../types/buttons.types";
import { Action } from "redux-saga";

function* buttonsSaga(): any {
    const apiClient: SubscribeApiClient = yield createApi()
    yield fork(initialize, apiClient)

    yield takeEvery(buttons.ADD, add, apiClient)
    yield takeEvery(buttons.UPDATE_ITEM, save, apiClient)
    yield takeEvery(buttons.DELETE, deleteButton, apiClient)
}


function* add(api: SubscribeApiClient) {
  const { result: createResult, error: createError } = yield call(
    [api, api.post],
    "buttons",
    {}
  );

  if (createError || !createResult?.id) {
    console.error("Fehler beim Erstellen des Buttons:", createError);
    return;
  }

  const { result } = yield api.get('buttons')

  if (!result) {
    return
  }

  yield put(buttons.set(result))
  yield put(buttons.set_last_created_id(createResult.id))
}

function* save(api: SubscribeApiClient, action: Action) {
  const payload: any = get(action, 'payload', null)
  if (!payload) return

  const id = get(payload, 'id', null)
  const rawProp = get(payload, 'prop', null)
  const value = get (payload, 'value', null)

  if (!id || !rawProp || !value) return

  yield call([api, api.put], `buttons/${id}`, {[rawProp]: value})
}

function* deleteButton(api: SubscribeApiClient, action: Action) {
  const button: SubscribeButton = get(action, ['payload']);
  if (button) {
    yield call([api, api.delete], `buttons/${button.id}`)
  }
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
