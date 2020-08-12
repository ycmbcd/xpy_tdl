export default {
  install(Vue,) {
    // 登出
    Vue.prototype.$logout = function () {
      let _this = this;
      _this.$axios
        .get("/api/login.php", {
          params: {
            logout: "bye",
          },
        })
        .then(function (res) {
          if (res.data == "bye") {
            sessionStorage.removeItem('accessToken');
            _this.$store.commit("pingStatus", "Ready");
            _this.$router.push("/");
          }
        })
        .catch(function (error) {
          console.log(error);
        });
    }
    // ping 
    Vue.prototype.$ping = function () {
      let _this = this;
      _this.$axios
        .get("/api/login.php", {
          params: {
            ping: "hello",
          },
        })
        .then((res) => {
          if (res.data.status === "ing") {
            _this.$store.commit("storeuName", res.data.u_name);
            _this.$store.commit("storeuID", res.data.u_id);
            _this.$store.commit("storeuType", res.data.u_type);
            _this.$store.commit("pingStatus", "Running");
            // 如果是运行状态，且在登录界面
            if (_this.$route.path == "/") {
              _this.logout();
              _this.$router.push("/");
            }
          } else {
            if (_this.$route.path !== "/") {
              _this.$Notice["success"](`你好，请重新登录使用 TDL`);
              _this.$router.push("/");
            }
          }
        })
        .catch((error) => {
          console.log(error);
        });
    }
  }
}
