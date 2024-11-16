<?php

namespace App\Http\Controllers;
use App\Models\Transport;
use App\Models\Employe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class TransportController extends Controller
{
        public function lister(Request $request)
        {
            $name = $request->input('name');
            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');

            // Remplacer Employe par Transport
            $query = Transport::where('is_deleted', 0);

            if ($name) {
                $query->where('name', 'LIKE', "%$name%");
            }

            if ($latitude) {
                $query->where('center_lat', 'LIKE', "%$latitude%");
            }

            if ($longitude) {
                $query->where('center_lng', 'LIKE', "%$longitude%");
            }

            $total_transports = $query->count(); // Modifier pour le total des transports

            $transports = $query->orderBy('id', 'asc')->paginate(10); // Modifier pour les transports

            if ($total_transports === 0) {
                session()->flash('attention', 'Aucun transport avec ces critères');
            }

            return view("admin.transports.transports", compact("transports", "total_transports")); // Modifier la vue
        }

        public function addForm()
        {
            return view("admin.transports.addTransport"); // Modifier la vue d'ajout
        }

        public function addT(Request $request)
        {
            $transport = new Transport(); // Modifier pour utiliser Transport
            $transport->name = $request->name;
            $transport->center_lat = $request->center_lat;
            $transport->center_lng = $request->center_lng;
            $transport->capacity = $request->capacity;
            $transport->is_deleted = 0;
            $transport->save();

            return redirect("admin/transports/lister")->with('success', 'Transport ajouté avec succès.'); // Modifier la redirection
        }

        public function showLocation($id)
        {
            $transport = Transport::findOrFail($id);
            return view('admin.transports.location', compact('transport'));
        }

        public function editer($id)
        {
            $transport = Transport::find($id);

            if (!$transport) {
                return redirect()->back()->with('error', 'Transport non trouvé');
            }
            return view('admin.transports.editer', compact('transport'));
        }

        public function modifier(Request $request, $id)
        {
            $transport = Transport::find($id);

            if (!$transport) {
                return redirect()->back()->with('error', 'Transport non trouvé');
            }

            $transport->name = $request->input('name');
            $transport->center_lat = $request->input('center_lat');
            $transport->center_lng = $request->input('center_lng');
            $transport->capacity = $request->input('capacity');

            $transport->save();
            return redirect('admin/transports/lister')->with('success', 'Transport mis à jour avec succès');
        }

        public function supprimer($id){
            $transport=Transport::find($id);

            if (!$transport) {
                return redirect()->back()->with('error', 'Transport non trouvé');
            }
            $transport->is_deleted="1";
            $transport->save();
            return redirect('admin/transports/lister')->with('success', 'Transport supprimé avec succès');
        }

        public function showAllZones()
        {
            $transports = Transport::where('is_deleted', 0)->get();
            return view('admin.transports.allZones', compact('transports'));
        }

        public function showTraject($id)
        {
            $transport = Transport::find($id);
            if (!$transport) {
                return redirect()->back()->with('error', 'Transport non trouvé');
            }

            $latitude = $transport->center_lat;
            $longitude = $transport->center_lng;

           // Récupérer les employés affectés au transport, groupés par latitude et longitude, avec les noms
            $employes = Employe::join('trajects', 'employees.id', '=', 'trajects.employee_id')
            ->where('trajects.transport_id', $id)
            ->where('employees.is_deleted', 0)
            ->selectRaw('employees.latitude, employees.longitude, MIN(employees.name) as name')
            ->groupBy('employees.latitude', 'employees.longitude')
            ->get();

            $nonAffectes = Employe::leftJoin('trajects', function ($join) use ($id) {
                $join->on('employees.id', '=', 'trajects.employee_id')
                     ->where('trajects.transport_id', $id);
            })
            ->whereNull('trajects.employee_id')
            ->where('employees.is_deleted', 0)
            ->whereNotNull('employees.latitude')
            ->whereNotNull('employees.longitude')
            ->select('employees.*')
            ->get();

            // Initialiser un tableau pour stocker les distances
            $distances = [];

            // Fonction de calcul de la distance Haversine
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

            foreach ($employes as $employe) {
                $distance = $haversine($latitude, $longitude, $employe->latitude, $employe->longitude);
                $distances[] = [
                    'latitude' => $employe->latitude,
                    'longitude' => $employe->longitude,
                    'distance' => $distance,
                    'name' => $employe->name  // Use 'names' instead of 'name'
                ];
            }


            usort($distances, function ($a, $b) {
                return $a['distance'] <=> $b['distance'];
            });

            return view('admin.transports.traject', compact('transport', 'distances','nonAffectes'));
        }

        public function listTous()
        {
            $transports = Transport::where('is_deleted', 0)->get();

            return view('admin.transports.select', compact('transports'));
        }

        public function listEmploye(Request $request)
        {
            $transportId = $request->input('transport_id');
            if (!$transportId) {
                return redirect()->back()->with('error', 'Transport non sélectionné.');
            }

            $transport = Transport::where('id', $transportId)->where('is_deleted', 0)->first();

            if (!$transport) {
                return redirect()->back()->with('error', 'Transport non trouvé ou supprimé.');
            }

            $employees = $transport->employees()->orderBy('Mat', 'asc')->get();
            $totalEmployees = $employees->count();

            return view('admin.transports.listEmploye', compact('transport', 'employees', 'totalEmployees'));
        }

        public function showAllTrajects()
        {
            $transports = Transport::where("is_deleted", 0)->get();

            $transportsData = [];
            foreach ($transports as $transport) {
                $latitude = $transport->center_lat;
                $longitude = $transport->center_lng;

                $employes = Employe::join('trajects', 'employees.id', '=', 'trajects.employee_id')
                    ->where('trajects.transport_id', $transport->id)
                    ->where('employees.is_deleted', 0)
                    ->selectRaw('employees.latitude, employees.longitude, MIN(employees.name) as name')
                    ->groupBy('employees.latitude', 'employees.longitude')
                    ->get();

                // Only process if employees exist
                if ($employes->isNotEmpty()) {
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

                    foreach ($employes as $employe) {
                        $distance = $haversine($latitude, $longitude, $employe->latitude, $employe->longitude);
                        $distances[] = [
                            'latitude' => $employe->latitude,
                            'longitude' => $employe->longitude,
                            'distance' => $distance,
                            'name' => $employe->name
                        ];
                    }

                    usort($distances, fn($a, $b) => $a['distance'] <=> $b['distance']);

                    $transportsData[] = [
                        'transport' => $transport,
                        'distances' => $distances
                    ];
                } else {
                    // If no employees, add only the transport data without distances
                    $transportsData[] = [
                        'transport' => $transport,
                        'distances' => []
                    ];
                }
            }

            $nonAffectes = Employe::leftJoin('trajects', 'employees.id', '=', 'trajects.employee_id')
                ->whereNull('trajects.employee_id')
                ->where('employees.is_deleted', 0)
                ->whereNotNull('employees.latitude')
                ->whereNotNull('employees.longitude')
                ->select('employees.*')
                ->get();

            return view('admin.transports.allTrajects', compact('transportsData', 'nonAffectes'));
        }


        public function listMesure() {
            // This will store data for each transport and its associated distances
            $transportsData = [];

            // Get all transport stations, replace with actual transport data
            $transports = Transport::where("is_deleted",0)->get();

            // Haversine function to calculate the distance between two points
            $haversine = function($lat1, $lon1, $lat2, $lon2) {
                $earthRadius = 6371; // Radius of the Earth in km

                // Convert degrees to radians
                $dLat = deg2rad($lat2 - $lat1);
                $dLon = deg2rad($lon2 - $lon1);

                // Haversine formula
                $a = sin($dLat / 2) * sin($dLat / 2) +
                    cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
                    sin($dLon / 2) * sin($dLon / 2);

                $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

                // Return distance in kilometers
                return $earthRadius * $c;
            };

            // Fixed end location
            $endLatitude = 33.989766;
            $endLongitude = -5.075888;

            // Loop through each transport station
            foreach ($transports as $transport) {
                // Get the transport's latitude and longitude
                $transportLatitude = $transport->center_lat;
                $transportLongitude = $transport->center_lng;

                // Get all employees associated with the transport
                $employees = DB::table('employees')
                    ->join('trajects', 'employees.id', '=', 'trajects.employee_id')
                    ->where('trajects.transport_id', $transport->id)
                    ->where('employees.is_deleted', 0)
                    ->selectRaw('employees.latitude, employees.longitude, MIN(employees.name) as name')
                    ->groupBy('employees.latitude', 'employees.longitude')
                    ->get();

                // Calculate the distance from transport to each employee and store the results
                $distances = [];
                $totalTrajectoryDistance = 0;

                // First, calculate the distance from the transport to each employee
                foreach ($employees as $employee) {
                    $distance = $haversine($transportLatitude, $transportLongitude, $employee->latitude, $employee->longitude);

                    $distances[] = [
                        'latitude' => $employee->latitude,
                        'longitude' => $employee->longitude,
                        'distance' => $distance,
                        'name' => $employee->name
                    ];
                }

                // Sort the employees by the distance from the transport
                usort($distances, fn($a, $b) => $a['distance'] <=> $b['distance']);

                // Starting point is the transport station, so we initialize `lastEmployee` with transport's location
                $lastEmployee = ['latitude' => $transportLatitude, 'longitude' => $transportLongitude];

                // Calculate the total trajectory distance between the transport and each employee, and then between consecutive employees
                foreach ($distances as $employee) {
                    // Get the distance between the last point (either transport or last employee) and the current employee
                    $employeeDistance = $haversine($lastEmployee['latitude'], $lastEmployee['longitude'], $employee['latitude'], $employee['longitude']);

                    // Add this distance to the total trajectory distance
                    $totalTrajectoryDistance += $employeeDistance;

                    // Update the last employee's coordinates
                    $lastEmployee = $employee;
                }

                // Now calculate the distance from the last employee to the fixed end location
                $endPointDistance = $haversine($lastEmployee['latitude'], $lastEmployee['longitude'], $endLatitude, $endLongitude);
                $totalTrajectoryDistance += $endPointDistance;

                // Add the transport and the computed distances to the result array
                $transportsData[] = [
                    'transport' => $transport,
                    'distances' => $distances,
                    'total_trajectory_distance' => $totalTrajectoryDistance // Store the total trajectory distance
                ];
            }

            // Pass data to the view
            return view("admin.transports.mesure", compact('transportsData'));
        }

        public function transportsStations(){
            $transports=Transport::where("is_deleted",0)->get();
           return view('admin.transports.stationsTransport',compact("transports"));
        }

        public function employesStations(Request $request){
            $transport_id=$request->transport_id;
            $transport = Transport::where('id', $transport_id)->where('is_deleted', 0)->first();

             // Get the transport's latitude and longitude
             $transportLatitude = $transport->center_lat;
             $transportLongitude = $transport->center_lng;

             // Get all employees associated with the transport
             $employees = DB::table('employees')
                 ->join('trajects', 'employees.id', '=', 'trajects.employee_id')
                 ->where('trajects.transport_id', $transport->id)
                 ->where('employees.is_deleted', 0)
                 ->selectRaw('employees.latitude, employees.longitude, MIN(employees.name) as name')
                 ->groupBy('employees.latitude', 'employees.longitude')
                 ->get();

             // Calculate the distance from transport to each employee and store the results
             $distances = [];

              // Haversine function to calculate the distance between two points
            $haversine = function($lat1, $lon1, $lat2, $lon2) {
                $earthRadius = 6371; // Radius of the Earth in km

                // Convert degrees to radians
                $dLat = deg2rad($lat2 - $lat1);
                $dLon = deg2rad($lon2 - $lon1);

                // Haversine formula
                $a = sin($dLat / 2) * sin($dLat / 2) +
                    cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
                    sin($dLon / 2) * sin($dLon / 2);

                $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

                // Return distance in kilometers
                return $earthRadius * $c;
            };

             foreach ($employees as $employee) {
                 $distance = $haversine($transportLatitude, $transportLongitude, $employee->latitude, $employee->longitude);

                 $distances[] = [
                     'latitude' => $employee->latitude,
                     'longitude' => $employee->longitude,
                     'distance' => $distance
                 ];
             }

             // Sort the employees by the distance from the transport
             usort($distances, fn($a, $b) => $a['distance'] <=> $b['distance']);

             // Add the transport and the computed distances to the result array
             $transportData = [
                'transport' => $transport,
                'distances' => $distances
            ];
           return view('admin.transports.stationsEmployes',compact("transportData"));
        }

        public function getEmployes($latitude, $longitude,$transportId) {
            $employees = DB::table('employees')
                ->where('latitude', $latitude)
                ->where('longitude', $longitude)
                ->where('is_deleted', 0)
                ->get();

            $count = $employees->count();

            return view('admin.transports.employesStationList', compact('employees','count','transportId'));
        }
}
