<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\CCity;
use App\Models\CCommune;
use App\Models\AdUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\SuccessEmail;

use Exception;

class mainController extends Controller
{

    public function getData()
    {

        $data = Cache::remember('data1', 1600, function () {
            return DB::select("SELECT texteconcours, intituleconcours, titreconcours, agrementtext, soustitre , with_note
                            FROM adempiere.sys_promotion 
                            WHERE isactive='Y'");
        });
        return response()->json($data);
    }

    public function getWilayas()
    {
        // $wilayas = Cache::remember('wilayas', 3600, function () {
        return DB::select("SELECT
            c_city.c_city_id,
            c_city.name,
            c_city.name_ar,
            c_city.locode,
            c_city.postal,
            sys_universitescity.sys_universites_id,
            sys_centreexamen.sys_centreexamen,
            sys_centreexamen.sys_centreexamen_ar,
            sys_centreexamen.sys_centreexamen_id 
            FROM
            adempiere.c_city
            INNER JOIN adempiere.sys_universitescity ON c_city.c_city_id = sys_universitescity.c_city_id
            INNER JOIN adempiere.sys_centreexamen ON sys_universitescity.sys_centreexamen_id = sys_centreexamen.sys_centreexamen_id
            WHERE c_city.isactive= 'Y'");
        // });

        // return $wilayas;
    }

    public function getCommunes(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'wilayaId' => 'required|integer',
                // Add other fields that you might need to validate
            ]);
        } catch (ValidationException $e) {
            // Customize the error response if needed
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
        $id = $validatedData['wilayaId'];
        $key = "commune_wilaya_{$id}";

        $Communes = Cache::remember($key, 10, function () use ($id) {
            return CCommune::select()->where('c_city_id', '=', $id)->orderBy('hh_commune_libelle', 'Asc')->get();
        });

        return $Communes;
    }

