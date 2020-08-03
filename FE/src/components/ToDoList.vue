<template>
  <div>
    <!-- 日期选择组 -->
    <div class="buttons f_left">
      <!-- 日期区间控件 -->
      <DateRangePicker v-model="datePicker" format="YYYY-MM-DD"></DateRangePicker>
      <!-- 周报 -->
      <button class="h-btn ml5" @click="downWeek()">
        <i class="h-icon-inbox green"></i>
        <span>周报</span>
      </button>
      <!-- 月控件 -->
      <DatePicker style="width: 86px" class="ml20" v-model="changeMonth" type="month" :clearable="clearable" readonly></DatePicker>
      <!-- 月报 -->
      <button class="h-btn ml5">
        <v-icon name="moon" style="width: 12px;height: 12px;" class="blue" />
        <span>月报</span>
      </button>
      <!-- 年控件 -->
      <DatePicker style="width: 66px" class="ml20" v-model="changeYear" type="year" :clearable="clearable" readonly></DatePicker>
      <!-- 年报 -->
      <button class="h-btn ml5">
        <v-icon name="inbox" style="width: 12px;height: 12px;" class="yellow" />
        <span>年报</span>
      </button>
    </div>
    <!-- 按钮组 -->
    <div class="buttons f_right">
      <div class="h-btn-group">
        <!-- 新建 -->
        <addList />
        <!-- 月报类型设置 -->
        <setType />
      </div>
    </div>
    <div class="clear"></div>

    <!-- ToDoList -->
    <table class="table mt20">
      <tr class="table_th f12">
        <td style="width:5%" class="tagc">星 期</td>
        <td style="width:7%" class="tagc">日 期</td>
        <td style="">工作内容</td>
        <td style="width:12%">月报类型</td>
        <td style="width:5%" class="tagc">完成成果</td>
        <td style="width:6%" class="tagr">工时/h</td>
        <td style="width:6%" class="tagr">加班/h</td>
        <td style="width:8%">存在问题</td>
        <td style="width:8%">采取措施</td>
        <td style="width:5%" class="tagc">操作</td>
      </tr>
      <tr v-for="item of listData" :key="item.id">
        <td class="tagc">
          <input type="hidden" :value="item.id" />
          {{item.t_week}}
        </td>
        <td class="tagc">{{item.t_date}}</td>
        <td>{{item.t_work}}</td>
        <td>{{item.t_type}}</td>
        <td class="tagc red bold" v-show="item.t_value < 100">{{item.t_value}}%</td>
        <td class="tagc green bold" v-show="item.t_value == 100">{{item.t_value}}%</td>
        <td class="tagr bold blue">{{item.t_time}}</td>
        <td class="tagr bold blue">{{item.t_time_other}}</td>
        <td>{{item.t_ask}}</td>
        <td>{{item.t_do}}</td>
        <td class="tagc">
          <!-- 修改按钮 -->
          <button
            @click="showEditModal(item.id);"
            type="button"
            class="h-btn h-btn-text-blue h-btn-s h-btn-no-border"
          >
            <i class="h-icon-edit"></i>
          </button>
          <!-- 删除按钮 -->
          <button
            @click="showDelModal(item.id, item.t_work);"
            type="button"
            class="h-btn h-btn-text-red h-btn-s h-btn-no-border"
          >
            <i class="h-icon-trash"></i>
          </button>
        </td>
      </tr>
    </table>

    <!-- 分页 -->
    <Pagination class="mt10 pageBar" v-model="pageBar" :sizes="sizes" @change="getTDL"></Pagination>

    <!-- 编辑模态框 -->
    <editModal ref="childEdit" />

    <!-- 删除模态框 -->
    <delModal ref="childDel" />

    <!-- 下载模态框 -->
    <Modal v-model="weekDown">
      <div slot="header" class="green bold">下载{{ downTxt }}</div>
      <div style="width: 400px;">您的「{{ downTxt }}」已生成：{{tableName}}</div>
      <div slot="footer">
        <a :href="weekUrl" :download="tableName" @click="weekDown = false">
          <Button class="h-btn h-btn-green">
            <i class="h-icon-inbox"></i> 下 载
          </Button>
        </a>
        <button class="h-btn" @click="weekDown = false">
          <i class="h-icon-close"></i> 取 消
        </button>
      </div>
    </Modal>
  </div>
