<template>
    <div>
        <div>
      <input placeholder="新的部门名称" type="text" v-model="newGroup"/>
      <Button class="ml10" @click="addGroup()" color="primary" :disabled="newGroup==''">添加</Button>
    </div>
    <table class="table mt20">
      <tr class="table_th bg_red">
        <td class="tagc">序号</td>
        <td class="tagc">部门</td>
        <td class="tagc">删除</td>
      </tr>
      <tr v-for="(item,index) of allGroup" :key="item.id">
        <td class="tagc">{{ index + 1 }}</td>
        <td class="tagc">
          {{ item.m_group }}
        </td>
        <td class="tagc">
          <button
            @click="delShow = true;delName = item.m_group;delId = item.id"
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
      <div slot="header" class="red bold">删除部门</div>
      <div style="width: 400px;">是否要删除：<span class="green bold">{{ delName }}</span> ?</div>
      <div slot="footer">
        <Button class="h-btn h-btn-red" @click="delGroup()">
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
      allGroup: [],
      newGroup: '',
      delShow: false,
      delName: '',
      delId: '',
    }
  },
  mounted(){
    let _this = this;
    _this.getGroup();
  },
  methods:{
    // 删除部门
    delGroup(){
      let _this = this;
      let qsData = qs.stringify({
        delGroup: _this.delId
      })
      _this.$axios.post("/api/group.php", qsData)
        .then(function(res) {
          if(res.data == 'ok'){
            _this.getGroup();
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
    // 查询部门
    getGroup(){
      let _this = this;
      let qsData = qs.stringify({
        getGroup: '',
      });

      _this.$axios
        .post("/api/group.php", qsData)
        .then(function(res) {
          _this.allGroup = res.data
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    // 添加部门
    addGroup(){
      let _this = this;
      let qsData = qs.stringify({
        newGroup: _this.newGroup,
      });

      _this.$axios
        .post("/api/group.php", qsData)
        .then(function(res) {
          if (res.data == "ban") {
            _this.$Notice["error"](`添加失败，请重试！`);
          } else if (res.data == "ok") {
            _this.$Notice["success"](`添加完成。`);
            _this.newGroup = '';
            _this.getGroup();
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
.bg_red
  background #b51100
</style>
