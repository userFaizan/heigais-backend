<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppText;
use App\Models\Category;
use App\Models\Visitor;
use Illuminate\Http\Request;

class AppTextController extends Controller
{
    public function get_app_text(Request $request, $id){
        $visitor = Visitor::where('mac',$request->mac)->first();
        if(!$visitor){
            Visitor::create(['mac'=>$request->mac]);
        }
        $app_text=AppText::select('text_key','text')->where('language_id',$id)->get();
        return response()->json($app_text);
    }



    public function cat(){
        $arr=[
            "Sport",
            "Football",
            "Cycling",
            "Baseball",
            "Golf",
            "Horse",
            "Tennis",
            "Icehockey",
            "Basket",
            "Discgolf",
            "Other",
            "Music",
            "Live",
            "Pop",
            "Rock",
            "Jazz",
            "Classical",
            "Latin",
            "R&B",
            "Electr.",
            "Karaoke",
            "Other",
            "Family",
            "All",
            "0-2 years",
            "3-6 years",
            "7-10 years",
            "Teens",
            "Parents",
            "Pregnant",
            "Moms",
            "Dads",
            "Other",
            "Party",
            "Dance",
            "Disco",
            "Music",
            "Cat-Child-Drinks",
            "Eating",
            "Kids",
            "Teens",
            "Adults",
            "Other",
            "Shopping",
            "Market",
            "2nd hand",
            "Discount",
            "Flee market",
            "Yard sale",
            "Opening",
            "Launch",
            "Other",
            "Food",
            "Breakfast",
            "Brunch",
            "Lunch",
            "Dinner",
            "Fine dining",
            "Steak",
            "Sea-food",
            "Spicy",
            "Vegan",
            "Other",
            "Parent-Cat-Drinks",
            "Wine",
            "Beer",
            "Whiskey",
            "Champagne",
            "Cocktail",
            "Parent-Cat-Child-Drinks",
            "Softdrinks",
            "Other",
            "Meetups",
            "Cars",
            "Motorcycles",
            "Mopeds",
            "Stitching",
            "Playing cards",
            "Chess",
            "Gaming",
            "Board game",
            "Other",
            "Pets",
            "Dogs",
            "Cats",
            "Horses",
            "Rabbit",
            "Homes",
            "Buy",
            "Rent",
            "Cabins",
            "Apartments",
            "Other",
            "Religion",
            "Islam",
            "Christianity",
            "Hinduism",
            "Buddhism",
            "Shintoism",
            "Sikhism",
            "Judaism",
            "Other",
        ];
        for($i=0;$i<sizeOf($arr);$i++){
            // dump('#f'.dechex( mt_rand( 16, 255 ) ).'f'.dechex( mt_rand( 16, 255 ) ));
            $color ='';
            if($i < 30){
                // $color = '#84'.dechex( mt_rand( 16, 255 ) ).'e3';
                $color = '#e'.dechex( mt_rand( 0, 205 )+rand(16,50) ).'3'.dechex( mt_rand( 0, 205 )+rand(16,50) );
            }elseif($i > 30 && $i < 60){
                $color = '#'.dechex( mt_rand( 0, 205 )+rand(16,50) ).'e3'.dechex( mt_rand( 0, 205 )+rand(16,50) );
            }else{
                $color = '#'.dechex( mt_rand( 0, 205 )+rand(16,50) ).'e'.dechex( mt_rand( 0, 205 )+rand(16,50) ).'3';
            }
            // Category::create(['title'=>$arr[$i],'color'=>'#f'.dechex( mt_rand( 16, 255 ) ).'f'.dechex( mt_rand( 16, 255 ) )]);
            Category::where('id',$i+1)->update(['color'=>$color]);
        }
        return "successfull";
    }

}
