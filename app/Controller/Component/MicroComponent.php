<?php 
class MicroComponent extends Component {
	
	public function __construct() {
		$this->User = ClassRegistry::init('User');
	}
	
	public function get_all_rate($user_id){
        $rate_data = $this->User->find('first',array('conditions'=>array('User.id'=>$user_id)));
        if(!empty($rate_data)){
            return $rate_data['User'];
        }else{
            return $rate_data;
        }
    }

    public function ustime_to_sql($date){
        if(!empty($date)){
            $breakDate = explode('-',$date);
            $correctOrder = $breakDate[1].'-'.$breakDate[0].'-'.$breakDate[2];
            return date('Y-m-d',strtotime($correctOrder));
        }
    }
}
?>