</template>

<script>
import addList from "./addList.vue";
import setType from "./setType.vue";
import editModal from "./modal/editModal.vue";
import delModal from "./modal/delModal.vue";
import qs from "qs";
import moment from "moment";

export default {
  data() {
    return {
      weekDown: false,
      tableName:'',
      downTxt: '',
      datePicker:{},
      listData: [],
      delWork: '',
      delId: '',
      startDate: '',
      endDate: '',
      sizes: [7, 14, 21, 28],
      pageBar: {
        page: 1,
        size: 7,
        total: 0
      },
      nowPage: 1,
      nowSize: 7,
      changeMonth: '',
      changeYear: '',
      clearable: false,
      weekUrl: '',
    };
  },
  components: {
    setType,
    addList,
    editModal,
    delModal,
  },
  watch: {
    datePicker(newVal){
      console.log(newVal);
      let _this = this;
      _this.startDate = newVal.start;
      _this.endDate = newVal.end;
      _this.getTDL();
    }
  },
  methods: {
    // 周报
    downWeek(toPage){
      let _this = this;
    console.log(_this.startDate+' '+_this.endDate)
      _this.$Loading(); // loading
      let qsData = qs.stringify({
        down_week: "",
        startDate: _this.startDate,
        endDate: _this.endDate,
      });

      _this.$axios
        .post("/api/down_week.php", qsData)
        .then(function (res) {
          _this.downTxt = '周报';
          _this.tableName = `${_this.$store.state.storeuName}工作周报${_this.startDate}→${_this.endDate}.xlsx`;
          _this.weekUrl = _this.$server + '/down/' + res.data;
          setTimeout(() => {
            _this.$Loading.close(); // 关闭loading
            _this.weekDown = true;
          }, 1000);
        })
        .catch(function (error) {
          console.log(error);
        });
    },

    // 获取ToDoList
    getTDL(toPage) {
      let _this = this;

      if(toPage){
        _this.nowPage = toPage.cur,
        _this.nowSize = toPage.size
      }
      
      let qsData = qs.stringify({
        get_tdl: "",
        startDate: _this.startDate,
        endDate: _this.endDate,
        nowPage: _this.nowPage,
        nowSize: _this.nowSize,
      });

      _this.$axios
        .post("/api/list.php", qsData)
        .then(function (res) {
          _this.listData = res.data.list;
          _this.pageBar.total = res.data.count;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    // 删除modal
    showDelModal(id, title){
      let _this = this;
      // 弹窗
      _this.$store.commit("storeDelModal", true);
      // 调用子方法传递数据
      _this.$refs.childDel.getDel(id, title);
    },

    // 编辑modal
    showEditModal(id) {
      let _this = this;
      // 弹窗
      _this.$store.commit("storeEditModal", true);
      // 调用子组件方法获取编辑数据
      _this.$refs.childEdit.getEdit(id);
    },
  },
  mounted() {
    let _this = this;
    // 获取本周
    let today = moment().format('E');
    _this.startDate = moment().day(1).format('YYYY-MM-DD');
    _this.endDate = moment().day(7).format('YYYY-MM-DD');
    _this.datePicker = {'start': _this.startDate,'end': _this.endDate};

    // 获取本月
    _this.changeMonth = moment().format('YYYY-MM');
    // 获取本年
    _this.changeYear = moment().format('YYYY');
    // 获取列表
    _this.getTDL();
  },
};
</script>

<style lang="stylus" scoped>
.h-btn.h-btn-s {
  padding: 5px 2px;
}

.table {
  width: 100%;
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
.pageBar{
  position fixed;
  bottom: 40px;
  right: 70px;
  background #FFF;
  padding: 8px 20px;
  border-radius 4px;
  box-shadow 1px 1px 10px #e5eee9
}
</style>