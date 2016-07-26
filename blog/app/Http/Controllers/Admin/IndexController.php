<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Http\Model\User;

use App\Http\Requests;

class IndexController extends CommonController
{
    public function index(){
        return view('admin.index');
    }
    
    public function info(){
        return view('admin.info');
    }
    
    //更改超级管理员密码
    public function pass(){
        
        if($input = Input::all()){
            
            $rules = [
              'password'=>'required|between:6,20|confirmed',
            ];
            
            $message = [
                'password.required' => '新密码不能为空',
                'password.between' => '新密码必须在6-20位之间',
                'password.confirmed' => '新密码和确认密码不一致',
            ];
            
            $validator = Validator::make($input,$rules,$message);
            if($validator->passes()){
                
                $user = User::first();
                $_password = Crypt::decrypt($user->user_pass);
                if($input['password_o']==$_password){
                    
                    $user->user_pass = Crypt::encrypt($input['password']);
                    $user->update();
                    return redirect('admin/info');
                    
                } else{
                    
                    return back()->with('errors','原密码错误');
                }
                
            } else{
                
                
                return back()->withErrors($validator);
            }
        } else{
            return view('admin.pass');
        }
        
    }
}
