<template>
  <Layout :headerFixed="headerFixed" class="layout">
    <!-- 页头 -->
    <HHeader theme="dark">
      <!-- logo -->
      <div class="layout-logo">
        <div class="f_left">
          <i class="h-icon-menu"></i>
        </div>
        <h3 class="f_left ml10">ToDoList</h3>
      </div>
      <!-- 菜单栏  -->
      <ul
        class="h-menu h-menu-dark h-menu-mode-horizontal"
        v-show="$store.state.pingStatus == 'Running'"
      >
        <router-link exact to="/ToDoList" tag="li" class="h-menu-li">
          <div content="任务列表" placement="right" class="h-menu-show">
            <span class="h-menu-show-icon">
              <i class="h-icon-task"></i>
            </span>
            <span class="h-menu-show-desc">任务列表</span>
          </div>
        </router-link>
        <router-link exact to="/TableExcel" tag="li" class="h-menu-li" v-show="$store.state.storeuType === 'admin'">
          <div content="统计报表" placement="right" class="h-menu-show">
            <span class="h-menu-show-icon">
              <i class="h-icon-inbox"></i>
            </span>
            <span class="h-menu-show-desc">统计报表</span>
          </div>
        </router-link>
        <router-link exact to="/setCommonType" tag="li" class="h-menu-li" v-show="$store.state.storeuType === 'admin'">
          <div content="公共月报类型" placement="right" class="h-menu-show">
            <span class="h-menu-show-icon">
              <i class="h-icon-message"></i>
            </span>
            <span class="h-menu-show-desc">公共月报类型</span>
          </div>
        </router-link>
        <router-link exact to="/UserManage" tag="li" class="h-menu-li" v-show="$store.state.storeuType === 'admin'">
          <div content="账号管理" placement="right" class="h-menu-show">
            <span class="h-menu-show-icon">
              <i class="h-icon-user"></i>
            </span>
            <span class="h-menu-show-desc">账号管理</span>
          </div>
        </router-link>
        <router-link exact to="/setGroup" tag="li" class="h-menu-li" v-show="$store.state.storeuType === 'admin'">
          <div content="部门管理" placement="right" class="h-menu-show">
            <span class="h-menu-show-icon">
              <i class="h-icon-users"></i>
            </span>
            <span class="h-menu-show-desc">部门管理</span>
          </div>
        </router-link>
        <router-link exact to="/ChangePwd" tag="li" class="h-menu-li">
          <div content="修改密码" placement="right" class="h-menu-show">
            <span class="h-menu-show-icon">
              <i class="h-icon-lock"></i>
            </span>
            <span class="h-menu-show-desc">修改密码</span>
          </div>
        </router-link>
      </ul>
      <!-- 退出 -->
      <div class="logout f_right" @click="$logout()" v-show="$store.state.pingStatus == 'Running'">
        <v-icon name="sign-out-alt" />&nbsp;退出系统
      </div>
      <!-- 用户信息 -->
      <div
        class="u_info f_right"
        v-show="$store.state.pingStatus == 'Running'"
      >工号：{{ $store.state.storeuID }} &nbsp;&nbsp;&nbsp;&nbsp;姓名：{{ $store.state.storeuName }}</div>
      <div class="clear"></div>
    </HHeader>
    <!-- 内容 -->
    <Content style="padding: 0px 50px;">
      <!-- 当前位置 -->
      <div class="h-breadcrumb" style="margin: 16px 0px;">
        <span class="h-breadcrumb-item">
          <span class="h-breadcrumb-item-title">
            <i class="h-breadcrumb-item-icon h-icon-home"></i>
            <span class="h-breadcrumb-item-word"></span>
          </span>
        </span>
        <span class="h-breadcrumb-item">
          <span class="h-breadcrumb-item-separator">/</span>
          <span class="h-breadcrumb-item-title h-breadcrumb-item-current">
            <i class="h-breadcrumb-item-icon" :class="$route.meta.icon"></i>
            <span class="h-breadcrumb-item-word">{{$route.meta.title}}</span>
          </span>
        </span>
      </div>
      <div class="toDoBox" :style="{'minHeight':boxHeight}">
        <transition name="fade" mode="out-in">
          <router-view />
        </transition>
      </div>
    </Content>
    <!-- 页脚 -->
    <HFooter class="tdl_footer text-center">
      Copyright &copy; {{year}}
      <a href="https://github.com/ycmbcd/xpy_tdl" target="_blank"><i class="h-icon-github"></i> ycmbcd </a> /
      <span>{{$store.state.pingStatus}}</span>
      <span v-show="$store.state.storeuType === 'admin'" class="red">管理员</span>
    </HFooter>
  </Layout>
