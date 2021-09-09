<?php

namespace App\Http\Controllers;

use App\Models\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RuleController extends Controller
{
    public function index(){
        $rulesEnabled = Array();
        $rules = Rule::all();
        foreach ($rules as $rule) {
            array_push($rulesEnabled, $rule->enabled);
        }
        return response()->json(['rules' => $rulesEnabled]);
    }

    public function changeRuleStatus(Request $request){
        if(Auth::check()){
            $rule = Rule::find($request->id);
            if($rule->enabled == '0'){
                $rule->enabled = 1;
            } else {
                $rule->enabled = 0;
            }
    
            $rule->save();
            return $rule;
        }
    }


    public static function SameSong(){
        return Rule::find(1)->enabled == 1 ? true : false;
    }
    
    public static function SameArtist(){
        return Rule::find(2)->enabled == 1 ? true : false;
    }

    public static function SpamProtection(){
        return Rule::find(3)->enabled == 1 ? true : false;
    }
    
    public static function HalfFullQueue(){
        return Rule::find(4)->enabled == 1 ? true : false;
    }
    
    public static function AutoDJ(){
        return Rule::find(5)->enabled == 1 ? true : false;
    } 
    
}
