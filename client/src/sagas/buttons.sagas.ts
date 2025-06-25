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

    yield takeEvery(buttons.ADD, add, apiClient)
    yield takeEvery(buttons.UPDATE_ITEM, save, apiClient)
    yield takeEvery(buttons.ADD_OR_UPDATE_REQUEST, addOrUpdateButton, apiClient)
    yield takeEvery(buttons.DELETE, deleteButton, apiClient)
}

function* addOrUpdateButton(api: SubscribeApiClient, action: Action) {
  const button: SubscribeButton = get(action, ['payload']);

  if (!button.id) {
    // Neuen Button erstellen
    const { result: createResult, error: createError } = yield call(
      [api, api.post],
      'buttons',
      {}
    );

    if (createError || !createResult?.id) {
      console.error('Fehler beim Erstellen des Buttons:', createError);
      return;
    }

    const newButtonWithId = {
      ...button,
      id: createResult.id,
    };

    yield call(
      [api, api.put],
      `buttons/${createResult.id}`,
      newButtonWithId
    );

    yield put(buttons.add_or_update_success(newButtonWithId));
  } else {
    // Existierenden Button updaten
    yield call(
      [api, api.put],
      `buttons/${button.id}`,
      button
    );

    yield put(buttons.add_or_update_success(button));
  }
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

  yield put(buttons.set_last_created_id(createResult.id))
}

function* save(api: SubscribeApiClient) {
    const buttons: SubscribeButton[] = yield select(selectors.buttons.buttons)

    yield all(
        buttons.map((button: SubscribeButton) =>
            call([api, api.put], `buttons/${button.id}`, button)
        )
    )
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
