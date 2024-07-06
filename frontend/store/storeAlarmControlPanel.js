// store/dashboard.js

export const state = () => ({
  BuildingTypes: null,
  AddressTypes: null,
  DeviceTypes: null,
  DeviceModels: null,
});

export const mutations = {
  RESET_STATE(state) {
    Object.keys(state).forEach((key) => {
      state[key] = null;
    });
  },

  BuildingTypes(state, data) {
    state.BuildingTypes = data;
  },
  AddressTypes(state, data) {
    state.AddressTypes = data;
  },
  DeviceTypes(state, data) {
    state.DeviceTypes = data;
  },
  DeviceModels(state, data) {
    state.DeviceModels = data;
  },
};

export const actions = {
  resetState({ commit }) {
    commit("RESET_STATE");
  },
};
