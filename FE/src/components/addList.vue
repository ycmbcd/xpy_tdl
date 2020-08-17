<template>
  <button class="h-btn" @click="resetAdd();getHasTime();addModal = true">
    <span><v-icon name="plus"/> 新增一条</span>
    <Modal v-model="addModal">
      <div slot="header">新增一条</div>
      <div style="width: 400px;">
        <div class="h-input-group mt10 auto" v-width="280">
          <span class="h-input-addon">日 期</span>
          <DatePicker readonly v-model="addDate" :format="'YYYY-MM-DD 星期w'"></DatePicker>
        </div>
        <div class="h-input-group mt10 auto" v-width="280">
          <textarea v-model="addWork" placeholder="工作内容"  v-width="280" size="s"></textarea>
        </div>
        <div class="h-input-group mt10 auto" v-width="280">
          <span class="h-input-addon">月报类型</span>
          <Select v-model="addType" :datas="allType" keyName="name" titleName="name"></Select>
        </div>
        <p v-width="280" style="margin-left:15%">
          <span slot="title">完成结果：<span class="green">{{addValue}}%</span></span>
          <Slider v-model="addValue" :showtip="false"></Slider>
        </p>
        <div class="h-input-group mt10 auto" v-width="280">
          <span class="h-input-addon">正常工时（小时）</span>
          <input type="number" v-model="addTime" />
        </div>
        <div class="h-input-group mt10 auto" v-width="280">
          <span class="h-input-addon">加班（小时）</span>
          <input type="number" v-model="addTimeOther" />
        </div>
        <div class="h-input-group mt10 auto" v-width="280">
          <span class="h-input-addon">存在问题</span>
          <input type="text" v-model="addAsk" />
        </div>
        <div class="h-input-group mt10 auto" v-width="280">
          <span class="h-input-addon">拟采取措施</span>
          <input type="text" v-model="addDo" />
        </div>
      </div>
      <div slot="footer">
        <Button class="h-btn" :disabled="addWork=='' || addType=='' || !addType || !addDate" @click="addList()" color="primary"><v-icon name="plus"/> 添加</Button>
        <button class="h-btn" @click="addModal=false"><v-icon name="ban"/> 取消</button>
      </div>
    </Modal>
  </button>
</template>


<script>
import qs from "qs";
import moment from 'moment';

export default {
  data() {
    return {
      addModal: false,
      addDate: "",
      addWork: "",
      addValue: 100,
      addType: "",
      addTime: 0,
      addTimeOther: 0,
      addAsk: "无",
      addDo: "",
      allType:[],
      hasTime: 0,
    };
  },
  watch: {
    addDate(val){
      var _this = this;
      _this.getHasTime();
    },
    addTime(val){
      var _this = this;
      if(val > _this.hasTime){
        _this.addTime = _this.hasTime;
      }
      if(val < 0){
        _this.addTime = _this.hasTime;
      }
    }
  },
  methods:{
    // 获取剩余工作时长
    getHasTime(){
      let _this = this;
      let arr = _this.addDate.split(' ');
      let addDate = arr[0];
      let qsData = qs.stringify({
        getHasTime: addDate,
      });

      _this.$axios
        .post("/api/list.php", qsData)
        .then(function(res) {
          _this.addTime = 7.5 - res.data;
          _this.hasTime = 7.5 - res.data;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    // 重置数据
    resetAdd(){
      let _this = this;
      _this.addWork = "";
      _this.addValue = 100;
      _this.addType = "";
      _this.addTime = 0;
      _this.addTimeOther = 0;
      _this.addAsk = "无";
      _this.addDo = "";
      _this.setToday();
      _this.getType();
    },
    // 设置日期
    setToday(){
      let _this = this;
      _this.addDate = moment().format('YYYY-MM-DD dddd');
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
          res.data.forEach(item => {
            _this.allType.push({'id':item.id,'name':item.m_type})
          });
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    // 添加一条
    addList(){
      let _this = this;
      // 拆分日期和星期
      let arr = _this.addDate.split(' ');
      let addDate = arr[0];
      let addWeek = arr[1];
      if(addWeek == 'Monday'){
        addWeek = '星期一';
      }
      if(addWeek == 'Tuesday'){
        addWeek = '星期二';
      }
      if(addWeek == 'Wednesday'){
        addWeek = '星期三';
      }
      if(addWeek == 'Thursday'){
        addWeek = '星期四';
      }
      if(addWeek == 'Friday'){
        addWeek = '星期五';
      }
      if(addWeek == 'Saturday'){
        addWeek = '星期六';
      }
      if(addWeek == 'Sunday'){
        addWeek = '星期七';
      }
      let qsData = qs.stringify({
        add_tdl: '',
        addWeek: addWeek,
        addDate: addDate,
        addWork: _this.addWork,
        addValue: _this.addValue,
        addType: _this.addType,
        addTime: _this.addTime,
        addTimeOther: _this.addTimeOther,
        addAsk: _this.addAsk,
        addDo: _this.addDo,
      });

      _this.$axios
        .post("/api/list.php", qsData)
        .then(function(res) {
          if(res.data == 'ok'){
            _this.$parent.getTDL();
            _this.addModal = false;
          }else{
            _this.$parent.getTDL();
            _this.$Notice["error"](`添加失败，请重试。`);
          }
        })
        .catch(function(error) {
          console.log(error);
        });
    },

  },
  mounted () {
    this.setToday();
    this.getType();
    this.getHasTime();
  },
};
</script>

<style lang="stylus" scoped>
.fa-icon{
  width 10px
  height 13px
}
</style>