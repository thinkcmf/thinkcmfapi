<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
namespace api\user\controller;

use cmf\controller\RestUserBaseController;
use think\Db;
use think\Validate;

class ProfileController extends RestUserBaseController
{
    // 用户密码修改
    public function changePassword()
    {
        $validate = new Validate([
            'old_password'     => 'require',
            'password'         => 'require',
            'confirm_password' => 'require|confirm:password'
        ]);

        $validate->message([
            'old_password.require'     => '请输入您的旧密码!',
            'password.require'         => '请输入您的新密码!',
            'confirm_password.require' => '请输入确认密码!',
            'confirm_password.confirm' => '两次输入的密码不一致!'
        ]);

        $data = $this->request->param();
        if (!$validate->check($data)) {
            $this->error($validate->getError());
        }

        $userId       = $this->getUserId();
        $userPassword = Db::name("user")->where('id', $userId)->value('user_pass');

        if (!cmf_compare_password($data['old_password'], $userPassword)) {
            $this->error('旧密码不正确!');
        }

        Db::name("user")->where('id', $userId)->update(['user_pass' => cmf_password($data['password'])]);

        $this->success("密码修改成功!");

    }

    // 用户绑定邮箱
    public function bindingEmail()
    {
        $validate = new Validate([
            'email'             => 'require|email',
            'verification_code' => 'require'
        ]);

        $validate->message([
            'email.require'             => '请输入您的邮箱!',
            'email.email'               => '请输入正确的邮箱格式!',
            'verification_code.require' => '请输入数字验证码!'
        ]);

        $data = $this->request->param();
        if (!$validate->check($data)) {
            $this->error($validate->getError());
        }

        $userId    = $this->getUserId();
        $userEmail = Db::name("user")->where('id', $userId)->value('user_email');

        if (!empty($userEmail)) {
            $this->error("您已经绑定邮箱!");
        }

        $errMsg = cmf_check_verification_code($data['email'], $data['verification_code']);
        if (!empty($errMsg)) {
            $this->error($errMsg);
        }

        Db::name("user")->where('id', $userId)->update(['user_email' => $data['email']]);

        $this->success("绑定成功!");
    }

    // 用户绑定手机号
    public function bindingMobile()
    {
        $validate = new Validate([
            'mobile'            => 'require',
            'verification_code' => 'require'
        ]);

        $validate->message([
            'email.require'             => '请输入您的邮箱!',
            'verification_code.require' => '请输入数字验证码!'
        ]);

        $data = $this->request->param();
        if (!$validate->check($data)) {
            $this->error($validate->getError());
        }

        if (!preg_match('/(^(13\d|15[^4\D]|17[13678]|18\d)\d{8}|170[^346\D]\d{7})$/', $data['mobile'])) {
            $this->error("请输入正确的手机格式!");
        }

        $userId = $this->getUserId();
        $mobile = Db::name("user")->where('id', $userId)->value('mobile');

        if (!empty($mobile)) {
            $this->error("您已经绑定手机!");
        }

        $errMsg = cmf_check_verification_code($data['mobile'], $data['verification_code']);
        if (!empty($errMsg)) {
            $this->error($errMsg);
        }

        Db::name("user")->where('id', $userId)->update(['mobile' => $data['mobile']]);

        $this->success("绑定成功!");
    }

}
