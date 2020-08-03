import axios from "axios";

const state = {
    user: null,
};

const getters = {
    user: (state) => state.user,
};

const mutations = {
    setUser(state, user) {
        state.user = user;
    },
};

const actions = {
    /**
     * ユーザの新規登録処理を行う
     * @param {*} param0
     * @param {*} data
     */
    async register({ commit }, data) {
        const response = await axios.post("/api/register", data);
        commit("setUser", response.data);
        return response;
    },

    /**
     * ログイン済みユーザを取得する
     * @param {*} param0
     */
    async getLoginUser({ commit }) {
        const response = await axios.get("/api/user");
        const user = response.data ? response.data : null;
        commit("setUser", user);
        return response;
    },

    /**
     * ログイン処理を行う
     * @param {*} param0
     * @param {*} data
     */
    async login({ commit }, data) {
        const response = await axios.post("/api/login", data);
        commit("setUser", response.data);
        return response;
    },

    /**
     * ログアウト処理を行う
     * @param {*} param0
     */
    async logout({ commit }) {
        commit("setUser", null);
        try {
            await axios.post("/api/logout");
        } catch (error) {
            alert(`logoutError:${error.response.status}`);
        }
    },

    clearUser({ commit }) {
        commit("setUser", null);
    },

    /**
     * 認証メール再送信処理を行う
     * @param {*} param0
     */
    async reSend({ commit }) {
        const response = await axios.post("/api/reSend");
        commit("setUser", response.data);
        return response;
    },
};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions,
};
