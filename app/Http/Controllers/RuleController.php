<?php

namespace App\Http\Controllers;

use App\Models\Rule;
use Illuminate\Http\Request;

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
        $rule = Rule::find($request->id);
        if($rule->enabled == '0'){
            $rule->enabled = 1;
        } else {
            $rule->enabled = 0;
        }

        $rule->save();
        return $rule;
    }


    public static function SameSong(){
    
    }
    /*
    public static function SameArtist(){
        
    }

    public static function SpamProtection(){
        
    }
    
    public static function HalfFullQueue(){
        
    }
    
    public static function AutoDJ(){
        
    } 
    */   
}
