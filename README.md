ThinkCMF 5.0 API 1.1.0
===============
### 环境推荐
> php5.5+

> mysql 5.6+

> 打开rewrite

### 安装步骤

1. 请先安装ThinkCMF5 https://github.com/thinkcmf/thinkcmf
2. 再把本项目代码覆盖到 thinkcmf5根目录(最终目录参考http://www.kancloud.cn/thinkcmf/doc/266477)
```
thinkcmf  根目录
├─api                   api目录
├─app                   应用目录
│  ├─portal             门户应用目录
│  │  ├─config.php      应用配置文件
│  │  ├─common.php      模块函数文件
│  │  ├─controller      控制器目录
│  │  ├─model           模型目录
│  │  └─ ...            更多类库目录
│  ├─ ...               更多应用
│  ├─command.php        命令行工具配置文件
│  ├─common.php         应用公共(函数)文件
│  ├─config.php         应用(公共)配置文件
│  ├─database.php       数据库配置文件
│  ├─tags.php           应用行为扩展定义文件
│  └─route.php          路由配置文件
├─data                  数据目录
│  ├─conf               动态配置目录
│  ├─runtime            应用的运行时目录（可写）
│  └─ ...               更多
├─public                WEB 部署目录（对外访问目录）
│  ├─api                api入口目录
│  ├─plugins            插件目录
│  ├─static             静态资源存放目录(css,js,image)
│  ├─themes             前后台主题目录
│  │  ├─admin_simpleboot3  后台默认主题
│  │  └─simpleboot3            前台默认主题
│  ├─upload             文件上传目录
│  ├─index.php          入口文件
│  ├─robots.txt         爬虫协议文件
│  ├─router.php         快速测试文件
│  └─.htaccess          apache重写文件
├─simplewind         
│  ├─cmf                CMF核心库目录
│  ├─extend             扩展类库目录
│  ├─thinkphp           thinkphp目录
│  └─vendor             第三方类库目录（Composer）
├─composer.json         composer 定义文件
├─LICENSE.txt           授权说明文件
├─README.md             README 文件
├─think                 命令行入口文件
```

### API手册
http://www.kancloud.cn/thinkcmf/cmf5api

QQ群:100828313 (付费)

### ThinkCMF小程序 ThinkCMFlite发布
https://www.kancloud.cn/thinkcmf/cmf5api/451391

### 更新日志
#### 1.1.0
[核心]
* 增加是否收藏判断
* 增加登录API,返回用户信息
* 优化登录时设备类型判断
* 优化手机号验证，支持国际手机号
* 优化评论列表，无须用户登录
* 优化rest api基类设备类型获取
* 优化rest api用户token生成逻辑
* 修复幻灯片列表，后台排序无效 #8
* 修复UserModel.php被写死了cmf_前缀 #9
* 修复文章附件url转化问题
* 修复评论成功后，评论对象评论数字段没有加1
* 修复添加收藏报错
* 修复收藏判断错误
* 修复文章列表发布时间格式问题，统一为时间戳
* 修复评论用户头像链接错误
* 修复不加载动态路由

[门户应用]
* 增加指定分类的子分类列表
* 增加文章点击数更新
* 增加相关文章接口
* 增加文章收藏接口
* 增加取消文章收藏接口







