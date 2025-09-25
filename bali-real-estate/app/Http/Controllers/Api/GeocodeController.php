<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GeocodingService;

class GeocodeController extends Controller {
  public function __invoke(Request $req, GeocodingService $geo){
    // 開発中はauth不要でもOK。必要なら ->middleware('auth') をルート側で。
    $addr = trim((string)$req->query('address',''));
    $pos = $geo->geocode($addr);
    return response()->json($pos ?: []);
  }
}
