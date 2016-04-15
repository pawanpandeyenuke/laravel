<?php 
 
    public function privateGroupAdd()
    {
 	 	try{
	    	$input = Request::all();

	    	if(isset($input['member_id'])&& isset($input['owner_id']) && $input['title']!=null )
	            {
	            	$user = User::find($input['owner_id']);
	            	if(!($user))
	            		throw new Exception("No user found");					
	            	else {

	            		$error=0;
	            		// $members=explode(',',$input['broadcast_members']);

	            		$row1=DB::table('group')->where('owner_id',$input['owner_id'])->where('title',$input['title'])->value('id');
		            		
			    		if($row1!=null)
			    			throw new Exception("Group Name already exists!");
		            
	            		foreach ($input['member_id'] as $key => $value) {
		            		$row=null;
		            		$row=DB::table('friends')->where('user_id',$input['owner_id'])->where('friend_id',$value)->where('status','Accepted')->value('id');
		            		if($row==null) {
		            		 	$error=$value;
		            	    	break;
		            		}

	            		}

	            		if($error!=0)
	            			throw new Exception($error." is not a friend and can't be added to group");	
	            		else {
	            			$data = array(
				                        'title'=>$input['title'],
				                        'user_id'=>$input['owner_id'],
	                       			);
	                              
			                $bid = Group::create($data);

			                foreach ($input['member_id'] as $key => $value) {
		                		$data1 = array(
					                        'group_id'=>$bid['id'],
					                        'member_id'=>$value,
					                        'status' => 'Joined'
			                            );  
		                    	GroupMembers::create($data1);
	               			}

			                $this->status="success";
			                $this->message="Broadcast created.";
			                $this->data=Group::where('id',$bid['id'])->get()->toArray();
	            		}            		
	            	}
	    		}
	    	else
	    	{
	    		throw new Exception("All three fields required.");	
	    	}
		}
		catch(Exception $e){
			$this->message = $e->getMessage();
		}

		return $this->output();

	}