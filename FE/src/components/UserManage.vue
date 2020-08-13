<template>
  <div>
    <div>
      <Select class="select_bar" placeholder="请选择部门" v-model="newGroup" :datas="allGroup" keyName="m_group" titleName="m_group"></Select>
      <input placeholder="添加新员工" type="text" v-model="newUser"/>
      <Button class="ml10" @click="addUser()" color="primary" :disabled="newUser=='' || newGroup == ''">添加</Button>
    </div>
    <table class="table mt20">
      <tr class="table_th bg_yellow">
        <td class="tagc">ID</td>
        <td class="tagc">部 门</td>
        <td class="tagc">用户名</td>
        <td class="tagc">重置密码</td>
        <td class="tagc">操 作</td>
      </tr>
      <tr v-for="item of allUsers" :key="item.id">
        <td class="tagc">{{ item.u_id }}</td>
        <td class="tagc">{{ item.u_group }}</td>
        <td class="tagc">
          {{ item.u_name }}
        </td>
        <td class="tagc yellow">
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
            type="button"
            class="h-btn h-btn-text-blue h-btn-s h-btn-no-border"
            @click="editShow = true;setUName = item.u_name;setUID = item.u_id;newGroup = item.u_group"
          >
            <i v-show="item.u_id > 999" class="h-icon-edit"></i>
          </button>
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

    <!-- 修改用户名 model -->
    <Modal v-model="editShow">
      <div slot="header" class="blue bold">修改用户 {{ setUID }} — {{ setUName }} 为：</div>
      <div class="mt10">
        <Select class="select_bar" placeholder="请选择部门" v-model="newGroup" :datas="allGroup" keyName="m_group" titleName="m_group"></Select>
        <input type="text" v-model="setUName" placeholder="新的用户名">
      </div>
      <div slot="footer">
        <Button class="h-btn h-btn-text-blue" @click="updateUser()" :disabled="setUName=='' || newGroup == ''">
          <i class="h-icon-edit"></i> 修 改
        </Button>
        <button class="h-btn" @click="editShow = false">
          <i class="h-icon-close"></i> 取 消
        </button>
      </div>
    </Modal>

    <!-- 重置密码 model -->
    <Modal v-model="rePwdShow">
      <div slot="header" class="red bold">重置密码</div>
      <div style="width: 400px;">是否要重置：<span class="green bold">{{ setUID }} — {{ setUName }}</span> 的密码？</div>
      <div slot="footer">
        <Button class="h-btn h-btn-text-red" @click="resetPwd()">
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
      <h3 style="width: 400px;">是否要删除：<span class="green bold">{{ setUID }} — {{ setUName }}</span> ?
      </h3>
      <div class="red bold">注意：删除该员工，则相关的月报类型一并删除，任务列表记录不做删除。</div>
      <div slot="footer">
        <Button class="h-btn h-btn-red" @click="delUser()">
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
      newGroup: '',
      allGroup: [],
      newUser: '',
      allUsers: [],
      setUID: '',
      setUName: '',
      setUName: '',
      rePwdShow: false,
      delShow: false,
      editShow: false,
    }
  },
  mounted(){
    let _this = this;
    _this.getUsers();
    _this.getGroup();
  },
  methods: {
    // 查询部门
    getGroup(){
      let _this = this;
      let qsData = qs.stringify({
        getGroup: '',
      });

      _this.$axios
        .post("/api/group.php", qsData)
        .then(function(res) {
          console.log(res.data);
          _this.allGroup = res.data
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    // 修改用户名
    updateUser(){
      let _this = this;
      let qsData = qs.stringify({
        updateUser: _this.setUID,
        newName: _this.setUName,
        newGroup: _this.newGroup
      });
       _this.$axios
        .post("/api/user.php", qsData)
        .then(function(res) {
          if(res.data == 'ok'){
            _this.getUsers();
            _this.$Notice["success"](`修改完成。`);
            _this.editShow = false;
          }else{
            _this.$Notice["error"](`添加失败。`);
            console.log(res.data)
          }
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    // 重置密码
    resetPwd(){
      let _this = this;
      let qsData = qs.stringify({
        resetPwd: _this.setUID,
      });

      _this.$axios
        .post("/api/user.php", qsData)
        .then(function(res) {
          if(res.data == 'ok'){
            _this.getUsers();
            _this.$Notice["success"](`重置完成。`);
            _this.rePwdShow = false;
          }else{
            _this.$Notice["error"](`添加失败。`);
            console.log(res.data)
          }
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    // 删除员工
    delUser(){
      let _this = this;
      let qsData = qs.stringify({
        delUser: _this.setUID,
      });

      _this.$axios
        .post("/api/user.php", qsData)
        .then(function(res) {
          if(res.data == 'ok'){
            _this.getUsers();
            _this.$Notice["success"](`删除完成。`);
            _this.delShow = false;
          }else{
            _this.$Notice["error"](`添加失败。`);
            console.log(res.data)
          }
        })
        .catch(function(error) {
          console.log(error);
        });
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
        newGroup: _this.newGroup,
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
.select_bar{
  float left
  width 200px;
  margin-right: 10px;
}
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
.bg_yellow{
  background #ad6d36;
}
.h-btn.h-btn-s {
    padding: 5px 0px;
}
</style>