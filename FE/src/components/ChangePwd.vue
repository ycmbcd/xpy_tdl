<template>
  <div v-width="360" class="auto">
    <div class="h-panel mt40">
      <div class="h-panel-bar">
        <span class="h-panel-title">修改密码</span>
      </div>
      <div class="h-panel-body">
        <div class="h-input-group mt10 auto" v-width="240">
          <span class="h-input-addon">原 密 码</span>
          <input type="password" v-model="o_pwd" />
        </div>
        <div class="h-input-group mt10 auto" v-width="240">
          <span class="h-input-addon">新 密 码</span>
          <input type="password" v-model="new_pwd" />
        </div>
        <div class="h-input-group mt10 auto" v-width="240">
          <span class="h-input-addon">重复新密码</span>
          <input type="password" v-model="re_pwd" />
        </div>
        <Button
          class="mt20 resetBtn"
          color="red"
          :disabled="o_pwd == '' || new_pwd == '' || re_pwd == '' || new_pwd !== re_pwd"
          @click="rePwd()"
        >确认修改</Button>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: "Login",
  data() {
    return {
      o_pwd: "",
      new_pwd: "",
      re_pwd: ""
    };
  },
  methods: {
    // 修改密码
    rePwd() {
      let _this = this;
      _this.$axios
        .get("/api/login.php", {
          params: {
            change_pwd: "change",
            o_pwd: _this.o_pwd,
            new_pwd: _this.new_pwd,
            re_pwd: _this.re_pwd
          }
        })
        .then(function(res) {
          if (res.data == "ban") {
            _this.$Notice["error"](`旧密码不匹配，请重试！`);
          } else if (res.data == "ok") {
            _this.$Notice["success"](`修改完成，请重新登录。`);
            _this.$store.commit("pingStatus", "Ready");
            _this.$router.push("/");
          }else{
            _this.$Notice["error"](`系统错误，请联系管理员！`);
            console.log(res.data);
          }
        })
        .catch(function(error) {
          console.log(error);
          _this.$Notice["error"](`系统错误，请联系管理员！`);
        });
    }
  }
};
</script>

<style lang="stylus" scoped>
.resetBtn {
  margin-left 12%
  width 76%
}
</style>