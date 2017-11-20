<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Place;
use App\Province;
use App\Favorite;
use App\User;
use App\Review;
use App\Schedule;
use App\SchedulePlace;
use App\ScheduleDay;

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::get('myroute', function() {
	echo "Hello";
});

Route::resource('Province', 'ControllerProvince');

Route::resource('Place', 'ControllerPlace');

Route::post('Province/get/all', function(Request $request){
	$provinces = Province::all();
	$result = array('status' => "OK", 'result' => $provinces, 'message' => "");
	return $result;
});

Route::post('PlaceSlider/get/all', function(Request $request) {
	$places = array();
	$array = array('P3523', 'P3562', 'P3672', 'P2332', 'P2312');
	for ($i=0; $i < sizeof($array); $i++) { 
		$place = Place::find($array[$i]);
		array_push($places, $place);
	}
	$result = array('status' => "OK", 'result' => $places, 'message' => "");
	return $result;
});

Route::post('Place/find/province', function(Request $request){
	$result = Place::where('province_id', $request->input("id"))->get();
	return array('status' => "OK", 'result' => $result, 'message' => "");
});

Route::post('Place/get/detail', function(Request $request){
	$result = Place::where('id', $request->input("id"))->first();
	if ($result == null) {
		return array('status' => "ERROR", 'result' => null, 'message' => "");
	}
	return array('status' => "OK", 'result' => $result, 'message' => "");
});

Route::post('Place/find/query', function(Request $request){
	$query = "%".$request->input('query')."%";
	$places = Place::where('long_name', 'like', $query)
					->orwhere('province_name', 'like', $query)
					->get();
	$provinces = null;
	
	return array('status' => "OK", 'result' => ['places' => $places], 'message' => "");
});

Route::post('User/add/new', function(Request $request){
	$count = User::where('email', $request->input("email"))->count();
	if ($count > 0){
		return array('status' => "ERROR", 'result' => array(), 'message' => "User đã tồn tại!");
	} else {
		try {
			$user = new User;
			$user->id = $request->input("id");
			$user->name = $request->input("name");
			$user->email = $request->input("email");
			$user->profile_photo_url = $request->input("profile_photo_url");
			$user->save();
			return array('status' => "OK", 'result' => response($user, 201), 'message' => "");
		} catch (Exception $e) {
			return array('status' => "ERROR", 'result' => array(), 'message' => "");
		}
	}
});

Route::post('Favorite/add/new', function(Request $request){
	$count = Favorite::where('idUser', $request->input("idUser"))
						->where('idPlace', $request->input("idPlace"))
						->count();
	if ($count > 0) {
		return array('status' => "ERROR", 'result' => "", 'message' => "Đã tồn tại!");
	} else {
		$favorite = new Favorite;
		$favorite->idUser = $request->input("idUser");
		$favorite->idPlace = $request->input("idPlace");
		$favorite->save();
		$result = Place::where('id', $favorite->idPlace)->get();
		return array('status' => "OK", 'result' => $result, 'message' => "");
	}
});

Route::post('Favorite/remove/new', function(Request $request){
	$favorite = Favorite::where('idUser', $request->input("idUser"))
						->where('idPlace', $request->input("idPlace"))
						->delete();
	$result = Place::where('id', $request->input("idPlace"))->get();
	return array('status' => "OK", 'result' => $result, 'message' => "");
});

Route::post('Favorite/get/user', function(Request $request){
	$favorites = Favorite::where('idUser', $request->input("idUser"))->get();
	$result = array();
	for ($i = 0; $i < count($favorites); $i++) { 
		$tam = Place::where('id', $favorites[$i]->idPlace)->first();
		array_push($result, $tam);
	}
	return array('status' => "OK", 'result' => $result, 'message' => "");
});

Route::post('Favorite/check/id', function(Request $request){
	$count = Favorite::where('idUser', $request->input("idUser"))
		->where('idPlace', $request->input("idPlace"))->count();
	$result = false;
	if ($count > 0) {
		$result = true;
	}
	return array('status' => "OK", 'result' => $result, 'message' => "");
});

Route::post('Place/find/type', function(Request $request){
	$type = $request->input("type");
	switch ($type) {
		case '2':
			$result = Place::where('type_sea', "1")->get();
			return array('status' => "OK", 'result' => $result, 'message' => "");

		case '3':
			$result = Place::where('type_attractions', "1")->get();
			return array('status' => "OK", 'result' => $result, 'message' => "");

		case '4':
			$result = Place::where('type_cultural', "1")->get();
			return array('status' => "OK", 'result' => $result, 'message' => "");

		case '5':
			$result = Place::where('type_entertainment', "1")->get();
			return array('status' => "OK", 'result' => $result, 'message' => "");

		case '6':
			$result = Place::where('type_spring', "1")->get();
			return array('status' => "OK", 'result' => $result, 'message' => "");

		case '7':
			$result = Place::where('type_summer', "1")->get();
			return array('status' => "OK", 'result' => $result, 'message' => "");

		case '8':
			$result = Place::where('type_autumn', "1")->get();
			return array('status' => "OK", 'result' => $result, 'message' => "");

		case '9':
			$result = Place::where('type_winter', "1")->get();
			return array('status' => "OK", 'result' => $result, 'message' => "");
		
		default:
			return array('status' => "ERROR", 'result' => "", 'message' => $type);
	}
});

