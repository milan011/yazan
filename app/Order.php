<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Order extends Model
{
    use SoftDeletes; //使用软删除

    /**
     * The database table used by the model.
     * 定义模型对应数据表及主键
     * @var string
     */
    // protected $table = 'users';
    protected $table = 'yz_orders';
    protected $primaryKey ='id';

    /**
     * The attributes that are mass assignable.
     * 定义可批量赋值字段
     * @var array
     */
    protected $fillable = ['order_code', 'exp_code', 'goods_num', 'type_num', 'total_price', 'user_id', 'user_top_id', 'user_level', 'exp_price', 'exp_company', 'user_telephone', 'user_name', 'creater_id', 'remark', 'status', 'address','sh_name', 'sh_telephone', 'send_name', 'send_telephone'];

    /**
     * The attributes excluded from the model's JSON form.
     * //在模型数组或 JSON 显示中隐藏某些属性
     * @var array
     */
    protected $hidden = [];

    /**
     * 应该被调整为日期的属性
     * 定义软删除
     * @var array
     */
    protected $dates = ['deleted_at'];

    // 插入数据时忽略唯一索引
    public static function insertIgnore($array){
        $a = new static();
        if($a->timestamps){
            $now = \Carbon\Carbon::now();
            $array['created_at'] = $now;
            $array['updated_at'] = $now;
        }
        DB::insert('INSERT IGNORE INTO '.$a->table.' ('.implode(',',array_keys($array)).
            ') values (?'.str_repeat(',?',count($array) - 1).')',array_values($array));
    }

    // 搜索条件处理
    public function addCondition($requestData){

        $query = $this;

        /*if($is_self){

            if(!(Auth::user()->isSuperAdmin())){

               if(Auth::user()->isMdLeader()){
                    //店长
                    $user_shop_id = Auth::user()->shop_id; //用户所属门店id
                    // $this->where('shop_id', $user_shop_id);
                    $query = $query->where('shop_id', $user_shop_id);    
                }else{
                    //店员
                    // $this->where('creater_id', Auth::id());
                    $query = $query->where('creater_id', Auth::id());  
                } 
            }           
        }*/

        if(!empty($requestData['order_code'])){  //有订单号

            $query = $query->where('order_code', $requestData['order_code']);

            return $query;
        }

        if(!empty($requestData['status'])){
            //有订单状态选项
            $query = $query->where('status', $requestData['status']);
        }else{
            $query = $query->whereIn('status', ['1', '2']);
        }    

        if(!empty($requestData['date'])){
            /*p(substr($requestData['date'], 0, 10));
            p(substr($requestData['date'], -10));
            p(explode('-',$requestData['date']));exit;*/
            $date_section = [substr($requestData['date'], 0, 10),substr($requestData['date'], -10)];
            $query = $query->whereBetween('created_at', $date_section);
        }
        
        return $query;
    }

     /**
     * 推荐车型信息的查询作用域
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOsRecommend($query, $requestData)
    {
        if(isset($requestData['top_price'])){
            $query = $query->where('top_price', '<=', $requestData['top_price']);
        }
        
        if(isset($requestData['bottom_price'])){
            $query = $query->where('bottom_price', '>=', $requestData['bottom_price']);
        }
        
        $query = $query->where('car_status', '1');
        return $query;
    }

    // 定义User表与order表一对多关系
    public function belongsToCreater(){

      return $this->belongsTo('App\User', 'creater_id', 'id')->select('id as user_id', 'nick_name', 'telephone as creater_telephone');
    }

    // 定义User表与order表一对多关系
    public function belongsToUser(){

      return $this->belongsTo('App\User', 'user_id', 'id')->select('id as user_id', 'nick_name', 'telephone as user_telephone');
    }

    // 定义User表与order表一对多关系
    public function belongsToUserWithTopUser(){

      return $this->belongsTo('App\User', 'user_top_id', 'id')->select('id as user_id', 'nick_name', 'telephone as user_telephone');
    }
 
    // 定义order表与order_goods表一对多关系
    public function hasManyOrderGoods()
    {
        return $this->hasMany('App\OrderGoods', 'order_id', 'id');
    }
}
