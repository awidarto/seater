<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
Route::controller('document', 'DocumentController');
Route::controller('attendee', 'AttendeeController');
//Route::controller('attendance', 'AttendanceController');
Route::controller('user', 'UserController');
Route::controller('agent', 'AgentController');
Route::controller('buyer', 'BuyerController');
Route::controller('potential', 'PotentialController');
Route::controller('report', 'ReportController');
Route::controller('pages', 'PagesController');
Route::controller('posts', 'PostsController');
Route::controller('category', 'CategoryController');
Route::controller('menu', 'MenuController');

Route::controller('propmanager', 'PropmanagerController');
Route::controller('promocode', 'PromocodeController');
Route::controller('transaction', 'TransactionController');
Route::controller('financial', 'FinancialController');

Route::controller('faq', 'FaqController');
Route::controller('faqcat', 'FaqcatController');

Route::controller('glossary', 'GlossaryController');

Route::controller('activity', 'ActivityController');
Route::controller('access', 'AccessController');

Route::controller('inprop', 'InpropController');

Route::controller('music', 'MusicController');
Route::controller('video', 'VideoController');
Route::controller('event', 'EventController');


Route::controller('upload', 'UploadController');
Route::controller('ajax', 'AjaxController');

Route::controller('home', 'HomeController');

Route::get('/', 'ScannerController@getIndex');



Route::get('content/pages', 'PagesController@getIndex');
Route::get('content/posts', 'PostsController@getIndex');
Route::get('content/category', 'CategoryController@getIndex');
Route::get('content/menu', 'MenuController@getIndex');



Route::get('regenerate',function(){
    $property = new Property();

    $props = $property->get()->toArray();

    $seq = new Sequence();

    foreach($props as $p){

        $_id = new MongoId($p['_id']);

        $nseq = $seq->getNewId('property');

        $sdata = array(
            'sequence'=>$nseq,
            'propertyId' => Config::get('ia.property_id_prefix').$nseq
            );

        if( $property->where('_id','=', $_id )->update( $sdata ) ){
            print $p['_id'].'->'.$sdata['propertyId'].'<br />';
        }

    }

});

Route::get('propman',function(){
    $property = new Property();

    $props = $property->distinct('propertyManager')->get();

    $propManArr = array();

    foreach($props as $p){
        $p = $p->toArray();
        //print $p[0];

        $propMan = $p[0];
        $propCount =    Property::where('propertyManager','=',$propMan)->count();
        $propLeaseMax = Property::where('propertyManager','=',$propMan)->max('leaseTerms');
        $propLeaseMin = Property::where('propertyManager','=',$propMan)->min('leaseTerms');
        $propLeaseAvg = Property::where('propertyManager','=',$propMan)->avg('leaseTerms');
        $propLeaseSum = Property::where('propertyManager','=',$propMan)->sum('leaseTerms');

        $propMonthlyMax = Property::where('propertyManager','=',$propMan)->max('monthlyRental');
        $propMonthlyMin = Property::where('propertyManager','=',$propMan)->min('monthlyRental');
        $propMonthlyAvg = Property::where('propertyManager','=',$propMan)->avg('monthlyRental');
        $propMonthlySum = Property::where('propertyManager','=',$propMan)->sum('monthlyRental');

        $pobj = Propman::where('name','=',$propMan)->first();

        if($pobj){
            $propManager = $pobj;
        }else{
            $propManager = new Propman();
            $propManager->createdDate = new MongoDate();
        }

        $propManager->name = $propMan;
        $propManager->count = $propCount;
        $propManager->max = $propLeaseMax;
        $propManager->min = $propLeaseMin;
        $propManager->avg = $propLeaseAvg;
        $propManager->sum = $propLeaseSum;

        $propManager->monthlyRental = $propMonthlySum;
        $propManager->monthlyMax = $propMonthlyMax;
        $propManager->monthlyMin = $propMonthlyMin;
        $propManager->monthlyAvg = $propMonthlyAvg;

        $propManager->annualRental = ($propMonthlySum * 12);

        $propManager->lastUpdate = new MongoDate();

        $propManager->save();

    }

    //print_r($propManArr);

});

Route::get('barcode/{txt}',function($txt){
    $barcode = new Barcode();
    $barcode->make($txt,'code39',40);
    return $barcode->render('jpg');
});