Route::post('Review/add/user', function(Request $request){
	$review = Review::where('email', $request->input("email"))
						->where('id_place', $request->input("id_place"))
						->count();
	if ($review > 0) {
		return array('status' => "ERROR", 'result' => array(), 'message' => "Đã tồn tại!");
	} else {
		$result = new Review;
		$result->author_name = $request->input("author_name");
		$result->email = $request->input("email");
		$result->profile_photo_url = $request->input("profile_photo_url");
		$result->id_place = $request->input("id_place");
		$result->rating = $request->input("rating");
		$result->text = $request->input("text");
		$result->time = $request->input("time");
		$result->save();
		return array('status' => "OK", 'result' => true, 'message' => "");
	}
});

Route::post('Review/edit/user', function(Request $request){
	$review = Review::where('email', $request->input("email"))
						->where('id_place', $request->input("id_place"))
						->first();
	if ($review == null) {
		return array('status' => "ERROR", 'result' => "", 'message' => "Không tồn tại!");
	} else {
		$review->rating = $request->input("rating");
		$review->text = $request->input("text");
		$review->time = $request->input("time");
		$review->save();
		return array('status' => "OK", 'result' => true, 'message' => "");
	}
});

Route::post('Review/delete/user', function(Request $request){
	$review = Review::where('email', $request->input("email"))
						->where('id_place', $request->input("id_place"))
						->first();
	if ($review == null) {
		return array('status' => "ERROR", 'result' => "", 'message' => "Không tồn tại!");
	} else {
		$review->delete();
		return array('status' => "OK", 'result' => true, 'message' => "Xóa thành công");
	}
});

Route::post('Review/get/id', function(Request $request){
	$result = Review::where('id_place', $request->input("id_place"))->get();
	return array('status' => "OK", 'result' => $result, 'message' => "");
});

Route::post('Place/edit/all', function(Request $request){
	$place = Place::where('id', $request->input("id"))->first();
	if ($place == null) {
		return array('status' => "ERROR", 'result' => false, 'message' => "Khong tim thay id");
	}
	$place->rating = $request->input("rating");
	if ($place->address === '') {
		$place->address = $request->input("address");
	}
	if ($place->phone === '') {
		$place->phone = $request->input("phone");
	}
	if ($place->location_lat == 0) {
		$place->location_lat = $request->input("location_lat");
	}
	if ($place->location_lng == 0) {
		$place->location_lng = $request->input("location_lng");
	}
	if ($place->opening_hours === '') {
		$place->opening_hours = $request->input("opening_hours");
	}
	if ($place->website === '') {
		$place->website = $request->input("website");
	}
	$place->save();
	return array('status' => "OK", 'result' => true, 'message' => "");
});

Route::post('Schedule/add/new', function(Request $request){
	$array = Schedule::where('email', $request->input("email"))->get()->toArray();
	$flag = true;

	$start = $request->input('date_start');
	$end = $request->input('date_end');
	foreach ($array as $item) {
		if (($start > $item['date_start'] && $start < $item['date_end']) 
			|| ($end > $item['date_start'] && $end < $item['date_end'])
			|| ($start <= $item['date_start'] && $end >= $item['date_end'])){
			$flag = false;
			break;
		}
	};

	if (!$flag) {
		return array('status' => "ERROR", 'result' => array(), 'message' => "Đã tồn tại!");
	} else {
		$schedule = new Schedule;
		$schedule->name = $request->input("name");
		$schedule->email = $request->input("email");
		$schedule->date_start = $request->input("date_start");
		$schedule->date_end = $request->input("date_end");
		$schedule->length = (int)(($request->input("date_end") - $request->input("date_start") + 86400)/86400);
		$schedule->save();
		//$result = Place::where('id', $favorite->idPlace)->get();
		return array('status' => "OK", 'result' => $schedule, 'message' => "");
	}
});

