<?php
	class MemberAction extends Action{

		public function _empty(){
			$this->member();
		}

		protected function member(){
			$this->display('member');
		}

		public function signin(){
			if(is_null($this->_post('email')))
			{
				$this->member();
			}
			else{
				$email=$this->_post('email');
				$password=md5($this->_post('password'));
				$user=M('User')->where("email='$email'")->find();
				if(!$user){
					$this->error("user not exists");
				}else if($user['password']!=$password){
					$this->error("wrong password");
				}else{
					//登录成功后跳转到主页
					//$name=$user['firstname'].'_'.$user['lastname'];
					$name=substr($email,0,strrpos($email,'@'));
			        $name=str_replace(".", " ", $name);
					$_SESSION['uid']=$user['uid'];
					$_SESSION['username']=$name;
					$_SESSION['score']=$user['score'];
					$_SESSION['email']=$user['email'];
					$this->success('登录成功','__APP__/TrainingClass');

				}
			}
		}

		public function signup(){
			echo "sign in";
			if(is_null($this->_post('email'))){
				$this->error('email is null');
			}else{
				$email=$this->_post('email');
				$user_module=M('User');
				$user_isexist=$user_module->where("email='$email'")->find();
				if(!$user_isexist){
					$udata['email']=$this->_post('email');
					$udata['password']=md5($this->_post('password'));
					$User=M('User');
					$User->create($udata);
					$User->add();

					//注册成功后跳转到主页
					$this->success('');					
				}
			}
		}
	}
