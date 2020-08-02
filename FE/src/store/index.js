import Vue from 'vue'
import Vuex from 'vuex'

// 挂载Vuex
Vue.use(Vuex)

// 创建VueX对象
const store = new Vuex.Store({
    state:{
      pingStatus: 'Ready',
      storeEditModal: false,
      storeDelModal: false,
    },
    mutations:{
      // ping
      pingStatus(state, status) {
        state.pingStatus = status;
        sessionStorage.setItem('accessToken', status)
      },
      // 编辑modal
      storeEditModal(state, status) {
        state.storeEditModal = status;
      },
      // 删除modal
      storeDelModal(state, status) {
        state.storeDelModal = status;
      }
      
  }
})

export default store