Route::post('Schedule/delete/id', function(Request $request){
	$schedule = Schedule::where('email', $request->input("email"))
						->where('id', $request->input("id"))
						->first();
	if ($schedule == null) {
		return array('status' => "ERROR", 'result' => array(), 'message' => "Không tồn tại!");
	} else {
		$schedule->delete();
		SchedulePlace::where('id_schedule', $request->input('id'))->delete();
		ScheduleDay::where('id_schedule', $request->input('id'))->delete();
		return array('status' => "OK", 'result' => array(), 'message' => "Xóa thành công");
	}
});

Route::post('Schedule/edit/new', function(Request $request){
	$schedule = Schedule::where('email', $request->input("email"))
			->where('id', $request->input("id"))
			->first();

	$flag = true;
	$num = -1;
	$start = $request->input('date_start');
	$end = $request->input('date_end');
	$array = Schedule::where('email', $request->input("email"))->get()->toArray();
	foreach ($array as $item) {
		if ($item['id'] == $request->input("id")) {
			continue;
		}
		if (($start > $item['date_start'] && $start < $item['date_end']) 
			|| ($end > $item['date_start'] && $end < $item['date_end'])
			|| ($start <= $item['date_start'] && $end >= $item['date_end'])){
			$flag = false;
			$num = $item['id'];
			break;
		}
	};

	if (!$flag) {
		return array('status' => "ERROR", 'result' => array(), 'message' => $num);
	} else {
		$schedule->name = $request->input("name");
		$schedule->date_start = $request->input("date_start");
		$schedule->date_end = $request->input("date_end");
		$schedule->length = (int)(($request->input("date_end") - $request->input("date_start") + 1)/86400);
		$schedule->save();
		//$result = Place::where('id', $favorite->idPlace)->get();
		return array('status' => "OK", 'result' => $schedule, 'message' => "");
	}
});

Route::post('Schedule/get/email', function(Request $request){
	$array = Schedule::where('email', $request->input("email"))->get()->toArray();
	foreach ($array as $item) { 
		$item['place'] = SchedulePlace::where('id_schedule', $item['id'])->count();
	}
	return array('status' => "OK", 'result' => $array, 'message' => "");
});

Route::post('Schedule/get/id', function(Request $request){
	$array = Schedule::where('email', $request->input("email"))
					->where('id', $request->input("id"))->get();
	return array('status' => "OK", 'result' => $array, 'message' => "");
});

Route::post('SchedulePlace/add/new', function(Request $request){
	$schedule = Schedule::where('id', $request->input('id_schedule'))->first();
	$schedule_start = $schedule->date_start;
	$schedule_end = $schedule->date_end;
	$array = SchedulePlace::where('email', $request->input("email"))
						->where('id_schedule', $request->input('id_schedule'))->get()->toArray();
	$flag = true;

	$start = $request->input('date_start');
	$end = $request->input('date_end');
	if ($start < $schedule_start || $end > $schedule_end || $start >= $end) {
		$flag = false;
	} else {
		foreach ($array as $item) {
			if (($start > $item['date_start'] && $start < $item['date_end']) 
				|| ($end > $item['date_start'] && $end < $item['date_end'])
				|| ($start <= $item['date_start'] && $end >= $item['date_end'])){
				$flag = false;
				break;
			}
		};
	}
	

	if (!$flag) {
		return array('status' => "ERROR", 'result' => array(), 'message' => "Đã tồn tại!");
	} else {
		$schedulePlace = new SchedulePlace;
		$schedulePlace->id_schedule = $request->input("id_schedule");
		$schedulePlace->id_place = $request->input("id_place");
		$schedulePlace->name = Place::where('id', $request->input("id_place"))->first()->long_name;
		$schedulePlace->email = $request->input("email");
		$schedulePlace->date_start = $request->input("date_start");
		$schedulePlace->date_end = $request->input("date_end");
		$schedulePlace->description = $request->input("description");
		$schedulePlace->length = (int)(($request->input("date_end") - $request->input("date_start") + 1)/86400);
		$schedulePlace->save();
		$schedule->place += 1;
		$schedule->save();
		//$result = Place::where('id', $favorite->idPlace)->get();
		return array('status' => "OK", 'result' => $schedulePlace, 'message' => $flag);
	}
});


Route::post('SchedulePlace/delete/id', function(Request $request){
	$schedulePlace = SchedulePlace::where('email', $request->input("email"))
						->where('id', $request->input("id"))
						->first();
	if ($schedulePlace == null) {
		return array('status' => "ERROR", 'result' => array(), 'message' => "Không tồn tại!");
	} else {
		$schedulePlace->delete();
		$schedule = Schedule::where('id', $schedulePlace->id_schedule)->first();
		$schedule->place -= 1;
		$schedule->save();
		$array = ScheduleDay::where('id_schedule_place', $request->input("id"))->delete();
		return array('status' => "OK", 'result' => array(), 'message' => "Xóa thành công");
	}
});

