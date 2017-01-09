# 简介

PHP CAS Server是一个基于Laravel框架开发的CAS服务端实现，旨在解决使用PHP技术栈的中小型公司因无法对Java版CAS服务端二次开发而放弃使用CAS的问题，因此本项目的核心目标之一就是易于扩展。

# 功能

* 目前已经实现了CAS协议v1/v2/v3版本的服务端核心逻辑（现在已经支持Proxy相关的接口！）。
* 用户管理，包含新增、修改、搜索用户，启用、禁用用户，设置、取消管理员。
* 服务管理，包含新增、修改、搜索服务，启用、禁用服务。
* 国际化，默认支持中文和英文，可自行添加语言包。
* 登录方式插件化，默认支持邮箱+密码登录，可通过插件新增登录方式，如微信登录，也可以通过配置关闭密码登录功能。

# 运行环境

* php >= 5.5.9

# 安装

## 通过composer安装

1. `composer create-project leo108/php_cas_server php_cas_server dev-master`
2. `npm install` 或者 `yarn`
3. `gulp`

## 下载压缩包

[压缩包列表](https://github.com/leo108/php_cas_server/releases)

# 配置

如果是通过压缩包方式安装，需要先将根目录下的`.env.example`文件复制一份并命名为`.env`，然后执行`php artisan key:generate`。

所有的配置均通过修改`.env`文件来完成。

## 基础配置

|配置项|默认值|说明|
|-----|-----|---|
|APP_ENV|`local`|运行环境，`local`代表本地开发环境，`production`代表线上环境|
|APP_KEY|随机值|加密所需的key，不需要修改|
|APP_DEBUG|`true`|是否开启Debug模式，设置成`false`关闭|
|APP_LOG_LEVEL|`debug`|日志级别，可选项有`debug`/`info`/`notice`/`warning`/`error`/`critical`/`alert`/`emergency`|
|APP_URL|`http://localhost`|访问网址，需要在前面加上`http(s)://`|
|APP_LOCALE|`en`|语言，默认提供`en`和`cn`|

---

## 数据库配置

修改所有以`DB_`开头的配置项。设置完成之后需要执行`php artisan migrate`来完成数据库的初始化。

## CAS配置

|配置项|默认值|说明|
|-----|-----|---|
|CAS_LOCK_TIMEOUT|`5000`|CAS令牌锁定时间，单位毫秒|
|CAS_TICKET_EXPIRE|`300`|CAS令牌过期时间，单位秒|
|CAS_TICKET_LEN|`32`|CAS令牌长度，推荐不低于32|
|CAS_PROXY_GRANTING_TICKET_EXPIRE|`7200`|CAS代理令牌过期时间，单位秒|
|CAS_PROXY_GRANTING_TICKET_LEN|`64`|CAS proxy-granting令牌长度，推荐不低于64|
|CAS_PROXY_GRANTING_TICKET_IOU_LEN|`64`|CAS proxy-granting ticket IOU长度，推荐不低于64|
|CAS_VERIFY_SSL|`true`|在回调PGT Url的时候是否校验ssl，测试时可以关闭，线上环境强烈建议打开此选项|
|CAS_SERVER_ALLOW_RESET_PWD|`true`|是否允许用户通过邮箱找回密码|
|CAS_SERVER_ALLOW_REGISTER|`true`|是否允许用户注册|
|CAS_SERVER_DISABLE_PASSWORD_LOGIN|`false`|是否禁用密码登录|
|CAS_SERVER_NAME|`Central Authentication Service`|CAS Server的站点名称（用于展示）|

## 反向代理配置

当PHP CAS Server运行在反向代理后面时，需要设置相关配置才可正常运行

|配置项|默认值|说明|
|-----|-----|---|
|TRUSTED_PROXIES|`127.0.0.1`|反向代理服务器ip，多个用英文逗号`,`隔开，支持指定ip：`127.0.0.1`和子网：`127.0.0.1/24`，以下配置只有访问ip在这个列表中时才生效。|
|TRUSTED_HEADER_CLIENT_IP|`X_FORWARDED_FOR`|该请求头保存用户的ip|
|TRUSTED_HEADER_CLIENT_HOST|`X_FORWARDED_HOST`|该请求头保存用户访问的域名|
|TRUSTED_HEADER_CLIENT_PROTO|`X_FORWARDED_PROTO`|该请求头保存用户访问时使用的协议|
|TRUSTED_HEADER_CLIENT_PORT|`X_FORWARDED_PORT`|该请求头保存用户访问的端口|

## 初始化数据库 && 创建管理员

在项目的根目录执行`php artisan migrate`来初始化数据库结构。

执行`php artisan make:admin --password=yourpassword`创建一个管理员账号。

# 登录插件

对于使用压缩包安装的用户，安装登录插件需要先安装composer，具体安装方式请自行搜索。

## 安装已有的插件

目前已有微信([leo108/php_cas_server_oauth_wechat](https://github.com/leo108/php_cas_server_oauth_wechat))和微博([leo108/php_cas_server_oauth_weibo](https://github.com/leo108/php_cas_server_oauth_weibo))两个插件，以微信插件为例：

1. 引入插件：`composer require leo108/php_cas_server_oauth_wechat`
2. 注册插件：修改`config/app.php`文件，在`providers`这一项的末尾加上`Leo108\CASServer\OAuth\WeChat\CASOAuthWeChatServiceProvider::class`
3. 注册微信操作类：在`app/Providers/AppServiceProvider.php`的`namespace App\Providers;`下方加入`use EasyWeChat\Foundation\Application`；在`register`方法中加入
    
    ```
    //注册微信公众平台登录方式
    $this->app->bind(
        'cas.server.wechat.mp',
        function () {
            return new Application(
                [
                    'app_id' => '公众平台AppId',
                    'secret' => '公众平台AppSecret',
                    'oauth'  => [
                        'scopes' => ['snsapi_userinfo'],
                    ],
                ]
            );
        }
    );
    //注册微信开放平台登录方式
    $this->app->bind(
        'cas.server.wechat.open',
        function () {
            return new Application(
                [
                    'app_id' => '开放平台AppId',
                    'secret' => '开放平台AppSecret',
                    'oauth'  => [
                        'scopes' => ['snsapi_login'],
                    ],
                ]
            );
        }
    );
    ```
    可以只注册公众平台或开放平台中的一个，具体参数请参考[EasyWechat文档](https://easywechat.org/zh-cn/docs/)
4. 导出数据库变更文件：`php artisan vendor:publish --provider="Leo108\CASServer\OAuth\WeChat\CASOAuthWeChatServiceProvider" --tag="migrations"`
5. 执行数据库变更： `php artisan migrate`

## 自定义插件

创建自定义插件通常需要如下几个步骤

1. 创建数据库结构变更文件：集成第三方登录时，需要记录用户在第三方平台上的id，这样才能将第三方的用户和本站用户关联起来。因此需要一个数据库结构变更文件在`user_oauth`表中创建一个对应的字段来储存这个id。
2. 创建插件类：插件类需要继承`Leo108\CASServer\OAuth\Plugin`类，并实现`gotoAuthUrl`和`getOAuthUser`。当有用户希望通过此插件提供的登录方式来登录时，CAS系统会调用`gotoAuthUrl`方法来获取第三方登录的授权url并跳转过去；当用户在第三方授权完毕跳转回CAS时，系统会调用`getOAuthUser`来获取授权用户信息，这个方法必须返回一个`Leo108\CASServer\OAuth\OAuthUser`对象。
3. 将插件注册到插件中心（PluginCenter）：这个步骤可以直接在`App\Providers\AppServiceProvider`中实现，也可以单独写一个ServiceProvider然后在`config/app.php`中注册。

具体插件的写法，可以参照已有的插件代码。
