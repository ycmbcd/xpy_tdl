<template>
  <div>
    <div>
      <input placeholder="添加新员工" type="text" v-model="newUser"/>
      <Button class="ml10" @click="addUser()" color="primary" :disabled="newUser==''">添加</Button>
    </div>
    <table class="table mt20">
      <tr class="table_th">
        <td class="tagc">ID</td>
        <td class="tagc">用户名</td>
        <td class="tagc">重置密码</td>
        <td class="tagc">删除</td>
      </tr>
      <tr v-for="item of allUsers" :key="item.id">
        <td class="tagc">{{ item.u_id }}</td>
        <td class="tagc">
          {{ item.u_name }}
          <button
            type="button"
            class="h-btn h-btn-text-blue h-btn-s h-btn-no-border"
          >
            <i v-show="item.u_id > 999" class="h-icon-edit"></i>
          </button>
        </td>
        <td class="tagc">
          <span v-show="item.u_id < 1000">—</span>
          <button
            v-show="item.u_id > 999"
            @click="rePwdShow = true;setUName = item.u_name;setUID = item.u_id"
            type="button"
            class="h-btn h-btn-s h-btn-no-border"
          >
            <i class="h-icon-refresh"></i> 重置
          </button>
        </td>
        <td class="tagc">
          <span v-show="item.u_id < 1000">—</span>
          <button
            v-show="item.u_id > 999"
            @click="delShow = true;setUName = item.u_name;setUID = item.u_id"
            type="button"
            class="h-btn h-btn-text-red h-btn-s h-btn-no-border"
          >
            <i class="h-icon-trash"></i>
          </button>
        </td>
      </tr>
    </table>

    <!-- 重置密码 model -->
    <Modal v-model="rePwdShow">
      <div slot="header" class="red bold">重置密码</div>
      <div style="width: 400px;">是否要重置：<span class="green bold">{{ setUID }} — {{ setUName }}</span> 的密码？</div>
      <div slot="footer">
        <Button class="h-btn h-btn-text-red" @click="delList()">
          <i class="h-icon-refresh"></i> 重 置
        </Button>
        <button class="h-btn" @click="rePwdShow = false">
          <i class="h-icon-close"></i> 取 消
        </button>
      </div>
    </Modal>

    <!-- 删除账户 model -->
    <Modal v-model="delShow">
      <div slot="header" class="red bold">删除账号</div>
      <div style="width: 400px;">是否要删除：<span class="green bold">{{ setUID }} — {{ setUName }}</span> ?</div>
      <div slot="footer">
        <Button class="h-btn h-btn-red" @click="delList()">
          <i class="h-icon-trash"></i> 删 除
        </Button>
        <button class="h-btn" @click="delShow = false">
          <i class="h-icon-close"></i> 取 消
        </button>
      </div>
    </Modal>

    <div>默认密码：<span class="red">Just.1t</span></div>
  </div>
</template>

<script>
import qs from 'qs'

export default {
  data(){
    return{
      newUser: '',
      allUsers: [],
      setUID: '',
      setUName: '',
      rePwdShow: false,
      delShow: false
    }
  },
  mounted(){
    let _this = this;
    _this.getUsers();
  },
  methods: {
    // 删除员工
    rePwd(id){
      alert(id)
    },
    // 查询员工
    getUsers(){
      let _this = this;
      let qsData = qs.stringify({
        getUsers: '',
      });

      _this.$axios
        .post("/api/user.php", qsData)
        .then(function(res) {
          _this.allUsers = res.data;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    // 添加员工
    addUser(){
      let _this = this;
      let qsData = qs.stringify({
        addUser: _this.newUser,
      });

      _this.$axios
        .post("/api/user.php", qsData)
        .then(function(res) {
          if(res.data == 'ok'){
            _this.newUser = '';
            _this.getUsers();
            _this.$Notice["success"](`添加成功。`);
          }else{
            _this.$Notice["error"](`添加失败。`);
            console.log(res.data)
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
</style>