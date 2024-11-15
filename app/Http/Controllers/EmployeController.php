<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employe;
use App\Models\Transport;
use App\Models\Traject;

class EmployeController extends Controller
{
    public function dashboard() {
        $employes = Employe::where('is_deleted', 0)->count();
        $transports = Transport::where('is_deleted', 0)->count();
        $non_mo = Employe::where('moto', "0")->where('is_deleted', 0)->count();
        $mo = Employe::where('moto', "1")->where('is_deleted', 0)->count();
        $transports_table = Transport::where('is_deleted', 0)->get();

        $stationsCount = Employe::selectRaw('count(*) as total')
        ->where('is_deleted', 0)
        ->whereNotNull('latitude')
        ->whereNotNull('longitude')
        ->groupBy('latitude', 'longitude')
        ->get()
        ->count(); // Compte le nombre de groupes distincts

        return view("admin.dashboard", compact("employes", "transports","transports_table","non_mo","mo","stationsCount"));
    }

    public function lister(Request $request)
    {
        $name = $request->input('name');
        $mat = $request->input('mat');
        $mode = $request->input('motorise');

        $query = Employe::with('transports')->where('is_deleted', 0);
        if ($name) {
            $query->where('name', 'LIKE', "%$name%");
        }

        if ($mat) {
            $query->where('Mat', '=', "$mat");
        }

        if ($mode) {
            $query->where('moto', '=', "$mode");
        }

        $total_employees = $query->count();

        $employees = $query->orderBy('Mat', 'asc')->paginate(20);

        if ($total_employees === 0) {
            session()->flash('attention', 'Aucun employé avec ces critères');
        }

        return view("admin.employes.employes", compact("employees", "total_employees"));
    }

    public function addForm(){
        return view("admin.employes.addE");
    }

    public function addE(Request $request)
    {
        $employe = new Employe();
        $employe->Mat = $request->mat;
        $employe->name = $request->name;
        $employe->latitude = $request->latitude;
        $employe->longitude = $request->longitude;
        $employe->is_deleted = 0;
        $employe->moto = $request->motorise;
        $employe->save();

        return redirect("admin/employes/lister")->with('success', 'Employé ajouté avec succès.');
    }

    public function editer($id){
        $employe=Employe::find($id);
        return view('admin.employes.editer', compact('employe'));
    }

    public function update(Request $request, $id)
        {
            $employe = Employe::find($id);

            if (!$employe) {
                return redirect()->back()->with('error', 'Employé(e) non trouvé');
            }

            $employe->Mat = $request->input('mat');
            $employe->name = $request->input('name');
            $employe->latitude = $request->input('latitude');
            $employe->longitude = $request->input('longitude');
            $employe->moto = $request->input('motorise');

            $employe->save();
            return redirect('admin/employes/lister')->with('success', "L'employé a été mis à jour avec succès");
        }

        public function delete($id){
            $employe=Employe::find($id);

            if (!$employe) {
                return redirect()->back()->with('error', 'Employé non trouvé');
            }
            $employe->is_deleted="1";
            $employe->save();
            return redirect('admin/employes/lister')->with('success', "L'employé a été supprimé avec succès");
        }

        public function showLocation($id)
        {
            $employe = Employe::findOrFail($id);
            return view('admin.employes.location', compact('employe'));
        }


        public function getClosestTransportZoneAndDistancesByEmployeId($id)
        {
            $employe = Employe::find($id);

            if (!$employe) {
                return "Employé non trouvé";
            }

            $latitude = $employe->latitude;
            $longitude = $employe->longitude;

            $zones = Transport::where("is_deleted","0")->get();
            $zoneAppartenance = null;
            $minDistance = PHP_INT_MAX;
            $distances = [];

            $haversine = function ($lat1, $lon1, $lat2, $lon2) {
                $earthRadius = 6371;

                $dLat = deg2rad($lat2 - $lat1);
                $dLon = deg2rad($lon2 - $lon1);

                $a = sin($dLat / 2) * sin($dLat / 2) +
                    cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
                    sin($dLon / 2) * sin($dLon / 2);
                $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

                return $earthRadius * $c;
            };

            foreach ($zones as $zone) {
                $distance = $haversine($latitude, $longitude, $zone->center_lat, $zone->center_lng);

                $distances[] = [
                    'zone' => $zone->name,
                    'distance' => $distance
                ];

                if ($minDistance === null || $distance < $minDistance) {
                    $minDistance = $distance;
                    $zoneAppartenance = $zone->name;
                }

            }

            if ($zoneAppartenance === null) {
                $zoneAppartenance = "Aucun transport";
                $minDistance = null;
            }

            return view('admin.employes.zones', compact('employe','zoneAppartenance', 'minDistance', 'distances'));
        }

        public function affecter($id) {
            $employe = Employe::find($id);
            $transports = Transport::where("is_deleted", "0")->get();

            // Vérifie si la latitude ou la longitude de l'employé est manquante
            if (is_null($employe->latitude) || is_null($employe->longitude)) {
                session()->flash('error', 'L\'employé n\'a pas de coordonnées géographiques (latitude et longitude). Il ne peut pas être affecté à un transport.');
                return redirect()->back(); // Redirige vers la page précédente avec l'alerte
            }

            return view("admin.employes.affecter", compact("employe", "transports"));
        }


        public function affecterSave(Request $request)
        {
            // Get the employee's latitude and longitude from the request
            $employeeId = $request->input('employee_id');
            $transportId = $request->input('transport_id');

            // Find the employee by ID to get their latitude and longitude
            $employee = Employe::find($employeeId);

            if ($employee) {
                $latitude = $employee->latitude;
                $longitude = $employee->longitude;

                // Retrieve all employees with the same latitude and longitude
                $employees = Employe::where('latitude', $latitude)
                                     ->where('longitude', $longitude)
                                     ->get();

                foreach ($employees as $emp) {
                    // Find or create a Traject record for each employee
                    $affectation = Traject::where('employee_id', $emp->id)->first();

                    if ($affectation) {
                        // Update existing affectation
                        $affectation->transport_id = $transportId;
                        $affectation->is_deleted = 0;
                    } else {
                        // Create a new affectation
                        $affectation = new Traject();
                        $affectation->employee_id = $emp->id;
                        $affectation->transport_id = $transportId;
                        $affectation->is_deleted = 0;
                    }

                    // Save the affectation
                    $affectation->save();
                }

                return redirect('admin/employes/lister')->with('success', 'Les employés ayant les mêmes coordonnées ont été affectés au transport avec succès.');
            } else {
                return redirect('admin/employes/lister')->with('error', 'Employé introuvable.');
            }
        }

        public function afficherPoints(){
            $employes = Employe::where('is_deleted', 0)->get();
            return view('admin.employes.points', compact('employes'));
        }


}
