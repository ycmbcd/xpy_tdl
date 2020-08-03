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
      storeuID: '',
      storeuName: '',
      storeuType:''
    },
    mutations:{
      // ping
      pingStatus(state, status) {
        state.pingStatus = status;
        sessionStorage.setItem('accessToken', status)
      },
      // 用户名
      storeuName(state, status) {
        state.storeuName = status;
      },
      // 用户ID
      storeuID(state, status) {
        state.storeuID = status;
      },
      // 用户类型
      storeuType(state, status) {
        state.storeuType = status;
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
