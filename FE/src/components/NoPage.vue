<template>
  <div>
    <h2>404 页面不存在，<span style="color: #F00">{{sec}}</span> 秒后跳转到登录。</h2>
  </div>
</template>

<script>
export default {
  data(){
    return {
      sec: 3,
    }
  },
  mounted: function(){
    setTimeout(() => {
      this.$router.push('/')
    }, 4000);
    setInterval(() => {
      this.sec = this.sec - 1
    }, 1000);
  }
}
</script>