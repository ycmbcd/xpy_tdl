import Vue from 'vue'
import Router from 'vue-router'
import Login from '../components/Login.vue'
import ToDoList from '../components/ToDoList.vue'
import ChangePwd from '../components/ChangePwd.vue'
import NoPage from '../components/NoPage.vue'
import UserManage from '../components/UserManage.vue'

Vue.use(Router)

const originalPush = Router.prototype.push;
Router.prototype.push = function push(location) {
  return originalPush.call(this, location).catch(err => err)
};

const router = new Router({
  mode: 'hash',
  routes: [
    {
      path: '*', // 404
      component: NoPage,
      meta: { title: '无此页面', icon: 'h-icon-error' }
    },
    {
      path: '/',
      name: 'Login',
      component: Login,
      meta: { title: '登录', icon: 'h-icon-complete' }
    },
    {
      path: '/ToDoList',
      name: 'ToDoList',
      component: ToDoList,
      meta: { title: '任务列表', icon: 'h-icon-task' }
    },
    {
      path: '/UserManage',
      name: 'UserManage',
      component: UserManage,
      meta: { title: '账号管理', icon: 'h-icon-users' }
    },
    {
      path: '/ChangePwd',
      name: 'ChangePwd',
      component: ChangePwd,
      meta: { title: '修改密码', icon: 'h-icon-lock' }
    }
  ],
  linkActiveClass: 'h-menu-li-selected' // 点击添加 active 类
})

router.beforeEach((to, from, next) => {
  if(to.path === '/'){
    // 如果访问首页则移除 accessToken，等待登录
    sessionStorage.removeItem('accessToken');
    next();
  }else{
    // 判断 accessToken = Running，处于登录状态
    if(sessionStorage.getItem('accessToken') == 'Running'){
      next();
    }else{
      next({
        path: '/'
      })
    }
  }
})

export default router;