Route::get('tofin',function(){
    $property = new Property();

    $props = $property->get();

    foreach($props as $p){
        $p->monthlyRental = (double) $p->monthlyRental;

        $p->annualRental = 12 * $p->monthlyRental;

        $p->insurance = ($p->insurance == 0 || $p->insurance == '')?800:$p->insurance;

        $p->tax = $p->tax;
        $p->insurance = $p->insurance;
        $p->maintenanceAllowance = $p->annualRental * 0.1;
        $p->vacancyAllowance = $p->annualRental * 0.05;
        $p->propManagement = $p->annualRental * 0.1;

        if($p->FMV > 0){
            $p->equity = (($p->FMV - $p->listingPrice ) / $p->FMV ) * 100;
        }else{
            $p->equity = 0;
        }
        $p->dpsqft = $p->listingPrice / $p->houseSize;

        if($p->annualRental > 0){
            $p->ROI = ($p->annualRental - ( $p->tax + $p->propManagement + $p->insurance)) / $p->listingPrice * 100;
            $p->ROIstar = ($p->annualRental - ( $p->tax + $p->propManagement + $p->insurance + $p->vacancyAllowance + $p->maintenanceAllowance )) / $p->listingPrice * 100;
            $p->OPR = ($p->monthlyRental / $p->listingPrice )*100;
            $p->RentalYield = ($p->annualRental / $p->listingPrice) * 100 ;

        }else{
            $p->ROI = 0;
            $p->ROIstar = 0;
            $p->OPR = 0;
            $p->RentalYield = 0 ;
        }

        $p->tax = new MongoInt32( $p->tax);
        $p->insurance = new MongoInt32($p->insurance);
        $p->houseSize = new MongoInt32($p->houseSize);

        if( $p->lotSize < 100){
            $p->lotSize = (double) $p->lotSize * 43560;
        }else{
            $p->lotSize = (double) $p->lotSize;
        }

        $p->leaseTerms = (double) $p->leaseTerms;

        $p->yearBuilt = new MongoInt32($p->yearBuilt);


        print_r($p->toArray());

        $p->save();


    }

});


Route::get('tonumber',function(){
    $property = new Property();

    $props = $property->get()->toArray();

    $seq = new Sequence();

    foreach($props as $p){

        $_id = new MongoId($p['_id']);

        $price = new MongoInt32( $p['listingPrice'] );
        $fmv = new MongoInt32( $p['FMV'] );

        $sdata = array(
            'listingPrice'=>$price,
            'FMV'=>$fmv
            );

        if( $property->where('_id','=', $_id )->update( $sdata ) ){
            print $p['_id'].'->'.$sdata['listingPrice'].'<br />';
        }

    }

});

Route::get('brochure/dl/{id}',function($id){

    $prop = Property::find($id)->toArray();

    //return View::make('print.brochure')->with('prop',$prop)->render();

    $content = View::make('print.brochure')->with('prop',$prop)->render();

    //return $content;

    return PDF::loadView('print.brochure',array('prop'=>$prop))
        ->stream('download.pdf');
});

Route::post('brochure/mail/{id}',function($id){

    $prop = Property::find($id)->toArray();

    //$content = View::make('print.brochure')->with('prop',$prop)->render();

    $brochurepdf =  PDF::loadView('print.brochure',array('prop'=>$prop))->output();

    file_put_contents(public_path().'/storage/pdf/'.$prop['propertyId'].'.pdf', $brochurepdf);

    //$mailcontent = View::make('emails.brochure')->with('prop',$prop)->render();

    Mail::send('emails.brochure',$prop, function($message) use ($prop, &$prop){
        $to = Input::get('to');
        $tos = explode(',', $to);
        if(is_array($tos) && count($tos) > 1){
            foreach($tos as $to){
                $message->to($to, $to);
            }
        }else{
                $message->to($to, $to);
        }

        $message->subject('Investors Alliance - '.$prop['propertyId']);

        $message->cc('support@propinvestorsalliance.com');

        $message->attach(public_path().'/storage/pdf/'.$prop['propertyId'].'.pdf');
    });

    print json_encode(array('result'=>'OK'));

});

Route::get('pr/print/{id}',function($id){

    $trx = Transaction::find($id)->toArray();

    $prop = Property::find($trx['propObjectId'])->toArray();

    $agent = Agent::find($trx['agentId'])->toArray();

    return View::make('print.pr')->with('prop',$prop)->with('trx',$trx)->with('agent',$agent);

    //$content = View::make('print.brochure')->with('prop',$prop)->render();

    //return $content;

    //return PDF::loadView('print.pr',array('prop'=>$prop, 'trx'=>$trx, 'agent'=>$agent))
        //->stream('download.pdf');
});


Route::get('pr/dl/{id}',function($id){

    $trx = Transaction::find($id)->toArray();

    $prop = Property::find($trx['propObjectId'])->toArray();

    $agent = Agent::find($trx['agentId'])->toArray();

    //return View::make('print.brochure')->with('prop',$prop)->render();

    //$content = View::make('print.brochure')->with('prop',$prop)->render();

    //return $content;

    return PDF::loadView('print.pr',array('prop'=>$prop, 'trx'=>$trx, 'agent'=>$agent))
        ->stream('download.pdf');
});

