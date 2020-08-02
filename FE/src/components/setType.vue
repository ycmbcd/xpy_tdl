<template>
  <button class="h-btn" @click="getType();newType = '';typePanel = true">
    <i class="h-icon-setting"></i>
    <span>月报类型设置</span>
    <!-- 月报类型 -->
    <Modal v-model="typePanel">
      <div slot="header">月报类型</div>
      <div>
        <div class="h-input-group" v-width="300">
          <input type="text" v-model="newType" placeholder="输入月报类型" />
          <Button color="primary" @click="addType" :disabled="newType == ''"><i class="h-icon-plus"></i> 添加</Button>
        </div>
        <table class="table mt20">
          <tr class="table_th bg_blue">
            <td>月报类型名称</td>
            <td class="tagc">操作</td>
          </tr>
          <tr v-for="item of allType" :key="item.id">
            <td>{{ item.m_type }}</td>
            <td class="tagc">
              <Button @click="delType(item.id)" class="h-btn-text-red h-btn-xs">删除</Button>
            </td>
          </tr>
        </table>
      </div>
      <div slot="footer">
        <button class="h-btn" @click="typePanel=false">关闭</button>
      </div>
    </Modal>
  </button>
</template>

<script>
import qs from "qs";

export default {
  data() {
    return {
      typePanel: false,
      newType: "",
      allType: []
    };
  },
  methods:{
    // 获取月报类型
    getType(){
      let _this = this;
      let qsData = qs.stringify({
        getType: '',
      });

      _this.$axios
        .post("/api/type.php", qsData)
        .then(function(res) {
          _this.allType = res.data
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    // 删除月报类型
    delType(id){
      let _this = this;
      let qsData = qs.stringify({
        delType: id
      })
      _this.$axios.post("/api/type.php", qsData)
        .then(function(res) {
          if(res.data == 'ok'){
            _this.getType();
            _this.$Notice["success"](`已删除。`);
          }else{
            console.log(res);
            _this.$Notice["error"](`删除失败！`);
          }
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    // 添加月报类型
    addType(){
      let _this = this;
      let qsData = qs.stringify({
        setNewType: _this.newType,
      });

      _this.$axios
        .post("/api/type.php", qsData)
        .then(function(res) {
          if (res.data == "ban") {
            _this.$Notice["error"](`添加失败，请重试！`);
          } else if (res.data == "ok") {
            _this.$Notice["success"](`添加完成。`);
            _this.newType = '';
            _this.getType();
          } else if(res.data == 'has'){
            _this.$Notice["warn"](`已存在此项！`);
          } else {
            _this.$Notice["error"](`系统错误！`);
            console.log(res)
          }
        })
        .catch(function(error) {
          console.log(error);
        });
    }
  }
};
</script>

<style lang="stylus" scoped>
.table
  width: 100%
  height: 30px
  line-height: 30px
  border-collapse: collapse

.table td
  border: 1px solid #dedede
  padding: 4px 20px

.table_th
  font-weight: bolder
  background: #348a5b
  color: #fff

.bg_blue
  background #3f75bb
</style>
