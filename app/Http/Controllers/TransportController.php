<?php

namespace App\Http\Controllers;
use App\Models\Transport;
use App\Models\Employe;
use Illuminate\Http\Request;

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



}
