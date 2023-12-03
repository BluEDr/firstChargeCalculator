<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection; //ayto to ebala gia na mporo na steilo dedomena typoy Collection stin argument metabliti tis synartisis pAmoundS
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\payed_amound;
use App\Models\category;
use App\Models\currency;
use App\Models\Sallary;
use Auth;
class calculatingController extends Controller
{
    public function showIndex(Request $request) {
        $username = Auth::user()->name;
        $userid = Auth::user()->id;
        // echo $request->method();
        $options = category::all();
        $today = Carbon::now();
        $todayDate = $today->toDateString();
        $pAmound = payed_amound::with('user','category')->where('user_id',$userid)->orderBy('updated_at','desc')->get(); //->orderBy('price','desc')
        $currency_options = currency::all();
        if ($request->method() == 'POST') {
            // echo $request->input('price');
            
            echo $request->input('defaultCheck11');
            $number = $request->input('price');
            if (is_numeric($number) && is_float($number + 0.0)) {
                $payed_amound = new payed_amound();
                $payed_amound->price = $number+0.0;
                if($request->get('reason') == null) 
                    $payed_amound->reason = "";
                else
                    $payed_amound->reason = $request->get('reason');
                if (count($options) > 0)
                    $payed_amound->category_id = $request->get('selected_option');
                else
                    $payed_amound->category_id = -1;
                $payed_amound->user_id = Auth::user()->id;     //todo an den eisai loged in crasharei
                if(count($currency_options)>0) 
                    $payed_amound->currency_id = $request->get('dropdown_currency');
                else
                    $payed_amound->currency_id = -1;    

                if ($request->has('defaultCheck11')) {
                    $payed_amound->is_negative = 1;
                } else {
                    $payed_amound->is_negative = 0;
                }
                $payed_amound->save();
                $a = $request->input('defaultCheck11');
                echo $userid;
                $pAmound = payed_amound::with('user','category')->where('user_id',$userid)->orderBy('updated_at','desc')->get();
                $pAmoundSum = $this->pAmoundS($pAmound);
                $monthsPriceSum = $this->monthsSum($pAmound);
                // $sumaryWhileNow = calulateSummaryWhileNow($monthsPriceSum);
                $summWhileNow = $this->calulateSummaryWhileNow($monthsPriceSum,$userid);
                $perDay = $this->howMuchPerDay($monthsPriceSum,$userid);
                return view('index')->with('errorCheck',$a)->with('pAmoundSum', $pAmoundSum)->with('pMonthsSum',$monthsPriceSum)->with(compact('options'))->with(compact('currency_options'))->with(compact('pAmound'))->with(compact('summWhileNow'))->with(compact('todayDate'))->with(compact('perDay'));
            } else {
                $a = 'errorCheck';
                $pAmoundSum = $this->pAmoundS($pAmound);
                $monthsPriceSum = $this->monthsSum($pAmound);
                $summWhileNow = $this->calulateSummaryWhileNow($monthsPriceSum,$userid);
                $perDay = $this->howMuchPerDay($monthsPriceSum,$userid);
                return view('index')->with('pAmoundSum',$pAmoundSum)->with('pMonthsSum',$monthsPriceSum)->with('errorCheck','This is not a correct price value.')->with(compact('options'))->with(compact('currency_options'))->with(compact('pAmound'))->with(compact('summWhileNow'))->with(compact('todayDate'))->with(compact('perDay'));          
            }
            
        } else {
            $pAmoundSum = $this->pAmoundS($pAmound);
            $monthsPriceSum = $this->monthsSum($pAmound);
            $summWhileNow = $this->calulateSummaryWhileNow($monthsPriceSum,$userid);
            $perDay = $this->howMuchPerDay($monthsPriceSum,$userid);
            return view('index')->with('pAmoundSum',$pAmoundSum)->with('pMonthsSum',$monthsPriceSum)->with(compact('options'))->with(compact('currency_options'))->with(compact('pAmound'))->with(compact('summWhileNow'))->with(compact('todayDate'))->with(compact('perDay'));
        }
    }

    private function calulateSummaryWhileNow(float $monthsPriceSum,int $userid) {
        $today = Carbon::now();
        $dayOfMonth = $today->day;
        $summaryWhileNow = ($this->howMuchPerDay($monthsPriceSum,$userid)*$dayOfMonth)-$monthsPriceSum;
        return number_format($summaryWhileNow,2); 
    }

    private function howMuchPerDay(float $monthsPriceSum,int $userid) {
        $sal = sallary::where('user_id', $userid)->latest('created_at')->first();
        $today = Carbon::now();
        $dayOfMonth = $today->day;
        $maxDaysInMonth = $today->daysInMonth;
        $moneyThatICanSpendDaily = number_format($sal->sallary/$maxDaysInMonth,2);
        return number_format($moneyThatICanSpendDaily,2); 
    }

    private function monthsSum(Collection $pAmound) {
        $sum = 0;
        $currentMonth = Carbon::now()->month;
        foreach ($pAmound as $p) {
            $createdMonth = Carbon::parse($p->created_at)->month; 
            if ($createdMonth == $currentMonth) {
                if ($p->is_negative)
                    $sum -= $p->price;
                else
                    $sum += $p->price;

            }
        }
        return $sum;
    }

    private function pAmoundS(Collection $pAmound) {
        $sum = 0;
        foreach($pAmound as $pRow){
            if ($pRow->is_negative)
                $sum -= $pRow->price;
            else
                $sum += $pRow->price;
        }
        return $sum;
    }

    public function index(Request $request) {
        $a1 = 1;
        $a2 = 2;
        $pAmound = payed_amound::all();
        if($request->method()=='POST') {
            $a1 = $request->get('price');
            $a2 = $request->get('reason');
        }
        return view('index', ['l'=>$request->method(), 'a' => $a1, 'b'=> $a2, compact('pAmound')]); 
    }

    public function pyli() {
        $akis = (100+4)/2;
        return "Hello world " . $akis;
    }
    public function pyliTtr($a) {
        $posts = Post::all();
        return view('ttr',['q' => $posts]);
    }

    public function insertValue(Request $request){
        if ($request->method()=='POST') {
            $post = new Post();
            $post->title = $request->get('value');
            $post->content = $request->get('value');
            $post->user_id = 0; 
            $post->save();
            $postSelects = Post::where('content', 'like', '%' . '88' . '%')->get();
            return view('insertValue', ['l'=> $request->method(), 't'=>5122, 'db' => $postSelects]);
        }
        return view('insertValue', ['l' => $request->method(), 't'=>5122, 'db' => null]);
    }

    public function deleteRow($deletedId) {
        $rowToDelete = payed_amound::where('id',$deletedId)->first();
        if ($rowToDelete){
            $rowToDelete->delete();
            return redirect()->back()->with('success', 'Row deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Row not found');
        }
            
    }

    public function postDelete(Post $post){
        echo "$post";
        $post->delete();
        return redirect('insert');
    }
    
    public function processForm(Request $request) {
        $request->validate([
            'price' => 'required|numeric',
        ]);

        $number = $request->input('price');

        if (is_numeric($number)) {
            return redirect()->route('home2'); // Redirect to success page
        } else {
            $a = 'errorCheck';
            return redirect()->route('index')->with($a, 'Please enter a valid real number.'); // Redirect back to the form with an error message
        }
    }
}
