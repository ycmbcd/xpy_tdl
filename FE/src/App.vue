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
        <router-link exact to="/ChangePwd" tag="li" class="h-menu-li">
          <div content="修改密码" placement="right" class="h-menu-show">
            <span class="h-menu-show-icon">
              <i class="h-icon-lock"></i>
            </span>
            <span class="h-menu-show-desc">修改密码</span>
          </div>
        </router-link>
        <router-link exact to="/UserManage" tag="li" class="h-menu-li">
          <div content="员工管理" placement="right" class="h-menu-show">
            <span class="h-menu-show-icon">
              <i class="h-icon-users"></i>
            </span>
            <span class="h-menu-show-desc">账号管理</span>
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
      >工号：{{ uID }}&nbsp;&nbsp;&nbsp;&nbsp;姓名：{{ uName }}</div>
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
        <router-view />
      </div>
    </Content>
    <!-- 页脚 -->
    <HFooter class="tdl_footer text-center">
      Copyright © {{year}}
      <a href="https://github.com/ycmbcd" target="_blank">ycmbcd</a> /
      <span>{{$store.state.pingStatus}}</span>
    </HFooter>
  </Layout>
</template>

<script>
import Login from "./components/Login.vue";

export default {
  data: function () {
    return {
      boxHeight: "",
      uName: "1",
      uID: "2",
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
  },
};
</script>

<style lang="stylus">
.h-notify .h-notify-mask{
  background rgba(0,0,0,.3) !important
}
.u_info {
  line-height: 64px;
}
.bold
  font-weight bold

.logout {
  width: 100px;
  text-align: right;
  line-height: 64px;
  font-size: 16px;
}

.logout:hover {
  cursor: pointer;
  color: #FFF;
}

.h-menu {
  float: left;
  width: 60% !important;
  line-height: 34px !important;
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
.red {
  color #F30;
}
.blue {
  color #5a91ca
}
.yellow{
  color: #F60
}
.green
  color green
.mt40 {
  margin-top: 40px;
}

.ml10 {
  margin-left: 10px;
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
    font-size: 14px;
  }
}

.tdl_footer{
  position fixed
  bottom 0
  width 100%
  background rgba(0, 88, 44,.1)
  z-index 120
}
</style>
