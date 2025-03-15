import { call, takeEvery, put } from 'redux-saga/effects'

import * as lifecycle from '../store/lifecycle.store'

function lifecycleSaga(): () => any {
  return function* () {
    yield takeEvery(lifecycle.INIT, ready)
  }
}

function* ready() {
  yield put(lifecycle.ready());
}

export default lifecycleSaga
