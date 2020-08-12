<template>
    <div>
        <div>
      <input placeholder="新的公共月报类型" type="text" v-model="newCommonType"/>
      <Button class="ml10" @click="addCommonType()" color="primary" :disabled="newCommonType==''">添加</Button>
    </div>
    <table class="table mt20">
      <tr class="table_th bg_blue">
        <td class="tagc">序号</td>
        <td class="tagc">公共月报类型</td>
        <td class="tagc">删除</td>
      </tr>
      <tr v-for="(item,index) of allCommonType" :key="item.id">
        <td class="tagc">{{ index + 1 }}</td>
        <td class="tagc">
          {{ item.m_type }}
        </td>
        <td class="tagc">
          <button
            @click="delShow = true;delType = item.m_type;delId = item.id"
            type="button"
            class="h-btn h-btn-text-red h-btn-s h-btn-no-border"
          >
            <i class="h-icon-trash"></i>
          </button>
        </td>
      </tr>
    </table>

    <!-- 删除模态框 -->
    <Modal v-model="delShow">
      <div slot="header" class="red bold">删除公共月报类型</div>
      <div style="width: 400px;">是否要删除：<span class="green bold">{{ delType }}</span> ?</div>
      <div slot="footer">
        <Button class="h-btn h-btn-red" @click="delCommonType()">
          <i class="h-icon-trash"></i> 删 除
        </Button>
        <button class="h-btn" @click="delShow = false">
          <i class="h-icon-close"></i> 取 消
        </button>
      </div>
    </Modal>
    </div>
</template>

<script>
import qs from 'qs'

export default {
  data(){
    return {
      allCommonType: [],
      newCommonType: '',
      delShow: false,
      delType: '',
      delId: '',
    }
  },
  mounted(){
    let _this = this;
    _this.getCommonType();
  },
  methods:{
    // 删除公共月报类型
    delCommonType(){
      let _this = this;
      let qsData = qs.stringify({
        delType: _this.delId
      })
      _this.$axios.post("/api/type.php", qsData)
        .then(function(res) {
          if(res.data == 'ok'){
            _this.getCommonType();
            _this.$Notice["success"](`已删除。`);
            _this.delShow = false
          }else{
            console.log(res);
            _this.$Notice["error"](`删除失败！`);
          }
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    // 查询公共月报类型
    getCommonType(){
      let _this = this;
      let qsData = qs.stringify({
        getCommonType: '',
      });

      _this.$axios
        .post("/api/type.php", qsData)
        .then(function(res) {
          _this.allCommonType = res.data
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    // 添加公共月报类型
    addCommonType(){
      let _this = this;
      let qsData = qs.stringify({
        newCommonType: _this.newCommonType,
      });

      _this.$axios
        .post("/api/type.php", qsData)
        .then(function(res) {
          if (res.data == "ban") {
            _this.$Notice["error"](`添加失败，请重试！`);
          } else if (res.data == "ok") {
            _this.$Notice["success"](`添加完成。`);
            _this.newCommonType = '';
            _this.getCommonType();
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
}
</script>

<style lang="stylus" scoped>
.table {
  width: 500px;
  height: 30px;
  line-height: 30px;
  border-collapse: collapse;
  margin-bottom: 100px;
}

.table td {
  padding 0 6px
  border: 1px solid #dedede;
}

.table_th {
  font-weight: bolder;
  background: #348a5b;
  color: #fff;
}
.bg_blue
  background #3f75bb
</style>