Route::post('pr/mail/{id}',function($id){

    $prop = Property::find($id)->toArray();

    //$content = View::make('print.brochure')->with('prop',$prop)->render();

    $brochurepdf =  PDF::loadView('print.brochure',array('prop'=>$prop))->output();

    file_put_contents(public_path().'/storage/pdf/'.$prop['propertyId'].'.pdf', $brochurepdf);

    //$mailcontent = View::make('emails.brochure')->with('prop',$prop)->render();

    Mail::send('emails.brochure',$prop, function($message) use ($prop, &$prop){
        $to = Input::get('to');
        $tos = explode(',', $to);
        if(is_array($tos) && count($tos) > 1){
            foreach($tos as $to){
                $message->to($to, $to);
            }
        }else{
                $message->to($to, $to);
        }

        $message->subject('Investors Alliance - '.$prop['propertyId']);

        $message->cc('support@propinvestorsalliance.com');

        $message->attach(public_path().'/storage/pdf/'.$prop['propertyId'].'.pdf');
    });

    print json_encode(array('result'=>'OK'));

});


Route::get('pdf',function(){
    $content = "
    <page>
        <h1>Exemple d'utilisation</h1>
        <br>
        Ceci est un <b>exemple d'utilisation</b>
        de <a href='http://html2pdf.fr/'>HTML2PDF</a>.<br>
    </page>";

    $html2pdf = new HTML2PDF();
    $html2pdf->WriteHTML($content);
    $html2pdf->Output('exemple.pdf','D');
});

Route::get('brochure/dl/{id}',function($id){

    $prop = Property::find($id)->toArray();

    //return View::make('print.brochure')->with('prop',$prop)->render();

    $content = View::make('print.brochure')->with('prop',$prop)->render();

    //return $content;

    return PDF::loadView('print.brochure',array('prop'=>$prop))
        ->stream('download.pdf');
});

Route::get('brochure',function(){
    View::make('print.brochure');
});

Route::get('inc/{entity}',function($entity){

    $seq = new Sequence();
    print_r($seq->getNewId($entity));

});

Route::get('last/{entity}',function($entity){

    $seq = new Sequence();
    print( $seq->getLastId($entity) );

});

Route::get('init/{entity}/{initial}',function($entity,$initial){

    $seq = new Sequence();
    print_r( $seq->setInitialValue($entity,$initial));

});

Route::get('hashme/{mypass}',function($mypass){

    print Hash::make($mypass);
});

Route::get('xtest',function(){
    Excel::load('WEBSITE_INVESTORS_ALLIANCE.xlsx')->calculate()->dump();
});

Route::get('xcat',function(){
    print_r(Prefs::getCategory());
});

Route::get('media',function(){
    $media = Product::all();

    print $media->toJson();

});

Route::get('login',function(){
    return View::make('login');
});

Route::post('login',function(){

    // validate the info, create rules for the inputs
    $rules = array(
        'email'    => 'required|email',
        'password' => 'required|alphaNum|min:3'
    );

    // run the validation rules on the inputs from the form
    $validator = Validator::make(Input::all(), $rules);

    // if the validator fails, redirect back to the form
    if ($validator->fails()) {
        return Redirect::to('login')->withErrors($validator);
    } else {

        $userfield = Config::get('kickstart.user_field');
        $passwordfield = Config::get('kickstart.password_field');

        // find the user
        $user = User::where($userfield, '=', Input::get('email'))->first();


        // check if user exists
        if ($user) {
            // check if password is correct
            if (Hash::check(Input::get('password'), $user->{$passwordfield} )) {

                //print $user->{$passwordfield};
                //exit();
                // login the user
                Auth::login($user);

                return Redirect::to('/');

            } else {
                // validation not successful
                // send back to form with errors
                // send back to form with old input, but not the password
                Session::flash('loginError', 'Email and password mismatch');
                return Redirect::to('login')
                    ->withErrors($validator)
                    ->withInput(Input::except('password'));
            }

        } else {
            // user does not exist in database
            // return them to login with message
            Session::flash('loginError', 'This user does not exist.');
            return Redirect::to('login');
        }

    }

});

Route::get('logout',function(){
    Auth::logout();
    return Redirect::to('/');
});

/* Filters */

Route::filter('auth', function()
{

    if (Auth::guest()){
        Session::put('redirect',URL::full());
        return Redirect::to('login');
    }

    if($redirect = Session::get('redirect')){
        Session::forget('redirect');
        return Redirect::to($redirect);
    }

    //if (Auth::guest()) return Redirect::to('login');
});
