<template>
  <div>
    <!-- 修改modal -->
    <Modal v-model="$store.state.storeEditModal">
      <div slot="header" class="blue bold">修改任务</div>
      <div style="width: 400px;">
        <div class="h-input-group mt10 auto" v-width="280">
          <span class="h-input-addon">日 期</span>
          <DatePicker readonly v-model="editDate" :format="'YYYY-MM-DD 星期w'"></DatePicker>
        </div>
        <div class="h-input-group mt10 auto" v-width="280">
          <span class="h-input-addon">工作内容</span>
          <input type="text" v-model="editWork" />
        </div>
        <div class="h-input-group mt10 auto" v-width="280">
          <span class="h-input-addon">月报类型</span>
          <Select v-model="editType" :datas="allType" keyName="name" titleName="name"></Select>
        </div>
        <p v-width="280" style="margin-left:15%">
          <span slot="title">完成结果：<span class="green">{{editValue}}%</span></span>
          <Slider v-model="editValue" :showtip="false"></Slider>
        </p>
        <div class="h-input-group mt10 auto" v-width="280">
          <span class="h-input-addon">正常工时（小时）</span>
          <input type="number" v-model="editTime" />
        </div>
        <div class="h-input-group mt10 auto" v-width="280">
          <span class="h-input-addon">加班（小时）</span>
          <input type="number" v-model="editTimeOther" />
        </div>
        <div class="h-input-group mt10 auto" v-width="280">
          <span class="h-input-addon">存在问题</span>
          <input type="text" v-model="editAsk" />
        </div>
        <div class="h-input-group mt10 auto" v-width="280">
          <span class="h-input-addon">拟采取措施</span>
          <input type="text" v-model="editDo" />
        </div>
      </div>
      <div slot="footer">
        <Button :disabled="editWork=='' || editType=='' || !editType || !editDate || editTime == ''" class="h-btn h-btn-text-blue" @click="updateEdit()">
          <i class="h-icon-edit"></i> 修 改
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
      editId: "",
      editDate: "",
      editWork: "",
      editValue: 0,
      editType: "",
      editTime: 0,
      editTimeOther: 0,
      editAsk: "",
      editDo: "",
      allType:[]
    }
  },
  mounted() {
    this.getType();
  },
  methods:{
    // 关闭 Modal
    closeModal(){
      let _this = this;
      _this.$store.commit("storeEditModal", false);
    },

    // 修改数据
    updateEdit(){
      let _this = this;
      let arr = _this.editDate.split(' ');
      let editDate = arr[0];
      let editWeek = arr[1];
      let qsData = qs.stringify({
        update_edit: _this.editId,
        editWeek: editWeek,
        editDate: editDate,
        editWork: _this.editWork,
        editValue: _this.editValue,
        editType: _this.editType,
        editTime: _this.editTime,
        editTimeOther: _this.editTimeOther,
        editAsk: _this.editAsk,
        editDo: _this.editDo,
      });
      _this.$axios
        .post("/api/list.php", qsData)
        .then(function (res) {
          if(res.data == 'ok'){
            _this.$Notice["success"](`修改完成。`);
            _this.$parent.getTDL();
            _this.closeModal();
          }
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    
    // 获取要修改的数据
    getEdit(id){
      let _this = this;
      _this.getType();
      let qsData = qs.stringify({
        get_edit: id,
      });

      _this.editId = id;

      _this.$axios
        .post("/api/list.php", qsData)
        .then(function (res) {
          _this.editDate = res.data.t_date + ' ' + res.data.t_week;
          _this.editWork = res.data.t_work;
          _this.editValue = Number(res.data.t_value);
          _this.editType = res.data.t_type;
          _this.editTime = res.data.t_time;
          _this.editTimeOther = res.data.t_time_other;
          _this.editAsk = res.data.t_ask;
          _this.editDo = res.data.t_do;
        })
        .catch(function (error) {
          console.log(error);
        });
    },

    // 月报类型
    getType(){
      let _this = this;
      _this.allType = [];
      let qsData = qs.stringify({
        getType: '',
      });

      _this.$axios
        .post("/api/type.php", qsData)
        .then(function(res) {
          for(let item of res.data){
            _this.allType.push({'id':item.id,'name':item.m_type})
          }
        })
        .catch(function(error) {
          console.log(error);
        });
    },
  }
}
</script>
