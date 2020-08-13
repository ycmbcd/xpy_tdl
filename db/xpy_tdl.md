> 创建数据库
```sql
create database xpy_tdl;
```

> 使用数据库
```sql
use xpy_tdl;
```

> 连接测试
```sql
create table ping(
    id int(2) auto_increment primary key,
    ping_info int(1)
)engine=InnoDB default charset='utf8mb4';
```

> 职员表
```sql
create table tdl_user(
    id int(9) auto_increment primary key,
    u_id int(9),        # 账号
    u_name varchar(10), # 用户名
    u_pwd varchar(20),  # 密码
    u_type varchar(10) # 账户类型
)engine=InnoDB default charset='utf8mb4';
```

> 创建管理员初始密码
```sql
insert into tdl_user values('1', '100', 'ycmbcd', '123456', 'admin');
```

> 创建月报类型表
```sql
create table tdl_type(
    id int(9) auto_increment primary key,
    u_id int(9),        # 归属id
    m_type varchar(200)  # 月报类型
)engine=InnoDB default charset='utf8mb4';
```

> 创建部门表
```sql
create table tdl_group(
    id int(9) auto_increment primary key,
    m_group varchar(200)  # 月报类型
)engine=InnoDB default charset='utf8mb4';
```

> 创建 td_list
```sql
create table tdl_list(
    id int(9) auto_increment primary key,
    u_id int(9),
    t_week varchar(10),        # 星期
    t_date date,  # 日期
    t_work varchar(200),  # 工作内容
    t_type varchar(200), # 月报类型
    t_value varchar(10),  # 完成成果
    t_time varchar(10),  # 正常工时
    t_time_other varchar(10),  # 加班
    t_ask varchar(10),  # 问题
    t_do varchar(10)  # 措施
)engine=InnoDB default charset='utf8mb4';
```