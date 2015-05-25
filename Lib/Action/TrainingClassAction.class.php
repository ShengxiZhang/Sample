<?php
	class TrainingClassAction extends Action
	{
			
		public function index()
		{ 	
			header("Content-Type:text/html; charset=utf-8");
			  
			if(session('?username'))
			{
				$this->assign('username',session('username'));

				$news=M('article');	
				$count=$news->count();
			
				import('ORG.Util.Page');
				$page=new Page($count,8);
		
				$page->setConfig('prev', "&laquo; Previous");//???
				$page->setConfig('next', 'Next &raquo;');//???
				$page->setConfig('first', '&laquo; First');//???
				$page->setConfig('last', 'Last &raquo;');//????	
				$page->setConfig('theme',' %first% %upPage%  %linkPage%  %downPage% %end%');
				
				$show=$page->show();
		
				$news_list=$news->field(array('id','subject','author','createtime'))->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
				
				$this->filter($news_list);
				
				$this->assign('news_count',$count);
				$this->assign('title','Training classes');
				$this->assign('news_list',$news_list);
				$this->assign('page_method',$show);
							
				$this->display();
			}
			else
			{
				$this->error('Please log in first!!!');
			}	
		}
		
		private function filter($list)
		{
			foreach($list as $key=>$value)
			{			
				$list[$key]['createtime']=date("Y-m-d H:i:s",$value['createtime']);

				if(!$value['lastmodifytime']){
					$list[$key]['lastmodifytime']="?";
				}else{
					$list[$key]['lastmodifytime']=date("Y-m-d H:i:s",$value['lastmodifytime']);
				}		
				
				if(strlen($list[$key]['subject'])>80){
						$list[$key]['subject']=$this->cutString($list[$key]['subject'],0,20).'...';				
				}
			}
		}
    
     
		private function cutString($str, $from, $len)
		{
			return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.
						   '((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s',
						   '$1',$str);
		}
    }
?>