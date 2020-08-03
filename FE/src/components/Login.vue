<template>
  <div v-width="360" class="auto">
    <div class="h-panel mt40">
      <div class="h-panel-bar">
        <span class="h-panel-title">登录</span>
        <!-- <span class="h-panel-right"><a>找回密码</a></span> -->
      </div>
      <div class="h-panel-body">
        <div class="h-input-group mt10 auto" v-width="240">
          <span class="h-input-addon">账 号</span>
          <input type="number" v-model="u_id" v-on:keyup.enter="login" />
        </div>
        <div class="h-input-group mt10 auto" v-width="240">
          <span class="h-input-addon">密 码</span>
          <input type="password" v-model="u_pwd" v-on:keyup.enter="login" />
        </div>
        <Button
          class="mt20 loginBtn"
          color="blue"
          :disabled="u_id == '' || u_pwd == ''"
          @click="login()"
        >登 录</Button>
      </div>
    </div>
  </div>
</template>
<script>
import qs from "qs";

export default {
  name: "Login",
  data() {
    return {
      u_id: "",
      u_pwd: "",
    };
  },
  methods: {
    // 登录
    login() {
      let _this = this;

      let qsData = qs.stringify({
        login: "hello",
        u_id: _this.u_id,
        u_pwd: _this.u_pwd,
      });

      _this.$axios
        .post("/api/login.php", qsData)
        .then((res) => {
          if (res.data == "ban") {
            _this.$Notice["error"](`账号密码不匹配，请重试！`);
          } else if (res.data == "go") {
            _this.$ping();
            _this.$store.commit("pingStatus", "Running");
            _this.$router.push("ToDoList");
            _this.$Notice["success"](`欢迎您，${_this.$store.state.storeuName}！`);
          }
        })
        .catch((error) => {
          console.log(error);
          _this.$Notice["error"](`系统错误，请联系管理员！`);
        });
    },
  },
  mounted: function () {
    let _this = this;
    _this.$logout();
  },
};
</script>

<style lang="stylus" scoped>
.loginBtn {
  margin-left: 12%;
  width: 76%;
}
</style>
