<template>
  <div>
    <!-- 日期选择组 -->
    <div class="buttons f_left">
      <!-- 日期区间控件 -->
      <DateRangePicker v-model="datePicker" format="YYYY-MM-DD"></DateRangePicker>
      <!-- 周报 -->
      <button class="h-btn ml5" @click="downWeek()">
        <i class="h-icon-inbox green"></i>
        <span>统计周报表</span>
      </button>
      <!-- 月控件 -->
      <DatePicker
        style="width: 86px"
        class="ml20"
        v-model="changeMonth"
        type="month"
        :clearable="clearable"
        readonly
      ></DatePicker>
      <!-- 月报 -->
      <button class="h-btn ml5" @click="downMonth()">
        <v-icon name="moon" style="width: 12px;height: 12px;" class="blue" />
        <span>统计月报表</span>
      </button>
      <!-- 年控件 -->
      <DatePicker
        style="width: 66px"
        class="ml20"
        v-model="changeYear"
        type="year"
        :clearable="clearable"
        readonly
      ></DatePicker>
      <!-- 年报 -->
      <button class="h-btn ml5" @click="downYear()">
        <v-icon name="inbox" style="width: 12px;height: 12px;" class="yellow" />
        <span>统计年报表</span>
      </button>
    </div>
    <div class="clear"></div>

    <!-- ToDoList -->
    <table class="table mt20">
      <tr class="table_th f12">
        <td style="width:3%" class="tagc">序号</td>
        <td style="width:4%" class="tagc">职 员</td>
        <td style="width:5%" class="tagc">星 期</td>
        <td style="width:6%" class="tagc">日 期</td>
        <td style>工作内容</td>
        <td style="width:12%">月报类型</td>
        <td style="width:5%" class="tagc">完成成果</td>
        <td style="width:6%" class="tagr">工时/h {{sum_t_time}}</td>
        <td style="width:6%" class="tagr">加班/h {{sum_t_time_other}}</td>
        <td style="width:8%">存在问题</td>
        <td style="width:8%">采取措施</td>
      </tr>
      <tr
        v-for="(item, index) of listData"
        :key="item.id"
        :class="{'mark':item.mark,'mark_name':nowMarkName == item.u_name}"
      >
        <td @click="item.mark = !item.mark" class="tagc gray bold">{{ index + 1 }}</td>
        <td class="tagc pointer mark_hover" @click="markName(item.u_name)">{{item.u_name}}</td>
        <td @click="item.mark = !item.mark" class="tagc">
          <input type="hidden" :value="item.id" />
          {{item.t_week}}
        </td>
        <td @click="item.mark = !item.mark" class="tagc">{{item.t_date}}</td>
        <td @click="item.mark = !item.mark">{{item.t_work}}</td>
        <td @click="item.mark = !item.mark">{{item.t_type}}</td>
        <td @click="item.mark = !item.mark" class="tagc red bold" v-show="item.t_value < 100">{{item.t_value}}%</td>
        <td @click="item.mark = !item.mark" class="tagc green bold" v-show="item.t_value == 100">{{item.t_value}}%</td>
        <td @click="item.mark = !item.mark" class="tagr bold blue">{{item.t_time}}</td>
        <td @click="item.mark = !item.mark" class="tagr bold blue">{{item.t_time_other}}</td>
        <td @click="item.mark = !item.mark">{{item.t_ask}}</td>
        <td @click="item.mark = !item.mark">{{item.t_do}}</td>
      </tr>
    </table>

    <!-- 分页 -->
    <Pagination class="mt10 pageBar" v-model="pageBar" :sizes="sizes" @change="getTDL"></Pagination>

    <!-- 下载模态框 -->
    <Modal v-model="ExcelDown">
      <div slot="header" class="green bold">下载{{ downTxt }}</div>
      <div style="width: 400px;">
        已生成：
        <span class="green">{{tableName}}</span>
      </div>
      <div slot="footer">
        <a :href="weekUrl" :download="tableName" @click="ExcelDown = false">
          <Button class="h-btn h-btn-green">
            <i class="h-icon-inbox"></i> 下 载
          </Button>
        </a>
        <button class="h-btn" @click="ExcelDown = false">
          <i class="h-icon-close"></i> 取 消
        </button>
      </div>
    </Modal>
  </div>
</template>

<script>
import qs from "qs";
import moment from "moment";

