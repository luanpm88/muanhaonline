<?php
class FixpriceOrder extends AppModel {
	var $name = 'FixpriceOrder';
	var $displayField = 'id';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'FixpriceCustomer' => array(
			'className' => 'FixpriceCustomer',
			'foreignKey' => 'fixprice_customer_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Expert' => array(
			'className' => 'User',
			'foreignKey' => 'expert_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Product' => array(
			'className' => 'Product',
			'foreignKey' => 'product_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'FixpriceType' => array(
			'className' => 'FixpriceType',
			'foreignKey' => 'fixprice_type_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'FixpriceService' => array(
			'className' => 'FixpriceService',
			'foreignKey' => 'fixprice_service_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'FixpricePayment' => array(
			'className' => 'FixpricePayment',
			'foreignKey' => 'fixprice_payment_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'FixpriceRate' => array(
			'className' => 'FixpriceRate',
			'foreignKey' => 'fixprice_rate_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'ExpertGroup' => array(
			'className' => 'ExpertGroup',
			'foreignKey' => 'expert_group_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	var $hasOne = array(
		'FixpriceGtable' => array(
			'className' => 'FixpriceGtable',
			'foreignKey' => 'fixprice_order_id',
                        'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'FixpriceAnswer' => array(
			'className' => 'FixpriceAnswer',
			'foreignKey' => 'fixprice_order_id',
                        'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	var $hasAndBelongsToMany = array(		
		'FixpriceOrderState' => array(
			'className' => 'FixpriceOrderState',
			'joinTable' => 'fixprice_orders_states',
			'foreignKey' => 'fixprice_order_id',
			'associationForeignKey' => 'fixprice_order_state_id',
			'unique' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		)
	);
	
	public static function setState($order_id, $state, $note = 'auto', $expert = '')
	{	
		App::import('model','FixpriceOrdersState');
		App::import('model','FixpriceOrderState');
		
		$sdb = new FixpriceOrdersState();
		$db = new FixpriceOrderState();
		$state = $db->find("first", array("conditions"=>array("FixpriceOrderState.alias"=>$state)));
		
		if(!$state) return false;
		
		$sdb->updateAll(
				array('FixpriceOrdersState.active' => 0),
				array('FixpriceOrdersState.fixprice_order_id' => $order_id)
			);
		
		$orderState["FixpriceOrdersState"]["fixprice_order_id"] = $order_id;
		$orderState["FixpriceOrdersState"]["fixprice_order_state_id"] = $state["FixpriceOrderState"]["id"];
		$orderState["FixpriceOrdersState"]["note"] = $note;
		$orderState["FixpriceOrdersState"]["expert_id"] = $expert;
		$orderState["FixpriceOrdersState"]["active"] = 1;
		$orderState["FixpriceOrdersState"]["created_date"] = date('Y-m-d H:i:s');
		
		$sdb->create();
		if ($sdb->save($orderState)) {
			return true;			
		} else {
			return false;
		}
	}
	
	public static function getState($order_id, $object = false)
	{
		App::import('model','FixpriceOrdersState');
		
		$sdb = new FixpriceOrdersState();	
		$state = $sdb->find("first", array(
							"conditions"=>array(
								"FixpriceOrdersState.fixprice_order_id"=>$order_id,
								"FixpriceOrdersState.active"=>1
							),
							"order"=>"FixpriceOrdersState.created_date DESC"
						)
				);
		
		if ($state) {
			if($object)
				return $state;
			else
				return $state["FixpriceOrderState"]["alias"];
		} else {
			return false;
		}
	}
	
	public static function getByState($str_state, $user = null)
	{
		$state_db = new FixpriceOrderState();
		$order_db = new FixpriceOrder();
		
		
		
		
		$state = $state_db->find('first', array(
						'conditions'=>array(
							'FixpriceOrderState.alias'=>$str_state
						)
					));
		
		$order_db->bindModel(array('hasOne' => array(
					'FixpriceOrdersState' => array(					    
					    'conditions' => array('FixpriceOrdersState.active = 1')
				    ))));
		
		if($user)
		{
			$orders = $order_db->find('all', array(					    
						    'conditions' => array('FixpriceOrdersState.active = 1',
										'FixpriceOrdersState.fixprice_order_state_id' => $state['FixpriceOrderState']['id'],
										'FixpriceOrder.expert_id' =>$user
									)
					    ));
		}		
		else
		{
			$orders = $order_db->find('all', array(					    
						    'conditions' => array('FixpriceOrdersState.active = 1',
										'FixpriceOrdersState.fixprice_order_state_id' => $state['FixpriceOrderState']['id']
									)
					    ));
		}
		
		$a_order = array();
		foreach($orders as $item)
		{
			$a_order[] = $item['FixpriceOrder']['id'];
		}
		//var_dump($a_order);
		return $a_order;
	}
	
	public static function getByUser($str_state, $user)
	{
		$state_db = new FixpriceOrderState();
		$order_db = new FixpriceOrder();
		
		
		
		
		$state = $state_db->find('all', array(
						'conditions'=>array(
							'FixpriceOrderState.alias'=>$str_state
						)
					));
		
		$state_a = array();
		foreach($state as $s)
		{
			$state_a[] = $s['FixpriceOrderState']['id'];
		}
		
		$order_db->bindModel(array('hasOne' => array(
					'FixpriceOrdersState' => array(					    
					    'conditions' => array('FixpriceOrdersState.active = 1')
				    ))));
		
		$orders = $order_db->find('all', array(					    
						    'conditions' => array('FixpriceOrdersState.active = 1',
										'FixpriceOrdersState.fixprice_order_state_id' => $state_a,
										'FixpriceOrder.user_id' =>$user
									)
					    ));
		
		
		$a_order = array();
		foreach($orders as $item)
		{
			$a_order[] = $item['FixpriceOrder']['id'];
		}
		//var_dump($a_order);
		return $a_order;
	}
	
	
	
	public static function getByGroupLeader($user_id, $str_state)
	{
		$state_db = new FixpriceOrderState();
		$order_db = new FixpriceOrder();
		
		$state = $state_db->find('first', array(
						'conditions'=>array(
							'FixpriceOrderState.alias'=>$str_state
						)
					));
		
		$order_db->bindModel(array('hasOne' => array(
					'FixpriceOrdersState' => array(					    
					    'conditions' => array('FixpriceOrdersState.active = 1')
				    ))));
		$orders = $order_db->find('all', array(					    
					    'conditions' => array('FixpriceOrdersState.active = 1',
									'FixpriceOrdersState.fixprice_order_state_id' => $state['FixpriceOrderState']['id'],
									'ExpertGroup.expert_id' => $user_id
								)
				    ));
		
		$a_order = array();
		foreach($orders as $item)
		{
			$a_order[] = $item['FixpriceOrder']['id'];
		}
		//var_dump($a_order);
		return $a_order;
	}
	
	public static function getByExpert($user_id, $str_state)
	{
		$state_db = new FixpriceOrderState();
		$order_db = new FixpriceOrder();
		
		$state = $state_db->find('first', array(
						'conditions'=>array(
							'FixpriceOrderState.alias'=>$str_state
						)
					));
		
		$order_db->bindModel(array('hasOne' => array(
					'FixpriceOrdersState' => array(					    
					    'conditions' => array('FixpriceOrdersState.active = 1')
				    ))));
		$orders = $order_db->find('all', array(					    
					    'conditions' => array('FixpriceOrdersState.active = 1',
									'FixpriceOrdersState.fixprice_order_state_id' => $state['FixpriceOrderState']['id'],
									'FixpriceOrder.expert_id' => $user_id
								)
				    ));
		
		$a_order = array();
		foreach($orders as $item)
		{
			$a_order[] = $item['FixpriceOrder']['id'];
		}
		//var_dump($a_order);
		return $a_order;
	}
	
	public static function setFixpriceOrderExpertGroup($group_id, $order_id, $note)
	{
		$db = new FixpriceOrdersExpertGroup();
		$group_db = new ExpertGroup();
		
		//$user = $this->Auth->user();		
		//$group = $group_db->find('first', array('conditions'=>array('ExpertGroup.expert_id'=>$user['User'])));
		
		$connect['FixpriceOrdersExpertGroup']['expert_group_id'] = $group_id;
		$connect['FixpriceOrdersExpertGroup']['fixprice_order_id'] = $order_id;
		$connect['FixpriceOrdersExpertGroup']['note'] = $note;
		$connect['FixpriceOrdersExpertGroup']['created_date'] = date('Y-m-d H:i:s');
		
		$db->create();
		if ($db->save($connect)) {
			return true;			
		} else {
			return false;
		}
	}
	
	public static function isExpertGroup($expert, $group)
	{
		$group_db = new ExpertGroup();
		
		$group = $group_db->find('first', array('conditions'=>array('ExpertGroup.id'=>$group)));
		
		if($group['ExpertGroup']['expert_id'] == $expert)
			return true;
		else
			return false;
	}
	
	public static function isLeader($expert)
	{
		$group_db = new ExpertGroup();
		
		$groups = $group_db->find('all', array('conditions'=>array('ExpertGroup.expert_id'=>$expert)));
		
		if(count($groups))
			return true;
		else
			return false;
	}
	
	public static function isLeaderGroup($expert, $group)
	{
		$group_db = new ExpertGroup();
		
		$groups = $group_db->find('all', array('conditions'=>array('ExpertGroup.expert_id'=>$expert, 'ExpertGroup.id'=>$group)));
		
		if(count($groups))
			return true;
		else
			return false;
	}
	
	public static function countInvalid($id)
	{
		$fsdb = new FixpriceOrdersState();
		$sdb = new FixpriceOrderState();
		
		$state = $sdb->find('first', array('conditions'=>array('FixpriceOrderState.alias'=>'INVALID')));
		
		$states = $fsdb->find('all', array('conditions'=>array('FixpriceOrdersState.fixprice_order_id'=>$id, 'FixpriceOrdersState.fixprice_order_state_id'=>$state['FixpriceOrderState']['id'])));
		
		return count($states);
	}
	
	public static function updateState($state = 0)
	{
		App::import('model','Setting');
		App::import('model','District');
		App::import('model','ExpertGroup');
		
		$db = new FixpriceOrder();
		$district_db = new District();
		$expert_group_db = new ExpertGroup();
		$setting = new Setting();
		
		$fixpriceOrders = $db->find('all');
		
		$publice_register_time = $setting->get('public_fixpriceorder_register_time');
		$private_expert_fixprice_time = $setting->get('private_expert_fixprice_time');
		$private_assign_time = $setting->get('private_fixpriceorder_assign_time');
		$private_expert_confirm_time = $setting->get('private_expert_confirm_time');
		
		foreach($fixpriceOrders as $key => $item)
		{
			$state = $db->getState($item['FixpriceOrder']['id'], true);
			
			if($state['FixpriceOrderState']['alias'] == 'PAID')
			{
				$remain_hours = ($publice_register_time - (strtotime(date('Y-m-d H:i:s')) - strtotime($state['FixpriceOrdersState']['created_date'])));
				
				if($remain_hours <= 0)
				{
					//find groups in district
					$district = $district_db->read(null, $item['Product']['district_id']);
					$group_id = $db->getOrdersGroupMinCount($district['ExpertGroup']);
					
					if($group_id)
					{
						$item['FixpriceOrder']['expert_group_id'] = $group_id;
						$db->save($item);
						$db->setState($item['FixpriceOrder']['id'], 'REGISTERED');
					}	
				}
			}
			else if($state['FixpriceOrderState']['alias'] == 'REGISTERED')
			{
				$remain_hours = ($private_assign_time - (strtotime(date('Y-m-d H:i:s')) - strtotime($state['FixpriceOrdersState']['created_date'])));
				
				if($remain_hours <= 0)
				{
					//echo $item['FixpriceOrder']['id'];
					//find groups in district
					$expert_group = $expert_group_db->read(null, $item['FixpriceOrder']['expert_group_id']);
					
					if($expert_group)
					{
						$item['FixpriceOrder']['expert_id'] = $expert_group['ExpertGroup']['expert_id'];
						$db->save($item);
						$db->setState($item['FixpriceOrder']['id'], 'EXPERT_CONFIRMED', 'auto', $expert_group['ExpertGroup']['expert_id']);
					}	
				}
			}
			else if($state['FixpriceOrderState']['alias'] == 'ASSIGNED')
			{
				$remain_hours = ($private_expert_fixprice_time - (strtotime(date('Y-m-d H:i:s')) - strtotime($state['FixpriceOrdersState']['created_date'])));
				
				if($remain_hours <= 0)
				{
					echo $item['FixpriceOrder']['id'];
					//find groups in district
					$expert_group = $expert_group_db->read(null, $item['FixpriceOrder']['expert_group_id']);
					
					if($expert_group)
					{
						$item['FixpriceOrder']['expert_id'] = $expert_group['ExpertGroup']['expert_id'];
						$db->save($item);
						$db->setState($item['FixpriceOrder']['id'], 'EXPERT_CONFIRMED', 'auto', $expert_group['ExpertGroup']['expert_id']);
					}	
				}
			}
			else if($state['FixpriceOrderState']['alias'] == 'EXPERT_CONFIRMED')
			{
				$remain_hours = ($private_expert_confirm_time - (strtotime(date('Y-m-d H:i:s')) - strtotime($state['FixpriceOrdersState']['created_date'])));
				
				if($remain_hours <= 0)
				{
					//echo $item['FixpriceOrder']['id'];
					$invalid_count = $db->countInvalid($item['FixpriceOrder']['id']);					
					if(!$invalid_count)
					{
						//echo $item['FixpriceOrder']['id'];					
						
						//assign for leader
						$expert_group = $expert_group_db->read(null, $item['FixpriceOrder']['expert_group_id']);						
						if($expert_group)
						{
							$item['FixpriceOrder']['expert_id'] = $expert_group['ExpertGroup']['expert_id'];
							$db->save($item);
							$db->setState($item['FixpriceOrder']['id'], 'INVALID', 'out of time');
							$db->setState($item['FixpriceOrder']['id'], 'EXPERT_CONFIRMED', 'auto', $expert_group['ExpertGroup']['expert_id']);
						}
					}
					if($invalid_count == 1)
					{
						//echo $item['FixpriceOrder']['id'];					
						
						//assign for KSV inspector												
						$item['FixpriceOrder']['expert_id'] = $setting->get('inspector');
						$db->save($item);
						$db->setState($item['FixpriceOrder']['id'], 'INVALID', 'out of time');
						$db->setState($item['FixpriceOrder']['id'], 'INSPECTOR_CONFIRMED', 'auto', $setting->get('inspector'));
						
					}
				}
			}
		}
	}
	
	public static function getOrdersGroupMinCount($groups)
	{
		//var_dump($groups);
		$group_array = array();
		foreach($groups as $g)
		{
			$group_array[] = $g['id'];
		}
		
		$state_db = new FixpriceOrderState();
		$order_db = new FixpriceOrder();
		
		
		
		
		$state = $state_db->find('all', array(
						'conditions'=>array(
							'FixpriceOrderState.alias NOT'=>array('NEW', 'NEW_PRODUCT', 'PAID', 'VALID', 'FINISHED_RATED', 'PUBLISHED')
						)
					));
		
		$state_a = array();
		foreach($state as $s)
		{
			$state_a[] = $s['FixpriceOrderState']['id'];
		}
		
		$order_db->bindModel(array('hasOne' => array(
					'FixpriceOrdersState' => array(					    
					    'conditions' => array('FixpriceOrdersState.active = 1')
				    ))));
		
		$orders = $order_db->find('all', array(
						       'fields' => array('COUNT(*) AS ocount', 'FixpriceOrder.expert_group_id'),
						    'conditions' => array('FixpriceOrdersState.active = 1',
										'FixpriceOrdersState.fixprice_order_state_id' => $state_a,
										'FixpriceOrder.expert_group_id' => $group_array
									),
						    'group' => array('FixpriceOrder.expert_group_id')
					    ));
		
		//$max = 
		//foreach($orders as $o)
		//{
		//	
		//}
		if(count($orders))
		{
			$max = $orders[0];
			foreach($orders as $o)
			{
				if($o[0]["ocount"] > $max[0]["ocount"])
				{
					$max = $o;
					//$max = $o["FixpriceOrder"]["expert_group_id"];
				}
			}
			return $max["FixpriceOrder"]["expert_group_id"];
		}
		else
		{
			return false;
		}
	}
	
	public static function checkAnswer($order_id)
	{
		App::import('model','FixpriceAnswer');
		
		$db = new FixpriceAnswer();
		$fixpriceAnswer = $db->find('first', array('conditions'=>array(
											'FixpriceAnswer.fixprice_order_id'=>$order_id
							)));
		echo (count($fixpriceAnswer['FixpriceAnswerCompareitem']) < 3) || $fixpriceAnswer['FixpriceAnswer']['price_unit'] == '' || $fixpriceAnswer['FixpriceAnswer']['price_total'] == '';
		
		if($fixpriceAnswer)
		{
			if((count($fixpriceAnswer['FixpriceAnswerCompareitem']) < 3) || $fixpriceAnswer['FixpriceAnswer']['price_unit'] == '' || $fixpriceAnswer['FixpriceAnswer']['price_total'] == '')
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		else
		{
			return false;
		}
	}
	
	public static function getExpertStatus($id)
	{
		$count = array();
		$db = new FixpriceOrder();
		
                
		
		$count["total"] = count($db->getByExpert($id, array('FINISHED_RATED', 'ASSIGNED', 'EXPERT_PENDING', 'INVALID', 'EXPERT_CONFIRMED')));
		
		//echo $count["total"];
		
		//$count['isLeader'] = $this->FixpriceOrder->isLeader($user['User']['id']);
		
		return $count["total"];
		
	}
}
