export const state = () => ({
  drawer: true,
});

export const getters = {
  getDrawerState(state) { return state.drawer }
};

export const mutations = {
  setDrawer(state, status) { state.drawer = status }
};

export const actions = {
  drawer({ commit }, state) {
    return commit('setDrawer', state)
  }
};