Route::post('SchedulePlace/edit/new', function(Request $request){
	$schedulePlace = SchedulePlace::where('id', $request->input('id'))->first();
	$schedule = Schedule::where('id', $request->input('id_schedule'))->first();
	$schedule_start = $schedule->date_start;
	$schedule_end = $schedule->date_end;
	$array = SchedulePlace::where('email', $request->input("email"))
						->where('id_schedule', $request->input('id_schedule'))->get()->toArray();
	$flag = true;

	$start = $request->input('date_start');
	$end = $request->input('date_end');
	if ($start < $schedule_start || $end > $schedule_end || $start >= $end) {
		$flag = false;
	} else {
		foreach ($array as $item) {
			if ($item['id'] == $request->input("id")) {
				continue;
			}
			if (($start > $item['date_start'] && $start < $item['date_end']) 
				|| ($end > $item['date_start'] && $end < $item['date_end'])
				|| ($start <= $item['date_start'] && $end >= $item['date_end'])){
				$flag = false;
				break;
			}
		};
	}
	

	if (!$flag || $schedulePlace == null) {
		return array('status' => "ERROR", 'result' => array(), 'message' => "Đã tồn tại!");
	} else {
		// $schedulePlace = new SchedulePlace;
		// $schedulePlace->id_schedule = $request->input("id_schedule");
		// $schedulePlace->id_place = $request->input("id_place");
		// $schedulePlace->email = $request->input("email");
		$schedulePlace->date_start = $request->input("date_start");
		$schedulePlace->date_end = $request->input("date_end");
		$schedulePlace->description = $request->input("description");
		$schedulePlace->length = (int)(($request->input("date_end") - $request->input("date_start") + 1)/86400);
		$schedulePlace->save();
		// $schedule->place += 1;
		// $schedule->save();
		//$result = Place::where('id', $favorite->idPlace)->get();
		return array('status' => "OK", 'result' => $schedulePlace, 'message' => $schedule_start);
	}
});

Route::post('SchedulePlace/get/id_schedule', function(Request $request){
	$array = SchedulePlace::where('email', $request->input("email"))
						->where('id_schedule', $request->input('id_schedule'))->get()->toArray();
	// foreach ($array as $item) { 
	// 	$item['place'] = SchedulePlace::where('id_schedule', $item['id'])->count();
	// }
	return array('status' => "OK", 'result' => $array, 'message' => "");
});

Route::post('SchedulePlace/get/id', function(Request $request){
	$array = SchedulePlace::where('email', $request->input("email"))
					->where('id', $request->input("id"))->first();
	if ($array == null) {
		return array('status' => "OK", 'result' => null, 'message' => "Không tồn tại!");
	}
	return array('status' => "OK", 'result' => $array, 'message' => "");
});

Route::post('ScheduleDay/add/new', function(Request $request){
	$schedule = ScheduleDay::where('email', $request->input('email'))
							->where('id_schedule', $request->input('id_schedule'))
							->where('id_schedule_place', $request->input('id_schedule_place'))
							->where('place_id', $request->input('place_id'))
							->where('type', $request->input('type'))->first();
	if ($schedule!=null) {
		return array('status' => "ERROR", 'result' => null, 'message' => "Đã tồn tại!");
	} else {
		$scheduleDay = new ScheduleDay;
		$scheduleDay->id_schedule = $request->input("id_schedule");
		$scheduleDay->id_schedule_place = $request->input("id_schedule_place");
		$scheduleDay->place_id = $request->input("place_id");
		$scheduleDay->email = $request->input("email");
		$scheduleDay->type = $request->input("type");
		$scheduleDay->save();
		return array('status' => "OK", 'result' => $scheduleDay, 'message' => "");
	}
});

Route::post('ScheduleDay/get/schedule_place', function(Request $request){
	$array = ScheduleDay::where('email', $request->input('email'))
							->where('id_schedule', $request->input('id_schedule'))
							->where('id_schedule_place', $request->input('id_schedule_place'))
							->where('type', $request->input('type'))
							->get();
	return array('status' => "OK", 'result' => $array, 'message' => "");
});

Route::post('ScheduleDay/delete/id', function(Request $request){
	$scheduleDay = ScheduleDay::where('email', $request->input('email'))
							->where('id_schedule', $request->input('id_schedule'))
							->where('id_schedule_place', $request->input('id_schedule_place'))
							->where('place_id', $request->input('place_id'))
							->where('type', $request->input('type'))->first();
	if ($scheduleDay == null) {
		return array('status' => "ERROR", 'result' => array(), 'message' => "Không tồn tại!");
	} else {
		$scheduleDay->delete();
		return array('status' => "OK", 'result' => array(), 'message' => "Xóa thành công");
	}
});