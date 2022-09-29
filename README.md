### 一. 项目背景&目标
#### 1.1 项目背景
世纪哲学问题：今天吃什么
#### 1.2 项目目标
用投票方式解决上述问题

### 二. 修订版本
|日期|版本|作者|备注|
|----|----|----|----|
|2022-09-27|v.10|[@duikerddd](https://github.com/duikerddd)|文档新建|
### 三.系统功能框架图
todo
### 四.系统功能需求
#### 用户管理
提供注册登录功能
能设置头像
1块钱永久会员，提供会员服务
#### 投票脚本
每个工作日5:00pm生成投票链接
#### 提醒投票
每个工作日5:30pm提醒未投票人员投票
#### 投票结果
每个工作日6:00pm公布投票结果
#### 投票页面
每周吃过的不再计入选项
页面由选项和条状统计图组成
条状统计图记录投票人员和数量
投票选项共三项，随机生成
#### 选项后台
选项池初始化10个选项
每个用户每天都可以添加一个临时选项，会员可以添加两个
#### 五.安装步骤

    $ git clone https://github.com/ericzyh/dinner

建表
    $ mysql -u xxx -h xxx
    $ source dinner/table.sql

nginx 
    $ cp dinner/nginx.conf ~/nginx/site-enabled/dinner.xxx.com
    $ nginx -s reload

修改数据库配置
    $ cp dinner/backend/config/db.config.php_example dinner/backend/config/db.config.php
    $ vim dinner/backend/config/db.config.php

修改应用配置(播报机器人\生成投票时间\播报时间等)
    $ cp dinner/backend/config/app.config.php_example dinner/backend/config/app.config.php
    $ vim dinner/backend/config/app.config.php