    public function getCentresExamen(Request $request) // need cache
    {
        try {
            $validatedData = $request->validate([
                'c_city_id' => 'required|integer',
                // Add other fields that you might need to validate
            ]);
        } catch (ValidationException $e) {
            // Customize the error response if needed
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
        $c_city_id = $validatedData['c_city_id'];

        $centres =  DB::select('SELECT 
                                    sys_centreexamen.sys_centreexamen_id,
                                    sys_centreexamen.sys_centreexamen,
                                    c_city.name AS wilaya,
                                    c_city.c_city_id
                                from adempiere.sys_universitescity 
                                INNER JOIN adempiere.sys_centreexamen	ON 
		                        adempiere.sys_universitescity.sys_centreexamen_id = adempiere.sys_centreexamen.sys_centreexamen_id 
                                INNER JOIN adempiere.c_city ON sys_universitescity.c_city_id = c_city.c_city_id
                                WHERE sys_universitescity.c_city_id =' . $c_city_id . '');

        return $centres;
        // ->whereRaw('sys_promotion.isactive=?', ['Y']);
        // return sys_centreExamen()
    }

    public function getSituations()
    {
        $situations = [
            ['id' => '1', 'name' => 'Non concernée'],
            ['id' => '2', 'name' => 'Accomplis'],
            ['id' => '3', 'name' => 'Dispensé'],
            ['id' => '4', 'name' => 'Sursitaire'],
            ['id' => '5', 'name' => 'Non-justifié']
        ];
        return $situations;
    }

    public function getTypeDiplomes()
    {
        $Diplomes = Cache::remember('diplomes', 600, function () {
            $latestPosts = DB::table('adempiere.sys_cond_form')
                ->join('adempiere.sys_promotion', 'sys_cond_form.sys_promotion_id', '=', 'sys_promotion.sys_promotion_id')
                ->selectRaw('sys_cond_form.*')
                ->whereRaw('sys_promotion.isactive=?', ['Y']);

            return  DB::table('adempiere.sys_diplomecon')
                ->leftJoinSub($latestPosts, 'latest_posts', function ($join) {
                    $join->on('sys_diplomecon.sys_diplomecon_id', '=', 'latest_posts.sys_diplomecon_id');
                })->selectRaw('sys_diplomecon.*,max_age,min_moyen')->whereRaw('sys_diplomecon.isactive=?', ['Y'])->orderBy('ordre')->get();
        });

        // $latestPosts = DB::table('adempiere.sys_cond_form')
        //     ->join('adempiere.sys_promotion', 'sys_cond_form.sys_promotion_id', '=', 'sys_promotion.sys_promotion_id')
        //     ->selectRaw('sys_cond_form.*')
        //     ->whereRaw('sys_promotion.isactive=?', ['Y']);

        // $Diplomes = DB::table('adempiere.sys_diplomecon')
        //     ->leftJoinSub($latestPosts, 'latest_posts', function ($join) {
        //         $join->on('sys_diplomecon.sys_diplomecon_id', '=', 'latest_posts.sys_diplomecon_id');
        //     })->selectRaw('sys_diplomecon.*,max_age,min_moyen')->whereRaw('sys_diplomecon.isactive=?', ['Y'])->orderBy('ordre')->get();
        // //$Diplomes = SysDiplomecon::select()->where('isactive','Y')->orderBy('ordre')->get();
        // return $Diplomes;

        return $Diplomes;
    }

    public function getSpecialitesDiplome(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'sys_diplomecon_id' => 'required|integer',
                // Add other fields that you might need to validate
            ]);
        } catch (ValidationException $e) {
            // Customize the error response if needed
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
        $sys_diplomecon_id = $validatedData['sys_diplomecon_id'];

        $key = "Sys_diplomecon_{$sys_diplomecon_id}";

        $SpecialitesDiplome = Cache::remember($key, 600, function () use ($sys_diplomecon_id) {
            return DB::select("SELECT specdiplome,sys_specdiplome_id FROM 
            adempiere.sys_specdiplome WHERE sys_diplomecon_id = " . $sys_diplomecon_id . " AND
             isactive = 'Y' order by specdiplome");
        });

        return $SpecialitesDiplome;
    }

    public function getUniversites(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'wilayaId' => 'required|integer',
                // Add other fields that you might need to validate
            ]);
        } catch (ValidationException $e) {
            // Customize the error response if needed
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
        $id = $validatedData['wilayaId'];
        $key = "univ_wilaya_{$id}";

        // DB::listen(fn($e)=>dump($e->toRawSql()));
        $Universites = Cache::remember($key, 600, function () use ($id) {
            return DB::select("SELECT * FROM adempiere.sys_universites Where c_city_id = " . $id . "ORDER BY universites");
        });
        return $Universites;
    }

    public function getFormationAutorisees(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'sys_specdiplome_id' => 'required|integer',
                'c_city_id' => 'nullable|integer',
                // Add other fields that you might need to validate
            ]);
        } catch (ValidationException $e) {
            // Customize the error response if needed
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }

        // $promo =  DB::select(`select * from adempiere.sys_promotion where sys_promotion.isactive='Y' AND sys_promotion.is_national= 'Y'`);
        $promo =  DB::select("SELECT * FROM adempiere.sys_promotion where sys_promotion.isactive='Y'
                            AND  sys_promotion.is_national= 'N'");

        $sys_specdiplome_id = $request->input('sys_specdiplome_id');
        $c_city_id = $request->input('c_city_id');


        // dd($promo);
        // echo $promo;
        // DB::enableQueryLog();
        // if ($promo) {
        // dd("Prom");
        $FormationAutorisees = DB::select("SELECT DISTINCT
            sys_speciliteform.sys_speciliteform_id ,sys_speciliteform.speciliteform
            FROM adempiere.sys_speciliteinscr
            INNER JOIN adempiere.sys_speciliteform 
            ON adempiere.sys_speciliteform.sys_speciliteform_id = adempiere.sys_speciliteinscr.sys_speciliteform_id
            INNER JOIN adempiere.sys_wilayaspec 
            ON adempiere.sys_wilayaspec.sys_speciliteform_id = adempiere.sys_speciliteform.sys_speciliteform_id
            WHERE sys_speciliteinscr.sys_specdiplome_id = " . $sys_specdiplome_id . " AND sys_speciliteform.isactive= 'Y' 
            AND sys_speciliteinscr.isactive= 'Y'  order by sys_speciliteform.speciliteform ");
        // } else {
        //     if(!$c_city_id) {
        //         $c_city_id = 100000;
        //     }

        //     $FormationAutorisees = DB::select("SELECT
        //     sys_speciliteform.sys_speciliteform_id ,sys_speciliteform.speciliteform
        //     FROM adempiere.sys_speciliteinscr
        //     INNER JOIN adempiere.sys_speciliteform 
        //     ON adempiere.sys_speciliteform.sys_speciliteform_id = adempiere.sys_speciliteinscr.sys_speciliteform_id
        //     INNER JOIN adempiere.sys_wilayaspec 
        //     ON adempiere.sys_wilayaspec.sys_speciliteform_id = adempiere.sys_speciliteform.sys_speciliteform_id
        //     WHERE sys_speciliteinscr.sys_specdiplome_id = " . $sys_specdiplome_id . "AND  
        //     sys_wilayaspec.c_city_id = " . $c_city_id . " order by sys_speciliteform.speciliteform");
        // }

        // dd(DB::getQueryLog());


        // this request needs to be reviewed
        // $FormationAutorisees = DB::select("SELECT
        // sys_speciliteform.sys_speciliteform_id ,sys_speciliteform.speciliteform
        // FROM adempiere.sys_speciliteinscr
        // INNER JOIN adempiere.sys_speciliteform 
        // ON adempiere.sys_speciliteform.sys_speciliteform_id = adempiere.sys_speciliteinscr.sys_speciliteform_id
        // INNER JOIN adempiere.sys_wilayaspec 
        // ON adempiere.sys_wilayaspec.sys_speciliteform_id = adempiere.sys_speciliteform.sys_speciliteform_id
        // WHERE sys_speciliteinscr.sys_specdiplome_id = " . $sys_specdiplome_id . " order by sys_speciliteform.speciliteform");

        // $key = "Autorisees_{$sys_specdiplome_id}{$c_city_id}";
        // this request needs to be reviewed
        // dd($key);
        // $FormationAutorisees = Cache::remember($key, 60, function () use ($sys_specdiplome_id, $c_city_id) {
        //     return DB::select("SELECT
        //     sys_speciliteform.sys_speciliteform_id ,sys_speciliteform.speciliteform
        //     FROM adempiere.sys_speciliteinscr
        //     INNER JOIN adempiere.sys_speciliteform 
        //     ON adempiere.sys_speciliteform.sys_speciliteform_id = adempiere.sys_speciliteinscr.sys_speciliteform_id
        //     INNER JOIN adempiere.sys_wilayaspec 
        //     ON adempiere.sys_wilayaspec.sys_speciliteform_id = adempiere.sys_speciliteform.sys_speciliteform_id
        //     WHERE sys_speciliteinscr.sys_specdiplome_id = " . $sys_specdiplome_id . "AND  
        //     sys_wilayaspec.c_city_id = " . $c_city_id . " order by sys_speciliteform.speciliteform");
        // });

        return $FormationAutorisees;
    }

    public  function addCondidats(Request $request)
    {
        $recaptchaToken = $request->input('recaptchaToken');
        if(!$recaptchaToken) return response()->json(['error' => 'reCAPTCHA requierd.'], 422);
        $response = (new Client)->post('https://www.google.com/recaptcha/api/siteverify', [
            'form_params' => [
                'secret' => config('services.recaptcha.secret'),
                'response' => $recaptchaToken
            ],
        ]);

        $body = json_decode($response->getBody()->getContents(), true);
        if (!$body['success']) {
            return response()->json(['error' => 'reCAPTCHA verification failed.'], 422);
        }

        try {
            $validatedData =  $request->validate([
                'numinscritanem' => 'required|string|unique:pgsql.adempiere.ad_user,numinscritanem',
                'email' => 'required|email|unique:pgsql.adempiere.ad_user,email',
                'phone' => 'required|unique:pgsql.adempiere.ad_user,phone|digits_between:10,12',
                // this fields should be indexed in the database

                'nom' => 'required|string|max:25|min:2|regex:/^[a-zA-Z\s]+$/',
                'nom_ar' => 'required|string|max:25|min:2|regex:/^[\p{Arabic}\s\-]+$/u',
                'prenom' => 'required|string|max:25|min:2|regex:/^[a-zA-Z\s]+$/',
                'prenom_ar' => 'required|string|max:25|min:2|regex:/^[\p{Arabic}\s\-]+$/u',

                'adresse' => 'nullable|max:125|min:5|regex:/^[a-zA-Z0-9\s]+$/',
                'sexe' => 'required|string|in:M,F',
                'situation' => 'nullable|integer|in:1,2,3,4,5',
                'datenaiss' => ['required', 'date_format:Y-m-d', 'before:' . now()->subYears(18)->format('Y-m-d')],
                'c_city_id' => 'required|integer|digits:7',
                'hh_commune_id' => 'nullable|integer|digits:7',
                'nom_institut' => 'nullable|string|max:255|min:3|regex:/^[a-zA-Z0-9\s\sÀ-ÖØ-öø-ÿ]+$/',
                'wilaya_institut' => 'nullable|string|max:255|min:3|regex:/^[a-zA-Z0-9\s\sÀ-ÖØ-öø-ÿ]+$/',
                'commune_institut' => 'nullable|string|max:255|min:3',
                'sys_universites_id' => 'required|integer|digits:7',
                'sys_diplomecon_id' => 'required|integer|digits:7',
                'sys_specdiplome_id' => 'required|integer|digits:7',
                'sys_speciliteform_id' => 'required|integer|digits:7',
                'confirmation_specialite' => 'required|string|max:255|min:3|regex:/^[a-zA-Z0-9\s\sÀ-ÖØ-öø-ÿ]+$/',
                'document' => 'required|file|mimes:jpg,png,pdf|max:4048', // Adjust in accepted file size in  php ini
                'recaptchaToken' => 'required'
            ]);


            // dd($validatedData);
            $sys_diplomCon_id = $validatedData['sys_diplomecon_id'];
            $key = "conAge_{$sys_diplomCon_id}";

            $sys_diplomCon = Cache::remember($key, 600, function () use ($sys_diplomCon_id) {
                return DB::table('adempiere.sys_diplomecon')->whereRaw('sys_diplomecon.sys_diplomecon_id=?', [$sys_diplomCon_id])->get();
            });


            // compare the diplom age condition with the candidate age.
            if ($sys_diplomCon && isset($sys_diplomCon[0]->max_age1)) {
                $dob = new \DateTime($validatedData['datenaiss']);
                // Get the current date
                $now = new \DateTime();
                // Calculate the difference between the current date and the date of birth
                $age = $now->diff($dob)->y;
                // Return the age in years
                if ($sys_diplomCon[0]->max_age1 < $age) {
                    return response()->json(['errors' => 'Condition d\'âge non satisfaite'], 422);
                }
            } else {
                // Handle the case where the record was not found or max_age is not set
                throw new Exception('Contacter l\'Admin.', 500);
            }

            $sys_promotion = Cache::remember('sys_promotion', 3600, function () {
                return DB::table('adempiere.sys_promotion')->whereRaw('sys_promotion.isactive=?', ['Y'])->get();
            });
            // dd($sys_promotion[0]->with_note);
            if ($sys_promotion[0]->with_note === "Y") {
                $moyen_validation =  $request->validate([
                    'inganneune' => 'required|numeric|min:0|max:20',
                    'ingannedeux' => 'required|numeric|min:0|max:20',
                    'ingannetrois' => 'required|numeric|min:0|max:20',
                    'ingannequatre' => 'required|numeric|min:0|max:20',
                    'ingannecinq' => 'required|numeric|min:0|max:20',
                    'ingannesix' => 'nullable|numeric|min:0|max:20',
                    'ingannesept' => 'nullable|numeric|min:0|max:20',
                    'moyeng' => 'required|numeric|min:0|max:20',
                ]);

                $validatedData = array_merge($validatedData, $moyen_validation);
            }

            $unique_id  = $this->generateUUIDKey($validatedData['nom'], $validatedData['prenom'], $validatedData['datenaiss']);

            $filePath = $this->uploadFile($request->file('document'), $validatedData['numinscritanem']);

            $validatedData['imagelink'] = $filePath->getData();
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $validatedData['unique_id'] = $unique_id;
        $validatedData['ad_client_id'] = 1000000;
        $validatedData['ad_org_id'] = 0;
        $validatedData['createdby'] = 1000000;
        $validatedData['updatedby'] = 1000000;
        $validatedData['name'] = $validatedData['nom'] . ' ' . $validatedData['prenom'];

        $time = strtotime($request['datenaiss']);
        $daten = date('Y-m-d', $time);
        $validatedData['datenaiss'] = $daten;

        try {
            $saved = AdUser::create($validatedData);
            unset($saved['imagelink']);
            // Mail::raw('This is a test email', function ($message) { //     $message->to('zakyboudo@gmail.com')->subject('Test Email'); });
            // Mail::to($saved['email'])->send(new SuccessEmail($saved));

            $data = [];
            $data['diplom'] = $sys_diplomCon[0]->diplom;

            $specdiplome_key = "specdiplome_{$saved->sys_specdiplome_id}";
            $specdiplome = Cache::remember($specdiplome_key, 7200, function () use ($saved) {
                return DB::select('select specdiplome from adempiere.sys_specdiplome where sys_specdiplome_id = ' . $saved->sys_specdiplome_id);
            });


            $speciliteform_key = "speciliteform_{$saved->sys_speciliteform_id}";
            $speciliteform = Cache::remember($speciliteform_key, 7200, function () use ($saved) {
                return DB::select('select speciliteform from adempiere.sys_speciliteform where sys_speciliteform_id = ' . $saved->sys_speciliteform_id);
            });


            $data['specdiplome'] = $specdiplome[0]->specdiplome;
            $data['speciliteform'] = $speciliteform[0]->speciliteform;

            // dd($data ,$saved);
            // Mail::to($saved['email'])->send(new SuccessEmail($saved, $data));
            // ------------------------ using the queue in Redis for Emails , faild jobs are stored in DB
            // start the queue : php artisan queue:work 
            Mail::to($validatedData['email'])->queue(new SuccessEmail($saved, $data));
        } catch (Exception $e) {
            // dd($e);
            // Log the error
            Log::error('creating candidate Error : ' . $e->getMessage());
            return response()->json(['errors' => $e->getMessage()], 500);
        }

        return $saved;
    }

    public function generateUUIDKey($name, $prenom, $birthdate)
    {
        $initials = strtoupper(substr($name, 0, 5)) . strtoupper(substr($prenom, 0, 5));
        // $randomString = Str::random(5);
        // $hash = substr(md5($name . $prenom . $randomString), 0, 8); // Use a part of md5 hash
        $date = new \DateTime($birthdate);
        return $initials . $date->format('Ymd'); // Custom unique key
    }

    public function uploadFile($file, $numinscritanem)
    {

        $fileExt = $file->getClientOriginalExtension();

        $filename = $numinscritanem . '-' . date('Ymd') . '.' . $fileExt;

        try {
            $filePath =  Storage::disk('remote')->putFile($filename, file_get_contents($file));
            return response()->json($filePath);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Remote server is not available: ' . $e->getMessage());
        }
        // Fall back to local storage/app/uploaded_files
        $filePath = $file->storeAs('uploaded_files', $filename, 'local');
        // $filePath = Storage::disk('local')->putFile($filename, file_get_contents($file));
        return response()->json($filePath);
    }

    public function generateUniqueId(string $nom, string $prenom, \DateTime $daten): string
    {
        // Generate the unique ID
        $checkId = strtoupper(
            soundex(preg_replace('/[aeiouhwy]/i', '', $nom)) .
                soundex(preg_replace('/[aeiouhwy]/i', '', $prenom)) .
                $daten->format('dmY')
        );

        // dd($checkId);
        // Check if the generated ID already exists
        if (AdUser::where('unique_id', $checkId)->exists()) {
            throw new \Exception("Generated h---------h unique_id {$checkId} already exists in ad_user table");
        }

        return $checkId;
    }
}