</template>

<script>
import Login from "./components/Login.vue";

export default {
  data: function () {
    return {
      boxHeight: "",
      year: new Date().getFullYear(),
      headerFixed: true,
      nowPageTxt: "任务列表",
      nowPageIcon: "h-icon-complete",
    };
  },
  components: {
    Login,
  },
  methods: {
    // 设置页面的高
    setHeight() {
      let _this = this;
      _this.boxHeight = document.documentElement.clientHeight - 120 + "px";
    },
  },
  mounted () {
    this.setHeight();
    this.$ping();
    // console.log(this.$store.state);
  },
};
</script>

<style lang="stylus">
.h-menu-show{
  padding: 15px 16px!important;
}
.h-menu-dark,.h-layout-header-theme-dark{
  background #444 !important
}
.layout .h-layout-header{
  height 54px
}
.layout .layout-logo{
  width: 130px !important;
  line-height 23px
}
.h-notify .h-notify-mask{
  background rgba(0,0,0,.3) !important
}
.u_info {
  line-height: 54px;
  font-size: 16px;
}
.mark_hover:hover{
  background #fde5a6
}
.bold
  font-weight bold
.mark
  background #ffe6e6 !important

.mark_name
  background #fff2cf
.logout {
  width: 100px;
  text-align: right;
  line-height: 54px;
  font-size: 16px;
}
tr:hover{
  background #f1f1f1
}
.logout:hover {
  cursor: pointer;
  color: #FFF;
}
.h-btn-s{
  background none !important
}

.h-menu {
  float: left;
  width: 60% !important;
  line-height: 24px !important;
  font-size: 16px;
}

.auto {
  margin: 0 auto;
}

.tagc {
  text-align: center;
}

.tagr {
  text-align: right;
}

.toDoBox {
  background: #ffffff;
  padding: 24px;
}

.f_left {
  float: left;
}

.f_right {
  float: right;
}

.clear {
  clear: both;
}

.mt10 {
  margin-top: 10px;
}
.ml10{
  margin-left: 10px;
}
.ml5{
  margin-left 5px
}
.mt20 {
  margin-top: 20px;
}
.ml20{
  margin-left 20px;
}
.f12{
  font-size 12px
}
.red {
  color #F30;
}
.blue {
  color #5a91ca
}
.yellow{
  color: #F60
}
.gray{
  color: #666
}
.green
  color green
.mt40 {
  margin-top: 40px;
}

.ml10 {
  margin-left: 10px;
}
.h-date-footer .h-btn.h-btn-primary{
  color: #000 !important;
}

.layout {
  background: #f0f2f5;

  .layout-logo {
    width: 180px;
    height: 31px;
    font-size: 20px;
    margin: 16px 24px 16px 0;
    float: left;
  }

  .h-layout-header {
    padding: 0 50px;
    line-height: 30px;
  }

  .h-layout-footer {
    padding: 24px 50px;
    color: rgba(0, 0, 0, 0.65);
    font-size: 12px;
  }
}

.tdl_footer{
  position fixed
  bottom 0
  z-index 120
  padding 6px !important;
  width: 100%;
  margin-left 0%;
  background #f0f2f5;
}

.fade-enter
  margin-top -10px
  opacity 0
.fade-leave
  margin-top 0
  opacity 1
.fade-enter-active
  transition-duration .4s
.fade-leave-active
  opacity 0
  margin-top -10px
  transition-duration .2s
</style>
