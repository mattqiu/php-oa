<?php
/*---------------------------------------------------------------------------
 小微OA系统 - 让工作更轻松快乐

 Copyright (c) 2013 http://www.smeoa.com All rights reserved.

 Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )

 Author:  jinzhu.yin<smeoa@qq.com>

 Support: https://git.oschina.net/smeoa/smeoa
 -------------------------------------------------------------------------*/

class MailaccountAction extends CommonAction {
	protected $config = array('app_type' => 'personal');
	public function index(){
		$mail_user = M("MailAccount") -> find(get_user_id());
		if (count($mail_user)) {
			$this -> assign('opmode', 'edit');
		} else {
			$this -> assign('opmode', 'add');
		}
		if(empty($mail_user)){
			$user_id = get_user_id();
			$user = M('user') -> find($user_id);
			$mail_user['id'] = $user_id;
			$mail_user['email'] = $user['email'];
			$mail_user['mail_name'] = '神洲酷奇-' .$user['name'];
			$mail_user['pop3svr'] = 'imap.exmail.qq.com';
			$mail_user['smtpsvr'] = 'smtp.exmail.qq.com';
			$mail_user['mail_id'] = $user['email'];
			$mail_user['mail_pwd'] = '';
		}
		$this -> assign('mail_user', $mail_user);
		$this -> display();
	}

	protected function _set_email($email) {
		$model = M("User");
		$user_id = get_user_id();
		$data['id'] = $user_id;
		$data['email'] = $email;
		$model -> save($data);
	}

	protected function _insert() {
		$model = M('MailAccount');
		if (false === $model -> create()) {
			$this -> error($model -> getError());
		}
		if (in_array('id', $model -> getDbFields())) {
			$model -> id = get_user_id();
		};
		if (in_array('user_name', $model -> getDbFields())) {
			$model -> user_name = get_user_name();
		};
		$email = $_POST['email'];
		//保存当前数据对象
		$list = $model -> add();
		if ($list !== false) {//保存成功
			$this -> _set_email($email);
			$this -> assign('jumpUrl', get_return_url());
			$this -> success('新增成功!');
		} else {
			//失败提示
			$this -> error('新增失败!');
		}
	}

	protected function _update() {

		$model = M('MailAccount');

		if (false === $model -> create()) {
			$this -> error($model -> getError());
		}
		if (in_array('id', $model -> getDbFields())) {
			$model -> id = get_user_id();
		};
		// 更新数据
		$email = $_POST['email'];
		$list = $model -> save();
		if (false !== $list) {
			//成功提示
			$this -> _set_email($email);
			$this -> assign('jumpUrl', get_return_url());
			$this -> success('编辑成功!');
		} else {
			//错误提示
			$this -> error('编辑失败!');
		}
	}

}
?>