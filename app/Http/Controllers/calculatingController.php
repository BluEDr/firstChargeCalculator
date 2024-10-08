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
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\App;

use Auth;
class calculatingController extends Controller
{
    public function showIndex(Request $request) {
        $username = Auth::user()->name;
        $userid = Auth::user()->id;
        // echo $request->method();
        $options = category::all();
        $today = Carbon::now();
        // $today = Carbon::create(2001,5,4,12,34,56); //todo: delete this just for testing
        $todayDate = $today->toDateString();
        $pAmound = payed_amound::with('user','category')->where('user_id',$userid)->orderBy('updated_at','desc')->get(); //->orderBy('price','desc')
        $yearsInDb = $this->getYearsFromCreatedAt($pAmound); //with this i receive the years that added in the database from the loged in user.
        $currency_options = currency::all();
        if ($request->method() == 'POST') {
            $number = $request->input('price');
            if (strpos($number, ',') !== false) {            // Check if there is a comma in the input
                $number = str_replace(',', '.', $number);    // Replace commas with dots
            }
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
                $payed_amound->user_id = Auth::user()->id;    
                if(count($currency_options)>0) 
                    $payed_amound->currency_id = $request->get('dropdown_currency');
                else
                    $payed_amound->currency_id = -1;    //ok lets do

                if ($request->has('defaultCheck11')) {
                    $payed_amound->is_negative = 1;
                } else {
                    $payed_amound->is_negative = 0;
                }
                $payed_amound->created_at = $today; //TODO: delete this just for testing
                $payed_amound->updated_at = $today; //todo: delete this just for testing
                if($request->hasFile('photo')) {    //save here the name and the path from the invoice(if)
                    if ($request->file('photo')->isValid()) {
                        // Validation passed; it's a valid image

                        $validator = Validator::make($request->all(), [
                            'photo' => 'required|file|image|mimes:jpeg,png,jpg,gif',
                        ]);
                        
                        if ($validator->fails()) {
                            $errors = $validator->errors();
                            foreach ($errors->all() as $error) {
                                echo $error;
                            }
                        } else {
                            $filename = time() . $request->file('photo')->getClientOriginalName();
                            $path = $request->file('photo')->storeAs('public/uploaded_photos',$filename);
                            $payed_amound->image = $filename;
                        }
                    } else { 
                        echo "wrong file type in the photo input field";
                    }
                }
                $payed_amound->save();
                $a = $request->input('defaultCheck11');
                $pAmound = payed_amound::with('user','category')->where('user_id',$userid)->orderBy('updated_at','desc')->take(40)->get();
                $monthsPriceSum = $this->monthsSum($pAmound);
                // $sumaryWhileNow = calulateSummaryWhileNow($monthsPriceSum);
                $perDay = $this->howMuchPerDay($monthsPriceSum,$userid);
                $summWhileNow = $this->calulateSummaryWhileNow($monthsPriceSum,$userid);
                $spentToday = $this->todaySum($pAmound,$todayDate);
                return view('index')->with('pMonthsSum',$monthsPriceSum)->with(compact('options'))->with(compact('currency_options'))->with(compact('pAmound'))->with(compact('summWhileNow'))->with(compact('todayDate'))->with(compact('perDay'))->with(compact('spentToday'))->with('yearsInDb',$yearsInDb);
            } else {
                $a = 'errorCheck';
                $monthsPriceSum = $this->monthsSum($pAmound);
                $summWhileNow = $this->calulateSummaryWhileNow($monthsPriceSum,$userid);
                $perDay = $this->howMuchPerDay($monthsPriceSum,$userid);
                $spentToday = $this->todaySum($pAmound,$todayDate);
                return view('index')->with('pMonthsSum',$monthsPriceSum)->with('errorCheck','This is not a correct price value.')->with(compact('options'))->with(compact('currency_options'))->with(compact('pAmound'))->with(compact('summWhileNow'))->with(compact('todayDate'))->with(compact('perDay'))->with(compact('spentToday'))->with('yearsInDb',$yearsInDb);          
            }
            
        } else {
            $monthsPriceSum = $this->monthsSum($pAmound);
            $summWhileNow = $this->calulateSummaryWhileNow($monthsPriceSum,$userid);
            $perDay = $this->howMuchPerDay($monthsPriceSum,$userid);
            $spentToday = $this->todaySum($pAmound,$todayDate);
            return view('index')->with('pMonthsSum',$monthsPriceSum)->with(compact('options'))->with(compact('currency_options'))->with(compact('pAmound'))->with(compact('summWhileNow'))->with(compact('todayDate'))->with(compact('perDay'))->with(compact('spentToday'))->with('yearsInDb',$yearsInDb);
        }
    }

    private function getYearsFromCreatedAt($pAmound) {
        $years = [];

        foreach ($pAmound as $record) {
            $year = $record->created_at->year;
            if (!in_array($year, $years)) {
                $years[] = $year;
            }
        }
        sort($years);
        return $years;
    }

    private function calulateSummaryWhileNow(float $monthsPriceSum,int $userid, $daysOfMonth = null, $month = null, $year = null) {
        $currMonth = $month ?? Carbon::now()->month;
        $currYear = $year ?? Carbon::now()->year;

        $today = ($daysOfMonth==null ? Carbon::now() : Carbon::create($currYear,$currMonth,$daysOfMonth));
        $dayOfMonth = $daysOfMonth ?? $today->day;
        // dd($this->howMuchPerDay($monthsPriceSum,$userid,$dayOfMonth));
        // dd($dayOfMonth);
        $summaryWhileNow = ($this->howMuchPerDay($monthsPriceSum,$userid,$dayOfMonth)*$dayOfMonth)-$monthsPriceSum;
        
        return number_format($summaryWhileNow,2); 
    }

    private function howMuchPerDay(float $monthsPriceSum,int $userid, $daysOfMonth = null, $month = null, $year = null) {
        $currMonth = $month ?? Carbon::now()->month;
        $currYear = $year ?? Carbon::now()->year;
        $sal = sallary::where('user_id', $userid)->latest('created_at')->first();
        if (!$sal)
            return null;
        
        $today = ($daysOfMonth==null ? Carbon::now() : Carbon::create($currYear,$currMonth,$daysOfMonth));
        // $today = Carbon::now();
        $dayOfMonth = $today->day;
        $maxDaysInMonth = $today->daysInMonth;
        $moneyThatICanSpendDaily = number_format($sal->sallary/$maxDaysInMonth,2);
        // dd($monthsPriceSum);
        return number_format($moneyThatICanSpendDaily,2); 
    }

    private function todaySum(Collection $pAmound, String $todayDate) { //todo edo
        $sum = 0;
        foreach($pAmound as $p) {
            if($p->created_at->format('Y-m-d') == $todayDate) {
                if($p->is_negative)
                    $sum -= $p->price;
                else
                    $sum += $p->price;
            }
        }
        return $sum;
    }

    private function monthsSum(Collection $pAmound, $currentMonth = null, $currentYear = null) {
        $sum = 0;
        $currentMonth = $currentMonth ?? Carbon::now()->month;
        $currentYear = $currentYear ?? Carbon::now()->year;
        foreach ($pAmound as $p) {
            $createdMonth = Carbon::parse($p->created_at)->month; 
            $createdYear = Carbon::parse($p->created_at)->year; //xriazomaste kai to etos giati allios tha ypologizei taytoxrona ton ekastote mina kathe etous poy exei ginei kataxorisi
            if (($createdMonth == $currentMonth) && ($createdYear == $currentYear)) {
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

    public function index(Request $request,$locale = null) {
        $a1 = 1;
        $a2 = 2;
        $pAmound = payed_amound::all();
        if($request->method()=='POST') {
            $a1 = $request->get('price');
            $a2 = $request->get('reason');
        }
        return view('index', ['l'=>$request->method(), 'a' => $a1, 'b'=> $a2, compact('pAmound')]); 
    }

    public function deleteRow($deletedId) {
        $rowToDelete = payed_amound::where('id',$deletedId)->first();  //TODO: find and delete the invoice image if exists
        if ($rowToDelete){
            $rowToDelete->delete();
            return redirect()->back()->with('success', 'Row deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Row not found');
        }
            
    }

    public function postDelete(Post $post){
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
    public function invoice(string $inv) {
        $userId = Auth::user()->id;
        $pa = payed_amound::where('id',$inv)->where('user_id',$userId)->first();
        if(!$pa)    //Protecting the the users to have access from others invoice.
            return redirect()->route('index');
        else    
            return view('invoice',['invoice' => $pa]);
        // return view('invoice',['invoice' => $inv]);
    }

    public function search(Request $req) {
        $userId = Auth::user()->id;
        $currency_options = currency::all();
        $options = category::all();
        $today = Carbon::now();
        $todayDate = $today->toDateString();
        $pAmound = payed_amound::with('user')->where('user_id',$userId)->get(); //->orderBy('price','desc')
        $yearsInDb = $this->getYearsFromCreatedAt($pAmound);
        if ($req->get('search') == null) { 
            if (($req->get('day') == 'Days') && ($req->get('month') == 'Months') && ($req->get('year') == 'Years')) { 
                $a = 'errorCheck';
                return redirect()->route('index')->with($a,'No search data.'); 
            } else {
                $year = intval($req->get('year'));
                $month = intval($req->get('month'));
                $day = intval($req->get('day'));
                $query = payed_amound::query();
                if ($req->get('year')!='Years')
                    $query->whereYear('created_at',$year);
                if($req->get('month')!='Months')
                    $query->whereMonth('created_at',$month);
                if($req->get('day')!='Days')
                    $query->whereDay('created_at',$day);
                $pAmound = $query->orderBy('created_at','desc')->get();
                $spentToday = null;
                $pMonthsSum = null;
                $summWhileNow = null;
                if ($req->get('month')!='Months' && $req->get('year') != 'Years' && $req->get('day') != 'Days') {
                    // $perDay = $this->howMuchPerDay($monthsPriceSum,$userid);
                    // $summWhileNow = $this->calulateSummaryWhileNow($monthsPriceSum,$userid);
                    $today = Carbon::create($year,$month,$day); 
                    $todayDate = $today->toDateString();
                    $spentToday = $this->todaySum($pAmound,$todayDate);
                } elseif ($req->get('month')!='Months' && $req->get('year') != 'Years' && $req->get('day')=='Days') {
                    $pMonthsSum = $this->monthsSum($pAmound,$month,$year);
                    $today = Carbon::create($year,$month,1); 
                    // $monthsPriceSum = $this->monthsSum($pAmound);
                    $maxDaysInMonth = $today->daysInMonth;
                    $today = Carbon::create($year,$month,$maxDaysInMonth); 
                    // $sumaryWhileNow = calulateSummaryWhileNow($monthsPriceSum);
                    $summWhileNow = $this->calulateSummaryWhileNow($pMonthsSum,$userId,$maxDaysInMonth,$month,$year);
                    
                    
                } elseif ($req->get('month')!='Months' && $req->get('year') != 'Years') {
                    $pMonthsSum = $this->monthsSum($pAmound,$month,$year);
                } 
                $calledFromSearch = true;
                $today = Carbon::now();
                $todayDate = $today->toDateString();
                return view('index')->with('pAmound',$pAmound)->with(compact('options'))->with(compact('spentToday'))->with(compact('pMonthsSum'))->with(compact('currency_options'))->with(compact('summWhileNow'))->with(compact('todayDate'))->with('yearsInDb',$yearsInDb)->with(compact('calledFromSearch'));
            }
        } else {
            $search = $req->get('search');
            $pAmound = payed_amound::where('reason', 'LIKE' , '%' . $search . '%')->where('user_id',$userId)->orderBy('updated_at','desc')->get();
            if($pAmound->isEmpty()) {
                $a = 'errorCheck';
                return redirect()->route('index')->with($a,'There is not imported data with reason '. $search . '.');
            } else
                return view('index')->with('pAmound',$pAmound)->with(compact('options'))->with(compact('currency_options'))->with(compact('todayDate'));
        }
    }
}