export default {
  data() {
    return {
      nowMarkName: "",
      ExcelDown: false,
      tableName: "",
      downTxt: "",
      datePicker: {},
      listData: [],
      delWork: "",
      delId: "",
      startDate: "",
      endDate: "",
      sizes: [10, 20, 30, 40, 50, 60, 70, 80, 90],
      pageBar: {
        page: 1,
        size: 10,
        total: 0,
      },
      nowPage: 1,
      nowSize: 10,
      changeMonth: "",
      changeYear: "",
      clearable: false,
      weekUrl: "",
    };
  },
  watch: {
    datePicker(newVal) {
      let _this = this;
      _this.startDate = newVal.start;
      _this.endDate = newVal.end;
      _this.getTDL();
    },
  },
  computed: {
    sum_t_time: function () {
      let _this = this;
      let sum = 0;
      if (_this.listData !== undefined) {
        _this.listData.forEach((item) => {
          sum += item.t_time - 0;
        });
      }
      return sum;
    },
    sum_t_time_other: function () {
      let _this = this;
      let sum = 0;
      if (_this.listData !== undefined) {
        _this.listData.forEach((item) => {
          sum += item.t_time_other - 0;
        });
      }
      return sum;
    },
  },
  methods: {
    // 姓名标记
    markName(name) {
      let _this = this;
      if(name == _this.nowMarkName){
        _this.nowMarkName = '';
      }else{
        _this.nowMarkName = name;
      }
    },
    // 统计年报表
    downYear() {
      let _this = this;
      _this.$Loading(); // loading
      let qsData = qs.stringify({
        down_year: _this.changeYear,
      });

      _this.$axios
        .post("/api/down_all_year.php", qsData)
        .then(function (res) {
          _this.downTxt = "统计年报表";
          _this.tableName = `年报表_${_this.changeYear}.xlsx`;
          _this.weekUrl = _this.$server + "/down/" + res.data;
          setTimeout(() => {
            _this.$Loading.close(); // 关闭loading
            _this.ExcelDown = true;
          }, 1000);
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    // 统计月报表
    downMonth() {
      let _this = this;
      _this.$Loading(); // loading
      let qsData = qs.stringify({
        down_month: _this.changeMonth,
      });

      _this.$axios
        .post("/api/down_all_month.php", qsData)
        .then(function (res) {
          _this.downTxt = "统计月报表";
          _this.tableName = `月报表_${_this.changeMonth}.xlsx`;
          _this.weekUrl = _this.$server + "/down/" + res.data;
          setTimeout(() => {
            _this.$Loading.close(); // 关闭loading
            _this.ExcelDown = true;
          }, 1000);
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    // 统计周报表
    downWeek(toPage) {
      let _this = this;
      _this.$Loading(); // loading
      let qsData = qs.stringify({
        down_week: "",
        startDate: _this.startDate,
        endDate: _this.endDate,
      });

      _this.$axios
        .post("/api/down_all_week.php", qsData)
        .then(function (res) {
          _this.downTxt = "周报统计表";
          _this.tableName = `周报统计表_${_this.startDate}→${_this.endDate}.xlsx`;
          _this.weekUrl = _this.$server + "/down/" + res.data;
          setTimeout(() => {
            _this.$Loading.close(); // 关闭loading
            _this.ExcelDown = true;
          }, 1000);
        })
        .catch(function (error) {
          console.log(error);
        });
    },

    // 获取ToDoList
    getTDL(toPage) {
      let _this = this;

      if (toPage) {
        (_this.nowPage = toPage.cur), (_this.nowSize = toPage.size);
      }

      let qsData = qs.stringify({
        get_all_tdl: "",
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
  },
  mounted() {
    let _this = this;
    // 获取本周
    let today = moment().format("E");
    _this.startDate = moment().day(1).format("YYYY-MM-DD");
    _this.endDate = moment().day(7).format("YYYY-MM-DD");
    _this.datePicker = { start: _this.startDate, end: _this.endDate };

    // 获取本月
    _this.changeMonth = moment().format("YYYY-MM");
    // 获取本年
    _this.changeYear = moment().format("YYYY");
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
  padding: 0 6px;
  border: 1px solid #dedede;
}

.table_th {
  font-weight: bolder;
  background: #348a5b;
  color: #fff;
}

.pageBar {
  position: fixed;
  bottom: 50px;
  right: 70px;
  background: #FFF;
  padding: 8px 20px;
  border-radius: 4px;
  box-shadow: 1px 1px 10px #e5eee9;
}
</style>