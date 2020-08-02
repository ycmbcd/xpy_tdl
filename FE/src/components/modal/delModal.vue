<template>
  <div>
    <Modal v-model="$store.state.storeDelModal">
      <div slot="header" class="red bold">删除任务</div>
      <div style="width: 400px;">是否要删除：{{ delWork }}</div>
      <div slot="footer">
        <Button class="h-btn h-btn-text-red" @click="delList()">
          <i class="h-icon-trash"></i> 删 除
        </Button>
        <button class="h-btn" @click="closeModal()">
          <i class="h-icon-close"></i> 取 消
        </button>
      </div>
    </Modal>
  </div>
</template>

<script>
import qs from 'qs';

export default {
  data() {
    return {
      delWork: "",
      delId: "",
    };
  },
  methods: {
    // 删除数据
    getDel(id, title){
      let _this = this;
      _this.delId = id;
      _this.delWork = title;
    },
    // 关闭 Modal
    closeModal(){
      let _this = this;
      _this.$store.commit("storeDelModal", false);
    },
    // 删除
    delList() {
      let _this = this;
      let qsData = qs.stringify({
        del_tdl: _this.delId,
      });

      _this.$axios
        .post("/api/list.php", qsData)
        .then(function (res) {
          if (res.data == "ok") {
            _this.$Notice["success"](`已删除完成。`);
            _this.closeModal();
            // 子调用父组件方法
            _this.$parent.getTDL();
          } else {
            _this.$Notice["error"](`删除失败，请重试。`);
          }
        })
        .catch(function (error) {
          console.log(error);
        });
    },
  }
}
</script>
