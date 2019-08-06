# 新闻后台管理系统

## 背景

本项目完成于`2019.01`  整理于 `2019.08`

毕业设计 && 提升自己

基于慕课网 singwa 老师的两个教程 [Swoole入门到实战](https://coding.imooc.com/class/197.html) 🔗 [PHP开发高可用高安全App后端](https://coding.imooc.com/class/135.html) 整合实现

主要内容是：一个体育新闻赛事直播系统。分为APP客户端和后台新闻管理端。

经历了3个月，终于从自己 *搭建云主机➡️**源码编译LNMP服务器及各种拓展**(3天机房通宵，以后绝不浪费生命)➡️安卓APP开发➡️不断跟着教程编码➡️debug➡️完成*

在这里非常感谢singwa老师、以及曾经的自己努力。虽然只换来了一份优秀毕设。（ps：工作真难找

## 技术栈

- Thinkphp5.*
- Swoole 4.*
- Nginx
- Mysql 
- Redis
- 第三方接口
  - 极光推送 JPush
  - 阿里云对象存储 OSS
  - 阿里云短信服务 SMS
- 前端基于hui-admin  `webSocket`、`ajax`



## 主机部署

1. php 7.*
   1. swoole 4.0 以上拓展
   2. redis 拓展
   3. curl 拓展 （用于模拟http请求，执行定时任务。 可以使用 Guzzle 代替）
   4. mysql 拓展
   5. gd 图像库拓展  （此拓展缺失无法处理图像）
2. mysql 5.* （最新版随意）
3. redis  （最新版随意）
4. nginx （最新版随意）



## 项目部署

1. 修改`composer.json`   

   根据需求 添加 `jpush`、`aliyun-oss`、`aliyun-sms` 仓库

   运行命令 `composer install`

2. 添加 `swoole-ide-helper`

3. 填写 `application`下的配置文件并测试



## 文件介绍

- `application` 应用主目录
  - `admin/controller` 后台新闻管理控制器
  - `admin/view` 后台新闻页面
  - `api`  安卓app接口控制器
  - `common/lib` 公共第三方调用类
  - `common/model` 公共数据库模型类
  - `common/validate` 公共字符校验规则类
  - `index` 直播系统控制器 (swoole)
- `public` 静态资源目录



## 最后

鉴于项目间隔时间太久、整理出来的内容可能多少有残缺。

本项目也是根据我个人的想法进行更改，如有疑问可以交流 QQ577429696

如有兴趣可以将两个**<u>*教学视频</u>***👬给你
